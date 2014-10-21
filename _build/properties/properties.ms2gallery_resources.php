<?php

$properties = array();

$tmp = array(
	'typeOfJoin' => array(
		'type' => 'list',
		'options' => array(
			array('text' => 'left','value' => 'left'),
			//array('text' => 'right','value' => 'right'),
			array('text' => 'inner','value' => 'inner'),
		),
		'value' => 'left',
	),
	'includeThumbs' => array(
		'type' => 'textfield',
		'value' => '120x90'
	),
	'includeOriginal' => array(
		'type' => 'combo-boolean',
		'value' => false
	)
);

foreach ($tmp as $k => $v) {
	$properties[] = array_merge(array(
			'name' => $k,
			'desc' => PKG_NAME_LOWER . '_prop_' . $k,
			'lexicon' => PKG_NAME_LOWER . ':properties',
		), $v
	);
}

return $properties;