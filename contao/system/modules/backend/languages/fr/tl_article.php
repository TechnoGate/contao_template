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

$GLOBALS['TL_LANG']['tl_article']['title'] = array('Titre', 'Saisir le titre de l\'article.');
$GLOBALS['TL_LANG']['tl_article']['alias'] = array('Alias de l\'article', 'L\'alias de l\'article est une référence unique qui remplace l\'ID de l\'article.');
$GLOBALS['TL_LANG']['tl_article']['author'] = array('Auteur', 'Saisir le nom de l\'auteur.');
$GLOBALS['TL_LANG']['tl_article']['inColumn'] = array('Afficher dans la zone', 'Choisir la zone dans laquelle vous voulez que l\'article s\'affiche.');
$GLOBALS['TL_LANG']['tl_article']['keywords'] = array('Mots clés', 'Saisir des mots-clés séparés par des virgules. Ils seront utilisés par les moteurs de recherche, tel que Google ou Yahoo. Les moteurs de recherche utilisent généralement jusqu\'à 800 caractères.');
$GLOBALS['TL_LANG']['tl_article']['teaserCssID'] = array('ID / classe(s) CSS de l\'accroche', 'Saisir un ID de feuille de style et/ou une ou plusieurs classes CSS pour l\'accroche.');
$GLOBALS['TL_LANG']['tl_article']['showTeaser'] = array('Afficher l\'accroche', 'Afficher le texte de l\'accroche s\'il y a plusieurs articles.');
$GLOBALS['TL_LANG']['tl_article']['teaser'] = array('Accroche', 'Le texte de l\'accroche peut être affiché avec l\'élément de contenu "accroche d\'article".');
$GLOBALS['TL_LANG']['tl_article']['printable'] = array('Imprimable', 'Ajouter un lien pour exporter l\'article dans un fichier PDF.');
$GLOBALS['TL_LANG']['tl_article']['cssID'] = array('ID / classe(s) CSS', 'Saisir un ID de feuille de style et/ou une ou plusieurs classes CSS.');
$GLOBALS['TL_LANG']['tl_article']['space'] = array('Espace avant et après', 'Saisir l\'espace avant et après l\'article en pixel.');
$GLOBALS['TL_LANG']['tl_article']['published'] = array('Publier l\'article', 'Rendre l\'article visible aux visiteurs de votre site.');
$GLOBALS['TL_LANG']['tl_article']['start'] = array('Afficher à partir du', 'Ne plus afficher cet article sur le site avant ce jour.');
$GLOBALS['TL_LANG']['tl_article']['stop'] = array('Afficher jusqu\'au', 'Ne plus afficher cet article sur le site après ce jour.');
$GLOBALS['TL_LANG']['tl_article']['tstamp'] = array('Date de révision', 'Date et heure de la dernière révision.');
$GLOBALS['TL_LANG']['tl_article']['title_legend'] = 'Titre et auteur';
$GLOBALS['TL_LANG']['tl_article']['layout_legend'] = 'Zone et mots clés';
$GLOBALS['TL_LANG']['tl_article']['teaser_legend'] = 'Accroche de l\'article';
$GLOBALS['TL_LANG']['tl_article']['expert_legend'] = 'Paramètres avancés';
$GLOBALS['TL_LANG']['tl_article']['publish_legend'] = 'Paramètres de publication';
$GLOBALS['TL_LANG']['tl_article']['print'] = 'Imprimer la page';
$GLOBALS['TL_LANG']['tl_article']['pdf'] = 'Exporter en PDF';
$GLOBALS['TL_LANG']['tl_article']['facebook'] = 'Partager sur Facebook';
$GLOBALS['TL_LANG']['tl_article']['twitter'] = 'Partager sur Twitter';
$GLOBALS['TL_LANG']['tl_article']['header'] = 'Entête de page';
$GLOBALS['TL_LANG']['tl_article']['left'] = 'Colonne de gauche';
$GLOBALS['TL_LANG']['tl_article']['main'] = 'Colonne principale';
$GLOBALS['TL_LANG']['tl_article']['right'] = 'Colonne de droite';
$GLOBALS['TL_LANG']['tl_article']['footer'] = 'Pied de page';
$GLOBALS['TL_LANG']['tl_article']['new'] = array('Nouvel article', 'Créer un nouvel article');
$GLOBALS['TL_LANG']['tl_article']['show'] = array('Détails', 'Afficher les détails de l\'article ID %s');
$GLOBALS['TL_LANG']['tl_article']['edit'] = array('Éditer l\'article', 'Éditer l\'article ID %s');
$GLOBALS['TL_LANG']['tl_article']['editheader'] = array('Éditer les paramètre de l\'article', 'Éditer les paramètre de l\'article ID %s');
$GLOBALS['TL_LANG']['tl_article']['copy'] = array('Dupliquer l\'article', 'Dupliquer l\'article ID %s');
$GLOBALS['TL_LANG']['tl_article']['cut'] = array('Déplacer l\'article', 'Déplacer l\'article ID %s');
$GLOBALS['TL_LANG']['tl_article']['delete'] = array('Supprimer l\'article', 'Supprimer l\'article ID %s');
$GLOBALS['TL_LANG']['tl_article']['toggle'] = array('Publier/dé-publier l\'article', 'Publier/dé-publier l\'article ID %s');
$GLOBALS['TL_LANG']['tl_article']['pasteafter'] = array('Coller après', 'Coller après l\'article ID %s');
$GLOBALS['TL_LANG']['tl_article']['pasteinto'] = array('Coller dedans', 'Coller dedans la page ID %s');

?>