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
 * This is the subcolumns configuration file.
 *
 * PHP version 5
 * @copyright  Felix Pfeiffer : Neue Medien 2010
 * @author     Felix Pfeiffer <info@felixpfeiffer.com>
 * @package    Subcolumns
 * @license    CC-A 2.0
 * @filesource
 */
 
/**
 * -------------------------------------------------------------------------
 * CONTENT ELEMENTS
 * -------------------------------------------------------------------------
 */

$GLOBALS['TL_CTE']['subcolumn'] = array(
	'colsetStart' => 'colsetStart',
	'colsetPart' => 'colsetPart',
	'colsetEnd' => 'colsetEnd'
);


array_insert($GLOBALS['FE_MOD']['application'], 4, array
(
	'subcolumns' => 'ModuleSubcolumns'
));

$GLOBALS['BE_FFL']['extendedModuleWizard'] = 'ExtendedModuleWizard';

/**
 * Form fields
 */
$GLOBALS['TL_FFL']['formcolstart'] = 'FormColStart';
$GLOBALS['TL_FFL']['formcolpart'] = 'FormColPart';
$GLOBALS['TL_FFL']['formcolend'] = 'FormColEnd';

/**
 * Spaltensets
**/
$GLOBALS['TL_SUBCL'] = array(
	'20x20x20x20x20' => array(array('c20l','subcl'),array('c20l','subc'),array('c20l','subc'),array('c20l','subc'),array('c20r','subcr')),
	'25x25x25x25' => array(array('c25l','subcl'),array('c25l','subc'),array('c25l','subc'),array('c25r','subcr')),
	'50x16x16x16' => array(array('c50l','subcl'),array('c16l','subc'),array('c16l','subc'),array('c16r','subcr')),
	'33x33x33' => array(array('c33l','subcl'),array('c33l','subc'),array('c33r','subcr')),
	'50x25x25' => array(array('c50l','subcl'),array('c25l','subc'),array('c25r','subcr')),
	'25x50x25' => array(array('c25l','subcl'),array('c50l','subc'),array('c25r','subcr')),
	'25x25x50' => array(array('c25l','subcl'),array('c25l','subc'),array('c50r','subcr')),
	'40x30x30' => array(array('c40l','subcl'),array('c30l','subc'),array('c30r','subcr')),
	'30x40x30' => array(array('c30l','subcl'),array('c40l','subc'),array('c30r','subcr')),
	'30x30x40' => array(array('c30l','subcl'),array('c30l','subc'),array('c40r','subcr')),
	'20x40x40' => array(array('c20l','subcl'),array('c40l','subc'),array('c40r','subcr')),
	'40x20x40' => array(array('c40l','subcl'),array('c20l','subc'),array('c40r','subcr')),
	'40x40x20' => array(array('c40l','subcl'),array('c40l','subc'),array('c20r','subcr')),
	'85x15' => array(array('c85l','subcl'),array('c15r','subcr')),
	'80x20' => array(array('c80l','subcl'),array('c20r','subcr')),
	'75x25' => array(array('c75l','subcl'),array('c25r','subcr')),
	'70x30' => array(array('c70l','subcl'),array('c30r','subcr')),
	'66x33' => array(array('c66l','subcl'),array('c33r','subcr')),
	'62x38' => array(array('c62l','subcl'),array('c38r','subcr')),
	'60x40' => array(array('c60l','subcl'),array('c40r','subcr')),
	'55x45' => array(array('c55l','subcl'),array('c45r','subcr')),
	'50x50' => array(array('c50l','subcl'),array('c50r','subcr')),
	'45x55' => array(array('c45l','subcl'),array('c55r','subcr')),
	'40x60' => array(array('c40l','subcl'),array('c60r','subcr')),
	'38x62' => array(array('c38l','subcl'),array('c62r','subcr')),
	'33x66' => array(array('c33l','subcl'),array('c66r','subcr')),
	'30x70' => array(array('c30l','subcl'),array('c70r','subcr')),
	'25x75' => array(array('c25l','subcl'),array('c75r','subcr')),
	'20x80' => array(array('c20l','subcl'),array('c80r','subcr')),
	'15x85' => array(array('c15l','subcl'),array('c85r','subcr'))
	
);
?>