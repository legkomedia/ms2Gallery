<?php

$chunks = array();

$tmp = array(
	'tpl.ms2Gallery.row' => 'ms2gallery_row'
	,'tpl.ms2Gallery.outer' => 'ms2gallery_outer'
	,'tpl.ms2Gallery.empty' => 'ms2gallery_empty'
	,'tpl.ms2Gallery.single' => 'ms2gallery_single'
);

// Save chunks for setup options
$BUILD_CHUNKS = array();

foreach ($tmp as $k => $v) {
	/* @avr modChunk $chunk */
	$chunk = $modx->newObject('modChunk');
	$chunk->fromArray(array(
		'id' => 0
		,'name' => $k
		,'description' => ''
		,'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/chunk.'.$v.'.tpl')
		,'static' => BUILD_CHUNK_STATIC
		,'source' => 1
		,'static_file' => 'core/components/'.PKG_NAME_LOWER.'/elements/chunks/chunk.'.$v.'.tpl'
	),'',true,true);

	$chunks[] = $chunk;

	$BUILD_CHUNKS[$k] = array(
		'static' => BUILD_CHUNK_STATIC,
		'source' => 1,
		'static_file' => 'core/components/'.PKG_NAME_LOWER.'/elements/chunks/chunk.'.$v.'.tpl',
	);
}

ksort($BUILD_CHUNKS);
return $chunks;