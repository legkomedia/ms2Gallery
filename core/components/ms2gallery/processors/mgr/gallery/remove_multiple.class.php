<?php

class msResourceFileRemoveMultipleProcessor extends modObjectProcessor {
	public $classKey = 'msResourceFile';
	public $languageTopics = array('ms2gallery:default');

	public function process() {
		$ids = $this->getProperty('ids');
		if (empty($ids)) return $this->failure($this->modx->lexicon('ms2gallery_err_ns'));
		$resource_id = $this->getProperty('resource_id');

		$separator = $this->getProperty('separator',',');
		$ids = explode($separator,$ids);

		foreach ($ids as $id) {
			/* @var msResourceFile $file */
			if ($file = $this->modx->getObject('msResourceFile', $id)) {
				$file->remove();
			}
		}

		$thumb = $this->modx->ms2Gallery->updateResourceImage($resource_id);
		return $this->success($thumb);
	}
}
return 'msResourceFileRemoveMultipleProcessor';