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
 * Add palettes to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['videobox'] = '{type_legend},type,headline;{videobox_legend},videobox_video,videobox_description,videobox_floating;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Add fields to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['videobox_video'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['videobox_video'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'		  => array('VideoBox_Helpers', 'getVideos'),
	'eval'					  => array('mandatory'=>true)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['videobox_description'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['videobox_description'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'					  => array('rte'=>'tinyMCE')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['videobox_floating'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['videobox_floating'],
	'exclude'                 => true,
	'inputType'               => 'radioTable',
	'options'                 => array('above', 'left', 'right', 'below'),
	'eval'                    => array('cols'=>4),
	'reference'               => &$GLOBALS['TL_LANG']['MSC']
);