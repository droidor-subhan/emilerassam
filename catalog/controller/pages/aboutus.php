<?php
class ControllerPagesAboutUs extends Controller {
    public function index() {
        $this->load->language('pages/aboutus');
        $this->load->model('pages/about_us');
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
        $data['content'] = $this->model_pages_about_us->getContent($content_id);


        $data['content']['timeline'] = array();
        if( isset( $data['content']['milestone_timeline']) &&  $data['content']['milestone_timeline'] != '' ) {
             $data['content']['timeline'] = json_decode( $data['content']['milestone_timeline']);
        }

        // print_r($data['content']['milestone_timeline']);
        // die;

        

        $this->load->model('pages/company');
        // $data['content_company'] = $this->model_pages_about_us->getContent($content_id);
        
        $this->response->setOutput($this->load->view('pages/about_us', $data));
    }
}
