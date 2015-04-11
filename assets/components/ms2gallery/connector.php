<?php
/**
 * ms2Gallery Connector
 *
 * @package ms2gallery
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('ms2gallery.core_path',null,$modx->getOption('core_path').'components/ms2gallery/');
require_once $corePath.'model/ms2gallery/ms2gallery.class.php';

$ms2Gallery = new ms2Gallery($modx);
$modx->lexicon->load('ms2gallery:default');

/* handle request */
$path = $modx->getOption('processorsPath', $ms2Gallery->config, $corePath.'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));