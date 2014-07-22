<?php

class msResourceFileGetListProcessor extends modObjectGetListProcessor {
	public $classKey = 'msResourceFile';
	public $defaultSortField = 'rank';
	public $defaultSortDirection  = 'ASC';
	public $languageTopics = array('default','ms2gallery:default');


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		$c->where(array('resource_id' => $this->getProperty('resource_id')));

		$parent = $this->getProperty('parent');
		if ($parent !== false) {
			$c->where(array('parent' => $parent));
		}

		return $c;
	}


	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object) {
		/* @var msProductFile $object */
		$row = $object->toArray();

		if ($row['type'] != 'image') {
			$row['thumbnail'] = (file_exists(MODX_ASSETS_PATH . 'components/ms2gallery/img/mgr/extensions/'.$row['type'].'.png'))
				? MODX_ASSETS_URL . 'components/minishop2/img/mgr/extensions/'.$row['type'].'.png'
				: MODX_ASSETS_URL . 'components/minishop2/img/mgr/extensions/other.png';
		}
		else {
			$tmp = $object->getFirstThumbnail();
			$row['thumbnail'] = !empty($tmp['url'])
				? $tmp['url']
				: MODX_ASSETS_URL . 'components/ms2gallery/img/mgr/ms2_small.png';
		}
		$row['class'] = empty($row['active'])
			? 'inactive'
			: 'active';

		return $row;
	}
}

return 'msResourceFileGetListProcessor';