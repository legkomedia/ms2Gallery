<?php

class msResourceFile extends xPDOSimpleObject {
	public $file;
	/* @var modPhpThumb $phpThumb */
	public $phpThumb;
	/* @var modMediaSource $mediaSource */
	public $mediaSource;


	/**
	 * @param modMediaSource $mediaSource
	 *
	 * @return bool|string
	 */
	public function prepareSource(modMediaSource $mediaSource = null) {
		if (is_object($this->mediaSource) && $this->mediaSource instanceof modMediaSource) {
			return true;
		}
		elseif ($mediaSource) {
			$this->mediaSource = $mediaSource;
			return true;
		}
		else {
			/* @var modResource $resource */
			if ($resource = $this->xpdo->getObject('modResource', $this->get('resource_id'))) {
				$ctx = $resource->get('context_key');
				$source = $this->get('source');
				$ms2Gallery = $this->xpdo->getService('ms2gallery','ms2Gallery', MODX_CORE_PATH.'components/ms2gallery/model/ms2gallery/');
				$this->mediaSource = $ms2Gallery->initializeMediaSource($ctx, $source);
				if (!$this->mediaSource || !($this->mediaSource instanceof modMediaSource)) {
					return 'Could not initialize media source for resource with id = '.$this->get('resource_id');
				}
				return true;
			}
			else {
				return 'Could not find resource with id = '.$this->get('resource_id');
			}
		}
	}


	/**
	 * @param modMediaSource $mediaSource
	 *
	 * @return bool|string
	 */
	public function generateThumbnails(modMediaSource $mediaSource = null) {
		if ($this->get('type') != 'image' || $this->get('parent') != 0) {return true;}

		$prepare = $this->prepareSource($mediaSource);
		if ($prepare !== true) {return $prepare;}

		$this->file = $this->mediaSource->getObjectContents($this->get('path').$this->get('file'));
		if (!empty($this->mediaSource->errors['file'])) {
			return 'Could not retrieve file "'.$this->path.$this->file.'" from media source. '.$this->mediaSource->errors['file'];
		}

		require_once  MODX_CORE_PATH . 'model/phpthumb/modphpthumb.class.php';
		$properties = $this->mediaSource->getProperties();
		$thumbnails = array();
		if (array_key_exists('thumbnails', $properties) && !empty($properties['thumbnails']['value'])) {
			$thumbnails = $this->xpdo->fromJSON($properties['thumbnails']['value']);
		}

		if (empty($thumbnails)) {
			$thumbnails = array(array(
				'w' => 120
				,'h' => 90
				,'q' => 90
				,'zc' => 'T'
				,'bg' => '000000'
				,'f' => !empty($properties['thumbnailType']['value']) ? $properties['thumbnailType']['value'] : 'jpg'
			));
		}

		foreach ($thumbnails as $options) {
			if (empty($options['f'])) {
				$options['f'] = !empty($properties['thumbnailType']['value']) ? $properties['thumbnailType']['value'] : 'jpg';
			}
			if ($image = $this->makeThumbnail($options)) {
				$this->saveThumbnail($image, $options);
			}
		}

		return true;
	}


	/**
	 * @param array $options
	 *
	 * @return bool|null
	 */
	public function makeThumbnail($options = array()) {
		$phpThumb = new modPhpThumb($this->xpdo);
		$phpThumb->initialize();

		$tf = tempnam(MODX_BASE_PATH, 'ms2g_');
		file_put_contents($tf, $this->file['content']);
		$phpThumb->setSourceFilename($tf);

		foreach ($options as $k => $v) {
			$phpThumb->setParameter($k, $v);
		}

		if ($phpThumb->GenerateThumbnail()) {
			ImageInterlace($phpThumb->gdimg_output, true);
			if ($phpThumb->RenderOutput()) {
				@unlink($phpThumb->sourceFilename);
				@unlink($tf);
				return $phpThumb->outputImageData;
			}
		}
		else {
			$this->xpdo->log(modX::LOG_LEVEL_ERROR, 'Could not generate thumbnail for "'.$this->get('url').'". '.print_r($phpThumb->debugmessages,1));
			return false;
		}
	}


	/**
	 * @param $raw_image
	 * @param array $options
	 *
	 * @return bool
	 */
	public function saveThumbnail($raw_image, $options = array()) {
		$filename = preg_replace('/\..*$/', '', $this->get('file')) . '.' . $options['f'];
		$path = $this->get('path') . $options['w'] .'x'.$options['h'] .'/';

		/* @var msResourceFile $resource_file */
		$resource_file = $this->xpdo->newObject('msResourceFile', array(
			'resource_id' => $this->get('resource_id')
			,'parent' => $this->get('id')
			,'name' => $this->get('name')
			,'file' => $filename
			,'path' => $path
			,'source' => $this->mediaSource->get('id')
			,'type' => $this->get('type')
			,'rank' => $this->get('rank')
			,'createdon' => date('Y-m-d H:i:s')
			,'createdby' => $this->xpdo->user->id
			,'active' => 1
			,'hash' => sha1($raw_image)
			,'properties' => array(
				'size' => strlen($raw_image),
			)
		));

		$tf = tempnam(MODX_BASE_PATH, 'ms2g_');
		file_put_contents($tf, $raw_image);
		$tmp = getimagesize($tf);
		if (is_array($tmp)) {
			$resource_file->set('properties', array_merge($resource_file->get('properties'),
				array(
					'width' => $tmp[0],
					'height' => $tmp[1],
					'bits' => $tmp['bits'],
					'mime' => $tmp['mime'],
				)
			));
		}
		unlink($tf);

		$this->mediaSource->createContainer($resource_file->get('path'), '/');
		$file = $this->mediaSource->createObject(
			$resource_file->get('path')
			,$resource_file->get('file')
			,$raw_image
		);

		if ($file) {
			$resource_file->set('url', $this->mediaSource->getObjectUrl($resource_file->get('path').$resource_file->get('file')));
			$resource_file->save();
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 * @return array|mixed
	 */
	public function getFirstThumbnail() {
		$c = array(
			'resource_id' => $this->get('resource_id')
			,'parent' => $this->get('id')
			,'path:LIKE' => '%'.$this->xpdo->getOption('ms2gallery_thumbnail_size', null, '120x90').'/'
		);

		if (!$this->xpdo->getCount('msResourceFile', $c)) {
			unset($c['path']);
		}

		$q = $this->xpdo->newQuery('msResourceFile', $c);
		$q->limit(1);
		$q->sortby('url', 'ASC');
		$q->select('id,url');

		$res = array();
		if ($q->prepare() && $q->stmt->execute()) {
			$res = $q->stmt->fetch(PDO::FETCH_ASSOC);
		}

		return $res;
	}


	/**
	 * @param array $ancestors
	 *
	 * @return bool
	 */
	public function remove(array $ancestors= array ()) {
		$this->prepareSource();
		if (!$this->mediaSource->removeObject($this->get('path').$this->get('file'))) {
			$this->xpdo->log(xPDO::LOG_LEVEL_ERROR,
				'Could not remove file at "'.$this->get('path').$this->get('file').'": '.$this->mediaSource->errors['file']
			);
		}

		$children = $this->xpdo->getIterator('msResourceFile', array('parent' => $this->get('id')));
		/** @var msResourceFile $child */
		foreach ($children as $child) {
			$child->remove();
		}

		return parent::remove($ancestors);
	}


	/**
	 * Recursive file rename
	 *
	 * @param string $new_name
	 * @param string $old_name
	 *
	 * @return bool
	 */
	public function rename($new_name, $old_name = '') {
		if (empty($old_name)) {
			$old_name = $this->get('file');
		}

		$path = $this->get('path');
		$tmp = explode('.', $old_name);
		$extension = end($tmp);
		$name = preg_replace('/\..*$/', '', $new_name) . '.' . $extension;

		// Processing children
		$children = $this->getMany('Children');
		if (!empty($children)) {
			/* @var msProductFile $child */
			foreach ($children as $child) {
				$child->rename($new_name, $child->get('file'));
			}
		}

		$this->prepareSource();
		if ($this->mediaSource->renameObject($path.$old_name, $name)) {
			$this->set('file', $name);
			$this->set('url', $this->mediaSource->getObjectUrl($path.$name));
			return $this->save();
		}
		else {
			return false;
		}
	}
}