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
 * Table tl_videobox_archive
 */
$GLOBALS['TL_DCA']['tl_videobox_archive'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_videobox','tl_videobox_settings'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback'			  => array
		(
			array('tl_videobox_archive', 'checkPermission')
		)
	),
	
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title'),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_videobox_archive']['edit'],
				'href'                => 'table=tl_videobox',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_videobox_archive']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_videobox_archive']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_videobox_archive']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'editvideosettings' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_videobox_archive']['editvideosettings'],
				'href'                => 'key=videobox_settings',
				'icon'                => 'system/modules/videobox/html/editvideosettings.png'
			),
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{title_legend},title,activeVideoTypes,allowedUserGroups;'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_archive']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true)
		),
		'activeVideoTypes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_archive']['activeVideoTypes'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options_callback'        => array('tl_videobox_archive', 'getVideoTypes'),
			'eval'                    => array('multiple'=>true)
		),
		'allowedUserGroups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_videobox_archive']['allowedUserGroups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_user_group.name',
			'eval'                    => array('multiple'=>true)
		)
	)
);

class tl_videobox_archive extends Backend
{
	
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	
	/**
	 * Check permissions to edit table tl_videobox_archive
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}
		
		// Set root IDs
		if (!is_array($this->User->videobox_archives) || count($this->User->videobox_archives) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->videobox_archives;
		}

		$GLOBALS['TL_DCA']['tl_videobox_archive']['list']['sorting']['root'] = $root;
		
		// Check permissions to add archives
		if (!$this->User->hasAccess('create', 'videobox_operations'))
		{
			$GLOBALS['TL_DCA']['tl_videobox_archive']['config']['closed'] = true;
		}

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record new record to the allowed archives if the user is allowed to create new entries
				if (!in_array($this->Input->get('id'), $root))
				{
					$arrNew = $this->Session->get('new_records');

					if (is_array($arrNew['tl_videobox_archive']) && in_array($this->Input->get('id'), $arrNew['tl_videobox_archive']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT videobox_archives, videobox_operations FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrOperations = deserialize($objUser->videobox_operations);

							if (is_array($arrOperations) && in_array('create', $arrOperations))
							{
								$arrArchives = deserialize($objUser->videobox_archives);
								$arrArchives[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user SET videobox_archives=? WHERE id=?")
											   ->execute(serialize($arrArchives), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT videobox_archives, videobox_operations FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrOperations = deserialize($objGroup->videobox_operations);

							if (is_array($arrOperations) && in_array('create', $arrOperations))
							{
								$arrArchives = deserialize($objGroup->videobox_archives);
								$arrArchives[] = $this->Input->get('id');

								$this->Database->prepare("UPDATE tl_user_group SET videobox_archives=? WHERE id=?")
											   ->execute(serialize($arrArchives), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = $this->Input->get('id');
						$this->User->videobox_archives = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $root) || ($this->Input->get('act') == 'delete' && !$this->User->hasAccess('delete', 'videobox_operations')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' videobox archive ID "'.$this->Input->get('id').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if ($this->Input->get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'videobox_operations'))
				{
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' videobox archives', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}
	
	
	
	/**
	 * Method to list all video types
	 * @return array
	 */
	public function getVideoTypes()
	{
		$arrTypes = array();

		foreach(array_keys($GLOBALS['VIDEOBOX']['VideoType']) as $type)
		{
			$arrTypes[$type] = $GLOBALS['TL_LANG']['VideoTypes'][$type];
		}

		return $arrTypes;
	}
}