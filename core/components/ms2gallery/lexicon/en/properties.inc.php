<?php
/**
 * Properties English Lexicon Entries for ms2Gallery
 *
 * @package ms2gallery
 * @subpackage lexicon
 */
$_lang['ms2gallery_prop_parents'] = 'List of containers separated by commas, to search for files of galleries. The default setting is empty, i.e. the query is not limited to parents and obeys parameter &resources. If the id of the parent begins with a hyphen, he and his descendants are excluded from the query.';
$_lang['ms2gallery_prop_resources'] = 'The resource List, separated by commas, for output files. If the id of the resource begins with a hyphen, this resource is excluded from the sample. If the parameter is empty - displays a gallery of the current resource.';

$_lang['ms2gallery_prop_tplRow'] = 'Chunk for template one row of query.';
$_lang['ms2gallery_prop_tplOuter'] = 'Wrapper for template results of snippet work.';
$_lang['ms2gallery_prop_tplEmpty'] = 'Chunk that returns when no results.';
$_lang['ms2gallery_prop_tplSingle'] = 'Chunk that returns when there is only one result.';

$_lang['ms2gallery_prop_limit'] = 'The number of results to limit.';
$_lang['ms2gallery_prop_offset'] = 'An offset of resources returned by the criteria to skip';
$_lang['ms2gallery_prop_sortby'] = 'The field to sort by.';
$_lang['ms2gallery_prop_sortdir'] = 'The direction to sort by';
$_lang['ms2gallery_prop_toPlaceholder'] = 'If not empty, the snippet will save output to placeholder with that name, instead of return it to screen.';
$_lang['ms2gallery_prop_showLog'] = 'Display additional information about snippet work. Only for authenticated in context "mgr".';
$_lang['ms2gallery_prop_where'] = 'A JSON-style expression of criteria to build any additional where clauses from. To filter the files you need to use the table alias "File". For example &where=`{"File.name:LIKE":"%img%"}`.';

$_lang['ms2gallery_prop_parents'] = 'Container list, separated by commas, to search results. By default, the query is limited to the current parent. If set to 0, query not limited.';
$_lang['ms2gallery_prop_resources'] = 'Comma-delimited list of ids to include in the results. Prefix an id with a dash to exclude the resource from the result.';
$_lang['ms2gallery_prop_prefix'] = 'The prefix for images properties, "img" for example. By default it is "ms2g".';
$_lang['ms2gallery_prop_filetype'] = 'Type of files for select. You can use "image" for images and extensions for other files. For example "image,pdf,xls,doc".';
$_lang['ms2gallery_prop_showInactive'] = 'Show inactive images.';

$_lang['ms2gallery_prop_frontend_css'] = 'Path to file with styles of the shop. If you want to use your own styles - specify them here, or clean this parameter and load them in site template.';
$_lang['ms2gallery_prop_frontend_js'] = 'Path to file with scripts of the shop. If you want to use your own scripts - specify them here, or clean this parameter and load them in site template.';

$_lang['ms2gallery_prop_typeOfJoin'] = 'attachment Type image resource. Left is the Left Join, that is, the resources will be selected, even if they have no pictures. And the inner is the Inner Join will only be selected resources with pictures.';
$_lang['ms2gallery_prop_includeThumbs'] = 'List of permissions preview separated by commas. For example, "120x90,360x270".';
$_lang['ms2gallery_prop_includeOriginal'] = 'Adding to the sample an additional join with a link to the original image. Will be available in an array of resource as "resolution.original", for example "120x90.original".';

$_lang['ms2gallery_prop_tags'] = 'List of tags, separated by commas, for output files.';
$_lang['ms2gallery_prop_tagsVar'] = 'If this parameter is not empty, then the snippet will take the value of "tags" in $_REQUEST[specifiedname]. For example, if you specify "tag", the snippet will display only files matching $_REQUEST["tag"].';
$_lang['ms2gallery_prop_getTags'] = 'Make additional requests to get the tags of a file?';
$_lang['ms2gallery_prop_tagsSeparator'] = 'If you enable the obtaining of tags of files, they will be separated by the string specified in this parameter.';