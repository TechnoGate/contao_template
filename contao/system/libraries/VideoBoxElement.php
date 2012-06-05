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
 * Class VideoBoxElement 
 *
 * @copyright  certo web & design GmbH 2010 - 2011 
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch> 
 * @package    Controller
 */
class VideoBoxElement extends System
{
    /**
     * Data table
     * @var string 
     */
	public $strTable = '';
	
	 /**
     * Video type
     * @var string 
     */
	public $strVideoType = '';
	 
    /**
     * Data array
     * @var array 
     */
    public $arrData = array();
	
    /**
     * Video object
     * @var object 
     */
    public $objVideo;
	
    /**
     * Initialize the object
     * @param mixed either the video ID or its alias 
     * @param string 
     * @param string 
     */
    public function __construct($varId, $strTable='tl_videobox', $strVideoType='')
    {
		// set vars
		$this->strTable = $strTable;
		$this->strVideoType = $strVideoType;
		
		$this->import('Database');
		
		// SQL - provide data for other tables too
		if($this->strTable == 'tl_videobox')
		{
			$strSQL =	'SELECT
							v.*,a.*,s.*,v.id as videoid
						FROM
						(
							tl_videobox v 
						LEFT JOIN
							tl_videobox_archive a
						ON
							v.pid = a.id
						)
						LEFT JOIN
							tl_videobox_settings s
						ON
							a.id = s.pid
						WHERE
							v.id=? OR v.alias=?';
		}
		else
		{
			$strSQL = 'SELECT * FROM ' . $this->strTable . ' WHERE id=? OR alias=?';
		}		
		
        // get data
        $objData = $this->Database->prepare($strSQL)
                                        ->limit(1)
                                        ->execute($varId, $varId);
                                        
        if (!$objData->numRows)
        {
            throw new Exception('The video with id or alias "' . $varId . '" does not exist!');
        }
        
		// set data
		$this->arrData = $objData->fetchAssoc();
		
		// set videotype
		if(!$this->strVideoType)
		{
			$this->strVideoType = $this->arrData['videotype'];
		}
    }


    /**
     * Get the data
     * @return array
     */
    public function getData()
    {
        return $this->arrData;
    }
    
    
    /**
     * Access the video data
     * @param string key
     * @return mixed
     */
    public function __get($strKey)
    {
        return (isset($this->arrData[$strKey])) ? $this->arrData[$strKey] : null;
    }
	
    
	/**
     * Return the video object as a string
     * @return string 
     */
 	public function generate()
	{
        // HOOK: processVideoData
        if(isset($GLOBALS['VIDEOBOX']['VideoType']) && is_array($GLOBALS['VIDEOBOX']['VideoType']) && array_key_exists($this->strVideoType, $GLOBALS['VIDEOBOX']['VideoType']))
        {
                $this->import($GLOBALS['VIDEOBOX']['VideoType'][$this->strVideoType][0]);
                return $this->$GLOBALS['VIDEOBOX']['VideoType'][$this->strVideoType][0]->$GLOBALS['VIDEOBOX']['VideoType'][$this->strVideoType][1]($this->arrData);
        }
        
        // other than that, there's no videobox hook for this type!
        throw new Exception('There is no valid video type hook for the video type "' . $this->strVideoType . '"!');
	}
}