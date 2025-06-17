<?php
class ControllerPagesContact extends Controller {
    public function index() {
        $this->load->language('pages/aboutus');
        $this->load->model('pages/contact');
        $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
        $data['base_url'] = HTTP_SERVER . "image/catalog/custom/pages/";

        $content_id = 1;
        if( isset($this->session->data['language']) && $this->session->data['language'] != '' && $this->session->data['language'] =='fr-FR' ) {
            $content_id = 2;
        }
        
        $data['content'] = $this->model_pages_contact->getContent($content_id);
        $this->response->setOutput($this->load->view('pages/contact', $data));
    }
    public function submit_career_form() {
        $this->load->model('pages/contact');
        $response = $this->model_pages_contact->saveCareers($_POST);
        echo $response;
    }   
}