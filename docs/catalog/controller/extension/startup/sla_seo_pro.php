<?php
class ControllerExtensionStartupSlaSeoPro extends Controller {
	private $cache_data = null;
	private $language_code = array();
	private $language_id = array();

	public function __construct($registry) {
		parent::__construct($registry);
		if ($this->config->get('config_seo_url') && $this->config->get('sla_seo_pro_status')) {

			$query = $this->db->query("SELECT language_id, code FROM " . DB_PREFIX . "language WHERE status = '1'");
			foreach ($query->rows as $result) {
				$this->language_id[$result['code']] = $result['language_id']?$result['language_id']:$this->config->get['config_language_id'];
				$this->language_code[$result['language_id']] = $result['code'];
			}
			
			$cache_key = 'seo_pro-'. $this->config->get('config_store_id');
			$this->cache_data = $this->cache->get($cache_key);
			if (!$this->cache_data) {

				$query = $this->db->query("SELECT `keyword`, `query`, `seopath`, `language_id` FROM " . DB_PREFIX . "seo_url WHERE store_id = '" . $this->config->get('config_store_id') . "'");
				$this->cache_data = array();
				foreach ($query->rows as $row) {
	
					if (isset($this->cache_data['keywords'][$row['language_id']][$row['keyword']])){
						continue;
					}
					$this->cache_data['keywords'][$row['keyword']] = array(
						'language_id' => $row['language_id']?$row['language_id']:$this->config->get['config_language_id'],
						'query'       => $row['query'],
					);
					$language_id = $row['language_id']?$row['language_id']:$this->config->get['config_language_id'];

					$this->cache_data['queries'][$row['query']][$language_id] = $row['keyword'];

					if ($row['seopath']) 
						$this->cache_data['seopath'][$row['query']] = $row['seopath'];
				}
				$this->cache->set($cache_key, $this->cache_data);
			}

		}
	}

	public function index() {

		if ($this->config->get('config_seo_url') && $this->config->get('sla_seo_pro_status')) {
			$this->url->addRewrite($this);
		} else {
			return;
		}
		$prefix = '';

		if (!isset($this->request->get['_route_'])) {
			$this->validate();
		} else {
			$route_ = $route = $this->request->get['_route_'];
			unset($this->request->get['_route_']);
			$parts = explode('/', trim($route, '/'));
			$sla_seo_pro_postfix = trim($this->config->get('sla_seo_pro_postfix'));
			if ($sla_seo_pro_postfix) {
				$last_part = array_pop($parts);

				$last_parts = explode('.', $last_part);
				$end_last_parts = end($last_parts);
				if ($end_last_parts && $end_last_parts == $sla_seo_pro_postfix) {
					array_pop($last_parts);
					array_push($parts, implode('.',$last_parts));
				} else {
					array_push($parts, $last_part);
				}
			}

			$rows = array();
			if ($this->config->get('sla_seo_pro_prefix_product')) {
				if ($parts[0] == $this->config->get('sla_seo_pro_prefix_product')) {
					unset($parts[0]);
					$prefix = 'product/product';
				}
			} 
			if ($this->config->get('sla_seo_pro_prefix_category') && !$prefix) {
				if ($parts[0] == $this->config->get('sla_seo_pro_prefix_category')) {
					unset($parts[0]);
					$prefix = 'product/category';
				}
			}
			if ($this->config->get('sla_seo_pro_prefix_brand') && !$prefix) {
				if ($parts[0] == $this->config->get('sla_seo_pro_prefix_brand')) {
					unset($parts[0]);
					$prefix = 'product/manufacturer';
				}
			}
			
			foreach ($parts as $keyword) {
				if (isset($this->cache_data['keywords'][$keyword])) {
					$rows[] = array(
						'keyword' => $keyword, 
						'query' => $this->cache_data['keywords'][$keyword]);
				}
			}

			if (isset($this->cache_data['keywords'][$route])){
				$keyword = $route;
				$parts = array($keyword);
			
				$rows = array(array(
					'keyword' => $keyword, 
					'query' => $this->cache_data['keywords'][$keyword])
				);
			}
			$language_id = $this->language_id[$this->session->data['language']];
			if (count($rows) == sizeof($parts)) {
			
				$queries = array();
				$code = $this->session->data['language'];
				$i=0;
				foreach ($rows as $row) {
					if ($i == 0) {
						$lang_code = $this->language_code[$row['query']['language_id']];
						$language_id = $this->language_id[$lang_code];
					}
					if ($lang_code == $this->language_code[$row['query']['language_id']]) {
						$queries[$row['keyword']] = $row['query']['query'];
					} else {
						$this->request->get['route'] = 'error/not_found';
						break;
					};
					$i++;
				}
				
				$this->session->data['language'] = $lang_code;
				$this->config->set('config_language_id', $language_id);
				
				reset($parts);
				foreach ($parts as $part) {
					if(!isset($queries[$part])) return false;
					$url = explode('=', $queries[$part], 2);

					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					} elseif (count($url) > 1) {
						$this->request->get[$url[0]] = $url[1];
					}
				}
			} else {
				$this->request->get['route'] = 'error/not_found';
			}

			if (isset($this->request->get['product_id'])) {
				$this->request->get['route'] = 'product/product';
				if (!isset($this->request->get['path'])) {
					$path = $this->getPathByProduct($this->request->get['product_id']);
					if ($path) $this->request->get['path'] = $path;
				}
			} elseif (isset($this->request->get['path'])) {
				if ($this->config->get('sla_seo_pro_short_url')) {
					$category = explode('_', $this->request->get['path']);
					$category = end($category);
					$this->request->get['path'] = $this->getPathByCategory($category);
				}
				$this->request->get['route'] = 'product/category';
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$this->request->get['route'] = 'product/manufacturer/info';
			} elseif (isset($this->request->get['information_id'])) {
				$this->request->get['route'] = 'information/information';
			} elseif (isset($this->cache_data['queries'][$language_id][$route_])) {
					header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
					$this->response->redirect($this->cache_data['queries'][$language_id][$route_]);
			} else {
				if (isset($parts[0]) && isset($queries[$parts[0]])) {
					$this->request->get['route'] = $queries[$parts[0]];
				} else {
					if ($prefix) {
						$this->request->get['route'] = 'error/not_found';
					}
				}
			}

			$this->validate();

			if (isset($this->request->get['route'])) {
				return new Action($this->request->get['route']);
			}
		}
	}

	public function rewrite($link) {
		if (!$this->config->get('config_seo_url') && !$this->config->get('sla_seo_pro_status')) return $link;
		$prefix = '';
		$seo_url = '';

		$component = parse_url(str_replace('&amp;', '&', $link));
		$data = array();
		parse_str($component['query'], $data);
		$route = $data['route'];
		unset($data['route']);

		switch ($route) {
			case 'product/product':
				if (isset($data['product_id'])) {
					$tmp = $data;
					$data = array();
					if ($this->config->get('sla_seo_pro_include_path')) {
						
						$data['path'] = $this->getPathByProduct($tmp['product_id']);
						if (!$data['path']) return $link;
					} else {
						unset ($data['path']);
					}
					$data['product_id'] = $tmp['product_id'];
					$sla_seo_pro_utm = explode('\n',$this->config->get('sla_seo_pro_utm'));
					foreach ($sla_seo_pro_utm as $utm) {
						if (isset($tmp[$utm])) {
							$data[$utm] = $tmp[$utm];
						}
					}
					
					$prefix = trim($this->config->get('sla_seo_pro_prefix_product'));
				}
				break;

			case 'product/category':
				if (isset($data['path'])) {
					$category = explode('_', $data['path']);
					$category = end($category);
					$data['path'] = $this->getPathByCategory($category);
					
					if (!$data['path']) return $link;

					if ($this->config->get('sla_seo_pro_short_url')) {	
						$dpath = explode('_',$data['path']);
						$data['path'] = end($dpath);
					}
					$prefix = $this->config->get('sla_seo_pro_prefix_category');
				}
				break;

			case 'product/product/review':
			case 'information/information/agree':
				return $link;
				break;

			default:
				break;
		}

		if ($component['scheme'] == 'https') {
			$link = $this->config->get('config_ssl');
		} else {
			$link = $this->config->get('config_url');
		}

		$link .= 'index.php?route=' . $route;

		if (count($data)) {
			$link .= '&amp;' . urldecode(http_build_query($data, '', '&amp;'));
		}

		$queries = array();
		if(!in_array($route, array('product/search'))) {
			foreach($data as $key => $value) {
				switch($key) {
					case 'product_id':
					case 'manufacturer_id':
					case 'category_id':
					case 'information_id':
						$queries[] = $key . '=' . $value;
						unset($data[$key]);
						$postfix = 1;
						break;

					case 'path':
						$categories = explode('_', $value);
						foreach($categories as $category) {
							$queries[] = 'category_id=' . $category;
						}
						unset($data[$key]);
						break;

					default:
						break;
				}
			}
		}

		if(empty($queries)) {
			$queries[] = $route;
		}

		$rows = array();
		foreach($queries as $query) {
			if(isset($this->cache_data['queries'][$query][$this->language_id[$this->session->data['language']]])) {
				$rows[] = array('query' => $query, 'keyword' => $this->cache_data['queries'][$query][$this->language_id[$this->session->data['language']]]);
			}
		}
		if(count($rows) == count($queries)) {
			$aliases = array();
			foreach($rows as $row) {
				$aliases[$row['query']] = $row['keyword'];
			}
			
			foreach($queries as $query) {
				$seo_url .= '/' . rawurlencode($aliases[$query]);
			}
		}

		if ($seo_url == '') return $link;
		
		$seo_url = trim($prefix, '/') . $seo_url;
		$seo_url = trim($seo_url, '/');

		if ($component['scheme'] == 'https') {
			$seo_url = $this->config->get('config_ssl') . $seo_url;
		} else {
			$seo_url = $this->config->get('config_url') . $seo_url;
		}

		if (isset($postfix)) {
			$sla_seo_pro_postfix = trim($this->config->get('sla_seo_pro_postfix'));
			if ($sla_seo_pro_postfix) {
				$seo_url .= '.' . $sla_seo_pro_postfix;
			} else {
				if 	($this->config->get('sla_seo_pro_postfix_slash_product')) {
					$seo_url .= '/';
				}
			}
		} else {
			if 	($this->config->get('sla_seo_pro_postfix_slash')) {
				$seo_url .= '/';
			}
		}

		if(substr($seo_url, -2) == '//') {
			$seo_url = substr($seo_url, 0, -1);
		}

		if (count($data)) {
			$seo_url .= '?' . urldecode(http_build_query($data, '', '&amp;'));
		}

		return $seo_url;
	}

	private function getPathByProduct($product_id) {
		$product_id = (int)$product_id;
		if ($product_id < 1) return false;
		$query_ = 'product_id=' . $product_id;

		if (isset($this->cache_data['seopath'][$query_]) && $this->cache_data['seopath'][$query_]) 
			return $this->cache_data['seopath'][$query_];
		$query = $this->db->query("	SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' ORDER BY main_category DESC LIMIT 1");
		if ($query->num_rows) {
			$path_product_id = $this->getPathByCategory($query->row['category_id']);
		} else {
			return false;
		}

		if ($path_product_id) {
			$query = $this->db->query("UPDATE " . DB_PREFIX . "seo_url SET seopath = '" . $this->db->escape($path_product_id) . "'	WHERE query = 'product_id=" .(int)$product_id . "'");
		}
		$this->cache_data['seopath'][$query_] = $path_product_id;

		return $path_product_id;
	}

	private function getPathByCategory($category_id) {
		$category_id = (int)$category_id;
		if ($category_id < 1) return false;
		$query_ = 'category_id=' . $category_id;
		if (isset($this->cache_data['seopath'][$query_]) && $this->cache_data['seopath'][$query_]) 
			return $this->cache_data['seopath'][$query_];
		$sql = "SELECT GROUP_CONCAT(c1.category_id ORDER BY level SEPARATOR '_') path 
		FROM " . DB_PREFIX . "category_path cp 
		LEFT JOIN oc_category c1 ON (cp.path_id = c1.category_id) 
		WHERE cp.category_id = " . (int)$category_id . " 
		GROUP BY cp.category_id";			
		$query = $this->db->query($sql);

		$path_category_id = $query->row['path'] ? $query->row['path'] : false;
		if ($path_category_id) {
			$query = $this->db->query("
			UPDATE " . DB_PREFIX . "seo_url 
			SET seopath = '" . $this->db->escape($path_category_id) . "' 
			WHERE query = 'category_id=" . (int)$category_id . "'");
		}
		$this->cache_data['seopath'][$query_] = $path_category_id;
		
		return $path_category_id;
	}

	private function validate() {

		if (isset($this->request->get['route']) && $this->request->get['route'] == 'error/not_found') {
			return;
		}
		if (ltrim($this->request->server['REQUEST_URI'], '/') == 'sitemap.xml') {
			$this->request->get['route'] = 'extension/feed/google_sitemap_fast';
			return;
		}

		if(empty($this->request->get['route'])) {
			$this->request->get['route'] = 'common/home';
		}

		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return;
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$config_ssl = substr($this->config->get('config_ssl'), 0, $this->strpos_offset('/', $this->config->get('config_ssl'), 3) + 1);
			$url = str_replace('&amp;', '&', $config_ssl . ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), true));
		} else {
			$config_url = substr($this->config->get('config_url'), 0, $this->strpos_offset('/', $this->config->get('config_url'), 3) + 1);
			$url = str_replace('&amp;', '&', $config_url . ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), false));
		}

		if (rawurldecode($url) != rawurldecode($seo)) {
			header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');

			$this->response->redirect($seo);
		}
	}

	private function strpos_offset($needle, $haystack, $occurrence) {
		// explode the haystack
		$arr = explode($needle, $haystack);
		// check the needle is not out of bounds
		switch($occurrence) {
			case $occurrence == 0:
				return false;
			case $occurrence > max(array_keys($arr)):
				return false;
			default:
				return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
		}
	}

	private function getQueryString($exclude = array()) {
		if (!is_array($exclude)) {
			$exclude = array();
			}

		return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
		}
	}
?>
