<?php
if (!defined('TL_ROOT'))
	die('You can not access this file directly!');

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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Backend
 * @license    GPL
 * @filesource
 */

/**
 * Class EfgLookupOptionWizard
 *
 * Provide methods to handle form field lookup option
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
*/
class EfgLookupOptionWizard extends Widget {

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget';

	/**
	 * DB Tables and fields
	 * @var array
	 */
	protected $arrDbStruct = array ();

	/**
	 * DB Tables to ignore
	 * @var array
	 */
	protected $arrIgnoreTables = array ();

	/**
	 * DB Fields to ignore
	 * @var array
	 */
	protected $arrIgnoreFields = array ();

	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue) {
		switch ($strKey) {
			case 'value' :
				$this->varValue = deserialize($varValue);
				break;
			case 'mandatory' :
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;
			default :
				parent :: __set($strKey, $varValue);
				break;
		}
	}

	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate() {

		$this->arrIgnoreTables = array (
			'tl_formdata',
			'tl_formdata_details',
			'tl_cache',
			'tl_extension',
			//'tl_form',
			//'tl_form_field',
			'tl_layout',
			'tl_log',
			//'tl_module',
			'tl_search',
			'tl_search_index',
			'tl_session',
			'tl_style',
			'tl_style_sheet',
			'tl_undo',
			'tl_version'

		);
		$this->arrIgnoreFields = array (
			/* 'id', */
			'pid', 'tstamp', 'sorting'
		);

		// get all tables
		$this->import('Database');
		$arrTables = $this->Database->listTables();

		foreach ($arrTables as $strTable)
		{
			if (!in_array($strTable, $this->arrIgnoreTables))
			{
				$arrFields = $this->Database->listFields($strTable);

				foreach ($arrFields as $arrField)
				{
					if (!in_array($arrField['name'], $this->arrIgnoreFields) && $arrField['type'] != 'index')
					{
						$this->arrDbStruct[$strTable][] = $arrField['name'];
					}
				}
			}
		}

		unset ($arrTables);
		unset ($arrFields);

		// get all forms marked to store data
		$objForms = $this->Database->prepare("SELECT id,title,formID FROM tl_form WHERE storeFormdata=?")->execute("1");
		if ($objForms->numRows)
		{
			while ($objForms->next())
			{
				if (strlen($objForms->formID))
				{
					$varKey = 'fd_' . $objForms->formID;
				}
				else
				{
					$varKey = 'fd_' . str_replace('-', '_', standardize($objForms->title));
				}

				if (!in_array($varKey, $this->arrIgnoreTables))
				{
					$objFields = $this->Database->prepare("SELECT DISTINCT ff.name FROM tl_form_field ff, tl_form f WHERE (ff.pid=f.id) AND ff.name != '' AND f.id=?")->execute($objForms->id);
					if ($objFields->numRows)
					{
						$this->arrDbStruct[$varKey][] = 'form';
						$this->arrDbStruct[$varKey][] = 'published';
						while ($objFields->next())
						{
							$this->arrDbStruct[$varKey][] = $objFields->name;
						}
					}
				}
			}
		}
		unset ($arrTables);
		unset ($arrFields);

		ksort($this->arrDbStruct);

		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || !$this->varValue['lookup_field'])
		{
			$this->varValue = array (
				array (
					''
				)
			);
		}

		$strSelectedTable = '';
		if (isset($this->varValue['lookup_field']) && strlen($this->varValue['lookup_field']))
		{
			$strSelectedTable = substr($this->varValue['lookup_field'], 0, strpos($this->varValue['lookup_field'], '.'));
		}

		// Begin table

		// table field used as option label
		$return .= '<div class="w50"><h3><label for="' . $this->strId . '_lookup_field">' . $GLOBALS['TL_LANG'][$this->strTable]['lookup_field'][0] . '</label></h3>
				<select name="' . $this->strId . '[lookup_field]" id="' . $this->strId . '_lookup_field" class="tl_select tl_chosen" onchange="Backend.autoSubmit(\'tl_form_field\');" onfocus="Backend.getScrollOffset();">';
		foreach ($this->arrDbStruct as $strTable => $arrFields)
		{
			$return .= '<optgroup label="' . $strTable . '">';
			foreach ($arrFields as $strField)
			{
				if ($strField == 'id')
				{
					continue;
				}
				$strSelected = ($this->varValue['lookup_field'] == $strTable . '.' . $strField) ? " selected " : "";
				$return .= '<option value="' . $strTable . '.' . $strField . '"' . $strSelected . '>' . $strTable . '.' . $strField . '</option>';

			}
			$return .= '</optgroup>';
		}
		$return .= '</select>';
		$return .= '
			<p class="tl_help tl_tip">' . $GLOBALS['TL_LANG'][$this->strTable]['lookup_field'][1] .'</p></div>';

		// table field used as option value
		if ($strSelectedTable != '' && substr($strSelectedTable, 0, 3)!='fd_' )
		{
			$return .= '<div class="w50"><h3><label for="' . $this->strId . '_lookup_val_field">' . $GLOBALS['TL_LANG'][$this->strTable]['lookup_val_field'][0] . '</label></h3>';
			$return .= '<select name="' . $this->strId . '[lookup_val_field]" id="' . $this->strId . '_lookup_val_field" class="tl_select tl_chosen">';
			foreach ($this->arrDbStruct as $strTable => $arrFields)
			{
				if ($strSelectedTable == $strTable)
				{
					foreach ($arrFields as $strField)
					{
						$strSelected = ($this->varValue['lookup_val_field'] == $strTable . '.' . $strField) ? " selected " : "";
						$return .= '<option value="' . $strTable . '.' . $strField . '"' . $strSelected . '>' . $strTable . '.' . $strField . '</option>';
					}
				}
			}
			$return .= '</select>';
			$return .= '
				<p class="tl_help tl_tip">' . $GLOBALS['TL_LANG'][$this->strTable]['lookup_val_field'][1] .'</p></div>';

		}


		// condition
		$return .= '<div class="w50 clr"><h3><label for="' . $this->strId . '_lookup_where">' . $GLOBALS['TL_LANG'][$this->strTable]['lookup_where'][0] . '</label></h3>
				<input type="text" name="' . $this->strId . '[lookup_where]" id="' . $this->strId . '_lookup_where" value="' . $this->varValue['lookup_where'] . '" class="tl_text"' . $this->strTagEnding;
		$return .= '
			<p class="tl_help tl_tip">' . $GLOBALS['TL_LANG'][$this->strTable]['lookup_where'][1] .'</p></div>';


		// order
		$return .= '<div class="w50"><h3><label for="' . $this->strId . '_lookup_sort">' . $GLOBALS['TL_LANG'][$this->strTable]['lookup_sort'][0] . '</label></h3>
				<input type="text" name="' . $this->strId . '[lookup_sort]" id="' . $this->strId . '_lookup_sort" value="' . $this->varValue['lookup_sort'] . '" class="tl_text"' . $this->strTagEnding;
		$return .= '
			<p class="tl_help tl_tip">' . $GLOBALS['TL_LANG'][$this->strTable]['lookup_sort'][1] .'</p></div>';

		return $return;

	}

}