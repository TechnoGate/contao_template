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
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['videobox'] = array('VideoBox', 'Dieses Modul erlaubt Ihnen Contao in ein Videoverwaltungssystem umzuwandeln.');

/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['videobox']           = 'VideoBox';
$GLOBALS['TL_LANG']['FMD']['videobox_list']      = array('VideoBox Liste', 'Gibt eine Liste von Videos aus VideoBox aus.');
$GLOBALS['TL_LANG']['FMD']['videobox_reader']    = array('VideoBox Reader', 'Die Detailansicht eines Videos aus VideoBox.');