<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 *
 * The TYPOlight webCMS is an accessible web content management system that 
 * specializes in accessibility and generates W3C-compliant HTML code. It 
 * provides a wide range of functionality to develop professional websites 
 * including a built-in search engine, form generator, file and user manager, 
 * CSS engine, multi-language support and many more. For more information and 
 * additional TYPOlight applications like the TYPOlight MVC Framework please 
 * visit the project website http://www.typolight.org.
 *
 * Language file for table tl_content (en).
 *
 * PHP version 5
 * @copyright  2007 
 * @author     Felix Pfeiffer 
 * @package    subcolumns v 1.1.1 
 * @license    GPL 
 * @filesource
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_content']['sc_name'] = array('Column-Set Name', 'This name is used to link the parts of the colse together.');
$GLOBALS['TL_LANG']['tl_content']['sc_gap'] = array('Column-Gap', 'The Column-Gap defines the space between two clumns in px. The default value is set to 12px.');
$GLOBALS['TL_LANG']['tl_content']['sc_gapdefault'] = array('Use column-gap', 'Do you want to use a gap between the columns?');
$GLOBALS['TL_LANG']['tl_content']['sc_type'] = array('Column-Set Type', 'How many columns and what widths should be created?<br />The numbers give the width in percent: 25x75 => first column 25%, second column 75% of the parent-container.');
$GLOBALS['TL_LANG']['tl_content']['sc_equalize'] = array('equal Heights', 'This options sets all columns in a set to the height of the longest one. So you can use background images or borders.<br />An example for this feature can be found on the webpage of the <a href="http://www.yaml.de/fileadmin/examples/06_layouts_advanced/equal_height_boxes.html" onclick="window.open(this.href); return false;" title="YAML-Framework">YAML-framework</a>.');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_content']['colset_legend']      = 'Columnset settings';
$GLOBALS['TL_LANG']['tl_content']['colheight_legend']      = 'Column height';

?>