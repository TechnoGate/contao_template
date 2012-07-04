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

$GLOBALS['TL_HOOKS']['generatePage'][] = array('favicon', 'createFavicon');

?>
