<?php
class ModelPagesShipping extends Model {
    public function getContent($content_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_shipping_delivery WHERE id = '".$content_id."' ");
        return $query->row;
    }
}
