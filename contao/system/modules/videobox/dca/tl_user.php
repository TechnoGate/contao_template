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


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_user']['palettes']['extend'] = str_replace('{account_legend}', '{videobox_legend},videobox_archives,videobox_operations;{account_legend}', $GLOBALS['TL_DCA']['tl_user']['palettes']['extend']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['custom'] = str_replace('{account_legend}', '{videobox_legend},videobox_archives,videobox_operations;{account_legend}', $GLOBALS['TL_DCA']['tl_user']['palettes']['extend']);

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['videobox_archives'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['videobox_archives'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'foreignKey'			  => 'tl_videobox_archive.title',
	'eval'                    => array('multiple'=>true)
);

$GLOBALS['TL_DCA']['tl_user']['fields']['videobox_operations'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_user']['videobox_operations'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('create', 'delete'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true)
);