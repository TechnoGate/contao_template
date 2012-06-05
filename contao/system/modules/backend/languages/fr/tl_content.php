<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Cyril Ponce 2007 - 2011
 * @copyright  Patrick Lefèvre 2008 - 2009
 * @copyright  Sophie Bertholet 2009
 * @copyright  Marie Noëlle Augendre 2009
 * @copyright  Jean-Christophe Brebion 2009
 * @copyright  Michel Laurent 2011
 * @author     Cyril Ponce <cyril@contao.fr>
 * @author     Patrick Lefèvre <patrick.lefevre@gmail.com>
 * @author     Sophie Bertholet <sophie.typolight@gmail.com>
 * @author     Marie Noëlle Augendre <mnaugendre@gmail.com>
 * @author     Jean-Christophe Brebion <jean-christophe@fairytree.net>
 * @author     Michel Laurent <michel.laurent@equipc.net>
 * @link       http://www.contao.fr
 * @package    French
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_LANG']['tl_content']['invisible'] = array('Non visible', 'L\'élément ne sera pas visible sur le site.');
$GLOBALS['TL_LANG']['tl_content']['type'] = array('Type d\'élément', 'Choisir le type de l\'élément de contenu.');
$GLOBALS['TL_LANG']['tl_content']['headline'] = array('Titre', 'Saisir un titre qui sera affiché au dessus de l\'élément de contenu.');
$GLOBALS['TL_LANG']['tl_content']['text'] = array('Texte', 'Vous pouvez utiliser des balises HTML pour formater le texte.');
$GLOBALS['TL_LANG']['tl_content']['addImage'] = array('Ajouter une image', 'Ajouter une image à l\'élément de contenu.');
$GLOBALS['TL_LANG']['tl_content']['singleSRC'] = array('Fichier source', 'Sélectionner un fichier à partir de l\'explorateur de fichiers.');
$GLOBALS['TL_LANG']['tl_content']['alt'] = array('Texte alternatif', 'Saisir un texte alternatif pour l\'image (attribut <em>alt</em> )');
$GLOBALS['TL_LANG']['tl_content']['title'] = array('Titre', 'Saisir un titre pour l\'image (attribut <em>title</em> )');
$GLOBALS['TL_LANG']['tl_content']['size'] = array('Largeur et hauteur de l\'image', 'Saisir les dimensions de l\'image et le mode de redimensionnement.');
$GLOBALS['TL_LANG']['tl_content']['imagemargin'] = array('Marge de l\'image', 'Saisir la marge supérieure, droite, inférieure et gauche.');
$GLOBALS['TL_LANG']['tl_content']['imageUrl'] = array('Cible du lien', 'Il ne sera pas possible de voir l\'image en taille réelle si vous utilisez une cible de lien personnalisée.');
$GLOBALS['TL_LANG']['tl_content']['fullsize'] = array('Vue en taille réelle / nouvelle fenêtre', 'Afficher l\'image en pleine taille si l\'on clique dessus, avec un effet lightbox, ou dans une nouvelle fenêtre du navigateur.');
$GLOBALS['TL_LANG']['tl_content']['floating'] = array('Alignement de l\'image', 'Spécifier comment aligner l\'image.');
$GLOBALS['TL_LANG']['tl_content']['caption'] = array('Légende de l\'image', 'Saisir un court texte qui s\'affichera au-dessous de l\'image.');
$GLOBALS['TL_LANG']['tl_content']['html'] = array('Code HTML', 'Saisir du texte au format HTML.');
$GLOBALS['TL_LANG']['tl_content']['listtype'] = array('Type de liste', 'Choisir un type de liste.');
$GLOBALS['TL_LANG']['tl_content']['listitems'] = array('Liste d\'éléments', 'Si JavaScript est désactivé, assurez-vous de sauvegarder vos changements avant de modifier l\'ordre.');
$GLOBALS['TL_LANG']['tl_content']['tableitems'] = array('Tableaux', 'Si JavaScript est désactivé, assurez-vous de sauvegarder vos changements avant de modifier l\'ordre.');
$GLOBALS['TL_LANG']['tl_content']['summary'] = array('Résumé du tableau', 'Afin de rendre les tableaux accessibles, fournissez toujours une description du contenu.');
$GLOBALS['TL_LANG']['tl_content']['thead'] = array('Entête du tableau', 'Si vous choisissez cette option, la première ligne sera employée comme en-tête du tableau.');
$GLOBALS['TL_LANG']['tl_content']['tfoot'] = array('Pied du tableau', 'Si vous choisissez cette option, la dernière ligne sera employée comme titre de pied de tableau.');
$GLOBALS['TL_LANG']['tl_content']['tleft'] = array('Utiliser les entêtes de ligne', 'Définir la colonne de gauche comme entête de ligne.');
$GLOBALS['TL_LANG']['tl_content']['sortable'] = array('Peut être trié', 'Rendre le tableau triable (nécessite JavaScript et un entête de tableau).');
$GLOBALS['TL_LANG']['tl_content']['sortIndex'] = array('Index de tri', 'Le numéro de la colonne de tri par défaut.');
$GLOBALS['TL_LANG']['tl_content']['sortOrder'] = array('Ordre de tri', 'Choisir un ordre de tri par défaut.');
$GLOBALS['TL_LANG']['tl_content']['mooType'] = array('Mode de fonctionnement', 'Sélectionner le mode de fonctionnement de l\'élément accordéon.');
$GLOBALS['TL_LANG']['tl_content']['single'] = array('Élément simple', 'Se comporte comme un seul élément de texte qui est à l\'intérieur d\'un accordéon.');
$GLOBALS['TL_LANG']['tl_content']['start'] = array('Début du conteneur', 'Marque le début d\'un accordéon qui englobe plusieurs éléments de contenu.');
$GLOBALS['TL_LANG']['tl_content']['stop'] = array('Fin du conteneur', 'Marque la fin d\'un accordéon qui englobe plusieurs éléments de contenu.');
$GLOBALS['TL_LANG']['tl_content']['mooHeadline'] = array('Titre de l\'accordéon', 'Saisir le titre de l\'élément accordéon (HTML permis).');
$GLOBALS['TL_LANG']['tl_content']['mooStyle'] = array('Formatage CSS', 'Vous pouvez saisir du code CSS pour formater le titre');
$GLOBALS['TL_LANG']['tl_content']['mooClasses'] = array('Classes CSS de l\'élément', 'Laissez vide pour utiliser les classes par défaut ou saisissez des classes personnalisées.');
$GLOBALS['TL_LANG']['tl_content']['shClass'] = array('Configuration', 'Vous pouvez configurer la coloration syntaxique (ex : <em>gutter: false;</em>).');
$GLOBALS['TL_LANG']['tl_content']['highlight'] = array('Coloration syntaxique', 'Choisir un langage de programmation.');
$GLOBALS['TL_LANG']['tl_content']['code'] = array('Code', 'Noter que le code ne sera pas exécuté. Utiliser F11 pour basculer en mode plein écran si vous utilisez l\'éditeur de code.');
$GLOBALS['TL_LANG']['tl_content']['linkTitle'] = array('Titre du lien', 'Le titre du lien sera affiché à la place de l\'URL cible..');
$GLOBALS['TL_LANG']['tl_content']['embed'] = array('Inclure le lien', 'Utiliser le joker "%s" pour inclure le lien dans une phrase (ex : <em>Pour plus d\'informations visitez %s!</em>).');
$GLOBALS['TL_LANG']['tl_content']['rel'] = array('Lightbox', 'Pour déclencher l\'effet lightbox, entrez un attribut "rel" ici.');
$GLOBALS['TL_LANG']['tl_content']['useImage'] = array('Image lien', 'Utiliser une image à la place du titre du lien.');
$GLOBALS['TL_LANG']['tl_content']['multiSRC'] = array('Source des fichiers', 'Choisir un ou plusieurs fichiers ou répertoires (les fichiers contenus dans un répertoires seront inclus automatiquement).');
$GLOBALS['TL_LANG']['tl_content']['useHomeDir'] = array('Utiliser le répertoire personnel', 'Utiliser le répertoire personnel d\'un membre comme source de fichiers.');
$GLOBALS['TL_LANG']['tl_content']['perRow'] = array('Vignettes par ligne', 'Saisir le nombre de vignettes par ligne.');
$GLOBALS['TL_LANG']['tl_content']['perPage'] = array('Nombre d\'éléments par page', 'Le nombre d\'éléments par page. Saisissez 0 pour désactiver la pagination.');
$GLOBALS['TL_LANG']['tl_content']['numberOfItems'] = array('Nombre total d\'images', 'Limiter le nombre total d\'images. Mettre à 0 pour tout afficher.');
$GLOBALS['TL_LANG']['tl_content']['sortBy'] = array('Trier par', 'Choisir un type de tri.');
$GLOBALS['TL_LANG']['tl_content']['galleryTpl'] = array('Modèle de galerie', 'Sélectionner un modèle de galerie.');
$GLOBALS['TL_LANG']['tl_content']['cteAlias'] = array('Élément référencé', 'Choisir l\'élément de contenu que vous voulez insérer.');
$GLOBALS['TL_LANG']['tl_content']['articleAlias'] = array('Article référencé', 'Choisir l\'article que vous voulez insérer.');
$GLOBALS['TL_LANG']['tl_content']['article'] = array('Article', 'Sélectionner un article');
$GLOBALS['TL_LANG']['tl_content']['form'] = array('Formulaire', 'Sélectionner un formulaire.');
$GLOBALS['TL_LANG']['tl_content']['module'] = array('Module', 'Sélectionner un module.');
$GLOBALS['TL_LANG']['tl_content']['protected'] = array('Protéger l\'élément', 'L\'élément sera affiché à certains groupes de membre seulement.');
$GLOBALS['TL_LANG']['tl_content']['groups'] = array('Groupes de membres autorisés', 'Ces groupes de membres seront autorisés à voir le contenu de l\'élément.');
$GLOBALS['TL_LANG']['tl_content']['guests'] = array('Visible par les invités seulement', 'Cacher l\'élément si un membre est connecté.');
$GLOBALS['TL_LANG']['tl_content']['cssID'] = array('ID / classe(s) CSS', 'Saisir un ID de feuille de style et/ou une ou plusieurs classes.');
$GLOBALS['TL_LANG']['tl_content']['space'] = array('Espace avant et après', 'Saisir l\'espace avant et après l\'élément en pixel. Il est conseillé de gérer ces espacements dans une feuille de style.');
$GLOBALS['TL_LANG']['tl_content']['type_legend'] = 'Type d\'élément';
$GLOBALS['TL_LANG']['tl_content']['text_legend'] = 'Texte / HTML / Code';
$GLOBALS['TL_LANG']['tl_content']['image_legend'] = 'Paramètres d\'image';
$GLOBALS['TL_LANG']['tl_content']['list_legend'] = 'Eléments de la liste';
$GLOBALS['TL_LANG']['tl_content']['table_legend'] = 'Eléments du tableau';
$GLOBALS['TL_LANG']['tl_content']['tconfig_legend'] = 'Configuration du tableau';
$GLOBALS['TL_LANG']['tl_content']['sortable_legend'] = 'Options de tri';
$GLOBALS['TL_LANG']['tl_content']['moo_legend'] = 'Paramètres de l\'accordéon';
$GLOBALS['TL_LANG']['tl_content']['link_legend'] = 'Paramètres du lien';
$GLOBALS['TL_LANG']['tl_content']['imglink_legend'] = 'Paramètres de l\'Image lien';
$GLOBALS['TL_LANG']['tl_content']['source_legend'] = 'Fichiers et répertoires';
$GLOBALS['TL_LANG']['tl_content']['dwnconfig_legend'] = 'Paramètres du téléchargement';
$GLOBALS['TL_LANG']['tl_content']['include_legend'] = 'Paramètres de l\'élément inclus';
$GLOBALS['TL_LANG']['tl_content']['protected_legend'] = 'Protection d\'accès';
$GLOBALS['TL_LANG']['tl_content']['expert_legend'] = 'Paramètres avancés';
$GLOBALS['TL_LANG']['tl_content']['template_legend'] = 'Paramètres des modèles';
$GLOBALS['TL_LANG']['tl_content']['ordered'] = 'liste ordonnée';
$GLOBALS['TL_LANG']['tl_content']['unordered'] = 'liste désordonnée';
$GLOBALS['TL_LANG']['tl_content']['name_asc'] = 'Nom de fichier (ascendant)';
$GLOBALS['TL_LANG']['tl_content']['name_desc'] = 'Nom de fichier (descendant)';
$GLOBALS['TL_LANG']['tl_content']['date_asc'] = 'Date (ascendant)';
$GLOBALS['TL_LANG']['tl_content']['date_desc'] = 'Date (descendant)';
$GLOBALS['TL_LANG']['tl_content']['meta'] = 'Fichier méta (meta.txt)';
$GLOBALS['TL_LANG']['tl_content']['random'] = 'Ordre aléatoire';
$GLOBALS['TL_LANG']['tl_content']['new'] = array('Nouvel élément', 'Créer un nouvel élément');
$GLOBALS['TL_LANG']['tl_content']['show'] = array('Détails de l\'élément', 'Afficher les détails de l\'élément de contenu ID %s');
$GLOBALS['TL_LANG']['tl_content']['cut'] = array('Déplacer l\'élément', 'Déplacer l\'élément de contenu ID %s');
$GLOBALS['TL_LANG']['tl_content']['copy'] = array('Dupliquer l\'élément', 'Dupliquer l\'élément de contenu ID %s');
$GLOBALS['TL_LANG']['tl_content']['delete'] = array('Supprimer l\'élément', 'Supprimer l\'élément de contenu ID %s');
$GLOBALS['TL_LANG']['tl_content']['edit'] = array('Éditer l\'élément', 'Éditer l\'élément de contenu ID %s');
$GLOBALS['TL_LANG']['tl_content']['editheader'] = array('Éditer les paramètres de l\'article', 'Éditer les paramètres de l\'article');
$GLOBALS['TL_LANG']['tl_content']['pasteafter'] = array('Coller au début', 'Coller après l\'élément de contenu ID %s');
$GLOBALS['TL_LANG']['tl_content']['pastenew'] = array('Créer un nouvel élément de contenu au début', 'Créer un élément de contenu après l\'élément ID %s');
$GLOBALS['TL_LANG']['tl_content']['toggle'] = array('Basculer l\'affichage', 'Bascule l\'affichage de l\'élément ID %s');
$GLOBALS['TL_LANG']['tl_content']['editalias'] = array('Éditer la source de l\'élément', 'Éditer la source de l\'élément ID %s');
$GLOBALS['TL_LANG']['tl_content']['editarticle'] = array('Éditer l\'article', 'Éditer l\'article ID %s');

?>