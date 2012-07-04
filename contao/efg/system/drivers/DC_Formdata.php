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
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 * @filesource
 */


/**
 * Class DC_Formdata
 * modified version of DC_Table by Leo Feyer
 *
 * Provide methods to modify data stored in tables tl_formdata and tl_formdata_details.
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */
class DC_Formdata extends DataContainer implements listable, editable
{

	/**
	 * Name of the parent table
	 * @param string
	 */
	protected $ptable;

	/**
	 * Names of the child tables
	 * @param array
	 */
	protected $ctable;

	/**
	 * ID of the current record
	 * @param integer
	 */
	protected $id;

	/**
	 * IDs of all root records
	 * @param array
	 */
	protected $root;

	/**
	 * ID of the button container
	 * @param string
	 */
	protected $bid;

	/**
	 * Limit (database query)
	 * @param string
	 */
	protected $limit;

	/**
	 * First sorting field
	 * @param string
	 */
	protected $firstOrderBy;

	/**
	 * Order by (database query)
	 * @param array
	 */
	protected $orderBy = array();

	/**
	 * Fields of a new or duplicated record
	 * @param array
	 */
	protected $set = array();

	/**
	 * IDs of all records that are currently displayed
	 * @param array
	 */
	protected $current = array();

	/**
	 * Show the current table as tree
	 * @param boolean
	 */
	protected $treeView = false;

	/**
	 * True if a new version has to be created
	 * @param boolean
	 */
	protected $blnCreateNewVersion = false;

	/**
	 * True if one of the form fields is uploadable
	 * @param boolean
	 */
	protected $blnUploadable = false;

	/**
	 * Related form, like fd_frm_contact
	 * @param string
	 */
	protected $strFormKey;

	/**
	 * Related form filter key, name of field in table tl_formdata holding form-identifier
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

	protected $arrFormsDcaKey = null;

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
	 * Detail fields names in table tl_formdata_details
	 * @param mixed
	 */
	protected $arrDetailFields = null;

	/**
	 * Detail fields in table tl_formdata_details
	 */
	protected $arrDetailFieldsObj = null;

	/**
	 * Fields available for import field mapping
	 */
	protected $arrImportableFields = null;

	/**
	 * Fields not available for import
	 */
	protected $arrImportIgnoreFields = null;

	/**
	 * Fields to ignore on export
	 */
	protected $arrExportIgnoreFields = null;

	/**
	 * Sql statements for detail fields
	 * @param mixed
	 */
	protected $arrSqlDetails;

	protected $arrMembers = null;

	protected $arrUsers = null;

	protected $arrMemberGroups = null;

	protected $arrUserGroups = null;

	// convert UTF8 to cp1251 on CSV-/XLS-Export
	protected $blnExportUTF8Decode = true;

	/**
	 * Initialize the object
	 * @param string
	 */
	public function __construct($strTable)
	{
		parent::__construct();
		$this->intId = $this->Input->get('id');

		// Clear the clipboard
		if (isset($_GET['clipboard']))
		{
			$this->Session->set('CLIPBOARD', array());
			$this->redirect($this->getReferer());
		}

		$this->import('String');
		$this->loadDataContainer('tl_form_field');
		$this->import('FormData');

		// in Backend: Check BE User, Admin...
		if (TL_MODE == 'BE' || BE_USER_LOGGED_IN)
		{
			$this->import('BackendUser', 'User');
		}

		// in Frontend:
		if (TL_MODE == 'FE')
		{
			$this->import('FrontendUser', 'Member');
		}

		if ($this->Input->get('key') == 'export')
		{
			$this->strMode = 'export';
		}

		if ($this->Input->get('key') == 'exportxls')
		{
			$this->strMode = 'exportxls';
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

		// get all forms marked to store data
		$objForms = $this->Database->prepare("SELECT id,title,formID,useFormValues,useFieldNames,efgAliasField FROM tl_form WHERE storeFormdata=?")
										->execute("1");
		if ( !$this->arrStoreForms )
		{
			while ($objForms->next())
			{
				if (strlen($objForms->formID))
				{
					$varKey = $objForms->formID;
				}
				else
				{
					$varKey = str_replace('-', '_', standardize($objForms->title));
				}
				$this->arrStoreForms[$varKey] = $objForms->row();
				$this->arrFormsDcaKey[$varKey] = $objForms->title;
			}
		}

		// all field names of table tl_formdata
		foreach ($this->Database->listFields('tl_formdata') as $arrField)
		{

			if ($arrField['type'] != 'index')
			{
				$this->arrBaseFields[] = $arrField['name'];
			}
		}
		$this->arrBaseFields = array_unique($this->arrBaseFields);

		$this->arrOwnerFields = array('fd_member','fd_user','fd_member_group','fd_user_group');

		$this->getMembers();
		$this->getUsers();
		$this->getMemberGroups();
		$this->getUserGroups();

		// Check whether the table is defined
		if ($strTable == '' || !isset($GLOBALS['TL_DCA'][$strTable]))
		{
			$this->log('Could not load the data container configuration for "' . $strTable . '"', 'DC_Formdata __construct()', TL_ERROR);
			trigger_error('Could not load the data container configuration', E_USER_ERROR);
		}

		// Set IDs and redirect
		if ($this->Input->post('FORM_SUBMIT') == 'tl_select')
		{
			$ids = deserialize($this->Input->post('IDS'));

			if (!is_array($ids) || empty($ids))
			{
				$this->reload();
			}

			$session = $this->Session->getData();
			$session['CURRENT']['IDS'] = deserialize($this->Input->post('IDS'));
			$this->Session->setData($session);

			if (isset($_POST['edit']))
			{
				$this->redirect(str_replace('act=select', 'act=editAll', $this->Environment->request));
			}
			elseif (isset($_POST['delete']))
			{
				$this->redirect(str_replace('act=select', 'act=deleteAll', $this->Environment->request));
			}
			elseif (isset($_POST['override']))
			{
				$this->redirect(str_replace('act=select', 'act=overrideAll', $this->Environment->request));
			}
			elseif (isset($_POST['cut']) || isset($_POST['copy']))
			{
				$arrClipboard = $this->Session->get('CLIPBOARD');

				$arrClipboard[$strTable] = array
				(
					'id' => $ids,
					'mode' => (isset($_POST['cut']) ? 'cutAll' : 'copyAll')
				);

				$this->Session->set('CLIPBOARD', $arrClipboard);
				$this->redirect($this->getReferer());
			}
		}

		$this->strTable = $strTable;
		$this->ptable = $GLOBALS['TL_DCA'][$this->strTable]['config']['ptable'];
		$this->ctable = $GLOBALS['TL_DCA'][$this->strTable]['config']['ctable'];
		$this->treeView = false;
		$this->root = null;

		// Key of a form or '' for no specific form
		$this->strFormKey = '';
		$this->strFormFilterKey = '';
		$this->strFormFilterValue = '';

		if ($this->Input->get('do'))
		{
			if ($this->Input->get('do') != 'feedback' )
			{
				if (array_key_exists($this->Input->get('do'), $GLOBALS['BE_MOD']['formdata']) )
				{
					$this->strFormKey = $this->Input->get('do');
					$this->strFormFilterKey = 'form';
					$this->strFormFilterValue = $this->arrStoreForms[str_replace('fd_', '', $this->strFormKey)]['title'];
					$this->sqlFormFilter = ' AND ' . $this->strFormFilterKey . '=\'' . $this->strFormFilterValue . '\' ';

					// add sql where condition 'form'=TILTE_OF_FORM
					if ($this->strTable == 'tl_formdata')
					{
						$this->procedure[] = $this->strFormFilterKey . '=?';
						$this->values[] = $this->strFormFilterValue;
					}
				}
			}
		}

		// Call onload_callback (e.g. to check permissions)
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onload_callback']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onload_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this);
				}
			}
		}

		// check names of detail fields
		// .. after call to onload_callback we have the form specific dca in $GLOBALS['TL_DCA'][$this->strTable]
		if (strlen($this->strFormKey))
		{
			$arrFFNames = array_keys($GLOBALS['TL_DCA'][$this->strTable]['fields']);
		}

		// get all FormField names of forms storing formdata
		else
		{
			$objFFNames = $this->Database->prepare("SELECT DISTINCT ff.name FROM tl_form_field ff, tl_form f WHERE (ff.pid=f.id) AND ff.name != '' AND f.storeFormdata=?")
											->execute("1");
			if ( $objFFNames->numRows)
			{
				$arrFFNames = $objFFNames->fetchEach('name');
			}
		}

		if (!empty($arrFFNames))
		{
			$this->arrDetailFields = array_diff($arrFFNames, $this->arrBaseFields, array('import_source'));
		}

		// store array of sql-stmts for detail fields
		if (!empty($this->arrDetailFields))
		{
			$this->arrSqlDetails = array();
			foreach ($this->arrDetailFields as $strFName)
			{
				$this->arrSqlDetails[$strFName] = '(SELECT value FROM tl_formdata_details WHERE ff_name=\'' .$strFName. '\' AND pid=f.id) AS `' . $strFName .'`';
			}
		}

		// Store the current referer
		if (!empty($this->ctable) && !$this->Input->get('act') && !$this->Input->get('key') && !$this->Input->get('token'))
		{
			$session = $this->Session->get('referer');
			$session[$this->strTable] = $this->Environment->requestUri;
			$this->Session->set('referer', $session);
		}
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
			case 'id':
				return $this->intId;
				break;

			case 'parentTable':
				return $this->ptable;
				break;

			case 'childTable':
				return $this->ctable;
				break;

			case 'rootIds':
				return $this->root;
				break;

			case 'createNewVersion':
				return $this->blnCreateNewVersion;
				break;

			case 'strFormFilterValue':
				return $this->strFormFilterValue;
				break;

			case 'arrFieldConfig':
				return $this->arrFieldConfig;
				break;

			default:
				return parent::__get($strKey);
				break;
		}
	}


	/**
	 * List all records of a particular table
	 * @return string
	 */
	public function showAll()
	{
		$return = '';
		$this->limit = '';
		$this->bid = 'tl_buttons';

		// Clean up old tl_undo and tl_log entries
		if ($this->strTable == 'tl_undo' && strlen($GLOBALS['TL_CONFIG']['undoPeriod']))
		{
			$this->Database->prepare("DELETE FROM tl_undo WHERE tstamp<?")
							->execute(intval(time() - $GLOBALS['TL_CONFIG']['undoPeriod']));
		}
		elseif ($this->strTable == 'tl_log' && strlen($GLOBALS['TL_CONFIG']['logPeriod']))
		{
			$this->Database->prepare("DELETE FROM tl_log WHERE tstamp<?")
							->execute(intval(time() - $GLOBALS['TL_CONFIG']['logPeriod']));
		}

		$this->reviseTable();

		// Add to clipboard
		if ($this->Input->get('act') == 'paste')
		{
			$arrClipboard = $this->Session->get('CLIPBOARD');

			$arrClipboard[$this->strTable] = array
			(
				'id' => $this->Input->get('id'),
				'childs' => $this->Input->get('childs'),
				'mode' => $this->Input->get('mode')
			);

			$this->Session->set('CLIPBOARD', $arrClipboard);
		}

		// Custom filter
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['filter']) && !empty($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['filter']))
		{
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['filter'] as $filter)
			{
				$this->procedure[] = $filter[0];
				$this->values[] = $filter[1];
			}
		}

		if ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 4)
		{
			$this->procedure[] = 'pid=?';
			$this->values[] = CURRENT_ID;
		}

		// Render view
		$return .= $this->panel();
		$return .= $this->listView();

		// Add another panel at the end of the page
		if (strpos($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['panelLayout'], 'limit') !== false && ($strLimit = $this->limitMenu(true)) != false)
		{
			$return .= '

<form action="'.ampersand($this->Environment->request, true).'" class="tl_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_filters_limit">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">

<div class="tl_panel_bottom">

<div class="tl_submit_panel tl_subpanel">
<input type="image" name="btfilter" id="btfilter" src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/reload.gif" class="tl_img_submit" title="' . $GLOBALS['TL_LANG']['MSC']['apply'] . '" alt="' . $GLOBALS['TL_LANG']['MSC']['apply'] . '">
</div>' . $strLimit . '

<div class="clear"></div>

</div>

</div>
</form>
';
		}

		// Store the current IDs
		$session = $this->Session->getData();
		$session['CURRENT']['IDS'] = $this->current;
		$this->Session->setData($session);

		return $return;
	}


	/**
	 * Return all non-excluded fields of a record as HTML table
	 * @return string
	 */
	public function show()
	{
		if (!strlen($this->intId))
		{
			return '';
		}

		$strFormFilter = ($this->strTable == 'tl_formdata' && strlen($this->strFormKey) ? $this->sqlFormFilter : '');
		$table_alias = ($this->strTable == 'tl_formdata' ? ' f' : '');

		$sqlQuery = "SELECT * " .(count($this->arrSqlDetails) > 0 ? ', '.implode(',' , array_values($this->arrSqlDetails)) : '') ." FROM " . $this->strTable . $table_alias;
		$sqlWhere = " WHERE id=?";
		if ( $sqlWhere != '')
		{
			$sqlQuery .= $sqlWhere;
		}

		$objRow = $this->Database->prepare($sqlQuery)
								 ->limit(1)
								 ->execute($this->intId);

		if ($objRow->numRows < 1)
		{
			return '';
		}

		$count = 1;
		$return = '';
		$row = $objRow->row();

		// Get all fields
		$fields = array_keys($row);
		$allowedFields = array('id', 'pid', 'sorting', 'tstamp');

		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields']))
		{
			$allowedFields = array_unique(array_merge($allowedFields, array_keys($GLOBALS['TL_DCA'][$this->strTable]['fields'])));
		}

		// Use the field order of the DCA file
		$fields = array_intersect($allowedFields, $fields);

		// Show all allowed fields
		foreach ($fields as $i)
		{
			if (!in_array($i, $allowedFields) || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['inputType'] == 'password' || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['eval']['doNotShow'] || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['eval']['hideInput'])
			{
				continue;
			}

			// Special treatment for table tl_undo
			if ($this->strTable == 'tl_undo' && $i == 'data')
			{
				continue;
			}

			$value = deserialize($row[$i]);
			$class = (($count++ % 2) == 0) ? ' class="tl_bg"' : '';

			// ignore display of empty detail fields if this is overall "feedback"
			if (empty($this->strFormKey) && in_array($i, $this->arrDetailFields) && empty($value))
			{
				continue;
			}

			// Get field value
			if (is_array($value))
			{
				foreach ($value as $kk=>$vv)
				{
					if (is_array($vv))
					{
						$vals = array_values($vv);
						$value[$kk] = $vals[0].' ('.$vals[1].')';
					}
				}

				$row[$i] = implode(', ', $value);
			}
			elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['eval']['rgxp'] == 'date')
			{
				$row[$i] = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $value);
			}
			elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['eval']['rgxp'] == 'time')
			{
				$row[$i] = $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $value);
			}
			elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['eval']['rgxp'] == 'datim' || in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['flag'], array(5, 6, 7, 8, 9, 10)) || $i == 'tstamp')
			{
				$row[$i] = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $value);
			}
			elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['eval']['multiple'])
			{
				$row[$i] = strlen($value) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['label'][0] : '-';
			}
			elseif (($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['inputType'] == 'checkbox'
					|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['inputType'] == 'efgLookupCheckbox'
					|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['inputType'] == 'select'
					|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['inputType'] == 'fp_preSelectMenu'
					|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['inputType'] == 'efgLookupSelect'
					|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['inputType'] == 'efgImageSelect'
					|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['inputType'] == 'fileTree')
					&& $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['eval']['multiple'])
			{
				$row[$i] = strlen($value) ? str_replace('|', ', ', $value) : $value;
			}
			elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['inputType'] == 'textarea' && ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['eval']['allowHtml'] || $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['eval']['preserveTags']))
			{
				$row[$i] = specialchars($value);
			}
			elseif (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['reference']))
			{
				$row[$i] = isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['reference'][$row[$i]]) ? ((is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['reference'][$row[$i]])) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['reference'][$row[$i]][0] : $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['reference'][$row[$i]]) : $row[$i];
			}
			elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['eval']['isAssociative'] || array_is_assoc($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['options']))
			{
				$row[$i] = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['options'][$row[$i]];
			}

			if (in_array($i, $this->arrBaseFields) || in_array($i, $this->arrOwnerFields))
			{
				if ($i == 'fd_member')
				{
					$row[$i] = $this->arrMembers[intval($value)];
				}
				elseif ($i == 'fd_user')
				{
					$row[$i] = $this->arrUsers[intval($value)];
				}
				elseif ($i == 'fd_member_group')
				{
					$row[$i] = $this->arrMemberGroups[intval($value)];
				}
				elseif ($i == 'fd_user_group')
				{
					$row[$i] = $this->arrUserGroups[intval($value)];
				}
			}

			// Replace foreign keys with their values
			// .. but not if foreignKey table is formdata table
			if (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['foreignKey']))
			{
				$chunks = explode('.', $GLOBALS['TL_DCA'][$this->strTable]['fields'][$i]['foreignKey']);

				if (substr($chunks[0], 0, 3) == 'fd_')
				{
					$row[$i] = $value;
				}
				else
				{
					$objKey = $this->Database->prepare("SELECT " . $chunks[1] . " FROM " . $chunks[0] . " WHERE id=?")
											 ->limit(1)
											 ->execute($row[$i]);
					if ($objKey->numRows)
					{
						$row[$i] = $objKey->$chunks[1];
					}
				}
			}

			// check multiline value
			if (!is_bool(strpos($row[$i], "\n")))
			{
				$strVal = $row[$i];
				$strVal = preg_replace('/(<\/|<)(h\d|p|div|ul|ol|li)([^>]*)(>)(\n)/si', "\\1\\2\\3\\4", $strVal);
				$strVal = nl2br($strVal);
				$strVal = preg_replace('/(<\/)(h\d|p|div|ul|ol|li)([^>]*)(>)/si', "\\1\\2\\3\\4\n", $strVal);
				$row[$i] = $strVal;
				unset($strVal);
			}

			// Get the field label
			if (isset($GLOBALS['TL_DCA'][$strTable]['fields'][$i]['label']))
			{
				$label = is_array($GLOBALS['TL_DCA'][$strTable]['fields'][$i]['label']) ? $GLOBALS['TL_DCA'][$strTable]['fields'][$i]['label'][0] : $GLOBALS['TL_DCA'][$strTable]['fields'][$i]['label'];
			}
			else
			{
				$label = is_array($GLOBALS['TL_LANG']['MSC'][$i]) ? $GLOBALS['TL_LANG']['MSC'][$i][0] : $GLOBALS['TL_LANG']['MSC'][$i];
			}

			if (!strlen($label))
			{
				$label = $i;
			}

			$return .= '
  <tr>
    <td'.$class.'><span class="tl_label">'.$label.': </span></td>
    <td'.$class.'>'.$row[$i].'</td>
  </tr>';
		}

		// Return table
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.sprintf($GLOBALS['TL_LANG']['MSC']['showRecord'], ($this->intId ? 'ID '.$this->intId : '')).'</h2>

<table class="tl_show">'.$return.'
</table>';
	}


	/**
	 * Insert a new row into a database table
	 * @param array
	 */
	public function create($set=array())
	{

		if (isset($this->strFormKey) && strlen($this->strFormKey))
		{
			$set['form'] = $this->arrStoreForms[str_replace('fd_', '', $this->strFormKey)]['title'];
			$set['date'] = time();
			$set['ip'] = $this->Environment->ip;

			if ($this->User && intval($this->User->id)>0)
			{
				$set['fd_user'] = intval($this->User->id);
			}

		}

		// Get all default values for the new entry
		foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'] as $k=>$v)
		{
			if (isset($v['default']))
			{
				if (!in_array($k, $this->arrBaseFields))
				{
					continue;
				}
				$this->set[$k] = is_array($v['default']) ? serialize($v['default']) : $v['default'];
			}
		}

		// Set passed values
		if (is_array($set) && !empty($set))
		{
			$this->set = array_merge($this->set, $set);
		}

		// Empty clipboard
		$arrClipboard = $this->Session->get('CLIPBOARD');
		$arrClipboard[$this->strTable] = array();
		$this->Session->set('CLIPBOARD', $arrClipboard);

		// Insert the record if the table is not closed and switch to edit mode
		if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['closed'])
		{
			$this->set['tstamp'] = 0;

			$objInsertStmt = $this->Database->prepare("INSERT INTO " . $this->strTable . " %s")
											->set($this->set)
											->execute();

			if ($objInsertStmt->affectedRows)
			{
				$s2e = $GLOBALS['TL_DCA'][$this->strTable]['config']['switchToEdit'] ? '&s2e=1' : '';
				$insertID = $objInsertStmt->insertId;

				foreach ($this->arrDetailFields as $strDetailField)
				{
					$strVal = '';
					$arrDetailSet = array(
						'pid' => $insertID,
						'tstamp' => time(),
						'ff_id' => $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strDetailField]['ff_id'],
						'ff_type' => $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strDetailField]['inputType'],
						'ff_label' => $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strDetailField]['label'][0] ,
						'ff_name' => $strDetailField,
						'ff_label' => $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strDetailField]['label'][0]
					);

   					// default value
   					if ( strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$strDetailField]['default']) )
   					{
   						$strVal = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strDetailField]['default'];
   					}

					// default value in case of field type checkbox, select, radio
   					if ( is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$strDetailField]['default']) && count($GLOBALS['TL_DCA'][$this->strTable]['fields'][$strDetailField]['default'])>0 )
   					{
   						$strVal = implode(',', $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strDetailField]['default']);
   					}

					$arrDetailSet['value'] = $strVal;

					$objInsertStmt = $this->Database->prepare("INSERT INTO tl_formdata_details %s")
											->set($arrDetailSet)
											->execute();
				}

				// Save new record in the session
				$new_records = $this->Session->get('new_records');
				$new_records[$this->strTable][] = $insertID;
				$this->Session->set('new_records', $new_records);

				// Call the oncreate_callback
				if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['oncreate_callback']))
				{
					foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['oncreate_callback'] as $callback)
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($this->strTable, $insertID, $this->set, $this);
					}
				}

				// Add a log entry
				$this->log('A new entry in table "'.$this->strTable.'" has been created (ID: '.$insertID.')', 'DC_Table create()', TL_GENERAL);
				$this->redirect($this->switchToEdit($insertID).$s2e);
			}
		}

		$this->redirect($this->getReferer());
	}


	/**
	 * Do nothing here
	 */
	public function cut() {}


	/**
	 * Do nothing here
	 */
	public function copy() {}


	/**
	 * Delete a record of the current table table and save it to tl_undo
	 * @param boolean
	 */
	public function delete($blnDoNotRedirect=false)
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notDeletable'])
		{
			$this->log('Table "'.$this->strTable.'" is not deletable', 'DC_Table delete()', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		if (!$this->intId)
		{
			$this->redirect($this->getReferer());
		}

		$data = array();
		$delete = array();

		// Do not save records from tl_undo itself
		if ($this->strTable == 'tl_undo')
		{
			$this->Database->prepare("DELETE FROM " . $this->strTable . " WHERE id=?")
							->limit(1)
							->execute($this->intId);

			$this->redirect($this->getReferer());
		}

		// If there is a PID field but no parent table
		if ($this->Database->fieldExists('pid', $this->strTable) && !strlen($this->ptable))
		{
			$delete[$this->strTable] = $this->getChildRecords($this->intId, $this->strTable);
			array_unshift($delete[$this->strTable], $this->intId);
		}
		else
		{
			$delete[$this->strTable] = array($this->intId);
		}

		// Delete all child records if there is a child table
		if (!empty($this->ctable))
		{
			foreach ($delete[$this->strTable] as $id)
			{
				$this->deleteChilds($this->strTable, $id, $delete);
			}
		}

		$affected = 0;

		// Save each record of each table
		foreach ($delete as $table=>$fields)
		{
			foreach ($fields as $k=>$v)
			{
				$objSave = $this->Database->prepare("SELECT * FROM " . $table . " WHERE id=?")
										->limit(1)
										->execute($v);

				if ($objSave->numRows)
				{
					$data[$table][$k] = $objSave->fetchAssoc();

					// Store the active record
					if ($table == $this->strTable && $v == $this->intId)
					{
						$this->objActiveRecord = $objSave;
					}
				}

				$affected++;
			}
		}

		$this->import('BackendUser', 'User');

		$objUndoStmt = $this->Database->prepare("INSERT INTO tl_undo (pid, tstamp, fromTable, query, affectedRows, data) VALUES (?, ?, ?, ?, ?, ?)")
									  ->execute($this->User->id, time(), $this->strTable, 'DELETE FROM '.$this->strTable.' WHERE id='.$this->intId, $affected, serialize($data));

		// Delete the records
		if ($objUndoStmt->affectedRows)
		{
			// Call ondelete_callback
			if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['ondelete_callback']))
			{
				foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['ondelete_callback'] as $callback)
				{
					if (is_array($callback))
					{
						$this->import($callback[0]);
						$this->$callback[0]->$callback[1]($this);
					}
				}
			}

			// Delete the records
			foreach ($delete as $table=>$fields)
			{
				foreach ($fields as $k=>$v)
				{
					$this->Database->prepare("DELETE FROM " . $table . " WHERE id=?")
								   ->limit(1)
								   ->execute($v);
				}
			}

			// Add a log entry unless we are deleting from tl_log itself
			if ($this->strTable != 'tl_log')
			{
				$this->log('DELETE FROM '.$this->strTable.' WHERE id='.$data[$this->strTable][0]['id'], 'DC_Table delete()', TL_GENERAL);
			}
		}

		if (!$blnDoNotRedirect)
		{
			$this->redirect($this->getReferer());
		}
	}


	/**
	 * Delete all records that are currently shown
	 */
	public function deleteAll()
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notDeletable'])
		{
			$this->log('Table "'.$this->strTable.'" is not deletable', 'DC_Table deleteAll()', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$session = $this->Session->getData();
		$ids = $session['CURRENT']['IDS'];

		if (is_array($ids) && strlen($ids[0]))
		{
			foreach ($ids as $id)
			{
				$this->intId = $id;
				$this->delete(true);
			}
		}

		$this->redirect($this->getReferer());
	}


	/**
	 * Recursively get all related table names and records
	 * @param string
	 * @param integer
	 * @param array
	 */
	public function deleteChilds($table, $id, &$delete)
	{
		$cctable = array();
		$ctable = $GLOBALS['TL_DCA'][$table]['config']['ctable'];

		if (!is_array($ctable))
		{
			return;
		}

		// Walk through each child table
		foreach ($ctable as $v)
		{
			$this->loadDataContainer($v);
			$cctable[$v] = $GLOBALS['TL_DCA'][$v]['config']['ctable'];

			$objDelete = $this->Database->prepare("SELECT id FROM " . $v . " WHERE pid=?")
										->execute($id);

			if (!$GLOBALS['TL_DCA'][$v]['config']['doNotDeleteRecords'] && strlen($v) && $objDelete->numRows)
			{
				foreach ($objDelete->fetchAllAssoc() as $row)
				{
					$delete[$v][] = $row['id'];

					if (!empty($cctable[$v]))
					{
						$this->deleteChilds($v, $row['id'], $delete);
					}
				}
			}
		}
	}


	/**
	 * Restore one or more deleted records
	 */
	public function undo()
	{
		$objRecords = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE id=?")
									 ->limit(1)
								 	 ->execute($this->intId);

		// Check whether there is a record
		if ($objRecords->numRows < 1)
		{
			$this->redirect($this->getReferer());
		}

		$error = false;
		$query = $objRecords->query;
		$data = deserialize($objRecords->data);

		if (!is_array($data))
		{
			$this->redirect($this->getReferer());
		}

		// Restore the data
		foreach ($data as $table=>$fields)
		{
			foreach ($fields as $row)
			{
				$restore = array();

				foreach ($row as $k=>$v)
				{
					$restore[$k] = $v;
				}

				$objInsertStmt = $this->Database->prepare("INSERT INTO " . $table . " %s")
												->set($restore)
												->execute();

				// Do not delete record from tl_undo if there is an error
				if ($objInsertStmt->affectedRows < 1)
				{
					$error = true;
				}
			}
		}

		// Add log entry and delete record from tl_undo if there was no error
		if (!$error)
		{
			$this->log('Undone '. $query, 'DC_Table undo()', TL_GENERAL);

			$this->Database->prepare("DELETE FROM " . $this->strTable . " WHERE id=?")
							->limit(1)
							->execute($this->intId);
		}

		$this->redirect($this->getReferer());
	}


	/**
	 * Do nothing here
	 */
	public function move() {}


	/**
	 * Autogenerate a form to edit the current database record
	 * @param integer
	 * @param integer
	 * @return string
	 */
	public function edit($intID=null, $ajaxId=null)
	{
		$strFormFilter = ($this->strTable == 'tl_formdata' && strlen($this->strFormKey) ? $this->sqlFormFilter : '');
		$table_alias = ($this->strTable == 'tl_formdata' ? ' f' : '');

		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notEditable'])
		{
			$this->log('Table "' . $this->strTable . '" is not editable', 'DC_Formdata edit()', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		if ($intID != '')
		{
			$this->intId = $intID;
		}

		$return = '';
		$this->values[] = $this->intId;
		$this->procedure[] = 'id=?';
		$this->blnCreateNewVersion = false;

		// Get current record
		$sqlQuery = "SELECT * " .(count($this->arrSqlDetails) > 0 ? ', '.implode(',' , array_values($this->arrSqlDetails)) : '') ." FROM " . $this->strTable . $table_alias;
		$sqlWhere = " WHERE id=?";
		if ( $sqlWhere != '')
		{
			$sqlQuery .= $sqlWhere;
		}

		$objRow = $this->Database->prepare($sqlQuery)
								 ->limit(1)
								 ->executeUncached($this->intId);

		// Redirect if there is no record with the given ID
		if ($objRow->numRows < 1)
		{
			$this->log('Could not load record ID "'.$this->intId.'" of table "'.$this->strTable.'"!', 'DC_Formdata edit()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$this->objActiveRecord = $objRow;
		$this->checkForTinyMce();

		// Build an array from boxes and rows
		$this->strPalette = $this->getPalette();
		$boxes = trimsplit(';', $this->strPalette);
		$legends = array();

		if (!empty($boxes))
		{
			foreach ($boxes as $k=>$v)
			{
				$eCount = 1;
				$boxes[$k] = trimsplit(',', $v);

				foreach ($boxes[$k] as $kk=>$vv)
				{
					if (preg_match('/^\[.*\]$/i', $vv))
					{
						++$eCount;
						continue;
					}

					if (preg_match('/^\{.*\}$/i', $vv))
					{
						$legends[$k] = substr($vv, 1, -1);
						unset($boxes[$k][$kk]);
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$vv]['exclude'] || !is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$vv]))
					{
						unset($boxes[$k][$kk]);
					}
				}

				// Unset a box if it does not contain any fields
				if (count($boxes[$k]) < $eCount)
				{
					unset($boxes[$k]);
				}
			}

			$class = 'tl_tbox';
			$fs = $this->Session->get('fieldset_states');
			$blnIsFirst = true;

			// Render boxes
			foreach ($boxes as $k=>$v)
			{
				$strAjax = '';
				$blnAjax = false;
				$legend = '';

				if (isset($legends[$k]))
				{
					list($key, $cls) = explode(':', $legends[$k]);
					$legend = "\n" . '<legend onclick="AjaxRequest.toggleFieldset(this,\'' . $key . '\',\'' . $this->strTable . '\')">' . (isset($GLOBALS['TL_LANG'][$this->strTable][$key]) ? $GLOBALS['TL_LANG'][$this->strTable][$key] : $key) . '</legend>';
				}

				if (isset($fs[$this->strTable][$key]))
				{
					$class .= ($fs[$this->strTable][$key] ? '' : ' collapsed');
				}
				else
				{
					$class .= (($cls && $legend) ? ' ' . $cls : '');
				}

				$return .= "\n\n" . '<fieldset' . ($key ? ' id="pal_'.$key.'"' : '') . ' class="' . $class . ($legend ? '' : ' nolegend') . '">' . $legend;

				// Build rows of the current box
				foreach ($v as $kk=>$vv)
				{
					if ($vv == '[EOF]')
					{
						if ($blnAjax && $this->Environment->isAjaxRequest)
						{
							return $strAjax . '<input type="hidden" name="FORM_FIELDS[]" value="'.specialchars($this->strPalette).'">';
						}

						$blnAjax = false;
						$return .= "\n" . '</div>';

						continue;
					}

					if (preg_match('/^\[.*\]$/i', $vv))
					{
						$thisId = 'sub_' . substr($vv, 1, -1);
						$blnAjax = ($ajaxId == $thisId && $this->Environment->isAjaxRequest) ? true : false;
						$return .= "\n" . '<div id="'.$thisId.'">';

						continue;
					}

					$this->strField = $vv;
					$this->strInputName = $vv;
					$this->varValue = $objRow->$vv;

					// Autofocus the first field
					if ($blnIsFirst && $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] == 'text')
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['autofocus'] = 'autofocus';
						$blnIsFirst = false;
					}

					// Call options_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options_callback']))
					{
						$strClass = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options_callback'][0];
						$strMethod = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options_callback'][1];

						$this->import($strClass);
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'] = $this->$strClass->$strMethod($this);
					}

					// Convert CSV fields (see #2890)
					if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['multiple'] && isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['csv']))
					{
						$this->varValue = trimsplit($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['csv'], $this->varValue);
					}

					/*
					// Call load_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback']))
					{
						foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback'] as $callback)
						{
							if (is_array($callback))
							{
								$this->import($callback[0]);
								$this->varValue = $this->$callback[0]->$callback[1]($this->varValue, $this);
							}
						}

						$this->objActiveRecord->{$this->strField} = $this->varValue;
					}
					*/

					// prepare values of special fields like radio, select and checkbox
					$strInputType = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'];

					// render inputType hidden as inputType text in Backend
					if ($strInputType == 'hidden')
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'text';
					}

					// field types radio, select, multi checkbox
					if (in_array($strInputType, array('radio', 'select', 'conditionalselect', 'countryselect'))
							|| ( $strInputType=='checkbox'  && $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['multiple'] ) )
					{
						if (in_array($this->strField, $this->arrBaseFields) && in_array($this->strField, $this->arrOwnerFields) )
						{
							if ($this->strField == 'fd_user')
							{
								if ($this->User && $this->User->id)
								{
									$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['default'] = $this->User->id;
								}
							}
						}
						elseif (!is_array($this->varValue))
						{

							// foreignKey fields
							if ($strInputType == 'select' && strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKey']))
							{
								// include blank Option
								$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['includeBlankOption'] = true;

								$arrKey = explode('.', $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKey']);
								$strForeignTable = $arrKey[0];
								$strForeignField = $arrKey[1];

								// WHERE condition for foreignKey
								$strForeignKeyCond = '';
								if (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKeyWhere']))
								{
									$strForeignKeyCond = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKeyWhere'];
								}

								// check if foreignKey table is a formdata table
								if (substr($strForeignTable, 0, 3) == 'fd_')
								{
									$strFormKey = substr($strForeignTable, 3);
									$strForeignDcaKey = $strForeignTable;
									$strForeignTable = 'tl_formdata';

									// backup current dca and load dca for foreign formdata
									$BAK_DCA = $GLOBALS['TL_DCA'][$this->strTable];
									$this->loadDataContainer($strForeignDcaKey);

									$strForeignField = $arrKey[1];
									$strForeignSqlField = '(SELECT value FROM tl_formdata_details WHERE ff_name="' .$strForeignField. '" AND pid=f.id ) AS `' . $strForeignField . '`';

									$sqlForeignFd = "SELECT f.id," . $strForeignSqlField . " FROM tl_formdata f, tl_formdata_details fd ";
									$sqlForeignFd .= "WHERE (f.id=fd.pid) AND f." . $GLOBALS['TL_DCA'][$strForeignTable]['tl_formdata']['formFilterKey'] . "='" . $GLOBALS['TL_DCA'][$strForeignTable]['tl_formdata']['formFilterValue'] . "' AND fd.ff_name='" . $strForeignField . "'";

									if (!empty($strForeignKeyCond))
									{
										$arrForeignKeyCond = preg_split('/([\s!=><]+)/', $strForeignKeyCond, -1, PREG_SPLIT_DELIM_CAPTURE);
										$strForeignCondField = $arrForeignKeyCond[0];
										unset($arrForeignKeyCond[0]);
										if (in_array($strForeignCondField, $GLOBALS['TL_DCA'][$strForeignTable]['tl_formdata']['baseFields']))
										{
											$sqlForeignFd .= ' AND f.' . $strForeignCondField . implode('', $arrForeignKeyCond);
										}
										if (in_array($strForeignCondField, $GLOBALS['TL_DCA'][$strForeignTable]['tl_formdata']['detailFields']))
										{
											$sqlForeignFd .= ' AND (SELECT value FROM tl_formdata_details WHERE ff_name="' .$strForeignCondField. '" AND pid=f.id ) ' . implode('', $arrForeignKeyCond);
										}
									}

									$objForeignFd = $this->Database->prepare($sqlForeignFd)->execute();

									// reset current dca
									$GLOBALS['TL_DCA'][$this->strTable] = $BAK_DCA;
									unset($BAK_DCA);

									if ($objForeignFd->numRows)
									{
										$arrForeignRecords = $objForeignFd->fetchAllAssoc();
										if (!empty($arrForeignRecords))
										{
											foreach ($arrForeignRecords as $arrForeignRecord )
											{
												$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'][$arrForeignRecord['id']] = $arrForeignRecord[$strForeignField] .  ' [~' . $arrForeignRecord['id'] . '~]';
											}
										}
										unset($arrForeignRecords);
									}

									// unset dca 'foreignKey': prevents Controller->prepareForWidget to read options from table instead handle as normal select
									unset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKey']);
									unset($objForeignFd);
								}
								// foreignKey table is 'normal' table
								elseif ($this->Database->fieldExists($strForeignField, $strForeignTable))
								{
									$blnAlias = $this->Database->fieldExists('alias', $strForeignTable);

									$sqlForeign = "SELECT id," . ($blnAlias ? "alias," : "") . $strForeignField . " FROM " . $strForeignTable . ( strlen($strForeignKeyCond) ? " WHERE ".$strForeignKeyCond : '' ) . " ORDER BY " . $strForeignField;

									$objForeign = $this->Database->prepare($sqlForeign)->execute();

									if ($objForeign->numRows)
									{
										$arrForeignRecords = $objForeign->fetchAllAssoc();
										if (!empty($arrForeignRecords))
										{
											foreach ($arrForeignRecords as $arrForeignRecord )
											{
												$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'][$arrForeignRecord['id']] = $arrForeignRecord[$strForeignField] . ' [~' . ( ($blnAlias && strlen($arrForeignRecord['alias'])) ? $arrForeignRecord['alias'] : $arrForeignRecord['id'] ) . '~]';
											}
										}
										unset($arrForeignRecords);
									}

									// unset dca 'foreignKey': prevents Controller->prepareForWidget to read options from table instead handle as normal select
									unset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKey']);
									unset($objForeign);
								}
								// sort options on label
								asort($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options']);
							} // foreignKey field

							$arrValues = explode('|', $this->varValue);

							if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['efgStoreValues'])
							{
								$this->varValue = $arrValues;
							}
							else
							{
								// prepare values
								$arrNewValues = array();

								foreach($arrValues as $kVal => $vVal)
								{
									$vVal = trim($vVal);
									$strK = false;
	 								if (strlen($vVal) && $strK == false)
	 								{

										// handle grouped options
										foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'] as $strOptsKey => $varOpts)
										{
											if (is_array($varOpts))
											{
												$strK = array_search($vVal, $varOpts);
											}
											else
											{
												$strK = array_search($vVal, $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options']);
											}

											if ($strK !== false)
											{
												$arrNewValues[] = $strK;
												break;
											}
										}

										// add saved option to avaliable options if not exists
										if ($strK === false)
										{
											$strK = preg_replace('/(.*?\[)(.*?)(\])/si', '$2', $vVal);
											$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'][$strK] = $vVal;
											$arrNewValues[] = $strK;
										}

	 								}
								}

								$this->varValue = $arrNewValues;
							}
						}
					} // field types radio, select, multi checkbox

					// field type single checkbox
					elseif ($strInputType=='checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['multiple'])
					{
						if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options']))
						{
							$arrVals = array_keys($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options']);
						}
						else
						{
							$arrVals = array($this->varValue);
						}

						// tom, 2007-09-27, bugfix:
						// .. not if value is empty or does not exist at all
						// .. for example record is created by frontend form, checkbox was not checked, then no record in tl_formdata_details exists
						if (strlen($arrVals[0]) && strlen($this->varValue))
						{
							$this->varValue = $arrVals[0];
						}
						else
						{
							$this->varValue = '';
						}
					} // field typ single checkbox

					// field type efgLookupSelect
					elseif ($strInputType=='efgLookupSelect')
					{
						$arrFieldOptions = $this->FormData->prepareDcaOptions($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]);

						// prepare options array and value
						if (is_array($arrFieldOptions))
						{
							// prepare options array
							$arrNewOptions = array();
							foreach ($arrFieldOptions as $k => $v)
							{
								$arrNewOptions[$v['value']] = $v['label'];
							}
						}

						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'] = $arrNewOptions;

						// prepare varValue
						if (strlen($this->varValue))
						{
							if (!is_array($this->varValue))
							{
								$this->varValue = explode('|', $this->varValue);
							}
							foreach ($this->varValue as $k => $v)
							{
								$sNewVal = array_search($v, $arrNewOptions);
								if ($sNewVal)
								{
									$this->varValue[$v] = $sNewVal;
								}
							}
						}

						// render type efgLookupSelect as SelectMenu
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'select';

					} // field type efgLookupSelect

					// field type efgLookupCheckbox
					elseif ($strInputType=='efgLookupCheckbox')
					{
						$arrFieldOptions = $this->FormData->prepareDcaOptions($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]);

						// prepare options array and value
						if (is_array($arrFieldOptions))
						{
							// prepare options array
							$arrNewOptions = array();
							foreach ($arrFieldOptions as $k => $v)
							{
								$arrNewOptions[$v['value']] = $v['label'];
							}
						}

						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'] = $arrNewOptions;

						// prepare varValue
						if (strlen($this->varValue))
						{
							if (!is_array($this->varValue))
							{
								$this->varValue = explode('|', $this->varValue);
							}
							foreach ($this->varValue as $k => $v)
							{
								$sNewVal = array_search($v, $arrNewOptions);
								if ($sNewVal)
								{
									$this->varValue[$v] = $sNewVal;
								}
							}
						}

						// render type efgLookupCheckbox as CheckboxMenu
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'checkbox';

					} // field type efgLookupCheckbox

					// field type efgLookupRadio
					elseif ($strInputType=='efgLookupRadio')
					{
						$arrFieldOptions = $this->FormData->prepareDcaOptions($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]);

						// prepare options array and value
						if (is_array($arrFieldOptions))
						{
							// prepare options array
							$arrNewOptions = array();
							foreach ($arrFieldOptions as $k => $v)
							{
								$arrNewOptions[$v['value']] = $v['label'];
							}
						}

						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'] = $arrNewOptions;

						// prepare varValue
						if (strlen($this->varValue))
						{
							if (!is_array($this->varValue))
							{
								$this->varValue = explode('|', $this->varValue);
							}
							foreach ($this->varValue as $k => $v)
							{
								$sNewVal = array_search($v, $arrNewOptions);
								if ($sNewVal)
								{
									$this->varValue[$v] = $sNewVal;
								}
							}
						}

						// render type efgLookupRadio as RadioMenu
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'radio';

					} // field type efgLookupRadio

					else
					{
						$this->varValue = $this->FormData->prepareDbValForWidget($this->varValue, $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]);
					}

					$this->objActiveRecord->{$this->strField} = $this->varValue;

					// Call load_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback']))
					{
						foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback'] as $callback)
						{
							if (is_array($callback))
							{
								$this->import($callback[0]);
								$this->varValue = $this->$callback[0]->$callback[1]($this->varValue, $this);
							}
						}

						$this->objActiveRecord->{$this->strField} = $this->varValue;
					}

					// Build the row and pass the current palette string (thanks to Tristan Lins)
					$blnAjax ? $strAjax .= $this->row($this->strPalette) : $return .= $this->row($this->strPalette);
				}

				$class = 'tl_box';
				$return .= "\n" . '</fieldset>';
			}
		}


		$version = '';

		// Add some buttons and end the form
		$return .= '
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['save']).'">
<input type="submit" name="saveNclose" id="saveNclose" class="tl_submit" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNclose']).'"> ' . (!$GLOBALS['TL_DCA'][$this->strTable]['config']['closed'] ? '
<input type="submit" name="saveNcreate" id="saveNcreate" class="tl_submit" accesskey="n" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNcreate']).'"> ' : '') . ($this->Input->get('s2e') ? '
<input type="submit" name="saveNedit" id="saveNedit" class="tl_submit" accesskey="e" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNedit']).'"> ' : '') .'
</div>

</div>
</form>';

		// Begin the form (-> DO NOT CHANGE THIS ORDER -> this way the onsubmit attribute of the form can be changed by a field)
		$return = $version . '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.sprintf($GLOBALS['TL_LANG']['MSC']['editRecord'], ($this->intId ? 'ID '.$this->intId : '')).'</h2>
'.$this->getMessages().'
<form action="'.ampersand($this->Environment->request, true).'" id="'.$this->strTable.'" class="tl_form" method="post" enctype="' . ($this->blnUploadable ? 'multipart/form-data' : 'application/x-www-form-urlencoded') . '"'.(!empty($this->onsubmit) ? ' onsubmit="'.implode(' ', $this->onsubmit).'"' : '').'>
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.specialchars($this->strTable).'">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<input type="hidden" name="FORM_FIELDS[]" value="'.specialchars($this->strPalette).'">'.($this->noReload ? '

<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').$return;

		// Reload the page to prevent _POST variables from being sent twice
		if ($this->Input->post('FORM_SUBMIT') == $this->strTable && !$this->noReload)
		{
			$arrValues = $this->values;
			array_unshift($arrValues, time());

			// Trigger the onsubmit_callback
			if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback']))
			{
				foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback'] as $callback)
				{
					$this->import($callback[0]);
					$this->$callback[0]->$callback[1]($this);
				}
			}

			// Set the current timestamp (-> DO NOT CHANGE ORDER version - timestamp)
			$this->Database->prepare("UPDATE " . $this->strTable . " SET tstamp=? WHERE " . implode(' AND ', $this->procedure))
							->execute($arrValues);

			// Redirect
			if (isset($_POST['saveNclose']))
			{
				$this->resetMessages();
				setcookie('BE_PAGE_OFFSET', 0, 0, '/');
				$this->redirect($this->getReferer());
			}
			elseif (isset($_POST['saveNedit']))
			{
				$this->resetMessages();
				setcookie('BE_PAGE_OFFSET', 0, 0, '/');
				$strUrl = $this->addToUrl($GLOBALS['TL_DCA'][$this->strTable]['list']['operations']['edit']['href']);

				$strUrl = preg_replace('/(&amp;)?s2e=[^&]*/i', '', $strUrl);
				$strUrl = preg_replace('/(&amp;)?act=[^&]*/i', '', $strUrl);

				$this->redirect($strUrl);
			}
			elseif (isset($_POST['saveNback']))
			{
				$this->resetMessages();
				setcookie('BE_PAGE_OFFSET', 0, 0, '/');

				$this->redirect($this->Environment->script . '?do=' . $this->Input->get('do'));
			}

			elseif (isset($_POST['saveNcreate']))
			{
				$this->resetMessages();
				setcookie('BE_PAGE_OFFSET', 0, 0, '/');
				$strUrl = $this->Environment->script . '?do=' . $this->Input->get('do');

				if (isset($_GET['table']))
				{
					$strUrl .= '&amp;table=' . $this->Input->get('table');
				}

				$strUrl .= strlen($GLOBALS['TL_DCA'][$this->strTable]['config']['ptable']) ? '&amp;act=create&amp;mode=2&amp;pid=' . CURRENT_ID : '&amp;act=create';

				$this->redirect($strUrl);
			}

			$this->reload();
		}

		// Set the focus if there is an error
		if ($this->noReload)
		{
			$return .= '

<script>
window.addEvent(\'domready\', function() {
    Backend.vScrollTo(($(\'' . $this->strTable . '\').getElement(\'label.error\').getPosition().y - 20));
});
</script>';
		}

		return $return;
	}


	/**
	 * Auto-Generate a form to edit all records that are currently shown
	 * @param integer
	 * @param integer
	 * @return string
	 */
	public function editAll($intId=null, $ajaxId=null)
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['config']['notEditable'])
		{
			$this->log('Table "' . $this->strTable . '" is not editable', 'DC_Formdata editAll()', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$return = '';
		$this->import('BackendUser', 'User');

		// Get current IDs from session
		$session = $this->Session->getData();
		$ids = $session['CURRENT']['IDS'];

		if ($intId != '' && $this->Environment->isAjaxRequest)
		{
			$ids = array($intId);
		}

		// Save field selection in session
		if ($this->Input->post('FORM_SUBMIT') == $this->strTable.'_all' && $this->Input->get('fields'))
		{
			$session['CURRENT'][$this->strTable] = deserialize($this->Input->post('all_fields'));
			$this->Session->setData($session);
		}

		// Add fields
		$fields = $session['CURRENT'][$this->strTable];

		if (is_array($fields) && !empty($fields) && $this->Input->get('fields'))
		{
			$class = 'tl_tbox';
			$this->checkForTinyMce();

			// Walk through each record
			foreach ($ids as $id)
			{
				$this->intId = $id;
				$this->procedure = array('id=?');
				$this->values = array($this->intId);
				$this->blnCreateNewVersion = false;
				$this->strPalette = trimsplit('[;,]', $this->getPalette());

				// Add meta fields if the current user is an administrator
				if ($this->User->isAdmin)
				{
					if ($this->Database->fieldExists('sorting', $this->strTable))
					{
						array_unshift($this->strPalette, 'sorting');
					}

					if ($this->Database->fieldExists('pid', $this->strTable))
					{
						array_unshift($this->strPalette, 'pid');
					}

					$GLOBALS['TL_DCA'][$this->strTable]['fields']['pid'] = array('label'=>&$GLOBALS['TL_LANG']['MSC']['pid'], 'inputType'=>'text', 'eval'=>array('rgxp'=>'digit'));
					$GLOBALS['TL_DCA'][$this->strTable]['fields']['sorting'] = array('label'=>&$GLOBALS['TL_LANG']['MSC']['sorting'], 'inputType'=>'text', 'eval'=>array('rgxp'=>'digit'));
				}

				// Begin current row
				$strAjax = '';
				$blnAjax = false;
				$return .= '
<div class="'.$class.'">';

				$class = 'tl_box';
				$formFields = array();

				$arrBaseFields = array();
				$arrDetailFields = array();
				$arrSqlDetails = array();

				foreach ($fields as $strField)
				{
					if (in_array($strField, $this->arrBaseFields))
					{
						$arrBaseFields[] = $strField;
					}
					if (in_array($strField, $this->arrDetailFields))
					{
						$arrDetailFields[] = $strField;
						$arrSqlDetails[] = '(SELECT value FROM tl_formdata_details WHERE ff_name=\'' .$strField. '\' AND pid=f.id) AS `' . $strField .'`';
					}
				}

				$strSqlFields = (count($arrBaseFields)>0 ? implode(', ', $arrBaseFields) : '');
				$strSqlFields .= (count($arrSqlDetails)>0 ? (strlen($strSqlFields) ? ', ' : '') . implode(', ', $arrSqlDetails) : '');

				// Get the field values
				$objRow = $this->Database->prepare("SELECT " . $strSqlFields . " FROM " . $this->strTable . " f WHERE id=?")
											->limit(1)
											->execute($this->intId);

				// Store the active record
				$this->objActiveRecord = $objRow;
				$blnIsFirst = true;

				foreach ($this->strPalette as $v)
				{
					// Check whether field is excluded
					if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['exclude'])
					{
						continue;
					}

					if ($v == '[EOF]')
					{
						if ($blnAjax && $this->Environment->isAjaxRequest)
						{
							return $strAjax . '<input type="hidden" name="FORM_FIELDS_'.$id.'[]" value="'.specialchars(implode(',', $formFields)).'">';
						}

						$blnAjax = false;
						$return .= "\n  " . '</div>';

						continue;
					}

					if (preg_match('/^\[.*\]$/i', $v))
					{
						$thisId = 'sub_' . substr($v, 1, -1) . '_' . $id;
						$blnAjax = ($ajaxId == $thisId && $this->Environment->isAjaxRequest) ? true : false;
						$return .= "\n  " . '<div id="'.$thisId.'">';

						continue;
					}

					if (!in_array($v, $fields))
					{
						continue;
					}

					$this->strField = $v;
					$this->strInputName = $v.'_'.$this->intId;
					$formFields[] = $v.'_'.$this->intId;

					// Set the default value and try to load the current value from DB
					$this->varValue = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['default'] ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['default'] : '';

					if ($objRow->$v !== false)
					{
						$this->varValue = $objRow->$v;
					}

					// Autofocus the first field
					if ($blnIsFirst && $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] == 'text')
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['autofocus'] = 'autofocus';
						$blnIsFirst = false;
					}

					// Call options_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options_callback']))
					{
						$strClass = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options_callback'][0];
						$strMethod = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options_callback'][1];

						$this->import($strClass);
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'] = $this->$strClass->$strMethod($this);
					}

					// prepare values of special fields like radio, select and checkbox
					$strInputType = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'];

					// render inputType hidden as inputType text in Backend
					if ($strInputType == 'hidden')
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'text';
					}

					// field types radio, select, multi checkbox
					elseif ( $strInputType=='radio' || $strInputType=='select' || $strInputType=='conditionalselect' || ( $strInputType=='checkbox'  && $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['multiple'] ) )
					{
						if (in_array($this->strField, $this->arrBaseFields) && in_array($this->strField, $this->arrOwnerFields) )
						{
							if ($this->strField == 'fd_user')
							{
								if ($this->User && $this->User->id)
								{
									$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['default'] = $this->User->id;
								}
							}
						}
						elseif (!is_array($this->varValue))
						{
							// foreignKey fields
							if ($strInputType == 'select' && strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKey']))
							{
								// include blank Option
								$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'][0] = "-";

								$arrKey = explode('.', $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKey']);
								$strForeignTable = $arrKey[0];
								$strForeignField = $arrKey[1];

								// WHERE condition for foreignKey
								$strForeignKeyCond = '';
								if (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKeyWhere']))
								{
									$strForeignKeyCond = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKeyWhere'];
								}

								// check if foreignKey table is a formdata table
								if (substr($strForeignTable, 0, 3) == 'fd_')
								{
									$strFormKey = substr($strForeignTable, 3);
									$strForeignDcaKey = $strForeignTable;
									$strForeignTable = 'tl_formdata';

									// backup current dca and load dca for foreign formdata
									$BAK_DCA = $GLOBALS['TL_DCA'][$this->strTable];
									$this->loadDataContainer($strForeignDcaKey);

									$strForeignField = $arrKey[1];
									$strForeignSqlField = '(SELECT value FROM tl_formdata_details WHERE ff_name="' .$strForeignField. '" AND pid=f.id ) AS `' . $strForeignField . '`';

									$sqlForeignFd = "SELECT f.id," . $strForeignSqlField . " FROM tl_formdata f, tl_formdata_details fd ";
									$sqlForeignFd .= "WHERE (f.id=fd.pid) AND f." . $GLOBALS['TL_DCA'][$strForeignTable]['tl_formdata']['formFilterKey'] . "='" . $GLOBALS['TL_DCA'][$strForeignTable]['tl_formdata']['formFilterValue'] . "' AND fd.ff_name='" . $strForeignField . "'";

									if (strlen($strForeignKeyCond))
									{
										$arrForeignKeyCond = preg_split('/([\s!=><]+)/', $strForeignKeyCond, -1, PREG_SPLIT_DELIM_CAPTURE);
										$strForeignCondField = $arrForeignKeyCond[0];
										unset($arrForeignKeyCond[0]);
										if (in_array($strForeignCondField, $GLOBALS['TL_DCA'][$strForeignTable]['tl_formdata']['baseFields']))
										{
											$sqlForeignFd .= ' AND f.' . $strForeignCondField . implode('', $arrForeignKeyCond);
										}
										elseif (in_array($strForeignCondField, $GLOBALS['TL_DCA'][$strForeignTable]['tl_formdata']['detailFields']))
										{
											$sqlForeignFd .= ' AND (SELECT value FROM tl_formdata_details WHERE ff_name="' .$strForeignCondField. '" AND pid=f.id ) ' . implode('', $arrForeignKeyCond);
										}
									}

									$objForeignFd = $this->Database->prepare($sqlForeignFd)->execute();

									// reset current dca
									$GLOBALS['TL_DCA'][$this->strTable] = $BAK_DCA;
									unset($BAK_DCA);

									if ($objForeignFd->numRows)
									{
										$arrForeignRecords = $objForeignFd->fetchAllAssoc();
										if (!empty($arrForeignRecords))
										{
											foreach ($arrForeignRecords as $arrForeignRecord )
											{
												$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'][$arrForeignRecord['id']] = $arrForeignRecord[$strForeignField] .  ' [~' . $arrForeignRecord['id'] . '~]';
											}
										}
										unset($arrForeignRecords);
									}

									// unset dca 'foreignKey': prevents Controller->prepareForWidget to read options from table instead handle as normal select
									unset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKey']);
									unset($objForeignFd);
								}
								// foreignKey table is 'normal' table
								elseif ($this->Database->fieldExists($strForeignField, $strForeignTable))
								{
									$blnAlias = $this->Database->fieldExists('alias', $strForeignTable);

									$sqlForeign = "SELECT id," . ($blnAlias ? "alias," : "") . $strForeignField . " FROM " . $strForeignTable . ( strlen($strForeignKeyCond) ? " WHERE ".$strForeignKeyCond : '' ) . " ORDER BY " . $strForeignField;

									$objForeign = $this->Database->prepare($sqlForeign)->execute();

									if ($objForeign->numRows)
									{
										$arrForeignRecords = $objForeign->fetchAllAssoc();
										if (!empty($arrForeignRecords))
										{
											foreach ($arrForeignRecords as $arrForeignRecord )
											{
												$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'][$arrForeignRecord['id']] = $arrForeignRecord[$strForeignField] . ' [~' . ( ($blnAlias && strlen($arrForeignRecord['alias'])) ? $arrForeignRecord['alias'] : $arrForeignRecord['id'] ) . '~]';
											}
										}
										unset($arrForeignRecords);
									}

									// unset dca 'foreignKey': prevents Controller->prepareForWidget to read options from table instead handle as normal select
									unset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['foreignKey']);
									unset($objForeign);
								}
								// sort options on label
								asort($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options']);
							}

							$arrValues = explode('|', $this->varValue);

							if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['efgStoreValues'])
							{
								$this->varValue = $arrValues;
							}
							else
							{
								// prepare values
								$arrNewValues = array();

								foreach($arrValues as $kVal => $vVal)
								{
									$vVal = trim($vVal);
									$strK = false;
	 								if (strlen($vVal) && $strK == false)
	 								{
										// handle grouped options
										foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'] as $strOptsKey => $varOpts)
										{
											if (is_array($varOpts))
											{
												$strK = array_search($vVal, $varOpts);
											}
											else
											{
												$strK = array_search($vVal, $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options']);
											}

											if ($strK !== false)
											{
												$arrNewValues[] = $strK;
												break;
											}
										}

										// add saved option to avaliable options if not exists
										if ($strK === false)
										{
											$strK = preg_replace('/(.*?\[)(.*?)(\])/si', '$2', $vVal);
											$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'][$strK] = $vVal;
											$arrNewValues[] = $strK;
										}

	 								}
								}

								$this->varValue = $arrNewValues;
							}

						}
					} // field types radio, select, multi checkbox

					// field type single checkbox
					elseif ($strInputType=='checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['eval']['multiple'])
					{
						if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options']))
						{
							$arrVals = array_keys($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options']);
						}
						else
						{
							$arrVals = array($this->varValue);
						}

						// tom, 2007-09-27, bugfix:
						// .. not if value is empty or does not exist at all
						// .. for example record is created by frontend form, checkbox was not checked, then no record in tl_formdata_details exisits
						if (strlen($arrVals[0]) && strlen($this->varValue))
						{
							$this->varValue = $arrVals[0];
						}
						else
						{
							$this->varValue = "";
						}
					} // field typ single checkbox

					// field type efgLookupSelect
					elseif ($strInputType=='efgLookupSelect')
					{
						$arrFieldOptions = $this->FormData->prepareDcaOptions($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]);

						// prepare options array and value
						if (is_array($arrFieldOptions))
						{
							// prepare options array
							$arrNewOptions = array();
							foreach ($arrFieldOptions as $k => $v)
							{
								$arrNewOptions[$v['value']] = $v['label'];
							}
						}

						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'] = $arrNewOptions;

						// prepare varValue
						if (strlen($this->varValue))
						{
							if (!is_array($this->varValue))
							{
								$this->varValue = explode('|', $this->varValue);
							}
							foreach ($this->varValue as $k => $v)
							{
								$sNewVal = array_search($v, $arrNewOptions);
								if ($sNewVal)
								{
									$this->varValue[$v] = $sNewVal;
								}
							}
						}

					} // field type efgLookupSelect

					// field type efgLookupCheckbox
					elseif ($strInputType=='efgLookupCheckbox')
					{
						$arrFieldOptions = $this->FormData->prepareDcaOptions($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]);

						// prepare options array and value
						if (is_array($arrFieldOptions))
						{
							// prepare options array
							$arrNewOptions = array();
							foreach ($arrFieldOptions as $k => $v)
							{
								$arrNewOptions[$v['value']] = $v['label'];
							}
						}

						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'] = $arrNewOptions;

						// prepare varValue
						if (strlen($this->varValue))
						{
							if (!is_array($this->varValue))
							{
								$this->varValue = explode('|', $this->varValue);
							}
							foreach ($this->varValue as $k => $v) {
								$sNewVal = array_search($v, $arrNewOptions);
								if ($sNewVal)
								{
									$this->varValue[$v] = $sNewVal;
								}
							}
						}

					} // field type efgLookupCheckbox

					// field type efgLookupRadio
					elseif ($strInputType=='efgLookupRadio')
					{
						$arrFieldOptions = $this->FormData->prepareDcaOptions($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]);

						// prepare options array and value
						if (is_array($arrFieldOptions))
						{
							// prepare options array
							$arrNewOptions = array();
							foreach ($arrFieldOptions as $k => $v)
							{
								$arrNewOptions[$v['value']] = $v['label'];
							}
						}

						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['options'] = $arrNewOptions;

						// prepare varValue
						if (strlen($this->varValue))
						{
							if (!is_array($this->varValue))
							{
								$this->varValue = explode('|', $this->varValue);
							}
							foreach ($this->varValue as $k => $v) {
								$sNewVal = array_search($v, $arrNewOptions);
								if ($sNewVal)
								{
									$this->varValue[$v] = $sNewVal;
								}
							}
						}

					} // field type efgLookupRadio


					// Call load_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback']))
					{
						foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['load_callback'] as $callback)
						{
							$this->import($callback[0]);
							$this->varValue = $this->$callback[0]->$callback[1]($this->varValue, $this);
						}
					}

					// Re-set the current value
					$this->objActiveRecord->{$this->strField} = $this->varValue;

					// input type efgLookupCheckbox: modify DCA to render as CheckboxMenu
					if ($strInputType=='efgLookupCheckbox')
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'checkbox';
					}
					// input type efgLookupRadio: modify DCA to render as RadioMenu
					elseif ($strInputType=='efgLookupRadio')
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'radio';
					}
					// input type efgLookupSelect: modify DCA to render as SelectMenu
					elseif ( $strInputType=='efgLookupSelect' )
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'select';
					}

					// Build the current row
					$blnAjax ? $strAjax .= $this->row() : $return .= $this->row();

					// input type efgLookupCheckbox: reset DCA inputType
					if ($strInputType=='efgLookupCheckbox')
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'efgLookupCheckbox';
					}
					// input type efgLookupRadio: reset DCA inputType
					elseif ( $strInputType=='efgLookupRadio' )
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'efgLookupRadio';
					}
					// input type efgLookupSelect: reset DCA inputType
					elseif ( $strInputType=='efgLookupSelect' )
					{
						$GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField]['inputType'] = 'efgLookupSelect';
					}

				}

				// Close box
				$return .= '
 <input type="hidden" name="FORM_FIELDS_'.$this->intId.'[]" value="'.specialchars(implode(',', $formFields)).'">
</div>';

				// Save record
				if ($this->Input->post('FORM_SUBMIT') == $this->strTable && !$this->noReload)
				{
					// Call onsubmit_callback
					if (is_array($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback']))
					{
						foreach ($GLOBALS['TL_DCA'][$this->strTable]['config']['onsubmit_callback'] as $callback)
						{
							$this->import($callback[0]);
							$this->$callback[0]->$callback[1]($this);
						}
					}

					// Set current timestamp (-> DO NOT CHANGE ORDER version - timestamp)
					$this->Database->prepare("UPDATE " . $this->strTable . " SET tstamp=? WHERE id=?")
								   ->execute(time(), $this->intId);
				}
			}

			// Add the form
			$return = '

<h2 class="sub_headline_all">'.sprintf($GLOBALS['TL_LANG']['MSC']['all_info'], $this->strTable).'</h2>

<form action="'.ampersand($this->Environment->request, true).'" id="'.$this->strTable.'" class="tl_form" method="post" enctype="' . ($this->blnUploadable ? 'multipart/form-data' : 'application/x-www-form-urlencoded') . '">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.$this->strTable.'">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">'.($this->noReload ? '

<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').$return.'

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['save']).'">
<input type="submit" name="saveNclose" id="saveNclose" class="tl_submit" accesskey="c" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['saveNclose']).'">
</div>

</div>
</form>';

			// Set the focus if there is an error
			if ($this->noReload)
			{
				$return .= '

<script>
window.addEvent(\'domready\', function() {
    Backend.vScrollTo(($(\'' . $this->strTable . '\').getElement(\'label.error\').getPosition().y - 20));
});
</script>';
			}

			// Reload the page to prevent _POST variables from being sent twice
			if ($this->Input->post('FORM_SUBMIT') == $this->strTable && !$this->noReload)
			{
				if ($this->Input->post('saveNclose'))
				{
					setcookie('BE_PAGE_OFFSET', 0, 0, '/');
					$this->redirect($this->getReferer());
				}

				$this->reload();
			}
		}

		// Else show a form to select the fields
		else
		{
			$options = '';
			$fields = array();

			// Add fields of the current table
			$fields = array_merge($fields, array_keys($GLOBALS['TL_DCA'][$this->strTable]['fields']));

			// Add meta fields if the current user is an administrator
			if ($this->User->isAdmin)
			{
				if ($this->Database->fieldExists('sorting', $this->strTable) && !in_array('sorting', $fields))
				{
					array_unshift($fields, 'sorting');
				}

				if ($this->Database->fieldExists('pid', $this->strTable) && !in_array('pid', $fields))
				{
					array_unshift($fields, 'pid');
				}
			}

			// Show all non-excluded fields
			foreach ($fields as $field)
			{
				if ($field == 'pid' || $field == 'sorting' || (!$GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['exclude'] && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['doNotShow'] && (strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['inputType']) || is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['input_field_callback']))))
				{
					$options .= '
<input type="checkbox" name="all_fields[]" id="all_'.$field.'" class="tl_checkbox" value="'.specialchars($field).'"> <label for="all_'.$field.'" class="tl_checkbox_label">'.(strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'][0]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'][0] : $GLOBALS['TL_LANG']['MSC'][$field][0]).'</label><br>';
				}
			}

			$blnIsError = ($_POST && empty($_POST['all_fields']));

			// Return the select menu
			$return .= '

<h2 class="sub_headline_all">'.sprintf($GLOBALS['TL_LANG']['MSC']['all_info'], $this->strTable).'</h2>

<form action="'.ampersand($this->Environment->request, true).'&amp;fields=1" id="'.$this->strTable.'_all" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="'.$this->strTable.'_all">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">'.($blnIsError ? '

<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['general'].'</p>' : '').'

<div class="tl_tbox">
<fieldset class="tl_checkbox_container">
  <legend'.($blnIsError ? ' class="error"' : '').'>'.$GLOBALS['TL_LANG']['MSC']['all_fields'][0].'</legend>
  <input type="checkbox" id="check_all" class="tl_checkbox" onclick="Backend.toggleCheckboxes(this)"> <label for="check_all" style="color:#a6a6a6"><em>'.$GLOBALS['TL_LANG']['MSC']['selectAll'].'</em></label><br>'.$options.'
</fieldset>'.($blnIsError ? '
<p class="tl_error">'.$GLOBALS['TL_LANG']['ERR']['all_fields'].'</p>' : (($GLOBALS['TL_CONFIG']['showHelp'] && strlen($GLOBALS['TL_LANG']['MSC']['all_fields'][1])) ? '
<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['all_fields'][1].'</p>' : '')).'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['continue']).'">
</div>

</div>
</form>';
		}

		// Return
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>'.$return;
	}


	/**
	 * Save the current value
	 * @param mixed
	 * @throws Exception
	 */
	protected function save($varValue)
	{
		if ($this->Input->post('FORM_SUBMIT') != $this->strTable)
		{
			return;
		}

		$arrField = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->strField];

		// table to write to tl_formdata (base fields) or tl_formdata_details (detail fields)
		$strTargetTable = $this->strTable;
		$strTargetField = $this->strField;
		$blnDetailField = false;

		// if field is one of detail fields
		if (in_array($strTargetField, $this->arrDetailFields))
		{
			$strTargetTable = $GLOBALS['TL_DCA'][$this->strTable]['config']['ctable'][0];
			$blnDetailField = true;
		}

		// Convert date formats into timestamps
		if ($varValue != '' && in_array($arrField['eval']['rgxp'], array('date', 'time', 'datim')))
		{
			$objDate = new Date($varValue, $GLOBALS['TL_CONFIG'][$arrField['eval']['rgxp'] . 'Format']);
			$varValue = $objDate->tstamp;
		}

		// Convert checkbox, radio, select, conditionalselect to store the values instead of keys
		if (($arrField['inputType']=='checkbox' && $arrField['eval']['multiple']) || $arrField['inputType']=='radio' || $arrField['inputType']=='select' || $arrField['inputType']=='conditionalselect')
		{

			if (!in_array($this->strField, $this->arrOwnerFields))
			{
				$arrOpts = $arrField['options'];

				// OptGroups can not be saved
				$arrNewOpts = array();

				foreach ($arrOpts as $strKey => $varOpt)
				{
					if (is_array($varOpt) && count($varOpt))
					{
						foreach ($varOpt as $keyOpt => $valOpt)
						{
							$arrNewOpts[$keyOpt] = $valOpt;
						}
					}
					else
					{
						$arrNewOpts[$strKey] = $varOpt;
					}
				}
				$arrOpts = $arrNewOpts;
				unset($arrNewOpts);

				$arrSel = deserialize($varValue, true);
				if (is_array($arrSel) && !empty($arrSel))
				{
					$arrSel = array_flip($arrSel);
					// use options value or options label
					if ($arrField['eval']['efgStoreValues'])
					{
						$arrVals = array_keys(array_intersect_key($arrOpts, $arrSel));
					}
					else
					{
						$arrVals = array_values(array_intersect_key($arrOpts, $arrSel));
					}
				}
				$varValue = (is_array($arrVals) && !empty($arrVals) ? implode('|', $arrVals) : '');
			}
		}

		if ($arrField['inputType']=='checkbox' && !$arrField['eval']['multiple'])
		{
			if (is_array($arrField['options']))
			{
				$arrVals = ($arrField['eval']['efgStoreValues'] ? array_keys($arrField['options']) : array_values($arrField['options']));
			}
			else
			{
				$arrVals = array("1");
			}

			if (strlen($varValue)) {

				$varValue =  $arrVals[0];
			}
			else
			{
				$varValue = '';
			}
		}


		// Make sure unique fields are unique
		if (strlen($varValue) && $arrField['eval']['unique'])
		{
			$objUnique = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE " . $this->strField . "=? AND id!=?")
										->execute($varValue, $this->intId);

			if ($objUnique->numRows)
			{
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['unique'], (strlen($arrField['label'][0]) ? $arrField['label'][0] : $this->strField)));
			}
		}

		// Trigger the save_callback
		if (is_array($arrField['save_callback']))
		{
			foreach ($arrField['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$varValue = $this->$callback[0]->$callback[1]($varValue, $this);
			}
		}

		// Save the value if there was no error
		if (($varValue != '' || !$arrField['eval']['doNotSaveEmpty']) && ($this->varValue != $varValue || $arrField['eval']['alwaysSave']))
		{
			// If the field is a fallback field, empty all other columns
			if ($arrField['eval']['fallback'] && $varValue != '')
			{
				$this->Database->execute("UPDATE " . $this->strTable . " SET " . $this->strField . "=''");
			}

			$arrValues = $this->values;
			$arrProcedures = $this->procedure;

			if($blnDetailField)
			{
				// add condition ff_name
				$arrProcedures[] = 'ff_name=?';
				$arrValues[] = $strTargetField;

				foreach($arrProcedures as $kP => $kV)
				{
					if ($kV == 'id=?')
					{
						$arrProcedures[$kP] = 'pid=?';
					}

					if ($kV == 'form=?')
					{
						$arrProcedures[$kP] = 'ff_name=?';
						$arrValues[$kP] = $strTargetField;
					}
				}
			}
			array_unshift($arrValues, $varValue);

			$sqlUpd = "UPDATE " . $strTargetTable . " SET " . $strTargetField . "=? WHERE " . implode(' AND ', $arrProcedures);
			if ($blnDetailField)
			{
				// if record does not exist insert an empty record
				$objExist = $this->Database->prepare("SELECT id FROM tl_formdata_details WHERE pid=? AND ff_name=?")
											->execute(array($this->intId, $strTargetField));

				if ($objExist->numRows == 0)
				{
					$arrSetInsert = array(
						'pid' => $this->intId,
						'tstamp' => time(),
						'ff_id' => $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strTargetField]['ff_id'],
						'ff_type' => $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strTargetField]['inputType'],
						'ff_label' => $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strTargetField]['label'][0] ,
						'ff_name' => $strTargetField
					);
					$objInsertStmt = $this->Database->prepare("INSERT INTO " . $strTargetTable . " %s")
										->set($arrSetInsert)
										->execute();
				}

				$sqlUpd = "UPDATE " . $strTargetTable . " SET value=? WHERE " . implode(' AND ', $arrProcedures);
			}

			$objUpdateStmt = $this->Database->prepare($sqlUpd)
											->execute($arrValues);

			if ($objUpdateStmt->affectedRows)
			{
				if ($varValue != $this->varValue)
				{
					if (!$arrField['eval']['submitOnChange'])
					{
						$this->blnCreateNewVersion = true;
					}
				}

				$this->varValue = deserialize($varValue);

				if (is_object($this->objActiveRecord))
				{
					$this->objActiveRecord->{$this->strField} = $this->varValue;
				}
			}
		}
	}


	/**
	 * Return the name of the current palette
	 * @return string
	 */
	public function getPalette()
	{
		$palette = 'default';
		$strPalette = $GLOBALS['TL_DCA'][$this->strTable]['palettes'][$palette];

		// Check whether there are selector fields
		if (!empty($GLOBALS['TL_DCA'][$this->strTable]['palettes']['__selector__']))
		{
			$sValues = array();
			$subpalettes = array();

			$objFields = $this->Database->prepare("SELECT * FROM " . $this->strTable . " WHERE id=?")
										->limit(1)
										->executeUncached($this->intId);

			// Get selector values from DB
			if ($objFields->numRows > 0)
			{
				foreach ($GLOBALS['TL_DCA'][$this->strTable]['palettes']['__selector__'] as $name)
				{
					$trigger = $objFields->$name;

					// Overwrite the trigger
					if ($this->Input->post('FORM_SUBMIT') == $this->strTable)
					{
						$key = ($this->Input->get('act') == 'editAll') ? $name.'_'.$this->intId : $name;

						if (isset($_POST[$key]))
						{
							$trigger = $this->Input->post($key);
						}
					}

					if ($trigger != '')
					{
						if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$name]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$name]['eval']['multiple'])
						{
							$sValues[] = $name;

							// Look for a subpalette
							if (strlen($GLOBALS['TL_DCA'][$this->strTable]['subpalettes'][$name]))
							{
								$subpalettes[$name] = $GLOBALS['TL_DCA'][$this->strTable]['subpalettes'][$name];
							}
						}
						else
						{
							$sValues[] = $trigger;
							$key = $name .'_'. $trigger;

							// Look for a subpalette
							if (strlen($GLOBALS['TL_DCA'][$this->strTable]['subpalettes'][$key]))
							{
								$subpalettes[$name] = $GLOBALS['TL_DCA'][$this->strTable]['subpalettes'][$key];
							}
						}
					}
				}
			}

			// Build possible palette names from the selector values
			if (!count($sValues))
			{
				$names = array('default');
			}
			elseif (count($sValues) > 1)
			{
				foreach ($sValues as $k=>$v)
				{
					// Unset selectors that just trigger subpalettes (see #3738)
					if (isset($GLOBALS['TL_DCA'][$this->strTable]['subpalettes'][$v]))
					{
						unset($sValues[$k]);
					}
				}

				$names = $this->combiner($sValues);
			}
			else
			{
				$names = array($sValues[0]);
			}

			// Get an existing palette
			foreach ($names as $paletteName)
			{
				if (strlen($GLOBALS['TL_DCA'][$this->strTable]['palettes'][$paletteName]))
				{
					$palette = $paletteName;
					$strPalette = $GLOBALS['TL_DCA'][$this->strTable]['palettes'][$paletteName];

					break;
				}
			}

			// Include subpalettes
			foreach ($subpalettes as $k=>$v)
			{
				$strPalette = preg_replace('/\b'. preg_quote($k, '/').'\b/i', $k.',['.$k.'],'.$v.',[EOF]', $strPalette);
			}
		}

		return $strPalette;
	}


	/**
	 * Delete all incomplete and unrelated records
	 */
	protected function reviseTable()
	{
		$reload = false;
		$ptable = $GLOBALS['TL_DCA'][$this->strTable]['config']['ptable'];
		$ctable = $GLOBALS['TL_DCA'][$this->strTable]['config']['ctable'];

		$new_records = $this->Session->get('new_records');

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['reviseTable']) && is_array($GLOBALS['TL_HOOKS']['reviseTable']))
		{
			foreach ($GLOBALS['TL_HOOKS']['reviseTable'] as $callback)
			{
				$this->import($callback[0]);
				$status = $this->$callback[0]->$callback[1]($this->strTable, $new_records[$this->strTable], $ptable, $ctable);

				if ($status === true)
				{
					$reload = true;
				}
			}
		}

		// Delete all new but incomplete records (tstamp=0)
		if (is_array($new_records[$this->strTable]) && !empty($new_records[$this->strTable]))
		{
			$objStmt = $this->Database->execute("DELETE FROM " . $this->strTable . " WHERE id IN(" . implode(',', array_map('intval', $new_records[$this->strTable])) . ") AND tstamp=0");

			if ($objStmt->affectedRows > 0)
			{
				$reload = true;
			}
		}

		// Delete all records of the current table that are not related to the parent table
		if (strlen($ptable))
		{
			$objStmt = $this->Database->execute("DELETE FROM " . $this->strTable . " WHERE NOT EXISTS (SELECT * FROM " . $ptable . " WHERE " . $this->strTable . ".pid = " . $ptable . ".id)");

			if ($objStmt->affectedRows > 0)
			{
				$reload = true;
			}
		}

		// Delete all records of the child table that are not related to the current table
		if (is_array($ctable) && !empty($ctable))
		{
			foreach ($ctable as $v)
			{
				if (strlen($v))
				{
					$objStmt = $this->Database->execute("DELETE FROM " . $v . " WHERE NOT EXISTS (SELECT * FROM " . $this->strTable . " WHERE " . $v . ".pid = " . $this->strTable . ".id)");

					if ($objStmt->affectedRows > 0)
					{
						$reload = true;
					}
				}
			}
		}

		// Reload the page
		if ($reload)
		{
			$this->reload();
		}
	}


	/**
	 * List all records of the current table and return them as HTML string
	 * @return string
	 */
	protected function listView()
	{
		$return = '';
		$table = ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 6) ? $this->ptable : $this->strTable;
		$table_alias = ($table == 'tl_formdata' ? ' f' : '');
		$orderBy = $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['fields'];
		$firstOrderBy = preg_replace('/\s+.*$/i', '', $orderBy[0]);

		if (is_array($this->orderBy) && strlen($this->orderBy[0]))
		{
			$orderBy = $this->orderBy;
			$firstOrderBy = $this->firstOrderBy;
		}

		if ($this->Input->get('table') && $GLOBALS['TL_DCA'][$this->strTable]['config']['ptable'] && $this->Database->fieldExists('pid', $this->strTable))
		{
			$this->procedure[] = 'pid=?';
			$this->values[] = $this->Input->get('id');
		}

		$query = "SELECT * " .(count($this->arrSqlDetails) > 0 ? ', '.implode(',' , array_values($this->arrSqlDetails)) : '') ." FROM " . $this->strTable . $table_alias;

		$sqlWhere = '';

		if (count($this->procedure))
		{
			$arrProcedure = $this->procedure;

			foreach ($arrProcedure as $kProc => $vProc)
			{
				//$strProcField = substr($vProc, 0, strpos($vProc, '='));
				$arrParts = preg_split('/[\s=><\!]/si', $vProc);
				$strProcField = $arrParts[0];
				if ( in_array($strProcField, $this->arrDetailFields) )
				{
					$arrProcedure[$kProc] = "(SELECT value FROM tl_formdata_details WHERE ff_name='" . $strProcField . "' AND pid=f.id)=?";
				}
			}
			$sqlWhere = " WHERE " . implode(' AND ', $arrProcedure);
		}

		if ($sqlWhere != '')
		{
			$query .= $sqlWhere;
		}

		if (is_array($orderBy) && $orderBy[0] != '')
		{
			foreach ($orderBy as $o => $strVal)
			{
				$arrOrderField = explode(' ', $strVal);
				$strOrderField = $arrOrderField[0];
				unset($arrOrderField);
				if (!in_array($strOrderField, $this->arrBaseFields))
				{
					$orderBy[$o] = "(SELECT value FROM tl_formdata_details WHERE ff_name='" . $strOrderField . "' AND pid=f.id)";
				}
			}

			$query .= " ORDER BY " . implode(', ', $orderBy);
		}

		if ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 1 && ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['flag'] % 2) == 0)
		{
			$query .= " DESC";
		}

		$objRowStmt = $this->Database->prepare($query);

		if ($this->limit != '')
		{
			$arrLimit = explode(',', $this->limit);
			$objRowStmt->limit($arrLimit[1], $arrLimit[0]);
		}

		$objRow = $objRowStmt->execute($this->values);
		$this->bid = strlen($return) ? $this->bid : 'tl_buttons';

		// Display buttons
		if (!$GLOBALS['TL_DCA'][$this->strTable]['config']['closed'] || !empty($GLOBALS['TL_DCA'][$this->strTable]['list']['global_operations']))
		{
			$return .= '

<div id="'.$this->bid.'">'.(($this->Input->get('act') == 'select' || $this->ptable) ? '
<a href="'.$this->getReferer(true, $this->ptable).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>' : '') . (($this->ptable && $this->Input->get('act') != 'select') ? ' &nbsp; :: &nbsp;' : '') . (($this->Input->get('act') != 'select') ? '
'.(!$GLOBALS['TL_DCA'][$this->strTable]['config']['closed'] ? '<a href="'.(strlen($this->ptable) ? $this->addToUrl('act=create' . (($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] < 4) ? '&amp;mode=2' : '') . '&amp;pid=' . $this->intId) : $this->addToUrl('act=create')).'" class="header_new" title="'.specialchars($GLOBALS['TL_LANG'][$this->strTable]['new'][1]).'" accesskey="n" onclick="Backend.getScrollOffset()">'.$GLOBALS['TL_LANG'][$this->strTable]['new'][0].'</a>' : '') . $this->generateGlobalButtons() : '') . '
</div>' . $this->getMessages(true);
		}

		// Return "no records found" message
		if ($objRow->numRows < 1)
		{
			$return .= '
<p class="tl_empty">'.$GLOBALS['TL_LANG']['MSC']['noResult'].'</p>';
		}

		// List records
		else
		{
			$result = $objRow->fetchAllAssoc();
			$return .= (($this->Input->get('act') == 'select') ? '

<form action="'.ampersand($this->Environment->request, true).'" id="tl_select" class="tl_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_select">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">' : '').'

<div class="tl_listing_container list_view">'.(($this->Input->get('act') == 'select') ? '

<div class="tl_select_trigger">
<label for="tl_select_trigger" class="tl_select_label">'.$GLOBALS['TL_LANG']['MSC']['selectAll'].'</label> <input type="checkbox" id="tl_select_trigger" onclick="Backend.toggleCheckboxes(this)" class="tl_tree_checkbox">
</div>' : '').'

<table class="tl_listing' . ($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['showColumns'] ? ' showColumns' : '') . '">';

			// Automatically add the "order by" field as last column if we do not have group headers
			if ($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['showColumns'] && !in_array($firstOrderBy, $GLOBALS['TL_DCA'][$this->strTable]['list']['label']['fields']))
			{
				$GLOBALS['TL_DCA'][$this->strTable]['list']['label']['fields'][] = $firstOrderBy;
			}

			// Rename each pid to its label and resort the result (sort by parent table)
			if ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 3)
			{
				$firstOrderBy = 'pid';
				$showFields = $GLOBALS['TL_DCA'][$table]['list']['label']['fields'];

				foreach ($result as $k=>$v)
				{
					$objField = $this->Database->prepare("SELECT " . $showFields[0] . " FROM " . $this->ptable . " WHERE id=?")
											   ->limit(1)
											   ->execute($v['pid']);

					$result[$k]['pid'] = $objField->$showFields[0];
				}

				$aux = array();

				foreach ($result as $row)
				{
					$aux[] = $row['pid'];
				}

				array_multisort($aux, SORT_ASC, $result);
			}

			// Generate the table header if the "show columns" option is active
			if ($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['showColumns'])
			{
				$return .= '
  <tr>';

				foreach ($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['fields'] as $f)
				{
					$return .= '
    <th class="tl_folder_tlist col_' . $f . (($f == $firstOrderBy) ? ' ordered_by' : '') . '">'.$GLOBALS['TL_DCA'][$this->strTable]['fields'][$f]['label'][0].'</th>';
			}

				$return .= '
    <th class="tl_folder_tlist tl_right_nowrap">&nbsp;</th>
  </tr>';
			}

			// Process result and add label and buttons
			$remoteCur = false;
			$groupclass = 'tl_folder_tlist';
			$eoCount = -1;

			foreach ($result as $row)
			{

				$rowFormatted = array();

				$args = array();
				$this->current[] = $row['id'];
				$showFields = $GLOBALS['TL_DCA'][$table]['list']['label']['fields'];

				// Label
				foreach ($showFields as $k=>$v)
				{
					if (in_array($v, $this->arrDetailFields)
						&& in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'], array('radio', 'efgLookupRadio', 'select', 'efgLookupSelect', 'checkbox', 'efgLookupCheckbox', 'efgImageSelect', 'fileTree')))
					{
						$row[$v] = str_replace('|', ', ', $row[$v]);
					}

					if (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['flag'], array(5, 6, 7, 8, 9, 10)))
					{
						if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['rgxp'] == 'date')
						{
							$args[$k] = strlen($row[$v]) ? $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $row[$v]) : '';
							$rowFormatted[$v] = $args[$k];
						}
						elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['rgxp'] == 'time')
						{
							$args[$k] = strlen($row[$v]) ? $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $row[$v]) : '';
							$rowFormatted[$v] = $args[$k];
						}
						else
						{
							$args[$k] = strlen($row[$v]) ? $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $row[$v]) : '';
							$rowFormatted[$v] = $args[$k];
						}
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['multiple'])
					{
						$args[$k] = strlen($row[$v]) ? $GLOBALS['TL_DCA'][$table]['fields'][$v]['label'][0] : '-';
						$rowFormatted[$v] = $args[$k];
					}
					elseif (in_array($v, $this->arrBaseFields) && in_array($v , $this->arrOwnerFields))
					{
						if ($v == 'fd_member')
						{
							$args[$k] = $this->arrMembers[$row[$v]];
							$rowFormatted[$v] = $args[$k];
						}
						elseif ($v == 'fd_user')
						{
							$args[$k] = $this->arrUsers[$row[$v]];
							$rowFormatted[$v] = $args[$k];
						}
						elseif ($v == 'fd_member_group')
						{
							$args[$k] = $this->arrMemberGroups[$row[$v]];
							$rowFormatted[$v] = $args[$k];
						}
						elseif ($v == 'fd_user_group')
						{
							$args[$k] = $this->arrUserGroups[$row[$v]];
							$rowFormatted[$v] = $args[$k];
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
								$args_k[] = strlen($GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$option]) ? $GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$option] : $option;
							}

							$args[$k] = implode(', ', $args_k);
							$rowFormatted[$v] = $args[$k];

						}
						elseif (isset($GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]]))
						{
							$args[$k] = is_array($GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]]) ? $GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]][0] : $GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]];
						}
						elseif (($GLOBALS['TL_DCA'][$table]['fields'][$v]['eval']['isAssociative'] || array_is_assoc($GLOBALS['TL_DCA'][$table]['fields'][$v]['options'])) && isset($GLOBALS['TL_DCA'][$table]['fields'][$v]['options'][$row[$v]]))
						{
							$args[$k] = $GLOBALS['TL_DCA'][$table]['fields'][$v]['options'][$row[$v]];
						}
						else
						{
							// check multiline value
							if (!is_bool(strpos($row[$v], "\n")))
							{
								$strVal = $row[$v];
								$strVal = preg_replace('/(<\/|<)(h\d|p|div|ul|ol|li)(>)(\n)/si', "\\1\\2\\3", $strVal);
								$strVal = nl2br($strVal);
								$strVal = preg_replace('/(<\/)(h\d|p|div|ul|ol|li)(>)/si', "\\1\\2\\3\n", $strVal);
								$row[$v] = $strVal;
								unset($strVal);
							}
							$args[$k] = $row[$v];
							$rowFormatted[$v] = $args[$k];
						}
					}

				} // foreach ($showFields as $k=>$v)

				// Shorten the label it if it is too long
				$label = vsprintf((strlen($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['format']) ? $GLOBALS['TL_DCA'][$this->strTable]['list']['label']['format'] : '%s'), $args);

				if ($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['maxCharacters'] > 0 && $GLOBALS['TL_DCA'][$this->strTable]['list']['label']['maxCharacters'] < strlen(strip_tags($label)))
				{
					$this->import('String');
					$label = trim($this->String->substrHtml($label, $GLOBALS['TL_DCA'][$this->strTable]['list']['label']['maxCharacters'])) . ' …';
				}

				// Remove empty brackets (), [], {}, <> and empty tags from the label
				//$label = preg_replace('/\( *\) ?|\[ *\] ?|\{ *\} ?|< *> ?/i', '', $label);
				//$label = preg_replace('/<[^>]+>\s*<\/[^>]+>/i', '', $label);

				// Build the sorting groups
				if ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] > 0)
				{
					$current = $row[$firstOrderBy];
					$orderBy = $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['fields'];
					$sortingMode = (count($orderBy) == 1 && $firstOrderBy == $orderBy[0] && $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['flag'] != '' && $GLOBALS['TL_DCA'][$this->strTable]['fields'][$firstOrderBy]['flag'] == '') ? $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['flag'] : $GLOBALS['TL_DCA'][$this->strTable]['fields'][$firstOrderBy]['flag'];
					$remoteNew = $this->formatCurrentValue($firstOrderBy, $current, $sortingMode);

					// Add the group header
					if (!$GLOBALS['TL_DCA'][$this->strTable]['list']['label']['showColumns'] && !$GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['disableGrouping'] && ($remoteNew != $remoteCur || $remoteCur === false))
					{
						$eoCount = -1;
						$group = $this->formatGroupHeader($firstOrderBy, $remoteNew, $sortingMode, $row);
						$remoteCur = $remoteNew;

						$return .= '
  <tr>
    <td colspan="2" class="'.$groupclass.'">'.$group.'</td>
  </tr>';
						$groupclass = 'tl_folder_list';
					}
				}

				$return .= '
  <tr class="'.((++$eoCount % 2 == 0) ? 'even' : 'odd').'" onmouseover="Theme.hoverRow(this,1)" onmouseout="Theme.hoverRow(this,0)">
    ';

				$colspan = 1;

				// Call the label callback ($row, $label, $this)
				if (is_array($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['label_callback']))
				{
					$strClass = $GLOBALS['TL_DCA'][$this->strTable]['list']['label']['label_callback'][0];
					$strMethod = $GLOBALS['TL_DCA'][$this->strTable]['list']['label']['label_callback'][1];

					$this->import($strClass);
					$args = $this->$strClass->$strMethod($row, $label, $this, $args);

					// Handle strings and arrays (backwards compatibility)
					if (!$GLOBALS['TL_DCA'][$this->strTable]['list']['label']['showColumns'])
					{
						$label = is_array($args) ? implode(' ', $args) : $args;
					}
					elseif (!is_array($args))
					{
						$args = array($args);
						$colspan = count($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['fields']);
					}
				}

				// Show columns
				if ($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['showColumns'])
				{
					foreach ($args as $j=>$arg)
					{
						$return .= '<td colspan="' . $colspan . '" class="tl_file_list col_' . $GLOBALS['TL_DCA'][$this->strTable]['list']['label']['fields'][$j] . (($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['fields'][$j] == $firstOrderBy) ? ' ordered_by' : '') . '">' . (($arg != '') ? $arg : '-') . '</td>';
					}
				}
				else
				{
					$return .= '<td class="tl_file_list">' . $label . '</td>';
				}

				// Buttons ($row, $table, $root, $blnCircularReference, $childs, $previous, $next)
				$return .= (($this->Input->get('act') == 'select') ? '
    <td class="tl_file_list tl_right_nowrap"><input type="checkbox" name="IDS[]" id="ids_'.$row['id'].'" class="tl_tree_checkbox" value="'.$row['id'].'"></td>' : '
    <td class="tl_file_list tl_right_nowrap">'.$this->generateButtons($row, $this->strTable, $this->root).'</td>') . '
  </tr>';
			}

			// Close the table
			$return .= '
</table>

</div>';

			// Close the form
			if ($this->Input->get('act') == 'select')
			{
				$return .= '

<div class="tl_formbody_submit" style="text-align:right">

<div class="tl_submit_container">' . (!$GLOBALS['TL_DCA'][$this->strTable]['config']['notDeletable'] ? '
  <input type="submit" name="delete" id="delete" class="tl_submit" accesskey="d" onclick="return confirm(\''.$GLOBALS['TL_LANG']['MSC']['delAllConfirm'].'\')" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['deleteSelected']).'"> ' : '') . (!$GLOBALS['TL_DCA'][$this->strTable]['config']['notEditable'] ? '
  <input type="submit" name="edit" id="edit" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['MSC']['editSelected']).'"> ' : '') . '
</div>

</div>
</div>
</form>';
			}
		}

		return $return;
	}


	/**
	 * Build the sort panel and return it as string
	 * @return string
	 */
	protected function panel()
	{
		$filter = $this->filterMenu();
		$search = $this->searchMenu();
		$limit = $this->limitMenu();
		$sort = $this->sortMenu();

		if (!strlen($filter) && !strlen($search) && !strlen($limit) && !strlen($sort))
		{
			return '';
		}

		if (!strlen($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['panelLayout']))
		{
			return '';
		}

		if ($this->Input->post('FORM_SUBMIT') == 'tl_filters')
		{
			$this->reload();
		}

		$return = '';
		$panelLayout = $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['panelLayout'];
		$arrPanels = trimsplit(';', $panelLayout);
		$intLast = count($arrPanels) - 1;

		for ($i=0; $i<count($arrPanels); $i++)
		{
			$panels = '';
			$submit = '';
			$arrSubPanels = trimsplit(',', $arrPanels[$i]);

			foreach ($arrSubPanels as $strSubPanel)
			{
				if (strlen($$strSubPanel))
				{
					$panels = $$strSubPanel . $panels;
				}
			}

			if ($i == $intLast)
			{
				$submit = '

<div class="tl_submit_panel tl_subpanel">
<input type="image" name="filter" id="filter" src="' . TL_FILES_URL . 'system/themes/' . $this->getTheme() . '/images/reload.gif" class="tl_img_submit" title="' . $GLOBALS['TL_LANG']['MSC']['apply'] . '" alt="' . $GLOBALS['TL_LANG']['MSC']['apply'] . '">
</div>';
			}

			if (strlen($panels))
			{
				$return .= '
<div class="tl_panel">'.$submit.$panels.'

<div class="clear"></div>

</div>';
			}
		}

		$return = '
<form action="'.ampersand($this->Environment->request, true).'" class="tl_form" method="post">
<div class="tl_formbody">
<input type="hidden" name="FORM_SUBMIT" value="tl_filters">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
' . $return . '
</div>
</form>
';

		return $return;
	}


	/**
	 * Return a search form that allows to search results using regular expressions
	 * @return string
	 */
	protected function searchMenu()
	{
		$searchFields = array();
		$session = $this->Session->getData();

		// Get search fields
		foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'] as $k=>$v)
		{
			if ($v['search'])
			{
				$searchFields[] = $k;
			}
		}

		// Return if there are no search fields
		if (empty($searchFields))
		{
			return '';
		}

		$strSessionKey = ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 4) ? $this->strTable.'_'.CURRENT_ID : (strlen($this->strFormKey)) ? $this->strFormKey : $this->strTable;

		// Store search value in the current session
		if ($this->Input->post('FORM_SUBMIT') == 'tl_filters')
		{

			$session['search'][$strSessionKey]['value'] = '';
			$session['search'][$strSessionKey]['field'] = $this->Input->post('tl_field', true);

			// Make sure the regular expression is valid
			if ($this->Input->postRaw('tl_value') != '')
			{
				$sqlSearchField = '(SELECT value FROM tl_formdata_details WHERE ff_name=\'' . $this->Input->post('tl_field', true) .'\' AND pid=f.id)';
				try
				{
					$this->Database->prepare("SELECT * ".(count($this->arrSqlDetails) > 0 ? ','.implode(', ', array_values($this->arrSqlDetails)) : '')." FROM " . $this->strTable . " f WHERE " . $sqlSearchField . " REGEXP ?")
								   ->limit(1)
								   ->execute($this->Input->postRaw('tl_value'));

					$session['search'][$strSessionKey]['value'] = $this->Input->postRaw('tl_value');
				}
				catch (Exception $e) {}
			}

			$this->Session->setData($session);
		}

		// Set search value from session
		elseif ($session['search'][$strSessionKey]['value'] != '')
		{
			$sqlSearchField = $session['search'][$strSessionKey]['field'];
			if (in_array($sqlSearchField, $this->arrDetailFields) )
			{
				$sqlSearchField = '(SELECT value FROM tl_formdata_details WHERE ff_name=\'' . $session['search'][$strSessionKey]['field'] .'\' AND pid=f.id)';
			}
			if (substr($GLOBALS['TL_CONFIG']['dbCollation'], -3) == '_ci')
			{
				$this->procedure[] = "LOWER(CAST(".$sqlSearchField." AS CHAR)) REGEXP LOWER(?)";
			}
			else
			{
				$this->procedure[] = "CAST(".$sqlSearchField." AS CHAR) REGEXP ?";
			}

			$this->values[] = $session['search'][$strSessionKey]['value'];
		}

		$options_sorter = array();

		foreach ($searchFields as $field)
		{
			$option_label = strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'][0]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'][0] : $GLOBALS['TL_LANG']['MSC'][$field];
			$options_sorter[utf8_romanize($option_label).'_'.$field] = '  <option value="'.specialchars($field).'"'.(($field == $session['search'][$this->strTable]['field']) ? ' selected="selected"' : '').'>'.$option_label.'</option>';		}

		// Sort by option values
		$options_sorter = natcaseksort($options_sorter);
		$active = strlen($session['search'][$strSessionKey]['value']) ? true : false;

		return '

<div class="tl_search tl_subpanel">
<strong>' . $GLOBALS['TL_LANG']['MSC']['search'] . ':</strong>
<select name="tl_field" class="tl_select' . ($active ? ' active' : '') . '">
'.implode("\n", $options_sorter).'
</select>
<span> = </span>
<input type="text" name="tl_value" class="tl_text' . ($active ? ' active' : '') . '" value="'.specialchars($session['search'][$strSessionKey]['value']).'">
</div>';
	}


	/**
	 * Return a select menu that allows to sort results by a particular field
	 * @return string
	 */
	protected function sortMenu()
	{
		if ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] != 2 && $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] != 4)
		{
			return '';
		}

		$sortingFields = array();

		// Get sorting fields
		foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'] as $k=>$v)
		{
			if ($v['sorting'])
			{
				$sortingFields[] = $k;
			}
		}

		// Return if there are no sorting fields
		if (empty($sortingFields))
		{
			return '';
		}

		$this->bid = 'tl_buttons_a';
		$session = $this->Session->getData();
		$orderBy = $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['fields'];
		$firstOrderBy = preg_replace('/\s+.*$/i', '', $orderBy[0]);

		$strSessionKey = ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 4) ? $this->strTable.'_'.CURRENT_ID : (strlen($this->strFormKey)) ? $this->strFormKey : $this->strTable;

		// Add PID to order fields
		if ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 3 && $this->Database->fieldExists('pid', $this->strTable))
		{
			array_unshift($orderBy, 'pid');
		}

		// Set sorting from user input
		if ($this->Input->post('FORM_SUBMIT') == 'tl_filters')
		{
			$session['sorting'][$strSessionKey] = in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$this->Input->post('tl_sort')]['flag'], array(2, 4, 6, 8, 10, 12)) ? $this->Input->post('tl_sort').' DESC' : $this->Input->post('tl_sort');
			$this->Session->setData($session);
		}

		// Overwrite the "orderBy" value with the session value
		elseif (strlen($session['sorting'][$strSessionKey]))
		{
			$overwrite = preg_quote(preg_replace('/\s+.*$/i', '', $session['sorting'][$strSessionKey]), '/');
			$orderBy = array_diff($orderBy, preg_grep('/^'.$overwrite.'/i', $orderBy));

			array_unshift($orderBy, $session['sorting'][$strSessionKey]);

			$this->firstOrderBy = $overwrite;
			$this->orderBy = $orderBy;
		}

		$options_sorter = array();

		// Sorting fields
		foreach ($sortingFields as $field)
		{
			$options_label = strlen(($lbl = is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label']) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'][0] : $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'])) ? $lbl : $GLOBALS['TL_LANG']['MSC'][$field];

			if (is_array($options_label))
			{
				$options_label = $options_label[0];
			}

			$options_sorter[$options_label] = '  <option value="'.specialchars($field).'"'.((!strlen($session['sorting'][$strSessionKey]) && $field == $firstOrderBy || $field == str_replace(' DESC', '', $session['sorting'][$strSessionKey])) ? ' selected="selected"' : '').'>'.$options_label.'</option>';
		}

		// Sort by option values
		uksort($options_sorter, 'strcasecmp');

		return '

<div class="tl_sorting tl_subpanel">
<strong>' . $GLOBALS['TL_LANG']['MSC']['sortBy'] . ':</strong>
<select name="tl_sort" id="tl_sort" class="tl_select">
'.implode("\n", $options_sorter).'
</select>
</div>';
	}


	/**
	 * Return a select menu to limit results
	 * @param boolean
	 * @return string
	 */
	protected function limitMenu($blnOptional=false)
	{
		$session = $this->Session->getData();
		$filter = ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 4) ? $this->strTable.'_'.CURRENT_ID : (strlen($this->strFormKey)) ? $this->strFormKey : $this->strTable;

		if (is_array($this->procedure))
		{
			$this->procedure = array_unique($this->procedure);
		}
		if (is_array($this->values))
		{
			$this->values = array_unique($this->values);
		}

		// Set limit from user input
		if ($this->Input->post('FORM_SUBMIT') == 'tl_filters' || $this->Input->post('FORM_SUBMIT') == 'tl_filters_limit')
		{
			if ($this->Input->post('tl_limit') != 'tl_limit')
			{
				$session['filter'][$filter]['limit'] = $this->Input->post('tl_limit');
			}
			else
			{
				unset($session['filter'][$filter]['limit']);
			}

			$this->Session->setData($session);

			if ($this->Input->post('FORM_SUBMIT') == 'tl_filters_limit')
			{
				$this->reload();
			}
		}

		// Set limit from table configuration
		else
		{
			$this->limit = strlen($session['filter'][$filter]['limit']) ? (($session['filter'][$filter]['limit'] == 'all') ? null : $session['filter'][$filter]['limit']) : '0,' . $GLOBALS['TL_CONFIG']['resultsPerPage'];

			$sqlQuery = '';
			$sqlSelect = '';
			$sqlDetailFields = '';
			$sqlWhere = '';

			if (!empty($this->procedure))
			{
				$arrProcedure = $this->procedure;
				foreach ($arrProcedure as $kProc => $vProc)
				{
					$arrParts = preg_split('/[\s=><\!]/si', $vProc);
					$strProcField = $arrParts[0];
					if (in_array($strProcField, $this->arrDetailFields) )
					{
						$arrProcedure[$kProc] = "(SELECT value FROM tl_formdata_details WHERE ff_name='" . $strProcField . "' AND pid=f.id)=?";
					}

				}
				$sqlWhere = " WHERE " . implode(' AND ', $arrProcedure);
			}
			$sqlSelect = "SELECT COUNT(*) AS total FROM " . $this->strTable . " f";
			$sqlQuery = $sqlSelect . $sqlWhere;

			$objTotal = $this->Database->prepare($sqlQuery)
									   ->execute($this->values);
			$total = $objTotal->total;
			$blnIsMaxResultsPerPage = false;

			// Overall limit
			if ($total > $GLOBALS['TL_CONFIG']['maxResultsPerPage'] && ($this->limit === null || preg_replace('/^.*,/i', '', $this->limit) == $GLOBALS['TL_CONFIG']['maxResultsPerPage']))
			{
				if ($this->limit === null)
				{
					$this->limit = '0,' . $GLOBALS['TL_CONFIG']['maxResultsPerPage'];
				}

				$blnIsMaxResultsPerPage = true;
				$GLOBALS['TL_CONFIG']['resultsPerPage'] = $GLOBALS['TL_CONFIG']['maxResultsPerPage'];
				$session['filter'][$filter]['limit'] = $GLOBALS['TL_CONFIG']['maxResultsPerPage'];
			}

			// Build options
			if ($total > 0)
			{
				$options = '';
				$options_total = ceil($total / $GLOBALS['TL_CONFIG']['resultsPerPage']);

				// Reset limit if other parameters have decreased the number of results
				if ($this->limit !== null && ($this->limit == '' || preg_replace('/,.*$/i', '', $this->limit) > $total))
				{
					$this->limit = '0,'.$GLOBALS['TL_CONFIG']['resultsPerPage'];
				}

				// Build options
				for ($i=0; $i<$options_total; $i++)
				{
					$this_limit = ($i*$GLOBALS['TL_CONFIG']['resultsPerPage']).','.$GLOBALS['TL_CONFIG']['resultsPerPage'];
					$upper_limit = ($i*$GLOBALS['TL_CONFIG']['resultsPerPage']+$GLOBALS['TL_CONFIG']['resultsPerPage']);

					if ($upper_limit > $total)
					{
						$upper_limit = $total;
					}

					$options .= '
  <option value="'.$this_limit.'"' . $this->optionSelected($this->limit, $this_limit) . '>'.($i*$GLOBALS['TL_CONFIG']['resultsPerPage']+1).' - '.$upper_limit.'</option>';
				}

				if (!$blnIsMaxResultsPerPage)
				{
					$options .= '
  <option value="all"' . $this->optionSelected($this->limit, null) . '>'.$GLOBALS['TL_LANG']['MSC']['filterAll'].'</option>';
				}
			}

			// Return if there is only one page
			if ($blnOptional && ($total < 1 || $options_total < 2))
			{
				return '';
			}

			$fields .= '
<select name="tl_limit" class="tl_select' . (($session['filter'][$filter]['limit'] != 'all' && $total > $GLOBALS['TL_CONFIG']['resultsPerPage']) ? ' active' : '') . '" onchange="this.form.submit()">
  <option value="tl_limit">'.$GLOBALS['TL_LANG']['MSC']['filterRecords'].'</option>'.$options.'
</select> ';
		}

		return '

<div class="tl_limit tl_subpanel">
<strong>' . $GLOBALS['TL_LANG']['MSC']['showOnly'] . ':</strong> '.$fields.'
</div>';
	}


	/**
	 * Generate the filter panel and return it as HTML string
	 * @return string
	 */
	protected function filterMenu()
	{
		$fields = '';
		$this->bid = 'tl_buttons_a';
		$sortingFields = array();
		$session = $this->Session->getData();
		$filter = ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 4) ? $this->strTable.'_'.CURRENT_ID : (strlen($this->strFormKey)) ? $this->strFormKey : $this->strTable;

		// Get sorting fields
		foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'] as $k=>$v)
		{
			if ($v['filter'])
			{
				$sortingFields[] = $k;
			}
		}

		// Return if there are no sorting fields
		if (empty($sortingFields))
		{
			return '';
		}

		// Set filter from user input
		if ($this->Input->post('FORM_SUBMIT') == 'tl_filters')
		{
			foreach ($sortingFields as $field)
			{
				if ($this->Input->post($field, true) != 'tl_'.$field)
				{
					$session['filter'][$filter][$field] = $this->Input->post($field, true);
				}
				else
				{
					unset($session['filter'][$filter][$field]);
				}
			}

			// add filter if called by special form dependent BE nav item
			if ($this->strFormFilterKey != '' && $this->strFormFilterValue != '')
			{
				$session['filter'][$filter][$this->strFormFilterKey] = $this->strFormFilterValue;
			}

			$this->Session->setData($session);
		}

		// Set filter from table configuration
		else
		{
			foreach ($sortingFields as $field)
			{
				if (isset($session['filter'][$filter][$field]))
				{
					// Sort by day
					if (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(5, 6)))
					{
						if ($session['filter'][$filter][$field] == '')
						{
							$this->procedure[] = $field . "=''";
						}
						else
						{
							$objDate = new Date($session['filter'][$filter][$field]);
							$this->procedure[] = $field . ' BETWEEN ? AND ?';
							$this->values[] = $objDate->dayBegin;
							$this->values[] = $objDate->dayEnd;
						}
					}

					// Sort by month
					elseif (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(7, 8)))
					{
						if ($session['filter'][$filter][$field] == '')
						{
							$this->procedure[] = $field . "=''";
						}
						else
						{
							$objDate = new Date($session['filter'][$filter][$field]);
							$this->procedure[] = $field . ' BETWEEN ? AND ?';
							$this->values[] = $objDate->monthBegin;
							$this->values[] = $objDate->monthEnd;
						}
					}

					// Sort by year
					elseif (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(9, 10)))
					{
						if ($session['filter'][$filter][$field] == '')
						{
							$this->procedure[] = $field . "=''";
						}
						else
						{
							$objDate = new Date($session['filter'][$filter][$field]);
							$this->procedure[] = $field . ' BETWEEN ? AND ?';
							$this->values[] = $objDate->yearBegin;
							$this->values[] = $objDate->yearEnd;
						}
					}

					// Manual filter
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['multiple'])
					{
						// CSV lists (see #2890)
						if (isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['csv']))
						{
							$this->procedure[] = $this->Database->findInSet('?', $field, true);
							$this->values[] = $session['filter'][$filter][$field];
						}
						else
						{
							$this->procedure[] = $field . ' LIKE ?';
							$this->values[] = '%"' . $session['filter'][$filter][$field] . '"%';
						}
					}

					// Other sort algorithm
					else
					{
						$this->procedure[] = $field . '=?';
						$this->values[] = $session['filter'][$filter][$field];
					}
				}
			}
		}

		// Add sorting options
		foreach ($sortingFields as $cnt => $field)
		{
			$arrValues = array();
			$arrProcedure = array();

			if ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 4)
			{
				$arrProcedure[] = 'pid=?';
				$arrValues[] = CURRENT_ID;
			}

			// add condition if called form specific formdata
			if ($this->strFormFilterKey != '' && $this->strFormFilterValue != '')
			{
				$arrProcedure[] = $this->strFormFilterKey . '=?';
				$arrValues[] = $this->strFormFilterValue;
			}

			if (is_array($this->root) && !empty($this->root))
			{
				$arrProcedure[] = "id IN(" . implode(',', array_map('intval', $this->root)) . ")";
			}

			if (in_array($field, $this->arrBaseFields) )
			{
				$sqlField = $field;
			}
			elseif (in_array($field, $this->arrDetailFields) )
			{
				$sqlField = "SELECT DISTINCT(value) FROM tl_formdata_details WHERE ff_name='" . $field . "' AND pid=f.id";
			}

			$objFields = $this->Database->prepare("SELECT DISTINCT(" . $sqlField . ") AS `". $field . "` FROM " . $this->strTable . " f ". ((is_array($arrProcedure) && strlen($arrProcedure[0])) ? ' WHERE ' . implode(' AND ', $arrProcedure) : ''))
										->execute($arrValues);

			// Begin select menu
			$fields .= '
<select name="'.$field.'" id="'.$field.'" class="tl_select' . (isset($session['filter'][$filter][$field]) ? ' active' : '') . '">
  <option value="tl_'.$field.'">'.(is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label']) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'][0] : $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label']).'</option>
  <option value="tl_'.$field.'">---</option>';

			if ($objFields->numRows)
			{
				$options = $objFields->fetchEach($field);

				// Sort by day
				if (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(5, 6)))
				{
					($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'] == 6) ? rsort($options) : sort($options);

					foreach ($options as $k=>$v)
					{
						if ($v == '')
						{
							$options[$v] = '-';
						}
						else
						{
							$options[$v] = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $v);
						}

						unset($options[$k]);
					}
				}

				// Sort by month
				elseif (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(7, 8)))
				{
					($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'] == 8) ? rsort($options) : sort($options);

					foreach ($options as $k=>$v)
					{
						if ($v == '')
						{
							$options[$v] = '-';
						}
						else
						{
							$options[$v] = date('Y-m', $v);
							$intMonth = (date('m', $v) - 1);

							if (isset($GLOBALS['TL_LANG']['MONTHS'][$intMonth]))
							{
								$options[$v] = $GLOBALS['TL_LANG']['MONTHS'][$intMonth] . ' ' . date('Y', $v);
							}
						}

						unset($options[$k]);
					}
				}

				// Sort by year
				elseif (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(9, 10)))
				{
					($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'] == 10) ? rsort($options) : sort($options);

					foreach ($options as $k=>$v)
					{
						if ($v == '')
						{
							$options[$v] = '-';
						}
						else
						{
							$options[$v] = date('Y', $v);
						}

						unset($options[$k]);
					}
				}

				// Manual filter
				if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['multiple'])
				{
					$moptions = array();

					foreach($options as $option)
					{
						// CSV lists (see #2890)
						if (isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['csv']))
						{
							$doptions = trimsplit($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['csv'], $option);
						}
						else
						{
							$doptions = deserialize($option);
						}

						if (is_array($doptions))
						{
							$moptions = array_merge($moptions, $doptions);
						}
					}

					$options = $moptions;
				}

				$options = array_unique($options);
				$options_callback = array();

				// Load options callback
				if ($field != 'form' && is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options_callback']) && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['reference'])
				{
					$strClass = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options_callback'][0];
					$strMethod = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options_callback'][1];

					$this->import($strClass);
					$options_callback = $this->$strClass->$strMethod($this);

					// Sort options according to the keys of the callback array
					$options = array_intersect(array_keys($options_callback), $options);
				}

				$options_sorter = array();
				$blnDate = in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(5, 6, 7, 8, 9, 10));

				// Options
				foreach ($options as $kk=>$vv)
				{
					$value = $blnDate ? $kk : $vv;

					// Replace the ID with the foreign key
					if (isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['foreignKey']))
					{
						$key = explode('.', $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['foreignKey'], 2);

						$objParent = $this->Database->prepare("SELECT " . $key[1] . " AS value FROM " . $key[0] . " WHERE id=?")
													->limit(1)
													->execute($vv);

						if ($objParent->numRows)
						{
							$vv = $objParent->value;
						}
					}

					// Replace boolean checkbox value with "yes" and "no"
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['isBoolean'] || ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['multiple']))
					{
						$vv = ($vv != '') ? $GLOBALS['TL_LANG']['MSC']['yes'] : $GLOBALS['TL_LANG']['MSC']['no'];
					}

					// Options callback
					elseif (is_array($options_callback) && !empty($options_callback))
					{
						$vv = $options_callback[$vv];
					}

					// Get the name of the parent record (see #2703)
					elseif ($field == 'pid')
					{
						$this->loadDataContainer($this->ptable);
						$showFields = $GLOBALS['TL_DCA'][$this->ptable]['list']['label']['fields'];

						if (!$showFields[0])
						{
							$showFields[0] = 'id';
						}

						$objShowFields = $this->Database->prepare("SELECT " . $showFields[0] . " FROM ". $this->ptable . " WHERE id=?")
														->limit(1)
														->execute($vv);

						if ($objShowFields->numRows)
						{
							$vv = $objShowFields->$showFields[0];
						}
					}

					$option_label = '';

					// Use reference array
					if (isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['reference']))
					{
						$option_label = is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['reference'][$vv]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['reference'][$vv][0] : $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['reference'][$vv];
					}

					// Associative array
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['isAssociative'] || array_is_assoc($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options']))
					{
						$option_label = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options'][$vv];
					}

					// No empty options allowed
					if (!strlen($option_label))
					{
						$option_label = strlen($vv) ? $vv : '-';
					}

					$options_sorter['  <option value="' . specialchars($value) . '"' . ((isset($session['filter'][$filter][$field]) && $value == $session['filter'][$filter][$field]) ? ' selected="selected"' : '').'>'.$option_label.'</option>'] = utf8_romanize($option_label);
				}

				// Sort by option values
				if (!$blnDate)
				{
					natcasesort($options_sorter);

					if (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(2, 4, 12)))
					{
						$options_sorter = array_reverse($options_sorter, true);
					}
				}

				$fields .= "\n" . implode("\n", array_keys($options_sorter));
			}

			// End select menu
			$fields .= '
</select> ';

			// Force a line-break after six elements (see #3777)
			if ((($cnt + 1) % 6) == 0)
			{
				$fields .= '<br>';
			}
		}

		return '

<div class="tl_filter tl_subpanel">
<strong>' . $GLOBALS['TL_LANG']['MSC']['filter'] . ':</strong> ' . $fields . '
</div>';
	}


	/**
	 * Return the formatted group header as string
	 * @param string
	 * @param mixed
	 * @param integer
	 * @return string
	 */
	protected function formatCurrentValue($field, $value, $mode)
	{
		$remoteNew = $value; // see #3861

		if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['multiple'])
		{
			$remoteNew = ($value != '') ? ucfirst($GLOBALS['TL_LANG']['MSC']['yes']) : ucfirst($GLOBALS['TL_LANG']['MSC']['no']);
		}
		elseif (isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['foreignKey']))
		{
			$key = explode('.', $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['foreignKey'], 2);

			$objParent = $this->Database->prepare("SELECT " . $key[1] . " AS value FROM " . $key[0] . " WHERE id=?")
										->limit(1)
										->execute($value);

			if ($objParent->numRows)
			{
				$remoteNew = $objParent->value;
			}
		}
		elseif (in_array($mode, array(1, 2)))
		{
			$remoteNew = ($value != '') ? ucfirst(utf8_substr($value , 0, 1)) : '-';
		}
		elseif (in_array($mode, array(3, 4)))
		{
			if (!isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['length']))
			{
				$GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['length'] = 2;
			}

			$remoteNew = ($value != '') ? ucfirst(utf8_substr($value , 0, $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['length'])) : '-';
		}
		elseif (in_array($mode, array(5, 6)))
		{
			$remoteNew = ($value != '') ? $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $value) : '-';
		}
		elseif (in_array($mode, array(7, 8)))
		{
			$remoteNew = ($value != '') ? date('Y-m', $value) : '-';
			$intMonth = ($value != '') ? (date('m', $value) - 1) : '-';

			if (isset($GLOBALS['TL_LANG']['MONTHS'][$intMonth]))
			{
				$remoteNew = ($value != '') ? $GLOBALS['TL_LANG']['MONTHS'][$intMonth] . ' ' . date('Y', $value) : '-';
			}
		}
		elseif (in_array($mode, array(9, 10)))
		{
			$remoteNew = ($value != '') ? date('Y', $value) : '-';
		}
		else
		{
			if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['multiple'])
			{
				$remoteNew = ($value != '') ? $field : '';
			}
			elseif (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['reference']))
			{
				$remoteNew = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['reference'][$value];
			}
			elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['isAssociative'] || array_is_assoc($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options']))
			{
				$remoteNew = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options'][$value];
			}
			else
			{
				$remoteNew = $value;
			}

			if (is_array($remoteNew))
			{
				$remoteNew = $remoteNew[0];
			}

			if (empty($remoteNew))
			{
				$remoteNew = '-';
			}
		}

		return $remoteNew;
	}


	/**
	 * Return the formatted group header as string
	 * @param string
	 * @param mixed
	 * @param integer
	 * @param array
	 * @return string
	 */
	protected function formatGroupHeader($field, $value, $mode, $row)
	{
		$group = '';
		static $lookup = array();

		if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['isAssociative'] || array_is_assoc($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options']))
		{
			$group = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options'][$value];
		}
		elseif (is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options_callback']))
		{
			if (!isset($lookup[$field]))
			{
				$strClass = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options_callback'][0];
				$strMethod = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['options_callback'][1];

				$this->import($strClass);
				$lookup[$field] = $this->$strClass->$strMethod($this);
			}

			$group = $lookup[$field][$value];
		}
		else
		{
			$group = is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['reference'][$value]) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['reference'][$value][0] : $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['reference'][$value];
		}

		if (empty($group))
		{
			$group = is_array($GLOBALS['TL_LANG'][$this->strTable][$value]) ? $GLOBALS['TL_LANG'][$this->strTable][$value][0] : $GLOBALS['TL_LANG'][$this->strTable][$value];
		}

		if (empty($group))
		{
			$group = $value;

			if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['isBoolean'] && $value != '-')
			{
				$group = is_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label']) ? $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'][0] : $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['label'];
			}
		}

		// Call the group callback ($group, $sortingMode, $firstOrderBy, $row, $this)
		if (is_array($GLOBALS['TL_DCA'][$this->strTable]['list']['label']['group_callback']))
		{
			$strClass = $GLOBALS['TL_DCA'][$this->strTable]['list']['label']['group_callback'][0];
			$strMethod = $GLOBALS['TL_DCA'][$this->strTable]['list']['label']['group_callback'][1];

			$this->import($strClass);
			$group = $this->$strClass->$strMethod($group, $mode, $field, $row, $this);
		}

		return $group;
	}


	/**
	 * Check if we need to preload TinyMCE
	 */
	protected function checkForTinyMce()
	{
		if (!isset($GLOBALS['TL_DCA'][$this->strTable]['subpalettes']))
		{
			return;
		}

		foreach ($GLOBALS['TL_DCA'][$this->strTable]['subpalettes'] as $palette)
		{
			$fields = trimsplit(',', $palette);

			foreach ($fields as $field)
			{
				if (!isset($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['rte']))
				{
					continue;
				}

				$rte = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['rte'];

				if (strncmp($rte, 'tiny', 4) !== 0)
				{
					continue;
				}

				list ($file, $type) = explode('|', $rte);
				$key = 'ctrl_' . $field;

				$GLOBALS['TL_RTE'][$file][$key] = array
				(
					'id'   => $key,
					'file' => $file,
					'type' => $type
				);
			}
		}
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
		if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['eval']['rgxp'] )
		{
			$rgxp = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['eval']['rgxp'];
		}
		else
		{
			$rgxp = $this->arrFF[$k]['rgxp'];
		}

		// Array
		if (is_array($value))
		{
			$value = implode(', ', $value);
		}

		// Date and time
		if ($value && $rgxp == 'date')
		{
			$value = $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $value);
		}
		elseif ($value && $rgxp == 'time')
		{
			$value = $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $value);
		}
		elseif ($value && $rgxp == 'datim')
		{
			$value = $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $value);
		}
		elseif ($value && ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['inputType']=='checkbox'
				|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['inputType']=='efgLookupCheckbox'
				|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['inputType']=='select'
				|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['inputType']=='conditionalselect'
				|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['inputType']=='efgLookupSelect'
				|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$k]['inputType']=='radio') )
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
			if ($k == 'fd_user')
			{
				$value = $this->arrUsers[$value];
			}
			if ($k == 'fd_member_group')
			{
				$value = $this->arrMemberGroups[$value];
			}
			if ($k == 'fd_user_group')
			{
				$value = $this->arrUserGroups[$value];
			}
		}

		return $value;
	}


	/**
	 * Send confirmation mail
	 * @param integer
	 * @param integer
	 * @return string
	 */
	public function mail($intID=false, $ajaxId=false)
	{

		$blnSend = false;

		if (strlen($this->Input->get('token')) && $this->Input->get('token') == $this->Session->get('fd_mail_send'))
		{
			$blnSend = true;
		}

		$strFormFilter = ($this->strTable == 'tl_formdata' && strlen($this->strFormKey) ? $this->sqlFormFilter : '');
		$table_alias = ($this->strTable == 'tl_formdata' ? ' f' : '');

		if ($intID)
		{
			$this->intId = $intID;
		}

		$return = '';
		$this->values[] = $this->intId;
		$this->procedure[] = 'id=?';
		$this->blnCreateNewVersion = false;


		// Get current record
		$sqlQuery = "SELECT * " .(count($this->arrSqlDetails) > 0 ? ', '.implode(',' , array_values($this->arrSqlDetails)) : '') ." FROM " . $this->strTable . $table_alias;
		$sqlWhere = " WHERE id=?";
		if ( $sqlWhere != '')
		{
			$sqlQuery .= $sqlWhere;
		}

		$objRow = $this->Database->prepare($sqlQuery)
								->limit(1)
								->execute($this->intId);

		// Redirect if there is no record with the given ID
		if ($objRow->numRows < 1)
		{
			$this->log('Could not load record ID "'.$this->intId.'" of table "'.$this->strTable.'"!', 'DC_Table edit()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$arrSubmitted = $objRow->fetchAssoc();
		$arrFiles = array();

		// Form
		$intFormId = 0;

		if (count($GLOBALS['TL_DCA'][$this->strTable]['tl_formdata']['detailFields']))
		{
			// try to get Form ID
			foreach ($GLOBALS['TL_DCA'][$this->strTable]['tl_formdata']['detailFields'] as $strField)
			{
				if ($intFormId > 0) break;
				if(strlen($GLOBALS['TL_DCA'][$this->strTable]['fields'][$strField]['f_id']))
				{
					$intFormId = intval($GLOBALS['TL_DCA'][$this->strTable]['fields'][$strField]['f_id']);
					$objForm = $this->Database->prepare("SELECT * FROM tl_form WHERE id=?")
						->limit(1)
						->execute($intFormId);
				}
			}
		}

		if ($intFormId == 0)
		{
			$objForm = $this->Database->prepare("SELECT * FROM tl_form WHERE title=?")
					->limit(1)
					->execute($arrSubmitted['form']);
		}

		if ($objForm->numRows < 1)
		{
			$this->log('Could not load form by ID ' . $intFormId . ' or title "'.$arrSubmitted['form'].'" of table "tl_form"!', 'DC_Formdata mail()', TL_ERROR);
			$this->redirect('typolight/main.php?act=error');
		}

		$arrForm = $objForm->fetchAssoc();

		if (strlen($arrForm['id']))
		{
			$arrFormFields = $this->FormData->getFormfieldsAsArray($arrForm['id']);
		}

		// Types of form fields with storable data
		$arrFFstorable = $this->FormData->arrFFstorable;

		if (empty($arrForm['confirmationMailSubject']) || (empty($arrForm['confirmationMailText']) && empty($arrForm['confirmationMailTemplate'])))
		{
			return '<p class="tl_error">Can not send this form data record.<br>Missing "Subject", "Text of confirmation mail" or "HTML-template for confirmation mail"<br>Please check configuration of form in form generator.</p>';
		}

		$this->import('String');
		$messageText = '';
		$messageHtml = '';
		$messageHtmlTmpl = '';
		$strRecipient  = '';
		$arrRecipient = array();
		$sender = '';
		$senderName = '';
		$attachments = array();

		$blnSkipEmpty = ($arrForm['confirmationMailSkipEmpty']) ? true : false;
		$blnStoreOptionsValues = ($arrForm['efgStoreValues']) ? true : false;

		$dirImages = '';

		$sender = $arrForm['confirmationMailSender'];
		if(strlen($sender))
		{
			$sender = str_replace(array('[', ']'), array('<', '>'), $sender);
			if (strpos($sender, '<')>0)
			{
				preg_match('/(.*)?<(\S*)>/si', $sender, $parts);
				$sender = $parts[2];
				$senderName = trim($parts[1]);
			}
		}

		$recipientFieldName = $arrForm['confirmationMailRecipientField'];

		if (strlen($recipientFieldName) && $arrSubmitted[$recipientFieldName])
		{
			$varRecipient = $arrSubmitted[$recipientFieldName];
			// handle efg option 'save options of values' for field types radio, select, checkbox
			if (in_array($arrFormFields[$recipientFieldName]['type'], array('radio', 'select', 'checkbox')))
			{
				if (!$blnStoreOptionsValues)
				{
					$arrRecipient = $this->FormData->prepareDbValForWidget($varRecipient, $arrFormFields[$recipientFieldName], false);
					if (count($arrRecipient))
					{
						$varRecipient = implode(', ', $arrRecipient);
					}
					unset($arrRecipient);
				}
			}
			$varRecipient = str_replace('|', ',', $varRecipient);
		}

		if (strlen($varRecipient) || strlen($arrForm['confirmationMailRecipient']))
		{
			$arrRecipient = array_unique(array_merge(trimsplit(',', $varRecipient), trimsplit(',', $arrForm['confirmationMailRecipient'])));
		}

		if ($this->Input->get('recipient'))
		{
			$arrRecipient = array_unique(trimsplit(',', $this->Input->get('recipient')));
		}

		if (is_array($arrRecipient))
		{
			$strRecipient = implode(', ', $arrRecipient);

			// handle insert tag {{user::email}} in recipient fields
			if (!is_bool(strpos($strRecipient, "{{user::email}}")) && $arrSubmitted['fd_member'] > 0)
			{
				$objUser = $this->Database->prepare("SELECT `email` FROM `tl_member` WHERE id=?")
									->limit(1)
									->execute($arrSubmitted['fd_member']);

				$arrRecipient = array_map("str_replace", array_fill(0, count($arrRecipient), "{{user::email}}"), array_fill(0, count($arrRecipient), $objUser->email), $arrRecipient);
				$strRecipient = implode(', ', $arrRecipient);
			}
		}

		$subject = $this->String->decodeEntities($arrForm['confirmationMailSubject']);
		$messageText = $this->String->decodeEntities($arrForm['confirmationMailText']);
		$messageHtmlTmpl = $arrForm['confirmationMailTemplate'];

		if ( $messageHtmlTmpl != '' )
		{
			$fileTemplate = new File($messageHtmlTmpl);
			if ( $fileTemplate->mime == 'text/html' )
			{
				$messageHtml = $fileTemplate->getContent();
			}
		}

		// prepare insert tags to handle separate from 'condition tags'
		$subject = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $subject);
		if (strlen($messageText))
		{
			$messageText = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $messageText);
		}
		if (strlen($messageHtml))
		{
			$messageHtml = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $messageHtml);
		}

		// replace 'condition tags'
		$blnEvalSubject = $this->FormData->replaceConditionTags($subject);
		$blnEvalMessageText = $this->FormData->replaceConditionTags($messageText);
		$blnEvalMessageHtml = $this->FormData->replaceConditionTags($messageHtml);

		// Replace tags in messageText, messageHtml ...
 		$tags = array();
 		// preg_match_all('/{{[^{}]+}}/i', $messageText . $messageHtml . $subject . $sender, $tags);
 		preg_match_all('/__BRCL__.*?__BRCR__/si', $messageText . $messageHtml . $subject . $sender, $tags);

 		// Replace tags of type {{form::<form field name>}}
		// .. {{form::uploadfieldname?attachment=true}}
		// .. {{form::fieldname?label=Label for this field: }}
 		foreach ($tags[0] as $tag)
 		{
			//$elements = explode('::', preg_replace(array('/^{{/i', '/}}$/i'), array('',''), $tag));
			$elements = explode('::', preg_replace(array('/^__BRCL__/i', '/__BRCR__$/i'), array('',''), $tag));
			switch (strtolower($elements[0]))
 			{
 				// Form
 				case 'form':
 					$strKey = $elements[1];
					$arrKey = explode('?', $strKey);
					$strKey = $arrKey[0];

 					$arrTagParams = null;
					if (isset($arrKey[1]) && strlen($arrKey[1]))
					{
						$arrTagParams = $this->FormData->parseInsertTagParams($tag);
					}

					$arrField = $arrFormFields[$strKey];
					$strType = $arrField['type'];
					if (!isset($arrFormFields[$strKey]) && in_array($strKey, $this->arrBaseFields))
					{
						$arrField = $GLOBALS['TL_DCA'][$this->strTable]['fields'][$strKey];
						$strType = $arrField['inputType'];
					}

					$strLabel = '';
					$strVal = '';

					if ($arrTagParams && strlen($arrTagParams['label']))
					{
						$strLabel = $arrTagParams['label'];
					}

					if (in_array($strType, $arrFFstorable))
					{
						if ($strType == 'efgImageSelect')
						{
							$varText = '';
							$varHtml = '';

							if (strlen($arrSubmitted[$strKey]) || is_array($arrSubmitted[$strKey]))
							{
								$varVal = $this->FormData->prepareDbValForMail($arrSubmitted[$strKey], $arrField, $arrFiles[$strKey]);
								foreach ($varVal as $k => $strVal)
								{
									if (strlen($strVal))
									{
										$varText[] = $this->Environment->base . $strVal;
										$varHtml[] = '<img src="' . $strVal . '">';
									}
								}
								if (is_array($varText))
								{
									$varText = implode(', ', $varText);
									$varHtml = implode(', ', $varHtml);
								}
							}

							if (!strlen($varText) && $blnSkipEmpty)
							{
								$strLabel = '';
							}

							$subject = str_replace($tag, $strLabel . $varText, $subject);
							$messageText = str_replace($tag, $strLabel . $varText, $messageText);
		 					$messageHtml = str_replace($tag, $strLabel . $varHtml, $messageHtml);

		 					unset($varText);
		 					unset($varHtml);
						}
						elseif ($strType=='upload')
						{

							if (strlen($arrSubmitted[$strKey]))
							{
								if (!array_key_exists($strKey, $arrFiles))
								{
									$objFile = new File($arrSubmitted[$strKey]);
									if ($objFile->size)
									{
										$arrFiles[$strKey] = array('tmp_name' => $objFile->value, 'file'=>$objFile->value,  'name' => $objFile->basename, 'mime' => $objFile->mime);
									}
								}
							}

							if ($arrTagParams && ((array_key_exists('attachment', $arrTagParams) && $arrTagParams['attachment'] == true) || (array_key_exists('attachement', $arrTagParams) && $arrTagParams['attachement'] == true)) )
							{
								if (array_key_exists($strKey, $arrFiles) && strlen($arrFiles[$strKey]['name']))
								{
									if (!count($attachments) || !in_array($arrFiles[$strKey]['file'], $attachments))
									{
										$attachments[] = $arrFiles[$strKey]['file'];
									}
								}
								$strVal = '';
							}
							else
							{
								$strVal = $this->FormData->prepareDbValForMail($arrSubmitted[$strKey], $arrField, $arrFiles[$strKey]);
								$strVal = $this->formatValue($strKey, $strVal);
							}
							if (!strlen($strVal) && $blnSkipEmpty)
							{
								$strLabel = '';
							}
							$subject = str_replace($tag, $strLabel . $strVal, $subject);
							$messageText = str_replace($tag, $strLabel . $strVal, $messageText);
		 					$messageHtml = str_replace($tag, $strLabel . $strVal, $messageHtml);

						}
						else
						{
							$strVal = $this->FormData->prepareDbValForMail($arrSubmitted[$strKey], $arrField, $arrFiles[$strKey]);
							$strVal = $this->formatValue($strKey, $strVal);

							if (!strlen($strVal) && $blnSkipEmpty)
							{
								$strLabel = '';
							}

							$messageText = str_replace($tag, $strLabel . $strVal, $messageText);

							if (!is_bool(strpos($strVal, "\n")))
							{
								$strVal = preg_replace('/(<\/|<)(h\d|p|div|ul|ol|li)([^>]*)(>)(\n)/si', "\\1\\2\\3\\4", $strVal);
								$strVal = nl2br($strVal);
								$strVal = preg_replace('/(<\/)(h\d|p|div|ul|ol|li)([^>]*)(>)/si', "\\1\\2\\3\\4\n", $strVal);
							}
		 					$messageHtml = str_replace($tag, $strLabel . $strVal, $messageHtml);

		 				}
					}

					// replace insert tags in subject
					if (strlen($subject))
					{
						$subject = str_replace($tag, $strVal, $subject);
					}

					// replace insert tags in sender
					if (strlen($sender))
					{
						$sender = str_replace($tag, $strVal, $sender);
					}

 				break;
			}
		}

		// Replace standard insert tags and eval condition tags
		if (strlen($subject))
		{
			$subject = preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $subject);
			$subject = $this->replaceInsertTags($subject);
			if ($blnEvalSubject)
			{
				$subject = $this->FormData->evalConditionTags($subject, $arrSubmitted, $arrFiles, $arrForm);
			}
		}
		if (strlen($messageText))
		{
			$messageText = preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $messageText);
			$messageText = $this->replaceInsertTags($messageText);
			if ($blnEvalMessageText)
			{
				$messageText = $this->FormData->evalConditionTags($messageText, $arrSubmitted, $arrFiles, $arrForm);
			}
		}
		if (strlen($messageHtml))
		{
			$messageHtml = preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $messageHtml);
			$messageHtml = $this->replaceInsertTags($messageHtml);
			if ($blnEvalMessageHtml)
			{
				$messageHtml = $this->FormData->evalConditionTags($messageHtml, $arrSubmitted, $arrFiles, $arrForm);
			}
		}

		// replace insert tags in sender
		if (strlen($sender))
		{
			$sender = $this->replaceInsertTags($sender);
		}

		$confEmail = new Email();
		$confEmail->from = $sender;
		if (strlen($senderName))
		{
			$confEmail->fromName = $senderName;
		}
		$confEmail->subject = $subject;

		// Thanks to Torben Schwellnus
		// check if we want custom attachments...
		if ($arrForm['addConfirmationMailAttachments'])
		{
			// check if we have custom attachments...
			if($arrForm['confirmationMailAttachments'])
			{
				$arrCustomAttachments = deserialize($arrForm['confirmationMailAttachments'], true);

				// did the saved value result in an array?
				if(is_array($arrCustomAttachments))
				{
					foreach ($arrCustomAttachments as $strFile)
					{
						// does the file really exist?
						if(is_file(TL_ROOT .'/' .$strFile))
						{
							// can we read the file?
							if(is_readable(TL_ROOT .'/' .$strFile))
							{
								$objFile = new File($strFile);
								if ($objFile->size)
								{
									$attachments[] = $objFile->value;
								}
							}
						}
					}
				}
			}
		}

		if (is_array($attachments) && count($attachments)>0)
		{
			foreach ($attachments as $attachment)
			{
				$confEmail->attachFile(TL_ROOT . '/' . $attachment);
			}
		}

		if ($dirImages != '')
		{
			$confEmail->imageDir = $dirImages;
		}
		if ( $messageText != '' )
		{
			$messageText = html_entity_decode($messageText, ENT_QUOTES, $GLOBALS['TL_CONFIG']['characterSet']);
			$messageText = strip_tags($messageText);
			$confEmail->text = $messageText;
		}
		if ( $messageHtml != '' )
		{
			$confEmail->html = $messageHtml;
		}

		// Send Mail
		if (strlen($this->Input->get('token')) && $this->Input->get('token') == $this->Session->get('fd_mail_send'))
		{

			$this->Session->set('fd_mail_send', null);
			$blnSend = true;

			$blnConfirmationSent = false;
			if ($blnSend)
			{
				// Send e-mail
				if (count($arrRecipient)>0)
				{
					$arrSentTo = array();
					foreach ($arrRecipient as $recipient)
					{
						if(strlen($recipient))
						{
							$recipient = str_replace(array('[', ']'), array('<', '>'), $recipient);
							$recipientName = '';
							if (strpos($recipient, '<') > 0)
							{
								preg_match('/(.*)?<(\S*)>/si', $recipient, $parts);
								$recipientName = trim($parts[1]);
								$recipient = (strlen($recipientName) ? $recipientName.' <'.$parts[2].'>' : $parts[2]);
							}
						}

						$confEmail->sendTo($recipient);
						$blnConfirmationSent = true;

						$_SESSION['TL_INFO'][] = sprintf($GLOBALS['TL_LANG']['tl_formdata']['mail_sent'], str_replace(array('<', '>'), array('[', ']'), $recipient));
					}
				}

				$url = $this->Environment->base . preg_replace('/&(amp;)?(token|recipient)=[^&]*/', '', $this->Environment->request);

				if ($blnConfirmationSent && isset($this->intId) && intval($this->intId)>0)
				{
					$arrUpd = array('confirmationSent' => '1', 'confirmationDate' => time());
					$res = $this->Database->prepare("UPDATE tl_formdata %s WHERE id=?")
									->set($arrUpd)
									->execute($this->intId);
				}

			}

		}

		$strToken = md5(uniqid('', true));
		$this->Session->set('fd_mail_send', $strToken);

		$strHint = '';

		if (strlen($objRow->confirmationSent))
		{
			if (!$blnSend)
			{
				if (strlen($objRow->confirmationDate))
				{
					$dateConfirmation = new Date($objRow->confirmationDate);
					$strHint .= '<div class="tl_message"><p class="tl_info">'. sprintf($GLOBALS['TL_LANG']['tl_formdata']['confirmation_sent'], $dateConfirmation->date, $dateConfirmation->time) .'</p></div>';
				}
				else
				{
					$strHint .= '<div class="tl_message"><p class="tl_info">'. sprintf($GLOBALS['TL_LANG']['tl_formdata']['confirmation_sent'], '-n/a-', '-n/a-') .'</p></div>';
				}
			}
		}

		// Preview Mail
		$return = '
<div id="tl_buttons">
<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_formdata']['mail'][0].'</h2>'.$this->getMessages(). $strHint .'

<form action="'.ampersand($this->Environment->script, ENCODE_AMPERSANDS).'" id="tl_formdata_send" class="tl_form" method="get">
<div class="tl_formbody_edit fd_mail_send">
<input type="hidden" name="do" value="' . $this->Input->get('do') . '">
<input type="hidden" name="table" value="' . $this->Input->get('table') . '">
<input type="hidden" name="act" value="' . $this->Input->get('act') . '">
<input type="hidden" name="id" value="' . $this->Input->get('id') . '">
<input type="hidden" name="token" value="' . $strToken . '">

<table cellpadding="0" cellspacing="0" class="prev_header" summary="">
  <tr class="row_0">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_formdata']['mail_sender'][0] . '</td>
    <td class="col_1">' . $sender . '</td>
  </tr>

  <tr class="row_1">
    <td class="col_0"><label for="ctrl_formdata_recipient">' . $GLOBALS['TL_LANG']['tl_formdata']['mail_recipient'][0]. '</label></td>
    <td class="col_1"><input name="recipient" type="ctrl_recipient" class="tl_text" value="' . $strRecipient . '" '.($blnSend ? 'disabled="disabled"' : '').'></td>
  </tr>

  <tr class="row_2">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_formdata']['mail_subject'][0] . '</td>
    <td class="col_1">' . $subject . '</td>
  </tr>';

		if (is_array($attachments) && count($attachments) > 0)
		{
  	$return .= '
  <tr class="row_3">
    <td class="col_0" style="vertical-align:top">' . $GLOBALS['TL_LANG']['tl_formdata']['attachments'] . '</td>
    <td class="col_1">' . implode(',<br> ', $attachments) . '</td>
  </tr>';
		}

  $return .= '
</table>

<h3>' . $GLOBALS['TL_LANG']['tl_formdata']['mail_body_plaintext'][0] . '</h3>
<div class="preview_plaintext">
' . nl2br($messageText) . '
</div>';

		if (strlen($messageHtml))
		{
	$return .= '
<h3>' . $GLOBALS['TL_LANG']['tl_formdata']['mail_body_html'][0] . '</h3>
<div class="preview_html">
' . preg_replace(array('/.*?<body.*?>/si','/<\/body>.*$/si'), array('', ''), $messageHtml) . '
</div>';
		}

$return .= '
</div>';

		if (!$blnSend)
		{
	$return .= '
<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" id="send" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_formdata']['mail'][0]).'">
</div>

</div>';
		}

$return .= '
</form>';

		return $return;
	}


	public function importFile()
	{
		if ($this->Input->get('key') != 'import')
		{
			return '';
		}

		if (null === $this->arrImportIgnoreFields)
		{
			$this->arrImportIgnoreFields = array('id', 'pid', 'sorting', 'tstamp', 'form', 'ip', 'date', 'confirmationSent', 'confirmationDate', 'import_source' );
		}

		if (null === $this->arrImportableFields)
		{
			$arrFdFields = array_merge($this->arrBaseFields, $this->arrDetailFields);
			$arrFdFields = array_diff($arrFdFields, $this->arrImportIgnoreFields);
			foreach ($arrFdFields as $strFdField)
			{
				$this->arrImportableFields[$strFdField] = $GLOBALS['TL_DCA']['tl_formdata']['fields'][$strFdField]['label'][0];
			}
		}

		$arrSessionData = $this->Session->get('EFG');
		if (null == $arrSessionData)
		{
			$arrSessionData = array();
		}
		$this->Session->set('EFG', $arrSessionData);

		// Import CSV
		if ($_POST['FORM_SUBMIT'] == 'tl_formdata_import')
		{

			$strMode = 'preview';
			$arrSessionData['import'][$this->strFormKey]['separator'] = $_POST['separator'];
			$arrSessionData['import'][$this->strFormKey]['csv_has_header'] = ($_POST['csv_has_header'] == '1' ? '1' : '');
			$this->Session->set('EFG', $arrSessionData);

			if (!$this->Input->post('import_source') || $this->Input->post('import_source') == '')
			{
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['tl_formdata']['error_select_source'];
				$this->reload();
			}

			$strCsvFile = $this->Input->post('import_source');
			$objFile = new File($strCsvFile);

			if ($objFile->extension != 'csv')
			{
				$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension);
				setcookie('BE_PAGE_OFFSET', 0, 0, '/');
				$this->reload();
			}

			// Get separator
			switch ($this->Input->post('separator'))
			{
				case 'semicolon':
					$strSeparator = ';';
					break;

				case 'tabulator':
					$strSeparator = '\t';
					break;

				default:
					$strSeparator = ',';
					break;
			}

			if ($_POST['FORM_MODE'] == 'import')
			{
				$strMode = 'import';

				$time = time();
				$intTotal = null;
				$intInvalid = 0;
				$intValid = 0;

				$arrImportCols = $this->Input->post('import_cols');
				$arrSessionData['import'][$this->strFormKey]['import_cols'] = $arrImportCols;
				$this->Session->set('EFG', $arrSessionData);

				$arrMapFields = array_flip($arrImportCols);
				if (isset($arrMapFields['__IGNORE__']))
				{
					unset($arrMapFields['__IGNORE__']);
				}

				$blnUseCsvHeader = ($arrSessionData['import'][$this->strFormKey]['csv_has_header'] == '1' ? true : false);

				$arrEntries = array();
				$resFile = $objFile->handle;

				$timeNow = time();
				$strFormTitle = $this->arrFormsDcaKey[substr($this->strFormKey, 3)];

				$strAliasField = (strlen($this->arrStoreForms[substr($this->strFormKey, 3)]['efgAliasField']) ? $this->arrStoreForms[substr($this->strFormKey, 3)]['efgAliasField'] : '');

				$objForm = $this->Database->prepare("SELECT id FROM tl_form WHERE `title`=?")
									->limit(1)
									->execute($strFormTitle);
				if ($objForm->numRows == 1)
				{
					$intFormId = intval($objForm->id);
					$arrFormFields = $this->FormData->getFormfieldsAsArray($intFormId);
				}

				while(($arrRow = @fgetcsv($resFile, null, $strSeparator)) !== false)
				{
					if (null === $intTotal)
					{
						$intTotal = 0;
						if ($blnUseCsvHeader)
						{
							continue;
						}
					}

					$strAlias = '';
					if (isset($arrRow[$arrMapFields['alias']]) && strlen($arrRow[$arrMapFields['alias']]))
					{
						$strAlias = $arrRow[$arrMapFields['alias']];
					}
					elseif (isset($arrRow[$arrMapFields[$strAliasField]]) && strlen($arrRow[$arrMapFields[$strAliasField]]))
					{
						$this->Input->setPost($strAliasField, $arrRow[$arrMapFields[$strAliasField]]);
					}

					$arrDetailSets = array();

					// prepare base data
					$arrSet = array
					(
						'tstamp' => $timeNow,
						'fd_member' => 0,
						'fd_user' => intval($this->User->id),
						'form' => $strFormTitle,
						'ip' => $this->Environment->ip,
						'date' => $timeNow,
						'published' => ($GLOBALS['TL_DCA']['tl_formdata']['fields']['published']['default'] == '1' ? '1' : '' ),
						// 'alias' => '',
						// 'fd_member_group' => 0,
						// 'fd_user_group' => 0
					);

					foreach ($arrMapFields as $strField => $intCol)
					{
						if (in_array($strField, $this->arrImportIgnoreFields))
						{
							continue;
						}

						if (in_array($strField, $this->arrBaseFields))
						{
							$arrField = $GLOBALS['TL_DCA']['tl_formdata']['fields'][$strField];

							if (in_array($strField, $this->arrOwnerFields))
							{
								switch ($strField)
								{
									case 'fd_user':
										$array = 'arrUsers';
										break;
									case 'fd_member':
										$array = 'arrMembers';
										break;
									case 'fd_user_group':
										$array = 'arrUserGroups';
										break;
									case 'fd_member_group':
										$array = 'arrMemberGroups';
										break;
								}

								if (is_numeric($arrRow[$intCol]) && array_key_exists($arrRow[$intCol], $this->{$array}))
								{
									$varValue = $arrRow[$intCol];
								}
								elseif (is_string($arrRow[$intCol]))
								{
									$varValue = intval(array_search($arrRow[$intCol], $this->{$array}));
								}
							}
							elseif ($strField == 'published')
							{
								if ($arrRow[$intCol] == $arrField['label'][0] || intval($arrRow[$intCol]) == 1)
								{
									$varValue = '1';
								}
								else
								{
									$varValue = '';
								}
							}
							elseif ($strField == 'alias')
							{
								continue;
							}
							else
							{
								$varValue = $arrRow[$intCol];
							}
							$arrSet[$strField] = $varValue;
						}
					}

					// prepare details data
					foreach ($arrMapFields as $strField => $intCol)
					{
						if (in_array($strField, $this->arrImportIgnoreFields))
						{
							continue;
						}

						if (in_array($strField, $this->arrDetailFields))
						{
							// $arrField = array_merge($arrFormFields[$strField], $GLOBALS['TL_DCA']['tl_formdata']['fields'][$strField]);
							$arrField = $GLOBALS['TL_DCA']['tl_formdata']['fields'][$strField];
							$arrField['type'] = $arrFormFields[$strField]['type'];

							$varValue = $this->FormData->prepareImportValForDb($arrRow[$intCol], $arrField);

							// prepare details data
							$arrDetailSet = array(
								// 'pid' => $intNewId,
								'sorting' => $arrFormFields[$strField]['sorting'],
								'tstamp' => $timeNow,
								'ff_id' => $arrField['ff_id'],
								'ff_type' => $arrField['inputType'],
								'ff_label' => $arrField['label'][0],
								'ff_name' => $strField,
								'value' => $varValue
							);

							$arrDetailSets[] = $arrDetailSet;
						}
					}

					$intNewId = 0;
					$blnSaved = true;

					if (count($arrDetailSets))
					{
						$objNewFormdata = $this->Database->prepare("INSERT INTO tl_formdata %s")->set($arrSet)->execute();
						$intNewId = $objNewFormdata->insertId;

						$strAlias = $this->FormData->generateAlias($strAlias, $this->strFormFilterValue, $intNewId);
						if (strlen($strAlias))
						{
							$this->Database->prepare("UPDATE tl_formdata %s WHERE id=?")->set(array('alias' => $strAlias))->execute($intNewId);
						}

						foreach ($arrDetailSets as $kD => $arrDetailSet)
						{
							$arrDetailSet['pid'] = $intNewId;
							try
							{
								$objNewFormdataDetails = $this->Database->prepare("INSERT INTO tl_formdata_details %s")
																		->set($arrDetailSet)
																		->execute();
							}
							catch(Exception $ee)
							{
								$blnSaved = false;
							}
						}

						if ($blnSaved === false && $intNewId > 0)
						{
							$this->Database->prepare("DELETE FROM tl_formdata WHERE id=?")->execute($intNewId);
						}
					}
					else
					{
						$blnSaved = false;
					}

					if ($blnSaved)
					{
						$intValid++;
					}
					else
					{

						$intInvalid++;
					}

					$intTotal++;

				} // while $arrRow

				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_formdata']['import_confirm'], $intValid);

				if ($intInvalid > 0)
				{
					$_SESSION['TL_INFO'][] = sprintf($GLOBALS['TL_LANG']['tl_formdata']['import_invalid'], $intInvalid);
				}

				$objFile->close();

				// Add a log entry
				$this->log('Imported file "'.$objFile->filename.'" into form data "'.$strFormTitle.'", created '.$intValid.' new records', 'DC_Formdata importFile()', TL_GENERAL);

				setcookie('BE_PAGE_OFFSET', 0, 0, '/');
				$this->reload();

			}

			// Generate preview and form to select import fields
			if ($strMode == 'preview')
			{
				return $this->formImportPreview($objFile, $strSeparator);
			}

		}

		return $this->formImportSource();

	}


	/**
	 * Generate the form to select import source and basic settings and return it as HTML string
	 * @return string
	 */
	protected function formImportSource()
	{
		$arrSessionData = $this->Session->get('EFG');

		$objTree = new FileTree($this->prepareForWidget($GLOBALS['TL_DCA']['tl_formdata']['fields']['import_source'], 'import_source', null, 'import_source', 'tl_formdata'));

		// Return form
		return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=import', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_formdata']['import'][1].'</h2>
'.$this->getMessages().'
<form action="'.ampersand($this->Environment->request, true).'" id="tl_formdata_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_formdata_import">
<input type="hidden" name="FORM_MODE" value="preview">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">

<div class="tl_tbox block">
  <div class="w50">
  <h3><label for="separator">'.$GLOBALS['TL_LANG']['MSC']['separator'][0].'</label></h3>
  <select name="separator" id="separator" class="tl_select" onfocus="Backend.getScrollOffset()">
    <option value="comma"'.($arrSessionData['import'][$this->strFormKey]['separator'] == 'comma' ? ' selected="selected"' : '').'>'.$GLOBALS['TL_LANG']['MSC']['comma'].'</option>
    <option value="semicolon"'.($arrSessionData['import'][$this->strFormKey]['separator'] == 'semicolon' ? ' selected="selected"' : '').'>'.$GLOBALS['TL_LANG']['MSC']['semicolon'].'</option>
    <option value="tabulator"'.($arrSessionData['import'][$this->strFormKey]['separator'] == 'tabulator' ? ' selected="selected"' : '').'>'.$GLOBALS['TL_LANG']['MSC']['tabulator'].'</option>
  </select>'.(strlen($GLOBALS['TL_LANG']['MSC']['separator'][1]) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['separator'][1].'</p>' : '').'
  </div>
  <div class="w50 m12 cbx">
  <div class="tl_checkbox_single_container">
  <input name="csv_has_header" id="csv_has_header" type="checkbox" value="1"'.($arrSessionData['import'][$this->strFormKey]['csv_has_header'] == '1' ? ' checked="checked"' : '').'>
  <label for="csv_has_header">'.$GLOBALS['TL_LANG']['tl_formdata']['csv_has_header'][0].'</label>
  </div>
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_formdata']['csv_has_header'][1].'</p>
  </div>

  <div class="clr">
  <h3><label for="import_source">'.$GLOBALS['TL_LANG']['tl_formdata']['import_source'][0].'</label> <a href="contao/files.php" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['fileManager']) . '" onclick="Backend.getScrollOffset(); Backend.openWindow(this, 750, 500); return false;">' . $this->generateImage('filemanager.gif', $GLOBALS['TL_LANG']['MSC']['fileManager'], 'style="vertical-align:text-bottom;"') . '</a></h3>
'.$objTree->generate().(strlen($GLOBALS['TL_LANG']['tl_formdata']['import_source'][1]) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_formdata']['import_source'][1].'</p>' : '').'
  </div>
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_formdata']['import'][0]).'" onfocus="document.cookie = \'BE_PAGE_OFFSET=0; path=/\';">
</div>

</div>
</form>';

	}


	/**
	 * Generate the form to select the field mappings and return it as HTML string
	 * @return string
	 */
	protected function formImportPreview($objFile, $strSeparator)
	{

		$arrSessionData = $this->Session->get('EFG');
		$blnUseCsvHeader = ($arrSessionData['import'][$this->strFormKey]['csv_has_header'] == '1' ? true : false);

		$arrEntries = array();
		$resFile = $objFile->handle;

		$intReadLines = 50;
		if ($blnUseCsvHeader)
		{
			$intReadLines++;
		}

		while(($arrRow = @fgetcsv($resFile, null, $strSeparator)) !== false)
		{
			$arrEntries[] = $arrRow;
			$intTotal++;
			if ($intTotal == $intReadLines)
			{
				break;
			}
		}

		if ($blnUseCsvHeader && !isset($arrSessionData['import'][$this->strFormKey]['import_cols']))
		{
			foreach ($arrEntries[0] as $col => $val)
			{
				if (array_key_exists($val, $this->arrImportableFields))
				{
					$arrSessionData['import'][$this->strFormKey]['import_cols'][$col] = $val;
				}
				else
				{
					$mxRes = array_search($val, $this->arrImportableFields);
					if ($mxRes !== false)
					{
						$arrSessionData['import'][$this->strFormKey]['import_cols'][$col] = $mxRes;
					}
					else
					{
						$arrSessionData['import'][$this->strFormKey]['import_cols'][$col] = '__IGNORE__';
					}
				}
			}
		}

		$this->Session->set('EFG', $arrSessionData);

		// plugin stylect cannot handle selects inside scrolling div
		// .. deactivate it
		$return = '
<script>
var Stylect = {
	convertSelects: function() { return; }
};
</script>
';

		$return .= '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=import', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_formdata']['import'][1].'</h2>'
.$this->getMessages().'
<form action="'.ampersand($this->Environment->request, true).'" id="tl_formdata_import" class="tl_form" method="post">
<div class="tl_formbody_edit tl_formdata_import">
	<input type="hidden" name="FORM_SUBMIT" value="tl_formdata_import">
	<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
	<input type="hidden" name="FORM_MODE" value="import">
	<input type="hidden" name="import_source" value="'.$this->Input->post('import_source').'">
	<input type="hidden" name="separator" value="'.$this->Input->post('separator').'">
	<input type="hidden" name="csv_has_header" value="'.$this->Input->post('csv_has_header').'">

	<div class="tl_tbox block">
		<h3>'.$GLOBALS['TL_LANG']['tl_formdata']['import_preview'][0].'</h3>
		<p class="tl_help">'.$GLOBALS['TL_LANG']['tl_formdata']['import_preview'][1].'</p>
		<div class="fd_import_prev">
			<div>';
		$return .= '
			<table class="fd_import_data">
				<thead><tr>';
		foreach ($arrEntries[0] as $col => $val)
		{
			$return .= '
					<td>'.$this->importFieldmapMenu($arrEntries, $col, $val).'</td>';
		}
		$return .= '
				</tr></thead>';
		$return .= '
				<tbody>';

		if ($blnUseCsvHeader)
		{
			array_shift($arrEntries);
		}

		foreach ($arrEntries as $row)
		{
			$return .= '
				<tr>';
			foreach ($row as $col => $val)
			{
				$return .= '
					<td>'.$val.'</td>';
			}
			$return .= '
			</tr>';
		}
		$return .= '
				</tbody>
			</table>';
		$return .= '
			</div>
		</div>
	</div>
</div>

<div class="tl_formbody_submit">
	<div class="tl_submit_container">
		<input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_formdata']['import'][0]).'">
	</div>
</div>
</form>';

		return $return;

	}


	/**
	 * Generate a dropdown menu to select destination field and return it as HTML string
	 * @return string
	 */
	protected function importFieldmapMenu(&$arrEntries, $col, $val)
	{

		$arrSessionData = $this->Session->get('EFG');

		$return = '
<select name="import_cols['.$col.']">
	<option value="__IGNORE__"'.((!isset($arrSessionData['import'][$this->strFormKey]['import_cols'][$col]) || $arrSessionData['import'][$this->strFormKey]['import_cols'][$col] == '__IGNORE__') ? ' selected="SELECTED"' : '').'>'.$GLOBALS['TL_LANG']['tl_formdata']['option_import_ignore'].'</option>';
		if (count($this->arrImportableFields) > 0)
		{
			foreach (array_keys($this->arrImportableFields) as $strFdField)
			{
				$selected = '';
				if (isset($arrSessionData['import'][$this->strFormKey]['import_cols']))
				{
					if ($arrSessionData['import'][$this->strFormKey]['import_cols'][$col] == $strFdField)
					{
						$selected = ' selected="selected"';
					}
				}
				$return .= '<option value="'.$strFdField.'"'.$selected.'>'.(isset($GLOBALS['TL_DCA']['tl_formdata']['fields'][$strFdField]['label'][0]) ? $GLOBALS['TL_DCA']['tl_formdata']['fields'][$strFdField]['label'][0] : $strFdField).'</option>';
			}
		}
		$return .= '
</select>';

		return $return;

	}


	public function export($strMode='csv')
	{

		if (strlen($this->Input->get('expmode')))
		{
			$strMode = $this->Input->get('expmode');
		}

		$return = '';

		$blnCustomXlsExport = false;
		$blnCustomExport = false;
		$arrHookData = array();
		$arrHookDataColumns = array();

		if ($strMode=='xls')
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
		elseif ($strMode!='csv')
		{
			$blnCustomExport = true;
		}

		// filter or search for values
		$session = $this->Session->getData();

		$showFields = array_merge($this->arrBaseFields, $this->arrDetailFields);
		$ignoreFields = array('tstamp', 'sorting');

		if (is_array($this->arrExportIgnoreFields) && count($this->arrExportIgnoreFields) > 0)
		{
			$ignoreFields = array_unique(array_merge($ignoreFields, $this->arrExportIgnoreFields));
		}

		$table = ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 6) ? $this->ptable : $this->strTable;
		$table_alias = ($table == 'tl_formdata' ? ' f' : '');

		$orderBy = $GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['fields'];
		$firstOrderBy = preg_replace('/\s+.*$/i', '', $orderBy[0]);

		if (is_array($this->orderBy) && strlen($this->orderBy[0]))
		{
			$orderBy = $this->orderBy;
			$firstOrderBy = $this->firstOrderBy;
		}

		if ($this->Input->get('table') && $GLOBALS['TL_DCA'][$this->strTable]['config']['ptable'] && $this->Database->fieldExists('pid', $this->strTable))
		{
			$this->procedure[] = 'pid=?';
			$this->values[] = $this->Input->get('id');
		}

		$query = "SELECT * " .(count($this->arrSqlDetails) > 0 ? ', '.implode(',' , array_values($this->arrSqlDetails)) : '') ." FROM " . $this->strTable . $table_alias;

		$sqlWhere = '';

		// Set search value from session
		$strSessionKey = ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 4) ? $this->strTable.'_'.CURRENT_ID : (strlen($this->strFormKey)) ? $this->strFormKey : $this->strTable;
		if (strlen($session['search'][$strSessionKey]['value']))
		{
			$sqlSearchField = $session['search'][$strSessionKey]['field'];
			if (in_array($sqlSearchField, $this->arrDetailFields) )
			{
				$sqlSearchField = '(SELECT value FROM tl_formdata_details WHERE ff_name=\'' . $session['search'][$strSessionKey]['field'] .'\' AND pid=f.id)';
			}
			$this->procedure[] = "CAST(".$sqlSearchField." AS CHAR) REGEXP ?";
			$this->values[] = $session['search'][$strSessionKey]['value'];
		}

		// Set filter from session
		$arrFilterFields = array();
		foreach ($GLOBALS['TL_DCA'][$this->strTable]['fields'] as $k=>$v)
		{
			if ($v['filter'] )
			{
				$arrFilterFields[] = $k;
			}
		}
		if (count($arrFilterFields))
		{
			foreach ($arrFilterFields as $field)
			{
				if (isset($session['filter'][$strSessionKey][$field]))
				{
					$sqlFilterField = $field;
					if (in_array($field, $this->arrDetailFields))
					{
						$sqlFilterField = '(SELECT value FROM tl_formdata_details WHERE ff_name=\'' . $field .'\' AND pid=f.id)';
					}

					// Sort by day
					if (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(5, 6)))
					{
						if ($session['filter'][$strSessionKey][$field] == '')
						{
							$this->procedure[] = $sqlFilterField . "=''";
						}
						else
						{
							$objDate = new Date($session['filter'][$strSessionKey][$field]);
							$this->procedure[] = $sqlFilterField . ' BETWEEN ? AND ?';
							$this->values[] = $objDate->dayBegin;
							$this->values[] = $objDate->dayEnd;
						}
					}

					// Sort by month
					elseif (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(7, 8)))
					{
						if ($session['filter'][$strSessionKey][$field] == '')
						{
							$this->procedure[] = $sqlFilterField . "=''";
						}
						else
						{
							$objDate = new Date($session['filter'][$strSessionKey][$field]);
							$this->procedure[] = $sqlFilterField . ' BETWEEN ? AND ?';
							$this->values[] = $objDate->monthBegin;
							$this->values[] = $objDate->monthEnd;
						}
					}

					// Sort by year
					elseif (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['flag'], array(9, 10)))
					{
						if ($session['filter'][$strSessionKey][$field] == '')
						{
							$this->procedure[] = $sqlFilterField . "=''";
						}
						else
						{
							$objDate = new Date($session['filter'][$strSessionKey][$field]);
							$this->procedure[] = $sqlFilterField . ' BETWEEN ? AND ?';
							$this->values[] = $objDate->yearBegin;
							$this->values[] = $objDate->yearEnd;
						}
					}

					// Manual filter
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$field]['eval']['multiple'])
					{
						$this->procedure[] = $sqlFilterField . ' LIKE ?';
						$this->values[] = '%"' . $session['filter'][$strSessionKey][$field] . '"%';
					}

					// Other sort algorithm
					else
					{
						$this->procedure[] = $sqlFilterField . '=?';
						$this->values[] = $session['filter'][$strSessionKey][$field];
					}
				}
			}
		}

		if (count($this->procedure))
		{
			$arrProcedure = $this->procedure;

			foreach ($arrProcedure as $kProc => $vProc)
			{
				$strProcField = substr($vProc, 0, strpos($vProc, '='));
				if (in_array($strProcField, $this->arrDetailFields))
				{
					$arrProcedure[$kProc] = "(SELECT value FROM tl_formdata_details WHERE ff_name='" . $strProcField . "' AND pid=f.id)=?";
				}
			}
			$sqlWhere .= ($sqlWhere != '' ? " AND " : " WHERE ") . implode(' AND ', $arrProcedure);
		}

		if ($sqlWhere != '')
		{
			$query .= $sqlWhere;
		}

		if (is_array($orderBy) && strlen($orderBy[0]))
		{
			foreach ($orderBy as $o => $strVal)
			{
				$arrOrderField = explode(' ', $strVal);
				$strOrderField = $arrOrderField[0];
				unset($arrOrderField);
				if (!in_array($strOrderField, $this->arrBaseFields))
				{
					$orderBy[$o] = "(SELECT value FROM tl_formdata_details WHERE ff_name='" . $strOrderField . "' AND pid=f.id)";
				}
			}
			$query .= " ORDER BY " . implode(', ', $orderBy);
		}
		if ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 1 && ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['flag'] % 2) == 0)
		{
			$query .= " DESC";
		}

		$objRowStmt = $this->Database->prepare($query);
		$objRow = $objRowStmt->execute($this->values);

		$intRowCounter = -1;

		$strExpEncl = '';
		$strExpSep = ';';

		$useFormValues = $this->arrStoreForms[substr($this->strFormKey, 3)]['useFormValues'];
		$useFieldNames = $this->arrStoreForms[substr($this->strFormKey, 3)]['useFieldNames'];

		if ($strMode=='csv')
		{
			header('Content-Type: appplication/csv; charset='.($this->blnExportUTF8Decode ? 'CP1252' : 'utf-8'));
			header('Content-Transfer-Encoding: binary');
			header('Content-Disposition: attachment; filename="export_' . $this->strFormKey . '_' . date("Ymd_His") .'.csv"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Expires: 0');
		}
		elseif ($strMode=='xls')
		{
			if (!$blnCustomXlsExport)
			{
				$xls = new xlsexport();
				$strXlsSheet = "Export";
				$xls->addworksheet($strXlsSheet);
			}
		}

		// List records
		if ($objRow->numRows)
		{
			$result = $objRow->fetchAllAssoc();

			// Rename each pid to its label and resort the result (sort by parent table)
			if ($GLOBALS['TL_DCA'][$this->strTable]['list']['sorting']['mode'] == 3 && $this->Database->fieldExists('pid', $this->strTable))
			{
				$firstOrderBy = 'pid';

				foreach ($result as $k=>$v)
				{
					$objField = $this->Database->prepare("SELECT " . $showFields[0] . " FROM " . $this->ptable . " WHERE id=?")
											   ->limit(1)
											   ->execute($v['pid']);
					$result[$k]['pid'] = $objField->$showFields[0];
				}

				$aux = array();
				foreach ($result as $row)
				{
					$aux[] = $row['pid'];
				}
				array_multisort($aux, SORT_ASC, $result);
			}

			// Process result and format values
			foreach ($result as $row)
			{
				$intRowCounter++;

				$args = array();
				$this->current[] = $row['id'];
				//$showFields = $GLOBALS['TL_DCA'][$table]['list']['label']['fields'];

				if ($intRowCounter == 0)
				{
					if ($strMode == 'xls')
					{
						if (!$blnCustomXlsExport)
						{
							$xls->totalcol = count($showFields);
						}
					}

					$strExpEncl = '"';
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
						}

						if ($this->blnExportUTF8Decode || ($strMode == 'xls' && !$blnCustomXlsExport))
						{
							$strName = $this->convertEncoding($strName, $GLOBALS['TL_CONFIG']['characterSet'], 'CP1252');
						}

						if ($strMode=='csv')
						{
							$strName = str_replace('"', '""', $strName);
							echo $strExpSep . $strExpEncl . $strName . $strExpEncl;
							$strExpSep = ";";
						}
						elseif ($strMode=='xls')
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
						elseif ($blnCustomExport)
						{
							$arrHookDataColumns[$v] = $strName;
						}

					}

					$intRowCounter++;

					if ($strMode=='csv')
					{
						echo "\n";
					}

				} // intRowCounter 0

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

					if ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'date' && in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['flag'], array(5, 6, 7, 8, 9, 10)))
					{
						$strVal = ( $row[$v] ? date($GLOBALS['TL_CONFIG']['dateFormat'], $row[$v]) : '' );
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'datim' && in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['flag'], array(5, 6, 7, 8, 9, 10)))
					{
						$strVal = ( $row[$v] ? date($GLOBALS['TL_CONFIG']['datimFormat'], $row[$v]) : '' );
					}
					elseif (in_array($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['flag'], array(5, 6, 7, 8, 9, 10)))
					{
						$strVal = ( $row[$v] ? date($GLOBALS['TL_CONFIG']['datimFormat'], $row[$v]) : '' );
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['eval']['multiple'])
					{
						if ($useFormValues == 1)
						{
							// single value checkboxes don't have options
							if ((is_array($GLOBALS['TL_DCA'][$table]['fields'][$v]['options']) && count($GLOBALS['TL_DCA'][$table]['fields'][$v]['options']) > 0))
							{
								$strVal = strlen($row[$v]) ? key($GLOBALS['TL_DCA'][$table]['fields'][$v]['options']) : '';
							}
							else
							{
								$strVal = $row[$v];
							}
						}
						else
						{
							$strVal = strlen($row[$v]) ? $GLOBALS['TL_DCA'][$table]['fields'][$v]['label'][0] : '-';
						}
					}
					elseif ($GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'radio'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'efgLookupRadio'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'select'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'conditionalselect'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'efgLookupSelect'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'checkbox'
							|| $GLOBALS['TL_DCA'][$this->strTable]['fields'][$v]['inputType'] == 'efgLookupCheckbox')
					{
						// take the assigned value instead of the user readable output
						if ($useFormValues == 1)
						{
							if ((strpos($row[$v], "|") == FALSE) && (is_array($GLOBALS['TL_DCA'][$table]['fields'][$v]['options']) && count($GLOBALS['TL_DCA'][$table]['fields'][$v]['options']) > 0))
							{
								// handle grouped options
								$arrOptions = array();
								foreach ($GLOBALS['TL_DCA'][$table]['fields'][$v]['options'] as $o => $mxVal)
								{
									if ((!is_array($mxVal)))
									{
										$arrOptions[$o] = $mxVal;
									}
									else
									{
										foreach ($mxVal as $ov => $mxOVal)
										{
											$arrOptions[$ov] = $mxOVal;
										}
									}
								}

								//$options = array_flip($GLOBALS['TL_DCA'][$table]['fields'][$v]['options']);
								$options = array_flip($arrOptions);
								$strVal = $options[$row[$v]];
							}
							else
							{
								if ((is_array($GLOBALS['TL_DCA'][$table]['fields'][$v]['options']) && count($GLOBALS['TL_DCA'][$table]['fields'][$v]['options']) > 0))
								{
									// handle grouped options
									$arrOptions = array();
									foreach ($GLOBALS['TL_DCA'][$table]['fields'][$v]['options'] as $o => $mxVal)
									{
										if ((!is_array($mxVal)))
										{
											$arrOptions[$o] = $mxVal;
										}
										else
										{
											foreach ($mxVal as $ov => $mxOVal)
											{
												$arrOptions[$ov] = $mxOVal;
											}
										}
									}

									//$options = array_flip($GLOBALS['TL_DCA'][$table]['fields'][$v]['options']);
									$options = array_flip($arrOptions);

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
					else
					{
						$row_v = deserialize($row[$v]);

						if (is_array($row_v))
						{
							$args_k = array();

							foreach ($row_v as $option)
							{
								$args_k[] = strlen($GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$option]) ? $GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$option] : $option;
							}

							$args[$k] = implode(",\n", $args_k);
						}
						elseif (is_array($GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]]))
						{
							$args[$k] = is_array($GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]]) ? $GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]][0] : $GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]];
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

						if ($this->blnExportUTF8Decode || ($strMode == 'xls' && !$blnCustomXlsExport))
						{
							$strVal = $this->convertEncoding($strVal, $GLOBALS['TL_CONFIG']['characterSet'], 'CP1252');
						}
					}

					if ($strMode=='csv')
					{
						$strVal = str_replace('"', '""', $strVal);
						echo $strExpSep . $strExpEncl . $strVal . $strExpEncl;

						$strExpSep = ";";
					}
					elseif ($strMode=='xls')
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
					elseif ($blnCustomExport)
					{
						$arrHookData[$intRowCounter][$v] = $strVal;
					}

				}

				if ($strMode=='csv')
				{
					$strExpSep = '';
					echo "\n";
				}

			} // foreach ($result as $row)

		} // if objRow->numRows

		if ($strMode=='xls')
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
		elseif ($blnCustomExport)
		{
			foreach ($GLOBALS['TL_HOOKS']['efgExport'] as $key => $callback)
			{
				$this->import($callback[0]);
				$res = $this->$callback[0]->$callback[1]($arrHookDataColumns, $arrHookData, $strMode);
			}
		}
		exit;
	}


	public function exportxls()
	{
		$this->export('xls');
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

			// Get all users
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

			// Get all member groups
			$objGroups = $this->Database->prepare("SELECT id, `name` FROM tl_member_group ORDER BY `name` ASC")
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

			// Get all user groups
			$objGroups = $this->Database->prepare("SELECT id, `name` FROM tl_user_group ORDER BY `name` ASC")
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

}

?>