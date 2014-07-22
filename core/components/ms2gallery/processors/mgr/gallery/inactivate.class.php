<?php

class msResourceFileInActivateProcessor extends modObjectProcessor {
	public $classKey = 'msResourceFile';
	public $languageTopics = array('ms2gallery:default');


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$id = $this->getProperty('id')) {
			return $this->failure($this->modx->lexicon('ms2gallery_err_ns'));
		}

		/* @var msResourceFile $file */
		if ($file = $this->modx->getObject('msResourceFile', $id)) {
			$file->set('active', 0);
			$file->save();
		}

		return $this->success();
	}
}

return 'msResourceFileInActivateProcessor';