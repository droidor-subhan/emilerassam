<?php
class ModelExtensionModuleProductGrouping extends Model {
    public function addGroupNameOption($data) {
        if( isset($data['group_name']) && $data['group_name'] != '' && is_array($data) && count($data) > 0 ) {
            if (isset($_FILES['product_pdf']) && is_array($_FILES['product_pdf']) && isset($_FILES['product_pdf']['name']) && $_FILES['product_pdf']['name'] != '') {
                $targetDirectory = DIR_IMAGE . "catalog/custom/products_icons_attributes/";
                $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['product_pdf']['name']);
                $uploadedFile = $_FILES['product_pdf']['tmp_name'];
                $targetFile = $targetDirectory . $uniqueFilename;
                if( move_uploaded_file($uploadedFile, $targetFile) ) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "collection_grouping_name SET name = '" . $this->db->escape($data['group_name']) . "', icon_image = '" . $this->db->escape($uniqueFilename) . "',  description = '" . $this->db->escape($data['icon_description']) . "'  ");

                }
            }
        }
        return 1;
    }
    public function updateGroupNameOption($data) {
        if( isset($data['group_id']) && $data['group_id'] != '' && is_array($data) && count($data) > 0 ) {
            if( isset($data['group_name']) && $data['group_name'] != '') {
                if (isset($_FILES['product_pdf']) && is_array($_FILES['product_pdf']) && isset($_FILES['product_pdf']['name']) && $_FILES['product_pdf']['name'] != '') {
                    $targetDirectory = DIR_IMAGE . "catalog/custom/products_icons_attributes/";
                    $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['product_pdf']['name']);
                    $uploadedFile = $_FILES['product_pdf']['tmp_name'];
                    $targetFile = $targetDirectory . $uniqueFilename;
                    if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                        $this->db->query("UPDATE " . DB_PREFIX . "collection_grouping_name SET icon_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '".$data['group_id']."' ");
                    }
                }

                $this->db->query("
                    UPDATE " . DB_PREFIX . "collection_grouping_name SET
                        name = '" . $this->db->escape($data['group_name']) . "',
                        description = '" . $this->db->escape($data['icon_description']) . "'
                        WHERE id = '".$data['group_id']."'
                ");
            }
        }
        return 1;
    }

    public function addProductColelctionProcess($data) {
        
        if( isset($data) && $data != '' && is_array($data) && count($data) > 0 ) {

            if( isset($data['product_id']) && $data['product_id'] != '' && is_array($data['product_id']) && count($data['product_id']) > 0 ) {

                if( isset($data['group_id']) && $data['group_id'] != '') {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "collection_grouping_products SET
                        collection_grouping_id = '" . $this->db->escape($data['group_id']) . "',
                        language_id = '" . $this->db->escape($data['language_id']) . "'
                    ");
                    $last_id = $this->db->getLastId();
                    foreach ($data['product_id'] as $key => $each_product) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "collection_grouping_products_list SET
                            product_id = '" . $this->db->escape($each_product) . "',
                            oc_collection_grouping_products_id = '" . $this->db->escape($last_id) . "'
                        ");
                    }
                }
                return 1;
            }
        }
    }

    public function editProductColelctionProcess($data) {
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "collection_grouping_products WHERE id = '".$data['current_id']."' ");
        $this->db->query("DELETE FROM " . DB_PREFIX . "collection_grouping_products_list WHERE oc_collection_grouping_products_id = '".$data['current_id']."' ");

        
        if( isset($data) && $data != '' && is_array($data) && count($data) > 0 ) {
            if( isset($data['group_id']) && $data['group_id'] != '') {
                $this->db->query("INSERT INTO " . DB_PREFIX . "collection_grouping_products SET
                    collection_grouping_id = '" . $this->db->escape($data['group_id']) . "',
                    language_id = '" . $this->db->escape($data['language_id']) . "'
                ");
                $last_id = $this->db->getLastId();
            }
            if( isset($data['product_id']) && $data['product_id'] != '' && is_array($data['product_id']) && count($data['product_id']) > 0 ) {   
                foreach ($data['product_id'] as $key => $each_product) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "collection_grouping_products_list SET
                        product_id = '" . $this->db->escape($each_product) . "',
                        oc_collection_grouping_products_id = '" . $this->db->escape($last_id) . "'
                    ");
                }
            }
        }
        return 1;
    }
    
    public function getListingDataFiltered($groupid) {
        $bulk_sale_array = array();
        if(  $groupid == "all" ){ 
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_grouping_name ORDER BY id DESC");
        } else {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_grouping_name where id = '".$groupid."' ORDER BY id DESC");
        }
        
        if( isset($query->rows) && count($query->rows) > 0  ) {
            foreach ($query->rows as $key => $row) {
                $bulk_sale_array[$key]['id'] = $row['id'];
                $bulk_sale_array[$key]['name'] = $row['name'];
                if( isset($row['id']) && $row['id'] != '' ) {
                    $query_sale_products = $this->db->query("SELECT " . DB_PREFIX . "collection_grouping_products.*, " . DB_PREFIX . "product_description.name FROM " . DB_PREFIX . "collection_grouping_products LEFT JOIN " . DB_PREFIX . "product_description ON (" . DB_PREFIX . "collection_grouping_products.product_id = " . DB_PREFIX . "product_description.product_id) where collection_grouping_id = '".$row['id']."' ");
                    
                    if( isset($query_sale_products->rows) && count($query_sale_products->rows) > 0  ) {
                        foreach ($query_sale_products->rows as $key_inner => $product_row) {
                            $bulk_sale_array[$key]['products'][] = $product_row['name'];
                            $bulk_sale_array[$key]['products_id'][] = $product_row['product_id'];
                        }
                    } else {
                        unset($bulk_sale_array[$key]);
                    }
                }
            }
        }
        return $bulk_sale_array;
    }

    public function getListingDataGroupName() {
        $bulk_sale_array = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_grouping_name ORDER BY id DESC");
        if( isset($query->rows) && count($query->rows) > 0  ) {
            $bulk_sale_array = $query->rows;
        }
        return $bulk_sale_array;
    }

    public function getGroupNameById($group_id) {
        $query = $this->db->query("SELECT name,icon_image,description FROM " . DB_PREFIX . "collection_grouping_name WHERE id = '".$group_id."' ORDER BY id DESC");
        return $query->row;
    }

    public function deleteGroupName() {
        if( isset($_POST['group_id']) && $_POST['group_id'] > 0 ) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "collection_grouping_name WHERE id = '".$_POST['group_id']."' ");
            $this->db->query("DELETE FROM " . DB_PREFIX . "collection_grouping_products WHERE collection_grouping_id = '".$_POST['group_id']."' ");
        }
    }

    public function deleteGroupCollection() {
        if( isset($_POST['group_id']) && $_POST['group_id'] > 0 ) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "collection_grouping_products WHERE id = '".$_POST['group_id']."' ");
            $this->db->query("DELETE FROM " . DB_PREFIX . "collection_grouping_products_list WHERE oc_collection_grouping_products_id = '".$_POST['group_id']."' ");
        }
    }

    public function getListingData() {
        $query_sale_products = $this->db->query("
            SELECT 
                " . DB_PREFIX . "collection_grouping_name.name AS group_name,
                " . DB_PREFIX . "collection_grouping_products.*
            FROM 
                " . DB_PREFIX . "collection_grouping_products
            LEFT JOIN 
                " . DB_PREFIX . "collection_grouping_name 
            ON 
                " . DB_PREFIX . "collection_grouping_name.id = " . DB_PREFIX . "collection_grouping_products.collection_grouping_id
        ");

        // echo "<pre>";
        //     print_r($query_sale_products);
        // echo "</pre>";

        if( isset($query_sale_products->num_rows) && $query_sale_products->num_rows > 0 ) {
            foreach ($query_sale_products->rows as $key => $record) {
                if( isset($record['id']) && $record['id'] != '' ) {
                    $get_prdoduct_details = $this->db->query("
                        SELECT 
                            " . DB_PREFIX . "product_description.name,
                            " . DB_PREFIX . "collection_grouping_products_list.* 
                        FROM 
                            " . DB_PREFIX . "collection_grouping_products_list
                        LEFT JOIN 
                            " . DB_PREFIX . "product_description
                        ON 
                            " . DB_PREFIX . "collection_grouping_products_list.product_id = " . DB_PREFIX . "product_description.product_id
                        WHERE 
                            " . DB_PREFIX . "product_description.language_id = 1
                            AND 
                            " . DB_PREFIX . "collection_grouping_products_list.oc_collection_grouping_products_id = ".$record['id']."
        
                    ");

                    if( isset($get_prdoduct_details->num_rows) &&  $get_prdoduct_details->num_rows > 0 ) {
                        $product_namesexploded = "";
                        foreach ($get_prdoduct_details->rows as $key_inner => $row) {
                            $product_namesexploded .= $row['name']."<br />";    
                        }
                        $query_sale_products->rows[$key]['products'] = $product_namesexploded;
                    }

                }
            }
        }
        return $query_sale_products->rows;
    }

    public function getGroupNames() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "collection_grouping_name ORDER BY id DESC");
        if( isset($query->num_rows) && $query->num_rows > 0 ) {
            return $query->rows;
        }
    }

    public function getAllAttributes() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE language_id = 1 AND name != '' ORDER BY name ASC");
        if( isset($query->num_rows) && $query->num_rows > 0 ) {
            return $query->rows;
        }
    }

    public function getDataForProductCollectionEdit($id) {
        $get_prdoduct_details = "";
        if( isset($id) && $id != '' ) {
            $get_prdoduct_details = $this->db->query("
                SELECT 
                    " . DB_PREFIX . "product_description.name,
                    " . DB_PREFIX . "collection_grouping_products.language_id,
                    " . DB_PREFIX . "collection_grouping_products.collection_grouping_id,
                    " . DB_PREFIX . "collection_grouping_products_list.* 
                FROM 
                    " . DB_PREFIX . "collection_grouping_products_list
                LEFT JOIN 
                    " . DB_PREFIX . "product_description
                ON 
                    " . DB_PREFIX . "collection_grouping_products_list.product_id = " . DB_PREFIX . "product_description.product_id
                LEFT JOIN 
                    " . DB_PREFIX . "collection_grouping_products
                ON 
                    " . DB_PREFIX . "collection_grouping_products.id = " . DB_PREFIX . "collection_grouping_products_list.oc_collection_grouping_products_id
                WHERE 
                    " . DB_PREFIX . "product_description.language_id = 1
                    AND 
                    " . DB_PREFIX . "collection_grouping_products_list.oc_collection_grouping_products_id = ".$id."
            ");
        }
        return $get_prdoduct_details->rows;

    }
}