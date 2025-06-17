<?php
class ModelPagesFaq extends Model {
    public function getContent($content_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_faq WHERE id = '".$content_id."' ");
        return $query->row;
    }
}
