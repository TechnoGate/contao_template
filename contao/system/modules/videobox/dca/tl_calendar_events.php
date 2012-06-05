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
 * Add palettes to tl_calendar_events
 */
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['__selector__'][] = 'videobox_addvideo';
$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default'] = str_replace('addEnclosure;','addEnclosure;{videobox_legend:hide},videobox_addvideo;',$GLOBALS['TL_DCA']['tl_calendar_events']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_calendar_events']['subpalettes']['videobox_addvideo'] = 'videobox_video';

/**
 * Add fields to tl_calendar_events
 */
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['videobox_addvideo'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['videobox_addvideo'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'					  => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['videobox_video'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['videobox_video'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'		  => array('VideoBox_Helpers', 'getVideos'),
	'eval'					  => array('mandatory'=>true)
);