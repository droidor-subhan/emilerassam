<?php

use Journal3\Opencart\Controller;
use Journal3\Utils\Arr;

class ControllerJournal3ModuleProduct extends Controller {

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('journal3/module');
		$this->load->language('error/permission');
	}

	public function all() {
		try {
			$filters = array(
				'type'   => $this->input(self::GET, 'type'),
				'filter' => $this->input(self::GET, 'filter', ''),
				'sort'   => $this->input(self::GET, 'sort', ''),
				'order'  => $this->input(self::GET, 'order', ''),
				'page'   => $this->input(self::GET, 'page', '1'),
				'limit'  => $this->input(self::GET, 'limit', '10'),
			);

			$this->renderJson(self::SUCCESS, $this->model_journal3_module->all($filters));
		} catch (Exception $e) {
			$this->renderJson(self::ERROR, $e->getMessage());
		}
	}

	public function get() {
		try {
			$id = $this->input(self::GET, 'id');

			$this->renderJson(self::SUCCESS, $this->model_journal3_module->get($id));
		} catch (Exception $e) {
			$this->renderJson(self::ERROR, $e->getMessage());
		}
	}

	public function add() {
		try {
			if (!$this->user->hasPermission('modify', 'journal3/module_product')) {
				throw new Exception($this->language->get('text_permission'));
			}

			$type = $this->input(self::GET, 'type');
			$data = $this->input(self::POST, 'data');

			$this->renderJson(self::SUCCESS, $this->model_journal3_module->add($type, $data));

			$this->journal3->cache->delete();
		} catch (Exception $e) {
			$this->renderJson(self::ERROR, $e->getMessage());
		}
	}

	public function edit() {
		try {
			if (!$this->user->hasPermission('modify', 'journal3/module_product')) {
				throw new Exception($this->language->get('text_permission'));
			}

			$id = $this->input(self::GET, 'id');
			$type = $this->input(self::GET, 'type');
			$data = $this->input(self::POST, 'data');

			$this->renderJson(self::SUCCESS, $this->model_journal3_module->edit($id, $type, $data));

			$this->journal3->cache->delete();
		} catch (Exception $e) {
			$this->renderJson(self::ERROR, $e->getMessage());
		}
	}

	public function copy() {
		try {
			if (!$this->user->hasPermission('modify', 'journal3/module_product')) {
				throw new Exception($this->language->get('text_permission'));
			}

			$id = $this->input(self::GET, 'id');

			$this->renderJson(self::SUCCESS, $this->model_journal3_module->copy($id));

			$this->journal3->cache->delete();
		} catch (Exception $e) {
			$this->renderJson(self::ERROR, $e->getMessage());
		}
	}

	public function remove() {
		try {
			if (!$this->user->hasPermission('modify', 'journal3/module_product')) {
				throw new Exception($this->language->get('text_permission'));
			}

			$id = $this->input(self::GET, 'id');

			$this->renderJson(self::SUCCESS, $this->model_journal3_module->remove($id));

			$this->journal3->cache->delete();
		} catch (Exception $e) {
			$this->renderJson(self::ERROR, $e->getMessage());
		}
	}

	public function slider_transitions() {
		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
		$data['transition'] = Arr::get($this->request->get, 'transition', 'fade');

		$this->renderOutput('journal3/slider_transitions', $data);
	}

	public function categories() {
		$this->load->model('catalog/category');

		$this->renderJson(self::SUCCESS, array_values(array_filter($this->model_catalog_category->getCategories(), function ($category) {
			return $category['parent_id'] == 0;
		})));
	}

	public function attributes() {
		try {
			if (!$this->user->hasPermission('modify', 'journal3/module_product')) {
				throw new Exception($this->language->get('text_permission'));
			}

			$this->model_journal3_module->explodeAttributeValues();

			$this->renderJson(self::SUCCESS);
		} catch (Exception $e) {
			$this->renderJson(self::ERROR, $e->getMessage());
		}
	}

}
