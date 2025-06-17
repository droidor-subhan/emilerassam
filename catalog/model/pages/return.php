<?php
class ModelPagesReturn extends Model {
    public function getContent($content_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_return_refund WHERE id = '".$content_id."' ");
        return $query->row;
    }
}
