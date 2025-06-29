<?php
class ModelExtensionDOpencartPatchModule extends Model {
    public function addModule($code, $data) {
        if(VERSION >= '2.1.0.0'){
            $this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "'");
        }else{
            $this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(serialize($data)) . "'");
        }
        return $this->db->getLastId();
    }
    
    public function editModule($module_id, $data) {
        if(VERSION >= '2.1.0.0'){
            $this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `setting` = '" . $this->db->escape(json_encode($data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
        }else{
            $this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($data['name']) . "', `setting` = '" . $this->db->escape(serialize($data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
        }
    }

    public function deleteModule($module_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `code` LIKE '%." . (int)$module_id . "'");
    }
        
    public function getModule($module_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");

        if(VERSION >= '2.1.0.0'){
            if ($query->row) {
                return json_decode($query->row['setting'], true);
            } else {
                return array();
            }
        }else{
            if ($query->row) {
                return unserialize($query->row['setting']);
            } else {
                return array(); 
            }
        }
    }
    
    public function getModules() {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` ORDER BY `code`");

        return $query->rows;
    }   
        
    public function getModulesByCode($code) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

        return $query->rows;
    }   
    
    public function deleteModulesByCode($code) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($code) . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "layout_module` WHERE `code` LIKE '" . $this->db->escape($code) . "' OR `code` LIKE '" . $this->db->escape($code . '.%') . "'");
    }   
}