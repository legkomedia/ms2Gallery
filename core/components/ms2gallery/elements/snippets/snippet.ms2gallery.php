<?php
/* @var array $scriptProperties */
/* @var ms2Gallery $ms2Gallery */
$ms2Gallery = $modx->getService('ms2gallery','ms2Gallery', MODX_CORE_PATH.'components/ms2gallery/model/ms2gallery/');
/* @var pdoFetch $pdoFetch */
if (!$modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {return false;}
$pdoFetch = new pdoFetch($modx, $scriptProperties);

$extensionsDir = $modx->getOption('extensionsDir', $scriptProperties, 'components/ms2gallery/img/mgr/extensions/', true);

// Register styles and scripts on frontend
$config = $ms2Gallery->makePlaceholders($ms2Gallery->config);
$css = $modx->getOption('frontend_css', $scriptProperties, 'frontend_css');
if (!empty($css) && preg_match('/\.css/i', $css)) {
	$modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
}

$js = $modx->getOption('frontend_js', $scriptProperties, 'frontend_js');
if (!empty($js) && preg_match('/\.js/i', $js)) {
	$modx->regClientStartupScript(str_replace('		', '', '
		<script type="text/javascript">
			if(typeof jQuery == "undefined") {
				document.write("<script src=\"'.$ms2Gallery->config['jsUrl'].'web/lib/jquery.min.js\" type=\"text/javascript\"><\/script>");
			}
		</script>
	'), true);
	$modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));
}

/** @var modResource $resource */
$resource = (!empty($resource) && $resource != $modx->resource->id)
	? $modx->getObject('modResource', $resource)
	: $modx->resource;

if (empty($limit) && !empty($offset)) {$scriptProperties['limit'] = 10000;}
$where = array(
	'resource_id' => $resource->get('id'),
	'parent' => 0,
);
if (!empty($filetype)) {
	$where['type:IN'] = array_map('trim', explode(',', $filetype));
}
if (empty($showInactive)) {
	$where['active'] = 1;
}
// processing additional query params
if (!empty($scriptProperties['where'])) {
	$tmp = $modx->fromJSON($scriptProperties['where']);
	if (is_array($tmp) && !empty($tmp)) {
		$where = array_merge($where, $tmp);
	}
}
unset($scriptProperties['where']);

// Default parameters
$default = array(
	'class' => 'msResourceFile',
	'where' => $modx->toJSON($where),
	//'select' => '{"msResourceFile":"all"}',
	'limit' => $limit,
	'sortby' => 'rank',
	'sortdir' => 'ASC',
	'fastMode' => false,
	'return' => 'data',
	'nestedChunkPrefix' => 'ms2gallery_',
);

// Merge all properties and run!
$scriptProperties['tpl'] = !empty($tplRow) ? $tplRow : '';
$pdoFetch->setConfig(array_merge($default, $scriptProperties));
$rows = $pdoFetch->run();

if (!empty($rows)) {
	$tmp = current($rows);
	$resolution = array();
	$ms2Gallery->initializeMediaSource($modx->context->key, $tmp['source']);
	$properties = $ms2Gallery->mediaSource->getProperties();
	if (isset($properties['thumbnails']['value'])) {
		$fileTypes = $modx->fromJSON($properties['thumbnails']['value']);
		foreach ($fileTypes as $v) {
			$resolution[] = $v['w'].'x'.$v['h'];
		}
	}
}


// Processing rows
$output = null; $images = array();
$pdoFetch->addTime('Fetching thumbnails');
foreach ($rows as $k => $row) {
	$row['idx'] = $pdoFetch->idx++;
	$images[$row['id']] = $row;

	if (isset($row['type']) && $row['type'] == 'image') {
		$q = $modx->newQuery('msResourceFile', array('parent' => $row['id']));
		$q->select('url');
		if ($q->prepare() && $q->stmt->execute()) {
			while ($tmp = $q->stmt->fetch(PDO::FETCH_COLUMN)) {
				if (preg_match('/((?:\d{1,4}|)x(?:\d{1,4}|))/', $tmp, $size)) {
					$images[$row['id']][$size[0]] = $tmp;
				}
			}
		}
	}
	elseif (isset($row['type'])) {
		$row['thumbnail'] = $row['url'] =  (file_exists(MODX_ASSETS_PATH . $extensionsDir . $row['type'] . '.png'))
			? MODX_ASSETS_URL . $extensionsDir . $row['type'].'.png'
			: MODX_ASSETS_URL . $extensionsDir . 'other.png';
		foreach ($resolution as $v) {
			$images[$row['id']][$v] = $row['thumbnail'];
		}
	}
}

// Processing chunks
$pdoFetch->addTime('Processing chunks');
$output = array();
foreach ($images as $row) {
	$tpl = $pdoFetch->defineChunk($row);

	$output[] = empty($tpl)
		? $pdoFetch->getChunk('', $row)
		: $pdoFetch->getChunk($tpl, $row, $pdoFetch->config['fastMode']);
}
$pdoFetch->addTime('Returning processed chunks');

// Return output
$log = '';
if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
	$log .= '<pre class="msGalleryLog">' . print_r($pdoFetch->getTime(), 1) . '</pre>';
}

if (!empty($toSeparatePlaceholders)) {
	$output['log'] = $log;
	$modx->setPlaceholders($output, $toSeparatePlaceholders);
}
else {
	if (count($output) === 1 && !empty($tplSingle)) {
		$output = $pdoFetch->getChunk($tplSingle, array_shift($images));
	}
	else {
		if (empty($outputSeparator)) {$outputSeparator = "\n";}
		$output = implode($outputSeparator, $output);

		if (!empty($tplOuter) && !empty($output)) {
			$arr = array_shift($images);
			$arr['rows'] = $output;
			$output = $pdoFetch->getChunk($tplOuter, $arr);
		}
		elseif (empty($output)) {
			$output = !empty($tplEmpty)
				? $pdoFetch->getChunk($tplEmpty)
				: '';
		}
	}

	$output .= $log;
	if (!empty($toPlaceholder)) {
		$modx->setPlaceholder($toPlaceholder, $output);
	}
	else {
		return $output;
	}
}