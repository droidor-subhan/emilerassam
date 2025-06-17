<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		$data['scripts'] = $this->document->getScripts('footer');
		$data['styles'] = $this->document->getStyles('footer');
		
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;
		
		$this->load->model('localisation/currency');
		$this->load->model('localisation/country');
		$data['currencies_list'] = $this->model_localisation_currency->getCurrencies();
		$data['countries_list'] = $this->model_localisation_country->getCountries();
		foreach ($data['countries_list'] as &$item) {
			$item['name'] = ucwords(strtolower($item['name']));
		}

		// echo "<pre>";
		// 	print_r($data['countries_list']);
		// echo "</pre>";

		$data['currency_code'] = "USD";
		$data['language_code'] = "en-gb";
		$data['country_id'] = "114";

		$this->load->model('setting/extension');
		$loc = $this->model_setting_extension->getLocationByIp();

		if( isset($loc['geoplugin_countryCode']) && $loc['geoplugin_countryCode'] != '' ) {
			$this->load->model('account/customer');
			$localization = $this->model_account_customer->getLocalizationData($loc['geoplugin_countryCode']);

			if (isset($localization) && is_array($localization) && count($localization) > 0) {
				if (isset($localization['currency_id']) && $localization['currency_id'] != '') {
					$data['currency_code'] = $localization['currency_id'];
					$data['country_id'] = $localization['country_id'];
					$data['language_code'] = $localization['language_id'];
				}
			}

		}
		
		return $this->load->view('common/footer', $data);
	}

	public function checkPhoneValidation() {
		$session = $this->session;
		$session_data = $session->data;

		if( isset($session_data['customer_id']) && $session_data['customer_id'] != '' && $session_data['customer_id'] > 0 ) {
			$this->load->model('account/customer');
			$customer_id = $session_data['customer_id'];
			$customer_info = $this->model_account_customer->getCustomer($customer_id);
			$response = array();
			if( $customer_info['is_phone_verified'] == 1 ) {
				echo 1;
			} else {
				echo 2;
			}
		}
	}

	public function resendPhoneVerification() {
		
		$session = $this->session;
		$session_data = $session->data;
		
		if( isset($session_data['customer_id']) && $session_data['customer_id'] != '' && $session_data['customer_id'] > 0 ) {
			$this->load->model('account/customer');
			$customer_id = $session_data['customer_id'];
			$customer_info = $this->model_account_customer->getCustomer($customer_id);
			if( isset($customer_info['country_code']) && isset($customer_info['telephone']) ) {
				$customer_info = $this->model_account_customer->savePhoneVerificationCode($customer_info);
				echo 1;
			}
		}
		echo 0;
	}

	public function codeVerificationProcess() {

		$session = $this->session;
		$session_data = $session->data;
		$phone_code = $this->request->post['phone_code'];
		$status = 0;

		if( isset($session_data['customer_id']) && $session_data['customer_id'] != '' && $session_data['customer_id'] > 0 ) {
			if( isset($phone_code) && $phone_code != '' && $phone_code > 0 ) {
				$this->load->model('account/customer');
				$customer_id = $session_data['customer_id'];
				$customer_info = $this->model_account_customer->getCustomer($customer_id);
				if( isset($customer_info['country_code']) && isset($customer_info['telephone']) ) {
					$data = $this->model_account_customer->codeVerificationProcess($customer_info, $phone_code);
					if( $data == 1 ) {
						$this->model_account_customer->updateUserPhoneVerificationStatus($customer_info, $phone_code);
						$status = 1;
					}
				}
			}
		}
		echo $status;
	}
}