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
 * Legends
 */
$GLOBALS['TL_LANG']['tl_videobox_archive']['title_legend'] = 'Global archive settings';


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_videobox_archive']['title'] = array('Title','Please give the archive a title.');
$GLOBALS['TL_LANG']['tl_videobox_archive']['activeVideoTypes'] = array('Videotypes in this archive','Choose the videotypes you would like to enable for this archive.');
$GLOBALS['TL_LANG']['tl_videobox_archive']['allowedUserGroups'] = array('Permissions','The checked usergroups will have access to the videos in this archive in the dropdown, where you choose the videos from.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_videobox_archive']['editvideosettings'] = array('Edit settings', 'Edit the settings for the VideoBox archive with id %s');
$GLOBALS['TL_LANG']['tl_videobox_archive']['new']    = array('New VideoBox archive', 'Create a new VideoBox archive');
$GLOBALS['TL_LANG']['tl_videobox_archive']['edit']   = array('Edit VideoBox archive', 'Edit the VideoBox archive with id %s');
$GLOBALS['TL_LANG']['tl_videobox_archive']['copy']   = array('Copy VideoBox archive', 'Copy the VideoBox archive with id %s');
$GLOBALS['TL_LANG']['tl_videobox_archive']['delete'] = array('Delete VideoBox archive', 'Delete the VideoBox archive with id %s');
$GLOBALS['TL_LANG']['tl_videobox_archive']['show']   = array('Show details', 'Show the details of the VideoBox archive with id %s');