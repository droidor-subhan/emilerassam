<?php
class ControllerExtensionModuleCustomOptions extends Controller {

    public function install() {
        $this->load->model('user/user_group');
    
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/custom_options');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/custom_options');
    }

    public function uninstall() {
        $this->load->model('user/user_group');
        
        // Note: OpenCart does not provide a built-in method to remove permissions.
        // You would need to execute a database query to remove the permissions.
        $this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE name = 'extension/module/custom_options'");
    }
    public function index() {
        // Load required models and language files
        $this->load->language('extension/module/custom_options');
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');
        $data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            ),
            array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/custom_options', 'user_token=' . $this->session->data['user_token'], true)
            )
        );
        $data['error_warning'] = @$this->session->data['error_warning_bulk'];
        $data['success'] = @$this->session->data['success_bulk'];
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['add_new'] = $this->url->link('extension/module/custom_options/addSaleOptions', 'user_token=' . $this->session->data['user_token'], true);
        $data['user_token'] = $this->session->data['user_token'];
        
        
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');

        $data['products'] = $this->model_catalog_product->getProducts();
        $data['categories'] = $this->model_catalog_category->getCategories();

		$data['action'] = $this->url->link('extension/module/custom_options/addSaleOptionsProcess', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->load->model('extension/module/custom_options');
        $data['listingData'] = $this->model_extension_module_custom_options->getListingData();
        $this->response->setOutput($this->load->view('extension/module/custom_options', $data));
    }

    public function addSaleOptions() {
        
        $this->load->language('extension/module/custom_options');
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');
        $data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            ),
            array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/custom_options', 'user_token=' . $this->session->data['user_token'], true)
            )
        );
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $this->load->model('catalog/product');
        $data['products'] = $this->model_catalog_product->getProducts();
		$data['action'] = $this->url->link('extension/module/custom_options/addSaleOptionsProcess', 'user_token=' . $this->session->data['user_token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('extension/module/custom_options_form_add', $data));
    }
    
    public function addSaleOptionsProcess() {
        $this->load->model('extension/module/custom_options');
        $last_inserted_id = $this->model_extension_module_custom_options->addOption($_POST);
        if( $last_inserted_id ) {
            $this->session->data['success_bulk'] = "Sale has been successfully created.";
        } else {
            $this->session->data['error_warning_bulk'] = "something went wrong, please try again.";
        }
        $this->response->redirect($this->url->link('extension/module/custom_options', 'user_token=' . $this->session->data['user_token']));
    }

    function deleteSaleOptions() {
        if( isset($_POST['sale_id']) && $_POST['sale_id'] != '' && is_numeric($_POST['sale_id']) && $_POST['sale_id'] > 0 ) {
            $this->load->model('extension/module/custom_options');
            $this->model_extension_module_custom_options->deleteOption($_POST);
        }
    }
}