<?php
class ModelExtensionModulePages extends Model {
    
    
    public function shipping_delivery_page_update($data) {


        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_shipping_delivery SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_shipping_delivery SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }
        
        $this->db->query(" UPDATE " . DB_PREFIX . "page_shipping_delivery SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_shipping_delivery SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description_fr'])) . "'
        WHERE id = '2'");
        return 1;
    }

    public function return_refund_page_update($data) {


        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_return_refund SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_return_refund SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }
        
        $this->db->query(" UPDATE " . DB_PREFIX . "page_return_refund SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_return_refund SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description_fr'])) . "'
        WHERE id = '2'");
        return 1;
    }

    public function privacy_page_update($data) {


        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_privacy SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_privacy SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }
        
        $this->db->query(" UPDATE " . DB_PREFIX . "page_privacy SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_privacy SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description_fr'])) . "'
        WHERE id = '2'");
        return 1;
    }
    
    public function faq_edit_page_update($data) {


        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_faq SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_faq SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }
        
        $this->db->query(" UPDATE " . DB_PREFIX . "page_faq SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_faq SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description_fr'])) . "'
        WHERE id = '2'");

        return 1;

    }

    public function sustainability_edit_page_update($data) {

        
        if (isset($_FILES['company_image_2']) && is_array($_FILES['company_image_2']) && isset($_FILES['company_image_2']['name']) && $_FILES['company_image_2']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_2']['name']);
            $uploadedFile = $_FILES['company_image_2']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_sustainability SET company_image_2 = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        

        if (isset($_FILES['company_image_2_fr']) && is_array($_FILES['company_image_2_fr']) && isset($_FILES['company_image_2_fr']['name']) && $_FILES['company_image_2_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_2_fr']['name']);
            $uploadedFile = $_FILES['company_image_2_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_sustainability SET company_image_2 = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }


        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_sustainability SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_sustainability SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }
        
        $this->db->query(" UPDATE " . DB_PREFIX . "page_sustainability SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_sustainability SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description_fr'])) . "'
        WHERE id = '2'");


        $this->db->query(" UPDATE " . DB_PREFIX . "page_sustainability SET 
            sec_2_description = '" . $this->db->escape(stripslashes($data['section_2_description'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_sustainability SET 
            sec_2_description = '" . $this->db->escape(stripslashes($data['section_2_description_fr'])) . "'
        WHERE id = '2'");

        return 1;


    }


    public function holdings_edit_page_update($data) {

        // echo "<pre>";
            // print_r($_POST);
            // print_r($_FILES);
        // echo "</pre>";

        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '2' ");
            }
        }

        

        if (isset($_FILES['main_image']) && is_array($_FILES['main_image']) && isset($_FILES['main_image']['name']) && $_FILES['main_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['main_image']['name']);
            $uploadedFile = $_FILES['main_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET main_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['main_image_fr']) && is_array($_FILES['main_image_fr']) && isset($_FILES['main_image_fr']['name']) && $_FILES['main_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['main_image_fr']['name']);
            $uploadedFile = $_FILES['main_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET main_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '2' ");
            }
        }

        



        if (isset($_FILES['image_foundation']) && is_array($_FILES['image_foundation']) && isset($_FILES['image_foundation']['name']) && $_FILES['image_foundation']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_foundation']['name']);
            $uploadedFile = $_FILES['image_foundation']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET image_foundation = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['image_foundation_fr']) && is_array($_FILES['image_foundation_fr']) && isset($_FILES['image_foundation_fr']['name']) && $_FILES['image_foundation_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_foundation_fr']['name']);
            $uploadedFile = $_FILES['image_foundation_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET image_foundation = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '2' ");
            }
        }

        
        
        
        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
        sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description'])) . "'
        WHERE id = '1'");
        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
        sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description_fr'])) . "'
        WHERE id = '2'");
        
        
        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
        sec_2_description = '" . $this->db->escape(stripslashes($data['section_2_description'])) . "'
        WHERE id = '1'");
        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
        sec_2_description = '" . $this->db->escape(stripslashes($data['section_2_description_fr'])) . "'
        WHERE id = '2'");


        if (isset($_FILES['image_1_eng']) && is_array($_FILES['image_1_eng']) && isset($_FILES['image_1_eng']['name']) && $_FILES['image_1_eng']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_1_eng']['name']);
            $uploadedFile = $_FILES['image_1_eng']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET image_1 = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['image_2_eng']) && is_array($_FILES['image_2_eng']) && isset($_FILES['image_2_eng']['name']) && $_FILES['image_2_eng']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_2_eng']['name']);
            $uploadedFile = $_FILES['image_2_eng']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET image_2 = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }


        if (isset($_FILES['image_3_eng']) && is_array($_FILES['image_3_eng']) && isset($_FILES['image_3_eng']['name']) && $_FILES['image_3_eng']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_3_eng']['name']);
            $uploadedFile = $_FILES['image_3_eng']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET image_3 = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['image_4_eng']) && is_array($_FILES['image_4_eng']) && isset($_FILES['image_4_eng']['name']) && $_FILES['image_4_eng']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_4_eng']['name']);
            $uploadedFile = $_FILES['image_4_eng']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET image_4 = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }


        if (isset($_FILES['image_1_fr']) && is_array($_FILES['image_1_fr']) && isset($_FILES['image_1_fr']['name']) && $_FILES['image_1_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_1_fr']['name']);
            $uploadedFile = $_FILES['image_1_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET image_1 = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '2' ");
            }
        }

        if (isset($_FILES['image_2_fr']) && is_array($_FILES['image_2_fr']) && isset($_FILES['image_2_fr']['name']) && $_FILES['image_2_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_2_fr']['name']);
            $uploadedFile = $_FILES['image_2_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET image_2 = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '2' ");
            }
        }


        if (isset($_FILES['image_3_fr']) && is_array($_FILES['image_3_fr']) && isset($_FILES['image_3_fr']['name']) && $_FILES['image_3_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_3_fr']['name']);
            $uploadedFile = $_FILES['image_3_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET image_3 = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '2' ");
            }
        }

        if (isset($_FILES['image_4_fr']) && is_array($_FILES['image_4_fr']) && isset($_FILES['image_4_fr']['name']) && $_FILES['image_4_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_4_fr']['name']);
            $uploadedFile = $_FILES['image_4_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET image_4 = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '2' ");
            }
        }
        

        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
            description1 = '" . $this->db->escape(stripslashes($data['description1_eng'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
            description1 = '" . $this->db->escape(stripslashes($data['description1_fr'])) . "'
        WHERE id = '2'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
            description2 = '" . $this->db->escape(stripslashes($data['description2_eng'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
            description2 = '" . $this->db->escape(stripslashes($data['description2_fr'])) . "'
        WHERE id = '2'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
            description3 = '" . $this->db->escape(stripslashes($data['description3_eng'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
            description3 = '" . $this->db->escape(stripslashes($data['description3_fr'])) . "'
        WHERE id = '2'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
            description4 = '" . $this->db->escape(stripslashes($data['description4_eng'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
            description4 = '" . $this->db->escape(stripslashes($data['description4_fr'])) . "'
        WHERE id = '2'");
        
        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
            section_3_description = '" . $data['section_3_description_eng'] . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_holdings SET 
            section_3_description = '" . $this->db->escape(stripslashes($data['section_3_description_fr'])) . "'
        WHERE id = '2'");
        

        // if (isset($_FILES['company_image_2_fr']) && is_array($_FILES['company_image_2_fr']) && isset($_FILES['company_image_2_fr']['name']) && $_FILES['company_image_2_fr']['name'] != '') {
        //     $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
        //     $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_2_fr']['name']);
        //     $uploadedFile = $_FILES['company_image_2_fr']['tmp_name'];
        //     $targetFile = $targetDirectory . $uniqueFilename;
        //     if( move_uploaded_file($uploadedFile, $targetFile) ) {       
        //         $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET company_image_2 = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
        //     }
        // }


        // if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
        //     $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
        //     $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
        //     $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
        //     $targetFile = $targetDirectory . $uniqueFilename;
        //     if( move_uploaded_file($uploadedFile, $targetFile) ) {       
        //         $this->db->query("UPDATE " . DB_PREFIX . "page_holdings SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
        //     }
        // }
        return 1;

    }

    
    public function careers_edit_page_update($data) {
       
        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_careers SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_careers SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }

        $this->db->query(" UPDATE " . DB_PREFIX . "page_careers SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_careers SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description_fr'])) . "'
        WHERE id = '2'");
        return 1;
    }


    public function contactus_edit_page_update($data) {
       
        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_contactus SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_contactus SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }

        $this->db->query(" UPDATE " . DB_PREFIX . "page_contactus SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_contactus SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description_fr'])) . "'
        WHERE id = '2'");
        return 1;
    }



    public function clients_edit_page_update($data) {
        
        if( isset($_FILES['all_clients']['name']) ) {
            for ($i=1; $i <= count($_FILES['all_clients']['name']); $i++) {
                if (isset($_FILES['all_clients']['name']) && is_array($_FILES['all_clients']['name']) && isset($_FILES['all_clients']['name'][$i]) && $_FILES['all_clients']['name'][$i] != '') {
                    $targetDirectory = DIR_IMAGE . "catalog/custom/pages/clients/";
                    $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['all_clients']['name'][$i]);
                    $uploadedFile = $_FILES['all_clients']['tmp_name'][$i];
                    $targetFile = $targetDirectory . $uniqueFilename;
                    if( move_uploaded_file($uploadedFile, $targetFile) ) {                        
                        $this->db->query("UPDATE " . DB_PREFIX . "page_all_client_logos SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE page_clients_id = '1' AND id = '".$i."' ");
                        echo "UPDATE " . DB_PREFIX . "page_all_client_logos SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE page_clients_id = '1' AND id = '".$i."' ";

                    }
                }
            }
        }


        for ($i=1; $i <= count($_POST['client']); $i++) {

            if (isset($_FILES['client']['name']) && is_array($_FILES['client']['name'][$i]) && isset($_FILES['client']['name'][$i]['company_image']) && $_FILES['client']['name'][$i]['company_image'] != '') {
                $targetDirectory = DIR_IMAGE . "catalog/custom/pages/clients/";
                $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['client']['name'][$i]['company_image']);
                $uploadedFile = $_FILES['client']['tmp_name'][$i]['company_image'];
                $targetFile = $targetDirectory . $uniqueFilename;
                if( move_uploaded_file($uploadedFile, $targetFile) ) {
                    $this->db->query("UPDATE " . DB_PREFIX . "page_clients_logos_and_descriptions SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE page_clients_id = '1' AND id = '".$i."' ");
                }
            }

            $this->db->query(" UPDATE " . DB_PREFIX . "page_clients_logos_and_descriptions SET sec_1_description = '" . $this->db->escape(stripslashes($_POST['client'][$i]['description'])) . "',   sub_2_description = '" . $this->db->escape(stripslashes($_POST['client'][$i]['sub_description'])) . "' WHERE page_clients_id = '1' AND id = '".$i."' ");
        }
        
        for ($i=8; $i <= 14; $i++) {
            if (isset($_FILES['client_fr']['name']) && is_array($_FILES['client_fr']['name'][$i]) && isset($_FILES['client_fr']['name'][$i]['company_image']) && $_FILES['client_fr']['name'][$i]['company_image'] != '') {
                $targetDirectory = DIR_IMAGE . "catalog/custom/pages/clients/";
                $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['client_fr']['name'][$i]['company_image']);
                $uploadedFile = $_FILES['client_fr']['tmp_name'][$i]['company_image'];
                $targetFile = $targetDirectory . $uniqueFilename;
                if( move_uploaded_file($uploadedFile, $targetFile) ) {
                    $this->db->query("UPDATE " . DB_PREFIX . "page_clients_logos_and_descriptions SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE page_clients_id = '2' AND id = '".$i."' ");
                }
            }

            $this->db->query(" UPDATE " . DB_PREFIX . "page_clients_logos_and_descriptions SET sec_1_description = '" . $this->db->escape(stripslashes($_POST['client_fr'][$i]['description'])) . "',   sub_2_description = '" . $this->db->escape(stripslashes($_POST['client_fr'][$i]['sub_description'])) . "' WHERE page_clients_id = '2' AND id = '".$i."' ");
        }


        if (isset($_FILES['company_image_2']) && is_array($_FILES['company_image_2']) && isset($_FILES['company_image_2']['name']) && $_FILES['company_image_2']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/clients/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_2']['name']);
            $uploadedFile = $_FILES['company_image_2']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_clients SET company_image_2 = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        

        if (isset($_FILES['company_image_2_fr']) && is_array($_FILES['company_image_2_fr']) && isset($_FILES['company_image_2_fr']['name']) && $_FILES['company_image_2_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/clients/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_2_fr']['name']);
            $uploadedFile = $_FILES['company_image_2_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_clients SET company_image_2 = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }


        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/clients/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_clients SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/clients/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_clients SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }
        return 1;
    }


    public function term_condition_page_update($data) {


        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_term_condition SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_term_condition SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }
        
        $this->db->query(" UPDATE " . DB_PREFIX . "page_term_condition SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_term_condition SET 
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description_fr'])) . "'
        WHERE id = '2'");

        return 1;

    }

    public function about_us_page_update($data) {

        if (isset($_FILES['company_image']) && is_array($_FILES['company_image']) && isset($_FILES['company_image']['name']) && $_FILES['company_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image']['name']);
            $uploadedFile = $_FILES['company_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_abous_us SET company_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['company_image_fr']) && is_array($_FILES['company_image_fr']) && isset($_FILES['company_image_fr']['name']) && $_FILES['company_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['company_image_fr']['name']);
            $uploadedFile = $_FILES['company_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_abous_us SET company_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }
        
        $this->db->query(" UPDATE " . DB_PREFIX . "page_abous_us SET 
            description = '" . $this->db->escape(stripslashes($data['page_description'])) . "',
            sub_description = '" . $this->db->escape(stripslashes($data['page_sub_description'])) . "',
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description'])) . "',
            sec_2_description = '" . $this->db->escape(stripslashes($data['section_2_description'])) . "',
            sec_3_description = '" . $this->db->escape(stripslashes($data['section_3_description'])) . "'
        WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_abous_us SET 
            description = '" . $this->db->escape(stripslashes($data['page_description_fr'])) . "',
            sub_description = '" . $this->db->escape(stripslashes($data['page_sub_description_fr'])) . "',
            sec_1_description = '" . $this->db->escape(stripslashes($data['section_1_description_fr'])) . "',
            sec_2_description = '" . $this->db->escape(stripslashes($data['section_2_description_fr'])) . "',
            sec_3_description = '" . $this->db->escape(stripslashes($data['section_3_description_fr'])) . "'
        WHERE id = '2'");


        if (isset($_FILES['image']) && is_array($_FILES['image']) && isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image']['name']);
            $uploadedFile = $_FILES['image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_abous_us SET image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['image_fr']) && is_array($_FILES['image_fr']) && isset($_FILES['image_fr']['name']) && $_FILES['image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['image_fr']['name']);
            $uploadedFile = $_FILES['image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_abous_us SET image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }

        if (isset($_FILES['milestone_image']) && is_array($_FILES['milestone_image']) && isset($_FILES['milestone_image']['name']) && $_FILES['milestone_image']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['milestone_image']['name']);
            $uploadedFile = $_FILES['milestone_image']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_abous_us SET milestone_image = '" . $this->db->escape($uniqueFilename) . "' WHERE id = '1' ");
            }
        }

        if (isset($_FILES['milestone_image_fr']) && is_array($_FILES['milestone_image_fr']) && isset($_FILES['milestone_image_fr']['name']) && $_FILES['milestone_image_fr']['name'] != '') {
            $targetDirectory = DIR_IMAGE . "catalog/custom/pages/";
            $uniqueFilename = time() . '_' . uniqid() . '_' . basename($_FILES['milestone_image_fr']['name']);
            $uploadedFile = $_FILES['milestone_image_fr']['tmp_name'];
            $targetFile = $targetDirectory . $uniqueFilename;
            if( move_uploaded_file($uploadedFile, $targetFile) ) {       
                $this->db->query("UPDATE " . DB_PREFIX . "page_abous_us SET milestone_image = '" . $this->db->escape($uniqueFilename) . "', language_id='4' WHERE id = '2'  ");
            }
        }
        $this->db->query(" UPDATE " . DB_PREFIX . "page_abous_us SET milestone_timeline = '" . $this->db->escape(json_encode($_POST['timeline'])) . "' WHERE id = '1'");
        $this->db->query(" UPDATE " . DB_PREFIX . "page_abous_us SET milestone_timeline = '" . $this->db->escape(json_encode($_POST['timeline_fr'])) . "' WHERE id = '2'");

        
        

        $this->db->query(" UPDATE " . DB_PREFIX . "page_abous_us SET main_content = '" . $this->db->escape(stripslashes($data['main_content'])) . "' WHERE id = '1'");

        $this->db->query(" UPDATE " . DB_PREFIX . "page_abous_us SET main_content = '" . $this->db->escape(stripslashes($data['main_content_fr'])) . "' WHERE id = '2' ");
        return 1;
        die;
    }


    public function about_us_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_abous_us where id = '".$table_id."' ");
        return $query->row;
    }

    public function shipping_delivery_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_shipping_delivery where id = '".$table_id."' ");
        return $query->row;
    }

    public function return_refund_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_return_refund where id = '".$table_id."' ");
        return $query->row;
    }

    public function privacy_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_privacy where id = '".$table_id."' ");
        return $query->row;
    }
    
    public function sustainability_edit_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_sustainability where id = '".$table_id."' ");
        return $query->row;
    }
    
    public function holdings_edit_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_holdings where id = '".$table_id."' ");
        return $query->row;
    }
    
    
    public function careers_edit_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_careers where id = '".$table_id."' ");
        return $query->row;
    }

    public function contactus_edit_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_contactus where id = '".$table_id."' ");
        return $query->row;
    }

    public function clients_edit_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_clients where id = '".$table_id."' ");
        return $query->row;
    }
    public function clients_description_edit_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_clients_logos_and_descriptions where page_clients_id = '".$table_id."' ");
        return $query->rows;
    }
    
    public function clients_all_logos($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_all_client_logos where page_clients_id = '".$table_id."' ");
        return $query->rows;
    }

    public function faq_edit_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_faq where id = '".$table_id."' ");
        return $query->row;
    }

    public function term_condition_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_term_condition where id = '".$table_id."' ");
        return $query->row;
    }

    public function company_page_data($table_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_company where id = '".$table_id."' ");
        return $query->row;
    }
    
    

    
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
    
    public function listing_contactus() {
        // $bulk_sale_array = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_contactus_form ORDER BY id DESC");
        // if( isset($query->rows) && count($query->rows) > 0  ) {
        //     $bulk_sale_array = $query->rows;
        // }
        return $query->rows;
    }

    public function listing_careers() {
        // $bulk_sale_array = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_careers_form ORDER BY id DESC");
        // if( isset($query->rows) && count($query->rows) > 0  ) {
        //     $bulk_sale_array = $query->rows;
        // }
        return $query->rows;
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