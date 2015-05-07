<?php
/**
 * Loads system settings into build
 *
 * @package ms2gallery
 * @subpackage build
 */
$settings = array();

$tmp = array(
	'duplicate_check' => array(
		'value' => true,
		'xtype' => 'combo-boolean',
		'area' => 'ms2gallery_resource',
	),
	'exact_sorting' => array(
		'value' => true,
		'xtype' => 'combo-boolean',
		'area' => 'ms2gallery_resource',
	),
	'source_default' => array(
		'value' => '0',
		'xtype' => 'modx-combo-source',
		'area' => 'ms2gallery_resource',
	),
	'date_format' => array(
		'value' => '%d.%m.%y %H:%M',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_resource',
	),
	'page_size' => array(
		'value' => '50',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_resource',
	),
	'disable_for_templates' => array(
		'value' => '',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_resource',
	),
	/*
	'disable_for_ms2' => array(
		'value' => true,
		'xtype' => 'combo-boolean',
		'area' => 'ms2gallery_resource',
	),
	*/
	'set_placeholders' => array(
		'value' => false,
		'xtype' => 'combo-boolean',
		'area' => 'ms2gallery_frontend',
	),
	'placeholders_prefix' => array(
		'value' => 'ms2g.',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_frontend',
	),
	'placeholders_tpl' => array(
		'value' => '',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_frontend',
	),
	'placeholders_for_templates' => array(
		'value' => '',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_frontend',
	),
	'placeholders_thumbs' => array(
		'value' => '',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_frontend',
	),
);


foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => PKG_NAME_LOWER . '_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	),'',true,true);

	$settings[] = $setting;
}
return $settings;