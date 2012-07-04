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
 * Class FormData
 *
 * Provide methods to handle data stored in tables tl_formdata and tl_formdata_details.
 *
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */
class FormData extends Frontend
{
	/**
	 * Items in tl_form, all forms marked to store data in tl_formdata
	 * @param array
	 */
	protected $arrStoreForms = null;

	protected $arrFormsDcaKey = null;
	protected $arrFormdataDetailsKey = null;

	/**
	 * Types of form fields with storable data
	 * @var array
	 */
	protected $arrFFstorable = array();

	protected $strFdDcaKey = null;

	protected $arrListingPages = null;
	protected $arrSearchableListingPages = null;

	public function __construct()
	{
		parent::__construct();

		$this->import('Database');
		$this->import('String');

		// Types of form fields with storable data
		$this->arrFFstorable = array(
			'sessionText', 'sessionOption', 'sessionCalculator',
			'hidden','text','calendar','xdependentcalendarfields','password','textarea',
			'select','efgImageSelect','conditionalselect', 'countryselect', 'fp_preSelectMenu','efgLookupSelect',
			'radio','efgLookupRadio',
			'checkbox','efgLookupCheckbox',
			'upload', 'fileTree'
		);

		if (is_array($GLOBALS['EFG']['storable_fields']) && count($GLOBALS['EFG']['storable_fields']))
		{
			$this->arrFFstorable = array_unique(array_merge($this->arrFFstorable, $GLOBALS['EFG']['storable_fields']));
		}

		$this->getStoreForms();

	}

	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'FdDcaKey':
				return $this->strFdDcaKey;
				break;
			case 'arrFFstorable':
				return $this->arrFFstorable;
				break;
			case 'arrStoreForms':
				return $this->arrStoreForms;
				break;
			case 'arrFormsDcaKey':
				return $this->arrFormsDcaKey;
				break;
		}
	}

	/**
	 * Autogenerate an alias if it has not been set yet
	 * if no form field is configured to be used as alias field
	 * first form field of type text not using rgxp=email/date/datim/time will be used
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($varValue=null, $strFormTitle=null, $intRecId=null)
	{
		$autoAlias = false;
		$strAliasField = '';

		if (is_null($strFormTitle))
		{
			return '';
		}
		if (intval($intRecId)==0)
		{
			return '';
		}

		// get field used to build alias
		$objForm = $this->Database->prepare("SELECT id, efgAliasField FROM tl_form WHERE title=?")
					->limit(1)
					->execute($strFormTitle);
		if ($objForm->numRows)
		{
			 if (strlen($objForm->efgAliasField))
			 {
			 	$strAliasField = $objForm->efgAliasField;
			 }
		}

		if ($strAliasField == '')
		{
			$objFormField = $this->Database->prepare("SELECT ff.name FROM tl_form f, tl_form_field ff WHERE (f.id=ff.pid) AND f.title=? AND ff.type=? AND ff.rgxp NOT IN ('email','date','datim','time') ORDER BY sorting")
							->limit(1)
							->execute($strFormTitle, 'text');

			if ($objFormField->numRows)
			{
				$strAliasField = $objFormField->name;
			}

		}

		// Generate alias if there is none
		if (is_null($varValue) || !strlen($varValue))
		{
			if (strlen($strAliasField))
			{
				// get value from post
				$autoAlias = true;
				$varValue = standardize($this->Input->post($strAliasField));
			}
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_formdata WHERE alias=? AND id != ?")
								   ->execute($varValue, $intRecId);

		// Check whether the alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= (strlen($varValue) ? '.' : '') . $intRecId;
		}

		return $varValue;
	}

	/**
	 * Get Listing Detail page ID from url
	 * is used no longer, just for backwards compatibility (not actual formdata dca files)
	 */
	public function getDetailPageIdFromUrl($arrFragments) {
		return $arrFragments;
	}

	/**
	 * Add formdata details to the indexer
	 * @param array
	 * @param integer
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0)
	{

		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->getChildRecords($intRoot, 'tl_page', true);
		}

		$this->getSearchableListingPages();

		$arrProcessed = array();

		if (is_array($this->arrSearchableListingPages) && count($this->arrSearchableListingPages)>0)
		{
			$this->loadDataContainer('fd_feedback');

			foreach ($this->arrSearchableListingPages as $pageId => $arrParams)
			{

				if (is_array($arrRoot) && count($arrRoot) > 0 && !in_array($pageId, $arrRoot))
				{
					continue;
				}

				// do not add if list condition contains insert tags
				if (strlen($arrParams['list_where']))
				{
					if (strpos($arrParams['list_where'], '{{') !== false)
					{
						continue;
					}
				}
				// do not add if no listing details fields are defined
				if (!strlen($arrParams['list_info']))
				{
					continue;
				}

				if (!strlen($arrParams['list_formdata']))
				{
					continue;
				}

				if (!isset($arrProcessed[$pageId]))
				{

					$arrProcessed[$pageId] = false;

					$strForm = '';
					$strFormsKey = substr($arrParams['list_formdata'], strlen('fd_'));
					if (isset($this->arrFormsDcaKey[$strFormsKey]))
					{
						$strForm = $this->arrFormsDcaKey[$strFormsKey];
					}

					$pageAlias = (strlen($arrParams['alias']) ? $arrParams['alias'] : null);

					if (strlen($strForm))
					{
						$strFormdataDetailsKey = 'details';
						if (strlen($arrParams['formdataDetailsKey']))
						{
							$strFormdataDetailsKey = $arrParams['formdataDetailsKey'];
						}

						// Determine domain
						if (intval($pageId)>0)
						{
							$domain = $this->Environment->base;
							$objParent = $this->getPageDetails($pageId);

							if (strlen($objParent->domain))
							{
								$domain = ($this->Environment->ssl ? 'https://' : 'http://') . $objParent->domain . TL_PATH . '/';
							}
						}
						$arrProcessed[$pageId] = $domain . $this->generateFrontendUrl($objParent->row(), '/'.$strFormdataDetailsKey.'/%s');
					}

					if ($arrProcessed[$pageId] === false)
					{
						continue;
					}

					$strUrl = $arrProcessed[$pageId];

					// prepare conditions
					$strQuery = "SELECT id,alias FROM tl_formdata f";
					$strWhere = " WHERE form=?";

					if (strlen($arrParams['list_where']))
					{
						$arrListWhere = array();
						$arrListConds = preg_split('/(\sAND\s|\sOR\s)/si', $arrParams['list_where'], -1, PREG_SPLIT_DELIM_CAPTURE);

						foreach ($arrListConds as $strListCond)
						{
							if (preg_match('/\sAND\s|\sOR\s/si', $strListCond))
							{
								$arrListWhere[] = $strListCond;
							}
							else
							{
								$arrListCond = preg_split('/([\s!=><]+)/', $strListCond, -1, PREG_SPLIT_DELIM_CAPTURE);
								$strCondField = $arrListCond[0];

								unset($arrListCond[0]);
								if (in_array($strCondField, $GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['detailFields']))
								{
									$arrListWhere[] = '(SELECT value FROM tl_formdata_details WHERE ff_name="'.$strCondField.'" AND pid=f.id ) ' . implode('', $arrListCond);
								}
								if (in_array($strCondField, $GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['baseFields']))
								{
									$arrListWhere[] = $strCondField . implode('', $arrListCond);
								}
							}
						}
						$strListWhere = (count($arrListWhere)>0) ? '(' . implode('', $arrListWhere) .')' : '';
						$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . $strListWhere;
					}

					$strQuery .=  $strWhere;

					// add details pages to the indexer
					$objData = $this->Database->prepare($strQuery)
							->execute($strForm);

					while ($objData->next())
					{
						$arrPages[] = sprintf($strUrl, ((strlen($objData->alias) && !$GLOBALS['TL_CONFIG']['disableAlias']) ? $objData->alias : $objData->id));
					}

				}

			} // foreach arrSearchableListingPages

		}

		return $arrPages;

	}

	/**
	 * Get all forms marked to store data in tl_formdata
	 */
	public function getStoreForms()
	{

		if (!$this->arrStoreForms)
		{
			// get all forms marked to store data
			$objForms = $this->Database->prepare("SELECT id,title,formID,useFormValues,useFieldNames FROM tl_form WHERE storeFormdata=?")
											->execute("1");

			while ($objForms->next())
			{
				if (strlen($objForms->formID)) {
					$varKey = str_replace('-', '_', standardize($objForms->formID));
				}
				else
				{
					$varKey = str_replace('-', '_', standardize($objForms->title));
				}
				$this->arrStoreForms[$varKey] = $objForms->row();
				$this->arrFormsDcaKey[$varKey] = $objForms->title;
			}
		}
	}

	/**
	 * Get all pages containig frontend module formdata listing
	 * @return array
	 */
	private function getListingPages()
	{
		if (!$this->arrListingPages)
		{
			// get all pages containig listing formdata
			$objListingPages = $this->Database->prepare("SELECT tl_page.id,tl_page.alias FROM tl_page, tl_content, tl_article, tl_module WHERE (tl_page.id=tl_article.pid AND tl_article.id=tl_content.pid AND tl_content.module=tl_module.id) AND tl_content.type=? AND tl_module.type=?")
									->execute("module", "formdatalisting");
			while ($objListingPages->next())
			{
				$this->arrListingPages[$objListingPages->id] = $objListingPages->alias;
			}
		}

		return $this->arrListingPages;
	}

	/**
	 * Get all pages for search indexer
	 * @return array
	 */
	private function getSearchableListingPages()
	{
		if (!$this->arrSearchableListingPages)
		{
			// get all pages containig listing formdata with details page
			$objListingPages = $this->Database->prepare("SELECT tl_page.id,tl_page.alias,tl_page.protected,tl_module.list_formdata,tl_module.efg_DetailsKey,tl_module.list_where,tl_module.efg_list_access,tl_module.list_fields,tl_module.list_info FROM tl_page, tl_content, tl_article, tl_module WHERE (tl_page.id=tl_article.pid AND tl_article.id=tl_content.pid AND tl_content.module=tl_module.id) AND tl_content.type=? AND tl_module.type=? AND tl_module.list_info != '' AND tl_module.efg_list_access=? AND (tl_page.start=? OR tl_page.start<?) AND (tl_page.stop=? OR tl_page.stop>?) AND tl_page.published=?")
									->execute("module", "formdatalisting", "public", '', time(), '', time(), 1);
			while ($objListingPages->next())
			{
				$strFormdataDetailsKey = 'details';
				if (strlen($objListingPages->efg_DetailsKey)) {
					$strFormdataDetailsKey = $objListingPages->efg_DetailsKey;
				}
				$this->arrSearchableListingPages[$objListingPages->id] = array('formdataDetailsKey' => $strFormdataDetailsKey, 'alias' => $objListingPages->alias, 'protected' => $objListingPages->protected, 'list_formdata' => $objListingPages->list_formdata, 'list_where' => $objListingPages->list_where, 'list_fields' => $objListingPages->list_fields, 'list_info' => $objListingPages->list_info, 'efg_list_acces' => $objListingPages->efg_list_access);
			}
		}

		return $this->arrSearchableListingPages;
	}

	/**
	 * Return record from tl_formdata as Array('fd_base' => base fields from tl_formdata, 'fd_details' => detail fields from tl_formdata_details)
	 * @param integer ID of tl_formdata record
	 * @return mixed
	 */
	public function getFormdataAsArray($intId=0)
	{

		$varReturn = array();

		if($intId > 0)
		{
			$objFormdata = $this->Database->prepare("SELECT * FROM tl_formdata WHERE id=?")
										->executeUncached($intId);
			if ($objFormdata->numRows == 1)
			{
				$varReturn['fd_base'] = $objFormdata->fetchAssoc();

				$objFormdataDetails = $this->Database->prepare("SELECT * FROM tl_formdata_details WHERE pid=?")
										->execute($intId);
				if ($objFormdataDetails->numRows)
				{
					$arrTemp = $objFormdataDetails->fetchAllAssoc();
					foreach ($arrTemp as $k => $arr)
					{
						$varReturn['fd_details'][$arr['ff_name']] = $arr;
					}
					unset($arrTemp);
				}
			}

			return $varReturn;

		}
		else
		{
			return false;
		}
	}

	/**
	 * Return form fields as associative array
	 * @param integer ID of tl_form record
	 * @return mixed
	 */
	public function getFormfieldsAsArray($intId=0)
	{

		$varReturn = array();

		if ($intId > 0)
		{
			$objFormFields = $this->Database->prepare("SELECT * FROM tl_form_field WHERE pid=? ORDER BY sorting ASC")
											->execute($intId);

			while ($objFormFields->next())
			{
				if (strlen($objFormFields->name)) {
					$varKey = $objFormFields->name;
				}
				else
				{
					$varKey = $objFormFields->id;
				}
				$varReturn[$varKey] = $objFormFields->row();
			}

			return $varReturn;

		}
		else
		{
			return false;
		}
	}

	/**
	 * Prepare post value for tl_formdata / tl_formdata_details DB record
	 * @param mixed post value
	 * @param array form field properties
	 * @param mixed file
	 * @return mixed
	 */
	public function preparePostValForDb($varSubmitted='', $arrField=false, $varFile=false)
	{

		if (!is_array($arrField))
		{
			return false;
		}

		$strType = $arrField['type'];
		$strVal = '';

		if (in_array($strType, $this->arrFFstorable))
		{

			switch ($strType)
			{
				case 'efgLookupCheckbox':
				case 'checkbox':
					$strSep = '';
					$strVal = '';
					$arrOptions = $this->prepareDcaOptions($arrField);

					$arrSel = array();
					if (is_string($varSubmitted))
					{
						$arrSel[] = $varSubmitted;
					}
					if (is_array($varSubmitted))
					{
						$arrSel = $varSubmitted;
					}

					foreach ($arrOptions as $o => $mxVal)
					{
						if (in_array($mxVal['value'], $arrSel))
						{
							//$strVal .= $strSep . $arrOptions[$o]['label'];
							if ($strType=='checkbox' && $arrField['eval']['efgStoreValues'])
							{
								$strVal .= $strSep . $arrOptions[$o]['value'];
							}
							else
							{
								$strVal .= $strSep . $arrOptions[$o]['label'];
							}
							$strSep = '|';
						}
					}

					if ($strVal == '')
					{
						$strVal = $varSubmitted;
						if (is_array($strVal))
						{
							$strVal = implode('|', $strVal);
						}
					}
				break;
				case 'efgLookupRadio':
				case 'radio':
					$strVal = $varSubmitted;
					$arrOptions = $this->prepareDcaOptions($arrField);
					foreach ($arrOptions as $o => $mxVal)
					{
						if ($mxVal['value'] == $varSubmitted)
						{
							//$strVal = $arrOptions[$o]['label'];
							if ($strType=='radio' && $arrField['eval']['efgStoreValues'])
							{
								$strVal = $arrOptions[$o]['value'];
							}
							else
							{
								$strVal = $arrOptions[$o]['label'];
							}
						}
					}
				break;
				case 'efgLookupSelect':
				case 'conditionalselect':
				case 'countryselect':
				case 'fp_preSelectMenu':
				case 'select':
					$strSep = '';
					$strVal = '';

					$arrOptions = $this->prepareDcaOptions($arrField);

					// select multiple
					if (is_array($varSubmitted))
					{
						foreach ($arrOptions as $o => $mxVal)
						{
							if (in_array($mxVal['value'], $varSubmitted))
							{
								//$strVal .= $strSep . $arrOptions[$o]['label'];
								if ($arrField['eval']['efgStoreValues'] && ($strType=='select' || $strType=='conditionalselect' || $strType=='countryselect' || $strType=='fp_preSelectMenu') )
								{
									$strVal .= $strSep . $arrOptions[$o]['value'];
								}
								else
								{
									$strVal .= $strSep . $arrOptions[$o]['label'];
								}
								$strSep = '|';
							}
						}
					}

					// select single
					if (is_string($varSubmitted))
					{
						foreach ($arrOptions as $o => $mxVal)
						{
							if ($mxVal['value'] == $varSubmitted)
							{
								//$strVal = $arrOptions[$o]['label'];
								if ($arrField['eval']['efgStoreValues'] && ($strType=='select' || $strType=='conditionalselect' || $strType=='countryselect' || $strType=='fp_preSelectMenu') )
								{
									$strVal = $arrOptions[$o]['value'];
								}
								else
								{
									$strVal = $arrOptions[$o]['label'];
								}
							}
						}
					}
				break;
				case 'efgImageSelect':
					$strVal = '';
					if (is_array($varSubmitted))
					{
						$strVal = implode('|', $varSubmitted);
					}
					elseif (strlen($varSubmitted))
					{
						$strVal = $varSubmitted;
					}
				break;
				case 'upload':
					$strVal = '';
					if (strlen($varFile['name']))
					{
						if ($arrField['storeFile'] == "1")
						{
							if ($arrField['useHomeDir'] == "1")
							{
								// Overwrite upload folder with user home directory
								if (FE_USER_LOGGED_IN)
								{
									$this->import('FrontendUser', 'User');
									if ($this->User->assignDir && $this->User->homeDir && is_dir(TL_ROOT . '/' . $this->User->homeDir))
									{
										$arrField['uploadFolder'] = $this->User->homeDir;
									}
								}
							}
							$strVal = $arrField['uploadFolder'] . '/' . $varFile['name'];
						}
						else
						{
							$strVal = $varFile['name'];
						}
					}

				break;

				case 'text':
				case 'calendar':
				case 'xdependentcalendarfields':
					$strVal = $varSubmitted;
					if (is_string($strVal) && strlen($strVal) && in_array($arrField['rgxp'], array('date', 'time', 'datim')))
					{
						$strFormat = $GLOBALS['TL_CONFIG'][$arrField['rgxp'] . 'Format'];
						if (!empty($arrField['dateFormat']))
						{
							$strFormat = $arrField['dateFormat'];
						}
						$objDate = new Date($strVal, $strFormat);
						$strVal = $objDate->tstamp;
					}
					else
					{
						$strVal = $varSubmitted;
					}
				break;

				default:
					$strVal = $varSubmitted;
					break;

			}

			if (is_array($strVal))
			{
				foreach ($strVal as $k=>$value)
				{
					$strVal[$k] = $this->String->decodeEntities($value);
				}
				$strVal = serialize($strVal);
			}
			else
			{
				$strVal = $this->String->decodeEntities($strVal);
			}

			return $strVal;

		} // if in_array arrFFstorable
		else
		{
			//return (is_array($varSubmitted) ? implode('|', $varSubmitted) : $varSubmitted);
			return (is_array($varSubmitted) ? serialize($varSubmitted) : $varSubmitted);
		}

	}


	/**
	 * Prepare value from CSV for tl_formdata / tl_formdata_details DB record
	 * @param string field value from csv file
	 * @param array form field properties
	 * @return mixed
	 */
	public function prepareImportValForDb($varValue='', $arrField=false)
	{
		if (!is_array($arrField))
		{
			return false;
		}

		$strType = $arrField['type'];
		$strVal = '';

		if ( in_array($strType, $this->arrFFstorable) )
		{

			switch ($strType)
			{
				case 'efgLookupCheckbox':
				case 'checkbox':
				case 'efgLookupRadio':
				case 'radio':
				case 'efgLookupSelect':
				case 'efgImageSelect':
				case 'conditionalselect':
				case 'countryselect':
				case 'fp_preSelectMenu':
				case 'select':
					$strVal = '';
					if ($arrField['eval']['multiple'])
					{
						$arrSel = array();
						if (strlen($varValue))
						{
							$arrSel = trimsplit(",\n", $varValue);
						}
						$strVal = implode('|', $arrSel);
					}
					else
					{
						$strVal = $varValue;
					}
				break;
				case 'text':
				case 'calendar':
				case 'xdependentcalendarfields':
					$strVal = $varValue;
					// Convert date formats into timestamps
					if (in_array($arrField['eval']['rgxp'], array('date', 'time', 'datim')))
					{
						if (is_numeric($strVal))
						{
							$strVal = (int) $strVal;
						}
						elseif (is_string($strVal) && strlen($strVal) )
						{
							$strFormat = $GLOBALS['TL_CONFIG'][$arrField['eval']['rgxp'] . 'Format'];
//							if (!empty($arrField['eval']['dateFormat']))
//							{
//								$strFormat = $arrField['eval']['dateFormat'];
//							}

							$objDate = new Date($strVal, $strFormat);
							$strVal = $objDate->tstamp;
						}
					}
				break;

				case 'hidden':
				case 'textarea':
				case 'upload':
				case 'password':
				default:
					$strVal = $varValue;
				break;

			}

			return (is_array($strVal) || is_object($strVal)) ? serialize($strVal) : $this->String->decodeEntities($strVal);

		} // if in_array arrFFstorable
		else
		{
			return (is_array($strVal) || is_object($strVal)) ? serialize($varValue) : $this->String->decodeEntities($varValue);
		}

	}



	/**
	 * Prepare post value for Mail / Text
	 * @param mixed post value
	 * @param array form field properties
	 * @param mixed file
	 * @param boolean skip empty values (do not return label of selected option if its value is empty)
	 * @return mixed
	 */
	public function preparePostValForMail($varSubmitted='', $arrField=false, $varFile=false, $blnSkipEmpty=false)
	{
		if (!is_array($arrField))
		{
			return false;
		}

		$strType = $arrField['type'];
		$strVal = '';

		if (isset($arrField['efgMailSkipEmpty']))
		{
			$blnSkipEmpty = $arrField['efgMailSkipEmpty'];
		}

		if ( in_array($strType, $this->arrFFstorable) )
		{

			switch ($strType)
			{
				case 'efgLookupCheckbox':
				case 'checkbox':
					$strSep = '';
					$strVal = '';
					$arrOptions = $this->prepareDcaOptions($arrField);

					$arrSel = array();
					if (is_string($varSubmitted))
					{
						$arrSel[] = $varSubmitted;
					}
					if (is_array($varSubmitted))
					{
						$arrSel = $varSubmitted;
					}
					foreach ($arrOptions as $o => $mxVal)
					{
						if ($blnSkipEmpty && !strlen($mxVal['value']))
						{
							continue;
						}

						if (in_array($mxVal['value'], $arrSel))
						{
							$strVal .= $strSep . $mxVal['label'];
							$strSep = ', ';
						}
					}

					if ($strVal == '')
					{
						$strVal = (is_array($varSubmitted) ? implode(', ', $varSubmitted) : $varSubmitted);
					}
				break;
				case 'efgLookupRadio':
				case 'radio':
					$strVal = (is_array($varSubmitted) ? $varSubmitted[0] : $varSubmitted);
					$arrOptions = $this->prepareDcaOptions($arrField);
					foreach ($arrOptions as $o => $mxVal)
					{
						if ($mxVal['value'] == $varSubmitted)
						{
							$strVal = $mxVal['label'];
						}
					}
				break;
				case 'efgLookupSelect':
				case 'conditionalselect':
				case 'countryselect':
				case 'fp_preSelectMenu':
				case 'select':
					$strSep = '';
					$strVal = '';
					$arrOptions = $this->prepareDcaOptions($arrField);

					// select multiple
					if (is_array($varSubmitted))
					{
						foreach ($arrOptions as $o => $mxVal)
						{
							if ($blnSkipEmpty && !strlen($mxVal['value']))
							{
								continue;
							}

							if (in_array($mxVal['value'], $varSubmitted))
							{
								$strVal .= $strSep . $mxVal['label'];
								$strSep = ', ';
							}
						}
					}

					// select single
					if (is_string($varSubmitted))
					{
						foreach ($arrOptions as $o => $mxVal)
						{
							if ($blnSkipEmpty && !strlen($mxVal['value']))
							{
								continue;
							}

							if ($mxVal['value'] == $varSubmitted)
							{
								$strVal = $mxVal['label'];
							}
						}
					}
				break;
				case 'efgImageSelect':
					$strVal = '';
					if (is_string($varSubmitted) && strlen($varSubmitted))
					{
						$strVal = $varSubmitted;
					}
					elseif (is_array($varSubmitted))
					{
						//$strVal = implode(', ', $varSubmitted);
						$strVal = $varSubmitted;
					}
				break;
				case 'upload':
					$strVal = '';
					if (strlen($varFile['name']))
					{
						$strVal = $varFile['name'];
					}
				break;
				case 'password':
				case 'hidden':
				case 'text':
				case 'textarea':
				default:
					$strVal = $varSubmitted;
				break;
			}

			return (is_string($strVal) && strlen($strVal)) ? $this->String->decodeEntities($strVal) : $strVal;

		} // if in_array arrFFstorable
		else
		{
			return (is_string($varSubmitted) && strlen($varSubmitted)) ? $this->String->decodeEntities($varSubmitted) : $varSubmitted;
		}

	}

	/**
	 * Prepare database value for Mail / Text
	 * @param mixed database value
	 * @param array form field properties
	 * @param mixed file
	 * @return mixed
	 */
	public function prepareDbValForMail($varValue='', $arrField=false, $varFile=false)
	{

		if (!is_array($arrField))
		{
			return false;
		}

		$strType = $arrField['type'];
		$strVal = '';

		if ( in_array($strType, $this->arrFFstorable) )
		{

			switch ($strType)
			{
				case 'efgLookupCheckbox':
				case 'checkbox':
					$blnEfgStoreValues = ($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrField['name']]['eval']['efgStoreValues'] ? true : false);

					$strVal = '';
					$arrSel = array();

					if (is_string($varValue) && strpos($varValue, '|') !== false)
					{
						$arrSel = explode('|', $varValue);
					}
					else
					{
						$arrSel = deserialize($varValue, true);
					}

					if (count($arrSel))
					{

						// get options labels instead of values for mail / text
						if ($blnEfgStoreValues && is_array($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrField['name']]['options']))
						{
							foreach ($arrSel as $kSel => $vSel)
							{
								foreach ($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrField['name']]['options'] as $strOptsKey => $varOpts)
								{
									if (is_array($varOpts))
									{
										if (isset($varOpts[$vSel]))
										{
											$arrSel[$kSel] = $varOpts[$vSel];
											break;
										}
									}
									else
									{
										if ($strOptsKey == $vSel)
										{
											$arrSel[$kSel] = $varOpts;
											break;
										}
									}
								}
							}
							$strVal = implode(', ', $arrSel);
						}
						else
						{
							$strVal = implode(', ', $arrSel);
						}
					}
				break;
				case 'efgLookupRadio':
				case 'radio':
					$blnEfgStoreValues = ($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrField['name']]['eval']['efgStoreValues'] ? true : false);

					$strVal = (is_array($varValue) ? $varValue[0] : $varValue);

					if ($blnEfgStoreValues && is_array($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrField['name']]['options']))
					{
						foreach ($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrField['name']]['options'] as $strOptsKey => $varOpts)
						{
							if (is_array($varOpts))
							{
								if (isset($varOpts[$strVal]))
								{
									$strVal = $varOpts[$vSel];
									break;
								}
							}
							else
							{
								if ($strOptsKey == $strVal)
								{
									$strVal = $varOpts;
									break;
								}
							}
						}
					}
				break;
				case 'efgLookupSelect':
				case 'conditionalselect':
				case 'countryselect':
				case 'fp_preSelectMenu':
				case 'select':
					$blnEfgStoreValues = ($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrField['name']]['eval']['efgStoreValues'] ? true : false);

					$strVal = '';
					$arrSel = array();

					if (is_string($varValue) && strpos($varValue, '|') !== false)
					{
						$arrSel = explode('|', $varValue);
					}
					else
					{
						$arrSel = deserialize($varValue, true);
					}

					if (count($arrSel))
					{
						// get options labels instead of values for mail / text
						if ($blnEfgStoreValues && is_array($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrField['name']]['options']))
						{
							foreach ( $arrSel as $kSel => $vSel)
							{
								foreach ($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrField['name']]['options'] as $strOptsKey => $varOpts)
								{
									if (is_array($varOpts))
									{
										if (isset($varOpts[$vSel]))
										{
											$arrSel[$kSel] = $varOpts[$vSel];
											break;
										}
									}
									else
									{
										if ($strOptsKey == $vSel)
										{
											$arrSel[$kSel] = $varOpts;
											break;
										}
									}
								}
							}
							$strVal = implode(', ', $arrSel);
						}
						else
						{
							$strVal = implode(', ', $arrSel);
						}
					}
				break;
				case 'efgImageSelect':
					$strVal = '';
					$arrSel = array();

					//$arrSel = deserialize($varValue, true);
					if (is_string($varValue) && strpos($varValue, '|') !== false)
					{
						$arrSel = explode('|', $varValue);
					}
					else
					{
						$arrSel = deserialize($varValue, true);
					}

					if (count($arrSel))
					{
						$strVal = $arrSel;
					}
				break;
				case 'upload':
					$strVal = '';
					if (strlen($varFile['name']))
					{
						$strVal = $varFile['name'];
					}
				break;
				case 'password':
				case 'hidden':
				case 'text':
				case 'textarea':
				default:
					$strVal = $varValue;
				break;
			}
			return (is_string($strVal) && strlen($strVal)) ? $this->String->decodeEntities($strVal) : $strVal;

		} // if in_array arrFFstorable
		else
		{
			return (is_string($varValue) && strlen($varValue)) ? $this->String->decodeEntities($varValue) : $varValue;
		}

	}

	/**
	 * Prepare database value from tl_formdata / tl_formdata_details for widget
	 * @param mixed stored value
	 * @param array form field properties (NOTE: set from dca or from tl_form_field, with differences in the structure)
	 * @return mixed
	 */
	public function prepareDbValForWidget($varValue='', $arrField=false, $varFile=false)
	{
		if (!is_array($arrField))
		{
			return false;
		}

		$strType = $arrField['type'];
		if (TL_MODE == 'BE' && isset($arrField['inputType']))
		{
			$strType = $arrField['inputType'];
		}

		$varVal = $varValue;

		if (in_array($strType, $this->arrFFstorable))
		{

			switch ($strType)
			{
				case 'efgLookupCheckbox':
				case 'checkbox':
					if ($arrField['options'])
					{
						$arrOptions = deserialize($arrField['options']);
					}
					else
					{
						$arrOptions = $this->prepareDcaOptions($arrField);
					}

					if (is_string($varVal))
					{
						$varVal = explode('|', $varVal);
					}

					if (is_array($arrOptions))
					{
						$arrTempOptions = array();
						foreach ($arrOptions as $sK => $mxVal)
						{
							$arrTempOptions[$mxVal['value']] = $mxVal['label'];
						}
					}

					if (is_array($varVal))
					{
						foreach ($varVal as $k => $v) {
							$sNewVal = array_search($v, $arrTempOptions);
							if ($sNewVal)
							{
								$varVal[$k] = $sNewVal;
							}
						}
					}
				break;
				case 'efgLookupRadio':
				case 'radio':
					if ($arrField['options'])
					{
						$arrOptions = deserialize($arrField['options']);
					}
					else
					{
						$arrOptions = $this->prepareDcaOptions($arrField);
					}

					if (is_string($varVal))
					{
						$varVal = explode('|', $varVal);
					}

					if (is_array($arrOptions))
					{
						$arrTempOptions = array();
						foreach ($arrOptions as $sK => $mxVal)
						{
							$arrTempOptions[$mxVal['value']] = $mxVal['label'];
						}
					}

					if (is_array($varVal))
					{
						foreach ($varVal as $k => $v) {
							$sNewVal = array_search($v, $arrTempOptions);
							if ($sNewVal)
							{
								$varVal[$k] = $sNewVal;
							}
						}
					}
				break;
				case 'efgLookupSelect':
				case 'conditionalselect':
				case 'countryselect':
				case 'fp_preSelectMenu':
				case 'select':
					if ($arrField['options'])
					{
						$arrOptions = deserialize($arrField['options']);
					}
					else
					{
						$arrOptions = $this->prepareDcaOptions($arrField);
					}

					if (is_string($varVal))
					{
						$varVal = explode('|', $varVal);
					}

					if (is_array($arrOptions))
					{
						$arrTempOptions = array();
						foreach ($arrOptions as $sK => $mxVal)
						{
							$arrTempOptions[$mxVal['value']] = $mxVal['label'];
						}
					}

					if (is_array($varVal))
					{
						foreach ($varVal as $k => $v) {
							$sNewVal = array_search($v, $arrTempOptions);
							if ($sNewVal)
							{
								$varVal[$k] = $sNewVal;
							}
						}
					}
				break;
				case 'efgImageSelect':
				case 'fileTree':
					if (is_string($varVal) && strpos($varVal, '|') !== false)
					{
						$varVal = explode('|', $varVal);
					}
					elseif (strlen($varVal))
					{
						$varVal = deserialize($varValue);
					}
				break;
				case 'upload':
					$varVal = '';
					if (strlen($varValue))
					{
						if ($arrField['storeFile'] == "1")
						{
							$strVal = $varValue;
						}
						else
						{
							$strVal = $varValue;
						}
						$varVal = $strVal;
					}
				break;

				case 'text':
				case 'calendar':
				case 'xdependentcalendarfields':
					// NOTE: different array structure in Backend (set by dca) and Frontend (set from tl_form_field)
					// .. in Frontend: one-dimensional array like $arrField['rgxp'], $arrField['dateFormat']
					// .. in Backend: multidemsional array like $arrField['eval']['rgxp']
					if ($arrField['rgxp'] && in_array($arrField['rgxp'], array('date', 'datim', 'time')))
					{
						if ($varVal)
						{

							if ($arrField['rgxp'] == 'date')
							{
								// $varVal = date((!empty($arrField['dateFormat']) ? $arrField['dateFormat'] : $GLOBALS['TL_CONFIG']['dateFormat']), $varVal);
								$varVal = $this->parseDate((!empty($arrField['dateFormat']) ? $arrField['dateFormat'] : $GLOBALS['TL_CONFIG']['dateFormat']), $varVal);
							}
							elseif ($arrField['rgxp'] == 'datim')
							{
								// $varVal = date($GLOBALS['TL_CONFIG']['datimFormat'], $varVal);
								$varVal = $this->parseDate((!empty($arrField['dateFormat']) ? $arrField['dateFormat'] : $GLOBALS['TL_CONFIG']['datimFormat']), $varVal);
							}
							elseif ($arrField['rgxp'] == 'time')
							{
								// $varVal = date($GLOBALS['TL_CONFIG']['timeFormat'], $varVal);
								$varVal = $this->parseDate((!empty($arrField['dateFormat']) ? $arrField['dateFormat'] : $GLOBALS['TL_CONFIG']['timeFormat']), $varVal);
							}
						}
					}
					else
					{
						$varVal = $varValue;
					}
				break;

				default:
					$varVal = $varValue;
				break;

			}

			return $varVal;

		} // if in_array arrFFstorable
		else
		{
			return $varVal;
		}

	}

	/**
	 * Prepare dca options array
	 * @param array form field
	 * @return array DCA options
	 */
	public function prepareDcaOptions($arrField=false)
	{

		if (!$arrField)
		{
			return false;
		}

		$strType = $arrField['type'];
		if ($arrField['inputType'] == 'efgLookupSelect')
		{
			$strType = 'efgLookupSelect';
		}
		if ($arrField['inputType'] == 'efgLookupCheckbox')
		{
			$strType = 'efgLookupCheckbox';
		}
		if ($arrField['inputType'] == 'efgLookupRadio')
		{
			$strType = 'efgLookupRadio';
		}

		$arrOptions = array();

		switch ($strType)
		{

			case 'efgLookupCheckbox':
			case 'efgLookupRadio':
			case 'efgLookupSelect':

				// get efgLookupOptions: array('lookup_field' => TABLENAME.FIELDNAME, 'lookup_val_field' => TABLENAME.FIELDNAME, 'lookup_where' => CONDITION, 'lookup_sort' => ORDER BY)
				$arrLookupOptions = deserialize($arrField['efgLookupOptions']);
				$strLookupField = $arrLookupOptions['lookup_field'];
				$strLookupValField = (strlen($arrLookupOptions['lookup_val_field'])) ? $arrLookupOptions['lookup_val_field'] : null;

				$strLookupWhere = $this->String->decodeEntities($arrLookupOptions['lookup_where']);
				if (strlen($strLookupWhere))
				{
					$strLookupWhere = $this->replaceInsertTags($strLookupWhere);
				}

				$arrLookupField = explode('.', $strLookupField);
				$sqlLookupTable = $arrLookupField[0];
				$sqlLookupField = $arrLookupField[1];
				$sqlLookupValField = (strlen($strLookupValField)) ? substr($strLookupValField, strpos($strLookupValField, '.')+1) : null;

				$sqlLookupIdField = 'id';
				$sqlLookupWhere = (strlen($strLookupWhere) ? " WHERE " . $strLookupWhere : "");
				$sqlLookupOrder = $arrLookupField[0] . '.' . $arrLookupField[1];
				if (strlen($arrLookupOptions['lookup_sort']))
				{
					$sqlLookupOrder = $arrLookupOptions['lookup_sort'];
				}

				$arrOptions = array();

				// handle lookup formdata
				if (substr($sqlLookupTable, 0, 3) == 'fd_')
				{
					// load formdata specific dca
					//if (!isset($GLOBALS['TL_DCA']['tl_formdata'])) {
					//$this->loadDataContainer($sqlLookupTable);
					//}

					$strFormKey = $this->arrFormsDcaKey[substr($sqlLookupTable, 3)];

					$sqlLookupTable = 'tl_formdata f, tl_formdata_details fd';
					$sqlLookupIdField = 'f.id';
					$sqlLookupWhere = " WHERE (f.id=fd.pid AND f.form='".$strFormKey."' AND ff_name='".$arrLookupField[1]."')";

					$arrDetailFields = array();
					if (strlen($strLookupWhere) || strlen($arrLookupOptions['lookup_sort']))
					{
						$objDetailFields = $this->Database->prepare("SELECT DISTINCT(ff.`name`) FROM tl_form f, tl_form_field ff WHERE f.storeFormdata=? AND (f.id=ff.pid) AND ff.`type` IN ('".implode("','", $this->arrFFstorable)."')")
														->execute('1');
						if ($objDetailFields->numRows)
						{
							$arrDetailFields = $objDetailFields->fetchEach('name');
						}
					}

					if (strlen($strLookupWhere))
					{
						// special treatment for fields in tl_formdata_details
						$arrPattern = array();
						$arrReplace = array();
						foreach($arrDetailFields as $strDetailField)
						{
							$arrPattern[] = '/\b' . $strDetailField . '\b/i';
							$arrReplace[] = '(SELECT value FROM tl_formdata_details fd WHERE (fd.pid=f.id AND ff_name=\''.$strDetailField.'\'))';
						}
						$sqlLookupWhere .= (strlen($sqlLookupWhere) ? " AND " : " WHERE ") . "(" .preg_replace($arrPattern, $arrReplace, $strLookupWhere) .")";
					}
					$sqlLookupField = '(SELECT value FROM tl_formdata_details fd WHERE (fd.pid=f.id AND ff_name=\''.$arrLookupField[1].'\') ) AS `'. $arrLookupField[1] .'`';

					if (strlen($arrLookupOptions['lookup_sort']))
					{
						// special treatment for fields in tl_formdata_details
						$arrPattern = array();
						$arrReplace = array();
						foreach($arrDetailFields as $strDetailField)
						{
							$arrPattern[] = '/\b' . $strDetailField . '\b/i';
							$arrReplace[] = '(SELECT value FROM tl_formdata_details fd WHERE (fd.pid=f.id AND ff_name=\''.$strDetailField.'\'))';
						}
						$sqlLookupOrder = preg_replace($arrPattern, $arrReplace, str_replace($arrLookupField[0].'.', '', $arrLookupOptions['lookup_sort']));
					}
					else
					{
						$sqlLookupOrder = '(SELECT value FROM tl_formdata_details fd WHERE (fd.pid=f.id AND ff_name=\''.$arrLookupField[1].'\'))';
					}

				} // end lookup formdata

				// handle lookup calendar events
				if ($sqlLookupTable == 'tl_calendar_events')
				{

					//$sqlLookupOrder = 'startTime ASC';
					$sqlLookupOrder = '';

					// handle order (max. 2 fields)
					// .. default startTime ASC
					$arrSortKeys = array(array('field'=>'startTime', 'order'=>'ASC'),array('field'=>'startTime', 'order'=>'ASC'));
					if (strlen($arrLookupOptions['lookup_sort']))
					{
						$sqlLookupOrder = $arrLookupOptions['lookup_sort'];
						$arrSortOn = trimsplit(',', $arrLookupOptions['lookup_sort']);
						$arrSortKeys = array();
						foreach ($arrSortOn as $strSort)
						{
							$arrSortPar = explode(' ', $strSort);
							$arrSortKeys[] = array('field'=>$arrSortPar[0], 'order'=> (strtoupper($arrSortPar[1])=='DESC'? 'DESC' : 'ASC'));
						}
					}

					$sqlLookupWhere = (strlen($strLookupWhere) ? "(" . $strLookupWhere . ")" : "");

					$strReferer = $this->getReferer();

					// if form is placed on an events detail page, automatically add restriction to event(s)
					if (strlen($this->Input->get('events')))
					{
						if (is_numeric($this->Input->get('events')))
						{
							$sqlLookupWhere .= (strlen($sqlLookupWhere) ? " AND " : "") . " tl_calendar_events.id=".intval($this->Input->get('events'))." ";
						}
						elseif (is_string($this->Input->get('events')))
						{
							$sqlLookupWhere .= (strlen($sqlLookupWhere) ? " AND " : "") . " tl_calendar_events.alias='".$this->Input->get('events')."' ";
						}
					}
					// if linked from event reader page
					if (strpos($strReferer, 'event-reader/events/') || strpos($strReferer, '&events=') )
					{
						if (strpos($strReferer, 'events/'))
						{
							$strEvents = substr($strReferer, strrpos($strReferer, '/')+1);
						}
						elseif (strpos($strReferer, '&events='))
						{
							$strEvents = substr($strReferer, strpos($strReferer, '&events=')+strlen('&events='));
						}

						if (is_numeric($strEvents))
						{
							$sqlLookupWhere .= (strlen($sqlLookupWhere) ? " AND " : "") . " tl_calendar_events.id=".intval($strEvents)." ";
						}
						elseif (is_string($strEvents))
						{
							$strEvents = str_replace('.html', '', $strEvents);
							$sqlLookupWhere .= (strlen($sqlLookupWhere) ? " AND " : "") . " tl_calendar_events.alias='".$strEvents."' ";
						}

					}

					$sqlLookup = "SELECT tl_calendar_events.* FROM tl_calendar_events, tl_calendar WHERE (tl_calendar.id=tl_calendar_events.pid) " . (strlen($sqlLookupWhere) ? " AND (" . $sqlLookupWhere . ")" : "") . (strlen($sqlLookupOrder) ? " ORDER BY " . $sqlLookupOrder  : "");

					$objEvents = $this->Database->prepare($sqlLookup)->execute();

					$arrEvents = array();

					if ($objEvents->numRows)
					{
						while ($arrEvent = $objEvents->fetchAssoc())
						{
							$intDate = $arrEvent['startDate'];

							$intStart = time();
							$intEnd = time() + 60*60*24*178 ; // max. 1/2 Jahr

							$span = Calendar::calculateSpan($arrEvent['startTime'], $arrEvent['endTime']);

							$strTime = '';
							$strTime .= date($GLOBALS['TL_CONFIG']['dateFormat'], $arrEvent['startDate']);

							if ($arrEvent['addTime'])
							{
								if ($span > 0)
								{
									$strTime .= ' ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrEvent['startTime']) . ' - ' . date($GLOBALS['TL_CONFIG']['dateFormat'], $arrEvent['endTime']) . ' ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrEvent['endTime']);
								}
								elseif ($arrEvent['startTime'] == $arrEvent['endTime'])
								{
									$strTime .= ' ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrEvent['startTime']);
								}
								else
								{
									$strTime .= ' ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrEvent['startTime']) . ' - ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrEvent['endTime']);
								}
							}
							else
							{
								if ($span > 1)
								{
									$strTime .= ' - ' . date($GLOBALS['TL_CONFIG']['dateFormat'], $arrEvent['endTime']);
								}
							}

							if ($sqlLookupValField)
							{
								//$arrEvents[$arrEvent[$sqlLookupValField].'@'.$strTime] = $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : '');
								if (count($arrSortKeys)>=2)
								{
									$arrEvents[$arrEvent[$arrSortKeys[0]['field']]][$arrEvent[$arrSortKeys[1]['field']]][] = array('value' => $arrEvent[$sqlLookupValField].'@'.$strTime, 'label' => $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : ''));
								}
								else
								{
									$arrEvents[$arrEvent[$arrSortKeys[0]['field']]][$arrEvent['startTime']][] = array('value' => $arrEvent[$sqlLookupValField].'@'.$strTime, 'label' => $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : ''));
								}
							}
							else
							{
								//$arrEvents[$arrEvent['id'].'@'.$strTime] = $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : '');
								if (count($arrSortKeys)>=2)
								{
									$arrEvents[$arrEvent[$arrSortKeys[0]['field']]][$arrEvent[$arrSortKeys[1]['field']]][] = array('value' => $arrEvent[$sqlLookupValField].'@'.$strTime, 'label' => $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : ''));
								}
								else
								{
									$arrEvents[$arrEvent[$arrSortKeys[0]['field']]][$arrEvent['startTime']][] = array('value' => $arrEvent[$sqlLookupValField].'@'.$strTime, 'label' => $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : ''));
								}
							}

							// Recurring events
							if ($arrEvent['recurring'])
							{
								$count = 0;
								$arrRepeat = deserialize($arrEvent['repeatEach']);
								$blnSummer = date('I', $arrEvent['startTime']);

								$intEnd = time() + 60*60*24*178; // max. 1/2 Jahr

								while ($arrEvent['endTime'] < $intEnd)
								{

									if ($arrEvent['recurrences'] > 0 && $count++ >= $arrEvent['recurrences'])
									{
										break;
									}

									$arg = $arrRepeat['value'];
									$unit = $arrRepeat['unit'];

									if ($arg == 1)
									{
										$unit = substr($unit, 0, -1);
									}

									$strtotime = '+ ' . $arg . ' ' . $unit;

									$arrEvent['startTime'] = strtotime($strtotime, $arrEvent['startTime']);
									$arrEvent['endTime'] = strtotime($strtotime, $arrEvent['endTime']);

									if ($arrEvent['startTime'] >= $intStart || $arrEvent['endTime'] <= $intEnd)
									{

										$strTime = '';
										$strTime .= date($GLOBALS['TL_CONFIG']['dateFormat'], $arrEvent['startTime']);

										if ($arrEvent['addTime'])
										{

											if ($span > 0)
											{
												$strTime .= ' ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrEvent['startTime']) . ' - ' . date($GLOBALS['TL_CONFIG']['dateFormat'], $arrEvent['endTime']) . ' ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrEvent['endTime']);
											}
											elseif ($arrEvent['startTime'] == $arrEvent['endTime'])
											{
												$strTime .= ' ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrEvent['startTime']);
											}
											else
											{
												$strTime .= ' ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrEvent['startTime']) . ' - ' . date($GLOBALS['TL_CONFIG']['timeFormat'], $arrEvent['endTime']);
											}
										}

										if ($sqlLookupValField)
										{
											//$arrEvents[$arrEvent[$sqlLookupValField].'@'.$strTime] = $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : '');
											if (count($arrSortKeys)>=2)
											{
												$arrEvents[$arrEvent[$arrSortKeys[0]['field']]][$arrEvent[$arrSortKeys[1]['field']]][] = array('value' => $arrEvent[$sqlLookupValField].'@'.$strTime, 'label' => $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : ''));
											}
											else
											{
												$arrEvents[$arrEvent[$arrSortKeys[0]['field']]][$arrEvent['startTime']][] = array('value' => $arrEvent[$sqlLookupValField].'@'.$strTime, 'label' => $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : ''));
											}
										}
										else
										{
											//$arrEvents[$arrEvent['id'].'@'.$strTime] = $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : '');
											if (count($arrSortKeys)>=2)
											{
												$arrEvents[$arrEvent[$arrSortKeys[0]['field']]][$arrEvent[$arrSortKeys[1]['field']]][] = array('value' => $arrEvent[$sqlLookupValField].'@'.$strTime, 'label' => $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : ''));
											}
											else
											{
												$arrEvents[$arrEvent[$arrSortKeys[0]['field']]][$arrEvent['startTime']][] = array('value' => $arrEvent[$sqlLookupValField].'@'.$strTime, 'label' => $arrEvent[$arrLookupField[1]] . (strlen($strTime) ? ', ' . $strTime : ''));
											}
										}

									}
								} // while endTime < intEnd

							} // if recurring

						} // while

						// sort events
						foreach ($arrEvents as $k => $arr)
						{
							if ($arrSortKeys[1]['order']=='DESC')
							{
								krsort($arrEvents[$k]);
							}
							else
							{
								ksort($arrEvents[$k]);
							}
						}
						if ($arrSortKeys[0]['order']=='DESC')
						{
							krsort($arrEvents);
						}
						else
						{
							ksort($arrEvents);
						}

						// set options
						foreach ($arrEvents as $k1 => $arr1)
						{
							foreach ($arr1 as $k2 => $arr2)
							{
								foreach ($arr2 as $k3 => $arr3)
								{
									$arrOptions[] = $arr3;
								}
							}
						}

						if ( count($arrOptions) == 1 )
						{
							$blnDoNotAddEmptyOption = true;
						}

						// include blank option to input type select
						if ( $strType == 'efgLookupSelect' )
						{
							if ( !$blnDoNotAddEmptyOption )
							{
								array_unshift($arrOptions, array('value'=>'', 'label'=>'-'));
							}
						}

					} // if objEvents->numRows

					return $arrOptions;

				} // end handle calendar events

				else // normal lookup table or formdata lookup table
				{
					$sqlLookup = "SELECT " . $sqlLookupIdField . (strlen($sqlLookupField) ? ', ' : '') . $sqlLookupField . ( strlen($sqlLookupValField) ? ', ' : '') . $sqlLookupValField . " FROM " . $sqlLookupTable . $sqlLookupWhere . (strlen($sqlLookupOrder)>1 ? " ORDER BY " . $sqlLookupOrder : "");

					if (strlen($sqlLookupTable))
					{
						$objOptions = $this->Database->prepare($sqlLookup)->execute();
					}
					if ($objOptions->numRows)
					{
						$arrOptions = array();
						while ($arrOpt = $objOptions->fetchAssoc())
						{
							if ($sqlLookupValField)
							{
								$arrOptions[$arrOpt[$sqlLookupValField]] = $arrOpt[$arrLookupField[1]];
							}
							else
							{
								$arrOptions[$arrOpt['id']] = $arrOpt[$arrLookupField[1]];
							}
						}
					}

				} // end normal lookup table


				$arrTempOptions = array();
				// include blank option to input type select
				if ( $strType == 'efgLookupSelect' )
				{
					if ( !$blnDoNotAddEmptyOption )
					{
						$arrTempOptions[] = array('value'=>'', 'label'=>'-');
					}
				}

				foreach ($arrOptions as $sK => $sV)
				{
					$strKey = (string) $sK;
					$arrTempOptions[] = array('value'=>$strKey, 'label'=>$sV);
				}
				$arrOptions = $arrTempOptions;

			break; // strType efgLookupCheckbox, efgLookupRadio or efgLookupSelect
			// countryselectmenu
			case 'countryselect':
				$arrCountries = $this->getCountries();
				$arrTempOptions = array();
				foreach($arrCountries as $strKey => $strVal)
				{
					$arrTempOptions[] = array('value'=>$strKey, 'label'=>$strVal);
				}
				$arrOptions = $arrTempOptions;
			break;

			default:
				if ($arrField['options'])
				{
					$arrOptions = deserialize($arrField['options']);
				}
				else
				{
					$strClass = $GLOBALS['TL_FFL'][$arrField['type']];
					if ($this->classFileExists($strClass))
					{
						$objWidget = new $strClass($arrField);
						if ($objWidget instanceof FormSelectMenu || $objWidget instanceof FormCheckbox || $objWidget instanceof FormRadioButton)
						{

							// HOOK: load form field callback
							if (isset($GLOBALS['TL_HOOKS']['loadFormField']) && is_array($GLOBALS['TL_HOOKS']['loadFormField']))
							{
								foreach ($GLOBALS['TL_HOOKS']['loadFormField'] as $callback)
								{
									$this->import($callback[0]);
									$objWidget = $this->$callback[0]->$callback[1]($objWidget, $arrField['pid'], array());
								}
							}

							$arrOptions = $objWidget->options;
						}
					}
				}
			break;
		} // end switch $arrField['type']

		return $arrOptions;

	}

	/**
	 * Parse Insert tag params
	 * @param string Insert tag
	 * @return mixed
	 */
	public function parseInsertTagParams($strTag='')
	{
		if ($strTag == '')
		{
			return null;
		}
		if (strpos($strTag, '?') == false)
		{
			return null;
		}
		$strTag = str_replace(array('{{', '}}', '__BRCL__', '__BRCR__'), array('', ''), $strTag);

		$arrTag = explode('?', $strTag);
		$strKey = $arrTag[0];
		if (isset($arrTag[1]) && strlen($arrTag[1]))
		{
			$arrTag[1] = str_replace('[&]', '__AMP__', $arrTag[1]);
			$strParams = $this->String->decodeEntities($arrTag[1]);
			$arrParams = preg_split('/&/sim', $strParams);

			$arrReturn = array();
			foreach ($arrParams as $strParam)
			{
				list($key, $value) = explode('=', $strParam);
				$arrReturn[$key] = str_replace('__AMP__', '&', $value);
			}
		}

		return $arrReturn;

	}

	/**
	 * Replace 'condition tags': {if ...}, {elseif ...}, {else} and  {endif}
	 * @param string String to parse
	 * @return boolean
	 */
	public function replaceConditionTags(&$strBuffer)
	{
		if (!strlen($strBuffer))
		{
			return false;
		}

		$blnEval = false;
		$strReturn = '';

		$arrTags = preg_split("/(\{[^}]+\})/sim", $strBuffer, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);

		if (is_array($arrTags) && count($arrTags)>0)
		{
			// Replace tags
			foreach ($arrTags as $strTag)
			{
				if (strncmp($strTag, '{if', 3) === 0)
				{
					$strReturn .= preg_replace('/\{if (.*)\}/i', '<?php if ($1): ?>', $strTag);
					$blnEval = true;
				}
				elseif (strncmp($strTag, '{elseif', 7) === 0)
				{
					$strReturn .= preg_replace('/\{elseif (.*)\}/i', '<?php elseif ($1): ?>', $strTag);
					$blnEval = true;
				}
				elseif (strncmp($strTag, '{else', 5) === 0)
				{
					$strReturn .= '<?php else: ?>';
					$blnEval = true;
				}
				elseif (strncmp($strTag, '{endif', 6) === 0)
				{
					$strReturn .= '<?php endif; ?>';
					$blnEval = true;
				}
				else
				{
					$strReturn .= $strTag;
				}
			}

  			$strBuffer = $strReturn;
		}

		return $blnEval;
	}

	public function evalConditionTags($strBuffer, $arrSubmitted = null, $arrFiles = null, $arrForm = null)
	{
		if (!strlen($strBuffer))
		{
			return;
		}

		$strReturn = str_replace('?><br />', '?>', $strBuffer);

		// Eval the code
		ob_start();
		$blnEval = eval("?>" . $strReturn);
		$strReturn = ob_get_contents();
		ob_end_clean();

		// Throw an exception if there is an eval() error
		if ($blnEval === false)
		{
			throw new Exception("Error eval() in FormData::evalConditionTags ($strReturn)");
		}

		// Return the evaled code
		return $strReturn;

	}

}