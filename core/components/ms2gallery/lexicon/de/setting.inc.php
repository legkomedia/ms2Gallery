<?php
/**
 * Settings German Lexicon Entries for ms2Gallery
 *
 * @package ms2gallery
 * @subpackage lexicon
 */
$_lang['area_ms2gallery_resource'] = 'Ressource';
$_lang['area_ms2gallery_frontend'] = 'Frontend';

$_lang['setting_ms2gallery_source_default'] = 'Standard Medienquelle';
$_lang['setting_ms2gallery_source_default_desc'] = 'Standard Medienquelle für die Produktgalerie.';
$_lang['setting_ms2gallery_thumbnail_size'] = 'Standard Thumbnail-Grösse';
$_lang['setting_ms2gallery_thumbnail_size_desc'] = 'Size of default pre-generated thumbnail for field "thumb" in msProduct table. Of course, this size should exist in the settings of your media source that generates the previews. Otherwise you will receive  miniShop2 logo instead of product image in manager.';

$_lang['setting_ms2gallery_date_format'] = 'Datums-Format';
$_lang['setting_ms2gallery_date_format_desc'] = 'ms2Gallery formatiert das Datum mit Hilfe der PHP strftime() Syntax. Standardmäßig ist das Format "%d.%m.%y %H:%M".';

$_lang['setting_ms2gallery_set_placeholders'] = 'Enable auto-placeholders?';
$_lang['setting_ms2gallery_set_placeholders_desc'] = 'You can enable auto set of placeholders with images of gallery on page.';
$_lang['setting_ms2gallery_placeholders_prefix'] = 'Prefix of placeholders';
$_lang['setting_ms2gallery_placeholders_prefix_desc'] = 'You can specify placeholders prefix for images. Than you will can use it as [[+prefix.rank.field]]. For example: [[+ms2g.0.url]] or [[+ms2g.1.120x90]]. Placeholder [[+prefix.rank]] will print array with the whole available file properties.';

$_lang['setting_ms2gallery_disable_for_templates'] = 'Deaktivieren für Vorlagen';
$_lang['setting_ms2gallery_disable_for_templates_desc'] = 'Geben Sie eine Komma-getrennte Liste von ids der a-Vorlagen, für die Sie nicht wollen, um die Anzeige der Registerkarte mit Galerie.';

$_lang['setting_ms2gallery_page_size'] = 'Anzahl der Dateien auf Seite';
$_lang['setting_ms2gallery_page_size_desc'] = 'Sie können die Anzahl der Dateien auf der Seite angezeigt gesetzt, Standard ist 20. 0 - zeigt alle Dateien.';

$_lang['ms2gallery_source_thumbnails_desc'] = 'JSON codiert Reihe von Optionen für die Generierung Thumbnails.';
$_lang['ms2gallery_source_maxUploadWidth_desc'] = 'Maximale Breite des Bildes für den Upload. Alle Bilder, die diesen Parameter überschreitet, wird geändert, um zu passen.';
$_lang['ms2gallery_source_maxUploadHeight_desc'] = 'Maximale Höhe des Bildes für den Upload. Alle Bilder, die diesen Parameter überschreitet, wird geändert, um zu passen.';
$_lang['ms2gallery_source_maxUploadSize_desc'] = 'Maximale Größe der Datei für den Upload (in Bytes).';
$_lang['ms2gallery_source_imageNameType_desc'] = 'Diese Einstellung legt fest, wie eine Datei nach dem Upload umbenennen. Hash ist die Erzeugung eines eindeutigen Namen in Abhängigkeit vom Inhalt der Datei. Friendly - Generation im Auftrag des Algorithmus freundliche URLs von Seiten der Website (sie werden durch Systemeinstellungen verwaltet).';
$_lang['ms2gallery_source_imageUploadDir_desc'] = 'Nder Dateien hochladen. 1 (Standard) - Herunterladen von Dateien an der Liste. 0 - Herunterladen von Dateien an die Spitze.';