<?php
class ModelPagesSustainability extends Model {
    public function getContent($content_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_sustainability WHERE id = '".$content_id."' ");
        return $query->row;
    }
}
