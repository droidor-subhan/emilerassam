<?php
class ControllerCommonLanguage extends Controller
{
	public function index()
	{
		$this->load->language('common/language');
		$this->load->model('setting/extension');
		$data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);
		$loc = $this->model_setting_extension->getLocationByIp();
		if (isset($loc['geoplugin_countryCode']) && $loc['geoplugin_countryCode'] != '') {
			$this->load->model('account/customer');
			$localization = $this->model_account_customer->getLocalizationData($loc['geoplugin_countryCode']);
			if (isset($localization) && is_array($localization) && count($localization) > 0) {
				if (isset($localization['language_id']) && $localization['language_id'] != '') {
					$language_code = $localization['language_id'];
					if (!isset($this->session->data['language_is_default'])) {

						if(isset($this->session->data['localiztion_changed_by'])) {
							if( $this->session->data['localiztion_changed_by'] == 'language' ) {
								$this->session->data['language'] = $language_code;
							}
							//  else if( $this->session->data['localiztion_changed_by'] == 'ip_address' ) {

							// }
						}

					}
				}
			}
		}
		$data['code'] = $this->session->data['language'];

		
		$this->load->model('localisation/language');
		$data['languages'] = array();
		$results = $this->model_localisation_language->getLanguages();
		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
					'name' => $result['name'],
					'code' => $result['code']
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

		if( $data['code'] == "en-gb" ) {
			$data['other_language_name'] = "French";
			$data['other_language_code'] = "fr-FR";
		} else if( $data['code'] == "fr-FR" ) {
			$data['other_language_name'] = "English";
			$data['other_language_code'] = "en-gb";
		} else {
			$data['other_language_name'] = "English";
			$data['other_language_code'] = "en-gb";
		}

		// echo "<pre>";
		// 	print_r($data);
		// echo "</pre>";
		// die;

		return $this->load->view('common/language', $data);
	}

	public function language()
	{
		if (isset($this->request->post['code'])) {
			if (isset($this->request->post['code'])) {
				if ($this->request->post['code'] == "fr-FR") {
					$this->session->data['currency'] = "EUR";
				} else if ($this->request->post['code'] == "en-gb") {
					$this->session->data['currency'] = "USD";
				}
				$this->session->data['localiztion_changed_by'] = 'language';
				$this->session->data['language_is_default'] = false;
			}
			$this->session->data['language'] = $this->request->post['code'];
		}
		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}
}
