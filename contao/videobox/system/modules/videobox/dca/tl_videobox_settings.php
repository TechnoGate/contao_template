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
 * Table tl_videobox_settings
 */
$GLOBALS['TL_DCA']['tl_videobox_settings'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_videobox_archive',
		'onload_callback'			  => array(array('tl_videobox_settings', 'hideFields'))
	),
	
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('youtube_template'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('youtube_template'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_videobox_settings']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_videobox_settings']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_videobox_settings']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_videobox_settings']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{youtube_legend},youtube_template,youtube_size,youtube_rel,youtube_autoplay,youtube_loop,youtube_border,youtube_color1,youtube_color2,youtube_start,youtube_fs,youtube_hd,youtube_showinfo;'
	),

	// Fields
	'fields' => array
	(
		'youtube_template' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_template'],
 			'default'                 => 'videobox_youtube',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('videobox_'),
			'eval'					  => array('tl_class'=>'w50')
		),
		'youtube_size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_size'],
 			'default'				  => array(425,344),
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'nospace'=>true, 'tl_class'=>'w50')
		),
		'youtube_rel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_rel'],
 			'default'                 => true,
			'exclude'				  => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('tl_class'=>'w50 cbx')
		),
		'youtube_autoplay' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_autoplay'],
 			'default'                 => false,
			'exclude'				  => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('tl_class'=>'w50 cbx')
		),
		'youtube_loop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_loop'],
 			'default'                 => false,
			'exclude'				  => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('tl_class'=>'w50 cbx')
		),
		'youtube_border' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_border'],
 			'default'                 => false,
			'exclude'				  => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('tl_class'=>'w50 cbx')
		),
		'youtube_color1' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_color1'],
 			'default'                 => '000000',
			'exclude'				  => true,
			'inputType'               => 'text',
			'eval'					  => array('maxlength' => 6,'tl_class'=>'w50')
		),
		'youtube_color2' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_color2'],
 			'default'                 => 'FFFFFF',
			'exclude'				  => true,
			'inputType'               => 'text',
			'eval'					  => array('maxlength' => 6,'tl_class'=>'w50')
		),
		'youtube_start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_start'],
 			'default'                 => '0',
			'exclude'				  => true,
			'inputType'               => 'text',
			'eval'					  => array('rgxp' => 'digit','tl_class'=>'w50')
		),
		'youtube_fs' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_fs'],
 			'default'                 => false,
			'exclude'				  => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('tl_class'=>'w50 cbx m12')
		),
		'youtube_hd' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_hd'],
 			'default'                 => false,
			'exclude'				  => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('tl_class'=>'w50 cbx')
		),
		'youtube_showinfo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_settings']['youtube_showinfo'],
 			'default'                 => true,
			'exclude'				  => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('tl_class'=>'w50 cbx')
		)
	)
);

class tl_videobox_settings extends Backend
{
	/**
	 * Hide the corresponding fields if they have been deactivated in the archive
	 * @param object
	 */
	public function hideFields(DataContainer $dc)
	{
		// get pid
		$objPid = $this->Database->prepare("SELECT pid FROM tl_videobox_settings WHERE id=?")
								 ->execute($dc->id);
								 
		// get all active videotypes
		$objAVT = $this->Database->prepare("SELECT activeVideoTypes FROM tl_videobox_archive WHERE id=?")
										 ->execute($objPid->pid);
		
		// if there is absolutely no video type active, we're going to unset all the fields
		if(strlen($objAVT->activeVideoTypes) == 0)
		{
			$GLOBALS['TL_DCA']['tl_videobox_settings']['palettes']['default'] = '';
			return;
		}

		// else we have to look for the intersection
		$arrAVT = deserialize($objAVT->activeVideoTypes);
		
		// build disallowed videotypes array
		$arrDAVT = $GLOBALS['VIDEOBOX']['VideoType'];
		
		foreach($arrAVT as $k => $vidType)
		{
			unset($arrDAVT[$vidType]);
		}
		
		// check the palette
		foreach($arrDAVT as $k => $vidType)
		{ 
			// awful regexp - thanks to chris (Xtra) for support - you're the man!
			$GLOBALS['TL_DCA']['tl_videobox_settings']['palettes']['default'] = preg_replace('#|(\{' . $vidType . '_[^}]*\}(\s*)(,|;)(\s*))|(' . $vidType . '_[^,;}]*(\s*)(,|;)(\s*))#i', '', $GLOBALS['TL_DCA']['tl_videobox_settings']['palettes']['default']);
		}
	}
}