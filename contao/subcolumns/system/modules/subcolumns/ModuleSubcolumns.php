<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Felix Pfeiffer : Neue Medien 2010
 * @author     Felix Pfeiffer <info@felixpfeiffer.com>
 * @package    Subcolumns
 * @license    CC-A 2.0 
 * @filesource
 */


/**
 * Class ModuleSubcolumns 
 *
 * @copyright  Felix Pfeiffer : Neue Medien 2010
 * @author     Felix Pfeiffer <info@felixpfeiffer.com>
 * @package    Subcolumns
 */
class ModuleSubcolumns extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_subcolumns';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### MODULE SUBCOLUMNS ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		
		/**
		 * CSS Code in das Pagelayout einfügen
		 */
		$mainCSS = $GLOBALS['TL_CONFIG']['subcolumns_altcss'] ? 'system/modules/subcolumns/html/subcols_extended.css' : 'system/modules/subcolumns/html/subcols.css';
		$IEHacksCSS = $GLOBALS['TL_CONFIG']['subcolumns_altcss'] ? 'system/modules/subcolumns/html/subcolsIEHacks_extended.css' : 'system/modules/subcolumns/html/subcolsIEHacks.css';
		$GLOBALS['TL_CSS']['subcolumns'] = $mainCSS;
		$GLOBALS['TL_HEAD']['subcolumns'] = '<!--[if lte IE 7]><link href="'.$IEHacksCSS.'" rel="stylesheet" type="text/css" /><![endif]--> ';
		
		$arrSet = $GLOBALS['TL_SUBCL'][$this->sc_type];
		
		$arrColumns = unserialize($this->sc_modules);
		
		if($this->sc_gapdefault == 1)
		{
			$gap_value = $this->sc_gap != "" ? $this->sc_gap : '12';
			$gap_unit = 'px';
			
			if(count($arrSet) == 2)
			{
				$arrSet[0][] = array('right'=>ceil(0.5*$gap_value).$gap_unit);
				$arrSet[1][] = array('left'=>floor(0.5*$gap_value).$gap_unit);
			}
			elseif (count($arrSet) == 3)
			{
				$arrSet[0][] = array('right'=>ceil(0.666*$gap_value).$gap_unit);
				$arrSet[1][] = array('right'=>floor(0.333*$gap_value).$gap_unit,'left'=>floor(0.333*$gap_value).$gap_unit);
				$arrSet[2][] = array('left'=>ceil(0.666*$gap_value).$gap_unit);
			}
			elseif (count($arrSet) == 4)
			{
				$arrSet[0][] = array('right'=>ceil(0.75*$gap_value).$gap_unit);
				$arrSet[1][] = array('right'=>floor(0.5*$gap_value).$gap_unit,'left'=>floor(0.25*$gap_value).$gap_unit);
				$arrSet[2][] = array('right'=>floor(0.25*$gap_value).$gap_unit,'left'=>ceil(0.5*$gap_value).$gap_unit);
				$arrSet[3][] = array('left'=>ceil(0.75*$gap_value).$gap_unit);
			}
			elseif (count($arrSet) == 5)
			{
				$arrSet[0][] = array('right'=>ceil(0.8*$gap_value).$gap_unit);
				$arrSet[1][] = array('right'=>floor(0.6*$gap_value).$gap_unit,'left'=>floor(0.2*$gap_value).$gap_unit);
				$arrSet[2][] = array('right'=>floor(0.4*$gap_value).$gap_unit,'left'=>ceil(0.4*$gap_value).$gap_unit);
				$arrSet[3][] = array('right'=>floor(0.2*$gap_value).$gap_unit,'left'=>ceil(0.6*$gap_value).$gap_unit);
				$arrSet[4][] = array('left'=>ceil(0.8*$gap_value).$gap_unit);
			}
		}
		
		foreach($arrColumns as $row)
		{
		
			$strMod = $this->getFrontendModule($row['mod']);
			
			switch($row['col'])
			{
				
				case 'first':
					$arrSet[0]['modules'][] = $strMod;
					break;
					
				case 'second':
					$arrSet[1]['modules'][] = $strMod;
					break;
					
				case 'third':
					$arrSet[2]['modules'][] = $strMod;
					break;
					
				case 'fourth':
					$arrSet[3]['modules'][] = $strMod;
					break;
					
				case 'fifth':
					$arrSet[4]['modules'][] = $strMod;
					break;
				
			}
		
		}
		#echo "<pre>"; print_r($arrSet); echo "</pre>";
		$this->Template->intCols = count($arrSet);
		$this->Template->arrSet = $arrSet;
		$this->Template->scclass = ($this->sc_equalize ? 'equalize ' : '') . 'subcolumns';
		#echo "<pre>"; print_r($arrSet); echo "</pre>";
		#echo "<pre>"; print_r($arrColumns); echo "</pre>";
		
	}
}

?>