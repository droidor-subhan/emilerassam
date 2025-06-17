<?php
class ControllerCommonCountry extends Controller {
	public function country() {

		if (isset($this->request->post['code'])) {
			$this->session->data['country'] = $this->request->post['code'];
			$this->session->data['country_is_default'] = false;

			$this->load->model('setting/extension');
			$this->load->model('account/customer');
			
			$loc = $this->model_account_customer->getCountryDataByCountryName($this->request->post['code']);
			if (isset($loc['iso_code_2']) && $loc['iso_code_2'] != '') {
				$localization = $this->model_account_customer->getLocalizationData($loc['iso_code_2']);
				if (isset($localization) && is_array($localization) && count($localization) > 0) {
					if (isset($localization['currency_id']) && $localization['currency_id'] != '') {
						
						$currency_code = $localization['currency_id'];
						$language_code = $localization['language_id'];
						$country = $localization['name'];
						$market_id = $localization['market_id'];
						
						$this->session->data['localiztion_changed_by'] = "country";
						if(isset($this->session->data['localiztion_changed_by'])) {
							if( $this->session->data['localiztion_changed_by'] == 'country' ) {
								$this->session->data['currency'] = $currency_code;
								$this->session->data['language'] = $language_code;
								$this->session->data['market_country'] = $country;
								$this->session->data['market_id'] = $market_id;
							}
							//  else if( $this->session->data['localiztion_changed_by'] == 'ip_address' ) {

							// }
						}
					}
				}
			}
		}
		
		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}
	}

	public function userChangedCountryLanguageDefaultPopup() {

		if (isset($this->request->post['country_id_user_selection']) && isset($this->request->post['language_id_user_selection'])) {
			
			$this->session->data['country'] = $this->request->post['country_id_user_selection'];
			$this->session->data['country_is_default'] = false;
			$this->load->model('setting/extension');
			$this->load->model('account/customer');
			
			$loc = $this->model_account_customer->getCountryDataByCountryName($this->request->post['country_id_user_selection']);
			if (isset($loc['iso_code_2']) && $loc['iso_code_2'] != '') {
				$localization = $this->model_account_customer->getLocalizationData($loc['iso_code_2']);

				if (isset($localization) && is_array($localization) && count($localization) > 0) {
					if (isset($localization['currency_id']) && $localization['currency_id'] != '') {
						
						$currency_code = $localization['currency_id'];
						$language_code = $this->request->post['language_id_user_selection'];
						$country = $this->request->post['country_id_user_selection'];
						$market_id = $localization['market_id'];
						
						$this->session->data['localiztion_changed_by'] = "country";
						if(isset($this->session->data['localiztion_changed_by'])) {
							if( $this->session->data['localiztion_changed_by'] == 'country' ) {
								$this->session->data['currency'] = $currency_code;
								$this->session->data['language'] = $language_code;
								$this->session->data['market_country'] = $country;
								$this->session->data['market_id'] = $market_id;
							}
							//  else if( $this->session->data['localiztion_changed_by'] == 'ip_address' ) {

							// }
						}
					}
				}
			}
		}
	}
}
