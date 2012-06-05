<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * Extended language file for table tl_form_field (en).
 *
 * PHP version 5
 * @copyright  Thomas Kuhn 2007 - 2010
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 * @license    LGPL
 * @filesource
 */

/**
 * Form fields
 */
$GLOBALS['TL_LANG']['FFL']['efgLookupSelect'] = array('Select menu (DB)', 'A select menu (DB) is a drop down menu containing several options from which one can be selected. The options are taken from a definable database table.');
$GLOBALS['TL_LANG']['FFL']['efgLookupCheckbox'] = array('Checkbox menu (DB)', 'A checkbox menu (DB) is a multi-line menu containing one or more options from which any can be selected. The options are taken from a definable database table.');
$GLOBALS['TL_LANG']['FFL']['efgLookupRadio'] = array('Radio button menu (DB)', 'A radio button menu (DB) is a multi-line menu containing several options from which one can be selected. The options are taken from a definable database table.');

$GLOBALS['TL_LANG']['FFL']['efgImageSelect'] = array('Image select menu', 'An image select menu renders an image gallery with radio buttons or checkboxes in the form.');

$GLOBALS['TL_LANG']['tl_form_field']['efgLookupOptions'] = array('Options from database');
$GLOBALS['TL_LANG']['tl_form_field']['lookup_field'] = array('Database field (label)', 'Please choose the database field where the available options (label) should be taken from.');
$GLOBALS['TL_LANG']['tl_form_field']['lookup_val_field'] = array('Database field (value)', 'Please choose the database field where the available options (value) should be taken from.');
$GLOBALS['TL_LANG']['tl_form_field']['lookup_where'] = array('Condition', 'If you want to exclude certain records, you can enter a condition here (e.g. <em>published=\'1\'</em> or <em>type!=\'admin\'</em>).');
$GLOBALS['TL_LANG']['tl_form_field']['lookup_sort'] = array('Order by', 'Here you can enter a comma seperated list of fields to sort the results by (e.g.  <em>title ASC</em> or <em>city ASC, gender DESC</em>). If you don\'t specify a sorting, records are sorted according to the label field.');

$GLOBALS['TL_LANG']['tl_form_field']['efgMultiSRC'] = array('Source files', 'Please select one or more files or folders (files in a folder will be included automatically).');
$GLOBALS['TL_LANG']['tl_form_field']['efgImageUseHomeDir'] = array('Use home directory as source', 'If there is a logged in user, use his home directory as source instead of the \'source files\' defined above.');
$GLOBALS['TL_LANG']['tl_form_field']['efgImageMultiple'] = array('Multiple selection', 'Allow visitors to select more than one image.');

$GLOBALS['TL_LANG']['tl_form_field']['efgImageSortBy'] = array('Order by', 'Please select a sort order.');
$GLOBALS['TL_LANG']['tl_form_field']['efgImageSize'] = array('Image width and height', 'Please enter either the image width, the image height or both measures to resize the image. If you leave both fields blank, the original image size will be displayed.');
$GLOBALS['TL_LANG']['tl_form_field']['efgImagePerRow'] = array('Thumbnails per row', 'Please enter the number of thumbnails per row.');
$GLOBALS['TL_LANG']['tl_form_field']['efgImageMargin'] = array('Image margin', 'Please enter the top, right, bottom and left margin and the unit. Image margin is the space inbetween an image and its neighbour elements.');
$GLOBALS['TL_LANG']['tl_form_field']['efgImageFullsize'] = array('Fullsize view', 'If you choose this option, the image can be viewed fullsize by clicking it.');

$GLOBALS['TL_LANG']['tl_form_field']['name_asc']  = 'File name (ascending)';
$GLOBALS['TL_LANG']['tl_form_field']['name_desc'] = 'File name (descending)';
$GLOBALS['TL_LANG']['tl_form_field']['date_asc']  = 'Date (ascending)';
$GLOBALS['TL_LANG']['tl_form_field']['date_desc'] = 'Date (descending)';
$GLOBALS['TL_LANG']['tl_form_field']['meta']      = 'Meta file (meta.txt)';
$GLOBALS['TL_LANG']['tl_form_field']['random']    = 'Random order';

$GLOBALS['TL_LANG']['FFL']['efgFormPaginator'] = array('Submit field and page break', 'buttons and page break for a multipage form.');

$GLOBALS['TL_LANG']['tl_form_field']['efgAddBackButton'] = array('Add a back button', 'Create a back button as link to previous form page.');
$GLOBALS['TL_LANG']['tl_form_field']['efgBackStoreSessionValues'] = array('Save form input on \'back\'', 'Store data in frontend form on submitting \'back\' to populate fields if this page is recalled.');
$GLOBALS['TL_LANG']['tl_form_field']['efgBackSlabel'] = array('Back button label','Please enter the label of the back button.');
$GLOBALS['TL_LANG']['tl_form_field']['efgBackImageSubmit'] = $GLOBALS['TL_LANG']['tl_form_field']['imageSubmit'];
$GLOBALS['TL_LANG']['tl_form_field']['efgBackSingleSRC'] = $GLOBALS['TL_LANG']['tl_form_field']['singleSRC'];

$GLOBALS['TL_LANG']['tl_form_field']['backbutton_legend'] = "Back button";

?>