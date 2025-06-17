<?php
class ModelExtensionModuleCustomOptions extends Model {
    public function addOption($data) {

        $last_inserted_id = 0;
        if( isset($data) && $data != '' && is_array($data) && count($data) > 0 ) {
            if( isset($data['product_id']) && $data['product_id'] != '' && is_array($data['product_id']) && count($data['product_id']) > 0 ) {
                $this->load->model('catalog/product');
                
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_bulk_special SET
                discount_type = '" . $this->db->escape($_POST['discount_type']) . "',
                discount_value = '" . $this->db->escape($_POST['discount_value']) . "'
                ");
                $last_inserted_id = $this->db->getLastId();
                
                foreach ($data['product_id'] as $key => $each_product) {
                    $allproducts_prices = $this->model_catalog_product->getProduct($each_product);
                    $product_price = $allproducts_prices['price'];
                    
                    $discounted_value = $_POST['discount_value'];
                    if( $_POST['discount_type'] == "percentage" ) {
                        $discounted_value = ($_POST['discount_value']/100) * $product_price;
                    }

                    if( ($product_price - $discounted_value) >= 0 ) {  
                        $discounted_price = $product_price - $discounted_value;
                    } else {
                        $discounted_price = $product_price;
                    }

                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET
                        product_id = '" . $this->db->escape($each_product) . "',
                        customer_group_id = 1,
                        priority = 0,
                        price = '" . $this->db->escape($discounted_price) . "',
                        date_start = '" . date("Y-m-d H:i:s", strtotime($this->db->escape($_POST['start_date']))) . "',
                        date_end = '" . date("Y-m-d H:i:s", strtotime($this->db->escape($_POST['end_date']))) . "',
                        product_bulk_special_id = '".$last_inserted_id."'
                    ");
                }
            }
        }
        return $last_inserted_id;
    }
    public function getListingData() {
        $bulk_sale_array = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_bulk_special ORDER BY id DESC");
        if( isset($query->rows) && count($query->rows) > 0  ) {
            foreach ($query->rows as $key => $row) {
                $bulk_sale_array[$key]['id'] = $row['id'];
                $bulk_sale_array[$key]['discount_type'] = $row['discount_type'];
                $bulk_sale_array[$key]['discount_value'] = $row['discount_value'];
                if( isset($row['id']) && $row['id'] != '' ) {
                    $query_sale_products = $this->db->query("SELECT " . DB_PREFIX . "product_special.*, " . DB_PREFIX . "product_description.name FROM " . DB_PREFIX . "product_special LEFT JOIN " . DB_PREFIX . "product_description ON (" . DB_PREFIX . "product_special.product_id = " . DB_PREFIX . "product_description.product_id) where product_bulk_special_id = '".$row['id']."' ");
                    if( isset($query_sale_products->rows) && count($query_sale_products->rows) > 0  ) {
                        $date_start = "0000-00-00";
                        $date_end = "0000-00-00";
                        foreach ($query_sale_products->rows as $key_inner => $product_row) {
                            $bulk_sale_array[$key]['products'][] = $product_row['name'];
                            $date_start = $product_row['date_start'];
                            $date_end = $product_row['date_end'];
                        }
                        $bulk_sale_array[$key]['date_start'] = $date_start;
                        $bulk_sale_array[$key]['date_end'] = $date_end;
                    }
                }

            }
        }
        return $bulk_sale_array;
    }

    public function deleteOption() {
        if( isset($_POST['sale_id']) && $_POST['sale_id'] > 0 ) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_bulk_special WHERE id = '".$_POST['sale_id']."' ");
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_bulk_special_id = '".$_POST['sale_id']."' ");
        }
    }
}