<?php
$corePath = $modx->getOption('core_path', null, MODX_CORE_PATH).'components/ms2gallery/';

switch ($modx->event->name) {
	case 'OnDocFormRender':
		/** @var modResource $resource */
		if ($resource instanceof msProduct || $mode == 'new') {
			return;
		}
		$modx23 = !empty($modx->version) && version_compare($modx->version['full_version'], '2.3.0', '>=');
		$modx->controller->addHtml('<script type="text/javascript">
			Ext.onReady(function() {
				MODx.modx23 = '.(int)$modx23.';
			});
		</script>');

		/** @var ms2Gallery $ms2Gallery */
		$ms2Gallery = $modx->getService('ms2gallery','ms2Gallery', MODX_CORE_PATH.'components/ms2gallery/model/ms2gallery/');
		$modx->controller->addLexiconTopic('ms2gallery:default');
		$url = $ms2Gallery->config['assetsUrl'];

		$modx->controller->addJavascript($url . 'js/mgr/ms2gallery.js');
		$modx->controller->addLastJavascript($url . 'js/mgr/misc/ms2.combo.js');
		$modx->controller->addLastJavascript($url . 'js/mgr/misc/ms2.utils.js');
		$modx->controller->addLastJavascript($url . 'js/mgr/misc/plupload/plupload.full.js');
		$modx->controller->addLastJavascript($url . 'js/mgr/misc/ext.ddview.js');
		$modx->controller->addLastJavascript($url . 'js/mgr/uploader.grid.js');
		$modx->controller->addLastJavascript($url . 'js/mgr/gallery.view.js');
		$modx->controller->addLastJavascript($url . 'js/mgr/gallery.window.js');
		$modx->controller->addLastJavascript($url . 'js/mgr/gallery.panel.js');
		$modx->controller->addCss($url . 'css/mgr/main.css');
		if (!$modx23) {
			$modx->controller->addCss($url . 'css/mgr/font-awesome.min.css');
		}

		$properties = $resource->getProperties('ms2gallery');
		if (empty($properties['media_source'])) {
			if (!$source_id = $resource->getTVValue('ms2Gallery')) {
				$source_id = $modx->getOption('ms2gallery_source_default');
			}
			$resource->setProperties(array('media_source' => $source_id), 'ms2gallery');
			$resource->save();
		}
		else {
			$source_id = $properties['media_source'];
		}

		if (empty($source_id)) {
			$source_id = $modx->getOption('ms2gallery_source_default');
		}
		$source_config = array();
		/** @var modMediaSource $source */
		if ($source = $modx->getObject('modMediaSource', $source_id)) {
			$tmp = $source->getProperties();
			$properties = array();
			foreach ($tmp as $v) {
				$source_config[$v['name']] = $v['value'];
			}
		}

		if ($modx->getCount('modPlugin', array('name' => 'AjaxManager', 'disabled' => false))) {
			$modx->controller->addHtml('
			<script type="text/javascript">
				ms2Gallery.config = ' . $modx->toJSON($ms2Gallery->config) . ';
				ms2Gallery.config.media_source = ' . $modx->toJSON($source_config) . ';
				Ext.onReady(function() {
					window.setTimeout(function() {
						var tabs = Ext.getCmp("modx-resource-tabs");
						if (tabs) {
							tabs.add({
								xtype: "ms2gallery-page",
								id: "ms2gallery-page",
								title: _("ms2gallery"),
								record: {
									id: ' . $resource->get('id') . '
									,source: ' . $source_id . '
								},
							});
						}
					}, 10);
				});
			</script>');
		}
		else {
			$modx->controller->addHtml('
			<script type="text/javascript">
				ms2Gallery.config = ' . $modx->toJSON($ms2Gallery->config) . ';
				ms2Gallery.config.media_source = ' . $modx->toJSON($source_config) . ';
				Ext.ComponentMgr.onAvailable("modx-resource-tabs", function() {
					this.on("beforerender", function() {
						this.add({
							xtype: "ms2gallery-page",
							id: "ms2gallery-page",
							title: _("ms2gallery"),
							record: {
								id: ' . $resource->get('id') . '
								,source: ' . $source_id . '
							},
						});
					});
					Ext.apply(this, {
							stateful: true,
							stateId: "modx-resource-tabs-state",
							stateEvents: ["tabchange"],
							getState: function() {return {activeTab:this.items.indexOf(this.getActiveTab())};
						}
					});
				});
			</script>');
		}
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