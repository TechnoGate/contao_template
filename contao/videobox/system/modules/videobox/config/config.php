<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  certo web & design GmbH 2010 - 2011 
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch> 
 * @package    videobox 
 * @license    LGPL 
 * @filesource
 */

// BE MOD
$GLOBALS['BE_MOD']['content']['videobox'] = array
(
    'tables'               => array('tl_videobox_archive','tl_videobox','tl_videobox_settings'),
    'icon'         		   => 'system/modules/videobox/html/videobox.png',
	'videobox_settings'	   => array('VideoBox_Helpers', 'linkToSettings')
);

// FE MOD
$GLOBALS['FE_MOD']['videobox'] = array
(
    'videobox_list'     => 'ModuleVideoBoxList',
    'videobox_reader'   => 'ModuleVideoBoxReader'
);

// CE
array_insert($GLOBALS['TL_CTE'], 2, array
(
	'videos' => array
	(
		'videobox'     => 'ContentVideoBox'
	)
));

// Permissions
$GLOBALS['TL_PERMISSIONS'][] = 'videobox_archives';
$GLOBALS['TL_PERMISSIONS'][] = 'videobox_operations';

// InsertTag Hook
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('VideoBox_Helpers', 'replaceVideoBoxInsertTags');

// Videotypes Array
$GLOBALS['VIDEOBOX']['VideoType'] = array();

/**
 * Add youtube videotype. This is how the array should look like if you're adding an additional video type:
$GLOBALS['VIDEOBOX']['VideoType']['videotype_name'] = array('Class', 'Method');
 */
$GLOBALS['VIDEOBOX']['VideoType']['youtube'] = array('YouTube', 'parseVideo');