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
 * Table tl_videobox_settings
 */
 
$GLOBALS['TL_DCA']['tl_videobox_settings']['list']['sorting']['fields'][] = 'vimeo_template';
$GLOBALS['TL_DCA']['tl_videobox_settings']['list']['label']['fields'][] = 'vimeo_template';
$GLOBALS['TL_DCA']['tl_videobox_settings']['palettes']['default'] .= '{vimeo_legend},vimeo_template,vimeo_size,vimeo_autoplay,vimeo_color,vimeo_showbyline,vimeo_showtitle,vimeo_showportrait;';
$GLOBALS['TL_DCA']['tl_videobox_settings']['fields'] += array(
    'vimeo_template' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['vimeo_template'],
	    'default'                 => 'videobox_vimeo',
        'exclude'                 => true,
        'inputType'               => 'select',
        'options'                 => $this->getTemplateGroup('videobox_'),
        'eval'					  => array('tl_class'=>'w50')
    ),
    'vimeo_size' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['vimeo_size'],
	    'default'				  => array(425,344),
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
    ),
    'vimeo_autoplay' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['vimeo_autoplay'],
	    'default'                 => false,
        'exclude'				  => true,
        'inputType'               => 'checkbox',
        'eval'					  => array('tl_class'=>'w50')
    ),
    'vimeo_color' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['vimeo_color'],
	    'default'                 => 'F7FFFD',
        'exclude'				  => true,
        'inputType'               => 'text',
        'eval'					  => array('maxlength' => 6,'tl_class'=>'w50')
    ),
    'vimeo_showbyline' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['vimeo_showbyline'],
	    'default'                 => true,
        'exclude'				  => true,
        'inputType'               => 'checkbox',
        'eval'					  => array('tl_class'=>'clr w50 cbx')
    ),
    'vimeo_showtitle' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['vimeo_showtitle'],
	    'default'                 => true,
        'exclude'				  => true,
        'inputType'               => 'checkbox',
        'eval'					  => array('tl_class'=>'w50 cbx')
    ),
    'vimeo_showportrait' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['vimeo_showportrait'],
	    'default'                 => true,
        'exclude'				  => true,
        'inputType'               => 'checkbox',
        'eval'					  => array('tl_class'=>'w50 cbx')
    )
);

?>
