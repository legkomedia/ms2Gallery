<?php

class ms2Gallery {
	/** @var modX $modx */
	public $modx;
	/** @var modMediaSource $mediaSource */
	public $mediaSource;


	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('ms2gallery.core_path', $config, $this->modx->getOption('core_path') . 'components/ms2gallery/');
		$assetsUrl = $this->modx->getOption('ms2gallery.assets_url', $config, $this->modx->getOption('assets_url') . 'components/ms2gallery/');
		$actionUrl = $this->modx->getOption('ms2gallery.action_url', $config, $assetsUrl . 'action.php');
		$connectorUrl = $assetsUrl . 'connector.php';
		$pageSize = $this->modx->getOption('ms2gallery_page_size', null, 20);

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'customPath' => $corePath . 'custom/',

			'connector_url' => $connectorUrl,
			'actionUrl' => $actionUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'ctx' => 'web',
			'json_response' => false,

			'templatesPath' => $corePath . 'elements/templates/',
			'pageSize' => $pageSize,
		), $config);

		$this->modx->addPackage('ms2gallery', $this->config['modelPath']);
		$this->modx->lexicon->load('ms2gallery:default');
	}


	/**
	 * Method for transform array to placeholders
	 *
	 * @var array $array With keys and values
	 * @var string $prefix
	 *
	 * @return array $array Two nested arrays With placeholders and values
	 * */
	public function makePlaceholders(array $array = array(), $prefix = '') {
		$result = array(
			'pl' => array(),
			'vl' => array(),
		);
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$result = array_merge_recursive($result, $this->makePlaceholders($v, $k . '.'));
			}
			else {
				$result['pl'][$prefix . $k] = '[[+' . $prefix . $k . ']]';
				$result['vl'][$prefix . $k] = $v;
			}
		}
		return $result;
	}


	/**
	 * Method loads custom classes from specified directory
	 *
	 * @var string $dir Directory for load classes
	 *
	 * @return void
	 * */
	public function loadCustomClasses($dir) {
		$files = scandir($this->config['customPath'] . $dir);
		foreach ($files as $file) {
			if (preg_match('/.*?\.class\.php$/i', $file)) {
				include_once($this->config['customPath'] . $dir . '/' . $file);
			}
		}
	}


	/**
	 * Collects and processes any set of tags
	 *
	 * @param mixed $html Source code for parse
	 * @param integer $maxIterations
	 *
	 * @return mixed $html Parsed html
	 */
	public function processTags($html, $maxIterations = 10) {
		$this->modx->getParser()->processElementTags('', $html, false, false, '[[', ']]', array(), $maxIterations);
		$this->modx->getParser()->processElementTags('', $html, true, true, '[[', ']]', array(), $maxIterations);
		return $html;
	}


	/**
	 * Function for formatting dates
	 *
	 * @param string $date Source date
	 *
	 * @return string $date Formatted date
	 * */
	public function formatDate($date = '') {
		$df = $this->modx->getOption('ms2gallery_date_format', null, '%d.%m.%Y %H:%M');
		return (!empty($date) && $date !== '0000-00-00 00:00:00')
			? strftime($df, strtotime($date))
			: '&nbsp;';
	}


	/**
	 * @param string $ctx
	 * @param $source
	 *
	 * @return bool|null|object
	 */
	public function initializeMediaSource($ctx = '', $source) {
		if ($this->mediaSource = $this->modx->getObject('sources.modMediaSource', $source)) {
			$this->mediaSource->set('ctx', $ctx);
			$this->mediaSource->initialize();

			return $this->mediaSource;
		}
		else {
			return false;
		}
	}


	/**
	 * Accurate sorting of resource files
	 *
	 * @param $resource_id
	 */
	public function rankResourceImages($resource_id) {
		if (!$this->modx->getOption('ms2gallery_exact_sorting', null, true, true)) {
			return;
		}

		$q = $this->modx->newQuery('msResourceFile', array('resource_id' => $resource_id, 'parent' => 0));
		$q->select('id');
		$q->sortby('rank ASC, createdon', 'ASC');

		if ($q->prepare() && $q->stmt->execute()) {
			$sql = '';
			$table = $this->modx->getTableName('msResourceFile');
			if ($ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN)) {
				foreach ($ids as $k => $id) {
					$sql .= "UPDATE {$table} SET `rank` = '{$k}' WHERE (`id` = {$id} OR `parent` = {$id});";
				}
			}
			$sql .= "ALTER TABLE {$table} ORDER BY `rank` ASC;";
			$this->modx->exec($sql);
		}
	}


	/**
	 * @param $source
	 *
	 * @return array
	 */
	public function getSourceProperties($source = 0) {
		if (empty($source)) {
			$source = $this->modx->getOption('ms2gallery_source_default', null, 1, true);
		}
		$properties = array();
		/* @var $source modMediaSource */
		if ($source = $this->initializeMediaSource('web', $source)) {
			$tmp = $source->getProperties();
			foreach ($tmp as $v) {
				$properties[$v['name']] = $v['value'];
			}
		}

		return $properties;
	}


	/**
	 * Compares MODX version
	 *
	 * @param string $version
	 * @param string $dir
	 *
	 * @return bool
	 */
	public function systemVersion($version = '2.3.0', $dir = '>=') {
		$this->modx->getVersionData();

		return !empty($this->modx->version) && version_compare($this->modx->version['full_version'], $version, $dir);
	}


	/**
	 * @param modManagerController $controller
	 * @param modResource $resource
	 */
	public function loadManagerFiles(modManagerController $controller, modResource $resource) {
		$modx23 = (int)$this->systemVersion();
		$cssUrl = $this->config['cssUrl'] . 'mgr/';
		$jsUrl = $this->config['jsUrl'] . 'mgr/';

		$properties = $resource->getProperties('ms2gallery');
		if (empty($properties['media_source'])) {
			if (!$source_id = $resource->getTVValue('ms2Gallery')) {
				$source_id = $this->modx->getOption('ms2gallery_source_default');
			}
			$resource->setProperties(array('media_source' => $source_id), 'ms2gallery');
			$resource->save();
		}
		else {
			$source_id = $properties['media_source'];
		}

		if (empty($source_id)) {
			$source_id = $this->modx->getOption('ms2gallery_source_default');
		}
		$resource->set('media_source', $source_id);

		$controller->addLexiconTopic('ms2gallery:default');
		$controller->addJavascript($jsUrl . 'ms2gallery.js');
		$controller->addLastJavascript($jsUrl . 'misc/ms2.combo.js');
		$controller->addLastJavascript($jsUrl . 'misc/ms2.utils.js');
		$controller->addLastJavascript($jsUrl . 'misc/plupload/plupload.full.js');
		$controller->addLastJavascript($jsUrl . 'misc/ext.ddview.js');
		$controller->addLastJavascript($jsUrl . 'uploader.grid.js');
		$controller->addLastJavascript($jsUrl . 'gallery.view.js');
		$controller->addLastJavascript($jsUrl . 'gallery.window.js');
		$controller->addLastJavascript($jsUrl . 'gallery.panel.js');
		$controller->addCss($cssUrl . 'main.css');
		if (!$modx23) {
			$controller->addCss($cssUrl . 'font-awesome.min.css');
		}

		$source_config = array();
		/** @var modMediaSource $source */
		if ($source = $this->modx->getObject('modMediaSource', $source_id)) {
			$tmp = $source->getProperties();
			foreach ($tmp as $v) {
				$source_config[$v['name']] = $v['value'];
			}
		}
		$controller->addHtml('
		<script type="text/javascript">
			MODx.modx23 = ' . $modx23 . ';
			ms2Gallery.config = ' . $this->modx->toJSON($this->config) . ';
			ms2Gallery.config.media_source = ' . $this->modx->toJSON($source_config) . ';
		</script>', true);

		if ($this->modx->getOption('ms2gallery_new_tab_mode', null, true)) {
			$controller->addLastJavascript($jsUrl . 'tab.js');
		}
		else {
			$insert = '
				tabs.add({
					xtype: "ms2gallery-page",
					id: "ms2gallery-page",
					title: _("ms2gallery"),
					record: {
						id: ' . $resource->get('id') . ',
						source: ' . $source_id . ',
					}
				});
			';
			if ($this->modx->getCount('modPlugin', array('name' => 'AjaxManager', 'disabled' => false))) {
				$controller->addHtml('
				<script type="text/javascript">
					Ext.onReady(function() {
						window.setTimeout(function() {
							var tabs = Ext.getCmp("modx-resource-tabs");
							if (tabs) {
								' . $insert . '
							}
						}, 10);
					});
				</script>', true);
			}
			else {
				$controller->addHtml('
				<script type="text/javascript">
					Ext.ComponentMgr.onAvailable("modx-resource-tabs", function() {
						var tabs = this;
						tabs.on("beforerender", function() {
							' . $insert . '
						});
					});
				</script>', true);
			}
		}
	}

}

