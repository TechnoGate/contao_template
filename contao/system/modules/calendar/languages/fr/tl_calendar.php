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

$GLOBALS['TL_LANG']['tl_calendar']['title'] = array('Titre', 'Saisir un titre');
$GLOBALS['TL_LANG']['tl_calendar']['jumpTo'] = array('Page de redirection', 'Sélectionner la page qui permettra de lire le détail de l\'événement après avoir cliqué sur celui-ci.');
$GLOBALS['TL_LANG']['tl_calendar']['allowComments'] = array('Activer les commentaires', 'Autoriser les visiteurs à commenter les événements.');
$GLOBALS['TL_LANG']['tl_calendar']['notify'] = array('Notifier', 'Choisir qui notifier lorsque des commentaires sont ajoutés.');
$GLOBALS['TL_LANG']['tl_calendar']['sortOrder'] = array('Ordre de tri', 'Par défaut, les commentaires sont triés en ordre croissant, en commençant par le plus ancien.');
$GLOBALS['TL_LANG']['tl_calendar']['perPage'] = array('Commentaires par page', 'Nombre de commentaires par page. Mettre 0 pour désactiver la pagination.');
$GLOBALS['TL_LANG']['tl_calendar']['moderate'] = array('Modérer les commentaires', 'Approuver les commentaires avant qu\'ils ne soient publiés sur le site.');
$GLOBALS['TL_LANG']['tl_calendar']['bbcode'] = array('BBCode autorisé', 'Autoriser les visiteurs à formater leurs commentaires à l\'aide de BBCode.');
$GLOBALS['TL_LANG']['tl_calendar']['requireLogin'] = array('Connexion préalable nécessaire', 'N\'autoriser que les utilisateurs authentifiés à créer des commentaires.');
$GLOBALS['TL_LANG']['tl_calendar']['disableCaptcha'] = array('Désactiver la question de sécurité', 'N\'utiliser cette option que si vous avez restreint les commentaires aux utilisateurs habilités.');
$GLOBALS['TL_LANG']['tl_calendar']['protected'] = array('Protéger le calendrier', 'Afficher les événements à certains groupes de membres seulement.');
$GLOBALS['TL_LANG']['tl_calendar']['groups'] = array('Groupes de membres autorisés', 'Ces groupes seront en mesure de voir les événements de ce calendrier.');
$GLOBALS['TL_LANG']['tl_calendar']['makeFeed'] = array('Générer un flux', 'Générer un flux RSS ou Atom à partir du calendrier.');
$GLOBALS['TL_LANG']['tl_calendar']['format'] = array('Format du flux', 'Sélectionner un format de flux.');
$GLOBALS['TL_LANG']['tl_calendar']['language'] = array('Langue du flux', 'Saisir la langue du flux en fonction de la norme ISO-639 (ex : <em>en</em>, <em>en-us</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['source'] = array('Paramètres d\'export', 'Choisir ce qui sera exporté.');
$GLOBALS['TL_LANG']['tl_calendar']['maxItems'] = array('Nombre maximum d\'éléments', 'Vous pouvez limiter le nombre d\'événements. Mettre à 0 pour tout exporter.');
$GLOBALS['TL_LANG']['tl_calendar']['feedBase'] = array('URL de base', 'Saisir l\'URL de base en incluant le protocole (ex : <em>http://</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['alias'] = array('Alias du flux', 'Saisir un nom unique pour le flux (sans extension). Un fichier XML sera créé dans le répertoire racine de votre site Contao (<em>name.xml</em>).');
$GLOBALS['TL_LANG']['tl_calendar']['description'] = array('Description du flux', 'Saisir une courte description du calendrier.');
$GLOBALS['TL_LANG']['tl_calendar']['tstamp'] = array('Date de révision', 'Date et heure de la dernière révision');
$GLOBALS['TL_LANG']['tl_calendar']['title_legend'] = 'Titre et page de redirection';
$GLOBALS['TL_LANG']['tl_calendar']['comments_legend'] = 'Commentaires';
$GLOBALS['TL_LANG']['tl_calendar']['protected_legend'] = 'Protection d\'accès';
$GLOBALS['TL_LANG']['tl_calendar']['feed_legend'] = 'Flux RSS / Atom';
$GLOBALS['TL_LANG']['tl_calendar']['notify_admin'] = 'Administrateur système';
$GLOBALS['TL_LANG']['tl_calendar']['notify_author'] = 'Auteur de l\'événement';
$GLOBALS['TL_LANG']['tl_calendar']['notify_both'] = 'Auteur et administrateur système';
$GLOBALS['TL_LANG']['tl_calendar']['source_teaser'] = 'Accroche d\'événements';
$GLOBALS['TL_LANG']['tl_calendar']['source_text'] = 'Articles complets';
$GLOBALS['TL_LANG']['tl_calendar']['new'] = array('Nouveau calendrier', 'Créer un nouveau calendrier');
$GLOBALS['TL_LANG']['tl_calendar']['show'] = array('Détails du calendrier', 'Afficher les détails du calendrier ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['edit'] = array('Éditer le calendrier', 'Éditer le calendrier ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['editheader'] = array('Éditer les paramètres du calendrier', 'Éditer les paramètres du calendrier ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['copy'] = array('Dupliquer le calendrier', 'Dupliquer le calendrier ID %s');
$GLOBALS['TL_LANG']['tl_calendar']['delete'] = array('Supprimer le calendrier', 'Supprimer le calendrier ID %s');

?>