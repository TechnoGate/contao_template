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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Backend
 * @license    GPL
 * @filesource
 */

/**
 * Class EfgFormLookupRadio
 *
 * Form field "radio (DB)".
 * based on FormRadio by Leo Feyer
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    Controller
 */
class EfgFormLookupRadio extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_widget';

	/**
	 * Options
	 * @var array
	 */
	protected $arrOptions = array();


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'efgLookupOptions':
				$this->import('Database');
				$this->import('String');
				$this->import('FormData');
				$this->arrConfiguration['efgLookupOptions'] = $varValue;

				$arrOptions = $this->FormData->prepareDcaOptions($this->arrConfiguration);
				$this->arrOptions = $arrOptions;
				break;

			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			case 'rgxp':
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Return a parameter
	 * @return string
	 * @throws Exception
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'options':
				return $this->arrOptions;
				break;
			default:
				return parent::__get($strKey);
				break;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$strOptions = '';

		foreach ($this->arrOptions as $i=>$arrOption)
		{
			$strOptions .= sprintf('<span><input type="radio" name="%s" id="opt_%s" class="radio" value="%s"%s%s <label for="opt_%s">%s</label></span>',
									$this->strName,
									$this->strId.'_'.$i,
									$arrOption['value'],
									((is_array($this->varValue) && in_array($arrOption['value'] , $this->varValue) || $this->varValue == $arrOption['value']) ? ' checked="checked"' : ''),
									$this->strTagEnding,
									$this->strId.'_'.$i,
									$arrOption['label']);
		}

        return sprintf('<div id="ctrl_%s" class="radio_container%s">%s</div>',
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$strOptions) . $this->addSubmit();
	}

}