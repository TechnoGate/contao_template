<?php

/**
 * PHP version 5
 * @copyright  Jan Theofel 2011, Tristan Lins 2011, ETES GmbH 2010
 * @author     Jan Theofel <jan@theofel.de>, Tristan Lins <tristan.lins@infinitysoft.de>, Sebastian Leitz <sebastian.leitz@etes.de>
 * @package    parentslist
 * @license    LGPL
 * @filesource
 */

/*
  THIS IS JUST A COPY TOO SEE THE RUNONCE AFTER IT HAS BEEN PROCESSED!
*/

class RunonceJob extends Frontend
{
	public function __construct()
	{
		parent::__construct();
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

$objRunonceJob = new RunonceJob();
$objRunonceJob->run();

?>
