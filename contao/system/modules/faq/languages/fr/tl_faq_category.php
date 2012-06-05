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

$GLOBALS['TL_LANG']['tl_faq_category']['title'] = array('Titre', 'Saisir le titre de la catégorie.');
$GLOBALS['TL_LANG']['tl_faq_category']['headline'] = array('En-tête', 'Saisir l\'en-tête de la catégorie.');
$GLOBALS['TL_LANG']['tl_faq_category']['jumpTo'] = array('Aller à la page', 'Sélectionner la page vers laquelle les visiteurs seront redirigés après avoir cliqué sur une question.');
$GLOBALS['TL_LANG']['tl_faq_category']['allowComments'] = array('Activer les commentaires', 'Autoriser les visiteurs à commenter les FAQ.');
$GLOBALS['TL_LANG']['tl_faq_category']['notify'] = array('Notifier', 'Choisir qui notifier lorsque des commentaires sont ajoutés.');
$GLOBALS['TL_LANG']['tl_faq_category']['sortOrder'] = array('Ordre de tri', 'Par défaut, les commentaires sont triés en ordre croissant, en commençant par le plus ancien.');
$GLOBALS['TL_LANG']['tl_faq_category']['perPage'] = array('Commentaires par page', 'Nombre de commentaires par page. Mettre 0 pour désactiver la pagination.');
$GLOBALS['TL_LANG']['tl_faq_category']['moderate'] = array('Modérer les commentaires', 'Approuver les commentaires avant qu\'ils ne soient publiés sur le site.');
$GLOBALS['TL_LANG']['tl_faq_category']['bbcode'] = array('BBCode autorisé', 'Autoriser les visiteurs à formater leurs commentaires à l\'aide de BBCode.');
$GLOBALS['TL_LANG']['tl_faq_category']['requireLogin'] = array('Connexion préalable nécessaire', 'N\'autoriser que les utilisateurs authentifiés à créer des commentaires.');
$GLOBALS['TL_LANG']['tl_faq_category']['disableCaptcha'] = array('Désactiver la question de sécurité', 'N\'utiliser cette option que si vous avez restreint les commentaires aux utilisateurs habilités.');
$GLOBALS['TL_LANG']['tl_faq_category']['tstamp'] = array('Date de révision', 'Date et heure de la dernière révision');
$GLOBALS['TL_LANG']['tl_faq_category']['title_legend'] = 'Titre et redirection';
$GLOBALS['TL_LANG']['tl_faq_category']['comments_legend'] = 'Commentaires';
$GLOBALS['TL_LANG']['tl_faq_category']['notify_admin'] = 'Administrateur système';
$GLOBALS['TL_LANG']['tl_faq_category']['notify_author'] = 'Auteur de la FAQ';
$GLOBALS['TL_LANG']['tl_faq_category']['notify_both'] = 'Auteur et administrateur système';
$GLOBALS['TL_LANG']['tl_faq_category']['deleteConfirm'] = 'La suppression de la catégorie ID %s entraînera la suppression de toutes ses questions ! Voulez-vous vraiment continuer ?';
$GLOBALS['TL_LANG']['tl_faq_category']['new'] = array('Nouvelle catégorie', 'Créer une nouvelle catégorie');
$GLOBALS['TL_LANG']['tl_faq_category']['show'] = array('Détails de la catégorie', 'Afficher les détails de la catégorie ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['edit'] = array('Éditer la catégorie', 'Éditer la catégorie ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['editheader'] = array('Éditer les paramètres de la catégorie', 'Éditer les paramètres de la catégorie ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['copy'] = array('Dupliquer la catégorie', 'Dupliquer la catégorie ID %s');
$GLOBALS['TL_LANG']['tl_faq_category']['delete'] = array('Supprimer la catégorie', 'Supprimer la catégorie ID %s');

?>