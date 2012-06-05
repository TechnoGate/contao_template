<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
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
 * @copyright  2007 
 * @author     Felix Pfeiffer 
 * @package    subcolumns v 1.1.1 
 * @license    GPL 
 * @filesource
 */


/**
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['subcolumns_altcss'] = array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['subcolumns_altcss'],
			'inputType'               => 'checkbox',
			'eval'                    => array()
		);
		
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{subcolumns_legend:hide},subcolumns_altcss';

?>