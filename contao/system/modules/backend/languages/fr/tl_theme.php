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

$GLOBALS['TL_LANG']['tl_theme']['name'] = array('Titre du thème', 'Saisir un titre unique.');
$GLOBALS['TL_LANG']['tl_theme']['author'] = array('Auteur', 'Saisir le nom du concepteur du thème.');
$GLOBALS['TL_LANG']['tl_theme']['folders'] = array('Répertoires', 'Sélectionner les répertoires qui appartiennent au thème.');
$GLOBALS['TL_LANG']['tl_theme']['templates'] = array('Répertoire des modèles', 'Sélectionner le répertoire des modèles qui seront exportés avec le thème.');
$GLOBALS['TL_LANG']['tl_theme']['screenshot'] = array('Capture d\'écran', 'Choisir une capture d\'écran du thème.');
$GLOBALS['TL_LANG']['tl_theme']['vars'] = array('Variables globales', 'Définir des variables globales pour les feuilles de style du thème (ex : <em>$rouge</em> -> <em>c00</em> ou <em>$marge</em> -> <em>12px</em>).');
$GLOBALS['TL_LANG']['tl_theme']['source'] = array('Fichiers sources', 'Choisir un ou plusieurs fichiers .cto');
$GLOBALS['TL_LANG']['tl_theme']['tstamp'] = array('Date de révision', 'Date et heure de la dernière révision');
$GLOBALS['TL_LANG']['tl_theme']['title_legend'] = 'Titre et auteur';
$GLOBALS['TL_LANG']['tl_theme']['config_legend'] = 'Configuration';
$GLOBALS['TL_LANG']['tl_theme']['vars_legend'] = 'Variables globales';
$GLOBALS['TL_LANG']['tl_theme']['theme_imported'] = 'Le thème "%s" a été importé.';
$GLOBALS['TL_LANG']['tl_theme']['checking_theme'] = 'Les données du thème sont en cours de vérification';
$GLOBALS['TL_LANG']['tl_theme']['tables_fields'] = 'Tables et champs';
$GLOBALS['TL_LANG']['tl_theme']['missing_field'] = 'Le champ <strong>%s</strong> est manquant dans la base de données et ne sera pas importé.';
$GLOBALS['TL_LANG']['tl_theme']['tables_ok'] = 'Les tables ont été vérifiées avec succès.';
$GLOBALS['TL_LANG']['tl_theme']['custom_sections'] = 'Présentations de page personnalisées';
$GLOBALS['TL_LANG']['tl_theme']['missing_section'] = 'La présentation de page <strong>%s</strong> n\'est pas défini dans les paramètres du back office.';
$GLOBALS['TL_LANG']['tl_theme']['sections_ok'] = 'Les présentations de page ont été contrôlées avec succès.';
$GLOBALS['TL_LANG']['tl_theme']['missing_xml'] = 'Le thème "%s" est corrompu et ne peut être importé.';
$GLOBALS['TL_LANG']['tl_theme']['custom_templates'] = 'Modèles personnalisés';
$GLOBALS['TL_LANG']['tl_theme']['template_exists'] = 'Le modèle <strong>"%s"</strong> existe et ne sera pas écrasé.';
$GLOBALS['TL_LANG']['tl_theme']['templates_ok'] = 'Aucun conflit détecté.';
$GLOBALS['TL_LANG']['tl_theme']['new'] = array('Nouveau thème', 'Créer un nouveau thème');
$GLOBALS['TL_LANG']['tl_theme']['show'] = array('Détails du thème', 'Afficher les détails du thème ID %s');
$GLOBALS['TL_LANG']['tl_theme']['edit'] = array('Éditer le thème', 'Éditer le thème ID %s');
$GLOBALS['TL_LANG']['tl_theme']['delete'] = array('Supprimer le thème', 'Supprimer le thème ID %s');
$GLOBALS['TL_LANG']['tl_theme']['css'] = array('Feuilles de style', 'Éditer les feuilles de style du thème ID %s');
$GLOBALS['TL_LANG']['tl_theme']['modules'] = array('Modules', 'Éditer les modules du thème ID %s');
$GLOBALS['TL_LANG']['tl_theme']['layout'] = array('Présentations', 'Éditer les présentation de page du thème ID %s');
$GLOBALS['TL_LANG']['tl_theme']['importTheme'] = array('Import de thème', 'Importer de nouveaux thèmes');
$GLOBALS['TL_LANG']['tl_theme']['exportTheme'] = array('Export', 'Exporter le thème ID %s');

?>