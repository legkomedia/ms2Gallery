<?php
/**
 * Settings Russian Lexicon Entries for ms2Gallery
 *
 * @package ms2gallery
 * @subpackage lexicon
 */
$_lang['area_ms2gallery_resource'] = 'Ресурс';
$_lang['area_ms2gallery_frontend'] = 'Сайт';

$_lang['setting_ms2gallery_duplicate_check'] = 'Проверка дубликатов в галерее';
$_lang['setting_ms2gallery_duplicate_check_desc'] = 'Включает или выключает проверку на дубликаты файлов в галерее.';
$_lang['setting_ms2gallery_exact_sorting'] = 'Точная сортировка файлов';
$_lang['setting_ms2gallery_exact_sorting_desc'] = 'Включает или выключает точную сортировку файлов дополнительными запросами в БД. Может замедлять сортировку файлов в больших галереях.';
$_lang['setting_ms2gallery_source_default'] = 'Источник файлов по умолчанию';
$_lang['setting_ms2gallery_source_default_desc'] = 'Источник файлов для галереи изображений ресурса по умолчанию.';
$_lang['setting_ms2gallery_date_format'] = 'Формат даты';
$_lang['setting_ms2gallery_date_format_desc'] = 'Укажите формат дат, используя синтаксис php функции strftime(). По умолчанию формат "%d.%m.%y %H:%M".';
$_lang['setting_ms2gallery_disable_for_templates'] = 'Отключить показ у шаблонов';
$_lang['setting_ms2gallery_disable_for_templates_desc'] = 'Перечислите id шаблонов через запятую, для которых не нужно выводить вкладку с галерей.';
$_lang['setting_ms2gallery_disable_for_ms2'] = 'Отключить для MS2';
$_lang['setting_ms2gallery_disable_for_ms2_desc'] = 'Отключить вкладку с галерей для товаров miniShop2, у которых есть собственная галерея?';
$_lang['setting_ms2gallery_new_tab_mode'] = 'Способ подключения вкладки';
$_lang['setting_ms2gallery_new_tab_mode_desc'] = 'Использовать новый способ подключения вкладки, который хорошо работает с AjaxManager, но не работает с Articles и miniShop2?';

$_lang['setting_ms2gallery_set_placeholders'] = 'Включить авто-плейсхолдеры?';
$_lang['setting_ms2gallery_set_placeholders_desc'] = 'Вы можете включить автоматическое выставление плейсхолдеров с изображениями на странице с гелереей.';
$_lang['setting_ms2gallery_placeholders_prefix'] = 'Префикс плейсхолдеров';
$_lang['setting_ms2gallery_placeholders_prefix_desc'] = 'Вы можете указать префикс для плейсхолдеров с изображениями - тогда они будут доступны на странице как [[+prefix.rank.field]]. Например: [[+ms2g.0.url]] или [[+ms2g.1.120x90]]. Плейсхолдер [[+prefix.rank]] выведет массив со всеми доступными свойствами файла или оформленную картинку, если указан параметр "placeholders_tpl". Например, "[[+ms2g.1]]".';
$_lang['setting_ms2gallery_placeholders_tpl'] = 'Шаблон плейсхолдеров';
$_lang['setting_ms2gallery_placeholders_tpl_desc'] = 'Укажите имя ТВ параметра или чанка, для оформления вывода плейсхолдеров типа [[+ms2g.0]], [[+ms2g.1]] на странице. ТВ параметр должен быть назначен ресурсу, иначе будет использован чанк с тем же именем. Если ТВ и чанк не существуют или пусты, то вы получите распечатанный массив с данными изображения в этом плейсхолдере.';
$_lang['setting_ms2gallery_placeholders_thumbs'] = 'Превью для плейсхолдеров';
$_lang['setting_ms2gallery_placeholders_thumbs_desc'] = 'Укажите список размеров превью через запятую, которые нужно выбирать для выставления в плейсхолдеры. Например, "120х90,360x270".';
$_lang['setting_ms2gallery_placeholders_for_templates'] = 'Плейсхолдеры для шаблонов';
$_lang['setting_ms2gallery_placeholders_for_templates_desc'] = 'Укажите список id шаблонов через запятую, для которых нужно выставлять плейсхолдеры с изображениями. Если пусто, то плейсхолдеры выставляются для всех ресурсов.';

$_lang['setting_ms2gallery_page_size'] = 'Файлов на странице';
$_lang['setting_ms2gallery_page_size_desc'] = 'Вы можете задать количество выводимых файлов на странице, по умолчанию 20. 0 - вывести все.';

$_lang['ms2gallery_source_thumbnails_desc'] = 'Закодированный в JSON массив с параметрами генерации уменьшенных копий изображений.';
$_lang['ms2gallery_source_maxUploadWidth_desc'] = 'Максимальная ширина изображения для загрузки. Всё, что больше, будет ужато до этого значения.';
$_lang['ms2gallery_source_maxUploadHeight_desc'] = 'Максимальная высота изображения для загрузки. Всё, что больше, будет ужато до этого значения.';
$_lang['ms2gallery_source_maxUploadSize_desc'] = 'Максимальный размер загружаемых изображений (в байтах).';
$_lang['ms2gallery_source_imageNameType_desc'] = 'Этот параметр указывает, как нужно переименовать файл при загрузке. Hash - это генерация уникального имени, в зависимости от содержимого файла. Friendly - генерация имени по алгоритму дружественных url страниц сайта (они управляются системными настройками).';
$_lang['ms2gallery_source_imageUploadDir_desc'] = 'Направление загрузки файлов. 1 (по умолчанию) - загрузка файлов в конец списка. 0 - загрузка файлов в начало списка.';