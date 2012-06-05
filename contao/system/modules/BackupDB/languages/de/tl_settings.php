<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Softleister 2007-2011
 * @author     Softleister <http://www.softleister.de>
 * @package    BackupDB
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['backupdb_saveddb'] = array('Datentabellen im Website-Template', 'Tragen Sie hier eine kommagetrennte Liste der Tabellenprefixe oder Tabellen ein, die in dem Website-Template berücksichtigt werden sollen. Alle Tabellen des Contao-Core beginnen mit <em>tl_</em>, für Catalogtabellen ggf. <em>cat_</em>');
$GLOBALS['TL_LANG']['tl_settings']['AutoBackupCount'] = array('Anzahl der Backups bei AutoBackupDB', 'Anzahl der Datensicherungsdateien, die von AutoBackupDB erstellt werden, der Standard ist 3. Das neueste Backup ist immer in der Datei <em>AutoBackupDB-1.sql</em>');
$GLOBALS['TL_LANG']['tl_settings']['WsTemplatePath'] = array('Alternativer Pfad für Website-Templates', 'Standard-Pfad ist <em>templates</em>. Sie können hier ein anderes Verzeichnis angeben, wo die Website-Templates gespeichert werden.');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_settings']['backupdb_legend']      = 'BackupDB Einstellungen';

?>