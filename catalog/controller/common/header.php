<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('setting/extension');
		$this->load->model('localisation/country');

		$data['analytics'] = array();

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['menu'] = $this->load->controller('common/menu');

		$data['countries_and_markets'] = $this->model_localisation_country->getCountriesAndMarkets();
		foreach ($data['countries_and_markets'] as &$item) {
			$item['name'] = ucwords(strtolower($item['name']));
		}
		
		// echo "<pre>";
		// 	print_r($data['countries_and_markets']);
		// echo "</pre>";
	
		
		$loc = $this->model_setting_extension->getLocationByIp();
		// echo "<pre>";
		// 	print_r($loc);
		// echo "</pre>";
		

		$notAllowedCountries = array('AF' => 'Afghanistan','AL' => 'Albania','DZ' => 'Algeria','AD' => 'Andorra','AR' => 'Argentina','BS' => 'Bahamas','BD' => 'Bangladesh','BY' => 'Belarus','BR' => 'Brazil','BG' => 'Bulgaria','KH' => 'Cambodia','IC' => 'Canary Islands','CV' => 'Cape Verde','KY' => 'Cayman Islands','CF' => 'Central African Republic','TD' => 'Chad','CO' => 'Colombia','EC' => 'Ecuador','EG' => 'Egypt','ER' => 'Eritrea','GL' => 'Greenland','GT' => 'Guatemala','GW' => 'Guinea-Bissau','IN' => 'India','IR' => 'Iran','IL' => 'Israel','KP' => 'Korea','LV' => 'Latvia','LY' => 'Libya','MX' => 'Mexico','MA' => 'Morocco','KP' => 'North Korea','PK' => 'Pakistan','PS' => 'Palestine State','RU' => 'Russian Federation','SD' => 'Sudan','SY' => 'Syria','TN' => 'Tunisia','TR' => 'Turkey','YE' => 'Yemen'
		);
		
		$data['notAllowedCountry'] = false;
		if (isset($notAllowedCountries[$loc['countryCode']])) {
			$data['notAllowedCountry'] = true;
		}

		if( isset($loc['countryName']) && $loc['countryName'] != '' ) {
			$countryCode = $loc['countryName'];
			// if( isset($loc['city']) && $loc['city'] != '' ) {
				if (!isset($this->session->data['country_is_default'])) {
					$data['country_name'] = $countryCode;
					// $data['country_name'] .= "<br />(".$loc['city'].")";
				} else {
					$data['country_name'] = $this->session->data['country'];
				}
			// }
		}

		$this->load->model('account/customer');
		
		if( isset($loc['geoplugin_countryCode']) && $loc['geoplugin_countryCode'] != '' ) {
			$localization = $this->model_account_customer->getLocalizationData($loc['geoplugin_countryCode']);
			
			if (isset($localization) && is_array($localization) && count($localization) > 0) {
				if (isset($localization['currency_id']) && $localization['currency_id'] != '') {
					
					$currency_code = $localization['currency_id'];
					$language_code = $localization['language_id'];
					$country = $localization['name'];
					$market_id = $localization['market_id'];
					
					if( !isset($this->session->data['localiztion_changed_by']) || $this->session->data['localiztion_changed_by'] == "" ) {
						$this->session->data['localiztion_changed_by'] = "ip_address";
						$this->session->data['currency'] = $currency_code;
						$this->session->data['language'] = $language_code;
						$this->session->data['market_country'] = $country;
						$this->session->data['market_id'] = $market_id;
					}
				}
			}
		}

		// echo "<pre>";
		// 	print_r(json_encode($this->session->data));
		// echo "</pre>";
		
		$url_data = $this->request->get;
		unset($url_data['_route_']);
		$route = "";
		if( isset($url_data['route']) ) {
			$route = $url_data['route'];
		}
		unset($url_data['route']);
		$url = '';
		if ($url_data) {
			$url = '&' . urldecode(http_build_query($url_data, '', '&'));
		}

		if( $route == "" && $url == "" ) {
			$data['redirect'] = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		} else {
			$data['redirect'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
		}

		$data['country_action'] = $this->url->link('common/country/country');

		$data['country_action'] = $this->url->link('common/country/country');

		$ishomepage = 0;
		// echo $this->request->getget['route']."<br /><br /><br />\n\n\n\n";
		if( (isset($this->request->get['route']) && ($this->request->get['route'] == "common/home" || $this->request->get['route'] == "" )) ) {
			$ishomepage = 1;
		} else if( !isset($this->request->get['route']) ) {
			$ishomepage = 1;
		}
		$data['ishomepage'] = $ishomepage;

		return $this->load->view('common/header', $data);
	}	
}
