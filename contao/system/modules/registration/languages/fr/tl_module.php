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

$GLOBALS['TL_LANG']['tl_module']['disableCaptcha'] = array('Désactiver la question de sécurité', 'Vous pouvez désactiver la question de sécurité (non recommandé)');
$GLOBALS['TL_LANG']['tl_module']['reg_groups'] = array('Groupes de membre', 'Vous pouvez affecter l\'utilisateur à un ou plusieurs groupes.');
$GLOBALS['TL_LANG']['tl_module']['reg_allowLogin'] = array('Permettre la connexion', 'Permet au nouvel utilisateur de se connecter au front office.');
$GLOBALS['TL_LANG']['tl_module']['reg_skipName'] = array('Passer le nom d\'utilisateur', 'N\'exige pas le nom d\'utilisateur pour demander un nouveau mot de passe.');
$GLOBALS['TL_LANG']['tl_module']['reg_close'] = array('Mode', 'Définir la manière de gérer la suppression.');
$GLOBALS['TL_LANG']['tl_module']['reg_assignDir'] = array('Créer un répertoire personnel', 'Créer un répertoire personnel au nom de l\'utilisateur enregistré.');
$GLOBALS['TL_LANG']['tl_module']['reg_homeDir'] = array('Chemin d\'accès au répertoire', 'Choisir le dossier parent dans l\'explorateur de de fichiers.');
$GLOBALS['TL_LANG']['tl_module']['reg_activate'] = array('Envoyer un e-mail d\'activation', 'Envoyer un e-mail d\'activation à l\'adresse e-mail enregistrée.');
$GLOBALS['TL_LANG']['tl_module']['reg_jumpTo'] = array('Page de confirmation', 'Choisir la page vers laquelle l\'utilisateur sera redirigé après que sa requête ait été exécutée.');
$GLOBALS['TL_LANG']['tl_module']['reg_text'] = array('Message d\'activation', 'Vous pouvez utiliser les caractères joker <em>##domain##</em> (nom de domaine), <em>##link##</em> (lien d\'activation) et tous les champs de saisie (ex: <em>##lastname##</em>).');
$GLOBALS['TL_LANG']['tl_module']['reg_password'] = array('Message de confirmation', 'Vous pouvez utiliser les caractères joker <em>##domain##</em> (nom de domaine), <em>##link##</em> (lien d\'activation) et tous les attributs de l\'utilisateur (ex: <em>##lastname##</em>).');
$GLOBALS['TL_LANG']['tl_module']['account_legend'] = 'Paramètres du compte';
$GLOBALS['TL_LANG']['tl_module']['emailText'] = array('Votre enregistrement sur %s', 'Merci de vous être inscrit sur ##domain##. Veuillez cliquer sur ##link## pour compléter votre inscription et activer votre compte. Si vous n\'avez pas demandé la création d\'un compte, merci de ne pas tenir compte de cet e-mail.');
$GLOBALS['TL_LANG']['tl_module']['passwordText'] = array('Votre demande de mot de passe sur %s', 'Vous avez demandé un nouveau mot de passe pour ##domain##. Veuillez cliquer sur ##link## pour entrer le nouveau mot de passe. Si vous n\'avez pas demandé pas cet e-mail, merci de contacter l\'administrateur du site internet.');
$GLOBALS['TL_LANG']['tl_module']['close_deactivate'] = 'Désactiver le compte';
$GLOBALS['TL_LANG']['tl_module']['close_delete'] = 'Supprimer le compte de manière irrévocable';

?>