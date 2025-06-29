<?php

use Journal3\Opencart\ModuleController;

class ControllerJournal3LayoutNotice extends ModuleController {

	public function index($args) {
		$data = parent::index($args);

		$this->journal3->document->addJs(array('layoutNotice' => array(array(
			'm' => $this->module_id,
			'c' => $this->settings['cookie'] ?? null,
		))));

		return $data;
	}

	/**
	 * @param \Journal3\Options\Parser $parser
	 * @param $index
	 * @return array
	 */
	protected function parseGeneralSettings($parser, $index) {
		$data = array(
			'options' => $parser->getJs(),
		);

		return $data;
	}

	/**
	 * @param \Journal3\Options\Parser $parser
	 * @param $index
	 * @return array
	 */
	protected function parseItemSettings($parser, $index) {
		return array();
	}

	/**
	 * @param \Journal3\Options\Parser $parser
	 * @param $index
	 * @return array
	 */
	protected function parseSubitemSettings($parser, $index) {
		return array();
	}

}
