<?php
class ControllerExtensionModuleProductGrouping extends Controller {

    public function install() {
        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/product_grouping');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/product_grouping');
    }

    public function uninstall() {
        $this->load->model('user/user_group');
        $this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE name = 'extension/module/product_grouping'");
    }
    public function index() {
        
        // Load required models and language files
        $this->load->language('extension/module/product_grouping');
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');
        $data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            ),
            array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token'], true)
            )
        );
        $data['error_warning'] = @$this->session->data['error_warning_bulk'];
        $data['success'] = @$this->session->data['success_bulk'];
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['add_new'] = $this->url->link('extension/module/product_grouping/addSaleOptions', 'user_token=' . $this->session->data['user_token'], true);
        $data['add_new_group_url'] = $this->url->link('extension/module/product_grouping/addGroupName', 'user_token=' . $this->session->data['user_token'], true);
        $data['add_new_group_url_product_collection'] = $this->url->link('extension/module/product_grouping/addProductCollection', 'user_token=' . $this->session->data['user_token'], true);
        $data['user_token'] = $this->session->data['user_token'];
        
        $data['edit_group_url_group'] = $this->url->link('extension/module/product_grouping/editGroupNameOptions', 'user_token=' . $this->session->data['user_token'], true);
        $data['edit_group_url'] = $this->url->link('extension/module/product_grouping/editProductCollection', 'user_token=' . $this->session->data['user_token'], true);
        
        $this->session->data['error_warning_bulk'] = "";
        $this->session->data['success_bulk'] = "";

        $this->load->model('catalog/product');
        $this->load->model('catalog/category');

        $data['products'] = $this->model_catalog_product->getProducts();
        $data['categories'] = $this->model_catalog_category->getCategories();

		$data['action'] = $this->url->link('extension/module/product_grouping/addSaleOptionsProcess', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->load->model('extension/module/product_grouping');
        $data['listingData'] = $this->model_extension_module_product_grouping->getListingDataGroupName();
        $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/products_icons_attributes/";

        $data['groupNames'] = $this->model_extension_module_product_grouping->getGroupNames();
        
        $data['collectionListingData'] = $this->model_extension_module_product_grouping->getListingData();
        
        // echo "<pre>";
        //     print_r($data['collectionListingData']);
        // echo "</pre>";
        // die;


        $data['product_url_to_open'] = $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'], true);
        $this->response->setOutput($this->load->view('extension/module/product_grouping/grouping_products_listing', $data));
    }
    
    public function filterCollectionProducts() {
        if (isset($this->request->post['group_id']) && $this->request->post['group_id'] != '') {
            $this->load->model('extension/module/product_grouping');
            $collectionListingData = $this->model_extension_module_product_grouping->getListingDataFiltered($this->request->post['group_id']);
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
        
        $this->load->language('extension/module/product_grouping');
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');
        $data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            ),
            array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token'], true)
            )
        );
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/product_grouping/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/product_grouping/addGroupNameOptionsProcess', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_group_url'] = $this->url->link('extension/module/product_grouping/editGroupNameOptions', 'user_token=' . $this->session->data['user_token'], true);

        $data['add_product_collection_process'] = $this->url->link('extension/module/product_grouping/addProductColelctionProcess', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->load->model('catalog/product');
        $this->load->model('extension/module/product_grouping');
        $data['products'] = $this->model_catalog_product->getProducts();
        $data['groupNames'] = $this->model_extension_module_product_grouping->getGroupNames();
        $data['attribute_name'] = $this->model_extension_module_product_grouping->getAllAttributes();

        $this->response->setOutput($this->load->view('extension/module/product_grouping/product_collection_form_add', $data));
    }

    public function addGroupName() {
        $this->load->language('extension/module/product_grouping');
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');
        $data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            ),
            array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token'], true)
            )
        );
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['cancel'] = $this->url->link('extension/module/product_grouping/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['action'] = $this->url->link('extension/module/product_grouping/addGroupNameOptionsProcess', 'user_token=' . $this->session->data['user_token'], true);

        $data['edit_group_url'] = $this->url->link('extension/module/product_grouping/editGroupNameOptions', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/product_grouping/product_grouping_form_add', $data));
    }

    public function editProductCollection() {
        if( isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0 ) {

            
            $this->load->model('extension/module/product_grouping');
            $this->load->language('extension/module/product_grouping');
            $data['all_group_collection_Data'] = $this->model_extension_module_product_grouping->getDataForProductCollectionEdit($_GET['id']);
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
                    'href' => $this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token'], true)
                )
            );
            $data['button_cancel'] = $this->language->get('button_cancel');
            $data['cancel'] = $this->url->link('extension/module/product_grouping/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
            $data['action'] = $this->url->link('extension/module/product_grouping/editProductColelctionProcess', 'user_token=' . $this->session->data['user_token'], true);
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $data['group_id'] = $_GET['id'];

            $this->load->model('catalog/product');
            $this->load->model('extension/module/product_grouping');
            $data['products'] = $this->model_catalog_product->getProducts();
            $data['groupNames'] = $this->model_extension_module_product_grouping->getGroupNames();
            $this->response->setOutput($this->load->view('extension/module/product_grouping/product_collection_form_edit', $data));

        } else {
            $this->response->redirect($this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token']));
        }
    }

    
    public function editGroupNameOptions() {
        if( isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0 ) {
            $this->load->model('extension/module/product_grouping');
            $grop_name = $this->model_extension_module_product_grouping->getGroupNameById($_GET['id']);

            $this->load->language('extension/module/product_grouping');
            $this->document->setTitle($this->language->get('heading_title'));
            $data['heading_title'] = $this->language->get('heading_title');
            $data['base_url'] = HTTP_CATALOG . "image/catalog/custom/products_icons_attributes/";
            $data['breadcrumbs'] = array(
                array(
                    'text' => $this->language->get('text_home'),
                    'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
                ),
                array(
                    'text' => $this->language->get('heading_title'),
                    'href' => $this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token'], true)
                )
            );
            $data['button_cancel'] = $this->language->get('button_cancel');
            $data['cancel'] = $this->url->link('extension/module/product_grouping/index', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
            $data['action'] = $this->url->link('extension/module/product_grouping/UpdateGroupNameOptionsProcess', 'user_token=' . $this->session->data['user_token'], true);
            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');

            $data['group_name'] = $grop_name['name'];
            $data['icon_image'] = $grop_name['icon_image'];
            $data['description'] = $grop_name['description'];
            $data['group_id'] = $_GET['id'];

            $this->response->setOutput($this->load->view('extension/module/product_grouping/grouping_form_edit', $data));
        } else {
            $this->response->redirect($this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token']));
        }
    }
    
    public function addProductColelctionProcess() {
        $this->load->model('extension/module/product_grouping');
        $last_inserted_id = $this->model_extension_module_product_grouping->addProductColelctionProcess($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Product Collection has been successfully Created.";
        }
        $this->response->redirect($this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token']));
    }

    public function editProductColelctionProcess() {
        $this->load->model('extension/module/product_grouping');
        $last_inserted_id = $this->model_extension_module_product_grouping->editProductColelctionProcess($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Product Collection has been successfully Updated.";
        }
        $this->response->redirect($this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token']));
    }
    public function addGroupNameOptionsProcess() {
        $this->load->model('extension/module/product_grouping');
        $this->model_extension_module_product_grouping->addGroupNameOption($_POST);
        $this->session->data['success_bulk'] = "Icon has been successfully updated.";
        $this->response->redirect($this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token']));
    }

    public function UpdateGroupNameOptionsProcess() {
        $this->load->model('extension/module/product_grouping');
        $last_inserted_id = $this->model_extension_module_product_grouping->updateGroupNameOption($_POST);
        if( $last_inserted_id == 1 ) {
            $this->session->data['success_bulk'] = "Icon has been successfully created.";
        }
        $this->response->redirect($this->url->link('extension/module/product_grouping', 'user_token=' . $this->session->data['user_token']));
    }

    function deleteGroupNameOptions() {
        if( isset($_POST['group_id']) && $_POST['group_id'] != '' && is_numeric($_POST['group_id']) && $_POST['group_id'] > 0 ) {
            $this->load->model('extension/module/product_grouping');
            $this->model_extension_module_product_grouping->deleteGroupName($_POST);
        }
    }

    function deleteGroupCollections() {
        if( isset($_POST['group_id']) && $_POST['group_id'] != '' && is_numeric($_POST['group_id']) && $_POST['group_id'] > 0 ) {
            $this->load->model('extension/module/product_grouping');
            $this->model_extension_module_product_grouping->deleteGroupCollection($_POST);
        }
    }
}