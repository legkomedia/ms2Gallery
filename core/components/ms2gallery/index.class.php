<?php

require_once dirname(__FILE__) . '/model/ms2gallery/ms2gallery.class.php';

abstract class ms2GalleryMainController extends modExtraManagerController {
	/** @var ms2Gallery $ms2gallery */
	public $ms2Gallery;


	public static function getInstance(modX &$modx, $className, array $config = array()) {
		$action = call_user_func(array($className,'getDefaultController'));
		if (isset($_REQUEST['action'])) {
			$action = str_replace(array('../','./','.','-','@'),'',$_REQUEST['action']);
		}
		$className = self::getControllerClassName($action,$config['namespace']);
		$classPath = $config['namespace_path'].'controllers/mgr/'.$action.'.class.php';
		require_once $classPath;
		/** @var modManagerController $controller */
		$controller = new $className($modx,$config);
		return $controller;
	}


	public function initialize() {
		$this->ms2Gallery = new ms2Gallery($this->modx);
		
		$this->modx->regClientCSS($this->ms2Gallery->config['cssUrl'].'mgr/main.css');
        $this->addJavaScript($this->ms2Gallery->config['jsUrl'].'mgr/ms2gallery.js');
        $this->addHtml(str_replace('		', '', '
		<script type="text/javascript">
			ms2Gallery.config = '.$this->modx->toJSON($this->ms2Gallery->config).';
			ms2Gallery.config.connector_url = "'.$this->ms2Gallery->config['connectorUrl'].'";
		</script>'));

		parent::initialize();
	}


	public function getLanguageTopics() {
		return array('ms2gallery:default');
	}


	public function checkPermissions() { return true;}
}