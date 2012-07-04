<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/* 
 * Maintainer contact: Jan Theofel <contao@theofel.com>
 * Ticket system: http://www.contao-forge.org/projects/favicon/issues
 * 
 * PHP version 5
 * @copyright  Jan Theofel 2011, ETES GmbH 2010
 * @author     Jan Theofel <contao@theofel.com>, Marc Schneider <marc.schneider@etes.de>, Sebastian Leitz <sebastian.leitz@etes.de>
 * @filesource
 */

class favicon extends Frontend
{
  // generate page hook to add icons
  public function createFavicon(Database_Result $objPage, Database_Result $objLayout, PageRegular $objPageRegular)
  {
    $favicon = '';
    $appleTouchIcon = '';

    // check actual page for internal or external favicon    
    if($objPage->faviconExternal)
    {
      $favicon = $objPage->faviconExternal;
    }
    elseif(file_exists($objPage->faviconPath))
    {
      $favicon = $objPage->faviconPath;
    }

    // check actual page for internal or external apple touch icon
    if($objPage->appleTouchIconExternal)
    {
      $appleTouchIcon = $objPage->appleTouchIconExternal;
    }
    elseif(file_exists($objPage->appleTouchIconPath))
    {
      $appleTouchIcon = $objPage->appleTouchIconPath;
    }

    // both found on this page
    if($favicon == '' || $appleTouchIcon == '')
    {
      // check if parents for settings 
      $parents = $this->Database->execute("select faviconExternal, faviconPath, appleTouchIconExternal, appleTouchIconPath, field(id," . $objPage->parents . ") as mysort from tl_page where id in (" . $objPage->parents . ") order by mysort;");

      if($parents->numRows > 0)
      {
        while($parents->next())
        {
          // check this parent for internal or external favicon if still not found
          if($favicon == '')
          {
            if($parents->faviconExternal)
            {
              $favicon = $parents->faviconExternal;
            }
            elseif(file_exists($parents->faviconPath))
            {
              $favicon = $parents->faviconPath;
            }
          }

          // check this parent for internal or external apple touch icon if still not found
          if($appleTouchIcon == '')
          {
            if($parents->appleTouchIconExternal)
            {
              $appleTouchIcon = $parents->appleTouchIconExternal;
            }
            elseif(file_exists($parents->appleTouchIconPath))
            {
              $appleTouchIcon = $parents->appleTouchIconPath;
            }
          }
          
          if($favicon != '' && $appleTouchIcon != '')
          {
            last;
          }
        }
      }
    }
   
    // add favicon to header
    if($favicon != '')
    {
      $GLOBALS['TL_HEAD'][] = '<link rel="icon" type="image/vnd.microsoft.icon" href="'.$favicon.'" />';
      $GLOBALS['TL_HEAD'][] = '<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="'.$favicon.'" />';
    }

    // add apple touch icon to header
    if($appleTouchIcon != '')
    {
      $GLOBALS['TL_HEAD'][] = '<link rel="apple-touch-icon" href="'.$appleTouchIcon.'" />';
    }
  }
}

?>
