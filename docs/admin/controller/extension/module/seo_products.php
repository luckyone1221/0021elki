<?php
class ControllerExtensionModuleSeoProducts extends Controller {
    public function index() {
        $this->document->addScript('view/javascript/drag/jquery.vSort.min.js');

        $this->load->language('extension/module/seo_products');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('seo_products', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }


        $this->session->data['darab_termek']    = 0;
        $this->session->data['darab_seo']       = 0;
        $this->session->data['kezdet'] = array();

        $this->document->setTitle($this->language->get('heading_title_base'));
        $this->load->model('extension/module/seo_products');
        $this->document->addStyle('view/stylesheet/vrcs_stylesheet.css');

        $data['user_token']     = $this->session->data['user_token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }



        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/module/seo_products', '&user_token=' . $this->session->data['user_token'], 'SSL'),
            'separator' => ' :: '
        );

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $setting_info = $this->model_setting_setting->getSetting('seo_products');
        }
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $seo_elemek = array();

        if (!empty($setting_info['seo_products_set'])) {
            foreach ($setting_info['seo_products_set'] as $key=>$value) {
                $seo_elemek[] = array(
                    'name'          => ($key == 'product_name' ? $this->language->get('text_temek_neve') : ($key == 'manufacturer_id' ? $this->language->get('text_gyarto') : $this->language->get('text_cikkszam'))),
                    'id'            => ($key == 'product_name' ? '3' : ($key == 'manufacturer_id' ? '2' : '1')),
                    'product_input' => ($key == 'product_name' ? 'product_name' : ($key == 'manufacturer_id' ? 'manufacturer_id' : 'model')),
                    'checked'       => 1
                );
            }
        }

        if (!$this->letezikElem($seo_elemek,'3')) {
            $seo_elemek[] = array(
                'name'          => $this->language->get('text_temek_neve'),
                'id'            => '3',
                'product_input' => 'product_name',
                'checked'       => 0

            );
        }
        if (!$this->letezikElem($seo_elemek,'1')) {
            $seo_elemek[] = array(
                'name'          => $this->language->get('text_cikkszam'),
                'id'            => '1',
                'product_input' => 'model',
                'checked'       => 0
            );
        }
        if (!$this->letezikElem($seo_elemek,'2')) {
            $seo_elemek[] = array(
                'name'          => $this->language->get('text_gyarto'),
                'id'            => '2',
                'product_input' => 'manufacturer_id',
                'checked'       => 0
            );
        }

        $alap_karakterek ='é:e,É:E,á:a,Á:A,ó:o,Ó:O,ö:o,Ö:O,ő:o,Ő:O,ú:u,Ú:U,ű:u,Ű:U,ü:u,Ü:U,í:i,Í:I, :-';


        $data['seo_products_set']           = $seo_elemek;
        $data['seo_products_csak_hianyzo']  = isset($setting_info['seo_products_csak_hianyzo']) ? $setting_info['seo_products_csak_hianyzo'] : '';
        $data['seo_products_helyettesito']  = !empty($setting_info['seo_products_helyettesito']) ? $setting_info['seo_products_helyettesito'] : $alap_karakterek;
        $data['products_total']     = $this->model_extension_module_seo_products->getTotalProducts();

        $data['products_total_hianyzik'] = $this->model_extension_module_seo_products->getTotalProductsNotSeo($data['products_total']);

        $data['egyszerre'] =  $data['products_total'] < 500 ?  $data['products_total'] : 500;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/seo_products', $data));

    }

    public function validateSeo() {

        $this->load->language('extension/module/seo_products');

        if (!empty($_POST['seo_products_set'])) {
            $this->load->model('extension/module/seo_products');
            $limit_tol  = !empty($_GET['limit_tol']) ? $_GET['limit_tol'] : 0;
            $limit_ig   = !empty($_GET['limit_ig'])  ? $_GET['limit_ig'] : 200;
            $products   = $this->model_extension_module_seo_products->getProducts($limit_tol,$limit_ig);

            $hu = array();
            $en = array();
            if (!empty($_POST['seo_products_helyettesito'])) {
                $csere_parok = explode(',',$_POST['seo_products_helyettesito']);
                foreach ($csere_parok as $par) {
                    if (!empty($par)) {
                        $value = explode(':', $par);
                        if (count($value) == 2 && $value[0]) {
                            $hu[] = $value[0];
                            $en[] = $value[1];
                        }
                    }
                }
            } else {
                $hu= array('é','É','á','Á','ó','Ó','ö','Ö','ő','Ő','ú','Ú','ű','Ű','ü','Ü','í','Í',' ',':');
                $en= array('e','E','a','A','o','O','o','O','o','O','u','U','u','U','u','U','i','I','-','_');
            }


            $json = array();

            $darab_termek   = array();
            $darab_seo      = 0;
            $i = 0;
            foreach($products as $product) {
                $i ++;

                foreach($product['seo'] as $seo_store_key=>$product_seo_store){

                    foreach($product_seo_store as $seo_language_key=>$product_seo_language){
                        $seo_name = '';
                        if (empty($_POST['seo_products_csak_hianyzo']) || empty($product_seo_store[$seo_language_key]) ) {

                            foreach ($_POST['seo_products_set'] as $key => $seo_set) {
                                if ($key == 'manufacturer_id') {
                                    $seo_name .= $seo_name ? '-'.$product['manufacturer'] : $product['manufacturer'];
                                } elseif ($key == 'model') {
                                    $seo_name .= $seo_name ? '-'.$product['model'] : $product['model'];

                                } elseif ($key == 'product_name') {
                                    $seo_name .= $seo_name ? '-'.$product['name'][$seo_language_key] : $product['name'][$seo_language_key];
                                }
                            }
                            if ($seo_name) {
                                $mehet = true;
                                $seo_name = str_replace($hu, $en, $seo_name);

                                if (!$this->model_extension_module_seo_products->validateSeoName($seo_name, $product['product_id'], $seo_store_key, $seo_language_key)) {
                                    $seo_name .= '-' . $seo_language_key;
                                    if (!$this->model_extension_module_seo_products->validateSeoName($seo_name, $product['product_id'], $seo_store_key, $seo_language_key)) {
                                        $seo_name .= '-' . $seo_store_key;
                                        if (!$this->model_extension_module_seo_products->validateSeoName($seo_name, $product['product_id'], $seo_store_key, $seo_language_key)) {
                                            $json['error']['exists'] = $this->language->get('error_exists') . ' - ' . $product['product_id'] . ' - ' . $seo_name;
                                            $mehet = false;
                                        }
                                    }
                                }
                                if ($mehet) {
                                    $this->model_extension_module_seo_products->addNewSeo($seo_name, $product['product_id'], $product['store_id'], $seo_language_key);
                                    $darab_termek[$product['product_id']] = 1;
                                    $darab_seo++;

                                }
                            } else {
                                $json['error']['unknown_error'] = $this->language->get('error_unknown_error') . $product['product_id'];
                            }
                        }
                    }
                }
            }



        }

        $this->response->setOutput(json_encode($json));
    }

    private function letezikElem($tomb,$id) {
        $vissza = false;

        foreach ($tomb as $value) {
            if ($value['id'] == $id) {
                $vissza = true;
                break;
            }
        }
        return $vissza;
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/seo_products')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }



        return !$this->error;
    }

}
?>
