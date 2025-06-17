<?php
class ModelCatalogProduct extends Model {
	public function addProduct($data) {
			
			
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', hs_code = '" . $this->db->escape($data['hs_code']) . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), date_modified = NOW()");
		$product_id = $this->db->getLastId();


		if (isset($_FILES['video']['name']['video_file']) && is_array($_FILES['video']['name']['video_file']) && $_FILES['video']['name']['video_file'] != '') {
			$targetDirectory = DIR_IMAGE . "catalog/custom/products_videos/";
			foreach ($_FILES['video']['name']['video_file'] as $key => $eachVideo) {
				$query_to_execute = "";
				$video_uploaded = 0;
				if( $eachVideo && $eachVideo != '' ) {
					$uniqueName = time() . '_' . uniqid() . '_';
					$videName = $uniqueName.basename($eachVideo);
					$videoTmpName = $_FILES['video']['tmp_name']['video_file'][$key];
					
					$targetFile = $targetDirectory . $videName;
					if( move_uploaded_file($videoTmpName, $targetFile) ) {
						$video_uploaded = 1;
						$query_to_execute = "INSERT INTO " . DB_PREFIX . "product_video SET product_id = '" . (int)$product_id . "', video = '" . $this->db->escape($videName) . "' ";
					}
					
					if( isset($_FILES['video']['tmp_name']['video_thumbnail'][$key]) && $_FILES['video']['tmp_name']['video_thumbnail'][$key] != '' ) {
						$thumbnailName = $uniqueName.basename($_FILES['video']['name']['video_thumbnail'][$key]);
						$thumnailTmpName = $_FILES['video']['tmp_name']['video_thumbnail'][$key];
						$targetFile = $targetDirectory . $thumbnailName;
						if( move_uploaded_file($thumnailTmpName, $targetFile) ) {
							if( $video_uploaded == 1 ) {
								$query_to_execute .= ", thumbnail = '" . $this->db->escape($thumbnailName) . "'";
							}
						}
					}
					if( $query_to_execute != '' ) {
						$this->db->query($query_to_execute);
					}
				}
			}
		}

		

		if( isset($_POST['market_prices']) && $_POST['market_prices'] != '' && is_array($_POST['market_prices']) && count($_POST['market_prices']) > 0 ) {
			
			foreach ($_POST['market_prices'] as $key => $each_market) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "products_market_prices SET
					product_id = '" . (int)$product_id . "',
					market_id = '" . (int)$each_market['market_id'] . "',
					price = '" . $each_market['price'] . "'
				");
			}
		}
		
		
		if (isset($_FILES['product_pdf']) && is_array($_FILES['product_pdf']) && isset($_FILES['product_pdf']['name']) && $_FILES['product_pdf']['name'] != '') {
			$targetDirectory = DIR_IMAGE . "catalog/custom/products_pdfs/";
			$uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['product_pdf']['name']);
			$uploadedFile = $_FILES['product_pdf']['tmp_name'];
			$targetFile = $targetDirectory . $uniqueFilename;
			if( move_uploaded_file($uploadedFile, $targetFile) ) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_pdf SET product_id = '" . (int)$product_id . "', media = '" . $this->db->escape($uniqueFilename) . "' ");
			}
		}

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'");

						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "', option_image = '" . $this->db->escape($product_option_value['option_image']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		if (isset($data['product_recurring'])) {
			foreach ($data['product_recurring'] as $recurring) {

				$query = $this->db->query("SELECT `product_id` FROM `" . DB_PREFIX . "product_recurring` WHERE `product_id` = '" . (int)$product_id . "' AND `customer_group_id = '" . (int)$recurring['customer_group_id'] . "' AND `recurring_id` = '" . (int)$recurring['recurring_id'] . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = '" . (int)$product_id . "', customer_group_id = '" . (int)$recurring['customer_group_id'] . "', `recurring_id` = '" . (int)$recurring['recurring_id'] . "'");
				}
			}
		}
		
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				if ((int)$product_reward['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
				}
			}
		}
		
		// SEO URL
		if (isset($data['product_seo_url'])) {
			foreach ($data['product_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
		
		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}


		$this->cache->delete('product');

		return $product_id;
	}

	public function editProduct($product_id, $data) {

		if (isset($_FILES['pdf']['name']['pdf_thumbnail']) && is_array($_FILES['pdf']['name']['pdf_thumbnail']) && $_FILES['pdf']['name']['pdf_thumbnail'] != '') {
			$targetDirectory = DIR_IMAGE . "catalog/custom/products_pdfs/";
			// $this->db->query("DELETE FROM " . DB_PREFIX . "product_pdf WHERE product_id = '" . (int)$product_id . "'");
			foreach ($_FILES['pdf']['name']['pdf_thumbnail'] as $key => $eachpdf) {
				$query_to_execute = "";
				if( $eachpdf && $eachpdf != '' ) {
					$uniqueName = time() . '_' . uniqid() . '_';
					$videName = $uniqueName.basename($eachpdf);
					$pdfTmpName = $_FILES['pdf']['tmp_name']['pdf_thumbnail'][$key];
					$targetFile = $targetDirectory . $videName;
					if( move_uploaded_file($pdfTmpName, $targetFile) ) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_pdf SET product_id = '" . (int)$product_id . "', media = '" . $this->db->escape($videName) . "' ");
					}
				}
			}
		}

		if (isset($_FILES['video']['name']['video_file']) && is_array($_FILES['video']['name']['video_file']) && $_FILES['video']['name']['video_file'] != '') {
			$targetDirectory = DIR_IMAGE . "catalog/custom/products_videos/";
			foreach ($_FILES['video']['name']['video_file'] as $key => $eachVideo) {
				$query_to_execute = "";
				$video_uploaded = 0;
				if( $eachVideo && $eachVideo != '' ) {
					$uniqueName = time() . '_' . uniqid() . '_';
					$videName = $uniqueName.basename($eachVideo);
					$videoTmpName = $_FILES['video']['tmp_name']['video_file'][$key];
					
					$targetFile = $targetDirectory . $videName;
					if( move_uploaded_file($videoTmpName, $targetFile) ) {
						$video_uploaded = 1;
						$query_to_execute = "INSERT INTO " . DB_PREFIX . "product_video SET product_id = '" . (int)$product_id . "', video = '" . $this->db->escape($videName) . "' ";
					}
					
					if( isset($_FILES['video']['tmp_name']['video_thumbnail'][$key]) && $_FILES['video']['tmp_name']['video_thumbnail'][$key] != '' ) {
						$thumbnailName = $uniqueName.basename($_FILES['video']['name']['video_thumbnail'][$key]);
						$thumnailTmpName = $_FILES['video']['tmp_name']['video_thumbnail'][$key];
						$targetFile = $targetDirectory . $thumbnailName;
						if( move_uploaded_file($thumnailTmpName, $targetFile) ) {
							if( $video_uploaded == 1 ) {
								$query_to_execute .= ", thumbnail = '" . $this->db->escape($thumbnailName) . "'";
							}
						}
					}
					if( $query_to_execute != '' ) {
						$this->db->query($query_to_execute);
					}
				}
			}
		}


		if( isset($_POST['market_prices']) && $_POST['market_prices'] != '' && is_array($_POST['market_prices']) && count($_POST['market_prices']) > 0 ) {
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "products_market_prices WHERE product_id = '" . (int)$product_id . "'");
			foreach ($_POST['market_prices'] as $key => $each_market) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "products_market_prices SET
					product_id = '" . (int)$product_id . "',
					market_id = '" . (int)$each_market['market_id'] . "',
					price = '" . $each_market['price'] . "'
				");
			}
		}


		

		// if (isset($_FILES['product_pdf']) && is_array($_FILES['product_pdf']) && isset($_FILES['product_pdf']['name']) && $_FILES['product_pdf']['name'] != '') {
		// 	$targetDirectory = DIR_IMAGE . "catalog/custom/products_pdfs/";
		// 	$uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['product_pdf']['name']);
		// 	$uploadedFile = $_FILES['product_pdf']['tmp_name'];
		// 	$targetFile = $targetDirectory . $uniqueFilename;
		// 	if( move_uploaded_file($uploadedFile, $targetFile) ) {
				
		// 		$this->db->query("DELETE FROM " . DB_PREFIX . "product_pdf WHERE product_id = '" . (int)$product_id . "'");

		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "product_pdf SET product_id = '" . (int)$product_id . "', media = '" . $this->db->escape($uniqueFilename) . "' ");
		// 	}
		// }

		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', hs_code = '" . $this->db->escape($data['hs_code']) . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

	
		$color_size_combination_array = array();
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();
						foreach ($product_option['product_option_value'] as $product_option_value) {

							$query_to_update = "INSERT INTO " . DB_PREFIX . "product_option_value SET 
							product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "',
							product_option_id = '" . (int)$product_option_id . "',
							product_id = '" . (int)$product_id . "',
							option_id = '" . (int)$product_option['option_id'] . "',
							option_value_id = '" . (int)$product_option_value['option_value_id'] . "',";
							
							$sizes_selection_array = array();
							if( $product_option['option_id'] == 15 ) {
								if( isset($product_option_value['color_id_combinations']) && isset($product_option_value['color_values_combinations']) ) {
									$color_id_combinations = $product_option_value['color_id_combinations'];
									if( is_array($color_id_combinations) == "array" && count($color_id_combinations) > 0 ) {
										$color_values_combinations = $product_option_value['color_values_combinations'];
										$each_size_of_different_color_collection = 0;
										foreach ($color_id_combinations as $key => $value) {
											
											
											$color_availablility = 0;
											foreach ($data['product_option'] as $product_option_for_colors) {
												foreach ($product_option_for_colors['product_option_value'] as $product_option_value_color_matching) {
													if( $product_option_for_colors['option_id'] == 14 ) {
														if( $product_option_value_color_matching['option_value_id'] == $value ) {
															$color_availablility = 1;
														}
													}
												}
											}

										 	if( $value > 0 && $color_availablility == 1 ) {
												$sizes_selection_array[$key] = [
													"size_selection_id" => $value,
													"size_selection_value" => $color_values_combinations[$key]
												];
												$each_size_of_different_color_collection += $color_values_combinations[$key];
											}
										}
										$color_size_combination_array[] = $sizes_selection_array;
										$query_to_update .= "quantity = '" . (int)$each_size_of_different_color_collection. "',";
									} else {
										$query_to_update .= "quantity = '" . (int)$product_option_value['quantity'] . "',";
						}
								} else {
									// $query_to_update .= "quantity = '" . (int)$product_option_value['quantity'] . "',";
								}
							} else {
								// $query_to_update .= "quantity = '" . (int)$product_option_value['quantity'] . "',";
							}

							$query_to_update .= "subtract = '" . (int)$product_option_value['subtract'] . "',
							price = '" . (float)$product_option_value['price'] . "',
							price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "',
							points = '" . (int)$product_option_value['points'] . "',
							points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "',
							weight = '" . (float)$product_option_value['weight'] . "',
							weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "',
							option_image = '" . $this->db->escape($product_option_value['option_image']) . "'
							, colors_selection_array = '" . $this->db->escape(json_encode($sizes_selection_array)) . "'
							";
							$this->db->query($query_to_update);
						}
						
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		
		if( isset($color_size_combination_array) && count($color_size_combination_array) > 0 ) {
			$total_quantity = 0;
			foreach ($color_size_combination_array as $subArray1) {
				foreach ($subArray1 as $subArray2) {
					if( $subArray2['size_selection_value'] ) {
						$total_quantity += (int)$subArray2['size_selection_value'];
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET 
							quantity = quantity + '" . (int)$subArray2['size_selection_value'] . "'
							WHERE
							product_id = '" . (int)$product_id . "' AND
							option_value_id = '" . (int)$subArray2['size_selection_id'] . "'
						");
					}
				}
			}
			$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . $this->db->escape($total_quantity) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int)$product_id);

		if (isset($data['product_recurring'])) {
			foreach ($data['product_recurring'] as $product_recurring) {
				$query = $this->db->query("SELECT `product_id` FROM `" . DB_PREFIX . "product_recurring` WHERE `product_id` = '" . (int)$product_id . "' AND `customer_group_id` = '" . (int)$product_recurring['customer_group_id'] . "' AND `recurring_id` = '" . (int)$product_recurring['recurring_id'] . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = '" . (int)$product_id . "', `customer_group_id` = '" . (int)$product_recurring['customer_group_id'] . "', `recurring_id` = '" . (int)$product_recurring['recurring_id'] . "'");
				}				
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "', product_additional_image_color_id = '" . (int)$product_image['product_additional_image_color_option'] . "' ");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				if ((int)$value['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
				}
			}
		}
		
		// SEO URL
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");
		
		if (isset($data['product_seo_url'])) {
			foreach ($data['product_seo_url']as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}
		$this->cache->delete('product');
		
	}

	public function copyProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int)$product_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['product_attribute'] = $this->getProductAttributes($product_id);
			$data['product_description'] = $this->getProductDescriptions($product_id);
			$data['product_discount'] = $this->getProductDiscounts($product_id);
			$data['product_filter'] = $this->getProductFilters($product_id);
			$data['product_image'] = $this->getProductImages($product_id);
			$data['product_option'] = $this->getProductOptions($product_id);
			$data['product_related'] = $this->getProductRelated($product_id);
			$data['product_reward'] = $this->getProductRewards($product_id);
			$data['product_special'] = $this->getProductSpecials($product_id);
			$data['product_category'] = $this->getProductCategories($product_id);
			$data['product_download'] = $this->getProductDownloads($product_id);
			$data['product_layout'] = $this->getProductLayouts($product_id);
			$data['product_store'] = $this->getProductStores($product_id);
			$data['product_recurrings'] = $this->getRecurrings($product_id);

			$this->addProduct($data);
		}
	}

	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_recurring WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE product_id = '" . (int)$product_id . "'");

		$this->cache->delete('product');
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductsByProductIdsList($product_id_list_array) {

		$product_id_array = $product_id_list_array;  // Assuming $yourArray is the name of your array
		$product_id_list_string = implode("','", $product_id_array);
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id IN('" . $product_id_list_string . "') ");
		return $query->rows;
	}

	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}



	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order ASC");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON(pov.option_value_id = ov.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY ov.sort_order ASC");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix'],
					'option_image'           => $product_option_value['option_image'],
					'colors_selection_array'           => $product_option_value['colors_selection_array'],
					'size_selection_array'           => $product_option_value['size_selection_array'],
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductOptionValue($product_id, $product_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductPdf($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_pdf WHERE product_id = '" . (int)$product_id . "' ");

		return $query->rows;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}
	
	public function getProductSeoUrls($product_id) {
		$product_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $product_seo_url_data;
	}
	
	public function getProductLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	public function getProductRelated($product_id) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getRecurrings($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalProductsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
	
	public function getSizeOptions($product_id) {
        $size_options = array();
		$query = $this->db->query("SELECT pov.product_option_value_id,pov.colors_selection_array,pov.size_selection_array,pov.quantity, pov.option_value_id, pov.product_option_id, od.name AS option_name, ovd.name AS option_value_name
                                   FROM " . DB_PREFIX . "product_option_value pov
                                   LEFT JOIN " . DB_PREFIX . "option_description od ON (pov.option_id = od.option_id)
                                   LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id)
                                   WHERE pov.product_id = '" . (int)$product_id . "'");

        foreach ($query->rows as $row) {
            if (strpos(strtolower($row['option_name']), 'size') !== false) {
                $size_options[] = array(
                    'product_option_value_id' => $row['product_option_value_id'],
					'option_id' => $row['option_value_id'],
                    'option_name' => $row['option_value_name'],
					'option_quantity' => $row['quantity'],
					'selected_colors' => $row['colors_selection_array'],
					'selected_sizes' => $row['size_selection_array'],
                );
            }
        }

        return $size_options;
    }


	public function getOptionNameDescription($product_id) {
        $query = $this->db->query("SELECT name FROM ". DB_PREFIX . "option_value_description WHERE option_value_id = '" . (int)$product_id . "'");
		return @$query->row['name'];
    }

	public function getColorOptions($product_id) {
        $color_options = array();
		$query = $this->db->query("SELECT pov.product_option_value_id,pov.colors_selection_array,pov.size_selection_array,pov.quantity, pov.option_value_id, pov.product_option_id, od.name AS option_name, od.option_id AS option_id_value_main, ovd.name AS option_value_name
                                   FROM " . DB_PREFIX . "product_option_value pov
                                   LEFT JOIN " . DB_PREFIX . "option_description od ON (pov.option_id = od.option_id)
                                   LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id)
                                   WHERE
								   pov.product_id = '" . (int)$product_id . "'
								   AND od.language_id = 1
								   AND ovd.language_id = 1
								   ");

        foreach ($query->rows as $row) {
            // if (strpos(strtolower($row['option_name']), 'coulour') !== false) {
			if ($row['option_id_value_main'] == 14) {
                $color_options[] = array(
                    'option_id' => $row['option_value_id'],
                    'option_name' => $row['option_value_name'],
					'option_quantity' => $row['quantity'],
					'selected_colors' => $row['colors_selection_array'],
					'selected_sizes' => $row['size_selection_array'],
                );
            }
        }

        return $color_options;
    }

	public function getCountriesList() {
		$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE status = 1 ");
		return $country_query->rows;
    }

	public function getCurrenciesList() {
		$currencies_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "currency` WHERE status = 1 ");
		return $currencies_query->rows;
    }

	public function getLanguagesList() {
		$language_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = 1 ");
		return $language_query->rows;
    }

	public function getConversions() {
		$localization_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "localization_preferences` WHERE status = 1 ");
		return $localization_query->rows;
    }

	public function getAllMarkets() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "markets WHERE status = '1' ");
		return $query->rows;
	}

	public function getProductsMarketPrices($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "products_market_prices WHERE product_id = '".$product_id."' ");
		return $query->rows;
	}

	public function getProductVideos($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_video WHERE product_id = '" . (int)$product_id . "' ");
		return $query->rows;
	}
	public function deleteProductVideo($video_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_video WHERE product_video_id = '" . (int)$video_id . "'");
	}

	public function deleteProductPdf($video_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_pdf WHERE product_pdf_id = '" . (int)$video_id . "'");
	}
}