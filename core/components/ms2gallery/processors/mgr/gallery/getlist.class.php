<?php

class msResourceFileGetListProcessor extends modObjectGetListProcessor {
	public $classKey = 'msResourceFile';
	public $defaultSortField = 'rank';
	public $defaultSortDirection = 'ASC';
	public $languageTopics = array('default', 'ms2gallery:default');


	/**
	 * {@inheritDoc}
	 * @return mixed
	 */
	public function process() {
		$beforeQuery = $this->beforeQuery();
		if ($beforeQuery !== true) {
			return $this->failure($beforeQuery);
		}
		$data = $this->getData();

		return $this->outputArray($data['results'], $data['total']);
	}


	/**
	 * Get the data of the query
	 * @return array
	 */
	public function getData() {
		$data = array();
		$limit = intval($this->getProperty('limit'));
		$start = intval($this->getProperty('start'));

		/* query for chunks */
		$c = $this->modx->newQuery($this->classKey);
		$c = $this->prepareQueryBeforeCount($c);
		$data['total'] = $this->modx->getCount($this->classKey, $c);
		$c = $this->prepareQueryAfterCount($c);
		$c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));

		$sortClassKey = $this->getSortClassKey();
		$sortKey = $this->modx->getSelectColumns($sortClassKey, $this->getProperty('sortAlias', $sortClassKey), '', array($this->getProperty('sort')));
		if (empty($sortKey)) {
			$sortKey = $this->getProperty('sort');
		}
		$c->sortby($sortKey, $this->getProperty('dir'));
		if ($limit > 0) {
			$c->limit($limit, $start);
		}

		$data['results'] = array();
		if ($c->prepare() && $c->stmt->execute()) {
			while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
				$data['results'][] = $this->prepareArray($row);
			}
		}
		else {
			$this->modx->log(modX::LOG_LEVEL_ERROR, print_r($c->stmt->errorInfo(), true));
		}

		return $data;
	}


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		$c->leftJoin('modMediaSource', 'Source');
		$c->leftJoin($this->classKey, 'Thumb', "`{$this->classKey}`.`id` = `Thumb`.`parent`");
		$c->groupby($this->classKey . '.id');
		$c->select('`Source`.`name` as `source_name`');
		$c->select('`Thumb`.`url` as `thumbnail`');

		$c->where(array('resource_id' => $this->getProperty('resource_id')));

		$parent = $this->getProperty('parent');
		if ($parent !== false) {
			$c->where(array('parent' => $parent));
		}
		$query = trim($this->getProperty('query'));
		if (!empty($query)) {
			$c->where(array(
				'file:LIKE' => "%{$query}%",
				'OR:name:LIKE' => "%{$query}%",
				'OR:alt:LIKE' => "%{$query}%",
				'OR:description:LIKE' => "%{$query}%",
				'OR:add:LIKE' => "%{$query}%",
			));
		}
		$tags = array_map('trim', explode(',',$this->getProperty('tags')));
		if (!empty($tags[0])) {
			$tags = implode("','", $tags);
			$c->innerJoin('msResourceFileTag','Tag',"`{$this->classKey}`.`id` = `Tag`.`file_id` AND `Tag`.`tag` IN ('" . $tags . "')");
			$c->groupby($this->classKey . '.id');
			$c->prepare();$this->modx->log(1, $c->toSQL());
		}

		return $c;
	}


	/**
	 * @param array $row
	 *
	 * @return array
	 */
	public function prepareArray(array $row) {

		if (empty($row['thumbnail'])) {
			if ($row['type'] != 'image') {
				$row['thumbnail'] = (file_exists(MODX_ASSETS_PATH . 'components/ms2gallery/img/mgr/extensions/' . $row['type'] . '.png'))
					? MODX_ASSETS_URL . 'components/ms2gallery/img/mgr/extensions/' . $row['type'] . '.png'
					: MODX_ASSETS_URL . 'components/ms2gallery/img/mgr/extensions/other.png';
			}
			else {
				//$row['thumbnail'] = $row['url'];
				$row['thumbnail'] = MODX_ASSETS_URL . 'components/ms2gallery/img/web/ms2g_small.jpg';
			}
		}

		$row['class'] = empty($row['active'])
			? 'inactive'
			: 'active';

		$row['properties'] = strpos($row['properties'], '{') === 0
			? $this->modx->fromJSON($row['properties'])
			: array();

		$row['active'] = !empty($row['active']);

		$row['tags'] = array();
		$q = $this->modx->newQuery('msResourceFileTag', array('file_id' => $row['id']));
		$q->select('tag');
		if ($q->prepare() && $q->stmt->execute()) {
			while ($tag = $q->stmt->fetchColumn()) {
				$row['tags'][] = array('tag' => $tag);
			}
		}

		return $row;
	}
}

return 'msResourceFileGetListProcessor';
