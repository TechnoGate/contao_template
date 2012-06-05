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

$GLOBALS['TL_LANG']['tl_form']['title'] = array('Titre', 'Saisir le titre du formulaire.');
$GLOBALS['TL_LANG']['tl_form']['alias'] = array('Alias du formulaire', 'L\'alias du formulaire est une référence unique qui remplace l\'ID du formulaire.');
$GLOBALS['TL_LANG']['tl_form']['jumpTo'] = array('Aller à la page', 'Choisir la page vers laquelle les visiteurs seront redirigés après soumission du formulaire.');
$GLOBALS['TL_LANG']['tl_form']['sendViaEmail'] = array('Envoyer les données du formulaire par e-mail', 'Envoyer les données soumises vers une adresse e-mail');
$GLOBALS['TL_LANG']['tl_form']['recipient'] = array('Adresse du/des destinataire(s)', 'Séparer les adresses multiples par une virgule.');
$GLOBALS['TL_LANG']['tl_form']['subject'] = array('Sujet', 'Saisir le sujet de l\'e-mail.');
$GLOBALS['TL_LANG']['tl_form']['format'] = array('Format des données', 'Choisir le format dans lequel les données seront transmises.');
$GLOBALS['TL_LANG']['tl_form']['raw'] = array('Données brutes', 'Les données du formulaire seront envoyées en texte simple avec une nouvelle ligne pour chaque champ.');
$GLOBALS['TL_LANG']['tl_form']['xml'] = array('Fichier XML', 'Les données du formulaire seront attachées à l\'e-mail sous forme de fichier XML.');
$GLOBALS['TL_LANG']['tl_form']['csv'] = array('Fichier CSV', 'Les données du formulaire seront attachées à l\'e-mail sous forme de fichier CSV (valeurs séparées par des virgules).');
$GLOBALS['TL_LANG']['tl_form']['email'] = array('Format E-mail', 'Ignore tous les champs autres que <em>email</em>, <em>subject</em>, <em>message</em> et <em>cc</em> (copie cachée), et envoie les données du formulaire comme s\'il avait été émis par un mail client. Permet le téléchargement de fichiers.');
$GLOBALS['TL_LANG']['tl_form']['skipEmtpy'] = array('Ignorer les champs non renseignés', 'Ne pas inclure dans l\'e-mail les champs non saisis');
$GLOBALS['TL_LANG']['tl_form']['storeValues'] = array('Enregistrer les données', 'Enregistrer les données du formulaire dans la base de données');
$GLOBALS['TL_LANG']['tl_form']['targetTable'] = array('Table cible', 'La table cible doit contenir une colonne pour chaque champ du formulaire');
$GLOBALS['TL_LANG']['tl_form']['method'] = array('Méthode de soumission du formulaire', 'Par défaut, la méthode de soumission est POST');
$GLOBALS['TL_LANG']['tl_form']['attributes'] = array('ID / classe(s) CSS', 'Saisir un ID et/ou une ou plusieurs classes CSS.');
$GLOBALS['TL_LANG']['tl_form']['formID'] = array('ID du formulaire', 'L\'ID de formulaire est nécessaire pour déclencher l\'exécution d\'un module Contao.');
$GLOBALS['TL_LANG']['tl_form']['tableless'] = array('Disposition sans tableau', 'Création du formulaire sans tableau HTML.');
$GLOBALS['TL_LANG']['tl_form']['allowTags'] = array('Autoriser les balises HTML', 'Autoriser les balises HTML dans les champs de formulaire.');
$GLOBALS['TL_LANG']['tl_form']['tstamp'] = array('Date de modification', 'Date et heure de la dernière modification');
$GLOBALS['TL_LANG']['tl_form']['title_legend'] = 'Titre et page de redirection';
$GLOBALS['TL_LANG']['tl_form']['email_legend'] = 'Envoyer les données';
$GLOBALS['TL_LANG']['tl_form']['store_legend'] = 'Stocker les données';
$GLOBALS['TL_LANG']['tl_form']['expert_legend'] = 'Paramètres avancés';
$GLOBALS['TL_LANG']['tl_form']['config_legend'] = 'Configuration du formulaire';
$GLOBALS['TL_LANG']['tl_form']['new'] = array('Nouveau formulaire', 'Créer un nouveau formulaire');
$GLOBALS['TL_LANG']['tl_form']['show'] = array('Détails du formulaire', 'Afficher les détails du formulaire ID %s');
$GLOBALS['TL_LANG']['tl_form']['edit'] = array('Éditer le formulaire', 'Éditer le formulaire ID %s');
$GLOBALS['TL_LANG']['tl_form']['editheader'] = array('Éditer les paramètres du formulaire', 'Éditer les paramètres du formulaire ID %s');
$GLOBALS['TL_LANG']['tl_form']['copy'] = array('Dupliquer le formulaire', 'Dupliquer le formulaire ID %s');
$GLOBALS['TL_LANG']['tl_form']['delete'] = array('Supprimer le formulaire', 'Supprimer le formulaire ID %s');

?>