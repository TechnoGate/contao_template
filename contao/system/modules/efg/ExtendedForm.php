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
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */


/**
 * Class ExtendedForm
 *
 * Provide methods to handle front end forms (multi page and formdata frontend editing)
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */
class ExtendedForm extends Form
{

	/**
	 * Active page
	 */
	protected $intActivePage = 1;

	/**
	 * Number of total pages
	 */
	protected $intTotalPages = 1;

	/**
	 * Paginator fields
	 * @var array
	 */
	protected $arrPaginators = array();

	/**
	 * Multipage form
	 * @var boolean
	 */
	protected $blnMultipage = false;

	/**
	 * Efg Edit Form
	 * @var boolean
	 */
	protected $blnEditform = false;

	/**
	 * Database record if used in frontend edit mode
	 * @var object Database_Result
	 */
	protected $objEditRecord = null;

	/**
	 * validation result of required widgets
	 * @var array
	 */
	protected $arrWidgetsFailedValidation = array();

	/**
	 * Allow form page submission, if ALL required fields are empty
	 * @var boolean
	 */
	protected $blnAllowSkipRequired = false;

	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'objEditRecord':
				if ($varValue instanceof Database_Result)
				{
					$this->objEditRecord = $varValue;
				}
				break;
			default:
				return parent::__set($strKey, $varValue);
				break;
		}
	}

	/**
	 * Generate the form
	 * @return string
	 */
	protected function compile()
	{
		$hasUpload = false;
		$doNotSubmit = false;
		$arrSubmitted = array();
		$blnAddDateJS = true;

		$this->loadDataContainer('tl_form_field');
		$formId = strlen($this->formID) ? 'auto_'.$this->formID : 'auto_form_'.$this->id;

		$arrUnset = array('FORM_NEXT', 'FORM_BACK');
		foreach ($arrUnset as $strKey)
		{
			unset($_SESSION['FORM_DATA'][$strKey]);
		}

		// form used to edit existing formdata record
		if ($this->objEditRecord instanceof Database_Result)
		{
			$arrEditRecord = $this->objEditRecord->row();
			$this->blnEditform = true;
			$this->import('FormData');
		}

		// Check multipage
		$objPaginators = $this->Database->prepare("SELECT id,pid,`sorting`,`type`,`name`,`label`,`value`,`imageSubmit`,`singleSRC`,`sLabel`,`efgAddBackButton`,`efgBackStoreSessionValues`,`efgBackSlabel`,`efgBackImageSubmit`,`efgBackSingleSRC` FROM tl_form_field WHERE pid=? AND `type`=? ORDER BY `sorting`")
									->execute($this->id, 'efgFormPaginator');
		if ($objPaginators->numRows)
		{
			$this->blnMultipage = true;
			$this->intTotalPages = (int) ($objPaginators->numRows);

			while ($objPaginators->next())
			{
				$this->arrPaginators[] = $objPaginators->row();
			}
		}

		// use core class Form if this is not a multi page form and not frontend edit form
		if ((!$this->blnMultipage && !$this->blnEditform) || $this->method == 'GET' || TL_MODE == 'BE')
		{
			$this->strTemplate = 'form';
			return parent::compile();
		}

		global $objPage;

		if ($objPage->outputFormat == 'html5')
		{
			$blnIsHtml5 = true;
		}

		$this->blnAllowSkipRequired = false; // allow form submission, if ALL required fields are empty
		$this->arrWidgetsFailedValidation = array(); // validation result of required widgets

		$doNotValidate = false;
		$strMode = '';
		$intActivePage = 1;

		$this->Template = new FrontendTemplate($this->strTemplate);
		$this->loadLanguageFile('tl_form');

		// render a previous completed page
		if (strlen($_SESSION['EFP'][$formId]['render_page']))
		{
			$intActivePage = (int) $_SESSION['EFP'][$formId]['render_page'];
			$this->intActivePage = (int) $_SESSION['EFP'][$formId]['render_page'];
			$strMode = 'reload';
			unset($_SESSION['EFP'][$formId]['render_page']);
		}
		elseif (!strlen($_POST['FORM_SUBMIT']))
		{
			unset($_SESSION['EFP'][$formId]['render_page']);
			unset($_SESSION['EFP'][$formId]['completed']);
		}

		if ($this->Input->post('FORM_SUBMIT') == $formId && (strlen($_POST['FORM_PAGE']) || is_array($_POST['FORM_STEP']) ))
		{
			$intActivePage = (int) $_POST['FORM_PAGE'];
			$intGoto = 0;

			if (strlen($_POST['FORM_BACK']) || strlen($_POST['FORM_BACK_x']) )
			{
				$intActivePage = ((int) $_POST['FORM_PAGE']);
				$doNotValidate = true;
				$strMode = 'back';
			}

			// .. "jump to" page per input type submit like FORM_STEP[2]
			elseif (is_array($_POST['FORM_STEP']) || is_array($_POST['FORM_STEP_x']))
			{
				$intGoto = (is_array($_POST['FORM_STEP']) ? key($_POST['FORM_STEP']) : key($_POST['FORM_STEP_x']));
				if ($intGoto < $intActivePage)
				{
					$_SESSION['EFP'][$formId]['render_page'] = ($intGoto<1 ? 1 : $intGoto);
					$this->reload();
				}
				elseif ($intGoto > $intActivePage && $_SESSION['EFP'][$formId]['completed']['page_'.$intGoto])
				{
					$_SESSION['EFP'][$formId]['render_page'] = $intGoto;
					$this->reload();
				}
			}

			if ($intActivePage < 1) $intActivePage = 1;
			if ($intActivePage > $this->intTotalPages) $intActivePage = $this->intTotalPages;

			$this->intActivePage = $intActivePage;
		}

		$this->Template->fields = '';
		$this->Template->hidden = '';
		$this->Template->formSubmit = $formId;
		$this->Template->tableless = $this->tableless ? true : false;
		$this->Template->method = ($this->method == 'GET') ? 'get' : 'post';

		if ($this->blnMultipage || $this->blnEditform)
		{
			$objPageWidget = new FormHidden(array('name' => 'FORM_PAGE', 'value' => $this->intActivePage));
			$this->Template->hidden .= $objPageWidget->parse();
		}

		$this->initializeSession($formId);
		$this->getMaxFileSize();

		// Get all form fields
		$objFields = $this->Database->prepare("SELECT * FROM tl_form_field WHERE pid=? AND invisible!=1 ORDER BY sorting")
									->execute($this->id);

		$row = 0;
		$max_row = $objFields->numRows;
		$arrLabels = array();

		while ($objFields->next())
		{

			if ($objFields->name != '')
			{
				$arrLabels[$objWidget->name] = $objFields->label;
			}

			if ($this->intTotalPages > 1 && ($this->blnMultipage || $this->blnEditform))
			{
				// skip fields outside range of active page
				$intFieldSorting = (int) $objFields->sorting;
				if ($this->intActivePage <= 1 && $intFieldSorting > (int) $this->arrPaginators[($this->intActivePage - 1)]['sorting'])
				{
					continue;
				}
				elseif ($this->intActivePage > 1 && $this->intActivePage < $this->intTotalPages
						&& ($intFieldSorting <= (int) $this->arrPaginators[($this->intActivePage - 2)]['sorting'] || $intFieldSorting > (int) $this->arrPaginators[($this->intActivePage - 1)]['sorting'] ))
				{
					continue;
				}
				elseif ($this->intActivePage == $this->intTotalPages && $intFieldSorting <= (int) $this->arrPaginators[($this->intActivePage - 2)]['sorting'])
				{
					continue;
				}
			}

			// unset session values if no FORM_SUBMIT or form page has not been completed
			// (to avoid wrong validation against session values and to avoid usage of values of other forms)
			// this behaviour can be deactivated by setting: $GLOBALS['EFP'][$formId]['doNotCleanStoredSessionData'] = true;
			if ($strMode != 'reload' && strlen($objFields->name))
			{
				if (!strlen($_POST['FORM_SUBMIT']) || !$_SESSION['EFP'][$formId]['completed']['page_'.$this->intActivePage])
				{
					if (!$GLOBALS['EFP'][$formId]['doNotCleanStoredSessionData'])
					{
						unset($_SESSION['FORM_DATA'][$objFields->name]);
					}
				}
			}

			$strClass = $GLOBALS['TL_FFL'][$objFields->type];

			// Continue if the class is not defined
			if (!$this->classFileExists($strClass))
			{
				continue;
			}

			$arrData = $objFields->row();

			$arrData['decodeEntities'] = true;
			$arrData['allowHtml'] = $this->allowTags;
			$arrData['rowClass'] = 'row_'.$row . (($row == 0) ? ' row_first' : (($row == ($max_row - 1)) ? ' row_last' : '')) . ((($row % 2) == 0) ? ' even' : ' odd');
			$arrData['tableless'] = $this->tableless;

			if ($this->blnMultipage || $this->blnEditform)
			{
				$arrData['formMultipage'] = $this->blnMultipage;
				$arrData['formActivePage'] = $this->intActivePage;
				$arrData['formTotalPages'] = $this->intTotalPages;
			}

			// Increase the row count if it is a password field
			if ($objFields->type == 'password')
			{
				++$row;
				++$max_row;

				$arrData['rowClassConfirm'] = 'row_'.$row . (($row == ($max_row - 1)) ? ' row_last' : '') . ((($row % 2) == 0) ? ' even' : ' odd');
			}

			// Submit buttons do not use the name attribute
			if ($objFields->type == 'submit')
			{
				$arrData['name'] = '';
			}

			$objWidget = new $strClass($arrData);
			$objWidget->required = $objFields->mandatory ? true : false;

			if ($objWidget->required)
			{
				$this->arrWidgetsFailedValidation[$objFields->name] = 0;
			}

			// always populate from existing session data if configured
			if ($GLOBALS['EFP'][$formId]['doNotCleanStoredSessionData'] == true && isset($_SESSION['FORM_DATA'][$objFields->name]))
			{
				$objWidget->value = $_SESSION['FORM_DATA'][$objFields->name];
			}

			if ($strMode=='reload' || ($this->blnEditform && !strlen($_POST['FORM_BACK']) && !strlen($_POST['FORM_BACK_x'])))
			{
				// frontend editing
				if ($this->blnEditform && !$_SESSION['EFP'][$formId]['completed']['page_'.$this->intActivePage])
				{
					if (is_array($objWidget->options))
					{
						$arrData['options'] = $objWidget->options;
					}

					// prepare options array
					$arrData['options'] = $this->FormData->prepareDcaOptions($arrData);

					// set rgxp 'date' for field type 'calendar' if not set
					if ($arrData['type'] == 'calendar')
					{
						if (!isset($arrData['rgxp']))
						{
							$arrData['rgxp'] = 'date';
						}
					}
					// set rgxp 'date' and dateFormat for field type 'xdependentcalendarfields'
					elseif ($arrData['type'] == 'xdependentcalendarfields')
					{
						$arrData['rgxp'] = 'date';
						$arrData['dateFormat'] = $arrData['xdateformat'];
					}

					// prepare value
					$varFieldValue = $this->FormData->prepareDbValForWidget($arrEditRecord[$objFields->name], $arrData);

					$objWidget->value = $varFieldValue;
				}
				else
				{
					// populate field if page has been completed
					if ($_SESSION['EFP'][$formId]['completed']['page_'.$this->intActivePage] == true)
					{
						$objWidget->value = $_SESSION['FORM_DATA'][$objFields->name];
					}
					else
					{
						if ($objWidget instanceof uploadable)
						{
							unset($_SESSION['FILES'][$objFields->name]);
						}
					}
				}
			}


			// HOOK: load form field callback
			if (!strlen($_POST['FORM_BACK']) && !strlen($_POST['FORM_BACK_x']))
			{
				if (isset($GLOBALS['TL_HOOKS']['loadFormField']) && is_array($GLOBALS['TL_HOOKS']['loadFormField']))
				{
					foreach ($GLOBALS['TL_HOOKS']['loadFormField'] as $callback)
					{
						$this->import($callback[0]);
						$objWidget = $this->$callback[0]->$callback[1]($objWidget, $formId, $this->arrData);
					}
				}
			}

			// Validate input
			if ($this->Input->post('FORM_SUBMIT') == $formId)
			{
				// populate field
				if (strlen($_POST['FORM_BACK']) || strlen($_POST['FORM_BACK_x']))
				{
					if ($strMode == 'back' && strlen($this->arrPaginators[($this->intActivePage-1)]['efgBackStoreSessionValues']))
					{
						unset($_SESSION['FORM_DATA'][$objFields->name]);
						$objWidget->value = $this->Input->post($objFields->name);
					}
					elseif ($_SESSION['EFP'][$formId]['completed']['page_'.$this->intActivePage] == true )
					{
						$objWidget->value = $_SESSION['FORM_DATA'][$objFields->name];
						unset($_SESSION['FORM_DATA'][$objFields->name]);
					}
				}

				if (!$doNotValidate)
				{
					if ($objWidget instanceof uploadable)
					{
						// if widget does not store the file, store it in tmp folder and session to make it available for mails etc.
						if (!$objWidget->storeFile)
						{
							if ($this->intActivePage < $this->intTotalPages)
							{
								// unset file in session, if this page has not been completed
								if (! $_SESSION['EFP'][$formId]['completed']['page_'.$this->intActivePage])
								{
									unset($_SESSION['FILES'][$objFields->name]);
								}

								$objWidget->validate();

								// file has been uploaded, store it in temp folder
								if (is_uploaded_file($_SESSION['FILES'][$objFields->name]['tmp_name']))
								{
									$this->import('Files');
									$strDstFile = TL_ROOT. '/system/tmp/' .md5_file($_SESSION['FILES'][$objFields->name]['tmp_name']);
									if (@copy($_SESSION['FILES'][$objFields->name]['tmp_name'], $strDstFile))
									{
										$_SESSION['FILES'][$objFields->name]['tmp_name'] = $strDstFile;
										$_SESSION['FILES'][$objFields->name]['uploaded'] = true;
										$this->Files->chmod($strDstFile, 0644);
									}
								}
							}
						}
						else
						{
							$objWidget->validate();
						}
					} // if instance of uploadable
					else
					{
						$objWidget->validate();
					}

					// HOOK: validate form field callback
					if (isset($GLOBALS['TL_HOOKS']['validateFormField']) && is_array($GLOBALS['TL_HOOKS']['validateFormField']))
					{
						foreach ($GLOBALS['TL_HOOKS']['validateFormField'] as $callback)
						{
							$this->import($callback[0]);
							$objWidget = $this->$callback[0]->$callback[1]($objWidget, $formId, $this->arrData);
						}
					}
				}

				if ($objWidget->hasErrors())
				{
					if($objWidget->required)
					{
						$this->arrWidgetsFailedValidation[$objFields->name] = 1;
					}
					$doNotSubmit = true;
				}
				// Store current value in the session
				elseif ($objWidget->submitInput() || $strMode == 'back')
				{
					$arrSubmitted[$objFields->name] = $objWidget->value;
					$_SESSION['FORM_DATA'][$objFields->name] = $objWidget->value;
				}

				unset($_POST[$objFields->name]);
			} // if ($this->Input->post('FORM_SUBMIT') == $formId)


			if ($objWidget instanceof FormHidden)
			{
				$this->Template->hidden .= $objWidget->parse();
				continue;
			}

			if ($objWidget instanceof uploadable)
			{
				$hasUpload = true;

				if ($this->blnMultipage)
				{
					// save file info in session in frontend edit mode
					if ($this->blnEditform && strlen($arrEditRecord[$objFields->name]) && (!isset($_SESSION['FILES'][$objFields->name]) || empty($_SESSION['FILES'][$objFields->name]) ))
					{
						$objFile = new File($arrEditRecord[$objFields->name]);
						if ($objFile->size)
						{
							$_SESSION['FILES'][$objFields->name] = array(
								'name' => $objFile->basename,
								'type' => $objFile->mime,
								'tmp_name' => TL_ROOT . '/' . $objFile->value,
								'size' => $objFile->size,
								'uploaded' => true
							);
						}
					}

					// add info about uploaded file to upload input
					if (isset($_SESSION['FILES'][$objFields->name]) && $_SESSION['FILES'][$objFields->name]['uploaded'])
					{
						$this->Template->fields .= preg_replace('/(.*?)(<input.*?>)(.*?)/sim', '$1<p class="upload_info">'.sprintf($GLOBALS['TL_LANG']['MSC']['fileUploaded'], $_SESSION['FILES'][$objFields->name]['name']).'</p>$2$3', $objWidget->parse());

						++$row;
						continue;
					}
				}
			} // objWidget instanceof uploadable

			$this->Template->fields .= $objWidget->parse();

			++$row;
		} // while $objFields

		if ($doNotSubmit && $this->blnAllowSkipRequired)
		{
			if (count($this->arrWidgetsFailedValidation) && count(array_count_values($this->arrWidgetsFailedValidation)) == 1)
			{
				$doNotSubmit = false;
			}
		}

		// Process form data
		if ($this->Input->post('FORM_SUBMIT') == $formId && !$doNotSubmit)
		{
			if ($this->intTotalPages == 1 || (!$this->blnMultipage && !$this->blnEditform))
			{
				$this->processFormData($arrSubmitted, $arrLabels);
			}
			else
			{
				// if not last page but page is completed, render next page
				if ($this->intActivePage < $this->intTotalPages && (strlen($_POST['FORM_NEXT']) || strlen($_POST['FORM_NEXT_x'])))
				{
					$_SESSION['EFP'][$formId]['render_page'] = ((int) $_POST['FORM_PAGE'] + 1);
					$_SESSION['EFP'][$formId]['completed']['page_'.$_POST['FORM_PAGE']] = true;
					$this->reload();
				}
				// if posted 'back'
				elseif ($strMode == 'back' && $this->intActivePage <= $this->intTotalPages
						&& (strlen($_POST['FORM_BACK']) || strlen($_POST['FORM_BACK_x'])))
				{
					$_SESSION['EFP'][$formId]['render_page'] = ((int) $_POST['FORM_PAGE'] - 1);
					$_SESSION['EFP'][$formId]['completed']['page_'.$_POST['FORM_PAGE']] = true;
					$this->reload();
				}
				// last page completed, process form
				else
				{
					if ((int) $_POST['FORM_PAGE'] == $this->intTotalPages && (strlen($_POST['FORM_NEXT']) || strlen($_POST['FORM_NEXT_x'])))
					{
						unset($_SESSION['EFP'][$formId]['render_page']);
						unset($_SESSION['EFP'][$formId]['completed']);
						unset($_SESSION['FORM_DATA']['FORM_PAGE']);
						unset($_SESSION['FORM_DATA']['FORM_NEXT']);
						unset($_SESSION['FORM_DATA']['FORM_BACK']);

						$arrSubmitted = $_SESSION['FORM_DATA'];
						$this->processFormData($arrSubmitted, $arrLabels);
					}
				}
			}
		}

		$strAttributes = '';
		$arrAttributes = deserialize($this->attributes, true);

		if (strlen($arrAttributes[1]))
		{
			$strAttributes .= ' class="' . $arrAttributes[1] . '"';
		}

		$this->Template->hasError = $doNotSubmit;
		$this->Template->attributes = $strAttributes;
		$this->Template->enctype = $hasUpload ? 'multipart/form-data' : 'application/x-www-form-urlencoded';
		$this->Template->formId = strlen($arrAttributes[0]) ? $arrAttributes[0] : 'f' . $this->id;
		//$this->Template->action = ampersand($this->Environment->request, true);
		$this->Template->action = $this->getIndexFreeRequest();

		// Get target URL
		if ($this->method == 'GET')
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($this->jumpTo);

			if ($objNextPage->numRows)
			{
				$this->Template->action = $this->generateFrontendUrl($objNextPage->fetchAssoc());
			}
		}


		// add Javascript to handle html5 input attribute 'required' on back button
		if ($blnIsHtml5)
		{
			$this->Template->fields .= '
<script>' . $this->getBackButtonJavascriptString() . '
</script>';

		}

		if ($blnAddDateJS)
		{
			$this->Template->fields .= '
<script'.((!$blnIsHtml5) ? ' type="text/javascript"' : '') .'>'
. $this->getDateString() . '
</script>';
		}

		return $this->Template->parse();

	}


	protected function getBackButtonJavascriptString()
	{
		return '
window.addEvent(\'domready\', function(){
	var elForm = document.id(\'{$this->Template->formId}\');
	if (elForm){
		var elBtnBack =	elForm.getElement(\'input[name=FORM_BACK]\');
		if (elBtnBack){
			elBtnBack.addEvent(\'click\', function(){
				elForm.getElements(\'input[required]\').each(function(item){
					item.removeProperty(\'required\');
				});
			});
		}
	}
});';

	}


	/* Return the datepicker string (method is copy from BackendTemplate)
	 *
	 * Fix the MooTools more parsers which incorrectly parse ISO-8601 and do
	 * not handle German date formats at all.
	 * @return string
	 */
	protected function getDateString()
	{
		return '
window.addEvent("domready",function(){'
			. 'Locale.define("en-US","Date",{'
				. 'months:["' . implode('","', $GLOBALS['TL_LANG']['MONTHS']) . '"],'
				. 'days:["' . implode('","', $GLOBALS['TL_LANG']['DAYS']) . '"],'
				. 'months_abbr:["' . implode('","', $GLOBALS['TL_LANG']['MONTHS_SHORT']) . '"],'
				. 'days_abbr:["' . implode('","', $GLOBALS['TL_LANG']['DAYS_SHORT']) . '"]'
			. '});'
			. 'Locale.define("en-US","DatePicker",{'
				. 'select_a_time:"' . $GLOBALS['TL_LANG']['DP']['select_a_time'] . '",'
				. 'use_mouse_wheel:"' . $GLOBALS['TL_LANG']['DP']['use_mouse_wheel'] . '",'
				. 'time_confirm_button:"' . $GLOBALS['TL_LANG']['DP']['time_confirm_button'] . '",'
				. 'apply_range:"' . $GLOBALS['TL_LANG']['DP']['apply_range'] . '",'
				. 'cancel:"' . $GLOBALS['TL_LANG']['DP']['cancel'] . '",'
				. 'week:"' . $GLOBALS['TL_LANG']['DP']['week'] . '"'
			. '});'
		. '});';
	}


}