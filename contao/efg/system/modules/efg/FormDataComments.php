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
 * @author     Leo Feyer
 * @filesource
 */

/**
 * Class FormDataComments
 *
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */
class FormDataComments extends Backend
{

	/**
	 * List a particular record
	 * @param array
	 * @return string
	 */
	public function listComments($arrRow)
	{
		$this->import('FormData');

		$strRet = '';

		$objParent = $this->Database->prepare("SELECT `id`, `form`, `alias`  FROM tl_formdata WHERE id=?")
											->execute($arrRow['parent']);

		if ($objParent->numRows)
		{
			$strRet .= ' (' . $objParent->form;

			if (strlen($objParent->alias))
			{
				$strRet .= ' - '.$objParent->alias;
			}
			$strRet .= ')';
		}

		return $strRet;
	}


}
