<?php

class ControllerProductCatalog extends Controller {
	public function index() {
		$this->load->language('product/manufacturer');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->journal3->themeConfig('product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
		);

		$this->document->setTitle($this->journal3->settings->get('allProductsPageMetaTitle'));
		$this->document->setDescription($this->journal3->settings->get('allProductsPageMetaDescription'));
		$this->document->setKeywords($this->journal3->settings->get('allProductsPageMetaKeywords'));

		$data['heading_title'] = $this->language->get('all_product_text');
		
		// $data['heading_title'] = $this->journal3->settings->get('allProductsPageTitle');

		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

		// Set the last category breadcrumb
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('all_product_text'),
			'href' => $this->url->link('product/catalog'),
		);


		$data['compare'] = $this->url->link('product/compare');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['thumb'] = null;
		$data['description'] = null;
		$data['categories'] = array();
		$data['button_grid'] = null;
		$data['button_list'] = null;
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['button_continue'] = $this->language->get('button_continue');

		$data['products'] = array();

		$filter_data = array(
			'filter_category_id' => 0,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit,
		);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_journal3_image->resize($result['image'], $this->journal3->themeConfig('image_product_width'), $this->journal3->themeConfig('image_product_height'), $this->journal3->themeConfig('image_product_resize'));
			} else {
				$image = $this->model_journal3_image->resize('placeholder.png', $this->journal3->themeConfig('image_product_width'), $this->journal3->themeConfig('image_product_height'), $this->journal3->themeConfig('image_product_resize'));
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
				$rating = (int)$result['rating'];
			} else {
				$rating = false;
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

			$data['products'][] = array(
				'product_id'  => $result['product_id'],
				'options'  => $options,
				'thumb'       => $image,
				'name'        => $result['name'],
				'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, (int)$this->journal3->themeConfig('product_description_length')) . '..',
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'rating'      => $result['rating'],
				'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'] . $url),
			);
		}

		$url = '';

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => $this->url->link('product/catalog', '&sort=p.sort_order&order=ASC' . $url),
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link('product/catalog', '&sort=pd.name&order=ASC' . $url),
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link('product/catalog', '&sort=pd.name&order=DESC' . $url),
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'p.price-ASC',
			'href'  => $this->url->link('product/catalog', '&sort=p.price&order=ASC' . $url),
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'p.price-DESC',
			'href'  => $this->url->link('product/catalog', '&sort=p.price&order=DESC' . $url),
		);

		if ($this->config->get('config_review_status')) {
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('product/catalog', '&sort=rating&order=DESC' . $url),
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('product/catalog', '&sort=rating&order=ASC' . $url),
			);
		}

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_model_asc'),
			'value' => 'p.model-ASC',
			'href'  => $this->url->link('product/catalog', '&sort=p.model&order=ASC' . $url),
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => $this->url->link('product/catalog', '&sort=p.model&order=DESC' . $url),
		);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['limits'] = array();

		$limits = array_unique(array($this->journal3->themeConfig('product_limit'), 25, 50, 75, 100, 500));

		sort($limits);

		foreach ($limits as $value) {
			if( $value == 500 ) {
				$data['limits'][] = array(
					'text'  => "All Products",
					'value' => $value,
					'href'  => $this->url->link('product/catalog', $url . '&limit=' . $value),
				);
			} else {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/catalog', $url . '&limit=' . $value),
				);

			}
			// $data['limits'][] = array(
			// 	'text'  => $value,
			// 	'value' => $value,
			// 	'href'  => $this->url->link('product/catalog', $url . '&limit=' . $value),
			// );
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('product/catalog', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));
		
		$data['total_products'] = $product_total;

		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
			$this->document->addLink($this->url->link('product/catalog'), 'canonical');
		} else {
			$this->document->addLink($this->url->link('product/catalog', '&page=' . $page), 'canonical');
		}

		if ($page > 1) {
			$this->document->addLink($this->url->link('product/catalog', (($page - 2) ? '&page=' . ($page - 1) : '')), 'prev');
		}

		if ($limit && ceil($product_total / $limit) > $page) {
			$this->document->addLink($this->url->link('product/catalog', '&page=' . ($page + 1)), 'next');
		}

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


		$data['category_image_one'] = "";
		$data['category_image_two'] = "";
		if( isset($category_id) && $category_id != '' ) {
			$category_size_guide = $this->model_catalog_category->getCategoryImages($category_id);
			if( isset($category_size_guide[0]['category_image_one']) && $category_size_guide[0]['category_image_one'] !=''  ) {
				$data['category_image_one'] = HTTP_SERVER . "image/catalog/custom/category_images/".$category_size_guide[0]['category_image_one'];
			} else {
				$data['category_image_one'] = HTTP_SERVER . "image/catalog/custom/category_images/default_1.png";
			}
			if( isset($category_size_guide[0]['category_image_two']) && $category_size_guide[0]['category_image_two'] !=''  ) {
				$data['category_image_two'] = HTTP_SERVER . "image/catalog/custom/category_images/".$category_size_guide[0]['category_image_two'];
			} else {
				$data['category_image_two'] = HTTP_SERVER . "image/catalog/custom/category_images/default_2.png";
			}
		} else {
			$data['category_image_one'] = HTTP_SERVER . "image/catalog/custom/category_images/default_1.png";
			$data['category_image_two'] = HTTP_SERVER . "image/catalog/custom/category_images/default_2.png";
		}

		$category_id = 0;
		$categories = $this->model_catalog_category->getCategories($category_id);

		$data['new_categories'] = array();
		// $data['new_categories1'] = array();
		
		$path_exploded = explode('_', @$this->request->get['path']);
		$selected_category = "";
		if( isset($path_exploded[1]) ) {
			$selected_category = $path_exploded[1];
		}

		$data['is_category_Selected'] = 0;
		foreach ($categories as $key => $category) {	

			// if( $key > 0 && $key <= 8) {
				if( $selected_category == $category['category_id'] ) {
					$data['is_category_Selected'] = 1;
					$data['new_categories'][] = array(
						'url' => "index.php?route=product/category&path=98_".$category['category_id'],
						'name' => $category['name'],
						'selected_category' => "active",
					);	
				} else {
					$data['new_categories'][] = array(
						'url' => "index.php?route=product/category&path=98_".$category['category_id'],
						'name' => $category['name'],
						'selected_category' => "",
					);
				}
			// } else {
			// 	if( $selected_category == $category['category_id'] ) {
			// 		$data['is_category_Selected'] = 1;
			// 		$data['new_categories1'][] = array(
			// 			'url' => "index.php?route=product/category&path=98_".$category['category_id'],
			// 			'name' => $category['name'],
			// 			'selected_category' => "active",
			// 		);	
			// 	} else {
			// 		$data['new_categories1'][] = array(
			// 			'url' => "index.php?route=product/category&path=98_".$category['category_id'],
			// 			'name' => $category['name'],
			// 			'selected_category' => "",
			// 		);
			// 	}
			// }

			

		}

		$route = isset($this->request->get['route']) ? $this->request->get['route'] : '';
		$data["route_name"] = $route;

		$this->response->setOutput($this->load->view('product/category', $data));
	}
}
