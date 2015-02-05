<?php
/** @var array $scriptProperties */
switch ($modx->event->name) {
	case 'OnDocFormRender':
		/** @var modResource $resource */
		if ($resource instanceof msProduct || $mode == 'new') {
			return;
		}
		$template = $resource->get('template');
		$templates = array_map('trim', explode(',', $modx->getOption('ms2gallery_disable_for_templates')));
		if ($templates[0] != '' && in_array($template, $templates)) {
			return;
		}
		$modx23 = !empty($modx->version) && version_compare($modx->version['full_version'], '2.3.0', '>=');
		$modx->controller->addHtml('<script type="text/javascript">
			Ext.onReady(function() {
				MODx.modx23 = ' . (int)$modx23 . ';
			});
		</script>');

		/** @var ms2Gallery $ms2Gallery */
		$ms2Gallery = $modx->getService('ms2gallery', 'ms2Gallery', MODX_CORE_PATH . 'components/ms2gallery/model/ms2gallery/');
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
		$resource->set('media_source', $source_id);

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
								}
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
							}
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

	case 'OnBeforeDocFormSave':
		if ($source_id = $resource->get('media_source')) {
			$resource->setProperties(array('media_source' => $source_id), 'ms2gallery');
		}
		break;

	case 'OnLoadWebDocument':
		$tstart = microtime(true);
		/** @var pdoFetch $pdoFetch */
		if (!$modx->getOption('ms2gallery_set_placeholders', null, false, true) || !$pdoFetch = $modx->getService('pdoFetch')) {
			return;
		}
		$plTemplates = array_map('trim', explode(',', $modx->getOption('ms2gallery_placeholders_for_templates')));
		if (!empty($plTemplates[0]) && !in_array($modx->resource->get('template'), $plTemplates)) {
			return;
		}
		$plPrefix = $modx->getOption('ms2gallery_placeholders_prefix', null, 'ms2g.', true);
		$plThumbs = array_map('trim', explode(',', $modx->getOption('ms2gallery_placeholders_thumbs')));
		$tplName = $modx->getOption('ms2gallery_placeholders_tpl');

		// Check for assigned TV
		$q = $modx->newQuery('modTemplateVarTemplate');
		$q->innerJoin('modTemplateVar', 'TemplateVar');
		$q->innerJoin('modTemplate', 'Template');
		$q->where(array(
			'TemplateVar.name' => $tplName,
			'Template.id' => $modx->resource->get('template')
		));
		$q->select('TemplateVar.id');

		$tpl = '';
		if ($modx->getCount('modTemplateVarTemplate', $q)) {
			$tpl = $modx->resource->getTVValue($tplName);
		}
		/** @var modChunk $chunk */
		if (empty($tpl) && $chunk = $modx->getObject('modChunk', array('name' => $tplName))) {
			$tpl = $chunk->getContent();
		}

		$options = array('loadModels' => 'ms2gallery');
		$where = array('resource_id' => $modx->resource->id, 'parent' => 0);

		$parents = $pdoFetch->getCollection('msResourceFile', $where, $options);
		$options['select'] = 'url';
		foreach ($parents as &$parent) {
			$where = array('parent' => $parent['id']);
			if (!empty($plThumbs[0])) {
				$where['path:IN'] = array();
				foreach ($plThumbs as $thumb) {
					$where['path:IN'][] = $parent['path'] . $thumb . '/';
				}
			}
			if ($children = $pdoFetch->getCollection('msResourceFile', $where, $options)) {
				foreach ($children as $child) {
					if (preg_match('/((?:\d{1,4}|)x(?:\d{1,4}|))/', $child['url'], $size)) {
						$parent[$size[0]] = $child['url'];
					}
				}
			}
			$pls = $pdoFetch->makePlaceholders($parent, $plPrefix . $parent['rank'] . '.', '[[+', ']]', false);
			$pls['vl'][$plPrefix . $parent['rank']] = !empty($tpl)
				? $pdoFetch->getChunk('@INLINE ' . $tpl, $parent)
				: htmlentities(print_r($parent, 1), ENT_QUOTES, 'UTF-8');
			$modx->setPlaceholders($pls['vl']);
		}

		$modx->log(modX::LOG_LEVEL_INFO, '[ms2Gallery] Set image placeholders for page id = ' . $modx->resource->id . ' in ' . number_format(microtime(true) - $tstart, 7) . ' sec.');
		break;

	case 'OnBeforeEmptyTrash':
		if (empty($scriptProperties['ids']) || !is_array($scriptProperties['ids'])) {
			return;
		}
		if (!$modx->addPackage('ms2gallery', MODX_CORE_PATH . 'components/ms2gallery/model/')) {
			return;
		}
		$resources = $modx->getIterator('modResource', array('id:IN' => $scriptProperties['ids']));
		/** @var modResource $resource */
		foreach ($resources as $resource) {
			$properties = $resource->getProperties('ms2gallery');
			if (!empty($properties['media_source'])) {
				/** @var modMediaSource $source */
				$source = $modx->getObject('modMediaSource', $properties['media_source']);
				$resource_id = $resource->get('id');
				if ($source) {
					$source->set('ctx', $resource->get('context_key'));
					$source->initialize();
				}
				$images = $modx->getIterator('msResourceFile', array('resource_id' => $resource_id, 'parent' => 0));
				/** @var msResourceFile $image */
				foreach ($images as $image) {
					$image->prepareSource($source);
					$image->remove();
				}
				if ($source) {
					$source->removeContainer($source->getBasePath() . $resource_id);
				}
			}
		}
		break;
}