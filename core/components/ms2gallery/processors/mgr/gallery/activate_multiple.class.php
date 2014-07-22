<?php

class msResourceFileActivateMultipleProcessor extends modObjectProcessor {
	public $classKey = 'msResourceFile';
	public $languageTopics = array('ms2gallery:default');


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$ids = $this->getProperty('ids')) {
			return $this->failure($this->modx->lexicon('ms2gallery_err_ns'));
		}

		$separator = $this->getProperty('separator', ',');
		$ids = explode($separator,$ids);

		foreach ($ids as $id) {
			/* @var msResourceFile $file */
			if ($file = $this->modx->getObject('msResourceFile', $id)) {
				$file->set('active', 1);
				$file->save();
			}
		}

		return $this->success();
	}
}

return 'msResourceFileActivateMultipleProcessor';