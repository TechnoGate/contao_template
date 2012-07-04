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
 * Class EfgFormLookupSelectMenu
 *
 * Form field "select menu (DB)".
 * based on FormSelectMenu by Leo Feyer
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    Controller2007 - 2012
 */
class EfgFormLookupSelectMenu extends Widget
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
			case 'mSize':
				if ($this->multiple)
				{
					$this->arrAttributes['size'] = $varValue;
				}
				break;
			case 'efgLookupOptions':
				$this->import('Database');
				$this->import('String');
				$this->import('FormData');

				$this->arrConfiguration['efgLookupOptions'] = $varValue;
				$arrOptions = $this->FormData->prepareDcaOptions($this->arrConfiguration);
				$this->arrOptions = $arrOptions;
				break;

			case 'multiple':
				if (strlen($varValue))
				{
					$this->arrAttributes[$strKey] = 'multiple';
				}
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
		$strClass = 'select';
		$strReferer = $this->getReferer();
		$arrLookupOptions = deserialize($this->arrConfiguration['efgLookupOptions']);
		$strLookupTable = substr($arrLookupOptions['lookup_field'], 0, strpos($arrLookupOptions['lookup_field'], '.'));
		$blnSingleEvent = false;

		// if used as lookup on table tl_calendar_events and placed on events detail page
		if ($strLookupTable=='tl_calendar_events' && strlen($this->Input->get('events')) )
		{
			if (count($this->arrOptions)==1)
			{
				$this->varValue = array($this->arrOptions[0]['value']);
				$blnSingleEvent = true;
			}
		}
		// .. equivalent,  if linked from event reader or events list page
		if ($strLookupTable=='tl_calendar_events' && (strpos($strReferer, '/event-reader/events/') || strpos($strReferer, '&events=') ) )
		{
			if (count($this->arrOptions)==1)
			{
				$this->varValue = array($this->arrOptions[0]['value']);
				$blnSingleEvent = true;
			}
		}

		if ($this->multiple)
		{
			$this->strName .= '[]';
			$strClass = 'multiselect';
		}

		// Add empty option (XHTML) if there are none
		if (!count($this->arrOptions) && !$blnSingleEvent )
		{
			$this->arrOptions = array(array('value'=>'', 'label'=>'-'));
		}
		foreach ($this->arrOptions as $i => $arrOption)
		{
			$selected = '';
			if ( (is_array($this->varValue) && in_array($arrOption['value'] , $this->varValue) || $this->varValue == $arrOption['value']) )
			{
			  $selected = ' selected="selected"';
			}

			$strOptions .= sprintf('<option value="%s"%s>%s</option>',
									$arrOption['value'],
									$selected,
									$arrOption['label']);

			// render as checked radio if used as lookup on tl_calendar_events and only one event available
			if ($strLookupTable=='tl_calendar_events' && $blnSingleEvent)
			{
				$selected = ' checked="checked"';
				$strOptions =  sprintf('<span><input type="radio" name="%s" id="opt_%s" class="radio" value="%s"%s%s <label for="opt_%s">%s</label></span>',
									$this->strName . ((count($this->arrOptions) > 1) ? '[]' : ''),
									$this->strId.'_'.$i,
									$arrOption['value'],
									$selected,
									$this->strTagEnding,
									$this->strId.'_'.$i,
									$arrOption['label']);

		        return sprintf('<div id="ctrl_%s" class="radio_container%s">%s</div>',
								$this->strId,
								(strlen($this->strClass) ? ' ' . $this->strClass : ''),
								$strOptions) . $this->addSubmit();

			}

		}



		return sprintf('<select name="%s" id="ctrl_%s" class="%s%s"%s>%s</select>',
						$this->strName,
						$this->strId,
						$strClass,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						$strOptions) . $this->addSubmit();
	}
}