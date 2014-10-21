<?php
/**
 * Properties Russian Lexicon Entries for ms2Gallery
 *
 * @package ms2gallery
 * @subpackage lexicon
 */
$_lang['ms2gallery_prop_resource'] = 'ID de l\'article. Si vide, l\'ID du document courant sera utilisé.';

$_lang['ms2gallery_prop_tplRow'] = '"Chunk" pour le modèle  d\'une ligne de requête.';
$_lang['ms2gallery_prop_tplOuter'] = 'Modèle d\'enveloppe pour les résultats du travail d\'un "snippet".';
$_lang['ms2gallery_prop_tplEmpty'] = '"Chunk" renvoyé quand il n\'y a aucun résultat.';

$_lang['ms2gallery_prop_limit'] = 'Limter les résultats a ce nombre.';
$_lang['ms2gallery_prop_offset'] = 'Décalage dans les résultats retournés';
$_lang['ms2gallery_prop_sortby'] = 'Champ de tri. Pour trier les articles sur un champ spécifique vous devez ajouter le préfix "Data.", par exemple : "&sortby=`Data.price`"';
$_lang['ms2gallery_prop_sortdir'] = 'Direction du tri';
$_lang['ms2gallery_prop_toPlaceholder'] = 'Si non vide, le "snippet" sauvegardera sa sortie dans l\'emplacement de ce nom au lieu de le retourner à l\'écran.';
$_lang['ms2gallery_prop_showLog'] = 'Afficher les information additionnelle à propos du travail de ce "snippet". Seulement si authentifié dans le context "mgr".';
$_lang['ms2gallery_prop_where'] = 'Une expression dans un style JSON pour construire une clause "where" additionnelle.';

$_lang['ms2gallery_prop_parents'] = 'Liste des conteneurs séparée par une virgule pour chaque résultats. Cette requête est par défaut limitée au parent courrant. Si 0, elle ne sera plus limitée.';
$_lang['ms2gallery_prop_resources'] = 'Liste des ids a inclure dans le résultat. Préfixer un id par un tiret va l\'exclure du résultat.';
$_lang['ms2gallery_prop_prefix'] = 'Le préfixe pour les propriétés des images, "img" par exemple. Par défaut, il est "ms2g".';
$_lang['ms2gallery_prop_showInactive'] = 'Show inactive images.';

$_lang['ms2gallery_prop_frontend_css'] = 'Chemin du fichier avec des styles de la boutique. Si vous souhaitez utiliser vos propres styles - préciser ici, ou nettoyer ce paramètre et de les charger dans le modèle de site.';
$_lang['ms2gallery_prop_frontend_js'] = 'Chemin du fichier avec son de la boutique. Si vous souhaitez utiliser vos propres sscripts - préciser ici, ou nettoyer ce paramètre et de les charger dans le modèle de site.';

$_lang['ms2gallery_prop_typeOfJoin'] = 'Type de l`adhésion des images de la ressource. Left, Left Join, qui est, les ressources seront choisis, même si elles n`ont pas d`images. Et inner est un Inner Join, seront choisis de ressources uniquement avec des images.';
$_lang['ms2gallery_prop_includeThumbs'] = 'Liste des autorisations extraits séparés par des virgules. Par exemple, "120x90,360x270".';
$_lang['ms2gallery_prop_includeOriginal'] = 'Ajouter dans l`échantillon supplémentaire de join avec le lien vers l`image originale. Sera disponible dans le tableau de la ressource comme "résolution.original", par exemple "120x90.original".';