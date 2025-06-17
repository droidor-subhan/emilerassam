<?php
class ModelPagesTerms extends Model {
    public function getContent($content_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_term_condition WHERE id = '".$content_id."' ");
        return $query->row;
    }
}
