<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * PHP version 5
 * @copyright  Jan Theofel 2011, ETES GmbH 2010
 * @author     Jan Theofel <jan@theofelde>
 * @package    googleanalytics
 * @license    LGPL
 * @filesource
 */

// register parse template hook
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array('googleanalytics', 'addGoogleAnalytics');

// register custom inserttags
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('googleanalytics', 'gaInsertTag');

?>
