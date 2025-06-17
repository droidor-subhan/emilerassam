<?php
class ModelPagesClients extends Model {
    public function getContent($content_id) {
        // $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_clients WHERE id = '".$content_id."' ");
        $query = $this->db->query("
            SELECT pc.*, pcld.*, pc.company_image as pc_logo
            FROM " . DB_PREFIX . "page_clients pc
            LEFT JOIN " . DB_PREFIX . "page_clients_logos_and_descriptions pcld
            ON pc.id = pcld.page_clients_id
            WHERE pc.id = '" . $content_id . "'
        ");
        return $query->rows;
    }

    public function getContentLogos($content_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_all_client_logos WHERE page_clients_id = '".$content_id."' ");
        return $query->rows;
    }
}
