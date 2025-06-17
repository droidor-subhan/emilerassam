<?php
class ControllerPagesClients extends Controller {
    public function index() {
        $this->load->language('pages/aboutus');
        $this->load->model('pages/clients');
        $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
        $data['base_url'] = HTTP_SERVER . "image/catalog/custom/pages/clients/";

        $content_id = 1;
        if( isset($this->session->data['language']) && $this->session->data['language'] != '' && $this->session->data['language'] =='fr-FR' ) {
            $content_id = 2;
        }
        $data['content'] = $this->model_pages_clients->getContent($content_id);

        $data['contentLogo'] = $this->model_pages_clients->getContentLogos($content_id);
        
        $this->response->setOutput($this->load->view('pages/clients', $data));
    }
}
