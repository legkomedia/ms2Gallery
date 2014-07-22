<?php
/**
 * Add snippets to build
 * 
 * @package ms2gallery
 * @subpackage build
 */
$snippets = array();

$tmp = array(
    'ms2Gallery' => 'ms2gallery'
    ,'ms2GalleryFirstImages' => 'ms2gallery_firstimages'
);
foreach ($tmp as $k => $v) {
    /* @avr modSnippet $snippet */
    $snippet = $modx->newObject('modSnippet');
    $snippet->fromArray(array(
        'id' => 0
        ,'name' => $k
        ,'description' => ''
        ,'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/snippet.'.$v.'.php')
        ,'static' => BUILD_SNIPPET_STATIC
        ,'source' => 1
        ,'static_file' => 'core/components/ms2gallery/elements/snippets/snippet.'.$v.'.php'
    ),'',true,true);

    $properties = include $sources['build'].'properties/properties.'.$v.'.php';
    $snippet->setProperties($properties);

    $snippets[] = $snippet;
}

unset($properties);
return $snippets;