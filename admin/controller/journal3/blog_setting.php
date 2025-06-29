<?php

use Journal3\Opencart\Controller;
use Journal3\Utils\Arr;

class ControllerJournal3BlogSetting extends Controller {

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('journal3/setting');
		$this->load->language('error/permission');
	}

	public function get() {
		try {
			$id = $this->input(self::GET, 'id');

			$this->renderJson(self::SUCCESS, $this->model_journal3_setting->get($id, array(
				'blog',
			)));

			$this->journal3->cache->delete();
		} catch (Exception $e) {
			$this->renderJson(self::ERROR, $e->getMessage());
		}
	}

	public function edit() {
		try {
			if (!$this->user->hasPermission('modify', 'journal3/blog_setting')) {
				throw new Exception($this->language->get('text_permission'));
			}

			$id = $this->input(self::GET, 'id');
			$data = $this->input(self::POST, 'data');

			$this->renderJson(self::SUCCESS, $this->model_journal3_setting->edit($id, array(
				'blog' => Arr::get($data, 'blog', array()),
			)));

			$this->journal3->cache->delete();
		} catch (Exception $e) {
			$this->renderJson(self::ERROR, $e->getMessage());
		}
	}

}
