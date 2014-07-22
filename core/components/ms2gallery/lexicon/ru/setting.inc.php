<?php
/**
 * Settings Russian Lexicon Entries for ms2Gallery
 *
 * @package ms2gallery
 * @subpackage lexicon
 */
$_lang['area_ms2gallery_resource'] = 'Ресурс';
$_lang['area_ms2gallery_frontend'] = 'Сайт';

$_lang['setting_ms2gallery_source_default'] = 'Источник файлов по умолчанию';
$_lang['setting_ms2gallery_source_default_desc'] = 'Источник файлов для галереи изображений ресурса по умолчанию.';

$_lang['setting_ms2gallery_thumbnail_size'] = 'Размер превью по умолчанию';
$_lang['setting_ms2gallery_thumbnail_size_desc'] = 'Здесь вы можете указать размер заранее уменьшенной копии изображения для вставки поля "thumb" товара. Конечно, этот размер должен существовать и в настройках источника медиа, чтобы генерировались такие превью. В противном случае вы получите логотип minIShop2 вместо изображения в админке.';

$_lang['setting_ms2gallery_date_format'] = 'Формат даты';
$_lang['setting_ms2gallery_date_format_desc'] = 'Укажите формат дат, используя синтаксис php функции strftime(). По умолчанию формат "%d.%m.%y %H:%M".';

$_lang['setting_ms2gallery_set_placeholders'] = 'Включить авто-плейсхолдеры?';
$_lang['setting_ms2gallery_set_placeholders_desc'] = 'Вы можете включить автоматическое выставление плейсхолдеров с изображениями на странице с гелереей.';
$_lang['setting_ms2gallery_placeholders_prefix'] = 'Префикс плейсхолдеров';
$_lang['setting_ms2gallery_placeholders_prefix_desc'] = 'Вы можете указать префикс для плейсхолдеров с изображениями - тогда они будут доступн на странице как [[+prefix.rank.field]]. Например: [[+ms2g.0.url]] или [[+ms2g.1.120x90]]. Плейсхолдер [[+prefix.rank]] выведет массив со всеми доступными свойствами файла.';

$_lang['setting_ms2gallery_page_size'] = 'Файлов на странице';
$_lang['setting_ms2gallery_page_size_desc'] = 'Вы можете задать количество выводимых файлов на странице, по умолчанию 20. 0 - вывести все.';

$_lang['ms2gallery_source_thumbnails_desc'] = 'Закодированный в JSON массив с параметрами генерации уменьшенных копий изображений.';
$_lang['ms2gallery_source_maxUploadWidth_desc'] = 'Максимальная ширина изображения для загрузки. Всё, что больше, будет ужато до этого значения.';
$_lang['ms2gallery_source_maxUploadHeight_desc'] = 'Максимальная высота изображения для загрузки. Всё, что больше, будет ужато до этого значения.';
$_lang['ms2gallery_source_maxUploadSize_desc'] = 'Максимальный размер загружаемых изображений (в байтах).';
$_lang['ms2gallery_source_imageNameType_desc'] = 'Этот параметр указывает, как нужно переименовать файл при загрузке. Hash - это генерация уникального имени, в зависимости от содержимого файла. Friendly - генерация имени по алгоритму дружественных url страниц сайта (они управляются системными настройками).';
$_lang['ms2gallery_source_imageUploadDir_desc'] = 'Направление загрузки файлов. 1 (по умолчанию) - загрузка файлов в конец списка. 0 - загрузка файлов в начало списка.';