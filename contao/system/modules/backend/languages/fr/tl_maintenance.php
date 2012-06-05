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

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'] = array('Nettoyer les données', 'Choisir les données que vous voulez nettoyer.');
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'] = array('Membre', 'Se connecter automatiquement en tant que membre pour indexer les pages protégées.');
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'Nettoyer les données';
$GLOBALS['TL_LANG']['tl_maintenance']['clearHtml'] = 'Répertoire system/html';
$GLOBALS['TL_LANG']['tl_maintenance']['clearScripts'] = 'Répertoire system/scripts';
$GLOBALS['TL_LANG']['tl_maintenance']['clearTemp'] = 'Répertoire system/tmp';
$GLOBALS['TL_LANG']['tl_maintenance']['clearXml'] = 'Fichiers XML';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCss'] = 'Fichiers CSS';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'Les données ont été nettoyées';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'Mise à jour automatique';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'ID de mise à jour automatique (Live Update ID)';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = 'Votre version %s de Contao est à jour';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = 'Une nouvelle version %s de Contao est disponible';
$GLOBALS['TL_LANG']['tl_maintenance']['betaVersion'] = 'Vous ne pouvez pas mettre à jour les versions bêta avec Live Update';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'Veuillez saisir votre ID de mise à jour automatique (Live Update ID)';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = 'Le répertoire temporaire (system/tmp) ne possède pas de droits en écriture';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = 'Voir le journal des modifications';
$GLOBALS['TL_LANG']['tl_maintenance']['backupFiles'] = 'Sauvegarder les fichiers qui seront mis à jour';
$GLOBALS['TL_LANG']['tl_maintenance']['showToc'] = 'Lister les fichiers qui seront mis à jour';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'Lancer la mise à jour';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'Contenu de la mise à jour';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'Fichiers sauvegardés';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = 'Fichiers mis à jour';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = 'Recréer l\'index de recherche';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'Recréer l\'index';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = 'Il n\'y a aucune page pouvant être recherchée';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = 'Veuillez attendre le chargement complet de cette page avant de continuer !';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = 'Veuillez patienter pendant que l\'index de recherche est en cours de création.';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = 'L\'index de recherche a été recréé. Vous pouvez maintenant continuer.';

?>