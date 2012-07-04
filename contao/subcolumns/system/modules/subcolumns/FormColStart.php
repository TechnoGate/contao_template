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
 * Class FormColStart
 *
 * Form field "explanation".
 * @copyright  Felix Pfeiffer : Neue Medien 2010
 * @author     Felix Pfeiffer <info@felixpfeiffer.com>
 * @package    Subcolumns
 */
class FormColStart extends Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_colset';
	protected $strColTemplate = 'ce_colsetStart';


	/**
	 * Do not validate
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
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### Subcolumns Start <strong>'.$this->fsc_name.'</strong> ### ' . sprintf($GLOBALS['TL_LANG']['MSC']['contentAfter'],$GLOBALS['TL_LANG']['MSC']['sc_first']);
			
			return $objTemplate->parse();
		}
		
		/**
		 * CSS Code in das Pagelayout einfügen
		 */
		$mainCSS = $GLOBALS['TL_CONFIG']['subcolumns_altcss'] ? 'system/modules/subcolumns/html/subcols_extended.css' : 'system/modules/subcolumns/html/subcols.css';
		$IEHacksCSS = $GLOBALS['TL_CONFIG']['subcolumns_altcss'] ? 'system/modules/subcolumns/html/subcolsIEHacks_extended.css' : 'system/modules/subcolumns/html/subcolsIEHacks.css';
		$GLOBALS['TL_CSS']['subcolumns'] = $mainCSS;
		$GLOBALS['TL_HEAD']['subcolumns'] = '<!--[if lte IE 7]><link href="'.$IEHacksCSS.'" rel="stylesheet" type="text/css" /><![endif]--> ';
		
		$container = $GLOBALS['TL_SUBCL'][$this->fsc_type];
		
		$objTemplate = new FrontendTemplate($this->strColTemplate);
		
		if($this->fsc_gapuse == 1)
		{
			$gap_value = $this->fsc_gap != "" ? $this->fsc_gap : '12';
			$gap_unit = 'px';
			
			if(count($container) == 2)
			{
				$objTemplate->gap = array('right'=>ceil(0.5*$gap_value).$gap_unit);
			}
			elseif (count($container) == 3)
			{
				$objTemplate->gap = array('right'=>ceil(0.666*$gap_value).$gap_unit);

			}
			elseif (count($container) == 4)
			{
				$objTemplate->gap = array('right'=>ceil(0.75*$gap_value).$gap_unit);
			}
			elseif (count($container) == 5)
			{
				$objTemplate->gap = array('right'=>ceil(0.8*$gap_value).$gap_unit);
			}
		}
		
		$objTemplate->column = $container[0][0] . ' first';
		$objTemplate->inside = $container[0][1];
		#$objTemplate->gap = $container[2];
		$objTemplate->scclass = ($this->fsc_equalize ? 'equalize ' : '') . 'subcolumns';
		$objTemplate->cssID = ' id="formcolset_' . $this->id . '"';
		return $objTemplate->parse();
	}
}

?>