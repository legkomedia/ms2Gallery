<?php

class msResourceFileUpdateProcessor extends modObjectUpdateProcessor {
	/* @var msResourceFile $object */
	public $object;
	public $classKey = 'msResourceFile';
	public $languageTopics = array('core:default', 'ms2gallery:default');
	protected $old_name = null;


	/**
	 * @return array|bool|string
	 */
	public function beforeSet() {
		if (!$this->getProperty('id')) {
			return $this->failure($this->modx->lexicon('ms2gallery_err_ns'));
		}

		foreach (array('file', 'name') as $v) {
			$tmp = $this->getProperty($v, null);
			if ($tmp !== null) {
				if (empty($tmp)) {
					$this->addFieldError($v, $this->modx->lexicon('field_required'));
				}
				else {
					$this->setProperty($v, $tmp);
				}
			}
		}

		$this->old_name = $this->object->get('file');
		return parent::beforeSet();
	}


	/**
	 * @return bool
	 */
	public function afterSave() {
		$extension = pathinfo($this->old_name, PATHINFO_EXTENSION);
		$file = preg_replace('#\.' . $extension . '$#i', '', $this->object->get('file'));
		$file .= '.' . $extension;
		if ($this->old_name != $file) {
			$this->object->rename($this->object->get('file'), $this->old_name);
		}
		else {
			$this->object->set('file', $this->old_name);
			$this->object->save();
		}

		$children = $this->object->getMany('Children');
		if (!empty($children)) {
			/* @var msProductFile $child */
			foreach ($children as $child) {
				$child->fromArray(array(
					'name' => $this->object->get('name'),
					'alt' => $this->object->get('alt'),
					'description' => $this->object->get('description'),
					'add' => $this->object->get('add'),
				));
				$child->save();
			}
		}

		return parent::beforeSave();
	}

}

return 'msResourceFileUpdateProcessor';