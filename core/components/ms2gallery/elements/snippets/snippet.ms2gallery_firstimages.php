<?php
/**@TODO It can be replaced by propertySet for pdoResources */

/* @var ms2Gallery $ms2Gallery */
$ms2Gallery = $modx->getService('ms2gallery','ms2Gallery', MODX_CORE_PATH.'components/ms2gallery/model/ms2gallery/');
//$ms2Gallery->initialize($modx->context->key);
/* @var pdoFetch $pdoFetch */
$pdoFetch = $modx->getService('pdofetch','pdoFetch', MODX_CORE_PATH.'components/pdotools/model/pdotools/',$scriptProperties);
$pdoFetch->addTime('pdoTools loaded.');


$parents = $modx->getOption('parents',$scriptProperties,'');

$prefix = $modx->getOption('prefix',$scriptProperties,'ms2g');

$where = array();

// Filter by ids
if (!empty($resources)){
    $resources = array_map('trim', explode(',', $resources));
    $in = $out = array();
    foreach ($resources as $v) {
        if (!is_numeric($v)) {continue;}
        if ($v < 0) {$out[] = abs($v);}
        else {$in[] = $v;}
    }
    if (!empty($in)) {$where['id:IN'] = $in;}
    if (!empty($out)) {$where['id:NOT IN'] = $out;}
}
else {
    // Filter by parents
    if (empty($parents) && $parents != '0') {$parents = $modx->resource->id;}
    if (!empty($parents) && $parents > 0){
        if (empty($depth)) {$depth = 1;}
        $pids = array_map('trim', explode(',', $parents));
        $parents = $pids;
        foreach ($pids as $v) {
            if (!is_numeric($v)) {continue;}
            $parents = array_merge($parents, $modx->getChildIds($v, $depth));
        }
        $where['parent:IN'] = $parents;
    }
}

$rightJoin = '{"class":"msResourceFile","alias":"IMG","on":"`IMG`.`resource_id` = `modResource`.`id` AND `IMG`.`rank` = 0"}';

// Default parameters
$default = array(
    'class' => 'modResource'
    ,'where' => $modx->toJSON($where)
    ,'rightJoin' => '['.$rightJoin.']'
    ,'limit' => '0'
    ,'select' => '{
        "modResource":"modResource.id, modResource.parent",
        "IMG":"IMG.*"
    }'
    ,'sortby' => 'modResource.id'
    ,'sortdir' => 'ASC'
    ,'fastMode' => true
    ,'return' => 'data'
    ,'nestedChunkPrefix' => 'ms2gallery_'
);

// Merge all properties and run!
$pdoFetch->addTime('Query parameters are prepared.');
$pdoFetch->config = array_merge($pdoFetch->config, $default, $scriptProperties);
$rows = $pdoFetch->run();

foreach ($rows as $k => $row) {
    if ($row['parent'] == 0) {
        $modx->setPlaceholder($prefix.'.'.$row['resource_id'].'_image', $row['url']);
        $modx->setPlaceholder($prefix.'.'.$row['resource_id'].'_name', $row['name']);
        $modx->setPlaceholder($prefix.'.'.$row['resource_id'].'_description', $row['description']);
    }
    else {
        preg_match('/(\d{1,4}x\d{1,4})/', $row['url'], $size);
        $modx->setPlaceholder($prefix.'.'.$row['resource_id'].'_'.$size[0], $row['url']);
    }
}

if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
    echo '<pre>' . print_r($pdoFetch->getTime(), 1) . '</pre>';
}