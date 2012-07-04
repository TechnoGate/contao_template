<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * @copyright  certo web & design GmbH 2010 - 2011 
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch> 
 * @package    videobox 
 * @license    LGPL 
 * @filesource
 */


/**
 * Class YouTube 
 *
 * @copyright  certo web & design GmbH 2010 - 2011 
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch> 
 * @package    Controller
 */
class YouTube extends Frontend
{
	/**
	 * Youtube URL
	 * @var string
	 */
	public $strYoutubeUrl = '';
	
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
		// set template
		$this->strTemplate = (strlen($arrDBData['youtube_template'])) ? $arrDBData['youtube_template'] : 'videobox_youtube';
		
		// pass on the database row unchanged
		$this->arrData['dbRow'] = $arrDBData;
		
		$this->arrData['id'] = 'video_' . $arrDBData['videoid'] . '_' . md5(uniqid(mt_rand(), true));
		$this->arrData['timestamp'] = $arrDBData['tstamp'];
		$this->arrData['video_title'] = $arrDBData['videotitle'];
		$this->arrData['archive_title'] = $arrDBData['title'];
		
		// size
		if(!strlen($arrDBData['youtube_size']))
		{
			$arrSize = array(425,344);
		}
		else
		{
			$arrSize = deserialize($arrDBData['youtube_size']);
		}
		
		$this->arrData['width'] = $arrSize[0];
		$this->arrData['height'] = $arrSize[1];
		
		// Youtube url...what a long chain again...copy&paste to the fullest!
		$arrUrlData = array();

		// rel
		if ($arrDBData['youtube_rel'])
		{
			$arrUrlData['rel'] = 1;
		}
		else
		{
			$arrUrlData['rel'] = 0;
		}

		// autoplay
		if ($arrDBData['youtube_autoplay'] && TL_MODE == 'FE')
			$arrUrlData['autoplay'] = 1;

		// loop
		if ($arrDBData['youtube_loop'])
			$arrUrlData['loop'] = 1;

		// border
		if ($arrDBData['youtube_border'])
			$arrUrlData['border'] = 1;

		// color1
		if ($arrDBData['youtube_color1'])
			$arrUrlData['color1'] = '0x' . $arrDBData['youtube_color1'];
		
		// color2
		if ($arrDBData['youtube_color2'])
			$arrUrlData['color2'] = '0x' . $arrDBData['youtube_color2'];

		// start
		if ($arrDBData['youtube_start'])
			$arrUrlData['start'] = '0x' . $arrDBData['youtube_start'];
		
		// fullscreen
		if ($arrDBData['youtube_fs'])
			$arrUrlData['fs'] = 1;

		// hd
		if ($arrDBData['youtube_hd'])
			$arrUrlData['hd'] = 1;

		// showinfo
		if ($arrDBData['youtube_showinfo'])
			$arrUrlData['showinfo'] = 1;	
		
		
		$this->arrData['urlParams']		= $arrUrlData;
		$this->arrData['youtubelink']	= 'http://www.youtube.com/embed/' . $arrDBData['youtube_id'] . self::generateQueryString($arrUrlData);

		// usability
		$this->arrData['noscript'] = specialchars(sprintf($GLOBALS['TL_LANG']['VideoBox']['youtube_noscript'], $arrDBData['videotitle']));
		$this->arrData['noflash'] = specialchars(sprintf($GLOBALS['TL_LANG']['VideoBox']['youtube_noflash'], $arrDBData['videotitle']));

		// Template
		$objTemplate = new FrontendTemplate($this->strTemplate);
		$objTemplate->setData($this->arrData);
		return $objTemplate->parse();
	}


	/**
	 * Generate youtube query string
	 * @param array
	 * @return string
	 */
	public static function generateQueryString($arrData)
	{
		$total = count($arrData);
		
		if ($total < 1)
		{
			return '';
		}
		
		$strQuery = '';
		$i = 0;
		
		foreach($arrData as $param => $value)
		{
			$strQuery .= (($i == 0) ? '?' : '&') . $param . '=' . $value;
			$i++;
		}
		
		// encode entities because the url is being used in html
		return specialchars($strQuery);
	}
}