<?php
class ControllerPagesNewsletter extends Controller {
    public function submit_newsletter_form() {
        $this->load->model('pages/newsletter');
        $response = $this->model_pages_newsletter->saveNewsletter($_POST);
        echo $response;
    }   
}