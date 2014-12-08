<?php
/**
 * Loads system settings into build
 *
 * @package ms2gallery
 * @subpackage build
 */
$settings = array();

$tmp = array(
	'source_default' => array(
		'value' => '0',
		'xtype' => 'modx-combo-source',
		'area' => 'ms2gallery_resource',
	),
	'thumbnail_size' => array(
		'value' => '120x90',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_resource',
	),
	'date_format' => array(
		'value' => '%d.%m.%y %H:%M',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_resource',
	),
	'page_size' => array(
		'value' => '20',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_resource',
	),
	'disable_for_templates' => array(
		'value' => '',
		'xtype' => 'textfield',
		'area' => 'ms2gallery_resource',
	),
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