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

$GLOBALS['TL_LANG']['tl_user']['username'] = array('Identifiant', 'Saisir un identifiant unique.');
$GLOBALS['TL_LANG']['tl_user']['name'] = array('Nom', 'Saisir le prénom ainsi que le nom.');
$GLOBALS['TL_LANG']['tl_user']['email'] = array('Adresse e-mail', 'Saisir une adresse e-mail valide.');
$GLOBALS['TL_LANG']['tl_user']['language'] = array('Langue du back office', 'Choisir une langue pour le back office.');
$GLOBALS['TL_LANG']['tl_user']['backendTheme'] = array('Thème du back office', 'Outrepasser le thème global du back office.');
$GLOBALS['TL_LANG']['tl_user']['uploader'] = array('Outil d\'envoi de fichier', 'Choisir un outil d\'envoi de fichier.');
$GLOBALS['TL_LANG']['tl_user']['showHelp'] = array('Afficher l\'aide', 'Afficher une courte explication après chaque champ de saisie.');
$GLOBALS['TL_LANG']['tl_user']['thumbnails'] = array('Afficher les vignettes', 'Afficher les vignettes d\'images dans le gestionnaire de fichiers.');
$GLOBALS['TL_LANG']['tl_user']['useRTE'] = array('Activer l\'éditeur de texte riche', 'Utiliser l\'éditeur de texte riche pour formater les éléments textes.');
$GLOBALS['TL_LANG']['tl_user']['useCE'] = array('Activer l\'éditeur de code', 'Utiliser l\'éditeur de code pour modifier les éléments de type "code"');
$GLOBALS['TL_LANG']['tl_user']['pwChange'] = array('Changement mot de passe requis', 'L\'utilisateur devra modifier son mot de passe lors de la prochaine connexion.');
$GLOBALS['TL_LANG']['tl_user']['admin'] = array('Définir l\'utilisateur comme administrateur', 'Un administrateur a un accès illimité à tous les modules et éléments.');
$GLOBALS['TL_LANG']['tl_user']['groups'] = array('Groupes d\'utilisateurs', 'Vous pouvez affecter l\'utilisateur à un ou plusieurs groupes.');
$GLOBALS['TL_LANG']['tl_user']['inherit'] = array('Transmission de permission', 'Définir de quelles permissions de groupe l\'utilisateur hérite.');
$GLOBALS['TL_LANG']['tl_user']['group'] = array('Utiliser les préférences de groupe uniquement', 'L\'utilisateur courant hérite seulement des permissions du groupe.');
$GLOBALS['TL_LANG']['tl_user']['extend'] = array('Étendre les préférences de groupe', 'Les permissions du groupe sont étendues par celles de l\'utilisateur.');
$GLOBALS['TL_LANG']['tl_user']['custom'] = array('Utiliser seulement les préférences de l\'utilisateur', 'Seules les permissions de l\'utilisateur courant sont appliquées.');
$GLOBALS['TL_LANG']['tl_user']['modules'] = array('Modules du back office', 'Accorder l\'accès à un ou plusieurs modules du back office.');
$GLOBALS['TL_LANG']['tl_user']['themes'] = array('Modules du thème', 'Accorder l\'accès au modules du thème.');
$GLOBALS['TL_LANG']['tl_user']['pagemounts'] = array('Pages autorisées', 'Accorder l\'accès à une ou plusieurs pages (les sous-pages seront incluses automatiquement).');
$GLOBALS['TL_LANG']['tl_user']['alpty'] = array('Types de page autorisés', 'Sélectionner les types de page que vous souhaitez autoriser.');
$GLOBALS['TL_LANG']['tl_user']['filemounts'] = array('Répertoires autorisés', 'Accorder l\'accès à un ou plusieurs répertoires (les sous-répertoires sont inclus automatiquement).');
$GLOBALS['TL_LANG']['tl_user']['forms'] = array('Formulaires autorisés', 'Accorder l\'accès à un ou plusieurs formulaires.');
$GLOBALS['TL_LANG']['tl_user']['formp'] = array('Permissions du formulaire', 'Définir les permissions du formulaire.');
$GLOBALS['TL_LANG']['tl_user']['disable'] = array('Inactif', 'Désactiver temporairement un compte.');
$GLOBALS['TL_LANG']['tl_user']['start'] = array('Activé à partir du', 'Activer automatiquement un compte à partir de ce jour.');
$GLOBALS['TL_LANG']['tl_user']['stop'] = array('Désactivé à partir du', 'Désactiver automatiquement un compte à partir de ce jour.');
$GLOBALS['TL_LANG']['tl_user']['session'] = array('Purger des données', 'Sélectionner les données que vous souhaitez purger.');
$GLOBALS['TL_LANG']['tl_user']['name_legend'] = 'Nom et e-mail';
$GLOBALS['TL_LANG']['tl_user']['backend_legend'] = 'Paramètres du back office';
$GLOBALS['TL_LANG']['tl_user']['theme_legend'] = 'Thème du back office';
$GLOBALS['TL_LANG']['tl_user']['password_legend'] = 'Paramètres du mot de passe';
$GLOBALS['TL_LANG']['tl_user']['admin_legend'] = 'Administrateur';
$GLOBALS['TL_LANG']['tl_user']['groups_legend'] = 'Groupes d\'utilisateurs';
$GLOBALS['TL_LANG']['tl_user']['modules_legend'] = 'Modules autorisés';
$GLOBALS['TL_LANG']['tl_user']['pagemounts_legend'] = 'Pages autorisées';
$GLOBALS['TL_LANG']['tl_user']['filemounts_legend'] = 'Répertoires autorisés';
$GLOBALS['TL_LANG']['tl_user']['forms_legend'] = 'Permissions des formulaires';
$GLOBALS['TL_LANG']['tl_user']['account_legend'] = 'Paramètres du compte';
$GLOBALS['TL_LANG']['tl_user']['session_legend'] = 'Effacer le cache';
$GLOBALS['TL_LANG']['tl_user']['sessionLabel'] = 'Données de session';
$GLOBALS['TL_LANG']['tl_user']['htmlLabel'] = 'Cache d\'image';
$GLOBALS['TL_LANG']['tl_user']['tempLabel'] = 'Répertoire temporaire';
$GLOBALS['TL_LANG']['tl_user']['sessionPurged'] = 'Les données de session ont été purgées';
$GLOBALS['TL_LANG']['tl_user']['htmlPurged'] = 'Le cache d\'image a été purgé';
$GLOBALS['TL_LANG']['tl_user']['tempPurged'] = 'Le répertoire temporaire a été purgé';
$GLOBALS['TL_LANG']['tl_user']['FileUpload'] = 'Outil d\'envoi de fichier par défaut';
$GLOBALS['TL_LANG']['tl_user']['new'] = array('Nouvel utilisateur', 'Créer un nouvel utilisateur');
$GLOBALS['TL_LANG']['tl_user']['show'] = array('Détails de l\'utilisateur', 'Afficher les détails de l\'utilisateur ID %s');
$GLOBALS['TL_LANG']['tl_user']['edit'] = array('Éditer l\'utilisateur', 'Éditer l\'utilisateur ID %s');
$GLOBALS['TL_LANG']['tl_user']['copy'] = array('Dupliquer l\'utilisateur', 'Dupliquer l\'utilisateur ID %s');
$GLOBALS['TL_LANG']['tl_user']['delete'] = array('Supprimer l\'utilisateur', 'Supprimer l\'utilisateur ID %s');
$GLOBALS['TL_LANG']['tl_user']['toggle'] = array('Activer / désactiver l\'utilisateur', 'Activer / désactiver l\'utilisateur ID %s');
$GLOBALS['TL_LANG']['tl_user']['su'] = array('Changer d\'utilisateur', 'Changer avec l\'utilisateur ID %s');

?>