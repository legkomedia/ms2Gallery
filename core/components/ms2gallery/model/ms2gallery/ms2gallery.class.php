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
	function __construct(modX &$modx,array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('ms2gallery.core_path', $config, $this->modx->getOption('core_path').'components/ms2gallery/');
		$assetsUrl = $this->modx->getOption('ms2gallery.assets_url', $config, $this->modx->getOption('assets_url').'components/ms2gallery/');
		$actionUrl = $this->modx->getOption('ms2gallery.action_url', $config, $assetsUrl.'action.php');
		$connectorUrl = $assetsUrl.'connector.php';
		$pageSize = $this->modx->getOption('ms2gallery_page_size', null, 20);

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl
			,'cssUrl' => $assetsUrl.'css/'
			,'jsUrl' => $assetsUrl.'js/'
			,'imagesUrl' => $assetsUrl.'images/'
			,'customPath' => $corePath.'custom/'

			,'connectorUrl' => $connectorUrl
			,'actionUrl' => $actionUrl

			,'corePath' => $corePath
			,'modelPath' => $corePath.'model/'
			,'ctx' => 'web'
			,'json_response' => false

			,'templatesPath' => $corePath.'elements/templates/'
			,'pageSize' => $pageSize
		),$config);

		$this->modx->addPackage('ms2gallery',$this->config['modelPath']);
		$this->modx->lexicon->load('ms2gallery:default');
	}


	/**
	/* Method for transform array to placeholders
	 *
	 * @var array $array With keys and values
	 * @return array $array Two nested arrays With placeholders and values
	 * */
	public function makePlaceholders(array $array = array(), $prefix = '') {
		$result = array(
			'pl' => array()
			,'vl' => array()
		);
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$result = array_merge_recursive($result, $this->makePlaceholders($v, $k.'.'));
			}
			else {
				$result['pl'][$prefix.$k] = '[[+'.$prefix.$k.']]';
				$result['vl'][$prefix.$k] = $v;
			}
		}
		return $result;
	}


	/** Method loads custom classes from specified directory
	 *
	 * @var string $dir Directory for load classes
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
	 * @return mixed $html Parsed html
	 */
	public function processTags($html, $maxIterations = 10) {
		$this->modx->getParser()->processElementTags('', $html, false, false, '[[', ']]', array(), $maxIterations);
		$this->modx->getParser()->processElementTags('', $html, true, true, '[[', ']]', array(), $maxIterations);
		return $html;
	}


	/** Function for formatting dates
	 *
	 * @param string $date Source date
	 * @return string $date Formatted date
	 * */
	public function formatDate($date = '') {
		$df = $this->modx->getOption('ms2gallery_date_format', null, '%d.%m.%Y %H:%M');
		return (!empty($date) && $date !== '0000-00-00 00:00:00') ? strftime($df, strtotime($date)) : '&nbsp;';
	}


	/**
	 * @param string $ctx
	 * @param $source
	 *
	 * @return bool|null|object
	 */
	public function initializeMediaSource($ctx = '', $source) {
		if  ($this->mediaSource = $this->modx->getObject('sources.modMediaSource', $source)) {
			$this->mediaSource->set('ctx', $ctx);
			$this->mediaSource->initialize();

			return $this->mediaSource;
		}
		else {
			return false;
		}
	}


	/**
	 * @param $resource_id
	 */
	public function rankResourceImages($resource_id) {
		$q = $this->modx->newQuery('msResourceFile', array('resource_id' => $resource_id, 'parent' => 0, 'type' => 'image'));
		$q->select('id');
		$q->sortby('rank ASC, createdon', 'ASC');

		if ($q->prepare() && $q->stmt->execute()) {
			$sql = '';
			$table = $this->modx->getTableName('msResourceFile');
			if ($ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN)) {
				foreach ($ids as $k => $id) {
					$sql .= "UPDATE {$table} SET `rank` = '{$k}' WHERE `type` = 'image' AND (`id` = '{$id}' OR `parent` = '{$id}');";
				}
			}
			$sql .= "ALTER TABLE {$table} ORDER BY `rank` ASC;";
			$this->modx->exec($sql);
		}
	}


	/**
	 * @param int $resource_id
	 *
	 * @return mixed
	 */
	public function updateResourceImage($resource_id = 0) {
		/* @var msResourceFile $file*/
		if ($resource_id && $file = $this->modx->getObject('msResourceFile', array('resource_id' => $resource_id, 'parent' => 0, 'rank' => 0, 'type' => 'image'))) {
			$this->rankResourceImages($resource_id);
			$thumb = $file->getFirstThumbnail();
			$arr = array(
				'image' => $file->get('url'),
				'thumb' => !empty($thumb['url'])
						? $thumb['url']
						: '',
			);
		}
		else {
			$arr = array(
				'image' => null,
				'thumb' => null,
			);
		}

		return $arr['thumb'];
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
}

