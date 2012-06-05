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
 * This is the extended data container array for table tl_form_field.
 *
 * PHP version 5
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 * @license    LGPL
 * @filesource
 */


// Table tl_form_fields
$GLOBALS['TL_DCA']['tl_form_field']['list']['sorting']['headerFields'][] = 'storeFormdata';
$GLOBALS['TL_DCA']['tl_form_field']['list']['sorting']['headerFields'][] = 'sendConfirmationMail';

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgLookupOptions'] = array
(
	'label'        => &$GLOBALS['TL_LANG']['tl_form_field']['efgLookupOptions'],
	'exclude'      => true,
	'inputType'    => 'efgLookupOptionWizard'
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgMultiSRC'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgMultiSRC'],
	'exclude'                 => true,
	'inputType'               => 'fileTree',
	'eval'                    => array('fieldType'=>'checkbox', 'files'=>true, 'mandatory'=>true, 'extensions' => 'gif,jpg,png')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['mandatory']['eval']['tl_class'] = 'w50 cbx';

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgImageMultiple'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgImageMultiple'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class' => 'w50 cbx')
);


$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgImageUseHomeDir'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgImageUseHomeDir'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class' => 'w50 m12 cbx')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgImageSortBy'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgImageSortBy'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('name_asc', 'name_desc', 'date_asc', 'date_desc', 'meta', 'random'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_form_field'],
	'eval'                    => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgImageSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgImageSize'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('multiple'=>true, 'size'=>2, 'rgxp'=>'digit', 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgImagePerRow'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgImagePerRow'],
	'default'                 => 4,
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
	'eval'                    => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgImageMargin'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgImageMargin'],
	'exclude'                 => true,
	'inputType'               => 'trbl',
	'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
	'eval'                    => array('includeBlankOption'=>true, 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgImageFullsize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgImageFullsize'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class' => 'w50 m12 cbx')
);


$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgAddBackButton'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgAddBackButton'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgBackSlabel'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgBackSlabel'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgBackImageSubmit'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgBackImageSubmit'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgBackSingleSRC'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgBackSingleSRC'],
	'exclude'                 => true,
	'inputType'               => 'fileTree',
	'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true,'extensions' => 'gif,jpg,png', 'mandatory'=>true, 'tl_class'=>'clr')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['efgBackStoreSessionValues'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['efgBackStoreSessionValues'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);


// add palette for field type efgLookupSelect
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['palettes']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['palettes'], count($GLOBALS['TL_DCA']['tl_form_field']['palettes']),
		array('efgLookupSelect' => '{type_legend},type,name,label;{options_legend},efgLookupOptions;{fconfig_legend},mandatory,multiple;{expert_legend:hide},accesskey,class;{submit_legend},addSubmit')
	);
}
// add field type efgLookupSelect to available form field 'type'
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'], (array_search('select', $GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'])+1),
		'efgLookupSelect'
	);
}

// add palette for field type efgLookupCheckbox
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['palettes']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['palettes'], count($GLOBALS['TL_DCA']['tl_form_field']['palettes']),
		array('efgLookupCheckbox' => '{type_legend},type,name,label;{options_legend},efgLookupOptions;{fconfig_legend},mandatory;{expert_legend:hide},accesskey,class;{submit_legend},addSubmit')
	);
}
// add field type efgLookupCheckbox to available form field 'type'
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'], (array_search('checkbox', $GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'])+1),
		'efgLookupCheckbox'
	);
}

// add palette for field type efgLookupRadio
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['palettes']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['palettes'], count($GLOBALS['TL_DCA']['tl_form_field']['palettes']),
		array('efgLookupRadio' => '{type_legend},type,name,label;{options_legend},efgLookupOptions;{fconfig_legend},mandatory;{expert_legend:hide},accesskey,class;{submit_legend},addSubmit')
	);
}
// add field type efgLookupRadio to available form field 'type'
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'], (array_search('radio', $GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'])+1),
		'efgLookupRadio'
	);
}

// add palette for field type efgImageSelect
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['palettes']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['palettes'], count($GLOBALS['TL_DCA']['tl_form_field']['palettes']),
		array('efgImageSelect' => '{type_legend},type,name,label;{options_legend},efgMultiSRC,efgImageUseHomeDir,efgImageFullsize,efgImageSize,efgImageMargin,efgImagePerRow,efgImageSortBy;{fconfig_legend},mandatory,efgImageMultiple;{expert_legend:hide},accesskey,class;{submit_legend},addSubmit')
	);
}

// add field type efgImageSelect to available form field 'type'
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'], (array_search('upload', $GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'])+1),
		'efgImageSelect'
	);
}

// add palette for field type efgFormPaginator
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['palettes']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['palettes'], count($GLOBALS['TL_DCA']['tl_form_field']['palettes']),
		array('efgFormPaginator' => '{type_legend},type,slabel;{image_legend:hide},imageSubmit;{backbutton_legend},efgAddBackButton;{expert_legend:hide},class,accesskey')
	);
	$GLOBALS['TL_DCA']['tl_form_field']['palettes']['__selector__'][] = 'efgAddBackButton';
	$GLOBALS['TL_DCA']['tl_form_field']['palettes']['__selector__'][] = 'efgBackImageSubmit';
	$GLOBALS['TL_DCA']['tl_form_field']['subpalettes']['efgAddBackButton'] = 'efgBackStoreSessionValues,efgBackSlabel,efgBackImageSubmit';
	$GLOBALS['TL_DCA']['tl_form_field']['subpalettes']['efgBackImageSubmit'] = 'efgBackSingleSRC';
}

// add field type efgFormPaginator to available form field 'type'
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'], (array_search('submit', $GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'])+1),
		'efgFormPaginator'
	);
}


// add backend form fields
$GLOBALS['BE_FFL']['efgLookupOptionWizard'] = 'EfgLookupOptionWizard';
$GLOBALS['BE_FFL']['efgLookupSelect'] = 'EfgFormLookupSelectMenu';
$GLOBALS['BE_FFL']['efgLookupCheckbox'] = 'EfgFormLookupCheckbox';
$GLOBALS['BE_FFL']['efgLookupRadio'] = 'EfgFormLookupRadio';
//$GLOBALS['BE_FFL']['efgImageSelectWizard'] = 'EfgImageSelectWizard';
$GLOBALS['BE_FFL']['efgFormPaginator'] = 'EfgFormPaginator';


// add front end form fields
$GLOBALS['TL_FFL']['efgLookupSelect'] = 'EfgFormLookupSelectMenu';
$GLOBALS['TL_FFL']['efgLookupCheckbox'] = 'EfgFormLookupCheckbox';
$GLOBALS['TL_FFL']['efgLookupRadio'] = 'EfgFormLookupRadio';
$GLOBALS['TL_FFL']['efgImageSelect'] = 'EfgFormImageSelect';
$GLOBALS['TL_FFL']['efgFormPaginator'] = 'EfgFormPaginator';

?>