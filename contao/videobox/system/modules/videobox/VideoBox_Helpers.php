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
 * Class VideoBox_Helpers 
 *
 * @copyright  certo web & design GmbH 2010 - 2011 
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch> 
 * @package    Controller
 */
 
class VideoBox_Helpers extends Controller
{
	/**
	 * Load database object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}
	
	
/***********************************************************************************************************************/
/* FRONTEND
/***********************************************************************************************************************/
	
	/**
	 * Compile InsertTags
	 * @param string
	 * @return mixed
	 */
	public function replaceVideoBoxInsertTags($strTag)
	{
		// {{VIDEOBOX_NEWS::CONTAINERID}} 
		if(strpos($strTag, 'VIDEOBOX_NEWS::') !== false)
		{
			$arrData = explode('::', $strTag);
			$strID = $arrData[1];
			
			$objNews = $this->Database->prepare("SELECT videobox_video FROM tl_news WHERE videobox_addvideo=? AND id=?")
									  ->execute(1,$strID);
			
			if($objNews->numRows < 1)
			{
				return '';
			}
			
            $objVideo = new VideoBoxElement((int) $objNews->videobox_video);
			return $objVideo->generate();
		}
		
		// {{VIDEOBOX_EVENTS::CONTAINERID}} 
		if(strpos($strTag, 'VIDEOBOX_EVENTS::') !== false)
		{
			$arrData = explode('::', $strTag);
			$strID = $arrData[1];
			
			$objNews = $this->Database->prepare("SELECT videobox_video FROM tl_calendar_events WHERE videobox_addvideo=? AND id=?")
									  ->execute(1,$strID);
			
			if($objNews->numRows < 1)
			{
				return '';
			}

            $objVideo = new VideoBoxElement((int) $objNews->videobox_video);
            return $objVideo->generate();
		}
		
		// {{VIDEOBOX_STANDALONE::VIDEOID}}
		if(strpos($strTag, 'VIDEOBOX_STANDALONE::') !== false)
		{
			$arrData = explode('::', $strTag);
			
            $objVideo = new VideoBoxElement((int) $arrData[1]);
            return $objVideo->generate();		
		}
        
		return false;
	}
	

	
	
	
/***********************************************************************************************************************/
/* BACKEND
/***********************************************************************************************************************/
	
	/**
	 * List all the videos in a dropdown (to choose from in the backend)
	 * @return array
	 */
	public function getVideos()
	{
		$this->import('BackendUser', 'User');

		$groups = array();
	
		$objVideos = $this->Database->execute("SELECT v.id AS videoid, v.videotitle, a.title AS archivetitle, a.allowedUserGroups FROM tl_videobox v LEFT JOIN tl_videobox_archive a ON (a.id = v.pid) ORDER BY a.title");

		// build groups
		
 		while($objVideos->next())
		{
			// show everything to admins
			if($this->User->isAdmin)
			{
				$groups[$objVideos->archivetitle][$objVideos->videoid] = $objVideos->videotitle . ' [ID: ' . $objVideos->videoid . ']';
				continue;
			}
			
			// if there is no usergroup allowed at all (empty blob)
			if(strlen($objVideos->allowedUserGroups) == 0)
			{
				continue;
			}
			
			// check whether the user is allowed to see the video
			if(count(array_intersect($this->User->groups, deserialize($objVideos->allowedUserGroups))))
			{
				$groups[$objVideos->archivetitle][$objVideos->videoid] = $objVideos->videotitle . ' [ID: ' . $objVideos->videoid . ']';
			}
		}
		return $groups; 
	}

	
	/**
	 * Compile InsertTags
	 * @param object
	 * @param string
	 * @param array
	 */
	public function linkToSettings($dc, $strTable, $arrModule)
	{

		// check wheter there has already been created a settings entry
		$objCheck = $this->Database->prepare("SELECT id FROM tl_videobox_settings WHERE pid=?")
								   ->execute($dc->id);
		
		// no entry yet - redirect to the create page
		if($objCheck->numRows < 1)
		{
			$this->redirect('contao/main.php?do=videobox&table=tl_videobox_settings&act=create&mode=2&pid=' . $dc->id);
		}

		// else redirect to the existing entry 
		$this->redirect('contao/main.php?do=videobox&table=tl_videobox_settings&act=edit&id=' . $objCheck->id);
		
	}
    
    
    /**
     * Prepare video template data
     * @param int video id
     * @param int jumpTo page
     * @return array
     */
    public function prepareVideoTemplateData($intVideoId, $intJumpTo=false)
    {
        $arrReturn = array();
        $objVideo = new VideoBoxElement($intVideoId);
        $arrReturn['video'] = $objVideo->generate();
        $arrReturn['videoData'] = $objVideo->getData();
        $arrReturn['title'] = $objVideo->videotitle;
        
        if ($intJumpTo)
        {
            // jumpTo gets cached automatically
            $objJumpTo = $this->Database->prepare('SELECT id,alias FROM tl_page WHERE id=?')->execute($intJumpTo);
            $arrReturn['href']  = ampersand($this->generateFrontendUrl($objJumpTo->row(), '/video/' . ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objVideo->alias != '') ? $objVideo->alias : $objVideo->videoid)));
        }

        // thumb
        if ($objVideo->thumb)
        {
            $objImgData = new stdClass();
            $arrItem = array_merge($arrReturn['videoData'], array
            (
                'singleSRC' => $objVideo->thumb
            ));
            
            $this->addImageToTemplate($objImgData, $arrItem);
            $arrReturn['imgData'] = $objImgData;
        }
        
        return $arrReturn;
    }
}