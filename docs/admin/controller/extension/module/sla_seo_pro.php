<?php
class ControllerExtensionModuleSlaSeoPro extends Controller {
	public function index() {
		$this->load->language('extension/module/sla_seo_pro');
		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->load->model('setting/setting');
			if (isset($this->request->post['sla_seo_pro_utm'])) {
				$sla_seo_pro_utm = $this->request->post['sla_seo_pro_utm'];
				$sla_seo_pro_utm = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->request->post['sla_seo_pro_utm'])));
				$new_sla_seo_pro_utm = array();
				foreach ($sla_seo_pro_utm as $utm) {
					$u = trim($utm);
					if ($u != '') {
						$new_sla_seo_pro_utm[] = $u;
					}
				}
				$this->request->post['sla_seo_pro_utm'] = implode('\n',$new_sla_seo_pro_utm);
			}

			$this->model_setting_setting->editSettingValue('config', 'config_seo_url', $this->request->post['config_seo_url']);
			
			unset($this->request->post['config_seo_url']);
			
			$this->model_setting_setting->editSetting('sla_seo_pro', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			$this->cache->delete('seo_pro');
			$this->response->redirect($this->url->link('extension/module/sla_seo_pro', 'user_token=' . $this->session->data['user_token'], true));
		}
/*
		$data['heading_title']    = $this->language->get('heading_title');

		$data['error_permission'] = $this->language->get('error_permission');

		$data['text_success']     = $this->language->get('text_success');
		$data['text_enabled']     = $this->language->get('text_enabled');
		$data['text_disabled']    = $this->language->get('text_disabled');
		$data['text_yes']         = $this->language->get('text_yes');
		$data['text_no']          = $this->language->get('text_no');
		$data['text_edit']        = $this->language->get('text_edit');

		$data['entry_seo_url']    = $this->language->get('entry_seo_url');
		$data['entry_status']     = $this->language->get('entry_status');
		
		$data['entry_sla_seo_pro_postfix']                    = $this->language->get('entry_sla_seo_pro_postfix');
		$data['entry_sla_seo_pro_postfix_help']               = $this->language->get('entry_sla_seo_pro_postfix_help');
		
		$data['entry_sla_seo_pro_include_path']               = $this->language->get('entry_sla_seo_pro_include_path');
		$data['entry_sla_seo_pro_include_path_help']          = $this->language->get('entry_sla_seo_pro_include_path_help');
		
		$data['entry_sla_seo_pro_postfix_slash']              = $this->language->get('entry_sla_seo_pro_postfix_slash');
		$data['entry_sla_seo_pro_postfix_slash_help']         = $this->language->get('entry_sla_seo_pro_postfix_slash_help');
		
		$data['entry_sla_seo_pro_postfix_slash_product']      = $this->language->get('entry_sla_seo_pro_postfix_slash_product');
		$data['entry_sla_seo_pro_postfix_slash_product_help'] = $this->language->get('entry_sla_seo_pro_postfix_slash_product_help');
		
		$data['entry_sla_seo_pro_short_url']                  = $this->language->get('entry_sla_seo_pro_short_url');
		$data['entry_sla_seo_pro_short_url_help']             = $this->language->get('entry_sla_seo_pro_short_url_help');
		
		$data['entry_sla_seo_pro_prefix_category']            = $this->language->get('entry_sla_seo_pro_prefix_category');
		$data['entry_sla_seo_pro_prefix_category_help']       = $this->language->get('entry_sla_seo_pro_prefix_category_help');
	
		$data['entry_sla_seo_pro_prefix_product']             = $this->language->get('entry_sla_seo_pro_prefix_product');
		$data['entry_sla_seo_pro_prefix_product_help']        = $this->language->get('entry_sla_seo_pro_prefix_product_help');

		$data['help_seo_url']     = $this->language->get('help_seo_url');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
*/
		$data['action'] = $this->url->link('extension/module/sla_seo_pro', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . "&type=module", true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/sla_seo_pro', 'user_token=' . $this->session->data['user_token'], true)
		);
		$this->config->load('sla_seo_pro');
		$allow_input = array(
			'sla_seo_pro_status',
			'sla_seo_pro_include_path',
			'sla_seo_pro_postfix',
			'sla_seo_pro_postfix_slash',
			'sla_seo_pro_postfix_slash_product',
			'sla_seo_pro_short_url',
			'sla_seo_pro_prefix_category',
			'sla_seo_pro_prefix_product',
			'sla_seo_pro_utm',
			'config_seo_url',
		);
		foreach ($allow_input as $input) {
			if (isset($this->request->post[$input])) {
				$data[$input] = $this->request->post[$input];
			} else {
				$data[$input] = $this->config->get($input);
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/sla_seo_pro', $data));
	}

	public function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/sla_seo_pro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	
	public function install() {
		
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "seo_url` WHERE Field = 'seopath'");
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "seo_url` ADD `seopath` VARCHAR( 255 ) NOT NULL";
			$this->db->query($sql);
		}
		
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "product_to_category` WHERE Field = 'main_category'");
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_to_category` ADD `main_category` INT(1) NOT NULL DEFAULT '0'";
			$this->db->query($sql);
		}
		
		$seourl = array(
			'common/home' 			=> '',
			'account/wishlist' 		=> 'wishlist',
			'account/account' 		=> 'account',
			'checkout/cart' 		=> 'cart',
			'checkout/checkout' 	=> 'checkout',
			'account/login'			=> 'login',
			'account/logout'		=> 'logout',
			'account/order'			=> 'order-history',
			'account/order/info'	=> 'order-information',
			'account/newsletter'	=> 'newsletter',
			'product/special'		=> 'specials',
			'affiliate/account'		=> 'affiliates',
			'account/voucher'		=> 'gift-vouchers',
			'account/recurring'		=> 'recurring-payments',
			'product/manufacturer'	=> 'brands',
			'information/contact'	=> 'contact-us',
			'account/return/add'	=> 'request-return',
			'information/sitemap'	=> 'sitemap',
			'account/forgotten'		=> 'forgot-password',
			'account/download'		=> 'downloads',
			'account/return'		=> 'returns',
			'account/transaction'	=> 'transactions',
			'account/register'		=> 'create-account',
			'product/compare'		=> 'compare-products',
			'product/search'		=> 'search',
			'account/edit'			=> 'edit-account',
			'account/password'		=> 'change-password',
			'account/address'		=> 'address-book',
			'account/address/edit'	=> 'edit-address',
			'account/address/add'	=> 'add-address',
			'account/address/delete'=> 'delete-address',
			'account/reward'		=> 'reward-points',
			'affiliate/edit'		=> 'edit-affiliate-account',
			'affiliate/password'	=> 'change-affiliate-password',
			'affiliate/payment'		=> 'affiliate-payment-options',
			'affiliate/tracking'	=> 'affiliate-tracking-code',
			'affiliate/transaction'	=> 'affiliate-transactions',
			'affiliate/logout'		=> 'affiliate-logout',
			'affiliate/forgotten'	=> 'affiliate-forgot-password',
			'affiliate/register'	=> 'create-affiliate-account',
			'affiliate/login'		=> 'affiliate-login'
		);
			
		foreach ($seourl as $query => $keyword) {
			$result = $this->db->query("SELECT `keyword` FROM " . DB_PREFIX ."seo_url WHERE `keyword`='" . $keyword. "'");
			if (!$result->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX ."seo_url (store_id, query, keyword) VALUES ('0', '" . $query . "', '" . $keyword . "')");
			}
		}
		
		
		$xml = "<?xml version=\"1.0\"?>
		<modification>
			<name>SlaSoft Seopro On</name>
			<code>SlaSoft.Seopro.On</code>
			<version>1.0</version>
			<author>SlaSoft</author>
				
			<file path=\"system/config/catalog.php\">
				<operation>
					<search><![CDATA['startup/seo_url']]></search>
					<add position=\"replace\"><![CDATA['extension/startup/sla_seo_pro',]]></add>
				</operation>
			</file>

			<file path=\"admin/model/catalog/*.php\">
				<operation>
					<search><![CDATA[\$this->cache->delete('product');]]></search>
					<add position=\"before\"><![CDATA[\$this->cache->delete('seo_pro');\$this->cache->delete('seopath');]]></add>
				</operation>
			</file>
		</modification>";
		
		$modification_data = array(
			'name'    => 'SlaSoft Seopro On',
			'code'    => 'SlaSoft.Seopro.On',
			'author'  => 'SlaSoft',
			'version' => '1.0',
			'link'    => '',
			'status'  => 1,
			'extension_install_id'  => 999998,
			'xml'     => $xml,
		);
		
		$this->load->model('setting/modification');
		$this->model_setting_modification->addModification($modification_data);
		
		$this->load->controller('marketplace/modification/refresh', array('redirect' => 'marketplace/extension'));
	}

	public function uninstall() {
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "seo_url` WHERE Field = 'seopath'");
		if($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "seo_url` DROP COLUMN `seopath`;");
		}
		$sql = "DELETE FROM " . DB_PREFIX . "modification WHERE code = 'SlaSoft.Seopro.On'";
		$query = $this->db->query($sql);
	}
}
