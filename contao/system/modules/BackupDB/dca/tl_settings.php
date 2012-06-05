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
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['backupdb_saveddb'] = array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['backupdb_saveddb'],
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'long')
		);

$GLOBALS['TL_DCA']['tl_settings']['fields']['AutoBackupCount'] = array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['AutoBackupCount'],
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		);
		
$GLOBALS['TL_DCA']['tl_settings']['fields']['WsTemplatePath'] = array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['WsTemplatePath'],
			'inputType'               => 'text',
			'eval'                    => array('nospace'=>'true', 'trailingSlash'=>false, 'tl_class'=>'w50')
		);

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{backupdb_legend:hide},backupdb_saveddb,WsTemplatePath,AutoBackupCount';

?>