<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Yanick Witschi 2010 
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch> 
 * @package    videobox 
 * @license    LGPL 
 * @filesource
 */


/**
 * Class Vimeo 
 *
 * @copyright  David Enke 2010-2012
 * @author     David Enke <post@davidenke.de> 
 * @package    Controller
 */
class Vimeo extends Frontend
{
	/**
	 * Youtube URL
	 * @var string
	 */
	public $strVimeoUrl = '';	
	
	
	/**
	 * Youtube URL
	 * @var string
	 */
	public $strTemplate = '';
	
	/**
	 * Data array
	 * @var array
	 */
	public $arrData = array();
	
	/**
	 * Parse the array data and prepare for the Youtube video
	 * @param array
	 * @return array
	 */
	public function parseVideo($arrDBData)
	{
		$this->import('String');
		
		// set template
		$this->strTemplate = (strlen($arrDBData['vimeo_template'])) ? $arrDBData['vimeo_template'] : 'videobox_vimeo';
		
		$this->arrData['id'] = 'video_' . md5(uniqid(mt_rand(), true));
		$this->arrData['timestamp'] = $arrDBData['tstamp'];
		$this->arrData['video_title'] = $arrDBData['videotitle'];
		$this->arrData['archive_title'] = $arrDBData['title'];
		
		// size
		if(!strlen($arrDBData['vimeo_size']))
		{
			$arrSize = array(425,344);
		}
		else
		{
			$arrSize = deserialize($arrDBData['vimeo_size']);
		}
		
		$this->arrData['width'] = $arrSize[0];
		$this->arrData['height'] = $arrSize[1];
		$this->arrData['autoplay'] = (strlen($arrDBData['vimeo_autoplay']) && TL_MODE == 'FE') ? true : false;
		$this->arrData['color'] = (strlen($arrDBData['vimeo_color'])) ? $arrDBData['vimeo_color'] : 'F7FFFD';
        $this->arrData['showbyline'] = (strlen($arrDBData['vimeo_showbyline'])) ? true : false;
        $this->arrData['showtitle'] = (strlen($arrDBData['vimeo_showtitle'])) ? true : false;
        $this->arrData['showportrait'] = (strlen($arrDBData['vimeo_showportrait'])) ? true : false;

		$this->arrData['params'] = (strlen($arrDBData['vimeo_autoplay']) && TL_MODE == 'FE') ? 'autoplay=1' : 'autoplay=0';
		$this->arrData['params'].= (strlen($arrDBData['vimeo_color'])) ? '&color=' . $arrDBData['vimeo_color'] : 'ffffff';
		$this->arrData['params'].= (strlen($arrDBData['vimeo_showbyline'])) ? '&byline=1' : '&byline=0';
		$this->arrData['params'].= (strlen($arrDBData['vimeo_showtitle'])) ? '&title=1' : '&title=0';
		$this->arrData['params'].= (strlen($arrDBData['vimeo_showportrait'])) ? '&portrait=1' : '&portrait=0';

		$this->strVimeoUrl = 'http://www.vimeo.com/moogaloop.swf?clip_id=' . $arrDBData['vimeo_id'] . '&server=www.vimeo.com&';
		$this->strVimeoUrl.= $this->arrData['params'];
		
		$this->arrData['vimeolink'] = $this->strVimeoUrl;
		$this->arrData['vimeoid'] = $arrDBData['vimeo_id'];

		// usability, useless as vimeo supports html5
		$this->arrData['noscript'] = $this->String->decodeEntities(sprintf($GLOBALS['TL_LANG']['VideoBox']['vimeo_noscript'], $arrDBData['videotitle']));
		$this->arrData['noflash'] = $this->String->decodeEntities(sprintf($GLOBALS['TL_LANG']['VideoBox']['vimeo_noflash'], $arrDBData['videotitle']));

		// Template
		$objTemplate = new FrontendTemplate($this->strTemplate);
		$objTemplate->setData($this->arrData);
		return $objTemplate->parse();
	}
}

?>