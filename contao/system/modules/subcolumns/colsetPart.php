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
 * Class colsetPart 
 *
 * @copyright  Felix Pfeiffer : Neue Medien 2010
 * @author     Felix Pfeiffer <info@felixpfeiffer.com>
 * @package    Subcolumns
 */
class colsetPart extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_colsetPart';
	
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			
			switch($this->sc_sortid)
			{
				case 1:
					$colID = $GLOBALS['TL_LANG']['MSC']['sc_second'];
					break;
				case 2:
					$colID = $GLOBALS['TL_LANG']['MSC']['sc_third'];
					break;
				case 3:
					$colID = $GLOBALS['TL_LANG']['MSC']['sc_fourth'];
					break;
				case 4:
					$colID = $GLOBALS['TL_LANG']['MSC']['sc_fifth'];
					break;
			}
			
			$this->Template = new BackendTemplate('be_wildcard');
			$this->Template->wildcard = '### Subcolumns Part <strong>'.$this->sc_name.'</strong> ### ' . sprintf($GLOBALS['TL_LANG']['MSC']['contentAfter'],$colID);
			
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
		$arrCounts = array('1'=>'second','2'=>'third','3'=>'fourth','4'=>'fifth');
		$container = $GLOBALS['TL_SUBCL'][$this->sc_type];
		
		if($this->sc_gapdefault == 1)
		{
			$gap_value = $this->sc_gap != "" ? $this->sc_gap : '12';
			$gap_unit = 'px';
			
			if(count($container) == 2)
			{
				$this->Template->gap = array('left'=>floor(0.5*$gap_value).$gap_unit);
			}
			elseif (count($container) == 3)
			{
				switch($this->sc_sortid)
				{
					case 1:
						$this->Template->gap = array('right'=>floor(0.333*$gap_value).$gap_unit,'left'=>floor(0.333*$gap_value).$gap_unit);
						break;
					case 2:
						$this->Template->gap = array('left'=>ceil(0.666*$gap_value).$gap_unit);
						break;
				}
			}
			elseif (count($container) == 4)
			{
				switch($this->sc_sortid)
				{
					case 1:
						$this->Template->gap = array('right'=>floor(0.5*$gap_value).$gap_unit,'left'=>floor(0.25*$gap_value).$gap_unit);
						break;
					case 2:
						$this->Template->gap = array('right'=>floor(0.25*$gap_value).$gap_unit,'left'=>ceil(0.5*$gap_value).$gap_unit);
						break;
					case 3:
						$this->Template->gap = array('left'=>ceil(0.75*$gap_value).$gap_unit);
						break;
				}
			}
			elseif (count($container) == 5)
			{
				switch($this->sc_sortid)
				{
					case 1:
						$this->Template->gap = array('right'=>floor(0.6*$gap_value).$gap_unit,'left'=>floor(0.2*$gap_value).$gap_unit);
						break;
					case 2:
						$this->Template->gap = array('right'=>floor(0.4*$gap_value).$gap_unit,'left'=>ceil(0.4*$gap_value).$gap_unit);
						break;
					case 3:
						$this->Template->gap = array('right'=>floor(0.2*$gap_value).$gap_unit,'left'=>ceil(0.6*$gap_value).$gap_unit);
						break;
					case 4:
						$this->Template->gap = array('left'=>ceil(0.8*$gap_value).$gap_unit);
						break;
				}
			}
		}
		
		#$container = unserialize($this->sc_container);
		$this->Template->column = $container[$this->sc_sortid][0] . ' ' . $arrCounts[$this->fsc_sortid];
		$this->Template->inside = $container[$this->sc_sortid][1];
		$this->Template->scclass = ($this->sc_equalize ? 'equalize ' : '') . 'subcolumns';
	}
}

?>