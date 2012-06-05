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

$GLOBALS['TL_LANG']['tl_style_sheet']['name'] = array('Nom', 'Saisir le nom de la feuille de style.');
$GLOBALS['TL_LANG']['tl_style_sheet']['cc'] = array('Commentaire conditionnel', 'Les commentaires conditionnels permettent de créer des feuilles de style spécifiques à Internet Explorer (ex : <em>if lt IE 9</em>).');
$GLOBALS['TL_LANG']['tl_style_sheet']['media'] = array('Types de média', 'Choisir un ou plusieurs types de supports auxquels s\'applique la feuille de style.');
$GLOBALS['TL_LANG']['tl_style_sheet']['mediaQuery'] = array('Media query', 'Définir le type de support en utilisant une requête de média comme : <em>screen and (min-width: 800px)</em>. Le type de support défini ci-dessus sera alors écrasé.');
$GLOBALS['TL_LANG']['tl_style_sheet']['vars'] = array('Variables globales', 'Définir des variables globales pour la feuille de style (ex : <em>$rouge</em> -> <em>c00</em> ou <em>$marge</em> -> <em>12px</em>).');
$GLOBALS['TL_LANG']['tl_style_sheet']['source'] = array('Source du fichier', 'Veuillez choisir le fichier CSS que vous souhaitez importer à partir de l\'explorateur de fichiers.');
$GLOBALS['TL_LANG']['tl_style_sheet']['tstamp'] = array('Date de révision', 'Date et heure de la dernière révision');
$GLOBALS['TL_LANG']['tl_style_sheet']['title_legend'] = 'Nom';
$GLOBALS['TL_LANG']['tl_style_sheet']['media_legend'] = 'Paramètres du média';
$GLOBALS['TL_LANG']['tl_style_sheet']['vars_legend'] = 'Variables globales';
$GLOBALS['TL_LANG']['tl_style_sheet']['css_imported'] = 'La feuille de style "%s" a été importée.';
$GLOBALS['TL_LANG']['tl_style_sheet']['css_renamed'] = 'La feuille de style "%s" a été importée en "%s".';
$GLOBALS['TL_LANG']['tl_style_sheet']['new'] = array('Nouvelle feuille de style', 'Créer une nouvelle feuille de style');
$GLOBALS['TL_LANG']['tl_style_sheet']['show'] = array('Détails de la feuille de style', 'Afficher les détails de la feuille de style ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['edit'] = array('Éditer la feuille de style', 'Éditer la feuille de style ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['editheader'] = array('Éditer les paramètres de la feuille de style', 'Éditer les paramètres de la feuille de style ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['cut'] = array('Déplacer la feuille de style', 'Déplacer la feuille de style ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['copy'] = array('Dupliquer la feuille de style', 'Dupliquer la feuille de style ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['delete'] = array('Supprimer la feuille de style', 'Supprimer la feuille de style ID %s');
$GLOBALS['TL_LANG']['tl_style_sheet']['import'] = array('Importation de CSS', 'Importer des fichiers CSS existants');

?>