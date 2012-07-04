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
 * This is the data container array for table tl_content.
 *
 * PHP version 5
 * @copyright  2007 
 * @author     Felix Pfeiffer 
 * @package    subcolumns v 1.1.1 
 * @license    GPL 
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_article']['config']['oncopy_callback'][] = array('tl_subcolumnsCallback','articleCheck');

?>