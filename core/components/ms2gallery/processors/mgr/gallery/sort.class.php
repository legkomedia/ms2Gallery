<?php

class msResourceFileSortProcessor extends modObjectProcessor {


	/**
	 * It is adapted code from
	 * https://github.com/splittingred/Gallery/blob/a51442648fde1066cf04d46550a04265b1ad67da/core/components/gallery/processors/mgr/item/sort.php
	 *
	 * @return array|string
	 */
	public function process() {
		/* @var msResourceFile $source */
		$source = $this->modx->getObject('msResourceFile', $this->getProperty('source'));
		/* @var msResourceFile $target */
		$target = $this->modx->getObject('msResourceFile', $this->getProperty('target'));
		$resource_id = $this->getProperty('resource_id');

		if (empty($source) || empty($target) || empty($resource_id)) {
			return $this->modx->error->failure();
		}

		if ($source->get('rank') < $target->get('rank')) {
			$this->modx->exec("UPDATE {$this->modx->getTableName('msResourceFile')}
				SET rank = rank - 1 WHERE
					resource_id = " . $resource_id . "
					AND parent = 0
					AND rank <= {$target->get('rank')}
					AND rank > {$source->get('rank')}
					AND rank > 0
			");
			$newRank = $target->get('rank');
		}
		else {
			$this->modx->exec("UPDATE {$this->modx->getTableName('msResourceFile')}
				SET rank = rank + 1 WHERE
					resource_id = " . $resource_id . "
					AND parent = 0
					AND rank >= {$target->get('rank')}
					AND rank < {$source->get('rank')}
			");
			$newRank = $target->get('rank');
		}
		$source->set('rank', $newRank);
		$source->save();

		/** @var ms2Gallery $ms2Gallery */
		if ($ms2Gallery = $this->modx->getService('ms2gallery')) {
			$ms2Gallery->rankResourceImages($resource_id);
		}

		return $this->modx->error->success();
	}
}

return 'msResourceFileSortProcessor';
