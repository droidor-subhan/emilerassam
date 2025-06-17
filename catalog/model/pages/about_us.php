<?php
class ModelPagesAboutUs extends Model {
    public function getContent($content_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_abous_us WHERE id = '".$content_id."' ");
        return $query->row;
    }
}
