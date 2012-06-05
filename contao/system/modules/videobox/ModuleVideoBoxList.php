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
 * Class ModuleVideoBoxList 
 *
 * @copyright  certo web & design GmbH 2010 - 2011 
 * @author     Yanick Witschi <yanick.witschi@certo-net.ch> 
 * @package    Controller
 */
class ModuleVideoBoxList extends Module
{
    
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_videobox_list';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### VIDEOBOX LIST ###';

			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
        
        // overwrite the module template
        if ($this->videobox_tpl_list)
        {
            $this->strTemplate = $this->videobox_tpl_list;
        }

		return parent::generate();
	}
	
	
    /**
     * Generate the module
     */
	protected function compile()
	{
        $arrArchives = deserialize($this->videobox_archives, true);
        
        if (empty($arrArchives))
        {
            return '';
        }
        
        // basic template variables
        $this->Template->hasVideos = true;
        
        // prepare the sql
        $strSQL = '';
        if ($this->videobox_sql)
        {
            $strSQL = ' ' . trim($this->videobox_sql);
        }
        
        $intTotal = (int) $this->Database->query('SELECT COUNT(id) AS total FROM tl_videobox WHERE pid IN (' . implode(',', $arrArchives) . ')' . $strSQL)->total;
        
        if ($intTotal == 0)
        {
            $this->Template->hasVideos = false;
            $this->Template->msg = $GLOBALS['TL_LANG']['VideoBox']['no_videos'];
            return;
        }
        
        $limit = $intTotal;
        $offset = 0;

        // Pagination
        if ($this->perPage > 0)
        {
            $page = $this->Input->get('page') ? $this->Input->get('page') : 1;
            $offset = ($page - 1) * $this->perPage;
            $limit = min($this->perPage + $offset, $intTotal);

            $objPagination = new Pagination($intTotal, $this->perPage);
            $this->Template->pagination = $objPagination->generate("\n  ");
        }

        // videobox statement
        $objVideosStmt = $this->Database->prepare('SELECT id FROM tl_videobox WHERE pid IN (' . implode(',', $arrArchives) . ')' . $strSQL);

        // Limit the result
        if (isset($limit))
        {
            $objVideosStmt->limit($limit, $offset);
        }
        
        $objVideos = $objVideosStmt->execute();
        $arrVideos = array();
        $count = 0;
        $this->import('VideoBox_Helpers', 'VBHelper');
        
        while ($objVideos->next())
        {
            $arrVideoData = $this->VBHelper->prepareVideoTemplateData($objVideos->id, $this->videobox_jumpTo);
            $arrVideos[$objVideos->id] = array_merge($arrVideoData, array
            (
                'count'    => ++$count,
                'cssClass' => (($count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even')
                
            ));
        }
        
        $this->Template->videos = $arrVideos;
	}
}