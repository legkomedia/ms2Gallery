<?php

if(class_exists('ms2GalleryInputRender')) {
	return 'ms2GalleryInputRender';
}

class ms2GalleryInputRender extends modTemplateVarInputRender {
	/**
	 * @param string $value
	 * @param array $params
	 */
	public function process($value,array $params = array()) {
		/** @var ms2Gallery $ms2Gallery */
		$ms2Gallery = $this->modx->getService('ms2gallery','ms2Gallery', MODX_CORE_PATH.'components/ms2gallery/model/ms2gallery/');

		$this->setPlaceholder('config', $this->modx->toJSON($ms2Gallery->config));
		$this->setPlaceholder('connector_url', $ms2Gallery->config['connectorUrl'] );
		$this->setPlaceholder('media_source', $this->modx->toJSON($ms2Gallery->getSourceProperties($value)));

		$resource_id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0);
		$this->setPlaceholder('resource_id',$resource_id);
		$source_id = $this->modx->getOption('ms2gallery_source_default');
		$this->setPlaceholder('source_id',$source_id);
	}


	/**
	 * @return string
	 */
	public function getTemplate() {
		return $this->modx->getOption('core_path').'components/ms2gallery/elements/tv/ms2gallery.input.tpl';
	}

}

return 'ms2GalleryInputRender';