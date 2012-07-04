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

$GLOBALS['TL_LANG']['tl_module']['name'] = array('Nom du module', 'Saisir le nom du module.');
$GLOBALS['TL_LANG']['tl_module']['headline'] = array('Titre', 'Saisir un titre à afficher en haut du module.');
$GLOBALS['TL_LANG']['tl_module']['type'] = array('Type de module', 'Choisir le type de module.');
$GLOBALS['TL_LANG']['tl_module']['levelOffset'] = array('Niveau de départ', 'Saisir une valeur supérieure à 0 pour ne montrer que des sous-menus.');
$GLOBALS['TL_LANG']['tl_module']['showLevel'] = array('Niveau de fin', 'Saisir une valeur supérieure à 0 pour limiter le niveau d\'imbrication du menu.');
$GLOBALS['TL_LANG']['tl_module']['hardLimit'] = array('Limite', 'Ne montrer aucun élément de menu au-delà du niveau de fin.');
$GLOBALS['TL_LANG']['tl_module']['showProtected'] = array('Afficher les éléments protégés', 'Afficher les éléments qui ne sont normalement visibles qu\'aux membres connectés.');
$GLOBALS['TL_LANG']['tl_module']['defineRoot'] = array('Définir une page de référence', 'Définir une page source ou cible pour le module.');
$GLOBALS['TL_LANG']['tl_module']['rootPage'] = array('Page de référence', 'Choisir une page de référence à partir de la structure du site.');
$GLOBALS['TL_LANG']['tl_module']['navigationTpl'] = array('Modèle de navigation', 'Choisir un modèle de navigation.');
$GLOBALS['TL_LANG']['tl_module']['pages'] = array('Pages', 'Choisir une ou plusieurs pages à partir de la structure du site.');
$GLOBALS['TL_LANG']['tl_module']['showHidden'] = array('Afficher les éléments cachés', 'Afficher les éléments habituellement cachés dans le menu de navigation.');
$GLOBALS['TL_LANG']['tl_module']['customLabel'] = array('Libellé personnalisé', 'Saisir un libellé personnalisé.');
$GLOBALS['TL_LANG']['tl_module']['autologin'] = array('Autoriser la connexion automatique', 'Autoriser les membres à se connecter automatiquement.');
$GLOBALS['TL_LANG']['tl_module']['jumpTo'] = array('Page de redirection', 'Choisir la page vers laquelle les visiteurs seront redirigés à la suite d\'un clic sur un lien ou à la soumission d\'un formulaire.');
$GLOBALS['TL_LANG']['tl_module']['redirectBack'] = array('Redirection vers la dernière page visitée', 'Rediriger l\'utilisateur vers la dernière page visitée au lieu de la page de redirection.');
$GLOBALS['TL_LANG']['tl_module']['cols'] = array('Nombre de colonnes', 'Choisir le nombre de colonnes du formulaire.');
$GLOBALS['TL_LANG']['tl_module']['1cl'] = array('Une colonne', 'Le libellé sera affiché au-dessus du champ.');
$GLOBALS['TL_LANG']['tl_module']['2cl'] = array('Deux colonnes', 'Le libellé sera affiché sur la gauche du champ.');
$GLOBALS['TL_LANG']['tl_module']['editable'] = array('Champs éditables', 'Afficher ces champs dans le formulaire du front office.');
$GLOBALS['TL_LANG']['tl_module']['memberTpl'] = array('Modèle du formulaire', 'Choisir un modèle de formulaire.');
$GLOBALS['TL_LANG']['tl_module']['tableless'] = array('Disposition sans tableau', 'Création du formulaire sans tableau HTML.');
$GLOBALS['TL_LANG']['tl_module']['form'] = array('Formulaire', 'Sélectionner un formulaire.');
$GLOBALS['TL_LANG']['tl_module']['queryType'] = array('Type de requête par défaut', 'Choisir le type de requête par défaut.');
$GLOBALS['TL_LANG']['tl_module']['and'] = array('Trouver tous les mots', 'Renvoie seulement les pages qui contiennent tous les mots-clés.');
$GLOBALS['TL_LANG']['tl_module']['or'] = array('Trouver au moins un mot', 'Renvoie toutes les pages qui contiennent au moins un des mots-clés.');
$GLOBALS['TL_LANG']['tl_module']['fuzzy'] = array('Recherche approximative', 'Trouvera "Contao" si vous recherchez "con" (égal à une recherche générique).');
$GLOBALS['TL_LANG']['tl_module']['simple'] = array('Formulaire simple', 'Contient un champ de saisie seulement.');
$GLOBALS['TL_LANG']['tl_module']['advanced'] = array('Formulaire avancé', 'Contient un champ de saisie et un menu de boutons à cocher pour choisir le type de requête.');
$GLOBALS['TL_LANG']['tl_module']['contextLength'] = array('Étendu du contexte', 'Saisir le nombre de caractères à droite et à gauche de chaque mot-clé qui doivent être utilisés comme contexte.');
$GLOBALS['TL_LANG']['tl_module']['totalLength'] = array('Longueur maximale du contexte', 'Saisir la longueur maximale du contexte de chaque résultat.');
$GLOBALS['TL_LANG']['tl_module']['perPage'] = array('Éléments par page', 'Le nombre d\'éléments par page. Saisir 0 pour désactiver la pagination.');
$GLOBALS['TL_LANG']['tl_module']['searchType'] = array('Formulaire de recherche', 'Choisir un formulaire de recherche.');
$GLOBALS['TL_LANG']['tl_module']['searchTpl'] = array('Modèle du résultat de recherche', 'Choisir un modèle de résultat de recherche.');
$GLOBALS['TL_LANG']['tl_module']['inColumn'] = array('Colonne', 'Choisir la colonne contenant les articles que vous voulez énumérer.');
$GLOBALS['TL_LANG']['tl_module']['skipFirst'] = array('Ignorer des éléments', 'Définir combien d\'éléments seront ignorés.');
$GLOBALS['TL_LANG']['tl_module']['loadFirst'] = array('Charger le premier élément', 'Rediriger automatiquement vers le premier élément si aucun n\'est sélectionné.');
$GLOBALS['TL_LANG']['tl_module']['size'] = array('Largeur et hauteur', 'Saisir la largeur et la hauteur en pixel.');
$GLOBALS['TL_LANG']['tl_module']['transparent'] = array('Animation Flash transparente', 'Rendre l\'animation Flash transparente (wmode = transparent).');
$GLOBALS['TL_LANG']['tl_module']['flashvars'] = array('FlashVars', 'Saisir les variables qui seront passées à l\'animation Flash (<em>var1=value1&var2=value2</em>).');
$GLOBALS['TL_LANG']['tl_module']['version'] = array('Version du lecteur Flash', 'Saisir la version du lecteur Flash requise (ex: 6.0.12).');
$GLOBALS['TL_LANG']['tl_module']['altContent'] = array('Contenu alternatif', 'Saisir un contenu alternatif à afficher au cas où l\'animation Flash ne pourrait être chargée. Le HTML est autorisé.');
$GLOBALS['TL_LANG']['tl_module']['source'] = array('Source', 'Choisir d\'utiliser un fichier sur le serveur ou de pointer vers une URL externe.');
$GLOBALS['TL_LANG']['tl_module']['singleSRC'] = array('Fichier source', 'Sélectionner un fichier à partir du gestionnaire de fichiers.');
$GLOBALS['TL_LANG']['tl_module']['url'] = array('URL', 'Saisir l\'URL (http://...) de l\'animation Flash.');
$GLOBALS['TL_LANG']['tl_module']['interactive'] = array('Animation Flash interactive', 'Faire interagir votre animation Flash avec le navigateur (nécessite Javascript).');
$GLOBALS['TL_LANG']['tl_module']['flashID'] = array('Animation Flash ID', 'Saisir un ID unique pour l\'animation Flash.');
$GLOBALS['TL_LANG']['tl_module']['flashJS'] = array('Javascript _DoFSCommand(command, args) {', 'Saisir du code Javascript.');
$GLOBALS['TL_LANG']['tl_module']['fullsize'] = array('Pleine taille / nouvelle fenêtre', 'Ouvrir l\'image grandeur réelle dans une lightbox ou le lien dans une autre fenêtre du navigateur.');
$GLOBALS['TL_LANG']['tl_module']['imgSize'] = array('Largeur et hauteur de l\'image', 'Saisir seulement la largeur ou la hauteur pour redimensionner l\'image. En saisissant les deux dimensions, l\'image sera recadrée.');
$GLOBALS['TL_LANG']['tl_module']['useCaption'] = array('Afficher le libellé', 'Afficher le nom de l\'image ou la description au-dessous de l\'image.');
$GLOBALS['TL_LANG']['tl_module']['multiSRC'] = array('Fichiers source', 'Sélectionner un ou plusieurs fichiers à partir du gestionnaire de fichiers.');
$GLOBALS['TL_LANG']['tl_module']['html'] = array('Code HTML', 'Vous pouvez modifier les balises HTML autorisées dans le menu "Configuration".');
$GLOBALS['TL_LANG']['tl_module']['protected'] = array('Protéger le module', 'Afficher le module à certains groupes de membre seulement.');
$GLOBALS['TL_LANG']['tl_module']['groups'] = array('Groupes de membres autorisés', 'Ces groupes de membres seront autorisés à voir le module.');
$GLOBALS['TL_LANG']['tl_module']['guests'] = array('Visible par les invités seulement', 'Cacher l\'élément si un membre est connecté.');
$GLOBALS['TL_LANG']['tl_module']['cssID'] = array('ID / classe(s) CSS', 'Saisir un ID de feuille de style et/ou une ou plusieurs classes.');
$GLOBALS['TL_LANG']['tl_module']['space'] = array('Espace avant et après', 'Saisir l\'espace avant et après le module en pixel. Dans l\'idéal, vous devriez définir ces valeurs dans une feuille de style.');
$GLOBALS['TL_LANG']['tl_module']['title_legend'] = 'Titre et type';
$GLOBALS['TL_LANG']['tl_module']['nav_legend'] = 'Paramètres de navigation';
$GLOBALS['TL_LANG']['tl_module']['reference_legend'] = 'Page de référence';
$GLOBALS['TL_LANG']['tl_module']['redirect_legend'] = 'Paramètres de redirection';
$GLOBALS['TL_LANG']['tl_module']['template_legend'] = 'Paramètres des modèles';
$GLOBALS['TL_LANG']['tl_module']['config_legend'] = 'Configuration du module';
$GLOBALS['TL_LANG']['tl_module']['include_legend'] = 'Parmètres des éléments inclus';
$GLOBALS['TL_LANG']['tl_module']['source_legend'] = 'Fichiers et répertoires';
$GLOBALS['TL_LANG']['tl_module']['interact_legend'] = 'Animation Flash interactive';
$GLOBALS['TL_LANG']['tl_module']['html_legend'] = 'Texte / HTML';
$GLOBALS['TL_LANG']['tl_module']['protected_legend'] = 'Protection d\'accès';
$GLOBALS['TL_LANG']['tl_module']['expert_legend'] = 'Paramètres avancés';
$GLOBALS['TL_LANG']['tl_module']['email_legend'] = 'Paramètres d\'e-mail';
$GLOBALS['TL_LANG']['tl_module']['header'] = 'En-tête';
$GLOBALS['TL_LANG']['tl_module']['left'] = 'Colonne de gauche';
$GLOBALS['TL_LANG']['tl_module']['main'] = 'Colonne principale';
$GLOBALS['TL_LANG']['tl_module']['right'] = 'Colonne de droite';
$GLOBALS['TL_LANG']['tl_module']['footer'] = 'Pied de page';
$GLOBALS['TL_LANG']['tl_module']['internal'] = 'Fichier interne';
$GLOBALS['TL_LANG']['tl_module']['external'] = 'URL externe';
$GLOBALS['TL_LANG']['tl_module']['new'] = array('Ajouter un module', 'Ajouter un module');
$GLOBALS['TL_LANG']['tl_module']['show'] = array('Détails du module', 'Afficher les détails du module ID %s');
$GLOBALS['TL_LANG']['tl_module']['edit'] = array('Éditer le module', 'Éditer le module ID %s');
$GLOBALS['TL_LANG']['tl_module']['cut'] = array('Déplacer le module', 'Déplacer le module ID %s');
$GLOBALS['TL_LANG']['tl_module']['copy'] = array('Dupliquer le module', 'Dupliquer le module ID %s');
$GLOBALS['TL_LANG']['tl_module']['delete'] = array('Supprimer le module', 'Supprimer le module ID %s');
$GLOBALS['TL_LANG']['tl_module']['editheader'] = array('Éditer le thème', 'Éditer les paramètres du thème');
$GLOBALS['TL_LANG']['tl_module']['pasteafter'] = array('Coller ici', 'Coller après le module ID %s');

?>