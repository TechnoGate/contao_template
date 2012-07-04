<?php

/**
 * PHP version 5
 * @copyright  Jan Theofel 2011, Tristan Lins 2011, ETES GmbH 2010
 * @author     Jan Theofel <jan@theofel.de>, Tristan Lins <tristan.lins@infinitysoft.de>, Sebastian Leitz <sebastian.leitz@etes.de>
 * @package    parentslist
 * @license    LGPL
 * @filesource
 */

class ParentslistRunonceJob extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	protected function updateChildren($strId, $strParents, $strRootId)
	{
		$strAllChildren = '';
		
		$objChildren = $this->Database->prepare("SELECT id from tl_page WHERE pid=?")->execute($strId);

		if($objChildren->numRows)
		{

			$arrChildren = $objChildren->fetchAllAssoc();
			
			if ($strParents != '')
			{
				$strParents = $strId . ',' . $strParents;
			}
			else
			{
				$strParents = $strId;
			}

			foreach ($arrChildren as $children)
			{
				$strChildren = $this->updateChildren($children['id'], $strParents, $strRootId);
				
				$this->Database->prepare("UPDATE tl_page SET rootId=?, parents=?, childrens=? WHERE id=?")->execute($strRootId, $strParents, $strChildren, $children['id']);

				$strAllChildren .= ($strAllChildren ? ',' : '') . $children['id'] . ($strChildren ? ',' : '') . $strChildren;
			}

		}

		return $strAllChildren;
	}

	public function run()
	{
		if (!$this->Database->fieldExists('parents', 'tl_page'))
		{
			$this->Database->query("ALTER TABLE `tl_page` ADD `parents` varchar(64) NOT NULL default '';");
		}

		if (!$this->Database->fieldExists('rootId', 'tl_page'))
		{
			$this->Database->query("ALTER TABLE `tl_page` ADD `rootId` int(10) unsigned NOT NULL default '0';");
		}
	
		if (!$this->Database->fieldExists('updatechilds', 'tl_page'))
		{
			$this->Database->query("ALTER TABLE `tl_page` ADD `updatechilds` int(1) unsigned NOT NULL default '0';");
		}
	
		if (!$this->Database->fieldExists('childrens', 'tl_page'))
		{
			$this->Database->query("ALTER TABLE `tl_page` ADD `childrens` blob NULL;");
		}
	
		$objRootPages = $this->Database->execute("SELECT id FROM tl_page WHERE pid=0 AND (parents = '' OR parents IS NULL OR childrens = '' OR childrens IS NULL)");

		if($objRootPages->numRows)
		{

			$arrRootPages = $objRootPages->fetchAllAssoc();

			foreach ($arrRootPages as $rootPages)
			{
				$strChildren = $this->updateChildren($rootPages['id'], '', $rootPages['id']);
				$this->Database->prepare("UPDATE tl_page SET rootId=?, parents=?, childrens=? WHERE id=?")->execute(0, 0, $strChildren, $rootPages['id']);
			}
		}
	}

}

$objParentslistRunonceJob = new ParentslistRunonceJob();
$objParentslistRunonceJob->run();

?>
