<?php

class msResourceFileTagsProcessor extends modProcessor {


	/**
	 * @return array|string
	 */
	public function process() {
		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->success();
		}

		foreach ($ids as $id) {
			/** @var modProcessorResponse $response */
			$response = $this->modx->runProcessor('mgr/gallery/update',
				array('id' => $id, 'tags' => $this->getProperty('tags')),
				array('processors_path' => MODX_CORE_PATH . 'components/ms2gallery/processors/')
			);
			if ($response && $response->isError()) {
				return $response->getResponse();
			}
		}

		return $this->success();
	}

}

return 'msResourceFileTagsProcessor';