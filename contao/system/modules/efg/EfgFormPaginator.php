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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class EfgFormPaginator
 *
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */
class EfgFormPaginator extends Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_paginator';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'singleSRC':
				$this->arrConfiguration['singleSRC'] = $varValue;
				break;

			case 'imageSubmit':
				$this->arrConfiguration['imageSubmit'] = $varValue ? true : false;
				break;

			case 'name':
				$this->arrAttributes['name'] = $varValue;
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		return;
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{

		global $objPage;

		if ($objPage->outputFormat == 'html5')
		{
			$blnIsHtml5 = true;
		}


		$return = '';

		if ($this->efgAddBackButton && (($this->formTotalPages > 1 && $this->formActivePage > 1) || TL_MODE == 'BE'))
		{
			if ($this->efgBackImageSubmit && is_file(TL_ROOT . '/' . $this->efgBackSingleSRC))
			{
				$return .= sprintf('<input type="image"%s src="%s" id="ctrl_%s_back" class="submit back%s" alt="%s" title="%s" value="%s"%s%s',
								($this->formActivePage ? ' name="FORM_BACK"' : ''),
								$this->efgBackSingleSRC,
								$this->strId,
								(strlen($this->strClass) ? ' ' . $this->strClass : ''),
								specialchars($this->efgBackSlabel),
								specialchars($this->efgBackSlabel),
								specialchars('submit_back'),
								$this->getAttributes(),
								$this->strTagEnding);
			}
			else
			{
				$return .= sprintf('<input type="submit"%s id="ctrl_%s_back" class="submit back%s" value="%s"%s%s',
							($this->formActivePage ? ' name="FORM_BACK"' : ''),
							$this->strId,
							(strlen($this->strClass) ? ' ' . $this->strClass : ''),
							specialchars($this->efgBackSlabel),
							$this->getAttributes(),
							$this->strTagEnding);
			}
		}


		if ($this->imageSubmit && is_file(TL_ROOT . '/' . $this->singleSRC))
		{
			$return .= sprintf('<input type="image"%s src="%s" id="ctrl_%s" class="submit next%s" alt="%s" title="%s" value="%s"%s%s',
							($this->formActivePage ? ' name="FORM_NEXT"' : ''),
							$this->singleSRC,
							$this->strId,
							(strlen($this->strClass) ? ' ' . $this->strClass : ''),
							specialchars($this->slabel),
							specialchars($this->slabel),
							specialchars('submit_next'),
							$this->getAttributes(),
							$this->strTagEnding);
		}
		else
		{
			$return .= sprintf('<input type="submit"%s id="ctrl_%s" class="submit next%s" value="%s"%s%s',
							($this->formActivePage ? ' name="FORM_NEXT"' : ''),
							$this->strId,
							(strlen($this->strClass) ? ' ' . $this->strClass : ''),
							specialchars($this->slabel),
							$this->getAttributes(),
							$this->strTagEnding);
		}

		return $return;

	}
}