<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @copyright  David Enke 2010 
 * @author     David Enke <post@davidenke.de> 
 * @package    videobox 
 * @license    LGPL 
 * @filesource
 */
 
/**
 * Table tl_videobox 
 */
$GLOBALS['TL_DCA']['tl_videobox']['palettes'] += array(
    'vimeo' => '{title_legend},videotitle,videotype;{vimeo_legend},vimeo_id;'
);

$GLOBALS['TL_DCA']['tl_videobox']['fields'] += array(
    'vimeo_id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox']['vimeo_id'],
			'exclude'                 => true,
			'inputType'               => 'text'
		)
);
?>
