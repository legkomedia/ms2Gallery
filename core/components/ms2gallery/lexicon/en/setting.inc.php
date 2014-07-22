<?php
/**
 * Settings English Lexicon Entries for ms2Gallery
 *
 * @package ms2gallery
 * @subpackage lexicon
 */
$_lang['area_ms2gallery_resource'] = 'Resource';
$_lang['area_ms2gallery_frontend'] = 'Frontend';

$_lang['setting_ms2gallery_source_default'] = 'Default media source';
$_lang['setting_ms2gallery_source_default_desc'] = 'Default media source for the resource gallery.';
$_lang['setting_ms2gallery_thumbnail_size'] = 'Default thumbnail size';
$_lang['setting_ms2gallery_thumbnail_size_desc'] = 'Size of default pre-generated thumbnail for field "thumb" in msProduct table. Of course, this size should exist in the settings of your media source that generates the previews. Otherwise you will receive  miniShop2 logo instead of resource image in manager.';

$_lang['setting_ms2gallery_date_format'] = 'Format of dates';
$_lang['setting_ms2gallery_date_format_desc'] = 'You can specify how to format dates using php strftime() syntax. By default format is "%d.%m.%y %H:%M".';

$_lang['setting_ms2gallery_set_placeholders'] = 'Enable auto-placeholders?';
$_lang['setting_ms2gallery_set_placeholders_desc'] = 'You can enable auto set of placeholders with images of gallery on page.';
$_lang['setting_ms2gallery_placeholders_prefix'] = 'Prefix of placeholders';
$_lang['setting_ms2gallery_placeholders_prefix_desc'] = 'You can specify placeholders prefix for images. Than you will can use it as [[+prefix.rank.field]]. For example: [[+ms2g.0.url]] or [[+ms2g.1.120x90]]. Placeholder [[+prefix.rank]] will print array with the whole available file properties.';

$_lang['setting_ms2gallery_page_size'] = 'Number of files on page';
$_lang['setting_ms2gallery_page_size_desc'] = 'You can set the number of files displayed on the page, default is 20. 0 - display all files.';

$_lang['ms2gallery_source_thumbnails_desc'] = 'JSON encoded array of options for generating thumbnails.';
$_lang['ms2gallery_source_maxUploadWidth_desc'] = 'Maximum width of image for upload. All images, that exceeds this parameter, will be resized to fit.';
$_lang['ms2gallery_source_maxUploadHeight_desc'] = 'Maximum height of image for upload. All images, that exceeds this parameter, will be resized to fit.';
$_lang['ms2gallery_source_maxUploadSize_desc'] = 'Maximum size of file for upload (in bytes).';
$_lang['ms2gallery_source_imageNameType_desc'] = 'This setting specifies how to rename a file after upload. Hash is the generation of a unique name depending on the contents of the file. Friendly - generation behalf of the algorithm friendly URLs of pages of the site (they are managed by system settings).';
$_lang['ms2gallery_source_imageUploadDir_desc'] = 'Direction of loading files. 1 (default) - upload files in the end list. 0 - upload files to start list';