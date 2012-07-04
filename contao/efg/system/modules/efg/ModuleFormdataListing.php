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
 * Class ModuleFormdataListing
 *
 * based on ModuleListing by Leo Feyer
 *
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */
class ModuleFormdataListing extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'list_default';

	protected $strTable = 'tl_formdata';

	/**
	 * Related form, like fd_frm_contact
	 * @param string
	 */
	protected $strFormKey;

	/**
	 * Related dca file, like fd_frm_contact
	 * @param string
	 */
	protected $strDcaKey;

	/**
	 * Related form filter key, name of field in table tl_formdata hlding form-identifier
	 * @param string
	 */
	protected $strFormFilterKey;

	/**
	 * Related form filter value, title of related form like 'Contact Form"
	 * @param string
	 */
	protected $strFormFilterValue;

	/**
	 * sql condition for form to filter
	 * @param string
	 */
	protected $sqlFormFilter;

	/**
	 * Items in tl_form, all forms marked to store data in tl_formdata
	 * @param array
	 */
	protected $arrStoreForms;

	/**
	 * Base fields in table tl_formdata
	 * @param mixed
	 */
	protected $arrBaseFields = null;

	/**
	 * Base fields for owner restriction (member,user,..)
	 * @param mixed
	 */
	protected $arrOwnerFields = null;

	/**
	 * Detail fields in table tl_formdata_details
	 * @param mixed
	 */
	protected $arrDetailFields = null;

	/**
	 * Form fields of forms storing data
	 * @param mixed
	 */
	protected $arrFF = null;

	/**
	 * Names of form fields
	 * @param mixed
	 */
	protected $arrFFNames = null;
	protected $arrAllowedOwnerIds = null;
	protected $arrAllowedEditOwnerIds = null;
	protected $arrAllowedDeleteOwnerIds = null;
	protected $arrAllowedExportOwnerIds = null;

	protected $intRecordId = null;
	protected $strDetailKey = 'details';

	protected $arrDetailKeys = array();

	// convert UTF8 to cp1251 on CSV-/XLS-Export
	protected $blnExportUTF8Decode = true;

	/**
	 * Fields to ignore on export
	 */
	protected $arrExportIgnoreFields = null;

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{

		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### LISTING FORMDATA ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		if (strlen($this->efg_DetailsKey))
		{
			$this->strDetailKey = $this->efg_DetailsKey;
		}

		$this->blnExportUTF8Decode = true;
		if (isset($GLOBALS['EFG']['exportUTF8Decode']) && $GLOBALS['EFG']['exportUTF8Decode'] == false)
		{
			$this->blnExportUTF8Decode = false;
		}

		if (isset($GLOBALS['EFG']['exportIgnoreFields']))
		{
			if (is_string($GLOBALS['EFG']['exportIgnoreFields']) && strlen($GLOBALS['EFG']['exportIgnoreFields']))
			{
				$this->arrExportIgnoreFields = trimsplit(',', $GLOBALS['EFG']['exportIgnoreFields']);
			}
		}

		// remove download and export from referer
		$session = $this->Session->getData();
		$session['referer']['last'] = preg_replace('@(\?|&amp;|&)download=.*?(&amp;|&|$)@si', '', $session['referer']['last']);
		$session['referer']['current'] = preg_replace('@(\?|&amp;|&)download=.*?(&amp;|&|$)@si', '', $session['referer']['current']);
		$session['referer']['last'] = preg_replace('@(\?|&amp;|&)act=export(&amp;|&|$)@si', '', $session['referer']['last']);
		$session['referer']['current'] = preg_replace('@(\?|&amp;|&)act=export(&amp;|&|$)@si', '', $session['referer']['current']);
		$this->Session->setData($session);

		if ($this->Input->get($this->strDetailKey) && !strlen($this->list_info) && !strlen($this->Input->get('act')) )
		{
			return '';
		}

		// Fallback template
		if (!strlen($this->list_layout))
		{
			$this->list_layout = 'list_default';
		}

		$this->arrOwnerFields = array('fd_member', 'fd_user','fd_member_group', 'fd_user_group');

		$this->getMembers();
		$this->getUsers();
		$this->getMemberGroups();
		$this->getUserGroups();

		$this->import('FrontendUser', 'Member');
		$this->import('FormData');

		// check list access
		if (strlen($this->efg_list_access))
		{
			$arrAllowedOwnerIds = array();
			switch ($this->efg_list_access)
			{
				case 'member': // display own records only
					if (intval($this->Member->id)>0)
					{
						$arrAllowedOwnerIds[] = intval($this->Member->id);
					}
					else
					{
						$arrAllowedOwnerIds[] = -1;
					}
					break;

				case 'groupmembers': // display records of group members
					if (intval($this->Member->id)>0)
					{
						$arrAllowedOwnerIds[] = intval($this->Member->id);
						$arrGroups = $this->Member->groups;
						$arrGroupsWhere = array();
						if(count($arrGroups)>0)
						{
							foreach($arrGroups as $group)
							{
								$arrGroupsWhere[] = ' groups LIKE \'%"' . intval($group) . '"%\'';
							}
							$objGroupMembers = $this->Database->prepare("SELECT DISTINCT id FROM tl_member WHERE " . implode(" OR ", $arrGroupsWhere))
													->execute();
							$arrGroupMembers = $objGroupMembers->fetchEach('id');
							if (count($arrGroupMembers)>0)
							{
								$arrAllowedOwnerIds = array_merge($arrAllowedOwnerIds, $arrGroupMembers);
							}
						}
					}
					else
					{
						$arrAllowedOwnerIds[] = -1;
					}
					break;

				case 'public':
				default:
					break;
			}
			if ($this->efg_list_access != 'public')
			{
				for ($n=0; $n<count($arrAllowedOwnerIds); $n++)
				{
					$arrAllowedOwnerIds[$n] = intval($arrAllowedOwnerIds[$n]);
				}
				$this->arrAllowedOwnerIds = array_unique($arrAllowedOwnerIds);
			}
			unset($arrAllowedOwnerIds);
		}

		// check edit access
		if (strlen($this->efg_fe_edit_access))
		{
			$arrAllowedOwnerIds = array();
			switch($this->efg_fe_edit_access)
			{
				case 'member': // edit own records only
					if (intval($this->Member->id)>0)
					{
						$arrAllowedOwnerIds[] = intval($this->Member->id);
					}
					else
					{
						$arrAllowedOwnerIds[] = -1;
					}
					break;

				case 'groupmembers': // edit records of group members
					if (intval($this->Member->id)>0)
					{
						$arrAllowedOwnerIds[] = intval($this->Member->id);
						$arrGroups = $this->Member->groups;
						$arrGroupsWhere = array();
						if(count($arrGroups)>0)
						{
							foreach($arrGroups as $group)
							{
								$arrGroupsWhere[] = ' groups LIKE \'%"' . intval($group) . '"%\'';
							}
							$objGroupMembers = $this->Database->prepare("SELECT DISTINCT id FROM tl_member WHERE " . implode(" OR ", $arrGroupsWhere))
													->execute();
							$arrGroupMembers = $objGroupMembers->fetchEach('id');
							if (count($arrGroupMembers)>0)
							{
								$arrAllowedOwnerIds = array_merge($arrAllowedOwnerIds, $arrGroupMembers);
							}
						}
					}
					else
					{
						$arrAllowedOwnerIds[] = -1;
					}
					break;

				case 'public':
				default:
					break;
			}
			if ($this->efg_fe_edit_access != 'public')
			{
				for ($n=0; $n<count($arrAllowedOwnerIds); $n++)
				{
					$arrAllowedOwnerIds[$n] = intval($arrAllowedOwnerIds[$n]);
				}
				$this->arrAllowedEditOwnerIds = array_unique($arrAllowedOwnerIds);
			}
			unset($arrAllowedOwnerIds);
		}

		// check delete access
		if (strlen($this->efg_fe_delete_access))
		{
			$arrAllowedOwnerIds = array();
			switch($this->efg_fe_delete_access)
			{
				case 'member': // delete own records only
					if (intval($this->Member->id)>0)
					{
						$arrAllowedOwnerIds[] = intval($this->Member->id);
					}
					else
					{
						$arrAllowedOwnerIds[] = -1;
					}
					break;

				case 'groupmembers': // delete records of group members
					if (intval($this->Member->id)>0)
					{
						$arrAllowedOwnerIds[] = intval($this->Member->id);
						$arrGroups = $this->Member->groups;
						$arrGroupsWhere = array();
						if(count($arrGroups)>0)
						{
							foreach($arrGroups as $group)
							{
								$arrGroupsWhere[] = ' groups LIKE \'%"' . intval($group) . '"%\'';
							}
							$objGroupMembers = $this->Database->prepare("SELECT DISTINCT id FROM tl_member WHERE " . implode(" OR ", $arrGroupsWhere))
													->execute();
							$arrGroupMembers = $objGroupMembers->fetchEach('id');
							if (count($arrGroupMembers)>0)
							{
								$arrAllowedOwnerIds = array_merge($arrAllowedOwnerIds, $arrGroupMembers);
							}
						}
					}
					else
					{
						$arrAllowedOwnerIds[] = -1;
					}
					break;
				case 'public':
				default:
					break;
			}
			if ($this->efg_fe_delete_access != 'public')
			{
				for ($n=0; $n<count($arrAllowedOwnerIds); $n++)
				{
					$arrAllowedOwnerIds[$n] = intval($arrAllowedOwnerIds[$n]);
				}
				$this->arrAllowedDeleteOwnerIds = array_unique($arrAllowedOwnerIds);
			}
			unset($arrAllowedOwnerIds);
		}

		// check export access
		if (strlen($this->efg_fe_export_access))
		{
			$arrAllowedOwnerIds = array();
			switch($this->efg_fe_export_access)
			{
				case 'member': // export own records only
					if (intval($this->Member->id)>0)
					{
						$arrAllowedOwnerIds[] = intval($this->Member->id);
					}
					else
					{
						$arrAllowedOwnerIds[] = -1;
					}
					break;

				case 'groupmembers': // export records of group members
					if (intval($this->Member->id)>0)
					{
						$arrAllowedOwnerIds[] = intval($this->Member->id);
						$arrGroups = $this->Member->groups;
						$arrGroupsWhere = array();
						if(count($arrGroups)>0)
						{
							foreach($arrGroups as $group)
							{
								$arrGroupsWhere[] = ' groups LIKE \'%"' . intval($group) . '"%\'';
							}
							$objGroupMembers = $this->Database->prepare("SELECT DISTINCT id FROM tl_member WHERE " . implode(" OR ", $arrGroupsWhere))
													->execute();
							$arrGroupMembers = $objGroupMembers->fetchEach('id');
							if (count($arrGroupMembers)>0)
							{
								$arrAllowedOwnerIds = array_merge($arrAllowedOwnerIds, $arrGroupMembers);
							}
						}
					}
					else
					{
						$arrAllowedOwnerIds[] = -1;
					}
					break;
				case 'public':
				default:
					break;
			}
			if ($this->efg_fe_export_access != 'public')
			{
				for ($n=0; $n<count($arrAllowedOwnerIds); $n++)
				{
					$arrAllowedOwnerIds[$n] = intval($arrAllowedOwnerIds[$n]);
				}
				$this->arrAllowedExportOwnerIds = array_unique($arrAllowedOwnerIds);
			}
			unset($arrAllowedOwnerIds);
		}


		// file download
		if (strlen($this->Input->get('download')))
		{
			$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

			$arrParams = explode('.' , $this->Input->get('download'));
			$intFdId = $arrParams[0];
			unset($arrParams[0]);

			$strField = implode('.', $arrParams);
			$objDownload = $this->Database->prepare("SELECT fd.fd_member, fd.fd_user, fdd.value FROM tl_formdata fd, tl_formdata_details fdd WHERE (fd.id=fdd.pid) AND fd.id=? AND fdd.ff_name=?")
										->execute($intFdId,  $strField);
			if ($objDownload->numRows)
			{
				$arrDownload = $objDownload->fetchAssoc();

				if ($this->arrAllowedOwnerIds && !in_array($arrDownload['fd_member'], $this->arrAllowedOwnerIds))
				{
					return;
				}
				else
				{
					// Send file to the browser
					if (is_file(TL_ROOT . '/' . $arrDownload['value']))
					{
						$objFile = new File($arrDownload['value']);
						if (in_array($objFile->extension, $allowedDownload) )
						{
							$this->sendFileToBrowser($arrDownload['value']);
							return;
						}
					}
				}
			}
		}

		$this->strTemplate = $this->list_layout;
		return parent::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile()
	{
		global $objPage;

		$blnExport = false;
		$blnCustomXlsExport = false;
		$strExportMode = 'csv';

		$strSearchFormType = 'dropdown';
		if ($this->efg_list_searchtype)
		{
			$strSearchFormType = $this->efg_list_searchtype;
		}

		$this->import('String');
		$this->import('FrontendUser', 'Member');

		$this->list_table = 'tl_formdata';

		$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		// get names of detail fields
		$objFF = $this->Database->prepare("SELECT ff.name, ff.label, ff.type, ff.rgxp FROM tl_form_field ff, tl_form f WHERE (ff.pid=f.id) AND ff.name != '' AND f.storeFormdata=?")
											->execute("1");
		if ( $objFF->numRows)
		{
			$this->arrFF = array();
			$this->arrFFNames = array();

			while ($objFF->next())
			{
				$arrField = $objFF->row();
				$this->arrFF[$arrField['name']] = $arrField;
				$this->arrFFNames[] = $arrField['name'];
			}
		}

		$this->strFormKey = '';
		$this->strDcaKey = 'tl_formdata';

		$this->strFormFilterKey = '';
		$this->strFormFilterValue = '';

		if (strlen($this->list_formdata))
		{
			if ($this->list_formdata == 'feedback' || $this->list_formdata == 'fd_feedback' || $this->list_formdata == 'tl_formdata')
			{
				$this->strFormKey = '';
				$this->strDcaKey = 'fd_feedback';
				$this->strFormFilterKey = '';
				$this->strFormFilterValue = '';
			}
			else
			{
				$this->strFormKey = (substr($this->list_formdata, 0 , 3) == 'fd_' ? $this->list_formdata : 'fd_' . $this->list_formdata);
				$this->strDcaKey = (substr($this->list_formdata, 0, 3) == 'fd_' ? $this->list_formdata : 'fd_'.$this->list_formdata);
				$this->strFormFilterKey = 'form';
				$this->strFormFilterValue = $this->arrStoreForms[str_replace('fd_', '', $this->strFormKey)]['title'];
				$this->sqlFormFilter = ' AND ' . $this->strFormFilterKey . '=\'' . $this->strFormFilterValue . '\' ';
			}
		}

		// load dca-config into $GLOBALS['TL_DCA']['tl_formdata']
		$this->loadDataContainer($this->strDcaKey);
		$this->loadLanguageFile('tl_formdata');

		// Export
		if ($this->Input->get('act') == 'export')
		{
			$blnExport = true;
			$strExportMode = 'csv';
		}
		elseif ($this->Input->get('act') == 'exportxls')
		{
			$blnExport = true;
			$strExportMode = 'xls';
		}

		if ($blnExport)
		{
			$blnCustomXlsExport = false;
			$arrHookData = array();
			$arrHookDataColumns = array();

			$useFormValues = $this->FormData->arrStoreForms[substr($this->strFormKey, 3)]['useFormValues'];
			$useFieldNames = $this->FormData->arrStoreForms[substr($this->strFormKey, 3)]['useFieldNames'];

			$this->blnExportUTF8Decode = true;
			if (isset($GLOBALS['EFG']['exportUTF8Decode']) && $GLOBALS['EFG']['exportUTF8Decode'] == false)
			{
				$this->blnExportUTF8Decode = false;
			}

			if ($strExportMode=='xls')
			{
				// check for HOOK efgExportXls
				if (array_key_exists('efgExportXls', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['efgExportXls']))
				{
					$blnCustomXlsExport = true;
				}
				else
				{
					include(TL_ROOT.'/plugins/xls_export/xls_export.php');
				}
			}
		}
		else
		{
			$this->Template->textlink_details = $GLOBALS['TL_LANG']['tl_formdata']['fe_link_details'];
			$this->Template->textlink_edit = $GLOBALS['TL_LANG']['tl_formdata']['fe_link_edit'];
			$this->Template->textlink_delete = $GLOBALS['TL_LANG']['tl_formdata']['fe_link_delete'];
			$this->Template->text_confirmDelete = $GLOBALS['TL_LANG']['tl_formdata']['fe_deleteConfirm'];
			$this->Template->textlink_export = $GLOBALS['TL_LANG']['tl_formdata']['fe_link_export'];
			$this->Template->iconFolder = (strlen($this->efg_iconfolder) ? $this->efg_iconfolder : 'system/modules/efg/html');

			$this->Template->details = strlen($this->list_info) ? true : false;

			$this->Template->editable = false;
			if (strlen($this->efg_fe_edit_access))
			{
				if ($this->efg_fe_edit_access == 'public')
				{
					$this->Template->editable = true;
				}
				elseif ( ($this->efg_fe_edit_access == 'member' || $this->efg_fe_edit_access == 'groupmembers') && intval($this->Member->id)>0 )
				{
					$this->Template->editable = true;
				}
			}

			$this->Template->deletable = false;
			if (strlen($this->efg_fe_delete_access))
			{
				if ($this->efg_fe_delete_access == 'public')
				{
					$this->Template->deletable = true;
				}
				elseif ( ($this->efg_fe_delete_access == 'member' || $this->efg_fe_delete_access == 'groupmembers') && intval($this->Member->id)>0 )
				{
					$this->Template->deletable = true;
				}
			}

			$this->Template->exportable = false;
			if (strlen($this->efg_fe_export_access))
			{
				if ($this->efg_fe_export_access == 'public')
				{
					$this->Template->exportable = true;
				}
				elseif ( ($this->efg_fe_export_access == 'member' || $this->efg_fe_export_access == 'groupmembers') && intval($this->Member->id)>0 )
				{
					$this->Template->exportable = true;
				}
			}

		}

		$this->arrBaseFields = $GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['baseFields'];
		$this->arrDetailFields = $GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['detailFields'];

		if (strlen($GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['formFilterKey']))
		{
			$this->strFormFilterKey = $GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['formFilterKey'];
		}
		if (strlen($GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['formFilterValue']))
		{
			$this->strFormFilterValue = $GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['formFilterValue'];
		}

		// List, edit or delete a single record
		if (strlen($this->Input->get($this->strDetailKey)))
		{

			// check details record
			$strQuery = "SELECT id FROM tl_formdata f";
			$strWhere = " WHERE (id=? OR alias=?)";
			if ($this->list_where)
			{
				$arrListWhere = array();
				$arrListConds = preg_split('/(\sAND\s|\sOR\s)/si', $this->list_where, -1, PREG_SPLIT_DELIM_CAPTURE);

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
						if (in_array($strCondField, $this->arrDetailFields))
						{
							$arrListWhere[] = '(SELECT value FROM tl_formdata_details WHERE ff_name="'.$strCondField.'" AND pid=f.id ) ' . implode('', $arrListCond);
						}
						if (in_array($strCondField, $this->arrBaseFields))
						{
							$arrListWhere[] = $strCondField . implode('', $arrListCond);
						}
					}
				}
				$strListWhere = (count($arrListWhere)>0) ? '(' . implode('', $arrListWhere) .')' : '';
				$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . $strListWhere;
			}
			if (strlen($this->strFormKey))
			{
				$strWhere .= (strlen($strWhere) ? " AND " : " WHERE "). $this->strFormFilterKey . "='" . $this->strFormFilterValue . "'";
			}

			// replace insert tags in where, e.g. {{user::id}}
			$strWhere = $this->replaceWhereTags($strWhere);
			$strWhere = $this->replaceInsertTags($strWhere);
			$strQuery .=  $strWhere;

			$objCheck = $this->Database->prepare($strQuery)
							->execute($this->Input->get($this->strDetailKey), $this->Input->get($this->strDetailKey));

			if ($objCheck->numRows == 1)
			{
				$this->intRecordId = intval($objCheck->id);
			}
			else
			{
				$this->log('Could not identify record by ID "'.$this->Input->get($this->strDetailKey).'"', 'ModuleFormdataListing compile()', TL_GENERAL);

				$strUrl = preg_replace('/\?.*$/', '', urldecode($this->Environment->request));
				$strUrlParams = '';
				$strUrlSuffix = $GLOBALS['TL_CONFIG']['urlSuffix'];

				$blnQuery = false;
				foreach (preg_split('/&(amp;)?/', urldecode($_SERVER['QUERY_STRING'])) as $fragment)
				{
					if (strlen($fragment))
					{
						if (strncasecmp($fragment, 'file', 5) !== 0 && strncasecmp($fragment, $this->strDetailKey, strlen($this->strDetailKey)) !== 0 && strncasecmp($fragment, 'order_by', 8) !== 0 && strncasecmp($fragment, 'sort', 4) !== 0 && strncasecmp($fragment, 'page', 4) !== 0)
						{
							$strUrlParams .= (!$blnQuery ? '' : '&amp;') . $fragment;
							$blnQuery = true;
						}
					}
				}

				$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i'), array('',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
				$this->redirect($strRed);
			}

			if ($this->Input->get('act') == 'edit' && intval($this->intRecordId) > 0 )
			{
				$this->editSingleRecord();
				return;
			}

			elseif ($this->Input->get('act') == 'delete' && intval($this->intRecordId) > 0 )
			{
				$this->deleteSingleRecord();
				return;
			}

			elseif ($this->Input->get('act') == 'export' && intval($this->intRecordId) > 0 )
			{
				$this->exportSingleRecord($strExportMode);
				return;
			}

			elseif (!is_null($this->intRecordId) && intval($this->intRecordId) > 0 )
			{
				$this->listSingleRecord();
				return;
			}
		}

		$page = $this->Input->get('page') ? $this->Input->get('page') : 1;
		$per_page = $this->Input->get('per_page') ? $this->Input->get('per_page') : $this->perPage;

		/**
		 * Add search query
		 */
		$strWhere = '';
		$varKeyword = '';
		$strOptions = '';

		if (count($this->arrAllowedOwnerIds)>0)
		{
			$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . "fd_member IN (" . implode(',', $this->arrAllowedOwnerIds).")";
		}

		if (!$blnExport)
		{
			$this->Template->searchable = false;
		}

		$arrSearchFields = trimsplit(',', $this->list_search);

		if ($strSearchFormType == 'none')
		{
			unset($arrSearchFields);
		}

		if (is_array($arrSearchFields) && count($arrSearchFields))
		{
			if (!$blnExport)
			{
				$this->Template->searchable = true;
				$this->Template->search_form_type = $strSearchFormType;
			}

			switch ($strSearchFormType)
			{

				case 'singlefield':
					if (strlen($this->Input->get('search')) && strlen($this->Input->get('for')))
					{
						$varKeyword = '%' . $this->Input->get('for') . '%';

						$arrConds = array();
						foreach (trimsplit(',', urldecode($this->Input->get('search'))) as $field)
						{
							if (in_array($field, $this->arrOwnerFields))
							{
								if ($field == "fd_member")
								{
									$prop = 'arrMembers';
								}
								elseif ($field == "fd_member_group")
								{
									$prop = 'arrMemberGroups';
								}
								elseif ($field == "fd_user")
								{
									$prop = 'arrUsers';
								}
								elseif ($field == "fd_user_group")
								{
									$prop = 'arrUserGroups';
								}

								$arrMatches = $this->array_filter_like($this->$prop, $this->Input->get('for'));
								if (count($arrMatches))
								{
									$arrConds[] = $field ." IN(".implode(",", array_keys($arrMatches)).")";
								}
							}
							elseif (in_array($field, $this->arrBaseFields))
							{
								$arrConds[] = $field . " LIKE ?";
							}
							else
							{
								$arrConds[] = '(SELECT value FROM tl_formdata_details WHERE ff_name="' .$field. '" AND pid=f.id ) LIKE ?';
							}
						}

						if (count($arrConds)>0)
						{
							$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . '(' . implode(" OR ", $arrConds) .')';
							$varKeyword = array_fill(0, count($arrConds), '%' . $this->Input->get('for') . '%');
						}
					}

					$strOptions = implode(',', $arrSearchFields);

					break;

				case 'multiplefields':
					$arrOptions = array();

					if (strlen($this->Input->get('search')) && is_array($this->Input->get('for')))
					{
						$arrConds = array();
						$arrKeywords = array();
						foreach ($this->Input->get('for') as $field => $for)
						{
							if (in_array($field, $arrSearchFields) && strlen($for))
							{
								if (in_array($field, $this->arrOwnerFields))
								{
									if ($field == "fd_member")
									{
										$prop = 'arrMembers';
									}
									elseif ($field == "fd_member_group")
									{
										$prop = 'arrMemberGroups';
									}
									elseif ($field == "fd_user")
									{
										$prop = 'arrUsers';
									}
									elseif ($field == "fd_user_group")
									{
										$prop = 'arrUserGroups';
									}

									$arrMatches = $this->array_filter_like($this->$prop, urldecode($for));
									if (count($arrMatches))
									{
										$arrConds[] = $field ." IN(".implode(",", array_keys($arrMatches)).")";
									}
								}
								elseif (in_array($field, $this->arrBaseFields))
								{
									$arrConds[] = $field . " LIKE ?";
									$arrKeywords[] = '%' . urldecode($for) . '%';
								}
								else
								{
									$arrConds[] = '(SELECT value FROM tl_formdata_details WHERE ff_name="' .$field. '" AND pid=f.id ) LIKE ?';
									$arrKeywords[] = '%' . urldecode($for) . '%';
								}
							}
						}

						if (count($arrConds)>0)
						{
							$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . '(' . implode(" AND ", $arrConds) .')';
							$varKeyword = $arrKeywords;
						}

					}

					foreach (trimsplit(',', $this->list_search) as $field)
					{
						if (in_array($field, $this->arrBaseFields))
						{
							if ( strlen($this->strFormKey) && $field == 'form')
							{
								continue;
							}
							else
							{
								$arrOptions[] = array('name' => $field, 'label' => ( $GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['label'][0] ? htmlspecialchars($GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['label'][0]) : $field ) );
							}
						}
						elseif (is_array($this->arrDetailFields) && count($this->arrDetailFields) && in_array($field, $this->arrDetailFields) )
						{
							$arrOptions[] = array('name' => $field, 'label' => htmlspecialchars($GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['label'][0]) );
						}
					}

					$strOptions = $this->list_search;
					$this->Template->search_searchfields = $arrOptions;
					unset($arrOptions);

					break;

				case 'dropdown':
				default:
					if ( strlen($this->Input->get('search')) && strlen($this->Input->get('for')) )
					{
						$varKeyword = '%' . $this->Input->get('for') . '%';

						if (in_array($this->Input->get('search'), $this->arrOwnerFields))
						{
							$field = $this->Input->get('search');
							if ($field == "fd_member")
							{
								$prop = 'arrMembers';
							}
							elseif ($field == "fd_member_group")
							{
								$prop = 'arrMemberGroups';
							}
							elseif ($field == "fd_user")
							{
								$prop = 'arrUsers';
							}
							elseif ($field == "fd_user_group")
							{
								$prop = 'arrUserGroups';
							}

							$arrMatches = $this->array_filter_like($this->$prop, $this->Input->get('for'));
							if (count($arrMatches))
							{
								$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . $field ." IN(".implode(",", array_keys($arrMatches)).")";
							}
						}
						elseif (in_array($this->Input->get('search'), $this->arrBaseFields))
						{
							$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . $this->Input->get('search') . " LIKE ?";
						}
						else
						{
							$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . '(SELECT value FROM tl_formdata_details WHERE ff_name="' .$this->Input->get('search'). '" AND pid=f.id ) LIKE ?';
						}
					}

					foreach (trimsplit(',', $this->list_search) as $field)
					{
						if (in_array($field, $this->arrBaseFields))
						{
							if ( strlen($this->strFormKey) && $field == 'form')
							{
								continue;
							}
							else
							{
								$strOptions .= '  <option value="' . $field . '"' . (($field == $this->Input->get('search')) ? ' selected="selected"' : '') . '>' . htmlspecialchars($GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['label'][0]) . '</option>' . "\n";
							}
						}
						elseif (is_array($this->arrDetailFields) && count($this->arrDetailFields) && in_array($field, $this->arrDetailFields) )
						{
							$strOptions .= '  <option value="' . $field . '"' . (($field == $this->Input->get('search')) ? ' selected="selected"' : '') . '>' . ( $GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['label'][0] ? htmlspecialchars($GLOBALS['TL_DCA'][$this->list_table]['fields'][$field]['label'][0]) : $field ) . '</option>' . "\n";
						}
					}

					break;

			}

		}

		if (!$blnExport)
		{
			$this->Template->search_fields = $strOptions;
		}

		/**
		 * Get total number of records
		 */
		$strQuery = "SELECT COUNT(*) AS count FROM " . $this->list_table ." f";
		if ($this->list_where)
		{
			$arrListWhere = array();
			$arrListConds = preg_split('/(\sAND\s|\sOR\s)/si', $this->list_where, -1, PREG_SPLIT_DELIM_CAPTURE);

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
					if (in_array($strCondField, $this->arrDetailFields))
					{
						// handle numeric values
						if (isset($GLOBALS['TL_DCA']['tl_formdata']['fields'][$strCondField]['eval']['rgxp']) && $GLOBALS['TL_DCA']['tl_formdata']['fields'][$strCondField]['eval']['rgxp']=='digit')
						{
							$arrListWhere[] = '(SELECT value FROM tl_formdata_details WHERE ff_name="'.$strCondField.'" AND pid=f.id )+0.0 ' . implode('', $arrListCond);
						}
						else
						{
							$arrListWhere[] = '(SELECT value FROM tl_formdata_details WHERE ff_name="'.$strCondField.'" AND pid=f.id ) ' . implode('', $arrListCond);
						}
					}
					if (in_array($strCondField, $this->arrBaseFields))
					{
						$arrListWhere[] = $strCondField . implode('', $arrListCond);
					}
				}
			}
			$strListWhere = (count($arrListWhere)>0) ? '(' . implode('', $arrListWhere) .')' : '';
			$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . $strListWhere;
		}
		if (strlen($this->strFormKey))
		{
			$strWhere .= (strlen($strWhere) ? " AND " : " WHERE "). $this->strFormFilterKey . "='" . $this->strFormFilterValue . "'";
		}

		// replace insert tags in where, e.g. {{user::id}}
		$strWhere = $this->replaceWhereTags($strWhere);
		$strWhere = $this->replaceInsertTags($strWhere);

		$strQuery .=  $strWhere;

		$objTotal = $this->Database->prepare($strQuery)->execute($varKeyword);
		$rowTotalcount = $objTotal->row();
		$intTotalcount = max(0, $rowTotalcount['count']);

		/**
		 * Get the selected records
		 */
		$arrListFields = trimsplit(',', $this->list_fields);

		$intLastCol = -1;

		if ($this->Template->details || $this->Template->editable || $this->Template->deletable || $this->Template->exportable)
		{
			$intLastCol++;
		}

		$strListFields = '';
		$strListFields .= 'id,alias';

		if (count($arrListFields)>0)
		{
			foreach ($arrListFields as $field)
			{
				// do not display field id
				if ($field == 'id')
				{
					continue;
				}

				$intLastCol++;

				if (in_array($field, $this->arrBaseFields))
				{
					$strListFields .= ',' . $field;
				}

				if (is_array($this->arrDetailFields) && count($this->arrDetailFields) && in_array($field, $this->arrDetailFields))
				{
					$strListFields .= ',(SELECT value FROM tl_formdata_details WHERE ff_name="' .$field. '" AND pid=f.id) AS `' . $field . '`';
				}
			}
		}

		// member and user
		if (!in_array('fd_user', $arrListFields))
		{
			$strListFields .= ',fd_user';
		}
		if (!in_array('fd_member', $arrListFields))
		{
			$strListFields .= ',fd_member';
		}

		$strQuery = "SELECT " . $strListFields . " FROM " . $this->list_table . " f";
		$strQuery .=  $strWhere;

		// Order by
		if (strlen($this->Input->get('order_by')))
		{
			if (in_array($this->Input->get('order_by'), $arrListFields) && (in_array($this->Input->get('order_by'), $this->arrBaseFields) || in_array($this->Input->get('order_by'), $this->arrDetailFields)))
			{
				if (isset($GLOBALS['TL_DCA']['tl_formdata']['fields'][$this->Input->get('order_by')]['eval']['rgxp']) && $GLOBALS['TL_DCA']['tl_formdata']['fields'][$this->Input->get('order_by')]['eval']['rgxp']=='digit')
				{
					$strQuery .= " ORDER BY CAST(`" . $this->Input->get('order_by') . '` AS DECIMAL) ' . $this->Input->get('sort');
				}
				else
				{
					$strQuery .= " ORDER BY `" . $this->Input->get('order_by') . '` ' . $this->Input->get('sort');
				}
			}
		}
		elseif ($this->list_sort)
		{
			$strListSort = $this->list_sort;

			$arrListSort = explode(',', $strListSort);
			$arrSort = array();
			$arrSortSigned = array('digit', 'date', 'datim', 'time');

			if (count($arrListSort))
			{
				foreach ($arrListSort as $strSort)
				{
					$strSort = trim($strSort);
					preg_match_all('/^(.*?)(\s|$)/i', $strSort, $arrMatch);
					if (!in_array($arrMatch[1][0], $arrListFields))
					{
						if (in_array($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrMatch[1][0]]['eval']['rgxp'], $arrSortSigned))
						{
							$arrSort[] = preg_replace('/\b'.$arrMatch[1][0].'\b/i', 'CAST((SELECT value FROM tl_formdata_details WHERE ff_name="' .$arrMatch[1][0]. '" AND pid=f.id) AS DECIMAL)', $strSort);
						}
						else
						{
							$arrSort[] = preg_replace('/\b'.$arrMatch[1][0].'\b/i', '(SELECT value FROM tl_formdata_details WHERE ff_name="' .$arrMatch[1][0]. '" AND pid=f.id)', $strSort);
						}
					}
					else
					{
						if (in_array($GLOBALS['TL_DCA']['tl_formdata']['fields'][$arrMatch[1][0]]['eval']['rgxp'], $arrSortSigned))
						{
							$arrSort[] = preg_replace('/\b'.$arrMatch[1][0].'\b/i', 'CAST('.$arrMatch[1][0].' AS DECIMAL)', $strSort);
						}
						else
						{
							$arrSort[] = $strSort;
						}
					}
				}
			}

			if (count($arrSort)>0)
			{
				$strListSort = 'ORDER BY ' . implode(',', $arrSort);
			}
			else
			{
				$strListSort = '';
			}
			$strQuery .= $strListSort;
		}

		$objDataStmt = $this->Database->prepare($strQuery);

		// Limit
		if (!$blnExport)
		{
			if (intval($this->Input->get('per_page'))>0)
			{
				$objDataStmt->limit($this->Input->get('per_page'), (($page - 1) * $per_page));
			}
			elseif (intval($this->perPage)>0)
			{
				$objDataStmt->limit($this->perPage, (($page - 1) * $per_page));
			}
		}

		$objData = $objDataStmt->execute($varKeyword);

		/**
		 * Prepare URL
		 */

		//$strUrl = preg_replace('/\?.*$/', '', urldecode($this->Environment->request));
		$strUrl = $this->generateFrontendUrl($objPage->row());
		if ($strUrl == '/' || $strUrl == '//')
		{
			$strUrl = '';
		}

		$strUrlParams = '';
		$strUrlSuffix = $GLOBALS['TL_CONFIG']['urlSuffix'];

		if (!$blnExport)
		{
			$this->Template->url = $strUrl;
		}

		$blnQuery = false;

		foreach (preg_split('/&(amp;)?/', urldecode($_SERVER['QUERY_STRING'])) as $fragment)
		{
			if (strlen($fragment))
			{
				if (strncasecmp($fragment, 'file', 5) !==0 && strncasecmp($fragment, 'act', 3) !==0 &&  strncasecmp($fragment, 'order_by', 8) !== 0 && strncasecmp($fragment, 'sort', 4) !== 0 && strncasecmp($fragment, 'page', 4) !== 0)
				{
					$strUrlParams .= (!$blnQuery ? '' : '&amp;') . $fragment;
					$blnQuery = true;
				}
			}
		}

		/**
		 * Prepare data arrays
		 */
		$arrTh = array();
		$arrTd = array();

		$arrFields = $arrListFields;

		$intRowCounter = -1;
		$intColCounter = 0;

		$intRowCounter++;

		$ignoreFields = array('id','pid','sorting');

		// THEAD
		if (!$blnExport)
		{
			for ($i=0; $i<count($arrFields); $i++)
			{
				// do not display some special fields
				if (in_array($arrFields[$i], $ignoreFields) || $GLOBALS['TL_DCA'][$this->list_table]['fields'][$arrFields[$i]]['inputType'] == 'password')
				{
					continue;
				}

				$class = '';
				$sort = 'asc';
				$strField = strlen($label = $GLOBALS['TL_DCA'][$this->list_table]['fields'][$arrFields[$i]]['label'][0]) ? $label : $arrFields[$i];

				if ($this->Input->get('order_by') == $arrFields[$i])
				{
					$sort = ($this->Input->get('sort') == 'asc') ? 'desc' : 'asc';
					$class = ' sorted ' . $this->Input->get('sort');
				}

				// add CSS class defined in form generator
				if (isset($GLOBALS['TL_DCA'][$this->list_table]['fields'][$arrFields[$i]]['ff_class']) && strlen($GLOBALS['TL_DCA'][$this->list_table]['fields'][$arrFields[$i]]['ff_class']))
				{
					$class .= ' ' . $GLOBALS['TL_DCA'][$this->list_table]['fields'][$arrFields[$i]]['ff_class'];
				}

				$arrTh[] = array
				(
					'link' => htmlspecialchars($strField),
					'href' => $strUrl . (strlen($strUrlParams) ? '?'.$strUrlParams.'&amp;' : '?') . 'order_by=' . $arrFields[$i] . '&amp;sort=' . $sort,
					'title' => htmlspecialchars(sprintf($GLOBALS['TL_LANG']['MSC']['list_orderBy'], $strField)),
					'class' => $class . (($i == 0) ? ' col_first' : '')
				);

			}
		}
		else
		{
			$strExpEncl = '"';
			$strExpSep = '';

			if ($strExportMode=='xls')
			{
				if (!$blnCustomXlsExport)
				{
					$xls = new xlsexport();
					$strXlsSheet = "Export";
					$xls->addworksheet($strXlsSheet);
				}
			}
			else // defaults to csv
			{
				header('Content-Type: appplication/csv; charset=' . ($this->blnExportUTF8Decode ? 'CP1252' : 'utf-8'));
				header('Content-Transfer-Encoding: binary');
				header('Content-Disposition: attachment; filename="export_' . $this->strFormKey . '_' . date("Ymd_His") .'.csv"');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Expires: 0');

				$strExpSep = '';
			}

			$intColCounter = -1;

			for ($i=0; $i<count($arrFields); $i++)
			{
				$v = $arrFields[$i];

				if (in_array($v, $ignoreFields))
				{
					continue;
				}

				$intColCounter++;

				if ($useFieldNames)
				{
					$strName = $v;
				}
				elseif (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['label'][0]))
				{
					$strName = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['label'][0];
				}
				elseif (strlen($GLOBALS['TL_LANG']['tl_formdata'][$v][0]))
				{
					$strName = $GLOBALS['TL_LANG']['tl_formdata'][$v][0];
				}
				else
				{
					$strName = strtoupper($v);
				}

				if (strlen($strName))
				{
					$strName = $this->String->decodeEntities($strName);
				}

				if ($this->blnExportUTF8Decode || ($strExportMode=='xls' && !$blnCustomXlsExport))
				{
					$strName = $this->convertEncoding($strName, $GLOBALS['TL_CONFIG']['characterSet'], 'CP1252');
				}

				if ($strExportMode=='csv')
				{
					$strName = str_replace('"', '""', $strName);
					echo $strExpSep . $strExpEncl . $strName . $strExpEncl;

					$strExpSep = ";";
				}
				if ($strExportMode=='xls')
				{
					if (!$blnCustomXlsExport)
					{
						$xls->setcell(array("sheetname" => $strXlsSheet,"row" => $intRowCounter, "col" => $intColCounter, "data" => $strName, "fontweight" => XLSFONT_BOLD, "vallign" => XLSXF_VALLIGN_TOP, "fontfamily" => XLSFONT_FAMILY_NORMAL));
						$xls->setcolwidth($strXlsSheet,$intColCounter,0x1aff);
					}
					else
					{
						$arrHookDataColumns[$v] = $strName;
					}
				}

			}

			$intRowCounter++;

			if ($strExportMode=='csv')
			{
				echo "\n";
			}

		}

		// Data result
		$arrRows = $objData->fetchAllAssoc();

		if (!$blnExport)
		{
			// also store as list of items
			$arrListItems = array();
			$arrEditAllowed = array();
			$arrDeleteAllowed = array();
			$arrExportAllowed = array();

			// TBODY
			for ($i=0; $i<count($arrRows); $i++)
			{
				$class = 'row_' . $i . (($i == 0) ? ' row_first' : '') . ((($i + 1) == count($arrRows)) ? ' row_last' : '') . ((($i % 2) == 0) ? ' even' : ' odd');

				// check edit access
				$blnEditAllowed = false;
				if ($this->efg_fe_edit_access == 'none')
				{
					$blnEditAllowed = false;
				}
				elseif ($this->efg_fe_edit_access == 'public')
				{
					$blnEditAllowed = true;
				}
				elseif (strlen($this->efg_fe_edit_access) )
				{
					if (in_array(intval($arrRows[$i]['fd_member']), $this->arrAllowedEditOwnerIds))
					{
						$blnEditAllowed = true;
					}
				}

				// check delete access
				$blnDeleteAllowed = false;
				if ($this->efg_fe_delete_access == 'none')
				{
					$blnDeleteAllowed = false;
				}
				elseif ($this->efg_fe_delete_access == 'public')
				{
					$blnDeleteAllowed = true;
				}
				elseif (strlen($this->efg_fe_delete_access) )
				{
					if (in_array(intval($arrRows[$i]['fd_member']), $this->arrAllowedDeleteOwnerIds))
					{
						$blnDeleteAllowed = true;
					}
				}

				// check export access
				$blnExportAllowed = false;
				if ($this->efg_fe_export_access == 'none')
				{
					$blnExportAllowed = false;
				}
				elseif ($this->efg_fe_export_access == 'public')
				{
					$blnExportAllowed = true;
				}
				elseif (strlen($this->efg_fe_export_access) )
				{
					if (in_array(intval($arrRows[$i]['fd_member']), $this->arrAllowedExportOwnerIds))
					{
						$blnExportAllowed = true;
					}
				}

				$arrEditAllowed[$arrRows[$i]['id']] = $blnEditAllowed;
				$arrDeleteAllowed[$arrRows[$i]['id']] = $blnDeleteAllowed;
				$arrExportAllowed[$arrRows[$i]['id']] = $blnExportAllowed;

				$j = 0;

				foreach ($arrListFields as $intKey => $strVal)
				{

					$k = $strVal;
					$v = $arrRows[$i][$k];

					// do not display some special fields
					if (in_array($k, $ignoreFields) || $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType'] == 'password')
					{
						continue;
					}

					$strLinkDetails = '';
					if (strlen($arrRows[$i]['alias']) && !$GLOBALS['TL_CONFIG']['disableAlias'])
					{
						$strLinkDetails = str_replace($strUrlSuffix, '', $strUrl) . (strlen($strUrl)? '/' : '') .$this->strDetailKey.'/' . $arrRows[$i]['alias'] . $strUrlSuffix . (strlen($strUrlParams) ? '?' . $strUrlParams : '');
					}
					else
					{
						$strLinkDetails = $strUrl .'?'.$this->strDetailKey.'=' . $arrRows[$i]['id'] . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
					}

					$strLinkEdit = '';
					if ($arrEditAllowed[$arrRows[$i]['id']])
					{
						if (strlen($arrRows[$i]['alias']) && !$GLOBALS['TL_CONFIG']['disableAlias'])
						{
							$strLinkEdit = str_replace($strUrlSuffix, '', $strUrl) . (strlen($strUrl)? '/' : '') .$this->strDetailKey.'/' . $arrRows[$i]['alias'] . $strUrlSuffix . '?act=edit' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
						}
						else
						{
							$strLinkEdit = $strUrl .'?'.$this->strDetailKey.'=' . $arrRows[$i]['id'] . '&amp;act=edit' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
						}
					}

					$strLinkDelete = '';
					if ($arrDeleteAllowed[$arrRows[$i]['id']])
					{
						if (strlen($arrRows[$i]['alias']) && !$GLOBALS['TL_CONFIG']['disableAlias'])
						{
							$strLinkDelete = str_replace($strUrlSuffix, '', $strUrl) . (strlen($strUrl)? '/' : '') .$this->strDetailKey.'/' . $arrRows[$i]['alias'] . $strUrlSuffix . '?act=delete' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
						}
						else
						{
							$strLinkDelete = $strUrl .'?'.$this->strDetailKey.'=' . $arrRows[$i]['id'] . '&amp;act=delete' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
						}
					}

					$strLinkExport = '';
					if ($arrExportAllowed[$arrRows[$i]['id']])
					{
						if (strlen($arrRows[$i]['alias']) && !$GLOBALS['TL_CONFIG']['disableAlias'])
						{
							$strLinkExport = str_replace($strUrlSuffix, '', $strUrl) . (strlen($strUrl)? '/' : '') .$this->strDetailKey.'/' . $arrRows[$i]['alias'] . $strUrlSuffix . '?act=export' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
						}
						else
						{
							$strLinkExport = $strUrl .'?'.$this->strDetailKey.'=' . $arrRows[$i]['id'] . '&amp;act=export' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
						}
					}

					$value = $this->formatValue($k, $v);
					$v = $this->String->decodeEntities($v);

					if ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType'] == 'fileTree' && $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['multiple'] == true)
					{
						$v = (is_string($v) && strpos($v, '|') !== false) ? explode('|', $v) : deserialize($v);
					}

					// add CSS class defined in form generator
					$ff_class = '';
					if (isset($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['ff_class']) && strlen($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['ff_class']))
					{
						$ff_class = ' ' . $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['ff_class'];
					}

					$arrTd[$class][] = array
					(
						'id' => $arrRows[$i]['id'],
						'alias' => $arrRows[$i]['alias'],
						'content' => ($value ? $value : '&nbsp;'),
						'raw' => $v,
						'class' => 'col_' . $j . $ff_class . (($j == 0) ? ' col_first' : '') . (($j == $intLastCol) ? ' col_last' : ''),
						'link_details' => $strLinkDetails,
						'link_edit' => $strLinkEdit,
						'link_delete' => $strLinkDelete,
						'link_export' => $strLinkExport
					);

					// store also as item
					$arrListItems[$i][$k] = array(
						'id'=>$arrRows[$i]['id'],
						'alias' => $arrRows[$i]['alias'],
						'name' => $k,
						'label' => strlen($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['label'][0]) ? htmlspecialchars($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['label'][0]) : htmlspecialchars($k),
						'content' => ($value ? $value : '&nbsp;'),
						'raw' => $v,
						'class' => 'field_' . $j . $ff_class . (($j == 0) ? ' field_first' : '') . (($j == ($intLastCol - 1)) ? ' field_last' : ''),
						'record_class' => str_replace('row_', 'record_', $class),
						'link_details' => $strLinkDetails,
						'link_edit' => $strLinkEdit,
						'link_delete' => $strLinkDelete,
						'link_export' => $strLinkExport
					);

					if ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType'] == 'fileTree')
					{
						$value = $arrListItems[$i][$k]['raw'];

						if (is_string($value) && strlen($value) && is_dir(TL_ROOT .'/' . $value))
						{
							$arrTd[$class][count($arrTd[$class])-1]['content'] = '&nbsp;';
							$arrListItems[$i][$k]['content'] = '&nbsp;';
						}
						// single file
						elseif (is_string($value) && strlen($value) && is_file(TL_ROOT . '/' . $value))
						{
							$objFile = new File($value);
							if (!in_array($objFile->extension, $allowedDownload) )
							{
								$arrTd[$class][count($arrTd[$class])-1]['content'] = '&nbsp;';
								$arrListItems[$i][$k]['content'] = '&nbsp;';
							}
							else
							{
								$arrTd[$class][count($arrTd[$class])-1]['type'] = 'file';
								$arrTd[$class][count($arrTd[$class])-1]['src'] = $this->urlEncode($value);
								$arrListItems[$i][$k]['type'] = 'file';
								$arrListItems[$i][$k]['src'] = $this->urlEncode($value);
								if ( substr($objFile->mime, 0, 6) == 'image/' )
								{
									$arrTd[$class][count($arrTd[$class])-1]['display'] = 'image';
									$arrListItems[$i][$k]['display'] = 'image';
								}
								else
								{
									$size = ' ('.number_format(($objFile->filesize/1024), 1, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']).' kB)';

									$href = preg_replace('@(\?|&amp;)download=.*?(&amp;|$)@si', '', $this->Environment->request);
									$href .= ((strpos($href, '?')>=1) ? '&amp;' : '?') . 'download=' . $arrRows[$i]['id'] . '.' . $k;
									$href = ampersand($href);

									$arrTd[$class][count($arrTd[$class])-1]['display'] = 'download';
									$arrTd[$class][count($arrTd[$class])-1]['size'] = $size;
									$arrTd[$class][count($arrTd[$class])-1]['href'] = $href;
									$arrTd[$class][count($arrTd[$class])-1]['linkTitle'] = basename($objFile->basename);
									$arrTd[$class][count($arrTd[$class])-1]['icon'] = (strlen($this->efg_iconfolder)? $this->efg_iconfolder.'/' : 'system/modules/efg/html/') . $objFile->icon;

									$arrListItems[$i][$k]['display'] = 'download';
									$arrListItems[$i][$k]['size'] = $size;
									$arrListItems[$i][$k]['href'] = $href;
									$arrListItems[$i][$k]['linkTitle'] = basename($objFile->basename);
									$arrListItems[$i][$k]['icon'] = (strlen($this->efg_iconfolder)? $this->efg_iconfolder.'/' : 'system/modules/efg/html/') . $objFile->icon;
								}
							}
						}
						// multiple files
						elseif (is_array($value))
						{
							$arrTemp = array();
							$keyTemp = -1;

							$arrTd[$class][count($arrTd[$class])-1]['type'] = 'file';
							$arrListItems[$i][$k]['type'] = 'file';

							foreach ($value as $kF => $strFile)
							{
								if (strlen($strFile) && is_file(TL_ROOT . '/' . $strFile)) {
									$objFile = new File($strFile);

									if (!in_array($objFile->extension, $allowedDownload) )
									{
										unset($arrListItems[$i][$k]['raw'][$kF]);
										continue;
									}
									else
									{
										$keyTemp++;

										$arrTemp[$keyTemp]['src'] = $this->urlEncode($strFile);

										if ( substr($objFile->mime, 0, 6) == 'image/' )
										{
											$arrTemp[$keyTemp]['display'] = 'image';
										}
										else
										{
											$size = ' ('.number_format(($objFile->filesize/1024), 1, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']).' kB)';

											$href = preg_replace('@(\?|&amp;)download=.*?(&amp;|$)@si', '', $this->Environment->request);
											$href .= ((strpos($href, '?')>=1) ? '&amp;' : '?') . 'download=' . $arrRows[$i]['id'] . '.' . $k;
											$href = ampersand($href);

											$arrTemp[$keyTemp]['display'] = 'download';
											$arrTemp[$keyTemp]['size'] = $size;
											$arrTemp[$keyTemp]['href'] = $href;
											$arrTemp[$keyTemp]['linkTitle'] = basename($objFile->basename);
											$arrTemp[$keyTemp]['icon'] = 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;

										}
									}

								}
							}

							$arrTd[$class][count($arrTd[$class])-1]['content'] = $arrTemp;
							$arrListItems[$i][$k]['content'] = $arrTemp;

							$arrTd[$class][count($arrTd[$class])-1]['multiple'] = true;
							$arrTd[$class][count($arrTd[$class])-1]['number_of_items'] = count($arrTemp);
							$arrListItems[$i][$k]['multiple'] = true;
							$arrListItems[$i][$k]['number_of_items'] = count($arrTemp);

							unset($arrTemp);
						}

					}

					$j++;
				}
			}

			$strTotalNumberOfItems = number_format((int) $intTotalcount, 0, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']);
			$this->Template->totalNumberOfItems = array(
				'raw' => (int) $intTotalcount,
				'formatted' => $strTotalNumberOfItems,
				'content' => sprintf($GLOBALS['TL_LANG']['MSC']['efgTotalNumberOfItems'], $strTotalNumberOfItems)
			);

			$this->Template->thead = $arrTh;
			$this->Template->tbody = $arrTd;

			$this->Template->listItems = $arrListItems;

			$this->Template->arrEditAllowed = $arrEditAllowed;
			$this->Template->arrDeleteAllowed = $arrDeleteAllowed;
			$this->Template->arrExportAllowed = $arrExportAllowed;

			/**
			 * Pagination
			 */
			if (intval($per_page)>0)
			{
				$objPagination = new Pagination($intTotalcount, $per_page);
				$this->Template->pagination = $objPagination->generate("\n  ");
			}

			/**
			 * Template variables
			 */
			$this->Template->action = ampersand(urldecode($this->Environment->request));
			$this->Template->per_page_label = specialchars($GLOBALS['TL_LANG']['MSC']['list_perPage']);
			$this->Template->search_label = specialchars($GLOBALS['TL_LANG']['MSC']['search']);
			$this->Template->per_page = $this->Input->get('per_page');
			if (intval($this->perPage)>0)
			{
				$this->Template->list_perPage = $this->perPage;
			}
			$this->Template->search = $this->Input->get('search');
			$this->Template->for = $this->Input->get('for');
			$this->Template->order_by = $this->Input->get('order_by');
			$this->Template->sort = $this->Input->get('sort');
			//$this->Template->col_last = 'col_' . $j;
			$this->Template->col_last = 'col_' . $intLastCol;

		} // !$blnExport

		else
		{

			$arrEditAllowed = array();
			$arrDeleteAllowed = array();
			$arrExportAllowed = array();

			// Process result and format values
			foreach ($arrRows as $row)
			{

				$args = array();

				$strExpEncl = '"';
				$strExpSep = '';

				$intColCounter = -1;

				// check export access
				$blnExportAllowed = false;
				if ($this->efg_fe_export_access == 'none')
				{
					$blnExportAllowed = false;
				}
				elseif($this->efg_fe_export_access == 'public')
				{
					$blnExportAllowed = true;
				}
				elseif (strlen($this->efg_fe_export_access) )
				{
					if (in_array(intval($row['fd_member']), $this->arrAllowedExportOwnerIds))
					{
						$blnExportAllowed = true;
					}
				}

				if ($blnExportAllowed == false)
				{
					continue;
				}

				// Prepare field value
				foreach ($arrFields as $k=>$v)
				{
					if (in_array($v, $ignoreFields) )
					{
						continue;
					}

					$intColCounter++;

					$strVal = '';
					$strVal = $row[$v];

					if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['rgxp'] == 'date')
					{
						$strVal = ( $row[$v] ? date($GLOBALS['TL_CONFIG']['dateFormat'], $row[$v]) : '' );
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['rgxp'] == 'time')
					{
						$strVal = ( $row[$v] ? date($GLOBALS['TL_CONFIG']['timeFormat'], $row[$v]) : '' );
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['rgxp'] == 'datim')
					{
						$strVal = ( $row[$v] ? date($GLOBALS['TL_CONFIG']['datimFormat'], $row[$v]) : '' );
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['multiple'])
					{
						if ($useFormValues == 1)
						{
							// single value checkboxes don't have options
							if ((is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) && count($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) > 0))
							{
								$strVal = strlen($row[$v]) ? key($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) : '';
							}
							else
							{
								$strVal = $row[$v];
							}
						}
						else
						{
							$strVal = strlen($row[$v]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['label'][0] : '-';
						}
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'radio'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'efgLookupRadio'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'select'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'efgLookupSelect'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'checkbox'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'efgLookupCheckbox')
					{
						// take the assigned value instead of the user readable output
						if ($useFormValues == 1)
						{
							if ((strpos($row[$v], "|") === false) && (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) && count($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) > 0))
							{
								$options = array_flip($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']);
								$strVal = $options[$row[$v]];
							}
							else
							{
								if ((is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) && count($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) > 0))
								{
									$options = array_flip($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']);
									$tmparr = split("\\|", $row[$v]);
									$fieldvalues = array();
									foreach ($tmparr as $valuedesc)
									{
										array_push($fieldvalues, $options[$valuedesc]);
									}
									$strVal = implode(",\n", $fieldvalues);
								}
								else
								{
									$strVal = strlen($row[$v]) ? str_replace('|', ",\n", $row[$v]) : '';
								}
							}
						}
						else
						{
							$strVal = strlen($row[$v]) ? str_replace('|', ",\n", $row[$v]) : '';
						}
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'fileTree')
					{
						if (is_string($row[$v]) && strpos($row[$v], '|') !== false)
						{
							$strVal = implode(",\n", explode('|', $row[$v]));
						}
						else
						{
							$strVal = implode(",\n", deserialize($row[$v], true));
						}
					}
					else
					{
						$row_v = deserialize($row[$v]);

						if (is_array($row_v))
						{
							$args_k = array();

							foreach ($row_v as $option)
							{
								$args_k[] = strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$option]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$option] : $option;
							}

							$args[$k] = implode(",\n", $args_k);
						}
						elseif (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$row[$v]]))
						{
							$args[$k] = is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$row[$v]]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$row[$v]][0] : $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$row[$v]];
						}
						else
						{
							$args[$k] = $row[$v];
						}
						$strVal = is_null($args[$k]) ? $args[$k] : vsprintf('%s', $args[$k]);
					}

					if (in_array($v, $this->arrBaseFields) || in_array($v, $this->arrOwnerFields))
					{
						if ($v == 'fd_member')
						{
							$strVal = $this->arrMembers[intval($row[$v])];
						}
						elseif ($v == 'fd_user')
						{
							$strVal = $this->arrUsers[intval($row[$v])];
						}
						elseif ($v == 'fd_member_group')
						{
							$strVal = $this->arrMemberGroups[intval($row[$v])];
						}
						elseif ($v == 'fd_user_group')
						{
							$strVal = $this->arrUserGroups[intval($row[$v])];
						}
					}

					if (strlen($strVal))
					{
						$strVal = $this->String->decodeEntities($strVal);
						$strVal = preg_replace(array('/<br.*\/*>/si'), array("\n"), $strVal);

						if ($this->blnExportUTF8Decode || ($strExportMode=='xls' && !$blnCustomXlsExport))
						{
							$strVal = $this->convertEncoding($strVal, $GLOBALS['TL_CONFIG']['characterSet'], 'CP1252');
						}
					}

					if ($strExportMode=='csv')
					{
						$strVal = str_replace('"', '""', $strVal);
						echo $strExpSep . $strExpEncl . $strVal . $strExpEncl;

						$strExpSep = ";";
					}

					if ($strExportMode=='xls')
					{
						if (!$blnCustomXlsExport)
						{
							$xls->setcell(array("sheetname" => $strXlsSheet,"row" => $intRowCounter, "col" => $intColCounter, "data" => $strVal, "vallign" => XLSXF_VALLIGN_TOP, "fontfamily" => XLSFONT_FAMILY_NORMAL));
						}
						else
						{
							$arrHookData[$intRowCounter][$v] = $strVal;
						}
					}

				}

				$intRowCounter++;

				if ($strExportMode=='csv')
				{
					$strExpSep = '';
					echo "\n";
				}

			} // foreach ($result as $row)

			if ($strExportMode=='xls')
			{
				if (!$blnCustomXlsExport)
				{
					$xls->sendfile("export_" . $this->strFormKey . "_" . date("Ymd_His") . ".xls");
					exit;
				}
				else
				{
					foreach ($GLOBALS['TL_HOOKS']['efgExportXls'] as $key => $callback)
					{
						$this->import($callback[0]);
						$res = $this->$callback[0]->$callback[1]($arrHookDataColumns, $arrHookData);
					}
				}
			}
			exit;

		} // $blnExport

	}

	/**
	 * List a single record
	 */
	protected function listSingleRecord()
	{
		global $objPage;

		/**
		 * Prepare URL
		 */
		$strUrl = preg_replace('/\?.*$/', '', urldecode($this->Environment->request));
		$strUrlParams = '';
		$strUrlSuffix = $GLOBALS['TL_CONFIG']['urlSuffix'];

		$blnQuery = false;
		foreach (preg_split('/&(amp;)?/', urldecode($_SERVER['QUERY_STRING'])) as $fragment)
		{
			if (strlen($fragment))
			{
				if (strncasecmp($fragment, 'file', 5) !== 0 && strncasecmp($fragment, $this->strDetailKey, strlen($this->strDetailKey)) !== 0 && strncasecmp($fragment, 'order_by', 8) !== 0 && strncasecmp($fragment, 'sort', 4) !== 0 && strncasecmp($fragment, 'page', 4) !== 0)
				{
					$strUrlParams .= (!$blnQuery ? '' : '&amp;') . $fragment;
					$blnQuery = true;
				}
			}
		}

		$strVarConnector = $blnQuery ? '&amp;' : '?';

		// check record
		if (is_null($this->intRecordId) || intval($this->intRecordId)<1)
		{
			$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i'), array('',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
			$this->redirect($strRed);
		}

		// check access
		if (strlen($this->efg_list_access) && $this->efg_list_access != 'public' )
		{
			$objOwner = $this->Database->prepare("SELECT fd_member FROM tl_formdata WHERE id=?")
							->execute($this->intRecordId);

			$varOwner = $objOwner->fetchAssoc();
			if (!in_array(intval($varOwner['fd_member']), $this->arrAllowedOwnerIds))
			{
				$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i'), array('',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
				$this->redirect($strRed);
			}
		}

		// check edit access
		$blnEditAllowed = false;
		if ($this->efg_fe_edit_access == 'none')
		{
			$blnEditAllowed = false;
		}
		elseif($this->efg_fe_edit_access == 'public')
		{
			$blnEditAllowed = true;
		}
		elseif (strlen($this->efg_fe_edit_access) )
		{
			$objOwner = $this->Database->prepare("SELECT fd_member FROM tl_formdata WHERE id=?")
							->execute($this->intRecordId);
			$varOwner = $objOwner->fetchAssoc();
			if (in_array(intval($varOwner['fd_member']), $this->arrAllowedEditOwnerIds))
			{
				$blnEditAllowed = true;
			}
		}

		// check delete access
		$blnDeleteAllowed = false;
		if ($this->efg_fe_delete_access == 'none')
		{
			$blnDeleteAllowed = false;
		}
		elseif($this->efg_fe_delete_access == 'public')
		{
			$blnDeleteAllowed = true;
		}
		elseif (strlen($this->efg_fe_delete_access) )
		{
			$objOwner = $this->Database->prepare("SELECT fd_member FROM tl_formdata WHERE id=?")
							->execute($this->intRecordId);
			$varOwner = $objOwner->fetchAssoc();
			if (in_array(intval($varOwner['fd_member']), $this->arrAllowedDeleteOwnerIds))
			{
				$blnDeleteAllowed = true;
			}
		}

		// check export access
		$blnExportAllowed = false;
		if ($this->efg_fe_export_access == 'none')
		{
			$blnExportAllowed = false;
		}
		elseif($this->efg_fe_export_access == 'public')
		{
			$blnExportAllowed = true;
		}
		elseif (strlen($this->efg_fe_export_access) )
		{
			$objOwner = $this->Database->prepare("SELECT fd_member FROM tl_formdata WHERE id=?")
							->execute($this->intRecordId);
			$varOwner = $objOwner->fetchAssoc();
			if (in_array(intval($varOwner['fd_member']), $this->arrAllowedExportOwnerIds))
			{
				$blnExportAllowed = true;
			}
		}

		$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		// Fallback template
		if (!strlen($this->list_info_layout))
			$this->list_info_layout = 'info_default';

		$this->Template = new FrontendTemplate($this->list_info_layout);

		$this->Template->textlink_details = $GLOBALS['TL_LANG']['tl_formdata']['fe_link_details'];
		$this->Template->textlink_edit = $GLOBALS['TL_LANG']['tl_formdata']['fe_link_edit'];
		$this->Template->textlink_delete = $GLOBALS['TL_LANG']['tl_formdata']['fe_link_delete'];
		$this->Template->text_confirmDelete = $GLOBALS['TL_LANG']['tl_formdata']['fe_deleteConfirm'];
		$this->Template->textlink_export = $GLOBALS['TL_LANG']['tl_formdata']['fe_link_export'];

		$this->Template->editAllowed = $blnEditAllowed;
		$this->Template->deleteAllowed = $blnDeleteAllowed;
		$this->Template->exportAllowed = $blnExportAllowed;

		$this->list_info = deserialize($this->list_info);

		$this->Template->record = array();

		// also store as single item
		$this->Template->listItem = array();

		$arrListFields = explode(',', $this->list_info);
		$strSep = '';

		// wildcards * and -
		if ($arrListFields[0] == '*')
		{
			$arrTempFields = array_merge($this->arrBaseFields, $this->arrDetailFields);
			foreach($arrListFields as $field)
			{
				if (substr($field,0,1) == '-')
				{
					$intKey = array_search(substr($field,1), $arrTempFields);
					if (!is_bool($intKey))
					{
						unset($arrTempFields[$intKey]);
					}
				}
			}
			$arrListFields = $arrTempFields;
		}

		$strQuery = "SELECT ";
		$strWhere = '';

		foreach($arrListFields as $field)
		{
			if ( in_array($field, $this->arrBaseFields) )
			{
				$strQuery .= $strSep . $field;
				$strSep = ', ';
			}
			if ( is_array($this->arrDetailFields) && count($this->arrDetailFields) && in_array($field, $this->arrDetailFields) )
			{
				$strQuery .= $strSep .'(SELECT value FROM tl_formdata_details WHERE ff_name="' .$field. '" AND pid=f.id ) AS `' . $field . '`';
				$strSep = ', ';
			}
		}

		$strQuery .= " FROM " . $this->list_table . " f";
		$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . "id=?";
		$strQuery .= $strWhere;

		$objRecord = $this->Database->prepare($strQuery)
									->limit(1)
									->execute($this->intRecordId);

		if ($objRecord->numRows < 1)
		{
			return;
		}

		$arrFields = array();
		$arrRow = $objRecord->fetchAssoc();
		$count = -1;

		$strLinkEdit = '';
		if ($blnEditAllowed)
		{
			if (strlen($arrRow['alias']) && !$GLOBALS['TL_CONFIG']['disableAlias'])
			{
				$strLinkEdit = $strUrl . '?act=edit' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
			}
			else
			{
				$strLinkEdit = $strUrl . '?'.$this->strDetailKey.'='.$this->intRecordId.'&amp;act=edit' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
			}
		}

		$strLinkDelete = '';
		if ($blnDeleteAllowed)
		{
			if (strlen($arrRow['alias']) && !$GLOBALS['TL_CONFIG']['disableAlias'])
			{
				$strLinkDelete = $strUrl . '?act=delete' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
			}
			else
			{
				$strLinkDelete = $strUrl . '?'.$this->strDetailKey.'='.$this->intRecordId.'&amp;act=delete' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
			}
		}

		$strLinkExport = '';
		if ($blnExportAllowed)
		{
			if (strlen($arrRow['alias']) && !$GLOBALS['TL_CONFIG']['disableAlias'])
			{
				$strLinkExport = $strUrl . '?act=export' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
			}
			else
			{
				$strLinkExport = $strUrl . '?'.$this->strDetailKey.'='.$this->intRecordId.'&amp;act=export' . (strlen($strUrlParams) ? '&amp;' . $strUrlParams : '');
			}
		}

		$arrItem = array();

		foreach ($arrListFields as $intKey => $strVal)
		{

			$k = $strVal;
			$v = $arrRow[$k];

			$value = $this->formatValue($k, $v);
			$v = deserialize($this->String->decodeEntities($v));

			if ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType'] == 'fileTree' && $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['multiple'] == true)
			{
				$v = (is_string($v) && strpos($v, '|') !== false) ? explode('|', $v) : deserialize($v);
			}

			$class = 'row_' . ++$count . (($count == 0) ? ' row_first' : '') . (($count >= (count($arrListFields) - 1)) ? ' row_last' : '') . ((($count % 2) == 0) ? ' even' : ' odd');

			// add CSS class defined in form generator
			if (isset($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['ff_class']) && strlen($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['ff_class']))
			{
				$class .= ' ' . $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['ff_class'];
			}

			$arrFields[$class] = array
			(
				'label' => (strlen($label = $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['label'][0]) ? htmlspecialchars($label) : htmlspecialchars($this->arrFF[$k]['label'])),
				'content' => $value,
				// 'raw' => deserialize($this->String->decodeEntities($v))
				'raw' => $v
			);

			$arrItem[$k] = array(
				'name' => $k,
				'label'=>(strlen($label = $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['label'][0]) ? htmlspecialchars($label) : htmlspecialchars($this->arrFF[$k]['label'])),
				'content' => $value,
				//'raw' => deserialize($this->String->decodeEntities($v)),
				'raw' => $v,
				'class' => str_replace('row_', 'field_', $class)
			);


			if ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType'] == 'fileTree')
			{
				if (is_dir(TL_ROOT .'/' . $arrFields[$class]['content']))
				{
					$arrFields[$class]['content'] = '&nbsp;';
					$arrItem[$k]['content'] = '&nbsp;';
				}

				// single file
				elseif (!is_array($arrFields[$class]['raw']) && strlen($arrFields[$class]['raw']) && is_file(TL_ROOT . '/' . $arrFields[$class]['raw']) )
				{
					$objFile = new File($arrFields[$class]['content']);

					if (!in_array($objFile->extension, $allowedDownload) )
					{
						$arrFields[$class]['content'] = '&nbsp;';
						$arrItem[$k]['content'] = '&nbsp;';
					}
					else
					{
						$arrFields[$class]['type'] = 'file';
						$arrFields[$class]['src'] = $this->urlEncode($arrFields[$class]['content']);
						$arrItem[$k]['type'] = 'file';
						$arrItem[$k]['src'] = $this->urlEncode($arrFields[$class]['content']);
						if ( substr($objFile->mime, 0, 6) == 'image/' )
						{
							$arrFields[$class]['display'] = 'image';
							$arrItem[$k]['display'] = 'image';
						}
						else
						{
							$size = ' ('.number_format(($objFile->filesize/1024), 1, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']).' kB)';

							$href = preg_replace('@(\?|&amp;)download=.*?(&amp;|$)@si', '', $this->Environment->request);
							$href .= ((strpos($href, '?')>=1) ? '&amp;' : '?') . 'download=' . $this->intRecordId . '.' . $k;
							$href = ampersand($href);

							$arrFields[$class]['display'] = 'download';
							$arrFields[$class]['size'] = $size;
							$arrFields[$class]['href'] = $href;
							$arrFields[$class]['linkTitle'] = basename($objFile->basename);
							$arrFields[$class]['icon'] = 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;

							$arrItem[$k]['display'] = 'download';
							$arrItem[$k]['size'] = $size;
							$arrItem[$k]['href'] = $href;
							$arrItem[$k]['linkTitle'] = basename($objFile->basename);
							$arrItem[$k]['icon'] = 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;
						}
					}
				}

				// multiple files
				elseif (is_array($arrFields[$class]['raw']))
				{
					$arrTemp = array();
					$keyTemp = -1;

					$arrFields[$class]['type'] = 'file';
					$arrItem[$k]['type'] = 'file';

					foreach ($arrFields[$class]['raw'] as $kF => $strFile)
					{
						if (strlen($strFile) && is_file(TL_ROOT . '/' . $strFile)) {
							$objFile = new File($strFile);

							if (!in_array($objFile->extension, $allowedDownload) )
							{
								unset($arrFields[$class]['raw'][$kF]);
								continue;
							}
							else
							{
								$keyTemp++;

								$arrTemp[$keyTemp]['src'] = $this->urlEncode($strFile);

								if ( substr($objFile->mime, 0, 6) == 'image/' )
								{
									$arrTemp[$keyTemp]['display'] = 'image';
								}
								else
								{
									$size = ' ('.number_format(($objFile->filesize/1024), 1, $GLOBALS['TL_LANG']['MSC']['decimalSeparator'], $GLOBALS['TL_LANG']['MSC']['thousandsSeparator']).' kB)';

									$href = preg_replace('@(\?|&amp;)download=.*?(&amp;|$)@si', '', $this->Environment->request);
									$href .= ((strpos($href, '?')>=1) ? '&amp;' : '?') . 'download=' . $this->intRecordId . '.' . $k;
									$href = ampersand($href);

									$arrTemp[$keyTemp]['display'] = 'download';
									$arrTemp[$keyTemp]['size'] = $size;
									$arrTemp[$keyTemp]['href'] = $href;
									$arrTemp[$keyTemp]['linkTitle'] = basename($objFile->basename);
									$arrTemp[$keyTemp]['icon'] = 'system/themes/' . $this->getTheme() . '/images/' . $objFile->icon;

								}
							}

						}
					}

					$arrFields[$class]['content'] = $arrTemp;
					$arrItem[$k]['content'] = $arrTemp;

					$arrFields[$class]['multiple'] = true;
					$arrFields[$class]['number_of_items'] = count($arrTemp);
					$arrItem[$k]['multiple'] = true;
					$arrItem[$k]['number_of_items'] = count($arrTemp);

					unset($arrTemp);
				}
			}
		}

		/**
		 * Prepare URL
		 */
		$strUrl = preg_replace('/\?.*$/', '', urldecode($this->Environment->request));
		$this->Template->url = $strUrl;
		$this->Template->listItem = $arrItem;
		$this->Template->record = $arrFields;
		$this->Template->recordID = $this->intRecordId;

		$this->Template->link_edit = $strLinkEdit;

		$this->Template->link_delete = $strLinkDelete;
		$this->Template->link_export = $strLinkExport;

		/**
		 * Comments
		 */
		if (!$this->efg_com_allow_comments || !in_array('comments', $this->Config->getActiveModules()))
		{
			$this->Template->allowComments = false;
			return;
		}

		$this->Template->allowComments = true;

		// Adjust the comments headline level
		$intHl = min(intval(str_replace('h', '', $this->hl)), 5);
		$this->Template->hlc = 'h' . ($intHl + 1);

		$this->import('Comments');
		$arrNotifies = array();

		// Notify system administrator
		if ($this->efg_com_notify != 'notify_author')
		{
			$arrNotifies[] = $GLOBALS['TL_ADMIN_EMAIL'];
		}

		// Notify author
		if ($this->efg_com_notify != 'notify_admin')
		{
			if (intval($objRecord->fd_user) > 0)
			{
				$objUser = $this->Database->prepare("SELECT email FROM tl_user WHERE id=?")
											->limit(1)
											->execute($objRecord->fd_user);
				if ($objUser->numRows)
				{
					$arrNotifies[] = $objUser->email;
				}
			}
			if (intval($objRecord->fd_member) > 0)
			{
				$objMember = $this->Database->prepare("SELECT email FROM tl_member WHERE id=?")
											->limit(1)
											->execute($objRecord->fd_member);
				if ($objMember->numRows)
				{
					$arrNotifies[] = $objMember->email;
				}
			}
		}

		$objConfig = new stdClass();

		$objConfig->perPage = $this->efg_com_per_page;
		$objConfig->order = $this->com_order;
		$objConfig->template = $this->com_template;
		$objConfig->requireLogin = $this->com_requireLogin;
		$objConfig->disableCaptcha = $this->com_disableCaptcha;
		$objConfig->bbcode = $this->com_bbcode;
		$objConfig->moderate = $this->com_moderate;

		$this->Comments->addCommentsToTemplate($this->Template, $objConfig, 'tl_formdata', $this->intRecordId, $arrNotifies);

	}

	/**
	 * Export a single record
	 */
	protected function exportSingleRecord($strExportMode='csv')
	{

		/**
		 * Prepare URL
		 */
		$strUrl = preg_replace('/\?.*$/', '', urldecode($this->Environment->request));
		$strUrlParams = '';
		$strUrlSuffix = $GLOBALS['TL_CONFIG']['urlSuffix'];

		$blnQuery = false;
		foreach (preg_split('/&(amp;)?/', urldecode($_SERVER['QUERY_STRING'])) as $fragment)
		{
			if (strlen($fragment))
			{
				if (strncasecmp($fragment, 'file', 5) !== 0 && strncasecmp($fragment, $this->strDetailKey, strlen($this->strDetailKey)) !== 0 && strncasecmp($fragment, 'order_by', 8) !== 0 && strncasecmp($fragment, 'sort', 4) !== 0 && strncasecmp($fragment, 'page', 4) !== 0)
				{
					$strUrlParams .= (!$blnQuery ? '' : '&amp;') . $fragment;
					$blnQuery = true;
				}
			}
		}

		$strVarConnector = $blnQuery ? '&amp;' : '?';

		// check record
		if (is_null($this->intRecordId) || intval($this->intRecordId)<1)
		{
			return;
		}

		// check access
		if (strlen($this->efg_list_access) && $this->efg_list_access != 'public' )
		{
			$objOwner = $this->Database->prepare("SELECT fd_member FROM tl_formdata WHERE id=?")
							->execute($this->intRecordId);

			$varOwner = $objOwner->fetchAssoc();
			if (!in_array(intval($varOwner['fd_member']), $this->arrAllowedOwnerIds))
			{
				$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i'), array('',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
				$this->redirect($strRed);
			}
		}


		// check export access
		$blnExportAllowed = false;
		if ($this->efg_fe_export_access == 'none')
		{
			$blnExportAllowed = false;
		}
		elseif($this->efg_fe_export_access == 'public')
		{
			$blnExportAllowed = true;
		}
		elseif (strlen($this->efg_fe_export_access) )
		{
			$objOwner = $this->Database->prepare("SELECT fd_member FROM tl_formdata WHERE id=?")
							->execute($this->intRecordId);
			$varOwner = $objOwner->fetchAssoc();
			if (in_array(intval($varOwner['fd_member']), $this->arrAllowedExportOwnerIds))
			{
				$blnExportAllowed = true;
			}
		}

		$allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

		$this->list_info = deserialize($this->list_info);
		$arrListFields = explode(',', $this->list_info);
		$strSep = '';

		// wildcards * and -
		if ($arrListFields[0] == '*')
		{
			$arrTempFields = array_merge($this->arrBaseFields, $this->arrDetailFields);
			foreach($arrListFields as $field)
			{
				if (substr($field,0,1) == '-')
				{
					$intKey = array_search(substr($field,1), $arrTempFields);
					if (!is_bool($intKey))
					{
						unset($arrTempFields[$intKey]);
					}
				}
			}
			$arrListFields = $arrTempFields;
		}



		$strQuery = "SELECT ";
		$strWhere = '';

		foreach($arrListFields as $field)
		{
			if ( in_array($field, $this->arrBaseFields) )
			{
				$strQuery .= $strSep . $field;
				$strSep = ', ';
			}
			if ( is_array($this->arrDetailFields) && count($this->arrDetailFields) && in_array($field, $this->arrDetailFields) )
			{
				$strQuery .= $strSep .'(SELECT value FROM tl_formdata_details WHERE ff_name="' .$field. '" AND pid=f.id ) AS `' . $field .'`';
				$strSep = ', ';
			}
		}

		$strQuery .= " FROM " . $this->list_table . " f";
		$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . "id=?";
		$strQuery .= $strWhere;

		$objRecords = $this->Database->prepare($strQuery)
									->limit(1)
									->execute($this->intRecordId);

		if ($objRecords->numRows < 1)
		{
			return;
		}

		$ignoreFields = array('id','alias','tstamp','sorting','ip','published');

		$showFields = array_diff($arrListFields, $ignoreFields);

		$intRowCounter = -1;

		$strExpEncl = '"';
		$strExpSep = '';

		$useFormValues = $this->FormData->arrStoreForms[substr($this->strFormKey, 3)]['useFormValues'];
		$useFieldNames = $this->FormData->arrStoreForms[substr($this->strFormKey, 3)]['useFieldNames'];

		$blnCustomXlsExport = false;
		$arrHookData = array();
		$arrHookDataColumns = array();

		if ($strExportMode=='xls')
		{
			// check for HOOK efgExportXls
			if (array_key_exists('efgExportXls', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['efgExportXls']))
			{
				$blnCustomXlsExport = true;
			}
			else
			{
				include(TL_ROOT.'/system/modules/efg/plugins/xls_export/xls_export.php');
			}
		}

		if ($strExportMode=='xls')
		{
			if (!$blnCustomXlsExport)
			{
				$xls = new xlsexport();

				$strXlsSheet = "Export";
				$xls->addworksheet($strXlsSheet);
			}

		}
		else // defaults to csv
		{
			header('Content-Type: appplication/csv; charset='.($this->blnExportUTF8Decode ? 'CP1252' : 'utf-8'));
			header('Content-Transfer-Encoding: binary');
			header('Content-Disposition: attachment; filename="export_' . $this->strFormKey . '_' . date("Ymd_His") .'.csv"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Expires: 0');
		}

		// List records
		if ($objRecords->numRows)
		{

			$result = $objRecords->fetchAllAssoc();

			// Process result and format values
			foreach ($result as $row)
			{

				$intRowCounter++;

				$args = array();

				if ($intRowCounter == 0)
				{

					if ($strExportMode == 'xls')
					{
						if (!$blnCustomXlsExport)
						{
							$xls->totalcol = count($showFields);
						}
					}

					$strExpSep = '';

					$intColCounter = -1;
					foreach ($showFields as $k=>$v)
					{
						if (in_array($v, $ignoreFields) )
						{
							continue;
						}

						$intColCounter++;

						if ($useFieldNames)
						{
							$strName = $v;
						}
						elseif ( strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['label'][0]) )
						{
							$strName = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['label'][0];
						}
						elseif ( strlen($GLOBALS['TL_LANG']['tl_formdata'][$v][0]) )
						{
							$strName = $GLOBALS['TL_LANG']['tl_formdata'][$v][0];
						}
						else
						{
							$strName = strtoupper($v);
						}

						if (strlen($strName))
						{
							$strName = $this->String->decodeEntities($strName);

							if ($this->blnExportUTF8Decode || ($strExportMode=='xls' && !$blnCustomXlsExport))
							{
								$strName = $this->convertEncoding($strName, $GLOBALS['TL_CONFIG']['characterSet'], 'CP1252');
							}
						}

						if ($strExportMode=='csv')
						{
							$strName = str_replace('"', '""', $strName);
							echo $strExpSep . $strExpEncl . str_replace('"', '""', $strName) . $strExpEncl;

							$strExpSep = ";";
						}
						if ($strExportMode=='xls')
						{
							if (!$blnCustomXlsExport)
							{
								$xls->setcell(array("sheetname" => $strXlsSheet,"row" => $intRowCounter, "col" => $intColCounter, "data" => $strName, "fontweight" => XLSFONT_BOLD, "vallign" => XLSXF_VALLIGN_TOP, "fontfamily" => XLSFONT_FAMILY_NORMAL));
								$xls->setcolwidth($strXlsSheet,$intColCounter,0x1aff);
							}
							else
							{
								$arrHookDataColumns[$v] = $strName;
							}
						}

					}

					$intRowCounter++;

					if ($strExportMode=='csv')
					{
						echo "\n";
					}

				}

				$strExpSep = '';

				$intColCounter = -1;

				// Prepare field value
				foreach ($showFields as $k=>$v)
				{

					if (in_array($v, $ignoreFields) )
					{
						continue;
					}

					$intColCounter++;

					$strVal = '';
					$strVal = $row[$v];

					if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['rgxp'] == 'date')
					{
						$strVal = ( $row[$v] ? date($GLOBALS['TL_CONFIG']['dateFormat'], $row[$v]) : '' );
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['rgxp'] == 'time')
					{
						$strVal = ( $row[$v] ? date($GLOBALS['TL_CONFIG']['timeFormat'], $row[$v]) : '' );
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['rgxp'] == 'datim')
					{
						$strVal = ( $row[$v] ? date($GLOBALS['TL_CONFIG']['datimFormat'], $row[$v]) : '' );
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['multiple'])
					{
						if ($useFormValues == 1)
						{
							// single value checkboxes don't have options
							if ((is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) && count($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) > 0))
							{
								$strVal = strlen($row[$v]) ? key($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) : '';
							}
							else
							{
								$strVal = $row[$v];
							}
						}
						else
						{
							$strVal = strlen($row[$v]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['label'][0] : '-';
						}
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'radio'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'efgLookupRadio'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'select'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'efgLookupSelect'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'checkbox'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'efgLookupCheckbox')
					{
						// take the assigned value instead of the user readable output
						if ($useFormValues == 1)
						{
							if ((strpos($row[$v], "|") == FALSE) && (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) && count($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) > 0))
							{
								$options = array_flip($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']);
								$strVal = $options[$row[$v]];
							}
							else
							{
								if ((is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) && count($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']) > 0))
								{
									$options = array_flip($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['options']);
									$tmparr = split("\\|", $row[$v]);
									$fieldvalues = array();
									foreach ($tmparr as $valuedesc)
									{
										array_push($fieldvalues, $options[$valuedesc]);
									}
									$strVal = join(",\n", $fieldvalues);
								}
								else
								{
									$strVal = strlen($row[$v]) ? str_replace('|', ",\n", $row[$v]) : '';
								}
							}
						}
						else
						{
							$strVal = strlen($row[$v]) ? str_replace('|', ",\n", $row[$v]) : '';
						}
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'fileTree')
					{
						if (is_string($row[$v]) && strpos($row[$v], '|') !== false)
						{
							$strVal = implode(",\n", explode('|', $row[$v]));
						}
						else
						{
							$strVal = implode(",\n", deserialize($row[$v], true));
						}
					}
					else
					{
						$row_v = deserialize($row[$v]);

						if (is_array($row_v))
						{
							$args_k = array();

							foreach ($row_v as $option)
							{
								$args_k[] = strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$option]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$option] : $option;
							}

							$args[$k] = implode(",\n", $args_k);
						}
						elseif (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$row[$v]]))
						{
							$args[$k] = is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$row[$v]]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$row[$v]][0] : $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['reference'][$row[$v]];
						}
						else
						{
							$args[$k] = $row[$v];
						}
						$strVal = is_null($args[$k]) ? $args[$k] : vsprintf('%s', $args[$k]);
					}

					if (in_array($v, $this->arrBaseFields) || in_array($v, $this->arrOwnerFields))
					{
						if ($v == 'fd_member')
						{
							$strVal = $this->arrMembers[intval($row[$v])];
						}
						if ($v == 'fd_user')
						{
							$strVal = $this->arrUsers[intval($row[$v])];
						}
						if ($v == 'fd_member_group')
						{
							$strVal = $this->arrMemberGroups[intval($row[$v])];
						}
						if ($v == 'fd_user_group')
						{
							$strVal = $this->arrUserGroups[intval($row[$v])];
						}
					}

					if (strlen($strVal))
					{
						$strVal = $this->String->decodeEntities($strVal);
						$strVal = preg_replace(array('/<br.*\/*>/si'), array("\n"), $strVal);

						if ($this->blnExportUTF8Decode || ($strExportMode=='xls' && !$blnCustomXlsExport))
						{
							$strVal = $this->convertEncoding($strVal, $GLOBALS['TL_CONFIG']['characterSet'], 'CP1252');
						}
					}


					if ($strExportMode=='csv')
					{
						$strVal = str_replace('"', '""', $strVal);
						echo $strExpSep . $strExpEncl . $strVal . $strExpEncl;

						$strExpSep = ";";
					}
					if ($strExportMode=='xls')
					{
						if (!$blnCustomXlsExport)
						{
							$xls->setcell(array("sheetname" => $strXlsSheet,"row" => $intRowCounter, "col" => $intColCounter, "data" => $strVal, "vallign" => XLSXF_VALLIGN_TOP, "fontfamily" => XLSFONT_FAMILY_NORMAL));
						}
						else
						{
							$arrHookData[$intRowCounter][$v] = $strVal;
						}
					}

				}

				if ($strExportMode=='csv')
				{
					$strExpSep = '';
					echo "\n";
				}

			} // foreach ($result as $row)

		} // if objRow->numRows

		if ($strExportMode=='xls')
		{
			if (!$blnCustomXlsExport)
			{
				$xls->sendfile("export_" . $this->strFormKey . "_" . date("Ymd") . ".xls");
				exit;
			}
			else
			{
				foreach ($GLOBALS['TL_HOOKS']['efgExportXls'] as $key => $callback)
				{
					$this->import($callback[0]);
					$res = $this->$callback[0]->$callback[1]($arrHookDataColumns, $arrHookData);
				}
			}
		}
		exit;

	}

	/**
	 * Edit a record
	 */
	protected function editSingleRecord()
	{
		/**
		 * Prepare URL
		 */
		$strUrl = preg_replace('/\?.*$/', '', urldecode($this->Environment->request));
		$strUrlParams = '';
		$strUrlSuffix = $GLOBALS['TL_CONFIG']['urlSuffix'];

		$blnQuery = false;

		foreach (preg_split('/&(amp;)?/', $_SERVER['QUERY_STRING']) as $fragment)
		{
			if (strlen($fragment))
			{
				if (strncasecmp($fragment, $this->strDetailKey, strlen($this->strDetailKey)) !== 0 && strncasecmp($fragment, 'act', 3) !== 0 && strncasecmp($fragment, 'order_by', 8) !== 0 && strncasecmp($fragment, 'sort', 4) !== 0 && strncasecmp($fragment, 'page', 4) !== 0)
				{
					$strUrlParams .= (!$blnQuery ? '' : '&amp;') . $fragment;
					$blnQuery = true;
				}
			}
		}

		$strVarConnector = $blnQuery ? '&amp;' : '?';
		// check record
		if ($this->intRecordId === null || intval($this->intRecordId)<1)
		{
			unset($_GET[$this->strDetailKey]);
			unset($_GET['act']);

			$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i'), array('',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
			$this->redirect($strRed);
		}

		// Check Owner and Alias
		$objOwner = $this->Database->prepare("SELECT fd_member,alias FROM tl_formdata WHERE id=?")
							->execute($this->intRecordId);
		$varOwner = $objOwner->fetchAssoc();

		// check access
		if (!empty($this->efg_list_access) && $this->efg_list_access != 'public' )
		{
			if (!in_array(intval($varOwner['fd_member']), $this->arrAllowedOwnerIds))
			{
				$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i', '/act=edit/i'), array('','',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
				$this->redirect($strRed);
			}
		}

		// check edit access
		$blnEditAllowed = false;
		if ($this->efg_fe_edit_access == 'none')
		{
			$blnEditAllowed = false;
		}
		elseif($this->efg_fe_edit_access == 'public')
		{
			$blnEditAllowed = true;
		}
		elseif (!empty($this->efg_fe_edit_access) )
		{
			if (in_array(intval($varOwner['fd_member']), $this->arrAllowedEditOwnerIds))
			{
				$blnEditAllowed = true;
			}
		}
		if ($blnEditAllowed == false)
		{
			$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i', '/act=edit/i'), array('','',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
			$this->redirect($strRed);
		}

		$intListingId = 0;
		if($this->id)
		{
			$intListingId = intval($this->id);
		}

		$strForm = '';
		$intFormId = 0;

		// Fallback template
		if (!strlen($this->list_edit_layout))
		{
			$this->list_edit_layout = 'edit_fd_default';
		}

		// get the form
		$objCheckRecord = $this->Database->prepare("SELECT form FROM tl_formdata WHERE id=?")
								->limit(1)
								->execute($this->intRecordId);
		if ($objCheckRecord->numRows == 1)
		{
			$strForm = $objCheckRecord->form;
		}
		if (strlen($strForm))
		{
			$objForm = $this->Database->prepare("SELECT * FROM tl_form WHERE title=?")
							->limit(1)
							->execute($strForm);
			if ($objForm->numRows == 1)
			{
				$intFormId = $objForm->id;
			}
		}

		// get ContentElement holding the form
		$objFormElement = $this->Database->prepare("SELECT * FROM tl_content WHERE form=?")
				->limit(1)
				->execute($intFormId);

		if ($objFormElement->numRows<1)
		{
			$this->log("Could not find a ContentElement containing the form \"".$strForm."\"", 'ModuleFormdataListing editSingleRecord()', 'ERROR');

			$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i', '/act=edit/i'), array('','',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
			$this->redirect($strRed);
		}

		$this->Template = new FrontendTemplate($this->list_edit_layout);

		$arrRecordFields = array_merge($this->arrBaseFields, $this->arrDetailFields);

		$strQuery = "SELECT ";
		$strWhere = '';
		$strSep = '';

		foreach($arrRecordFields as $field)
		{
			if (in_array($field, $this->arrBaseFields))
			{
				$strQuery .= $strSep . $field;
				$strSep = ', ';
			}
			if (is_array($this->arrDetailFields) && count($this->arrDetailFields) && in_array($field, $this->arrDetailFields))
			{
				$strQuery .= $strSep .'(SELECT value FROM tl_formdata_details WHERE ff_name="' .$field. '" AND pid=f.id ) AS `' . $field . '`';
				$strSep = ', ';
			}

		}

		$strQuery .= " FROM " . $this->list_table . " f";
		$strWhere .= (strlen($strWhere) ? " AND " : " WHERE ") . "id=?";
		$strQuery .= $strWhere;

		$objRecord = $this->Database->prepare($strQuery)
									->limit(1)
									->execute($this->intRecordId);
		if ($objRecord->numRows < 1)
		{
			return;
		}
		$_SESSION['EFP']['LISTING_MOD']['id'] = $intListingId;

		if ($objFormElement->numRows == 1)
		{
			$this->Template->editform = $this->generateEditForm($objFormElement, $objRecord);
		}
	}

	protected function deleteSingleRecord()
	{

		$intDeleteId = 0;

		$this->import('FrontendUser', 'Member');


		/**
		 * Prepare URL
		 */
		$strUrl = preg_replace('/\?.*$/', '', urldecode($this->Environment->request));
		$strUrlParams = '';
		$strUrlSuffix = $GLOBALS['TL_CONFIG']['urlSuffix'];

		$blnQuery = false;

		foreach (preg_split('/&(amp;)?/', $_SERVER['QUERY_STRING']) as $fragment)
		{
			if (strlen($fragment))
			{
				if (strncasecmp($fragment, 'act', 3) !== 0 &&  strncasecmp($fragment, 'order_by', 8) !== 0 && strncasecmp($fragment, 'sort', 4) !== 0 && strncasecmp($fragment, 'page', 4) !== 0)
				{
					$strUrlParams .= (!$blnQuery ? '' : '&amp;') . $fragment;
					$blnQuery = true;
				}
			}
		}

		$strVarConnector = $blnQuery ? '&amp;' : '?';

		// check record
		if (is_null($this->intRecordId) || intval($this->intRecordId)<1)
		{
			unset($_GET[$this->strDetailKey]);
			unset($_GET['act']);

			$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i', 'act=delete'), array('','',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
			$this->redirect($strRed);
		}

		// Check Owner and Alias
		$objOwner = $this->Database->prepare("SELECT fd_member,alias FROM tl_formdata WHERE id=?")
							->execute($this->intRecordId);
		$varOwner = $objOwner->fetchAssoc();

		// check list access
		if (strlen($this->efg_list_access) && $this->efg_list_access != 'public' )
		{
			if (!in_array(intval($varOwner['fd_member']), $this->arrAllowedOwnerIds))
			{
				$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i', '/act=delete/i'), array('','',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
				$this->redirect($strRed);
			}
		}

		// check delete access
		$blnDeleteAllowed = false;
		if ($this->efg_fe_delete_access == 'none')
		{
			$blnEditAllowed = false;
		}
		elseif($this->efg_fe_delete_access == 'public')
		{
			$blnDeleteAllowed = true;
			$intDeleteId = intval($this->intRecordId);
		}
		elseif (strlen($this->efg_fe_delete_access) )
		{
			if (intval($varOwner['fd_member'])>0 && intval($varOwner['fd_member']) === intval($this->Member->id) )
			{
				$blnDeleteAllowed = true;
				$intDeleteId = intval($this->intRecordId);
			}
		}

		if ($blnDeleteAllowed == false)
		{
			$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i', '/act=delete/i'), array('','',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
			$this->redirect($strRed);
		}

		if ($blnDeleteAllowed)
		{
			$this->Database->prepare("DELETE FROM tl_formdata_details WHERE pid=?")
				->execute(array($intDeleteId));
			$this->Database->prepare("DELETE FROM tl_formdata WHERE id=?")
				->execute(array($intDeleteId));
		}

		$_SESSION['EFP']['LISTING_MOD']['id'] = $this->id;

		// redirect to list
		$strRed = preg_replace(array('/\/'.$this->strDetailKey.'\/'.$this->Input->get($this->strDetailKey).'/i', '/'.$this->strDetailKey.'='.$this->Input->get($this->strDetailKey).'/i', '/act=delete/i'), array('','',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
		$this->redirect($strRed);

	}

	/**
	 * Generate frontend editing form
	 * @return string
	 */
	// public function generateEditForm($objFormElement, $arrRecord)
	public function generateEditForm($objFormElement, $objRecord)
	{
		if (TL_MODE == 'BE')
		{
			return '';
		}

		$strClass = $this->findContentElement($objFormElement->type);
		$objFormElement->typePrefix = 'ce_';

		$this->EditForm = new ExtendedForm($objFormElement);
		$this->EditForm->objEditRecord = $objRecord;

		return $this->EditForm->generate();

	}

	/**
	 * Format a value
	 * @param mixed
	 * @return mixed
	 */
	public function formatValue($k, $value)
	{
		$value = deserialize($value);

		$rgxp = '';
		if ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'] )
		{
			$rgxp = $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['rgxp'];
		}
		else
		{
			$rgxp = $this->arrFF[$k]['rgxp'];
		}

		global $objPage;

		// Array
		if (is_array($value))
		{
			$value = implode(', ', $value);
		}

		// Date and time
		elseif ($value && $rgxp == 'date')
		{
			$value = $this->parseDate((!empty($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['dateFormat']) ? $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['eval']['dateFormat'] : $objPage->dateFormat), $value);
		}

		// Time
		elseif ($value && $rgxp == 'time')
		{
			$value = $this->parseDate($objPage->timeFormat, $value);
		}

		// Date and time
		elseif ($value && $rgxp == 'datim')
		{
			$value = $this->parseDate($objPage->datimFormat, $value);
		}
		elseif ($value && ($GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType']=='checkbox'
				|| $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType']=='efgLookupCheckbox'
				|| $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType']=='select'
				|| $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType']=='conditionalselect'
				|| $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType']=='efgLookupSelect'
				|| $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType']=='radio'
				|| $GLOBALS['TL_DCA'][$this->list_table]['fields'][$k]['inputType']=='fileTree')
			)
		{
			$value = str_replace('|', ', ', $value);
		}

		// owner fields fd_member, fd_user
		if (in_array($k, $this->arrBaseFields) && in_array($k, $this->arrOwnerFields))
		{
			if ($k == 'fd_member')
			{
				$value = $this->arrMembers[$value];
			}
			elseif ($k == 'fd_user')
			{
				$value = $this->arrUsers[$value];
			}
			elseif ($k == 'fd_member_group')
			{
				$value = $this->arrMemberGroups[$value];
			}
			elseif ($k == 'fd_user_group')
			{
				$value = $this->arrUserGroups[$value];
			}
		}

		// URLs
		if ($value && $rgxp == 'url' && preg_match('@^(https?://|ftp://)@i', $value))
		{
			$value = '<a href="' . $value . '"' . (($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"') . '>' . $value . '</a>';
			return $value;
		}

		// E-mail addresses
		if ($value && ($rgxp == 'email' || strpos($this->arrFF[$k]['name'], 'mail') !== false || strpos($k, 'mail') !== false ) )
		{
			$value = $this->String->encodeEmail($value);
			$value = '<a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;' . $value . '">' . $value . '</a>';
			return $value;
		}

		if (strlen($value))
		{
			$value = $this->String->decodeEntities($value);
			$value = ampersand($value);

			if (!is_bool(strpos($value, "\n")))
			{
				$strVal = $value;
				$strVal = preg_replace('/(<\/|<)(h\d|p|div|ul|ol|li)([^>]*)(>)(\n)/si', "\\1\\2\\3\\4", $strVal);
				$strVal = nl2br($strVal);
				$strVal = preg_replace('/(<\/)(h\d|p|div|ul|ol|li)([^>]*)(>)/si', "\\1\\2\\3\\4\n", $strVal);
				$value = $strVal;
				unset($strVal);
			}

		}

		return $value;
	}

	/**
	 * get all members (FE)
	 */
	protected function getMembers()
	{
		if (!$this->arrMembers)
		{
			$members = array();
			$objMembers = $this->Database->prepare("SELECT id, CONCAT(firstname,' ',lastname) AS name,groups,login,username,locked,disable,start,stop FROM tl_member ORDER BY name ASC")
								->execute();
			$members[] = '-';
			if ($objMembers->numRows)
			{
				while ($objMembers->next())
				{
					$k = $objMembers->id;
					$v = $objMembers->name;
					$members[$k] = $v;
				}
			}
			$this->arrMembers = $members;
		}
	}

	/**
	 * get all users (BE)
	 */
	protected function getUsers()
	{
		if (!$this->arrUsers)
		{
			$users = array();
			$objUsers = $this->Database->prepare("SELECT id,username,name,locked,disable,start,stop,admin,groups,modules,inherit,fop FROM tl_user ORDER BY name ASC")
								->execute();
			$users[] = '-';
			if ($objUsers->numRows)
			{
				while ($objUsers->next())
				{
					$k = $objUsers->id;
					$v = $objUsers->name;
					$users[$k] = $v;
				}
			}
			$this->arrUsers = $users;
		}
	}

	/**
	 * get all member groups (FE)
	 */
	protected function getMemberGroups()
	{
		if (!$this->arrMemberGroups)
		{
			$groups = array();
			$objGroups = $this->Database->prepare("SELECT id,name FROM tl_member_group ORDER BY name ASC")
								->execute();
			$groups[] = '-';
			if ($objGroups->numRows)
			{
				while ($objGroups->next())
				{
					$k = $objGroups->id;
					$v = $objGroups->name;
					$groups[$k] = $v;
				}
			}
			$this->arrMemberGroups = $groups;
		}
	}

	/**
	 * get all user groups (BE)
	 */
	protected function getUserGroups()
	{
		if (!$this->arrUserGroups)
		{
			$groups = array();
			$objGroups = $this->Database->prepare("SELECT id,name FROM tl_user_group ORDER BY name ASC")
								->execute();
			$groups[] = '-';
			if ($objGroups->numRows)
			{
				while ($objGroups->next())
				{
					$k = $objGroups->id;
					$v = $objGroups->name;
					$groups[$k] = $v;
				}
			}
			$this->arrUserGroups = $groups;
		}
	}

	/**
	 * Convert encoding
	 * @return String
	 * @param $strString String to convert
	 * @param $from charset to convert from
	 * @param $to charset to convert to
	 */
	public function convertEncoding($strString, $from, $to)
	{
		if (USE_MBSTRING)
		{
			@mb_substitute_character('none');
			return @mb_convert_encoding($strString, $to, $from);
		}
		elseif (function_exists('iconv'))
		{
			if (strlen($iconv = @iconv($from, $to . '//IGNORE', $strString)))
			{
				return $iconv;
			}
			else
			{
				return @iconv($from, $to, $strString);
			}
		}
		return $strString;
	}

	protected function replaceWhereTags($strBuffer)
	{

		$tags = array();
		preg_match_all('/{{[^{}]+}}/i', $strBuffer, $tags);

		// Replace tags
		foreach ($tags[0] as $tag)
		{
			$elements = explode('::', trim(str_replace(array('{{', '}}'), array('', ''), $tag)));

			switch (strtolower($elements[0]))
			{
				case 'input':
					$strKey = $elements[1];
					$strNewVal = '';

					$strVal = $this->Input->get($strKey);
					if (!strlen($strVal))
					{
						$strVal = $this->Input->post($strKey);
					}
					if (!strlen($strVal))
					{
						$strVal = $this->Input->cookie($strKey);
					}

					if (strlen($strVal))
					{
						$strNewVal = preg_replace(array('/\[&\]/i'), array('&'), $strVal);
					}

					$strBuffer = str_replace($tag, $strNewVal, $strBuffer);
					break;

				default:
					break;
			}
		}

		return $strBuffer;

	}

	private function array_filter_like($arrInput, $varSearch)
	{
		$arrRet = array(-1 => "-");

		if (!is_array($arrInput) || !count($arrInput))
		{
			return $arrRet;
		}

		foreach ($arrInput as $k=>$v)
		{
			if (!is_bool(mb_stripos($v, $varSearch)))
			{
				$arrRet[$k] = $v;
			}
		}

		return $arrRet;
	}

}