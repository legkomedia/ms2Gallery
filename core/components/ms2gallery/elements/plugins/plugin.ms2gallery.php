<?php
$corePath = $modx->getOption('core_path', null, MODX_CORE_PATH).'components/ms2gallery/';

switch ($modx->event->name) {
	case 'OnTVInputRenderList':
		$modx->event->output($corePath.'elements/tv/input/');
		break;

	case 'OnTVOutputRenderList':
		$modx->event->output($corePath.'elements/tv/output/');
		break;

	case 'OnTVInputPropertiesList':
		$modx->event->output($corePath.'elements/tv/inputoptions/');
		break;

	case 'OnTVOutputRenderPropertiesList':
		$modx->event->output($corePath.'elements/tv/properties/');
		break;

	case 'OnManagerPageBeforeRender':
		break;

	case 'OnDocFormRender':
		$mgrUrl = $modx->getOption('manager_url',null,MODX_MANAGER_URL);
		$modx->controller->addLexiconTopic('ms2gallery:default');

		$modx->controller->addCss($modx->config['assets_url'].'components/ms2gallery/css/mgr/bootstrap.min.css');
		$modx->controller->addCss($modx->config['assets_url'].'components/ms2gallery/css/mgr/main.css');
		break;

	case 'OnLoadWebDocument':
		$tstart = microtime(true);
		/** @var pdoFetch $pdoFetch */
		if (!$modx->getOption('ms2gallery_set_placeholders', null, false, true) || !$pdoFetch = $modx->getService('pdoFetch')) {return;}
		$plPrefix = $modx->getOption('ms2gallery_placeholders_prefix', null, 'ms2g', true);

		$options = array('loadModels' => 'ms2gallery');
		$where = array('resource_id' => $modx->resource->id, 'parent' => 0);

		$parents = $pdoFetch->getCollection('msResourceFile', $where, $options);
		$options['select'] = 'url';
		foreach ($parents as &$parent) {
			$where = array('parent' => $parent['id']);
			if ($children = $pdoFetch->getCollection('msResourceFile', $where, $options)) {
				foreach ($children as $child) {
					if (preg_match('/((?:\d{1,4}|)x(?:\d{1,4}|))/', $child['url'], $size)) {
						$parent[$size[0]] = $child['url'];
					}
				}
			}
			$pls = $pdoFetch->makePlaceholders($parent, $plPrefix . $parent['rank'] . '.', '[[+', ']]', false);
			$pls['vl'][$plPrefix . $parent['rank']] = htmlentities(print_r($parent, 1), ENT_QUOTES, 'UTF-8');
			$modx->setPlaceholders($pls['vl']);
		}

		$modx->log(modX::LOG_LEVEL_INFO, '[ms2Gallery] Set image placeholders for page id = ' . $modx->resource->id .' in ' . number_format(microtime(true) - $tstart, 7) . ' sec.');
		break;
}