<?php

class ControllerExtensionModulePages extends Controller {

    public function install() {
        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/pages');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/pages');
    }

    public function uninstall() {
        $this->load->model('user/user_group');
        $this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE name = 'extension/module/pages'");
    }
    public function index() {
        $this->load->language('extension/module/pages');
        $this->document->setTitle($this->language->get('heading_title'));
        
        $data['error_warning'] = @$this->session->data['error_warning_bulk'];
        $data['success'] = @$this->session->data['success_bulk'];
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['add_new'] = $this->url->link('extension/module/pages/addSaleOptions', 'user_token=' . $this->session->data['user_token'], true);
        $data['add_new_group_url'] = $this->url->link('extension/module/pages/addGroupName', 'user_token=' . $this->session->data['user_token'], true);
        $data['add_new_group_url_product_collection'] = $this->url->link('extension/module/pages/addProductCollection', 'user_token=' . $this->session->data['user_token'], true);
        $data['user_token'] = $this->session->data['user_token'];
        
        $data['edit_about_us_page'] = $this->url->link('extension/module/pages/about_us_page_edit', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_shipping_delivery'] = $this->url->link('extension/module/pages/shipping_delivery_edit', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_return_refund'] = $this->url->link('extension/module/pages/return_refund_edit', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_privacy'] = $this->url->link('extension/module/pages/privacy_edit', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_term_condition'] = $this->url->link('extension/module/pages/term_condition_edit', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_faq'] = $this->url->link('extension/module/pages/faq_edit', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_sustainability'] = $this->url->link('extension/module/pages/sustainability_edit', 'user_token=' . $this->session->data['user_token'], true);
        $data['edit_holdings'] = $this->url->link('extension/module/pages/holdings_edit', 'user_token=' . $this->session->data['user_token'], true);

        

        $data['edit_clients'] = $this->url->link('extension/module/pages/clients_edit', 'user_token=' . $this->session->data['user_token'], true);
        
        $data['edit_careers'] = $this->url->link('extension/module/pages/careers_edit', 'user_token=' . $this->session->data['user_token'], true);
        
        $data['edit_contactus'] = $this->url->link('extension/module/pages/contactus_edit', 'user_token=' . $this->session->data['user_token'], true);


        $data['listing_careers'] = $this->url->link('extension/module/pages/listing_careers', 'user_token=' . $this->session->data['user_token'], true);

        $data['listing_contactus'] = $this->url->link('extension/module/pages/listing_contactus', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_group_url'] = $this->url->link('extension/module/pages/editProductCollection', 'user_token=' . $this->session->data['user_token'], true);
        
        $this->session->data['error_warning_bulk'] = "";
        $this->session->data['success_bulk'] = "";

        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        
        $data['products'] = $this->model_catalog_product->getProducts();
        $data['categories'] = $this->model_catalog_category->getCategories();
        
		$data['action'] = $this->url->link('extension/module/pages/addSaleOptionsProcess', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->load->model('extension/module/pages');
        $data['listingData'] = $this->model_extension_module_pages->getListingDataGroupName();
        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['product_url_to_open'] = $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'], true);
        
        $this->response->setOutput($this->load->view('extension/module/pages/listing', $data));
    }

    public function company_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->company_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "About us page has been successfully created.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    
    
    public function listing_contactus() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/careers_edit_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->contactus_edit_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->contactus_edit_page_data(2);


        $data['listingData'] = $this->model_extension_module_pages->listing_contactus();
        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['product_url_to_open'] = $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'], true);
        

        $this->response->setOutput($this->load->view('extension/module/pages/listing_careers', $data));
    
    }

    public function listing_careers() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/careers_edit_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->careers_edit_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->careers_edit_page_data(2);


        $data['listingData'] = $this->model_extension_module_pages->listing_careers();
        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['product_url_to_open'] = $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'], true);
        

        $this->response->setOutput($this->load->view('extension/module/pages/listing_careers', $data));
    
    }

    public function contactus_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/contactus_edit_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->contactus_edit_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->contactus_edit_page_data(2);

        $this->response->setOutput($this->load->view('extension/module/pages/contactus_edit', $data));
    }

    public function careers_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/careers_edit_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->careers_edit_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->careers_edit_page_data(2);

        $this->response->setOutput($this->load->view('extension/module/pages/careers_edit', $data));
    }

    public function clients_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/clients/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/clients_edit_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->clients_edit_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->clients_edit_page_data(2);

        $data['data_client_description_eng'] = $this->model_extension_module_pages->clients_description_edit_page_data(1);
        $data['data_client_description_fr'] = $this->model_extension_module_pages->clients_description_edit_page_data(2);

        $data['data_all_logos_eng'] = $this->model_extension_module_pages->clients_all_logos(1);
        $data['data_all_logos_fr'] = $this->model_extension_module_pages->clients_all_logos(2);

        $this->response->setOutput($this->load->view('extension/module/pages/clients_edit', $data));
    }
    public function holdings_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/holdings_edit_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->holdings_edit_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->holdings_edit_page_data(2);

        $this->response->setOutput($this->load->view('extension/module/pages/holdings_edit', $data));
    }

    public function sustainability_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/sustainability_edit_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->sustainability_edit_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->sustainability_edit_page_data(2);

        $this->response->setOutput($this->load->view('extension/module/pages/sustainability_edit', $data));
    }

    public function faq_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/faq_edit_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->faq_edit_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->faq_edit_page_data(2);

        $this->response->setOutput($this->load->view('extension/module/pages/faq_edit', $data));
    }

    public function term_condition_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/term_condition_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->term_condition_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->term_condition_page_data(2);

        $this->response->setOutput($this->load->view('extension/module/pages/term_condition_edit', $data));
    }

    public function privacy_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/privacy_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->privacy_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->privacy_page_data(2);

        $this->response->setOutput($this->load->view('extension/module/pages/privacy_edit', $data));
    }

    
    
    
    public function contactus_edit_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->contactus_edit_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Contact Us page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function careers_edit_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->careers_edit_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Clients page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function clients_edit_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->clients_edit_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Clients page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function holdings_edit_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->holdings_edit_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Holdings page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }
    public function sustainability_edit_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->sustainability_edit_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Sustainability page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function faq_edit_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->faq_edit_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "terms & Conditions page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function term_condition_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->term_condition_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "terms & Conditions page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function privacy_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->privacy_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Shipping & Delivery page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function return_refund_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/return_refund_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->return_refund_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->return_refund_page_data(2);

        $this->response->setOutput($this->load->view('extension/module/pages/return_refund_edit', $data));
    }

    public function return_refund_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->return_refund_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Shipping & Delivery page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function shipping_delivery_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/shipping_delivery_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->shipping_delivery_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->shipping_delivery_page_data(2);

        $this->response->setOutput($this->load->view('extension/module/pages/shipping_delivery_edit', $data));
    }

    public function about_us_page_edit() {
        $this->load->model('extension/module/pages');
        $this->load->language('extension/module/pages');

        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/about_us_page_update', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $data['data_eng'] = $this->model_extension_module_pages->about_us_page_data(1);
        $data['data_fr'] = $this->model_extension_module_pages->about_us_page_data(2);

        $data['data_eng']['timeline'] = array();
        if( isset($data['data_eng']['milestone_timeline']) && $data['data_eng']['milestone_timeline'] != '' ) {
            $data['data_eng']['timeline'] = json_decode($data['data_eng']['milestone_timeline']);
        }

        $data['data_fr']['timeline'] = array();
        if( isset($data['data_fr']['milestone_timeline']) && $data['data_fr']['milestone_timeline'] != '' ) {
            $data['data_fr']['timeline'] = json_decode($data['data_fr']['milestone_timeline']);
        }

        $this->response->setOutput($this->load->view('extension/module/pages/about_us_edit', $data));
    }

    

    

    public function shipping_delivery_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->shipping_delivery_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Shipping & Delivery page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function about_us_page_update() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->about_us_page_update($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "About us page has been successfully updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function editGroupNameOptions() {
        if( isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0 ) {
            $this->load->model('extension/module/pages');
            $grop_name = $this->model_extension_module_pages->getGroupNameById($_GET['id']);

            $this->load->language('extension/module/pages');
            $this->document->setTitle($this->language->get('heading_title'));
            $data['heading_title'] = $this->language->get('heading_title');
            $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/pages/";
            $data['breadcrumbs'] = array(
                array(
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
                ),
                array(
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token'], true)
                )
            );
            $data['button_cancel'] = $this->language->get('button_cancel');
            $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
            $data['action'] = $this->url->link('extension/module/pages/UpdateGroupNameOptionsProcess', 'user_token=' . $this->session->data['user_token'], true);
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $data['group_name'] = $grop_name['name'];
            $data['icon_image'] = $grop_name['icon_image'];
            $data['description'] = $grop_name['description'];
            $data['group_id'] = $_GET['id'];

            $this->response->setOutput($this->load->view('extension/module/pages/grouping_form_edit', $data));
        } else {
            $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
        }
    }
    
    public function filterCollectionProducts() {
        if (isset($this->request->post['group_id']) && $this->request->post['group_id'] != '') {
            $this->load->model('extension/module/pages');
            $collectionListingData = $this->model_extension_module_pages->getListingDataFiltered($this->request->post['group_id']);
            $html_daat = "";
            if( count($collectionListingData) > 0 ) {
                $counter_loop = 1;
                $product_url_to_open = $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'], true);
                foreach ($collectionListingData as $key => $data) {
                    $html_daat .= '<tr>';
                        $html_daat .= '<td>'.$counter_loop++.'</td>';
                        $html_daat .= '<td>'.$data['name'].'</td>';
                        $html_daat .= '<td>';
                            foreach ($data['products'] as $key_products => $products_data) {
                                if( $key_products > 0 ) {
                                    $html_daat .= " | ";
                                }
                                $html_daat .= '<a href="'.$product_url_to_open.'&product_id='.$data['products_id'][$key_products].'" target="_blank">';
                                    $html_daat .= $products_data;
                                $html_daat .= '</a>';
                            }
                        $html_daat .= '</td>';
                        $html_daat .= '<td>';
                        $html_daat .= '<a href="javascript:void(0);" onclick="deleteGroupCollections('.$data['id'].')" class="btn btn-danger"><i class="fa fa-trash"></i></a>';
                        $html_daat .= '</td>';
                    $html_daat .= '</tr>';
                }
            }
            echo $html_daat;
        }
    }

    public function addProductCollection() {
        
        $this->load->language('extension/module/pages');
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');
        $data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            ),
            array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token'], true)
            )
        );
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/addGroupNameOptionsProcess', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_group_url'] = $this->url->link('extension/module/pages/editGroupNameOptions', 'user_token=' . $this->session->data['user_token'], true);

        $data['add_product_collection_process'] = $this->url->link('extension/module/pages/addProductColelctionProcess', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->load->model('catalog/product');
        $this->load->model('extension/module/pages');
        $data['products'] = $this->model_catalog_product->getProducts();
        $data['groupNames'] = $this->model_extension_module_pages->getGroupNames();
        $data['attribute_name'] = $this->model_extension_module_pages->getAllAttributes();

        $this->response->setOutput($this->load->view('extension/module/pages/product_collection_form_add', $data));
    }

    public function addGroupName() {
        $this->load->language('extension/module/pages');
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');
        $data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            ),
            array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token'], true)
            )
        );
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/pages/addGroupNameOptionsProcess', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_group_url'] = $this->url->link('extension/module/pages/editGroupNameOptions', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/pages/product_grouping_form_add', $data));
    }

    public function editProductCollection() {
        if( isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0 ) {

            
            $this->load->model('extension/module/pages');
            $this->load->language('extension/module/pages');
            $data['all_group_collection_Data'] = $this->model_extension_module_pages->getDataForProductCollectionEdit($_GET['id']);
            $data['current_id'] = $_GET['id'];

            $data['language_id'] = 0;
            $data['collection_grouping_id'] = 0;
            if( isset($data['all_group_collection_Data'][0]['language_id']) ) {
                $data['language_id'] = $data['all_group_collection_Data'][0]['language_id'];
            }

            if( isset($data['all_group_collection_Data'][0]['language_id']) ) {
                $data['collection_grouping_id'] = $data['all_group_collection_Data'][0]['collection_grouping_id'];
            }
            
            $this->document->setTitle($this->language->get('heading_title'));
            $data['heading_title'] = $this->language->get('heading_title');
            $data['breadcrumbs'] = array(
                array(
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
                ),
                array(
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token'], true)
                )
            );
            $data['button_cancel'] = $this->language->get('button_cancel');
            $data['cancel'] = $this->url->link('extension/module/pages/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
            $data['action'] = $this->url->link('extension/module/pages/editProductColelctionProcess', 'user_token=' . $this->session->data['user_token'], true);
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $data['group_id'] = $_GET['id'];

            $this->load->model('catalog/product');
            $this->load->model('extension/module/pages');
            $data['products'] = $this->model_catalog_product->getProducts();
            $data['groupNames'] = $this->model_extension_module_pages->getGroupNames();

            $this->response->setOutput($this->load->view('extension/module/pages/product_collection_form_edit', $data));

        } else {
            $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
        }
    }

    
    
    
    public function addProductColelctionProcess() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->addProductColelctionProcess($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Product Collection has been successfully Created.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    public function editProductColelctionProcess() {
        $this->load->model('extension/module/pages');
        $last_inserted_id = $this->model_extension_module_pages->editProductColelctionProcess($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Product Collection has been successfully Updated.";
        }
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }
    public function addGroupNameOptionsProcess() {
        $this->load->model('extension/module/pages');
        $this->model_extension_module_pages->addGroupNameOption($_POST);
        $this->session->data['success_bulk'] = "Icon has been successfully updated.";
        $this->response->redirect($this->url->link('extension/module/pages', 'user_token=' . $this->session->data['user_token']));
    }

    

    function deleteGroupNameOptions() {
        if( isset($_POST['group_id']) && $_POST['group_id'] != '' && is_numeric($_POST['group_id']) && $_POST['group_id'] > 0 ) {
            $this->load->model('extension/module/pages');
            $this->model_extension_module_pages->deleteGroupName($_POST);
        }
    }

    function deleteGroupCollections() {
        if( isset($_POST['group_id']) && $_POST['group_id'] != '' && is_numeric($_POST['group_id']) && $_POST['group_id'] > 0 ) {
            $this->load->model('extension/module/pages');
            $this->model_extension_module_pages->deleteGroupCollection($_POST);
        }
    }
}