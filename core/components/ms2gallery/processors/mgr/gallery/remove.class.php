<?php


class msResourceFileRemoveProcessor extends modObjectProcessor {
	public $classKey = 'msResourceFile';
	public $languageTopics = array('ms2gallery:default');


	/**
	 * @return array|string
	 */
	public function process() {
		$id = $this->getProperty('id');
		if (empty($id)) return $this->failure($this->modx->lexicon('ms2gallery_err_ns'));

		$resource_id ='';
		/* @var msResourceFile $file */
		if ($file = $this->modx->getObject('msResourceFile', $id)) {
			$resource_id = $file->get('resource_id');
			$file->remove();
		}

		$thumb = $this->modx->ms2Gallery->updateResourceImage($resource_id);
		return $this->success($thumb);
	}
}

return 'msResourceFileRemoveProcessor';