<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Cyril Ponce 2007 - 2011
 * @copyright  Patrick Lefèvre 2008 - 2009
 * @copyright  Sophie Bertholet 2009
 * @copyright  Marie Noëlle Augendre 2009
 * @copyright  Jean-Christophe Brebion 2009
 * @copyright  Michel Laurent 2011
 * @author     Cyril Ponce <cyril@contao.fr>
 * @author     Patrick Lefèvre <patrick.lefevre@gmail.com>
 * @author     Sophie Bertholet <sophie.typolight@gmail.com>
 * @author     Marie Noëlle Augendre <mnaugendre@gmail.com>
 * @author     Jean-Christophe Brebion <jean-christophe@fairytree.net>
 * @author     Michel Laurent <michel.laurent@equipc.net>
 * @link       http://www.contao.fr
 * @package    French
 * @license    LGPL
 * @filesource
 */

$GLOBALS['TL_LANG']['tl_module']['newsletters'] = array('Bulletins d\'information disponibles pour inscription', 'Afficher ces bulletins dans le formulaire du front office.');
$GLOBALS['TL_LANG']['tl_module']['nl_channels'] = array('Listes de diffusion', 'Sélectionner une ou plusieurs listes de diffusion.');
$GLOBALS['TL_LANG']['tl_module']['nl_hideChannels'] = array('Cacher le menu des listes de diffusion', 'Ne pas afficher le menu des listes de diffusion.');
$GLOBALS['TL_LANG']['tl_module']['nl_subscribe'] = array('E-mail d\'abonnement', 'Vous pouvez utiliser les jokers <em>##channels##</em> (nom des listes de diffusion), <em>##domain##</em> (nom de domaine courant) et <em>##link##</em> (lien d\'activation).');
$GLOBALS['TL_LANG']['tl_module']['nl_unsubscribe'] = array('E-mail de désabonnement', 'Vous pouvez utiliser les jokers <em>##channels##</em> (nom des listes de diffusion) et <em>##domain##</em> (nom de domaine courant).');
$GLOBALS['TL_LANG']['tl_module']['nl_template'] = array('Présentation du module', 'Choisir une présentation de module.');
$GLOBALS['TL_LANG']['tl_module']['text_subscribe'] = array('Votre abonnement sur %s', 'Vous avez souscrit aux listes de diffusions suivantes sur ##domain##: ##channels## Veuillez cliquer sur ##link## pour valider votre abonnement. Si vous ne vous ne souhaitez pas souscrire à ces listes de diffusion, ignorez cet e-mail.');
$GLOBALS['TL_LANG']['tl_module']['text_unsubscribe'] = array('Votre abonnement sur %s', 'Vous avez été désabonné des listes de diffusions suivantes sur ##domain##: ##channels##');

?>