<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.typolight.org>
 * @package    Registration
 * @license    LGPL
 * @filesource
 */


/**
 * Add selectors to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['subcolumns'] = '{title_legend},name,headline,type;{subcolumns_legend},sc_type,sc_modules;{subcolumns_settings_legend},sc_gap,sc_gapdefault,sc_equalize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';




/**
 * Add fields to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['fields']['sc_type'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['sc_type'],
	'exclude'       => true,
	'inputType'     => 'select',
	'options'		=> array_keys($GLOBALS['TL_SUBCL']),
	'eval'          => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['sc_modules'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['sc_modules'],
	'exclude'       => true,
	'inputType'     => 'extendedModuleWizard'
);

$GLOBALS['TL_DCA']['tl_module']['fields']['sc_gap'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_module']['sc_gap'],
	'inputType'	=> 'text',
	'eval'		=> array('maxlength'=>'4','regxp'=>'digit', 'tl_class'=>'w50')		
);

$GLOBALS['TL_DCA']['tl_module']['fields']['sc_gapdefault'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_module']['sc_gapdefault'],
	'default'	=> 1,
	'inputType'	=> 'checkbox',
	'eval'		=> array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['sc_equalize'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_module']['sc_equalize'],
	'inputType'	=> 'checkbox',
	'eval'		=> array('tl_class'=>'clr')		
);

?>