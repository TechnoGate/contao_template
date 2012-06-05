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
 * This file modifies the data container array of table tl_module.
 *
 * PHP version 5
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 * @license    LGPL
 * @filesource
 */

/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['formdatalisting'] = '{title_legend},name,headline,type;{config_legend},list_formdata,list_where,list_sort,perPage,list_fields,list_info;{efgSearch_legend},list_search,efg_list_searchtype;{protected_legend:hide},efg_list_access,efg_fe_edit_access,efg_fe_delete_access,efg_fe_export_access;{comments_legend:hide},efg_com_allow_comments;{template_legend:hide},list_layout,list_info_layout;{expert_legend:hide},efg_DetailsKey,efg_iconfolder,efg_fe_keep_id,efg_fe_no_formatted_mail,efg_fe_no_confirmation_mail,align,space,cssID';
$GLOBALS['TL_DCA']['tl_module']['fields']['type']['load_callback'][] = array('tl_ext_module', 'onloadModuleType');

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'efg_com_allow_comments';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['efg_com_allow_comments'] = 'com_moderate,com_bbcode,com_requireLogin,com_disableCaptcha,efg_com_per_page,com_order,com_template,efg_com_notify';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['list_formdata'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_formdata'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_ext_module', 'getFormdataTables'),
	'eval'                    => array('mandatory' => true, 'maxlength' => 64, 'includeBlankOption' => true, 'submitOnChange' => true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_where'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_where'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_sort'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_sort'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_fields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_fields'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50" style="height:auto'),
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_list_searchtype'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_list_searchtype'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('dropdown', 'singlefield', 'multiplefields'),
	'reference'               => &$GLOBALS['TL_LANG']['efg_list_searchtype'],
	'eval'                    => array('mandatory'=>false, 'includeBlankOption'=>true, 'helpwizard'=>true,  'tl_class'=>'w50" style="height:auto')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_search'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_search'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50" style="height:auto')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['list_info'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list_info'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50" style="height:auto')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_list_access'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_list_access'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('public','groupmembers','member'),
	'reference'               => &$GLOBALS['TL_LANG']['efg_list_access'],
	'eval'                    => array('mandatory'=>true, 'includeBlankOption' => true, 'helpwizard'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_edit_access'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_edit_access'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('none','public','groupmembers','member'),
	'reference'               => &$GLOBALS['TL_LANG']['efg_fe_edit_access'],
	'eval'                    => array('mandatory'=>true, 'includeBlankOption' => true, 'helpwizard'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_delete_access'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_delete_access'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('none','public','groupmembers','member'),
	'reference'               => &$GLOBALS['TL_LANG']['efg_fe_delete_access'],
	'eval'                    => array('mandatory'=>true, 'includeBlankOption' => true, 'helpwizard'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_export_access'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_export_access'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('none','public','groupmembers','member'),
	'reference'               => &$GLOBALS['TL_LANG']['efg_fe_export_access'],
	'eval'                    => array('mandatory'=>true, 'includeBlankOption' => true, 'helpwizard'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_DetailsKey'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_DetailsKey'],
	'exclude'                 => false,
	'filter'                  => false,
	'inputType'               => 'text',
	'eval'                    => array('default' => 'details', 'maxlength'=>64, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['efg_iconfolder'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_iconfolder'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'trailingSlash'=>false, 'tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_keep_id'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_keep_id'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'clr cbx')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_no_formatted_mail'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_no_formatted_mail'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'clr cbx')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['efg_fe_no_confirmation_mail'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_fe_no_confirmation_mail'],
	'exclude'                 => true,
	'filter'                  => false,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'clr cbx')
);


$GLOBALS['TL_DCA']['tl_module']['fields']['efg_com_allow_comments'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_com_allow_comments'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true)
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_moderate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_moderate'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_bbcode'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_bbcode'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_requireLogin'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_requireLogin'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_disableCaptcha'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_disableCaptcha'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['efg_com_per_page'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_com_per_page'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_order'],
	'default'                 => 'ascending',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('ascending', 'descending'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['com_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['com_template'],
	'default'                 => 'com_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_comments', 'getCommentTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['efg_com_notify'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['efg_com_notify'],
	'default'                 => 'notify_admin',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('notify_admin', 'notify_author', 'notify_both'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class' => 'w50')
);



/**
 * Class tl_ext_module
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Thomas Kuhn 2007 - 2011
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */
class tl_ext_module extends Backend
{

	private $arrFormdataTables = null;
	private $arrFormdataFields = null;

	public function onloadModuleType($varValue, DataContainer $dc)
	{
		if ($varValue == 'formdatalisting')
		{
			$GLOBALS['TL_LANG']['tl_module']['list_fields'] = $GLOBALS['TL_LANG']['tl_module']['efg_list_fields'];
			$GLOBALS['TL_LANG']['tl_module']['list_search'] = $GLOBALS['TL_LANG']['tl_module']['efg_list_search'];
			$GLOBALS['TL_LANG']['tl_module']['list_info'] = $GLOBALS['TL_LANG']['tl_module']['efg_list_info'];

			$GLOBALS['TL_DCA']['tl_module']['fields']['list_fields']['inputType'] = 'checkboxWizard';
			$GLOBALS['TL_DCA']['tl_module']['fields']['list_fields']['eval']['mandatory'] = false;
			$GLOBALS['TL_DCA']['tl_module']['fields']['list_fields']['options_callback'] = array('tl_ext_module', 'optionsListFields');
			$GLOBALS['TL_DCA']['tl_module']['fields']['list_fields']['load_callback'][] = array('tl_ext_module', 'onloadListFields');
			$GLOBALS['TL_DCA']['tl_module']['fields']['list_fields']['save_callback'][] = array('tl_ext_module', 'onsaveFieldList');

			$GLOBALS['TL_DCA']['tl_module']['fields']['list_search']['inputType'] = 'checkboxWizard';
			$GLOBALS['TL_DCA']['tl_module']['fields']['list_search']['options_callback'] = array('tl_ext_module', 'optionsSearchFields');
			$GLOBALS['TL_DCA']['tl_module']['fields']['list_search']['load_callback'][] = array('tl_ext_module', 'onloadSearchFields');
			$GLOBALS['TL_DCA']['tl_module']['fields']['list_search']['save_callback'][] = array('tl_ext_module', 'onsaveFieldList');

			$GLOBALS['TL_DCA']['tl_module']['fields']['list_info']['inputType'] = 'checkboxWizard';
			$GLOBALS['TL_DCA']['tl_module']['fields']['list_info']['options_callback'] = array('tl_ext_module', 'optionsInfoFields');
			$GLOBALS['TL_DCA']['tl_module']['fields']['list_info']['load_callback'][] = array('tl_ext_module', 'onloadInfoFields');
			$GLOBALS['TL_DCA']['tl_module']['fields']['list_info']['save_callback'][] = array('tl_ext_module', 'onsaveFieldList');
		}
		return $varValue;
	}

	/**
	 * Return all formdata tables as array
	 * @return array
	 */
	public function getFormdataTables(DataContainer $dc)
	{
		if (is_null($this->arrFormdataTables) || is_null($this->arrFormdataFields))
		{
			$this->arrFormdataTables = array();
			$this->arrFormdataTables['fd_feedback'] = $GLOBALS['TL_LANG']['MOD']['feedback'][0];

			// all forms marked to store data
			$objForms = $this->Database->prepare("SELECT f.id,f.title,f.formID,ff.type,ff.name,ff.label FROM tl_form f, tl_form_field ff WHERE (f.id=ff.pid) AND storeFormdata=? ORDER BY title")
										->execute("1");
			while ($objForms->next())
			{
				if (strlen($objForms->formID)) {
					$varKey = 'fd_' . $objForms->formID;
				}
				else
				{
					$varKey = 'fd_' . str_replace('-', '_', standardize($objForms->title));
				}
				$this->arrFormdataTables[$varKey] = $objForms->title;
				$this->arrFormdataFields['fd_feedback'][$objForms->name] = $objForms->label;
				$this->arrFormdataFields[$varKey][$objForms->name] = $objForms->label;
			}
		}

		$this->loadLanguageFile('tl_formdata');
		if (strlen($dc->value))
		{
			$this->loadDataContainer($dc->value);
		}
		return $this->arrFormdataTables;
	}

	public function optionsListFields(DataContainer $dc)
	{
		return $this->getFieldsOptionsArray('list_fields');
	}

	public function optionsSearchFields(DataContainer $dc)
	{
		return $this->getFieldsOptionsArray('list_search');
	}

	public function optionsInfoFields(DataContainer $dc)
	{
		return $this->getFieldsOptionsArray('list_info');
	}

	public function getFieldsOptionsArray($strField)
	{
		$arrReturn = array();
		if (count($GLOBALS['TL_DCA']['tl_formdata']['fields']))
		{
			$GLOBALS['TL_DCA']['tl_module']['fields'][$strField]['inputType'] = 'CheckboxWizard';
			$GLOBALS['TL_DCA']['tl_module']['fields'][$strField]['eval']['multiple'] = true;
			$GLOBALS['TL_DCA']['tl_module']['fields'][$strField]['eval']['mandatory'] = false;
			foreach ($GLOBALS['TL_DCA']['tl_formdata']['fields'] as $k => $v)
			{
				if (in_array($k, array('import_source')) )
				{
					continue;
				}
				$arrReturn[$k] = (strlen($GLOBALS['TL_DCA']['tl_formdata']['fields'][$k]['label'][0]) ? $GLOBALS['TL_DCA']['tl_formdata']['fields'][$k]['label'][0] . ' [' . $k . ']' : $k);
			}
		}
		return $arrReturn;
	}

	public function onloadListFields($varValue, DataContainer $dc)
	{
		return $this->onloadFieldList('list_fields', $varValue);
	}

	public function onloadSearchFields($varValue, DataContainer $dc)
	{
		return $this->onloadFieldList('list_search', $varValue);
	}

	public function onloadInfoFields($varValue, DataContainer $dc)
	{
		return $this->onloadFieldList('list_info', $varValue);
	}

	public function onsaveFieldList($varValue)
	{
		if (strlen($varValue))
		{
			return implode(',', deserialize($varValue));
		}
		return $varValue;
	}

	public function onloadFieldList($strField, $varValue)
	{
		if (isset($GLOBALS['TL_DCA']['tl_module']['fields'][$strField]))
		{
			$GLOBALS['TL_DCA']['tl_module']['fields'][$strField]['eval']['multiple'] = true;
			if (is_string($varValue))
			{
				$varValue = explode(',', $varValue);
			}
		}
		return $varValue;
	}

}

?>