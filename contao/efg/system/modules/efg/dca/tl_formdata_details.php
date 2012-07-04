<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
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
 * This is the data container array for table tl_formdata_details.
 *
 * PHP version 5
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_formdata_details
 */
$GLOBALS['TL_DCA']['tl_formdata_details'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               => 'Formdata',
		'ptable'                      => 'tl_formdata',
		'closed'                      => true,
		'notEditable'                 => false,
		'enableVersioning'            => false,
		'doNotCopyRecords'            => false,
		'doNotDeleteRecords'          => false,
		'switchToEdit'                => false,

	),
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('ff_name','ff_label','value'),
			'panelLayout'             => 'search,filter',
			'headerFields'            => array('form', 'date', 'ip', 'be_notes'),
			'child_record_callback'   => array('tl_formdata_details', 'listFormdata')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_formdata']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_formdata']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => 'pid,id,ff_name,ff_label,ff_type,value'
	),

	// Fields
	'fields' => array
	(
		'value' => array
		(
			'label'                   => array('Value', 'Wert des tl_formdata_details-Datensatzes'),
			'inputType'               => 'text',
			'exclude'                 => false,
			'search'                  => false,
			'sorting'                 => false,
			'filter'                  => false
		)
	)
);

?>