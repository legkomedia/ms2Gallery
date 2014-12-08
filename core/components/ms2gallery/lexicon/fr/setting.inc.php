<?php
/**
 * Settings France Lexicon Entries for ms2Gallery
 *
 * @package ms2gallery
 * @subpackage lexicon
 */
$_lang['area_ms2gallery_resource'] = 'Ressource';
$_lang['area_ms2gallery_frontend'] = 'Frontal web';

$_lang['setting_ms2gallery_source_default'] = 'Source des médias par défaut';
$_lang['setting_ms2gallery_source_default_desc'] = 'Source des médias par défaut pour la gallerie des articles.';
$_lang['setting_ms2gallery_thumbnail_size'] = 'Taille des vignettes par défaut';
$_lang['setting_ms2gallery_thumbnail_size_desc'] = 'Taille des vignettes défaut prégénérée pour le champ "pouce" dans le tableau msProduct. Bien sûr, cette taille ne devrait exister dans les paramètres de votre source de média qui génère les extraits. Sinon, vous recevrez miniShop2 logo à la place de image dans le gestionnaire de ressources.';
$_lang['setting_ms2gallery_date_format'] = 'Format des dates';
$_lang['setting_ms2gallery_date_format_desc'] = 'Vous pouvez spécifier comment formater les dates en utilisant php strftime() syntaxe. En format par défaut est "%d.%m.%y %H:%M".';
$_lang['setting_ms2gallery_disable_for_templates'] = 'Désactiver pour les modèles';
$_lang['setting_ms2gallery_disable_for_templates_desc'] = 'Indiquez liste séparée par des virgules de l`id d`un des modèles, pour lesquels vous ne souhaitez pas afficher l`onglet galerie.';

$_lang['setting_ms2gallery_set_placeholders'] = 'Enable auto-placeholders?';
$_lang['setting_ms2gallery_set_placeholders_desc'] = 'You can enable auto set of placeholders with images of gallery on page.';
$_lang['setting_ms2gallery_placeholders_prefix'] = 'Prefix of placeholders';
$_lang['setting_ms2gallery_placeholders_prefix_desc'] = 'You can specify placeholders prefix for images. Than you will can use it as [[+prefix.rank.field]]. For example: [[+ms2g.0.url]] or [[+ms2g.1.120x90]]. Placeholder [[+prefix.rank]] will print array with the whole available file properties.';
$_lang['setting_ms2gallery_placeholders_tpl'] = 'Template pour les espaces réservés';
$_lang['setting_ms2gallery_placeholders_tpl_desc'] = 'Indiquez la TV ou chunk pour modèle l`image des espaces réservés sur la page.';
$_lang['setting_ms2gallery_placeholders_thumbs'] = 'les Pouces pour des espaces réservés';
$_lang['setting_ms2gallery_placeholders_thumbs_desc'] = 'Indiquez une liste séparée par des virgules des tailles de pouce, vous voulez choisir de les installer dans des espaces réservés. Par exemple "120х90,360x270".';

$_lang['setting_ms2gallery_page_size'] = 'Nombre de fichiers à la page';
$_lang['setting_ms2gallery_page_size_desc'] = 'Vous pouvez définir le nombre de fichiers affichés sur la page, par défaut est 20. 0 - afficher tous les fichiers.';

$_lang['ms2gallery_source_thumbnails_desc'] = 'JSON codé avec des paramètres généré vignettes des images.';
$_lang['ms2gallery_source_maxUploadWidth_desc'] = 'La largeur maximale de l\'image à charger. Rien plus être uzhato à cette valeur.';
$_lang['ms2gallery_source_maxUploadHeight_desc'] = 'La hauteur maximale de l\'image à charger. Rien plus être uzhato à cette valeur.';
$_lang['ms2gallery_source_maxUploadSize_desc'] = 'La taille maximale des images téléchargées (en octets).';
$_lang['ms2gallery_source_imageNameType_desc'] = 'Cette option spécifie comment renommer le fichier au démarrage. Hash - générer un nom unique basé sur le contenu du fichier. Amical - au nom des pages URL conviviale algorithme de génération (ils sont contrôlés par les paramètres du système).';
$_lang['ms2gallery_source_imageUploadDir_desc'] = 'Direction télécharger des fichiers. 1 (par défaut) - télécharger des fichiers dans la liste. 0 - télécharger des fichiers vers le haut.';