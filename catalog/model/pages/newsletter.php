<?php
class ModelPagesNewsletter extends Model {
    public function saveNewsletter() {

        $email = $_POST['country_id_user_selection'];
        $check_query = $this->db->query("
            SELECT email 
            FROM " . DB_PREFIX . "page_newsletter_form 
            WHERE email = '$email'
        ");

        if ($check_query->num_rows > 0) {
            echo "2";
        } else {
            $query = $this->db->query("
            INSERT INTO " . DB_PREFIX . "page_newsletter_form 
            SET 
                email = '$email' 
            ");
        }
    }
}
