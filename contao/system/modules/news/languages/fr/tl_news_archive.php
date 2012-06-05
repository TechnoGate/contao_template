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

$GLOBALS['TL_LANG']['tl_news_archive']['title'] = array('Titre', 'Saisir un titre d\'archive.');
$GLOBALS['TL_LANG']['tl_news_archive']['jumpTo'] = array('Aller à la page', 'Choisir la page vers laquelle les visiteurs seront réorientés en cliquant sur une actualité.');
$GLOBALS['TL_LANG']['tl_news_archive']['allowComments'] = array('Activer les commentaires', 'Autoriser les visiteurs à commenter les actualités.');
$GLOBALS['TL_LANG']['tl_news_archive']['notify'] = array('Notifier', 'Choisir qui notifier lorsque des commentaires sont ajoutés.');
$GLOBALS['TL_LANG']['tl_news_archive']['sortOrder'] = array('Ordre de tri', 'Par défaut, les commentaires sont triés en ordre croissant, en commençant par le plus ancien.');
$GLOBALS['TL_LANG']['tl_news_archive']['perPage'] = array('Commentaires par page', 'Nombre de commentaires par page. Mettre 0 pour désactiver la pagination.');
$GLOBALS['TL_LANG']['tl_news_archive']['moderate'] = array('Modérer les commentaires', 'Approuver les commentaires avant qu\'ils ne soient publiés sur le site.');
$GLOBALS['TL_LANG']['tl_news_archive']['bbcode'] = array('BBCode autorisé', 'Autoriser les visiteurs à formater leurs commentaires à l\'aide de BBCode.');
$GLOBALS['TL_LANG']['tl_news_archive']['requireLogin'] = array('Connexion préalable nécessaire', 'N\'autoriser que les utilisateurs authentifiés à créer des commentaires.');
$GLOBALS['TL_LANG']['tl_news_archive']['disableCaptcha'] = array('Désactiver la question de sécurité', 'N\'utiliser cette option que si vous avez limité les commentaires aux membres connectés.');
$GLOBALS['TL_LANG']['tl_news_archive']['protected'] = array('Protéger l\'archive d\'actualités', 'Afficher les actualités à certains groupes de membres seulement.');
$GLOBALS['TL_LANG']['tl_news_archive']['groups'] = array('Groupes de membres autorisés', 'Choisir quels groupes seront autorisés à voir les actualités.');
$GLOBALS['TL_LANG']['tl_news_archive']['makeFeed'] = array('Générer un flux', 'Générer un flux RSS ou Atom à partir de l\'archive des actualités.');
$GLOBALS['TL_LANG']['tl_news_archive']['format'] = array('Format du flux', 'Choisir le format du flux.');
$GLOBALS['TL_LANG']['tl_news_archive']['language'] = array('Langue', 'Saisir la langue selon le format ISO-639 (ex: <em>en</em> ou <em>en-us</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['source'] = array('Paramètres d\'exportation', 'Choisir ce qui sera exporté.');
$GLOBALS['TL_LANG']['tl_news_archive']['maxItems'] = array('Nombre maximum d\'éléments', 'Limiter le nombre d\'articles exportés. Saisir 0 pour tout exporter.');
$GLOBALS['TL_LANG']['tl_news_archive']['feedBase'] = array('URL de base', 'Saisir l\'URL de base en incluant le protocole (ex: <em>http://</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['alias'] = array('Alias du flux', 'Entrer un nom unique pour le flux. Un fichier XML sera auto-généré à la racine de votre installation Contao (<em>name.xml</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['description'] = array('Description du flux', 'Saisir une courte description du flux d\'actualités.');
$GLOBALS['TL_LANG']['tl_news_archive']['tstamp'] = array('Date de révision', 'Date et heure de la dernière révision');
$GLOBALS['TL_LANG']['tl_news_archive']['title_legend'] = 'Titre et page de redirection';
$GLOBALS['TL_LANG']['tl_news_archive']['comments_legend'] = 'Commentaires';
$GLOBALS['TL_LANG']['tl_news_archive']['protected_legend'] = 'Protection d\'accès';
$GLOBALS['TL_LANG']['tl_news_archive']['feed_legend'] = 'Flux RSS/Atom';
$GLOBALS['TL_LANG']['tl_news_archive']['notify_admin'] = 'Administrateur système';
$GLOBALS['TL_LANG']['tl_news_archive']['notify_author'] = 'Auteur de l\'actualité';
$GLOBALS['TL_LANG']['tl_news_archive']['notify_both'] = 'Auteur et administrateur du système';
$GLOBALS['TL_LANG']['tl_news_archive']['source_teaser'] = 'Accroches d\'actualités';
$GLOBALS['TL_LANG']['tl_news_archive']['source_text'] = 'Articles complets';
$GLOBALS['TL_LANG']['tl_news_archive']['new'] = array('Nouvelle archive', 'Créer une nouvelle archive');
$GLOBALS['TL_LANG']['tl_news_archive']['show'] = array('Détails de l\'archive', 'Afficher les détails de l\'archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['edit'] = array('Éditer l\'archive', 'Éditer l\'archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['editheader'] = array('Éditer les paramètres de l\'archive', 'Éditer les paramètres de l\'archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['copy'] = array('Dupliquer l\'archive', 'Dupliquer l\'archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['delete'] = array('Supprimer l\'archive', 'Supprimer l\'archive ID %s');
$GLOBALS['TL_LANG']['tl_news_archive']['comments'] = array('Commentaires', 'Afficher les commentaires de l\'archive ID %s');

?>