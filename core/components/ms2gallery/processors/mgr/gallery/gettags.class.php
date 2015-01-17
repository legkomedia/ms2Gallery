<?php

class msResourceFileGetTagsProcessor extends modObjectProcessor {
	public $classKey = 'msResourceFileTag';

	/** {@inheritDoc} */
	public function process() {
		$query = trim($this->getProperty('query'));
		$limit = trim($this->getProperty('limit', 10));
		$offset = trim($this->getProperty('start', 0));

		$c = $this->modx->newQuery($this->classKey);
		$c->sortby('tag', 'ASC');
		$c->select('tag');
		$c->groupby('tag');
		if (!empty($query)) {
			$c->where(array('tag:LIKE' => "%{$query}%"));
		}
		$total = $this->modx->getCount($this->classKey, $c);

		$c->limit($limit, $offset);
		$res = array();
		if ($c->prepare() && $c->stmt->execute()) {
			$res = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		return $this->outputArray($res, $total);
	}
}

return 'msResourceFileGetTagsProcessor';