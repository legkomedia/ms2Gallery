<?php

$properties = array();

$tmp = array(
	'parents' => array(
		'type' => 'textfield'
		,'value' => ''
	),
	'resources' => array(
		'type' => 'textfield'
		,'value' => ''
	),
	'prefix' => array(
		'type' => 'textfield'
		,'value' => 'ms2g'
	),
	'showLog' => array(
		'type' => 'combo-boolean',
		'value' => false,
	)

);

foreach ($tmp as $k => $v) {
	$properties[] = array_merge(array(
			'name' => $k,
			'desc' => 'ms2gallery_prop_' . $k,
			'lexicon' => 'ms2gallery:properties',
		), $v
	);
}

return $properties;