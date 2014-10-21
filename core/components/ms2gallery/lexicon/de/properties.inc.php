<?php
/**
 * Properties German Lexicon Entries for ms2Gallery
 *
 * @package ms2gallery
 * @subpackage lexicon
 */
$_lang['ms2gallery_prop_resource'] = 'ID des ressource. Falls leer, wird die ID des aktuellen Dokuments verwendet.';

$_lang['ms2gallery_prop_tplRow'] = 'Chunk für Vorlage einer Reihe von Abfrage.';
$_lang['ms2gallery_prop_tplOuter'] = 'Wrapper für template Ergebnisse Snippet Arbeit.';
$_lang['ms2gallery_prop_tplEmpty'] = 'Chunk, der angezeigt wird, wenn keine Ergebnisse ausgegeben werden können.';

$_lang['ms2gallery_prop_limit'] = 'Anzahl der zu begrenzenden Ergebnisse.';
$_lang['ms2gallery_prop_offset'] = 'Eine der Ressourcen nach den Kriterien kehrte überspringen Offset.';
$_lang['ms2gallery_prop_sortby'] = 'Das Feld, nach dem sortiert.';
$_lang['ms2gallery_prop_sortdir'] = 'Sortierreihenfolge.';
$_lang['ms2gallery_prop_toPlaceholder'] = 'Wenn nicht leer, wird das Snippet Ausgang Platzhalter sparen mit diesem Namen, statt es zurück zum Bildschirm.';
$_lang['ms2gallery_prop_showLog'] = 'Anzeige zusätzlicher Informationen über Snippet Arbeit. Nur für in context "mgr" authentifiziert.';
$_lang['ms2gallery_prop_where'] = 'Ein JSON-Stil Ausdruck von Kriterien, um zusätzliche where-Klauseln von bauen';

$_lang['ms2gallery_prop_parents'] = 'Container Liste, durch Komma getrennt, zu den Suchergebnissen. Standardmäßig wird die Abfrage an den aktuellen übergeordneten begrenzt. Wenn auf 0 gesetzt, fragen nicht begrenzt.';
$_lang['ms2gallery_prop_resources'] = 'Durch Kommata getrennte Liste von IDs in den Ergebnissen enthalten. Präfix eine ID mit einem Schuss auf die Ressource aus dem Ergebnis auszuschließen.';
$_lang['ms2gallery_prop_prefix'] = 'Das Präfix für Bilder Eigenschaften "img" zum Beispiel. Standardmäßig ist "ms2g".';
$_lang['ms2gallery_prop_showInactive'] = 'Show inactive images.';

$_lang['ms2gallery_prop_frontend_css'] = 'Pfad zur Datei mit den Stilen des Shops. Wenn Sie möchten, verwenden Sie Ihre eigenen Stile, geben Sie hier, oder reinigen Sie diesen parameter, und laden Sie Sie in der site-template.';
$_lang['ms2gallery_prop_frontend_js'] = 'Pfad zu der Datei mit scripts für das Geschäft. Wenn Sie möchten, verwenden Sie Ihre eigenen Skripte, geben Sie hier, oder reinigen Sie diesen parameter, und laden Sie Sie in der site-template.';

$_lang['ms2gallery_prop_typeOfJoin'] = 'Typ Anschlüsse Bilder Ressource. Left ist ein Left Join, das heißt, die Ressourcen gewählt werden, auch wenn Sie keine Bilder. Und inner - es Inner Join, gewählt werden kann nur Ressourcen mit den Bildern.';
$_lang['ms2gallery_prop_includeThumbs'] = 'Liste der Berechtigungen Daumen durch Komma getrennt. Zum Beispiel "120x90,360x270".';
$_lang['ms2gallery_prop_includeOriginal'] = 'Hinzufügen in die Stichprobe zusätzliche join mit der Verbannung auf die ursprüngliche Bild. Verfügbar im array Ressource als "auflösung.original", Z. B. "120x90.original".';