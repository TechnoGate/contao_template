<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 *
 * The TYPOlight webCMS is an accessible web content management system that 
 * specializes in accessibility and generates W3C-compliant HTML code. It 
 * provides a wide range of functionality to develop professional websites 
 * including a built-in search engine, form generator, file and user manager, 
 * CSS engine, multi-language support and many more. For more information and 
 * additional TYPOlight applications like the TYPOlight MVC Framework please 
 * visit the project website http://www.typolight.org.
 *
 * This is the data container array for table tl_content.
 *
 * PHP version 5
 * @copyright  2007 
 * @author     Felix Pfeiffer 
 * @package    subcolumns v 1.1.1 
 * @license    GPL 
 * @filesource
 */


/**
 * Table tl_content 
 */

$GLOBALS['TL_DCA']['tl_content']['fields']['sc_name'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_content']['sc_name'],
	'inputType'	=> 'text',
	'save_callback' => array(array('tl_content_sc','setColsetName')),
	'eval'		=> array('maxlength'=>'255','unique'=>true,'spaceToUnderscore'=>true)		
);

$GLOBALS['TL_DCA']['tl_content']['fields']['sc_gap'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_content']['sc_gap'],
	'inputType'	=> 'text',
	'eval'		=> array('maxlength'=>'4','regxp'=>'digit', 'tl_class'=>'w50')		
);

$GLOBALS['TL_DCA']['tl_content']['fields']['sc_type'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_content']['sc_type'],
	'inputType'	=> 'select',
	#'options'	=> array('25x25x25x25','33x33x33','50x25x25','25x50x25','25x25x50','40x30x30','30x40x30','30x30x40','20x40x40','40x20x40','40x40x20','80x20','75x25','70x30','66x33','62x38','60x40','55x45','50x50','45x55','40x60','38x62','33x66','30x70','25x75','20x80'),
	'options' => array_keys($GLOBALS['TL_SUBCL']),
	'eval'		=> array('mandatory'=>true, 'tl_class'=>'w50')		
);

$GLOBALS['TL_DCA']['tl_content']['fields']['sc_gapdefault'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_content']['sc_gapdefault'],
	'default'	=> 1,
	'inputType'	=> 'checkbox',
	'eval'		=> array('tl_class'=>'clr w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['sc_equalize'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['tl_content']['sc_equalize'],
	'inputType'	=> 'checkbox',
	'eval'		=> array()		
);

/* Extend existing fields with additional functionality */

$GLOBALS['TL_DCA']['tl_content']['fields']['invisible']['save_callback'][] = array('tl_content_sc','toggleAdditionalElements');

/* hidden fields */

$GLOBALS['TL_DCA']['tl_content']['fields']['sc_parent'] = array
(
	'inputType'	=> 'text',	
);
$GLOBALS['TL_DCA']['tl_content']['fields']['sc_childs'] = array
(
	'inputType'	=> 'text',	
);
$GLOBALS['TL_DCA']['tl_content']['fields']['sc_sortid'] = array
(
	'inputType'	=> 'text',	
);


$GLOBALS['TL_DCA']['tl_content']['palettes']['colsetStart'] = '{type_legend},type;{colset_legend},sc_name,sc_type,sc_gapdefault,sc_gap;{colheight_legend:hide},sc_equalize;{protected_legend:hide},protected;{expert_legend:hide},guests,invisible,cssID,space';
$GLOBALS['TL_DCA']['tl_content']['palettes']['colsetPart'] = 'cssID';
$GLOBALS['TL_DCA']['tl_content']['palettes']['colsetEnd'] = $GLOBALS['TL_DCA']['tl_content']['palettes']['default'];

$GLOBALS['TL_DCA']['tl_content']['config']['onsubmit_callback'][] = array('tl_content_sc','scUpdate');
$GLOBALS['TL_DCA']['tl_content']['config']['onsubmit_callback'][] = array('tl_content_sc','setElementProperties');
$GLOBALS['TL_DCA']['tl_content']['config']['ondelete_callback'][] = array('tl_content_sc','scDelete');
$GLOBALS['TL_DCA']['tl_content']['config']['oncopy_callback'][] = array('tl_content_sc','scCopy');

/**
 * Operations
**/
$arrModules = $this->Config->getActiveModules();
if(!in_array('ce-access',$arrModules))
{
	$GLOBALS['TL_DCA']['tl_content']['list']['operations']['edit']['button_callback'] = array('tl_content_sc','showEditOperation'); 
	$GLOBALS['TL_DCA']['tl_content']['list']['operations']['copy']['button_callback'] = array('tl_content_sc','showCopyOperation'); 
	#$GLOBALS['TL_DCA']['tl_content']['list']['operations']['delete']['button_callback'] = array('tl_content_sc','showDeleteOperation'); 
	#$GLOBALS['TL_DCA']['tl_content']['list']['operations']['toggle']['button_callback'] = array('tl_content_sc','toggleIcons'); 
}
/**
 * Erweiterung für die tl_conten-Klasse
 */

class tl_content_sc extends tl_content
{
	/**
	 * Autogenerate an name for the colset if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function setColsetName($varValue, DataContainer $dc)
	{
		$autoName = false;
		
		// Generate alias if there is none
		if (!strlen($varValue))
		{	
			$autoName = true;
			$varValue = 'colset.' . $dc->id;
		}

		return $varValue;
	}
	
	/**
	 * Write the other Sets
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function scUpdate(DC_Table $dc)
	{
		
		if($dc->activeRecord->type != 'colsetStart' || $dc->activeRecord->sc_type == "") return '';
		
		$id = $dc->id;
					
		$sorting = $dc->activeRecord->sorting;
		
		$arrColset = $GLOBALS['TL_SUBCL'][$dc->activeRecord->sc_type];
		
		$arrChilds = $dc->activeRecord->sc_childs != "" ? unserialize($dc->activeRecord->sc_childs) : "";
		
		if($dc->activeRecord->sc_gapdefault == 1)
		{
			$gap_value = $dc->activeRecord->sc_gap != "" ? $dc->activeRecord->sc_gap : '12';
		}
		
		$intColcount = count($arrColset) - 2;
		
		
		
		/* Neues Spaltenset anlegen */
		if($arrChilds == '')
		{
			
			$arrChilds = array();
			
			$this->moveRows($dc->activeRecord->pid,$dc->activeRecord->sorting,128 * ( count($arrColset) + 1 ));
			
			$arrSet = array('pid' => $dc->activeRecord->pid,
							'tstamp' => time(),
							'sorting'=>0,
							'type' => 'colsetPart',
							'sc_name'=> '',
							'sc_type'=>$dc->activeRecord->sc_type,
							'sc_parent'=>$dc->activeRecord->id,
							'sc_sortid'=>0,
							'sc_gap' => $dc->activeRecord->sc_gap,
							'sc_gapdefault' => $dc->activeRecord->sc_gapdefault
							);

                        if(in_array('GlobalContentelements',$this->Config->getActiveModules()))
		        {
			     $arrSet['do'] = $this->Input->get('do');
		        }			

			for($i=1;$i<=$intColcount+1;$i++)
			{
				
				$arrSet['sorting'] = $dc->activeRecord->sorting+($i+1)*64;
				$arrSet['sc_name'] = $dc->activeRecord->sc_name.'-Part-'.($i);
				$arrSet['sc_sortid'] = $i;
				
				$insertElement = $this->Database->prepare("INSERT INTO tl_content %s")
												->set($arrSet)
												->execute()
												->insertId;
				
				$arrChilds[] = $insertElement;
			}
			
			$arrSet['sorting'] = $dc->activeRecord->sorting+($i+1)*64;
			$arrSet['type'] = 'colsetEnd';
			$arrSet['sc_name'] = $dc->activeRecord->sc_name.'-End';
			$arrSet['sc_sortid'] = $intColcount+2;
			
			$insertElement = $this->Database->prepare("INSERT INTO tl_content %s")
											->set($arrSet)
											->execute()
											->insertId;
			
			$arrChilds[] = $insertElement;
			
			$insertElement = $this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set(array('sc_childs'=>$arrChilds,'sc_parent'=>$dc->activeRecord->id,))
											->execute($dc->id);
			
			return true;
		
		}
		
		/* Gleiche Spaltenzahl */
		if(count($arrChilds) == count($arrColset))
		{
			$intLastElement = array_pop($arrChilds);
			
			$i = 1;
			foreach($arrChilds as $v)
			{
				$arrSet = array('sc_type' => $dc->activeRecord->sc_type,
								'sc_gap' => $dc->activeRecord->sc_gap,
								'sc_gapdefault' => $dc->activeRecord->sc_gapdefault,
								'sc_sortid' => $i,
								'sc_name' => $dc->activeRecord->sc_name.'-Part-'.($i++)
								
				);
				
				$this->Database->prepare("UPDATE tl_content %s WHERE id=".$v)
											->set($arrSet)
											->execute();
			}
			
			$arrSet = array('sc_type' => $dc->activeRecord->sc_type,
							'sc_gap' => $dc->activeRecord->sc_gap,
							'sc_sortid' => $i,
							'sc_name' => $dc->activeRecord->sc_name.'-End'
			);
			
			$this->Database->prepare("UPDATE tl_content %s WHERE id=".$intLastElement)
										->set($arrSet)
										->execute();
			
			
			
			return true;
			
		}
		
		/* Weniger Spalten */
		if(count($arrChilds) > count($arrColset))
		{
		
			$intDiff = count($arrChilds) - count($arrColset);
			
			for($i=1;$i<=$intDiff;$i++)
			{
				$intChildId = array_pop($arrChilds);
				$this->Database->prepare("DELETE FROM tl_content WHERE id=?")
											->execute($intChildId);
				
			}
			
			$this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set(array('sc_childs'=>$arrChilds))
											->execute($dc->id);
			
			/* Andere Daten im Colset anpassen - Spaltenabstand und SpaltenSet-Typ */
			$arrSet = array('sc_type' => $dc->activeRecord->sc_type,
							'sc_gap' => $dc->activeRecord->sc_gap,
							'sc_gapdefault' => $dc->activeRecord->sc_gapdefault
							);
			
			foreach($arrChilds as $value)
			{
			
				$this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set($arrSet)
											->execute($value);
			
			}
			
			/*  Den Typ des letzten Elements auf End-ELement umsetzen und FSC-namen anpassen */
			$intChildId = array_pop($arrChilds);
			
			$arrSet['sc_name'] = $dc->activeRecord->sc_name.'-End';
			$arrSet['type'] = 'colsetEnd';
			
			$this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set($arrSet)
											->execute($intChildId);
			
			return true;
		}
		
		/* Mehr Spalten */
		if(count($arrChilds) < count($arrColset))
		{
		
			$intDiff = count($arrColset) - count($arrChilds);
			
			$objEnd = $this->Database->prepare("SELECT id,sorting,sc_sortid FROM tl_content WHERE id=?")->execute($arrChilds[count($arrChilds)-1]);
			
			$this->moveRows($dc->activeRecord->pid,$objEnd->sorting,64 * ( $intDiff) );
			
			/*  Den Typ des letzten Elements auf End-ELement umsetzen und SC-namen anpassen */
			$intChildId	= count($arrChilds);
			$arrSet['sc_name'] = $dc->activeRecord->sc_name.'-Part-'.($intChildId);
			$arrSet['type'] = 'colsetPart';
			
			$this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set($arrSet)
											->execute($objEnd->id);
			
			
			
			$intFscSortId = $objEnd->sc_sortid;
			$intSorting = $objEnd->sorting;
			
			$arrSet = array('type' => 'colsetPart',
							'pid' => $dc->activeRecord->pid,
							'tstamp' => time(),
							'sorting' => 0,
							'sc_name' => '',
							'sc_type' => $dc->activeRecord->sc_type,
							'sc_parent' => $dc->id,
							'sc_sortid' => 0,
							'sc_gap' => $dc->activeRecord->sc_gap,
							'sc_gapdefault' => $dc->activeRecord->sc_gapdefault
							);

			if(in_array('GlobalContentelements',$this->Config->getActiveModules()))
		        {
			     $arrSet['do'] = $this->Input->get('do');
		        }

			$intDiff;
			
			if($intDiff>0)
			{
				
				/* Andere Daten im Colset anpassen - Spaltenabstand und SpaltenSet-Typ */				
				for($i=1;$i<$intDiff;$i++)
				{
					++$intChildId;
					++$intFscSortId;
					$intSorting += 64;
					$arrSet['sc_name'] = $dc->activeRecord->sc_name.'-Part-'.($intChildId);
					$arrSet['sc_sortid'] = $intFscSortId;
					$arrSet['sorting'] = $intSorting;
					
					$objInsertElement = $this->Database->prepare("INSERT INTO tl_content %s")
											->set($arrSet)
											->execute();
					
					$insertElement = $objInsertElement->insertId;
			
					$arrChilds[] = $insertElement;
					
				}
				
				
			}
			
			/* Andere Daten im Colset anpassen - Spaltenabstand und SpaltenSet-Typ */
			$arrData = array('sc_type' => $dc->activeRecord->sc_type,
							'sc_gap' => $dc->activeRecord->sc_gap,
							'sc_gapdefault' => $dc->activeRecord->sc_gapdefault
							);
			
			foreach($arrChilds as $value)
			{
			
				$this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set($arrData)
											->execute($value);
			
			}
			
			/* Neues End-element erzeugen */
			$arrSet['sorting'] = $intSorting + 64;
			$arrSet['type'] = 'colsetEnd';
			$arrSet['sc_name'] = $dc->activeRecord->sc_name.'-End';
			$arrSet['sc_sortid'] = ++$intFscSortId;
			
			$insertElement = $this->Database->prepare("INSERT INTO tl_content %s")
											->set($arrSet)
											->execute()
											->insertId;
			
			$arrChilds[] = $insertElement;
			
			/* Kindelemente in Startelement schreiben */
			$insertElement = $this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set(array('sc_childs'=>$arrChilds))
											->execute($dc->id);
			
			return true;
			
		}
		
		
		
	}
	
	/**
	 * Write the other Sets
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function setElementProperties(DC_Table $dc)
	{
	
		if($dc->activeRecord->type != 'colsetStart' || $dc->activeRecord->sc_type == "") return '';
	
		$objEnd = $this->Database->prepare("SELECT sorting FROM tl_content WHERE sc_name=?")->execute($dc->activeRecord->sc_name . '-End');
		
		$arrSet = array(
			'protected' => $dc->activeRecord->protected,
			'groups' => $dc->activeRecord->groups,
			'guests' => $dc->activeRecord->guests
		);
		
		$this->Database->prepare("UPDATE tl_content %s WHERE pid=? AND sorting > ? AND sorting <= ?")->set($arrSet)->execute($dc->activeRecord->pid,$dc->activeRecord->sorting,$objEnd->sorting);
		
	
	}
	
	public function scDelete(DC_Table $dc)
	{
		
		$delRecord = $this->Database->prepare("SELECT * FROM tl_content WHERE id=?")
												->execute($dc->id)
												->fetchAssoc();
		
		
		if($delRecord['type'] == 'colsetStart' || $delRecord['type'] == 'colsetPart' || $delRecord['type'] == 'colsetEnd')
		{
			
			/**
			 * Wird ein Startelement gelöscht, werden alle Kindelemente in ein Array geschrieben 
			 * und ebenfalls gelöscht
			 */
			if($delRecord['type'] == 'colsetStart') $eraseArray = $delRecord['sc_childs'] != "" ? unserialize($delRecord['sc_childs']) : array();
			
			/**
			 * Wird ein Teiler oder das Endelement gelöscht
			 */
			if($delRecord['type'] == 'colsetPart' || $delRecord['type'] == 'colsetEnd')
			{
				$parent = $this->Database->prepare("SELECT sc_childs FROM tl_content WHERE id=?")
										  ->execute($delRecord['sc_parent'])
										  ->fetchAssoc();
				$childs = $parent['sc_childs'] != "" ? unserialize($parent['sc_childs']) : array();
				
				$eraseArray[] = $delRecord['sc_parent'];
				
				foreach($childs as $wert)
				{
					if($wert != $delRecord['id']) $eraseArray[] = $wert;
				}
			}
			
			if(count($eraseArray) > 0)
			{
								
				for($i = 0;$i < count($eraseArray); $i++)
				{
					$this->Database->prepare("DELETE FROM tl_content WHERE id=?")
										  ->execute($eraseArray[$i]);
				}
				
			}
			
		}
		
	}
	
	private function moveRows($pid,$sorting,$ammount=128)
	{
		$this->Database->prepare("UPDATE tl_content SET sorting = sorting + ? WHERE pid=? AND sorting > ?")
									->execute($ammount,$pid,$sorting);
		
		
	}
	
	/* Bearbeiten-Icon für Trenn- und Endelemente ausblenden */
	public function showEditOperation($arrRow, $href, $label, $title, $icon, $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext)
	{
		
		#return '<a href="typolight/main.php?do=form&table=tl_form_field&act=paste&mode=copy&id=7" title="Das Feld ID 7 duplizieren" onclick="Backend.getScrollOffset();"><img src="system/themes/default/images/copy.gif" width="14" height="16" alt="Feld duplizieren" /></a>';
		if($arrRow['type'] != 'colsetPart' && $arrRow['type'] != 'colsetEnd')
		{
			$href .= '&id='.$arrRow['id'];
			return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		}
	
	}
	
	/* Kopier-Icon für Trenn- und Endelemente ausblenden */
	public function showCopyOperation($arrRow, $href, $label, $title, $icon, $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext)
	{
	
		if($arrRow['type'] != 'colsetPart' && $arrRow['type'] != 'colsetEnd')
		{
			$href .= '&id='.$arrRow['id'];
			return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		}
	
	}
	
	/* Kopier-Icon für Trenn- und Endelemente ausblenden */
	public function showDeleteOperation($arrRow, $href, $label, $title, $icon, $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext)
	{
	
		if($arrRow['type'] != 'colsetPart' && $arrRow['type'] != 'colsetEnd')
		{
			$href .= '&id='.$arrRow['id'];
			return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		}
	
	}
	
	/* Kopier-Icon für Trenn- und Endelemente ausblenden */
	public function toggleIcons($arrRow, $href, $label, $title, $icon, $attributes, $strTable, $arrRootIds, $arrChildRecordIds, $blnCircularReference, $strPrevious, $strNext)
	{
	
		if($arrRow['type'] != 'colsetPart' && $arrRow['type'] != 'colsetEnd')
		{
			return parent::toggleIcon($arrRow, $href, $label, $title, $icon, $attributes);
		}
	
	}
	
	/* Toggle-Status auf Trenn und End-elemente anwenden */
	public function toggleAdditionalElements($varValue,$dc)
	{
		$objEntry = $this->Database->prepare("UPDATE tl_content SET tstamp=". time() .", invisible='" . ( $varValue ? 1 : '') . "' WHERE sc_parent=? AND type!=?")->execute($dc->id,'colsetStart');
		
		return $varValue;
	
	}
	
	public function scCopy($intId,DataContainer $dc)
	{
		
		if($this->Input->get('act') == 'copy')
		{
			if($objActiveRecord->type == 'colsetStart')
			{
				$this->Database->prepare("UPDATE tl_content %s WHERE id=?")
							->set(array('sc_parent'=>'','sc_childs'=>''))
							->execute($intId);
			}
		}
		
		if($this->Input->get('act') == 'copyAll')
		{
			
			$objActiveRecord = $this->Database->prepare("SELECT * FROM tl_content WHERE id=?")->execute($intId);
			
			if($objActiveRecord->type != 'colsetStart' && $objActiveRecord->type != 'colsetPart' && $objActiveRecord->type != 'colsetEnd')
			{	
				$this->Database->prepare("UPDATE tl_content %s WHERE id=?")->set(array('sc_name'=>''))->execute($intId);
			}
			
			// Startelement mit aktuellen Daten besetzen und Session mit alten Daten füllen
			if($objActiveRecord->type == 'colsetStart')
			{
				
				$arrSession = array(
					'parentId' 	=> $intId,
					'count'		=> 1,
					'childs'	=> array()
				);
				
				$this->Session->set('sc'.$objActiveRecord->sc_parent,$arrSession);
				
				$arrSet = array(
					'sc_name'	=> 'colset.' . $intId,
					'sc_parent' => $intId
				);
				
				$this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set($arrSet)
											->execute($intId);
			}
			
			if($objActiveRecord->type == 'colsetPart')
			{
				$arrSession = $this->Session->get('sc'.$objActiveRecord->sc_parent);
				
				$intNewParent = $arrSession['parentId'];
				$intCount = $arrSession['count'];
				$arrChilds = $arrSession['childs'];
				
				
				$arrSet = array(
					'sc_name'	=> 'colset.' . $intNewParent . '-Part-' . $intCount,
					'sc_parent' => $intNewParent
				);
				
				$this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set($arrSet)
											->execute($intId);
				
				$arrChilds[] = $intId;
				
				$arrSession['count'] = ++$intCount;
				$arrSession['childs'] = $arrChilds;
				
				$this->Session->set('sc'.$objActiveRecord->sc_parent,$arrSession);
			
			}
			
			if($objActiveRecord->type == 'colsetEnd')
			{
				
				$arrSession = $this->Session->get('sc'.$objActiveRecord->sc_parent);
				
				$intNewParent = $arrSession['parentId'];
				$intCount = $arrSession['count'];
				$arrChilds = $arrSession['childs'];
				
				$arrSet = array(
					'sc_name'	=> 'colset.' . $intNewParent . '-End',
					'sc_parent' => $intNewParent
				);
				
				$this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set($arrSet)
											->execute($intId);
				
				$arrChilds[] = $intId;
				
				$arrSet = array(
					'sc_childs' => $arrChilds
				);
				
				$this->Database->prepare("UPDATE tl_content %s WHERE id=?")
											->set($arrSet)
											->execute($intNewParent);
				
				
			
			}
			
			
			
		}
	}
}
?>