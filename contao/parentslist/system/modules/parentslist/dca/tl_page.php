<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * PHP version 5
 * @copyright  Jan Theofel 2011, Tristan Lins 2011, ETES GmbH 2010
 * @author     Jan Theofel <jan@theofel.de>, Tristan Lins <tristan.lins@infinitysoft.de>, Sebastian Leitz <sebastian.leitz@etes.de>
 * @package    parentslist
 * @license    LGPL
 * @filesource
 */


/**
 * Table tl_page
 */
$GLOBALS['TL_DCA']['tl_page']['config']['onsubmit_callback'][] = array('Parentslist', 'updateParentsList');

$GLOBALS['TL_DCA']['tl_page']['config']['oncut_callback'][] = array('Parentslist', 'cutCallback');
$GLOBALS['TL_DCA']['tl_page']['config']['oncopy_callback'][] = array('Parentslist', 'copyCallback');
$GLOBALS['TL_DCA']['tl_page']['config']['onsubmit_callback'][] = array('Parentslist', 'submitCallback');

/**
 * Update the parent page tree
 */

class Parentslist extends Backend
{

	protected $strRootId;
	
	public function updateParentsList(DataContainer $dc)
	{
		// we start with pid, so add pid at the end
		$strParents = $this->getPid($dc->activeRecord->pid);
		
		$this->Database->prepare("UPDATE tl_page SET rootId=?, parents=? WHERE id=?")->execute($this->strRootId, $strParents, $dc->activeRecord->id);
	}

	private function cutNCopy($myid, DataContainer $dc)
	{	
		// get object related to which we insert the new object
		$objBigBrother = $this->Database->prepare("SELECT pid, rootId, parents FROM tl_page WHERE id=?")->limit(1)->execute($this->Input->get('pid'));

		// Insert as sibling of element with ID "pid"
		// Copy the parent list from that element because it is identical for our object
		if($this->Input->get('mode') == 1)
		{
			$newParents = $objBigBrother->parents;
		}

		// Insert as child of element with ID "pid"
		// Copy the partenst list from that element, add the parent itself and us this as parentslist for our object
		elseif($this->Input->get('mode') == 2)
		{
			$newParents = $this->Input->get('pid') . "," . $objBigBrother->parents;
		}

		// update childs after copy on next submit
		$updateChilds = $this->Input->get('childs')?1:0;

		// database update for this object
		$this->Database->prepare("UPDATE tl_page SET rootId=?, parents=?, updatechilds=? WHERE id=?")->execute($objBigBrother->rootId, $newParents, $updateChilds, $myid);

		// Update all childs in case of moving or copy with childs		
		if($this->Input->get('act') == 'cut')
		{
			$this->updateChildren($objBigBrother->id, $newParents, $objBigBrother->rootId);
			$this->updateChildren($dc->id, $newParents, $objBigBrother->rootId);
			
			// go through the parents of moved page and update the new childrens
			$arrParents = explode(',', $newParents);
			foreach ($arrParents as $intId)
			{
				$arrChildren = $this->getUncachedChildRecords($intId);
				$this->Database->prepare("UPDATE tl_page SET childrens=? WHERE id=?")->execute(implode(',', $arrChildren), $intId);
			}

			$this->removeChild($myid, $newParents);
		}
	}
	
	/**
	 * This caching is possible :-)
	 * I just habe to skip the contao caching, the getChildRecords does not support uncached :-(
	 */
	private static $arrUncachedChildRecords = array();
	
	private function getUncachedChildRecords($intId)
	{
		if (!isset(self::$arrUncachedChildRecords[$intId]))
		{
			$objChildRecords = $this->Database->prepare("SELECT id FROM tl_page WHERE pid=?")->executeUncached($intId);
			$arrChildIds = $objChildRecords->fetchEach('id');
			$arrFinal = $arrChildIds;
			
			foreach ($arrChildIds as $intChildId)
			{
				$arrFinal = array_merge($arrFinal, $this->getUncachedChildRecords($intChildId));
			}
			
			self::$arrUncachedChildRecords[$intId] = $arrFinal;
		}
		return self::$arrUncachedChildRecords[$intId];
	}

	// callback for "cut" case (called while moving page elements)
	public function cutCallback(DataContainer $dc)
	{
		$this->cutNCopy($dc->id, $dc);
	}

	// callback for "copy" case 
	public function copyCallback($insertId, DataContainer $dc)
	{
		$this->cutNCopy($insertId, $dc);
	}

	// callback for "submit" case - used to update possible children after a copy
	public function submitCallback(DataContainer $dc)
	{
		if($dc->activeRecord->updatechilds)
		{
			// reset flag
			$this->Database->prepare("UPDATE tl_page SET updatechilds=0 WHERE id=?")->execute($dc->id);

			// update childs
			$this->updateChildren($dc->activeRecord->id, $dc->activeRecord->parents, $dc->activeRecord->rootId);
		}
	}
	

	protected function getPid($id)
	{
		$pid = $this->Database->prepare("SELECT pid FROM tl_page WHERE id=?")->limit(1)->execute($id)->pid;
		
		if($pid == 0)
		{
			$this->strRootId = $id;
			return $id;
		}
		else
		{
			return $id . ',' . $this->getPid($pid);
		}
	}
	
	protected function updateChildren($strId, $strParents, $strRootId)
	{
		$strAllChildren = '';
		
		$objChildren = $this->Database->prepare("SELECT id from tl_page WHERE pid=?")->execute($strId);
		
		if($objChildren->numRows)
		{
			$arrChildren = $objChildren->fetchAllAssoc();
			
			foreach ($arrChildren as $children)
			{
				$strChildren = $this->updateChildren($children['id'], $strId . ',' . $strParents, $strRootId);
				$this->Database->prepare("UPDATE tl_page SET rootId=?, parents=?, childrens=? WHERE id=?")->execute($strRootId, $strId . ',' . $strParents, $strChildren, $children['id']);
				
				$strAllChildren .= ($strAllChildren ? ',' : '') . $children['id'] . ',' . $strChildren;
			}
		}
		
		return $strAllChildren;
	}
	
	protected function removeChild($strChildId, $strExpect)
	{
		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE FIND_IN_SET(?, childrens)>0 AND id NOT IN(" . $strExpect . ")")->execute($strChildId);
		while ($objPage->next())
		{
			$arrChildren = explode(',', $objPage->childrens);
			$intIndex = array_search($strChildId, $arrChildren);
			if ($intIndex !== false && $intIndex !== null)
			{
				unset($arrChildren[$intIndex]);
				$this->Database->prepare("UPDATE tl_page SET childrens=? WHERE id=?")->execute(implode(',', $arrChildren), $objPage->id);
			}
		}
	}
	
	public function runonce()
	{
		$objRootPages = $this->Database->execute("SELECT id FROM tl_page WHERE pid=0 AND (parents = '' OR parents IS NULL OR childrens = '' OR childrens IS NULL)");
		
		if($objRootPages->numRows)
		{
			$arrRootPages = $objRootPages->fetchAllAssoc();

			foreach ($arrRootPages as $rootPages)
			{
				$strChildren = $this->updateChildren($rootPages->id, $rootPages->id, 0);
				$this->Database->prepare("UPDATE tl_page SET childrens=? WHERE id=?")->execute($strChildren, $rootPages->id);
			}
		}
	}
	
}

?>