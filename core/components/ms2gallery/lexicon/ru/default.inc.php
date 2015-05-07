<?php
/**
* Products Russian Lexicon Entries for ms2Gallery
*
* @package ms2gallery
* @subpackage lexicon
*/
include_once('setting.inc.php');
$files = scandir(dirname(__FILE__));
foreach ($files as $file) {
	if (strpos($file, 'msp.') === 0) {
		@include_once($file);
	}
}

$_lang['ms2gallery'] = 'Галерея';
$_lang['ms2gallery_source'] = 'Источник файлов';
$_lang['ms2gallery_source_help'] = 'Источник файлов, который хранят изображения ресурса.';
$_lang['ms2gallery_change_source_confirm'] = 'Вы уверены, что хотите изменить источник файлов? ВНИМАНИЕ: При этом будут сохранены все изменения, убедитесь, что вы готовы сделать это перед продолжением.';

$_lang['ms2gallery_disabled_while_creating'] = '<i>Эта функция отключена при создании нового ресурса.</i>';

$_lang['ms2gallery_parent'] = '';
$_lang['ms2gallery_parent_help'] = '';
$_lang['ms2gallery_emptymsg'] = 'Перетяните в эту таблицу файлы для загрузки, или выберите их кнопкой вверху.';
$_lang['ms2gallery_introtext'] = 'Здесь вы управляете галереей ресурса. Вы можете загрузить новые изображения просто перетянув их в таблицу.';
$_lang['ms2gallery_id'] = 'Id';
$_lang['ms2gallery_rank'] = 'Позиция';
$_lang['ms2gallery_filename'] = 'Имя файла';
$_lang['ms2gallery_size'] = 'Размер';
$_lang['ms2gallery_status'] = 'Статус';
$_lang['ms2gallery_progress'] = 'Прогресс';
$_lang['ms2gallery_inactive'] = 'Отключено';
$_lang['ms2gallery_createdon'] = 'Дата загрузки';
$_lang['ms2gallery_createdby'] = 'Пользователь';
$_lang['ms2gallery_url'] = 'Адрес';
$_lang['ms2gallery_name'] = 'Название';
$_lang['ms2gallery_status'] = 'Статус';
$_lang['ms2gallery_button_upload'] = 'Выбрать файлы';
$_lang['ms2gallery_uploads_clear'] = 'Очистить';
$_lang['ms2gallery_generate_thumbs'] = 'Обновить все превьюшки';
$_lang['ms2gallery_browse_files'] = 'Посмотреть файлы';
$_lang['ms2gallery_images_selected'] = 'Нажмите для выбора';
$_lang['ms2gallery_combo_select'] = 'Нажмите для выбора';

$_lang['ms2gallery_image_generate_thumbs'] = 'Обновить превьюшки';
$_lang['ms2gallery_file_update'] = 'Изменить свойства';
$_lang['ms2gallery_file_show'] = 'Открыть в новом окне';
$_lang['ms2gallery_file_delete'] = 'Удалить файл';
$_lang['ms2gallery_file_delete_confirm'] = 'Вы действительно хотите удалить этот файл вместе со всеми его уменьшенными копиями? Эта операция необратима.';
$_lang['ms2gallery_file_delete_multiple'] = 'Удалить файлы';
$_lang['ms2gallery_file_delete_multiple_confirm'] = 'Вы действительно хотите удалить эти файлы со всеми их уменьшенными копиями? Эта операция необратима.';
$_lang['ms2gallery_file_activate'] = 'Включить файл';
$_lang['ms2gallery_file_inactivate'] = 'Отключить файл';
$_lang['ms2gallery_file_activate_multiple'] = 'Включить файлы';
$_lang['ms2gallery_file_inactivate_multiple'] = 'Отключить файлы';
$_lang['ms2gallery_file_edit_tags'] = 'Изменить теги';
$_lang['ms2gallery_file_edit_tags_intro'] = 'Эта операция перезапишет все теги выбранных файлов!';

$_lang['ms2gallery_message_close_all'] = 'закрыть все';
$_lang['ms2gallery_err_unknown'] = 'Неизвестная ошибка';
$_lang['ms2gallery_err_ns'] = 'Это поле обязательно';
$_lang['ms2gallery_err_ae'] = 'Это поле должно быть уникально';
$_lang['ms2gallery_err_register_globals'] = 'Ошибка: php параметр <b>register_globals</b> должен быть выключен.';
$_lang['ms2gallery_err_no_resource'] = 'Не найден ресурс';
$_lang['ms2gallery_err_no_source'] = 'Не указан источник';
$_lang['ms2gallery_err_no_file'] = 'Файл не найден';
$_lang['ms2gallery_err_wrong_ext'] = 'Неверное расширение файла';
$_lang['ms2gallery_err_no_file_saved'] = 'Файл не был сохранен';
$_lang['ms2gallery_err_gallery_thumb'] = 'Не могу сгенерировать превью (см. системный журнал).';
$_lang['ms2gallery_err_gallery_save'] = 'Не могу сохранить файл не был сохранён (см. системный журнал).';
$_lang['ms2gallery_err_gallery_ns'] = 'Передан пустой файл';
$_lang['ms2gallery_err_gallery_ext'] = 'Неверное расширение файла';
$_lang['ms2gallery_err_gallery_exists'] = 'Такое изображение уже есть в галерее ресурса.';
$_lang['ms2gallery_err_wrong_image'] = 'Файл не является корректным изображением.';

$_lang['ms2gallery_file_active'] = 'Активен';
$_lang['ms2gallery_file_inactive'] = 'Неактивен';
$_lang['ms2gallery_file_name'] = 'Файл';
$_lang['ms2gallery_file_title'] = 'Название';
$_lang['ms2gallery_file_description'] = 'Описание';
$_lang['ms2gallery_file_add'] = 'Дополнительно';
$_lang['ms2gallery_file_alt'] = 'Альтернативное имя';
$_lang['ms2gallery_file_tags'] = 'Теги (Группы)';
$_lang['ms2gallery_file_search'] = 'Поиск...';

$_lang['ms2gallery_status_code_1'] = 'В очереди';
$_lang['ms2gallery_status_code_2'] = 'В процессе';
$_lang['ms2gallery_status_code_4'] = 'Ошибка';
$_lang['ms2gallery_status_code_5'] = 'Готово';

$_lang['ms2gallery_errors'] = 'Ошибки при загрузке';