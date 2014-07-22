<?php
/**
 * Add chunks to build
 *
 * @package ms2Gallery
 * @subpackage build
 */
$tvs = array();

$tvs['ms2Gallery']= $modx->newObject('modTemplateVar');
$tvs['ms2Gallery']->fromArray(array(
	'id' => 0
	,'name' => 'ms2Gallery'
	,'description' => 'Custom input for ms2Gallery'
	,'type' => 'ms2gallery.input'
	,'display' => 'default'
	,'locked' => 0
),'',true,true);


return $tvs;