<?php

class msResourceFileUploadProcessor extends modObjectProcessor {
	/** @var modResource $resource */
	private $resource = 0;
	/** @var modMediaSource $mediaSource */
	public $mediaSource;
	public $languageTopics = array('ms2gallery:default');


	/**
	 * @return bool|null|string
	 */
	public function initialize() {
		/* @var modResource $resource */
		$id = $this->getProperty('id', @$_GET['id']);
		if (!$resource = $this->modx->getObject('modResource', $id)) {
			return $this->modx->lexicon('ms2gallery_err_no_resource');
		}
		$ctx = $resource->get('context_key');
		$properties = $resource->getProperties('ms2gallery');
		$source = $properties['media_source'];

		/** @var ms2Gallery $ms2Gallery */
		if (!$ms2Gallery = $this->modx->getService('ms2gallery')) {
			return 'Could not load class ms2Gallery!';
		}
		elseif (!$this->mediaSource = $ms2Gallery->initializeMediaSource($ctx, $source)) {
			return $this->modx->lexicon('ms2gallery_err_no_source');
		}

		$this->resource = $resource;
		return true;
	}


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$data = $this->handleFile()) {
			return $this->failure($this->modx->lexicon('ms2gallery_err_gallery_ns'));
		}

		$properties = $this->mediaSource->getPropertyList();
		$extension = strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
		$basename = strtolower(pathinfo($data['name'], PATHINFO_BASENAME));
		$basename = preg_replace('#\.' . $extension . '$#i', '', $basename);

		$image_extensions = $allowed_extensions = array();
		if (!empty($properties['imageExtensions'])) {
			$image_extensions = array_map('trim', explode(',', strtolower($properties['imageExtensions'])));
		}
		if (!empty($properties['allowedFileTypes'])) {
			$allowed_extensions = array_map('trim', explode(',', strtolower($properties['allowedFileTypes'])));
		}
		if (!empty($allowed_extensions) && !in_array($extension, $allowed_extensions)) {
			return $this->failure($this->modx->lexicon('ms2gallery_err_gallery_ext'));
		}
		else {
			if (in_array($extension, $image_extensions)) {
				if (empty($data['properties']['height']) || empty($data['properties']['width'])) {
					return $this->failure($this->modx->lexicon('ms2gallery_err_wrong_image'));
				}
				$type = 'image';
			}
			else {
				$type = $extension;
			}
		}
		$hash = sha1($data['stream']);

		if ($this->modx->getOption('ms2gallery_duplicate_check', null, true, true)) {
			if ($this->modx->getCount('msResourceFile', array('resource_id' => $this->resource->id, 'hash' => $hash, 'parent' => 0))) {
				return $this->failure($this->modx->lexicon('ms2gallery_err_gallery_exists'));
			}
		}

		$filename = !empty($properties['imageNameType']) && $properties['imageNameType'] == 'friendly'
			? $this->resource->cleanAlias($basename)
			: $hash;
		$filename .= '.' . $extension;
		$tmp_filename = $filename;
		$i = 1;
		while (true) {
			if (!$count = $this->modx->getCount('msResourceFile', array('resource_id' => $this->resource->id, 'file' => $tmp_filename, 'parent' => 0))) {
				$filename = $tmp_filename;
				break;
			}
			else {
				$pcre = '#(-'.($i - 1).'|)\.'.$extension.'$#';
				$tmp_filename = preg_replace($pcre, "-$i.$extension", $tmp_filename);
				$i ++;
			}
		}

		$rank = isset($properties['imageUploadDir']) && empty($properties['imageUploadDir'])
			? 0
			: $this->modx->getCount('msResourceFile', array('parent' => 0, 'resource_id' => $this->resource->id));

		/* @var msResourceFile $product_file */
		$product_file = $this->modx->newObject('msResourceFile', array(
			'resource_id' => $this->resource->id,
			'parent' => 0,
			'name' => preg_replace('#\.' . $extension . '$#i', '', $data['name']),
			'file' => $filename,
			'path' => $this->resource->id . '/',
			'source' => $this->mediaSource->get('id'),
			'type' => $type,
			'rank' => $rank,
			'createdon' => date('Y-m-d H:i:s'),
			'createdby' => $this->modx->user->id,
			'active' => 1,
			'hash' => $hash,
			'properties' => $data['properties'],
		));

		$this->mediaSource->createContainer($product_file->path, '/');
		$file = $this->mediaSource->createObject(
			$product_file->get('path'),
			$product_file->get('file'),
			$data['stream']
		);

		if ($file) {
			$url = $this->mediaSource->getObjectUrl($product_file->get('path') . $product_file->get('file'));
			$product_file->set('url', $url);
			$product_file->save();

			if (empty($rank)) {
				$imagesTable = $this->modx->getTableName('msResourceFile');
				$sql = "UPDATE {$imagesTable} SET rank = rank + 1 WHERE resource_id ='" . $this->resource->id . "' AND id !='" . $product_file->get('id') . "'";
				$this->modx->exec($sql);
			}

			$generate = $product_file->generateThumbnails($this->mediaSource);
			if ($generate !== true) {
				$this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not generate thumbnails for image with id = ' . $product_file->get('id') . '. ' . $generate);
				return $this->failure($this->modx->lexicon('ms2gallery_err_gallery_thumb'));
			}
			else {
				return $this->success();
			}
		}
		else {
			return $this->failure($this->modx->lexicon('ms2gallery_err_gallery_save') . ': ' . print_r($this->mediaSource->getErrors(), 1));
		}
	}


	/**
	 * @return array|bool
	 */
	public function handleFile() {
		$stream = $name = null;

		$contentType = isset($_SERVER["HTTP_CONTENT_TYPE"]) ? $_SERVER["HTTP_CONTENT_TYPE"] : $_SERVER["CONTENT_TYPE"];

		$file = $this->getProperty('file');
		if (!empty($file) && file_exists($file)) {
			$tmp = explode('/', $file);
			$name = end($tmp);
			$stream = file_get_contents($file);
		}
		elseif (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				$name = $_FILES['file']['name'];
				$stream = file_get_contents($_FILES['file']['tmp_name']);
			}
		}
		else {
			$name = $this->getProperty('name', @$_REQUEST['name']);
			$stream = file_get_contents('php://input');
		}

		if (!empty($stream)) {
			$data = array(
				'name' => $name,
				'stream' => $stream,
				'properties' => array(
					'size' => strlen($stream),
				)
			);

			$tf = tempnam(MODX_BASE_PATH, 'ms2g_');
			file_put_contents($tf, $stream);
			$tmp = @getimagesize($tf);
			unlink($tf);
			if (is_array($tmp)) {
				$data['properties'] = array_merge(
					$data['properties'],
					array(
						'width' => $tmp[0],
						'height' => $tmp[1],
						'bits' => $tmp['bits'],
						'mime' => $tmp['mime'],
					)
				);
			}
			return $data;
		}
		return false;
	}

}

return 'msResourceFileUploadProcessor';