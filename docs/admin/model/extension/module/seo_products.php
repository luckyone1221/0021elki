<?php
class ModelExtensionModuleSeoProducts extends Model {

    public function getProducts($limit_tol,$limit_ig) {
        $vissza = array();
        $this->load->model('catalog/product');
        $this->load->model('setting/store');
        $this->load->model('localisation/language');

        $stores     = $this->getStores();
        $languages  = $this->getLanguages();

        $sql = " SELECT p.product_id, p.model, m.name AS manufacturer, p2s.store_id  FROM " . DB_PREFIX . "product p";
        $sql .= " LEFT JOIN ".DB_PREFIX."manufacturer m ON (m.manufacturer_id=p.manufacturer_id) ";
        $sql .= " LEFT JOIN ".DB_PREFIX."product_to_store p2s ON (p2s.product_id=p.product_id) ";
        if ($limit_tol != 1 || $limit_ig !=1) {
            $sql .= " LIMIT " . $limit_tol . "," . $limit_ig;
        }
        $query = $this->db->query($sql);

        if ($query->row) {
            foreach($query->rows as $row) {
                $sql = " SELECT language_id, name FROM ".DB_PREFIX."product_description WHERE product_id='".$row['product_id']."'";
                $query_desc     = $this->db->query($sql);
                if ($query_desc->row) {
                    foreach($query_desc->rows as $desc_row) {
                        $row['name'][$desc_row['language_id']]    = $desc_row['name'];
                    }


                    $row['seo']     = $this->model_catalog_product->getProductSeoUrls($row['product_id']);
                    if (!$row['seo']) {
                        if ($stores) {
                            foreach($stores as $store) {
                                $store_id = $store['store_id'];
                                foreach($languages as $language) {
                                    $language_id = $language['language_id'];
                                    $row['seo'][$store_id][$language_id] = '';
                                }
                            }
                        } else {
                            $store_id = 0;
                            foreach($languages as $language) {
                                $language_id = $language['language_id'];
                                $row['seo'][$store_id][$language_id] = '';
                            }
                        }
                    }

                    $vissza[] = $row;

                }

            }
        }

        return $vissza;
    }

    public function getTotalProducts() {

        $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p ";
        $query = $this->db->query($sql);

        return $query->row['total'];

    }

    public function getTotalProductsNotSeo($products_total) {

        $this->load->model('setting/store');
        $this->load->model('localisation/language');

        $stores     = $this->getStores();
        $languages  = $this->getLanguages();

        $sql = "SELECT COUNT(DISTINCT seo_url_id) AS total FROM " . DB_PREFIX . "seo_url WHERE substr(query,1,10)='product_id' ";
        $query = $this->db->query($sql);

        $total = $query->row['total'];


        $teljes_lenne = $products_total * (count($stores)+1) * count($languages);
        $hianyzik = $teljes_lenne - $total;

        return $hianyzik;

    }

    public function validateSeoName($seo_name, $product_id, $store_id, $language_id) {
        $mehet = true;

        $sql = " SELECT * FROM ".DB_PREFIX."seo_url WHERE   keyword     =   '".$this->db->escape($seo_name)."'";
        $query = $this->db->query($sql);
        if ($query->row) {
            foreach($query->rows as $key=>$rows) {
                if ($rows['store_id'] == $store_id && $rows['language_id'] == $language_id && $rows['query'] == 'product_id='.$product_id) {
                    unset ($query->rows[$key]);
                }
            }
            if ($query->rows) {
                $mehet = false;
            }
        }
        return $mehet;
    }

    public function addNewSeo($keyword, $product_id, $store_id, $language_id) {
        $sql = "DELETE FROM " . DB_PREFIX . "seo_url
                    WHERE   store_id    =   '".$store_id."'
                    AND     language_id =   '".$language_id."'
                    AND     query       =   'product_id=" . (int)$product_id . "'";
        $siker = $this->db->query($sql);
        $sql = "INSERT INTO " . DB_PREFIX . "seo_url SET
                                store_id = '" . (int)$store_id . "',
                                language_id = '" . (int)$language_id . "',
                                query = 'product_id=" . (int)$product_id . "',
                                keyword = '" . $this->db->escape($keyword) . "'";
        $siker = $this->db->query($sql);

    }

    public function getStores() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

        return $query->rows;
    }

    public function getLanguages() {

        $language_data = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");

        foreach ($query->rows as $result) {
            $language_data[$result['code']] = array(
                'language_id' => $result['language_id'],
                'name'        => $result['name'],
                'code'        => $result['code'],
                'locale'      => $result['locale'],
                'image'       => $result['image'],
                'directory'   => $result['directory'],
                'sort_order'  => $result['sort_order'],
                'status'      => $result['status']
            );
        }

        return $language_data;
    }
}
?>