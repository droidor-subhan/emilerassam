<?php

use Journal3\Opencart\ModuleController;
use Journal3\Options\Parser;
use Journal3\Utils\Arr;

class ControllerJournal3Products extends ModuleController {

	public function __construct($registry) {
		parent::__construct($registry);

		$this->load->language('product/product');

		$this->load->model('journal3/filter');
		$this->load->model('journal3/product');
		$this->load->model('catalog/product');
	}

	/**
	 * @param Parser $parser
	 * @param $index
	 * @return array
	 */
	protected function parseGeneralSettings($parser, $index) {
		$default = $parser->getSetting('default');

		$data = array(
			'classes'         => array(
				'module-products-' . $parser->getSetting('display'),
				'carousel-mode' => $parser->getSetting('carousel'),
				'isotope'       => $parser->getSetting('sectionsDisplay') === 'isotope',
			),
			'image_width'     => $parser->getSetting('imageDimensions.width', $this->journal3->themeConfig('image_product_width')),
			'image_height'    => $parser->getSetting('imageDimensions.height', $this->journal3->themeConfig('image_product_height')),
			'image_resize'    => $parser->getSetting('imageDimensions.resize'),
			'carouselOptions' => $this->journal3->carousel($parser->getJs(), 'carouselStyle'),
		);

		if ($this->journal3->settings->get('performanceLazyLoadImagesStatus')) {
			$data['dummy_image'] = $this->model_journal3_image->transparent($parser->getSetting('imageDimensions.width', $this->journal3->themeConfig('image_product_width')), $parser->getSetting('imageDimensions.height', $this->journal3->themeConfig('image_product_height')));
		}

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$data['default_index'] = $parser->getSetting('sectionsDisplay') === 'isotope' ? 0 : 1;

		if ($default) {
			foreach (Arr::get($this->module_data, 'items') as $index => $item) {
				if ($default === Arr::get($item, 'id')) {
					$data['default_index'] = $index + 1;
					break;
				}
			}
		}

		return $data;
	}

	/**
	 * @param Parser $parser
	 * @param $index
	 * @return array
	 */
	protected function parseItemSettings($parser, $index) {
		$filter_data = $parser->getSetting('filter');
		$preset = Arr::get($filter_data, 'preset');
		$limit = Arr::get($filter_data, 'limit');

		if ($this->settings['sectionsDisplay'] === 'tabs' && $parser->getSetting('tabType') === 'link') {
			$products = array();
		} else {
			switch ($preset) {
				case 'related':
				case 'related_category':
				case 'related_manufacturer':
				case 'alsobought':
				case 'recently_viewed':
					$products = null;
					break;

				case 'most_viewed':
					$products = $this->model_journal3_product->getMostViewedProducts($limit);
					break;

				case 'custom':
					$products = $this->model_journal3_product->getProduct(array_filter(Arr::get($filter_data, 'products')));
					break;

				default:
					$products = $this->model_journal3_product->getProducts($filter_data);
			}
		}

		return array(
			'tab_classes'   => array(
				'tab-' . $this->item_id,
			),
			'panel_classes' => array(
				'panel-collapse',
				'collapse',
			),
			'classes'       => array(
				'tab-pane'     => $this->settings['sectionsDisplay'] === 'tabs',
				'panel'        => $this->settings['sectionsDisplay'] === 'accordion',
				'swiper-slide' => ($this->settings['sectionsDisplay'] === 'blocks') && $this->settings['carousel'],
			),
			'products'      => $products,
		);
	}

	/**
	 * @param Parser $parser
	 * @param $index
	 * @return array
	 */
	protected function parseSubitemSettings($parser, $index) {
		return array();
	}

	protected function beforeRender() {
		if (!$this->settings['items']) {
			$this->settings = null;

			return;
		}

		foreach ($this->settings['items'] as $key => $item) {
			$products = $item['products'];

			if ($products === null) {
				$filter_data = Arr::get($item, 'filter');
				$preset = Arr::get($filter_data, 'preset');
				$limit = Arr::get($filter_data, 'limit');

				switch ($preset) {
					case 'related':
						switch (Arr::get($this->request->get, 'route')) {
							case 'journal3/blog/post':
								$post_id = (int)Arr::get($this->request->get, 'journal_blog_post_id');
								$results = $this->model_journal3_blog->getRelatedProducts($post_id, $limit);
								break;

							case 'product/product':
								$product_id = (int)Arr::get($this->request->get, 'product_id');
								$results = $this->model_journal3_product->getRelatedProducts($product_id, $limit);
								break;

							case 'checkout/cart':
							case 'checkout/checkout':
								$product_ids = $this->cart->getProducts();
								$results = $this->model_journal3_product->getRelatedProducts($product_ids, $limit);
								break;

							case 'account/wishlist':
								$this->load->model('account/wishlist');
								$product_ids = $this->model_account_wishlist->getWishlist();
								$results = $this->model_journal3_product->getRelatedProducts($product_ids, $limit);
								break;

							default:
								$results = array();
						}

						break;

					case 'related_category':
						$product_id = (int)Arr::get($this->request->get, 'product_id');

						if ($product_id) {
							$results = $this->model_journal3_product->getRelatedProductsByCategory($product_id, $limit);
						} else {
							$results = array();
						}

						break;

					case 'related_manufacturer':
						$product_id = (int)Arr::get($this->request->get, 'product_id');

						if ($product_id) {
							$results = $this->model_journal3_product->getRelatedProductsByManufacturer($product_id, $limit);
						} else {
							$results = array();
						}

						break;

					case 'alsobought':
						switch (Arr::get($this->request->get, 'route')) {
							case 'product/product':
								$product_id = (int)Arr::get($this->request->get, 'product_id');
								$results = $this->model_journal3_product->getAlsoBoughtProducts($product_id, $limit);
								break;

							case 'checkout/cart':
							case 'checkout/checkout':
								$product_ids = $this->cart->getProducts();
								$results = $this->model_journal3_product->getAlsoBoughtProducts($product_ids, $limit);
								break;

							case 'account/wishlist':
								$this->load->model('account/wishlist');
								$product_ids = $this->model_account_wishlist->getWishlist();
								$results = $this->model_journal3_product->getAlsoBoughtProducts($product_ids, $limit);
								break;

							default:
								$results = array();
						}

						break;

					case 'recently_viewed':
						$results = $this->model_journal3_product->getRecentlyViewedProducts($limit);
						break;

					case 'most_viewed':
						$results = $this->model_journal3_product->getMostViewedProducts($limit);
						break;

					case 'custom':
						$results = $this->model_journal3_product->getProduct(Arr::get($filter_data, 'products'));
						break;

					default:
						$results = $this->model_journal3_product->getProducts($filter_data);
				}

				if (!$results) {
					unset($this->settings['items'][$key]);

					continue;
				}

				$products = $this->parseProducts($results);
			} else {
				$products = $this->parseProducts($products);
			}

			if (!$products && !($this->settings['sectionsDisplay'] === 'tabs' && $item['tabType'] === 'link')) {
				unset($this->settings['items'][$key]);

				continue;
			}

			$this->settings['items'][$key]['products'] = $products;

			if ($this->settings['sectionsDisplay'] === 'isotope') {
				foreach ($products as $product) {
					if (!isset($this->settings['products'][$product['product_id']])) {
						$this->settings['products'][$product['product_id']] = $products[$product['product_id']];
					}

					$this->settings['products'][$product['product_id']]['classes'] = array_merge_recursive($this->settings['products'][$product['product_id']]['classes'], array($this->settings['id'] . '-section-' . $key));
				}
			}
		}

		if (!$this->settings['items']) {
			$this->settings = null;

			return;
		}

		$keys = array_keys($this->settings['items']);

		if (!in_array($this->settings['default_index'], $keys)) {
			$this->settings['default_index'] = $keys[0];
		}

		if ($this->settings['sectionsDisplay'] === 'tabs') {
			$this->settings['items'][$this->settings['default_index']]['classes'][] = 'active';
			$this->settings['items'][$this->settings['default_index']]['tab_classes'][] = 'active';
		}

		if ($this->settings['sectionsDisplay'] === 'accordion') {
			$this->settings['items'][$this->settings['default_index']]['classes'][] = 'active';
			$this->settings['items'][$this->settings['default_index']]['panel_classes'][] = 'in';
		}
	}

	protected function afterRender() {
		if ($this->settings['sectionsDisplay'] === 'isotope') {
			$this->journal3->document->addScript('catalog/view/theme/journal3/lib/isotope/isotope.pkgd.min.js', 'footer');
		}

		if ($this->settings['carousel']) {
			$this->journal3->document->addStyle('catalog/view/theme/journal3/lib/swiper/swiper.min.css');
			$this->journal3->document->addScript('catalog/view/theme/journal3/lib/swiper/swiper.min.js', 'footer');
		}
	}

	private function parseProducts($results) {
		$products = array();

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_journal3_image->resize($result['image'], $this->settings['image_width'], $this->settings['image_height'], $this->settings['image_resize']);
				$image2x = $this->model_journal3_image->resize($result['image'], $this->settings['image_width'] * 2, $this->settings['image_height'] * 2, $this->settings['image_resize']);
			} else {
				$image = $this->model_journal3_image->resize('placeholder.png', $this->settings['image_width'], $this->settings['image_height'], $this->settings['image_resize']);
				$image2x = $this->model_journal3_image->resize('placeholder.png', $this->settings['image_width'] * 2, $this->settings['image_height'] * 2, $this->settings['image_resize']);
			}

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$special = false;
			}

			if ($this->config->get('config_tax')) {
				$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
			} else {
				$tax = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}

			if ($this->journal3->document->isDesktop() && Arr::get($this->settings, 'moduleProductGridSecondImageStatus', $this->journal3->settings->get('globalProductGridSecondImageStatus')) && ($additional_image = $this->journal3->productSecondImage($result))) {
				$second_image = $this->model_journal3_image->resize($additional_image, $this->settings['image_width'], $this->settings['image_height'], $this->settings['image_resize']);
				$second_image2x = $this->model_journal3_image->resize($additional_image, $this->settings['image_width'] * 2, $this->settings['image_height'] * 2, $this->settings['image_resize']);
			} else {
				$second_image = false;
				$second_image2x = false;
			}
			
			$options = array();
			foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
				$product_option_value_data = array();
				foreach ($option['product_option_value'] as $option_value) {
					// if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if( $option['option_id'] == '14' ) {
							$product_option_value_data['name'] = $option_value['name'];
							$product_option_value_data['image'] = "image/".$option_value['image'];
							
							$image_color_hovering = $this->model_tool_image->resize($option_value['option_image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));

							$product_option_value_data['option_image'] = $image_color_hovering;
							$options[] = $product_option_value_data;
						}
					// }
				}
			}


			$products[$result['product_id']] = array(
				'options'  => $options,
				'classes'        => array(
					'swiper-slide' => $this->settings['sectionsDisplay'] !== 'isotope' && $this->settings['carousel'],
					'isotope-item' => $this->settings['sectionsDisplay'] === 'isotope',
					defined('JOURNAL3_ACTIVE') ? $this->journal3->productExcludeButton($result, $price, $special) : null,
				),
				'quantity'       => defined('JOURNAL3_ACTIVE') ? $result['quantity'] : null,
				'stock_status'   => defined('JOURNAL3_ACTIVE') ? $result['stock_status'] : null,
				'thumb2x'        => defined('JOURNAL3_ACTIVE') ? $image2x : null,
				'second_thumb'   => defined('JOURNAL3_ACTIVE') ? $second_image : null,
				'second_thumb2x' => defined('JOURNAL3_ACTIVE') ? $second_image2x : null,
				'labels'         => defined('JOURNAL3_ACTIVE') ? $this->journal3->productLabels($result, $price, $special) : null,
				'extra_buttons'  => defined('JOURNAL3_ACTIVE') ? $this->journal3->productExtraButton($result, $price, $special) : null,
				'date_end'       => defined('JOURNAL3_ACTIVE') ? $this->journal3->productCountdown($result) : null,
				'price_value'    => defined('JOURNAL3_ACTIVE') ? ($result['special'] ? $result['special'] > 0 : $result['price'] > 0) : null,
				'stat1'          => defined('JOURNAL3_ACTIVE') ? $this->journal3->productStat($result, Arr::get($this->settings, 'moduleProductStat1')) : null,
				'stat2'          => defined('JOURNAL3_ACTIVE') ? $this->journal3->productStat($result, Arr::get($this->settings, 'moduleProductStat2')) : null,
				'product_id'     => $result['product_id'],
				'thumb'          => $image,
				'name'           => $result['name'],
				'description'    => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, (int)$this->settings['moduleProductDescriptionLimit']) . '..',
				'price'          => $price,
				'special'        => $special,
				'tax'            => $tax,
				'minimum'        => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'rating'         => $rating,
				'href'           => $this->url->link('product/product', 'product_id=' . $result['product_id']),
			);
		}

		return $products;
	}

}
