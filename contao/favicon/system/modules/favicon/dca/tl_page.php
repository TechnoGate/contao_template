<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/*
 * Maintainer contact: Jan Theofel <contao@theofel.com>
 * Ticket system: http://www.contao-forge.org/projects/favicon/issues
 *
 * PHP version 5
 * @copyright  Jan Theofel 2011, ETES GmbH 2010
 * @author     Jan Theofel <contao@theofel.com>, Marc Schneider <marc.schneider@etes.de>, Sebastian Leitz <sebastian.leitz@etes.de>
 * @package    Language
 * @filesource
 */

// palettes

$GLOBALS['TL_DCA']['tl_page']['palettes']['root']    = str_replace('{publish_legend}', '{favicon_legend:hide},faviconPath,faviconExternal,appleTouchIconExternal,appleTouchIconPath;{publish_legend}', $GLOBALS['TL_DCA']['tl_page']['palettes']['root']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace('{publish_legend}', '{favicon_legend:hide},faviconPath,faviconExternal,appleTouchIconExternal,appleTouchIconPath;{publish_legend}', $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);

// fields

$GLOBALS['TL_DCA']['tl_page']['fields']['faviconPath'] = array
(
  'label'        => &$GLOBALS['TL_LANG']['tl_page']['faviconPath'],
  'inputType'    => 'fileTree',
  'eval'         => array('extensions'=>'ico,png,gif', 'files'=>true, 'fieldType'=>'radio')
);

$GLOBALS['TL_DCA']['tl_page']['fields']['faviconExternal'] = array
(
  'label'        => &$GLOBALS['TL_LANG']['tl_page']['faviconExternal'],
  'inputType'    => 'text',
  'eval'         => array('size'=>100, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_page']['fields']['appleTouchIconPath'] = array
(
  'label'        => &$GLOBALS['TL_LANG']['tl_page']['appleTouchIconPath'],
  'inputType'    => 'fileTree',
  'eval'         => array('extensions'=>'png', 'files'=>true, 'fieldType'=>'radio')
);

$GLOBALS['TL_DCA']['tl_page']['fields']['appleTouchIconExternal'] = array
(
  'label'        => &$GLOBALS['TL_LANG']['tl_page']['appleTouchIconExternal'],
  'inputType'    => 'text',
  'eval'         => array('size'=>100, 'tl_class'=>'w50')
);

?>
