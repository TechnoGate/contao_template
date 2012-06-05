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
 * PHP version 5
 * @copyright  Felix Pfeiffer : Neue Medien 2010
 * @author     Felix Pfeiffer <info@felixpfeiffer.com>
 * @package    Subcolumns
 * @license    CC-A 2.0 
 */


/**
 * Class colsetStart 
 *
 * @copyright  Felix Pfeiffer : Neue Medien 2010
 * @author     Felix Pfeiffer <info@felixpfeiffer.com>
 * @package    Subcolumns
 */
class colsetStart extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_colsetStart';
	
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$this->Template = new BackendTemplate('be_wildcard');
			$this->Template->wildcard = '### Subcolumns Start <strong>'.$this->sc_name.'</strong> ### ' . sprintf($GLOBALS['TL_LANG']['MSC']['contentAfter'],$GLOBALS['TL_LANG']['MSC']['sc_first']);
			
			return $this->Template->parse();
		}

		return parent::generate();
	}
	
	/**
	 * Generate content element
	 * @return string
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
		
		$container = $GLOBALS['TL_SUBCL'][$this->sc_type];
		
		if($this->sc_gapdefault == 1)
		{
			$gap_value = $this->sc_gap != "" ? $this->sc_gap : '12';
			$gap_unit = 'px';
			
			if(count($container) == 2)
			{
				$this->Template->gap = array('right'=>ceil(0.5*$gap_value).$gap_unit);
			}
			elseif (count($container) == 3)
			{
				$this->Template->gap = array('right'=>ceil(0.666*$gap_value).$gap_unit);

			}
			elseif (count($container) == 4)
			{
				$this->Template->gap = array('right'=>ceil(0.75*$gap_value).$gap_unit);
			}
			elseif (count($container) == 5)
			{
				$this->Template->gap = array('right'=>ceil(0.8*$gap_value).$gap_unit);
			}
		}
		
		#$container = unserialize($this->sc_container);
		$this->Template->column = $container[0][0] . ' first';
		$this->Template->inside = $container[0][1];
		$this->Template->scclass = ($this->sc_equalize ? 'equalize ' : '') . 'subcolumns';
		
	}
}

?>