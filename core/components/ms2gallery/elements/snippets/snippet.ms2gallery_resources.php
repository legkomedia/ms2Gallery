<?php
/** @var array $scriptProperties */
$class = 'modResource';

// Load model
if (empty($loadModels)) {
	$scriptProperties['loadModels'] = 'ms2Gallery';
} else {
	$loadModels = array_map('trim', explode(',', $loadModels));
	if (!in_array('ms2gallery', $loadModels)) {
		$loadModels[] = 'ms2gallery';
	}
	$scriptProperties['loadModels'] = $loadModels;
}

// Type of join
if (empty($typeOfJoin)) {
	$typeOfJoin = 'left';
}
switch (strtolower(trim($typeOfJoin))) {
	case 'right':
		$join = 'rightJoin';
		break;
	case 'inner':
		$join = 'innerJoin';
		break;
	default:
		$join = 'leftJoin';
		break;
}

// Select modResource
$columns = array_keys($modx->getFieldMeta($class));
if (empty($includeContent) && empty($useWeblinkUrl)) {
	$key = array_search('content', $columns);
	unset($columns[$key]);
}
$select = array(
	$class => implode(',', $columns)
);

// Include Thumbnails
${$join} = array();
if (empty($includeThumbs)) {
	$includeThumbs = '120x90';
}
$thumbs = array_map('trim', explode(',', $includeThumbs));
if (!empty($thumbs[0])) {
	foreach ($thumbs as $thumb) {
		${$join}[] = array(
			'class' => 'msResourceFile',
			'alias' => $thumb,
			'on' => preg_replace('/(\n|\t)/', '', "
				`$thumb`.`resource_id` = `$class`.`id` AND
				`$thumb`.`parent` != 0 AND
				`$thumb`.`path` LIKE '%/$thumb/' AND
				`$thumb`.`active` = 1 AND
				`$thumb`.`rank` = 0
			")
		);
		$select[$thumb] = preg_replace('/(\n|\t)/', '', "
			`$thumb`.`url` as `$thumb`,
			`$thumb`.`name` as `$thumb.name`,
			`$thumb`.`description` as `$thumb.description`,
			`$thumb`.`createdon` as `$thumb.createdon`,
			`$thumb`.`createdby` as `$thumb.createdby`,
			`$thumb`.`properties` as `$thumb.properties`,
			`$thumb`.`alt` as `$thumb.alt`,
			`$thumb`.`add` as `$thumb.add`
		");

		if (!empty($includeOriginal)) {
			${$join}[] = array(
				'class' => 'msResourceFile',
				'alias' => $thumb.'o',
				'on' => preg_replace('/(\n|\t)/', '', "
					`{$thumb}o`.`resource_id` = `$class`.`id` AND
					`{$thumb}o`.`parent` = 0 AND
					`{$thumb}o`.`active` = 1 AND
					`{$thumb}o`.`rank` = 0
				")
			);
			$select[$thumb] .= ", `{$thumb}o`.`url` as `$thumb.original`";
		}
	}
}

foreach (array('leftJoin', 'innerJoin', 'rightJoin', 'select') as $v) {
	if (!empty($scriptProperties[$v])) {
		$tmp = $modx->fromJSON($scriptProperties[$v]);
		if (is_array($tmp)) {
			$$v = array_merge($$v, $tmp);
		}
	}
	unset($scriptProperties[$v]);
}
$scriptProperties['select'] = $modx->toJSON($select);
$scriptProperties['groupby'] = $class . '.id';
$scriptProperties[$join] = $modx->toJSON(${$join});

return $modx->runSnippet('pdoResources', $scriptProperties);