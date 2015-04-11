<?php

class msResourceFileRemoveMultipleProcessor extends modObjectProcessor {
	public $classKey = 'msResourceFile';
	public $languageTopics = array('ms2gallery:default');

	public function process() {
		$ids = $this->getProperty('ids');
		if (empty($ids)) return $this->failure($this->modx->lexicon('ms2gallery_err_ns'));

		$separator = $this->getProperty('separator',',');
		$ids = explode($separator,$ids);

		$resource_id = 0;
		foreach ($ids as $id) {
			/* @var msResourceFile $file */
			if ($file = $this->modx->getObject('msResourceFile', $id)) {
				$resource_id = $file->get('resource_id');
				$file->remove();
			}
		}

		/** @var ms2Gallery $ms2Gallery */
		if ($ms2Gallery = $this->modx->getService('ms2gallery')) {
			$ms2Gallery->rankResourceImages($resource_id);
		}

		return $this->success();
	}
}
return 'msResourceFileRemoveMultipleProcessor';