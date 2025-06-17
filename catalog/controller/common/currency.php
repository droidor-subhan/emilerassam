<?php
class ControllerCommonCurrency extends Controller {
	public function index() {
		$this->load->language('common/currency');

		$data['action'] = $this->url->link('common/currency/currency', '', $this->request->server['HTTPS']);

		$this->load->model('setting/extension');
		$loc = $this->model_setting_extension->getLocationByIp();
		if (isset($loc['geoplugin_countryCode']) && $loc['geoplugin_countryCode'] != '') {
			$this->load->model('account/customer');
			$localization = $this->model_account_customer->getLocalizationData($loc['geoplugin_countryCode']);
			if (isset($localization) && is_array($localization) && count($localization) > 0) {
				if (isset($localization['language_id']) && $localization['language_id'] != '') {
					if (isset($localization['currency_id']) && $localization['currency_id'] != '') {
						$currency_code = $localization['currency_id'];
						if (!isset($this->session->data['currency_is_default'])) {
							
							if(isset($this->session->data['localiztion_changed_by'])) {
								if( $this->session->data['localiztion_changed_by'] == 'currency' ) {
									$this->session->data['currency'] = $currency_code;
								}
								//  else if( $this->session->data['localiztion_changed_by'] == 'ip_address' ) {

								// }
							}
						}
					}
				}
			}
		}

		$data['code'] = $this->session->data['currency'];
		$this->load->model('localisation/currency');

		$data['currencies'] = array();

		$results = $this->model_localisation_currency->getCurrencies();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']
				);
			}
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect'] = $this->url->link('common/home');
		} else {
			$url_data = $this->request->get;

			unset($url_data['_route_']);

			$route = $url_data['route'];

			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

			$data['redirect'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
		}

		return $this->load->view('common/currency', $data);
	}

	public function currency() {
		if (isset($this->request->post['code'])) {
			$this->session->data['currency'] = $this->request->post['code'];
			$this->session->data['currency_is_default'] = false;
			$this->session->data['localiztion_changed_by'] = 'currency';
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}
		
		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}
}