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
 * Class Efp
 * extended form processing
 *
 * @copyright  Thomas Kuhn 2007-2012
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    efg
 */
class Efp extends Frontend
{

	/**
	 * Key
	 * @var string
	 */
	protected $strKey = 'form';

	/**
	 * Table
	 * @var string
	 */
	protected $strTable = 'tl_form';

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form';

	protected $strFdDcaKey = '';

	protected $strFormdataDetailsKey = 'details';

	/**
	 * Data array
	 * @var array
	 */
	protected $arrData = array();

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Set an object property
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}

	/**
	 * Return an object property
	 * @param string
	 * @return mixed
	 */
	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}


	/**
	 * Optional confirmation mail, optional store data in backend
	 * @param array	Submitted data
	 * @param array	Form configuration
	 * @param array Files uploaded
	 * @param array	Form field labels
	 */
	public function processSubmittedData($arrSubmitted, $arrForm=false, $arrFiles=false, $arrLabels=false) {

		// Form config
		if (!$arrForm)
		{
			return;
		}

		$arrFormFields = array();

// NOTE: maybe use form alias instead in upcoming release
		$this->strFdDcaKey = 'fd_' . (strlen($arrForm['formID']) ? $arrForm['formID'] : str_replace('-', '_', standardize($arrForm['title'])) );

		$this->import('FormData');
		$this->FormData->FdDcaKey = $this->strFdDcaKey;

		$this->import('FrontendUser', 'Member');
		$this->import('String');

		$dirImages = '';

		// get params of related listing formdata
		$intListingId = intval($_SESSION['EFP']['LISTING_MOD']['id']);
		if ($intListingId)
		{
			$objListing = $this->Database->prepare("SELECT * FROM tl_module WHERE id=?")
								->execute($intListingId);
			if ($objListing->numRows)
			{
				$arrListing = $objListing->fetchAssoc();

				// mail delivery defined in frontend listing module
				$arrForm['sendConfirmationMailOnFrontendEditing'] = ($arrListing['efg_fe_no_confirmation_mail']) ? false : true;
				$arrForm['sendFormattedMailOnFrontendEditing'] = ($arrListing['efg_fe_no_formatted_mail']) ? false : true;
			}
		}

		if (strlen($arrListing['efg_DetailsKey']))
		{
			$this->strFormdataDetailsKey = $arrListing['efg_DetailsKey'];
		}

		$blnFEedit = false;
		$intOldId = 0;
		$strRedirectTo = '';

		$strUrl = preg_replace('/\?.*$/', '', $this->Environment->request);
		$strUrlParams = '';
		$strUrlSuffix = $GLOBALS['TL_CONFIG']['urlSuffix'];

		$blnQuery = false;
		foreach (preg_split('/&(amp;)?/', $_SERVER['QUERY_STRING']) as $fragment)
		{
			if (strlen($fragment))
			{
				if (strncasecmp($fragment, $this->strFormdataDetailsKey, strlen($this->strFormdataDetailsKey)) !== 0 && strncasecmp($fragment, 'act', 3) !== 0)
				{
					$strUrlParams .= (!$blnQuery ? '' : '&amp;') . $fragment;
					$blnQuery = true;
				}
			}
		}

		if (in_array($arrListing['efg_fe_edit_access'], array('public','groupmembers','member')))
		{
			if ($this->Input->get('act') == 'edit')
			{
				$blnFEedit = true;

				$objCheck = $this->Database->prepare("SELECT id FROM tl_formdata WHERE id=? OR alias=?")
								->execute($this->Input->get($this->strFormdataDetailsKey), $this->Input->get($this->strFormdataDetailsKey));

				if ($objCheck->numRows == 1)
				{
					$intOldId = intval($objCheck->id);
				}
				else
				{
					$this->log('Could not identify record by ID "'.$this->Input->get($this->strFormdataDetailsKey).'"', 'Efp processSubmittedData()', TL_GENERAL);
				}
			}
		}

		// Types of form fields with storable data
		$arrFFstorable = $this->FormData->arrFFstorable;

		if (($arrForm['storeFormdata'] || $arrForm['sendConfirmationMail'] || $arrForm['sendFormattedMail']) && count($arrSubmitted)>0)
		{
			$timeNow = time();

			$this->loadDataContainer($this->strFdDcaKey);
			$this->loadDataContainer('tl_formdata_details');

			$arrFormFields = $this->FormData->getFormfieldsAsArray($arrForm['id']);

			$arrBaseFields = array();
			$arrDetailFields = array();
			if (count($GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['baseFields']))
			{
				$arrBaseFields = $GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['baseFields'];
			}
			if (count($GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['detailFields']))
			{
				$arrDetailFields = $GLOBALS['TL_DCA']['tl_formdata']['tl_formdata']['detailFields'];
			}
			$arrHookFields = array_merge($arrBaseFields, $arrDetailFields);

			$arrToSave = array();
			foreach($arrSubmitted as $k => $varVal)
			{
				if (in_array($k, array('id')) )
				{
					continue;
				}
				elseif ( in_array($k, $arrHookFields) || in_array($k, array_keys($arrFormFields)) || in_array($k, array('FORM_SUBMIT','MAX_FILE_SIZE')) )
				{
					$arrToSave[$k] = $varVal;
				}
			}

			// HOOK: process efg form data callback
			if (array_key_exists('processEfgFormData', $GLOBALS['TL_HOOKS']) && is_array($GLOBALS['TL_HOOKS']['processEfgFormData']))
			{
				foreach ($GLOBALS['TL_HOOKS']['processEfgFormData'] as $key => $callback)
				{

					$this->import($callback[0]);
					$arrResult = $this->$callback[0]->$callback[1]($arrToSave, $arrFiles, $intOldId, $arrForm, $arrLabels);
					if (is_array($arrResult) && count($arrResult)>0)
					{
						$arrSubmitted = $arrResult;
						$arrToSave = $arrSubmitted;
					}
				}
			}

		}

		// Formdata storage
		if ($arrForm['storeFormdata'] && count($arrSubmitted)>0 )
		{
			$blnStoreOptionsValue = ($arrForm['efgStoreValues']=="1" ? true : false);

			// if frontend editing, get old record
			if ($intOldId > 0)
			{
				$arrOldData = $this->FormData->getFormdataAsArray($intOldId);
				$arrOldFormdata = $arrOldData['fd_base'];
				$arrOldFormdataDetails = $arrOldData['fd_details'];
			}

			// Prepare record tl_formdata
			if ($arrFormFields['name'])
			{
				$strUserName = $arrSubmitted['name'];
			}
			if ($arrFormFields['email'])
			{
				$strUserEmail = $arrSubmitted['email'];
			}
			if ($arrFormFields['confirmationMailRecipientField'])
			{
				$strUserEmail = $arrSubmitted[$arrForm['confirmationMailRecipientField']];
			}
			if ($arrFormFields['message'])
			{
				$strUserMessage = $arrSubmitted['message'];
			}

			$arrSet = array
			(
				'form' => $arrForm['title'],
				'tstamp' => $timeNow,
				'date' => $timeNow,
				'ip' => $this->Environment->ip,
				'published' => ($GLOBALS['TL_DCA']['tl_formdata']['fields']['published']['default'] == '1' ? '1' : '' ),
				'fd_member' => intval($this->Member->id),
				'fd_member_group' => intval($this->Member->groups[0]),
				'fd_user' => intval($this->User->id),
				'fd_user_group' => intval($this->User->groups[0])
			);

			// if frontend editing keep some values from existing record
			if ($intOldId > 0)
			{
				$arrSet['form'] = $arrOldFormdata['form'];
				$arrSet['be_notes'] = $arrOldFormdata['be_notes'];
				$arrSet['fd_member'] = $arrOldFormdata['fd_member'];
				$arrSet['fd_member_group'] = $arrOldFormdata['fd_member_group'];
				if (intval($this->Member->id) > 0)
				{
					$arrSet['fd_member'] = intval($this->Member->id);
					if (count($this->Member->groups) == 1 && intval($this->Member->groups[0]) > 0)
					{
						$arrSet['fd_member_group'] = intval($this->Member->groups[0]);
					}
				}
				else
				{
					$arrSet['fd_member'] = 0;
				}
				$arrSet['fd_user'] = $arrOldFormdata['fd_user'];
				$arrSet['fd_user_group'] = $arrOldFormdata['fd_user_group'];

				// set published to value of old record, if NO default value is defined
				if ( !isset($GLOBALS['TL_DCA']['tl_formdata']['fields']['published']['default']) )
				{
					$arrSet['published'] = $arrOldFormdata['published'];
				}
			}

			// store formdata
			// update or insert and delete
			if ($blnFEedit && strlen($arrListing['efg_fe_keep_id']))
			{
				$intNewId = $intOldId;
				$this->Database->prepare("UPDATE tl_formdata %s WHERE id=?")->set($arrSet)->execute($intOldId);
 				$this->Database->prepare("DELETE FROM tl_formdata_details WHERE pid=?")
								->execute($intOldId);
			}
			else
			{
				$objNewFormdata = $this->Database->prepare("INSERT INTO tl_formdata %s")->set($arrSet)->execute();
				$intNewId = $objNewFormdata->insertId;

				// update related comments
				if (in_array('comments', $this->Config->getActiveModules()))
				{
					$this->Database->prepare("UPDATE tl_comments %s WHERE `source` = 'tl_formdata' AND parent=?")
								->set(array('parent' => $intNewId))
								->execute($intOldId);
				}
			}

			// store details data
			foreach ($arrFormFields as $k => $arrField)
			{
				$strType = $arrField['type'];
				$strVal = '';

				if ( in_array($strType, $arrFFstorable) )
				{

					if ($blnStoreOptionsValue && in_array($strType, array('checkbox', 'radio', 'select')))
					{
						$arrField['eval']['efgStoreValues'] = true;
					}

					// set rgxp 'date' for field type 'calendar' if not set
					if ($arrField['type'] == 'calendar')
					{
						if (!isset($arrField['rgxp']))
						{
							$arrField['rgxp'] = 'date';
						}
					}
					// set rgxp 'date' and dateFormat for field type 'xdependentcalendarfields'
					elseif ($arrField['type'] == 'xdependentcalendarfields')
					{
						$arrField['rgxp'] = 'date';
						$arrField['dateFormat'] = $arrField['xdateformat'];
					}

					$strVal = $this->FormData->preparePostValForDb($arrSubmitted[$k], $arrField, $arrFiles[$k]);

					// special treatment for type upload
					// if frontend editing and no new upload, keep old file
					if ($strType == 'upload')
					{
						if ($intOldId)
						{
							if (!$arrFiles[$k]['name'])
							{
								if (strlen($arrOldFormdataDetails[$k]['value']))
								{
									$strVal = $arrOldFormdataDetails[$k]['value'];
								}
							}
						}
					}

					if ($arrSubmitted[$k] || ($strType == 'upload' && strlen($strVal)) )
					{
						// prepare data
						$arrFieldSet = array(
							'pid' => $intNewId,
							'sorting' => $arrField['sorting'],
							'tstamp' => $timeNow,
							'ff_id' => $arrField['id'],
							'ff_type' => $strType,
							'ff_label' => $arrField['label'],
							'ff_name' => $arrField['name'],
							'value' => $strVal
						);

						$objNewFormdataDetails = $this->Database
								->prepare("INSERT INTO tl_formdata_details %s")
								->set($arrFieldSet)
								->execute();

						if (strlen($_SESSION['EFP_ERROR']))
						{
							unset($_SESSION['EFP_ERROR']);
						}
					}

				}
			} // end foreach $arrFormFields

			// after frontend editing delete old record
			if ($blnFEedit)
			{
				if (!isset($arrListing['efg_fe_keep_id']) || $arrListing['efg_fe_keep_id'] != "1")
				{
					if ($intNewId > 0 && intval($intOldId)>0 && intval($intNewId) != intval($intOldId))
					{
	 					$this->Database->prepare("DELETE FROM tl_formdata_details WHERE pid=?")
	 							->execute($intOldId);
	 					$this->Database->prepare("DELETE FROM tl_formdata WHERE id=?")
	 							->execute($intOldId);
					}
				}
				$strRedirectTo = preg_replace('/\?.*$/', '', $this->Environment->request);
			}

			// auto generate alias
			$strAlias = $this->FormData->generateAlias($arrOldFormdata['alias'], $arrForm['title'], $intNewId);
			if (strlen($strAlias))
			{
				$arrUpd = array('alias' => $strAlias);
				$this->Database->prepare("UPDATE tl_formdata %s WHERE id=?")
								->set($arrUpd)
								->execute($intNewId);
			}
		} // end form data storage

		// store data in session to display on confirmation page
		unset($_SESSION['EFP']['FORMDATA']);
		$blnSkipEmpty = ($arrForm['confirmationMailSkipEmpty']) ? true : false;

		foreach ($arrFormFields as $k => $arrField)
		{
			$strType = $arrField['type'];
			$strVal = '';
			if (in_array($strType, $arrFFstorable))
			{
				$strVal = $this->FormData->preparePostValForMail($arrSubmitted[$k], $arrField, $arrFiles[$k], $blnSkipEmpty);
			}

			$_SESSION['EFP']['FORMDATA'][$k] = $strVal;
		}
		$_SESSION['EFP']['FORMDATA']['_formId_'] = $arrForm['id'];
		// end store data in session

		// Confirmation Mail

		if ($blnFEedit && !$arrForm['sendConfirmationMailOnFrontendEditing'])
		{
			$arrForm['sendConfirmationMail'] = false;
		}

		if ($arrForm['sendConfirmationMail'])
		{
			$this->import('String');
			$messageText = '';
			$messageHtml = '';
			$messageHtmlTmpl = '';
			$recipient  = '';
			$arrRecipient = array();
			$sender = '';
			$senderName = '';
			$replyTo = '';
			$attachments = array();

			$blnSkipEmpty = ($arrForm['confirmationMailSkipEmpty']) ? true : false;

			$sender = $arrForm['confirmationMailSender'];
			if (strlen($sender)){
				$sender = str_replace(array('[', ']'), array('<', '>'), $sender);
				if (strpos($sender, '<')>0) {
					preg_match('/(.*)?<(\S*)>/si', $sender, $parts);
					$sender = $parts[2];
					$senderName = trim($parts[1]);
				}
			}

			$recipientFieldName = $arrForm['confirmationMailRecipientField'];
			$varRecipient = $arrSubmitted[$recipientFieldName];
			if (is_array($varRecipient))
			{
				$arrRecipient = $varRecipient;
			}
			else
			{
				$arrRecipient = trimsplit(',', $varRecipient);
			}

			if (strlen($arrForm['confirmationMailRecipient']))
			{
				$varRecipient = $arrForm['confirmationMailRecipient'];
				$arrRecipient = array_merge($arrRecipient, trimsplit(',', $varRecipient));
			}
			$arrRecipient = array_unique($arrRecipient);

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
			if (strlen($messageText))
			{
				$messageText = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $messageText);
			}
			if (strlen($messageHtml))
			{
				$messageHtml = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $messageHtml);
			}

			if (strlen($subject))
			{
				$subject = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $subject);
			}
			if (strlen($sender))
			{
				$sender = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $sender);
			}

			$blnEvalSubject = $this->FormData->replaceConditionTags($subject);
			$blnEvalMessageText = $this->FormData->replaceConditionTags($messageText);
			$blnEvalMessageHtml = $this->FormData->replaceConditionTags($messageHtml);

			// Replace tags in messageText, messageHtml ...
	 		$tags = array();
 			//preg_match_all('/{{[^{}]+}}/i', $messageText . $messageHtml . $subject . $sender, $tags);
			preg_match_all('/__BRCL__.*?__BRCR__/si', $messageText . $messageHtml . $subject . $sender, $tags);

	 		// Replace tags of type {{form::<form field name>}}
			// .. {{form::uploadfieldname?attachment=true}}
			// .. {{form::fieldname?label=Label for this field: }}
	 		foreach ($tags[0] as $tag)
	 		{
	 			//$elements = explode('::', str_replace(array('{{', '}}'), array('', ''), $tag));
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
 						$arrField['efgMailSkipEmpty'] = $blnSkipEmpty;

 						$strType = $arrField['type'];

						$strLabel = '';
						$strVal = '';

						if ($arrTagParams && strlen($arrTagParams['label']))
						{
							$strLabel = $arrTagParams['label'];
						}

						if (in_array($strType, $arrFFstorable) )
						{
							if ( $strType == 'efgImageSelect' )
							{
								$strVal = '';
								$varVal = $this->FormData->preparePostValForMail($arrSubmitted[$strKey], $arrField, $arrFiles[$strKey]);
								$varTxt = array();
								$varHtml = array();
								if (is_string($varVal)) $varVal = array($varVal);
								if (count($varVal))
								{
									foreach ($varVal as $strVal)
									{
										if (strlen($strVal))
										{
											$varTxt[] = $this->Environment->base . $strVal;
											$varHtml[] = '<img src="' . $strVal . '" />';
										}
									}
								}
								if (!count($varTxt) &&  $blnSkipEmpty)
								{
									$strLabel = '';
								}

								$messageText = str_replace($tag, $strLabel . implode(', ', $varTxt), $messageText);
			 					$messageHtml = str_replace($tag, $strLabel . implode(' ', $varHtml) , $messageHtml);
							}
							elseif ($strType=='upload')
							{
								if ($arrTagParams && ((array_key_exists('attachment', $arrTagParams) && $arrTagParams['attachment'] == true) || (array_key_exists('attachement', $arrTagParams) && $arrTagParams['attachement'] == true)) )
								{
									if (strlen($arrFiles[$strKey]['tmp_name']) && is_file($arrFiles[$strKey]['tmp_name']))
									{
										if (!isset($attachments[$arrFiles[$strKey]['tmp_name']]))
										{
											$attachments[$arrFiles[$strKey]['tmp_name']] = array('name'=>$arrFiles[$strKey]['name'], 'file'=>$arrFiles[$strKey]['tmp_name'], 'mime'=>$arrFiles[$strKey]['type']);
										}

									}
									$strVal = '';
								}
								else
								{
									$strVal = $this->FormData->preparePostValForMail($arrSubmitted[$strKey], $arrField, $arrFiles[$strKey]);
								}
								if (!is_array($strVal) && !strlen($strVal) && $blnSkipEmpty)
								{
									$strLabel = '';
								}
								$messageText = str_replace($tag, $strLabel . $strVal, $messageText);
			 					$messageHtml = str_replace($tag, $strLabel . $strVal, $messageHtml);
							}
							else
							{
								$strVal = $this->FormData->preparePostValForMail($arrSubmitted[$strKey], $arrField, $arrFiles[$strKey]);
								if (!is_array($strVal) && !strlen($strVal) && $blnSkipEmpty)
								{
									$strLabel = '';
								}
								$messageText = str_replace($tag, $strLabel . $strVal, $messageText);

								if (is_string($strVal) && strlen($strVal) && !is_bool(strpos($strVal, "\n")))
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
			} // foreach tags

			// Replace standard insert tags
			if (strlen($messageText))
			{
				$messageText = preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $messageText);
				$messageText = $this->replaceInsertTags($messageText);
				if ($blnEvalMessageText)
				{
					$messageText = $this->FormData->evalConditionTags($messageText, $arrSubmitted, $arrFiles, $arrForm);
				}
				$messageText = strip_tags($messageText);
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
			// replace insert tags in subject
			if (strlen($subject))
			{
				$subject = preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $subject);
				$subject = $this->replaceInsertTags($subject);
				if ($blnEvalSubject)
				{
					$subject = $this->FormData->evalConditionTags($subject, $arrSubmitted, $arrFiles, $arrForm);
				}
			}
			// replace insert tags in sender
			if (strlen($sender))
			{
				$sender = preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $sender);
				// 2008-09-20 tom: Controller->replaceInsertTags seems not to work if string to parse contains insert tag only, so add space and trim result
				$sender = trim($this->replaceInsertTags(" " . $sender . " "));
			}

			// replace insert tags in replyto
			if (strlen($arrForm['confirmationMailReplyto']))
			{
				$replyTo = $this->replaceInsertTags($arrForm['confirmationMailReplyto']);
			}

			$confEmail = new Email();
			$confEmail->from = $sender;
			if (strlen($senderName))
			{
				$confEmail->fromName = $senderName;
			}
			if (strlen($replyTo))
			{
				$confEmail->replyTo($replyTo);
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
					if(is_array($arrCustomAttachments))
					{
						foreach ($arrCustomAttachments as $strFile)
						{
							if(is_file($strFile))
							{
								if(is_readable($strFile))
								{
									$objFile = new File($strFile);
									if ($objFile->size)
									{
										$attachments[$objFile->value] = array('file' => TL_ROOT . '/' . $objFile->value, 'name' => $objFile->basename, 'mime' => $objFile->mime);
									}
								}
							}
						}
					}
				}
			}

			if (is_array($attachments) && count($attachments)>0)
			{
				foreach ($attachments as $strFile => $varParams)
				{
					$strContent = file_get_contents($strFile, false);
					$confEmail->attachFileFromString($strContent, $varParams['name'], $varParams['mime']);
				}
			}

			if ($dirImages != '')
			{
				$confEmail->imageDir = $dirImages;
			}
			if ( $messageText != '' )
			{
				$confEmail->text = $messageText;
			}
			if ( $messageHtml != '' )
			{
				$confEmail->html = $messageHtml;
			}

			// Send e-mail
			$blnConfirmationSent = false;
			if (count($arrRecipient)>0)
			{
				foreach ($arrRecipient as $recipient)
				{
					if(strlen($recipient))
					{
						$recipient = str_replace(array('[', ']'), array('<', '>'), $recipient);
						$recipientName = '';
						if (strpos($recipient, '<')>0)
						{
							preg_match('/(.*)?<(\S*)>/si', $recipient, $parts);
							$recipientName = trim($parts[1]);
							$recipient = (strlen($recipientName) ? $recipientName.' <'.$parts[2].'>' : $parts[2]);
						}
					}

					$confEmail->sendTo($recipient);
					$blnConfirmationSent = true;
				}
			}

			if ($blnConfirmationSent && isset($intNewId) && intval($intNewId)>0)
			{
				$arrUpd = array('confirmationSent' => '1', 'confirmationDate' => $timeNow);
				$res = $this->Database->prepare("UPDATE tl_formdata %s WHERE id=?")
								->set($arrUpd)
								->execute($intNewId);
			}

		} // End confirmation mail

		// Information (formatted) Mail

		if ($blnFEedit && !$arrForm['sendFormattedMailOnFrontendEditing'])
		{
			$arrForm['sendFormattedMail'] = false;
		}

		if ($arrForm['sendFormattedMail'])
		{
			$this->import('String');
			$messageText = '';
			$messageHtml = '';
			$messageHtmlTmpl = '';
			$recipient  = '';
			$arrRecipient = array();
			$sender = '';
			$senderName = '';
			$attachments = array();

			$blnSkipEmpty = ($arrForm['formattedMailSkipEmpty']) ? true : false;

			// Set the admin e-mail as "from" address
			$sender = $GLOBALS['TL_ADMIN_EMAIL'];
			if(strlen($sender)){
				$sender = str_replace(array('[', ']'), array('<', '>'), $sender);
				if (strpos($sender, '<')>0)
				{
					preg_match('/(.*)?<(\S*)>/si', $sender, $parts);
					$sender = $parts[2];
					$senderName = trim($parts[1]);
				}
			}

			$varRecipient = $arrForm['formattedMailRecipient'];
			if (is_array($varRecipient))
			{
				$arrRecipient = $varRecipient;
			}
			else
			{
				$arrRecipient = trimsplit(',', $varRecipient);
			}
			$arrRecipient = array_unique($arrRecipient);

			$subject = $this->String->decodeEntities($arrForm['formattedMailSubject']);
			$messageText = $this->String->decodeEntities($arrForm['formattedMailText']);
			$messageHtmlTmpl = $arrForm['formattedMailTemplate'];

			if ( $messageHtmlTmpl != '' )
			{
				$fileTemplate = new File($messageHtmlTmpl);
				if ( $fileTemplate->mime == 'text/html' )
				{
					$messageHtml = $fileTemplate->getContent();
				}
			}

			// prepare insert tags to handle separate from 'condition tags'
			if (strlen($messageText))
			{
				$messageText = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $messageText);
			}
			if (strlen($messageHtml))
			{
				$messageHtml = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $messageHtml);
			}
			if (strlen($subject))
			{
				$subject = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $subject);
			}
			if (strlen($sender))
			{
				$sender = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $sender);
			}

			$blnEvalSubject = $this->FormData->replaceConditionTags($subject);
			$blnEvalMessageText = $this->FormData->replaceConditionTags($messageText);
			$blnEvalMessageHtml = $this->FormData->replaceConditionTags($messageHtml);

			// Replace tags in messageText, messageHtml ...
	 		$tags = array();
 			//preg_match_all('/{{[^{}]+}}/i', $messageText . $messageHtml . $subject . $sender, $tags);
			preg_match_all('/__BRCL__.*?__BRCR__/si', $messageText . $messageHtml . $subject . $sender, $tags);

	 		// Replace tags of type {{form::<form field name>}}
			// .. {{form::uploadfieldname?attachment=true}}
			// .. {{form::fieldname?label=Label for this field: }}
	 		foreach ($tags[0] as $tag)
	 		{
	 			//$elements = explode('::', trim(str_replace(array('{{', '}}'), array('', ''), $tag)));
	 			$elements = explode('::', trim(str_replace(array('__BRCL__', '__BRCR__'), array('', ''), $tag)));
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
 						$arrField['efgMailSkipEmpty'] = $blnSkipEmpty;

 						$strType = $arrField['type'];

						$strLabel = '';
						$strVal = '';

						if ($arrTagParams && strlen($arrTagParams['label']))
						{
							$strLabel = $arrTagParams['label'];
						}

						if ( in_array($strType, $arrFFstorable) )
						{
							if ( $strType == 'efgImageSelect' )
							{
								$strVal = '';
								$varVal = $this->FormData->preparePostValForMail($arrSubmitted[$strKey], $arrField, $arrFiles[$strKey]);
								$varTxt = array();
								$varHtml = array();
								if (is_string($varVal)) $varVal = array($varVal);
								if (count($varVal))
								{
									foreach ($varVal as $strVal)
									{
										if (strlen($strVal))
										{
											$varTxt[] = $this->Environment->base . $strVal;
											$varHtml[] = '<img src="' . $strVal . '" />';
										}
									}
								}
								if (!count($varTxt) &&  $blnSkipEmpty)
								{
									$strLabel = '';
								}

								$messageText = str_replace($tag, $strLabel . implode(', ', $varTxt), $messageText);
			 					$messageHtml = str_replace($tag, $strLabel . implode(' ', $varHtml) , $messageHtml);
							}
							elseif ($strType=='upload')
							{
								if ($arrTagParams && ((array_key_exists('attachment', $arrTagParams) && $arrTagParams['attachment'] == true) || (array_key_exists('attachement', $arrTagParams) && $arrTagParams['attachement'] == true)) )
								{
									if (strlen($arrFiles[$strKey]['tmp_name']) && is_file($arrFiles[$strKey]['tmp_name']))
									{
										if (!isset($attachments[$arrFiles[$strKey]['tmp_name']]))
										{
											$attachments[$arrFiles[$strKey]['tmp_name']] = array('name'=>$arrFiles[$strKey]['name'], 'file'=>$arrFiles[$strKey]['tmp_name'], 'mime'=>$arrFiles[$strKey]['type']);
										}
									}
									$strVal = '';
								}
								else
								{
									$strVal = $this->FormData->preparePostValForMail($arrSubmitted[$strKey], $arrField, $arrFiles[$strKey]);
								}
								if (!is_array($strVal) && !strlen($strVal) && $blnSkipEmpty)
								{
									$strLabel = '';
								}
								$messageText = str_replace($tag, $strLabel . $strVal, $messageText);
			 					$messageHtml = str_replace($tag, $strLabel . $strVal, $messageHtml);
							}
							else
							{
								$strVal = $this->FormData->preparePostValForMail($arrSubmitted[$strKey], $arrField, $arrFiles[$strKey]);
								if (!is_array($strVal) && !strlen($strVal) && $blnSkipEmpty)
								{
									$strLabel = '';
								}
								$messageText = str_replace($tag, $strLabel . $strVal, $messageText);

								if (is_string($strVal) && strlen($strVal) && !is_bool(strpos($strVal, "\n")))
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

			// Replace standard insert tags
			if (strlen($messageText))
			{
				$messageText = preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $messageText);
				$messageText = $this->replaceInsertTags($messageText);
				if ($blnEvalMessageText)
				{
					$messageText = $this->FormData->evalConditionTags($messageText, $arrSubmitted, $arrFiles, $arrForm);
				}
				$messageText = strip_tags($messageText);
			}
			if (strlen($messageHtml))
			{
				$messageHtml =  preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $messageHtml);
				$messageHtml = $this->replaceInsertTags($messageHtml);
				if ($blnEvalMessageHtml)
				{
					$messageHtml = $this->FormData->evalConditionTags($messageHtml, $arrSubmitted, $arrFiles, $arrForm);
				}
			}
			// replace insert tags in subject
			if (strlen($subject))
			{
				$subject = preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $subject);
				$subject = $this->replaceInsertTags($subject);
				if ($blnEvalSubject)
				{
					$subject = $this->FormData->evalConditionTags($subject, $arrSubmitted, $arrFiles, $arrForm);
				}
			}
			// replace insert tags in sender
			if (strlen($sender))
			{
				$sender = preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $sender);
				// 2008-09-20 tom: Controller->replaceInsertTags seems not to work if string to parse contains insert tag only, so add space and trim result
				$sender = trim($this->replaceInsertTags(" " . $sender . " "));
			}

			$infoEmail = new Email();
			$infoEmail->from = $sender;
			if (strlen($senderName))
			{
				$infoEmail->fromName = $senderName;
			}
			$infoEmail->subject = $subject;

			// Get "reply to" address, if form contains field named 'email'
			if (isset($arrSubmitted['email']) && strlen($arrSubmitted['email']) && !is_bool(strpos($arrSubmitted['email'], '@')))
			{
				$replyTo = $arrSubmitted['email'];
				// add name
				if (isset($arrSubmitted['name']) && strlen($arrSubmitted['name']))
				{
					$replyTo = '"'. $arrSubmitted['name'] .'" <' . $arrSubmitted['email'] . '>';
				}
				$infoEmail->replyTo($replyTo);
			}

			// check if we want custom attachments...
			if ($arrForm['addFormattedMailAttachments'])
			{
				// check if we have custom attachments...
				if($arrForm['formattedMailAttachments'])
				{
					$arrCustomAttachments = deserialize($arrForm['formattedMailAttachments'], true);
					if(is_array($arrCustomAttachments))
					{
						foreach ($arrCustomAttachments as $strFile)
						{
							if(is_file($strFile))
							{
								if(is_readable($strFile))
								{
									$objFile = new File($strFile);
									if ($objFile->size)
									{
										$attachments[$objFile->value] = array('file' => TL_ROOT . '/' . $objFile->value, 'name' => $objFile->basename, 'mime' => $objFile->mime);
									}
								}
							}
						}
					}
				}
			}

			if (is_array($attachments) && count($attachments)>0)
			{
				foreach ($attachments as $strFile => $varParams)
				{
					$strContent = file_get_contents($strFile, false);
					$infoEmail->attachFileFromString($strContent, $varParams['name'], $varParams['mime']);
				}
			}

			if ($dirImages != '')
			{
				$infoEmail->imageDir = $dirImages;
			}
			if ( $messageText != '' )
			{
				$infoEmail->text = $messageText;
			}
			if ( $messageHtml != '' )
			{
				$infoEmail->html = $messageHtml;
			}

			// Send e-mail
			if (count($arrRecipient)>0)
			{
				foreach ($arrRecipient as $recipient)
				{
					if(strlen($recipient))
					{
						$recipient = str_replace(array('[', ']'), array('<', '>'), $recipient);
						$recipientName = '';
						if (strpos($recipient, '<')>0)
						{
							preg_match('/(.*)?<(\S*)>/si', $recipient, $parts);
							$recipientName = trim($parts[1]);
							$recipient = (strlen($recipientName) ? $recipientName.' <'.$parts[2].'>' : $parts[2]);
						}
					}

					$infoEmail->sendTo($recipient);
				}
			}

		} // End information mail

		// redirect after frontend editing
		if ($blnFEedit)
		{
			if (strlen($strRedirectTo))
			{
				$strRed = preg_replace(array('/\/'.$this->strFormdataDetailsKey.'\/'.$this->Input->get($this->strFormdataDetailsKey).'/i', '/'.$this->strFormdataDetailsKey.'='.$this->Input->get($this->strFormdataDetailsKey).'/i', '/act=edit/i'), array('','',''), $strUrl) . (strlen($strUrlParams) ? '?'.$strUrlParams : '');
				$this->redirect($strRed);
			}
		}

	}

	/*
	 * Callback function to display submitted data on confirmation page
	 */
	public function processConfirmationContent($strContent)
	{

		$arrSubmitted = $_SESSION['EFP']['FORMDATA'];

		// fix: after submission of normal single page form array $_SESSION['EFP']['FORMDATA'] is empty
		if (is_null($arrSubmitted) || (count($arrSubmitted) == 1 && array_keys($arrSubmitted) === array('_formId_')) )
		{
			$arrSubmitted = $_SESSION['FORM_DATA'];
			$arrSubmitted['_formId_'] = $_SESSION['EFP']['FORMDATA'];
		}

		$blnProcess = false;
		if (preg_match('/\{\{form::/si', $strContent)) {
			$blnProcess = true;
		}

 		if ($arrSubmitted && count($arrSubmitted)>0 && isset($arrSubmitted['_formId_']) && $blnProcess)
		{
			$blnSkipEmpty = false;

			$objSkip = $this->Database->prepare("SELECT confirmationMailSkipEmpty FROM tl_form WHERE id=?")->execute($arrSubmitted['_formId_']);
			if ($objSkip->confirmationMailSkipEmpty == 1)
			{
				$blnSkipEmpty = true;
			}

			$this->import('FormData');
			$arrFormFields = $this->FormData->getFormfieldsAsArray(intval($arrSubmitted['_formId_']));

			preg_match('/<body[^>]*?>.*?<\/body>/si', $strContent, $arrMatch);

			if (is_array($arrMatch) && count($arrMatch))
			{

				for ($m=0; $m < count($arrMatch); $m++)
				{
					$strTemp = $arrMatch[$m];
					$strTemp = preg_replace(array('/\{\{/', '/\}\}/'), array('__BRCL__', '__BRCR__'), $strTemp);
					$blnEval = $this->FormData->replaceConditionTags($strTemp);

					// Replace tags
					$tags = array();
					// preg_match_all('/{{[^{}]+}}/i', $strContent, $tags);
					preg_match_all('/__BRCL__.*?__BRCR__/si', $strTemp, $tags);

					// Replace tags of type {{form::<form field name>}}
					// .. {{form::fieldname?label=Label for this field: }}
					foreach ($tags[0] as $tag)
					{
						// $elements = explode('::', trim(str_replace(array('{{', '}}'), array('', ''), $tag)));
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

								$strLabel = '';
								$strVal = '';
								if ($arrTagParams && strlen($arrTagParams['label']))
								{
									$strLabel = $arrTagParams['label'];
								}

								$strVal = $arrSubmitted[$strKey];
								if (is_array($strVal))
								{
									$strVal = implode(', ', $strVal);
								}

								if (strlen($strVal))
								{
									$strVal = nl2br($strVal);
								}

								if (!strlen($strVal) && $blnSkipEmpty)
								{
									$strLabel = '';
								}

								// $strContent = str_replace($tag, $strLabel . $strVal, $strContent);
								$strTemp = str_replace($tag, $strLabel . $strVal, $strTemp);
							break;
						}
					}
					// unset($_SESSION['EFP']['FORMDATA']);

					$strTemp = preg_replace(array('/__BRCL__/', '/__BRCR__/'), array('{{', '}}'), $strTemp);

					// Eval the code
					if ($blnEval)
					{
						ob_start();
						$blnCheck = eval("?>" . $strTemp);
						$strCheck = ob_get_contents();
						$strTemp = $strCheck;
						ob_end_clean();
					}

					$strContent = str_replace($arrMatch[$m], $strTemp, $strContent);

				}
			}

		}

		return $strContent;

	}

}