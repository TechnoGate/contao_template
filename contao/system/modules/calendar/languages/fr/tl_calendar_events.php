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

$GLOBALS['TL_LANG']['tl_calendar_events']['title'] = array('Titre', 'Saisir le titre de l\'événement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['alias'] = array('Alias de l\'événement', 'L\'alias est une référence unique qui peut s\'utiliser à la place de l\'ID numérique de l\'événement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['author'] = array('Auteur', 'Vous pouvez changer le nom de l\'auteur de l\'événement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addTime'] = array('Ajouter l\'heure', 'Ajouter une heure de début et de fin pour l\'événement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['startTime'] = array('Heure de début', 'Saisir l\'heure de début de l\'événement selon le format de date global.');
$GLOBALS['TL_LANG']['tl_calendar_events']['endTime'] = array('Heure de fin', 'Utiliser la même valeur pour l\'heure de début et de fin crée un événement sans fin.');
$GLOBALS['TL_LANG']['tl_calendar_events']['startDate'] = array('Date de début', 'Saisir la date de début de l\'événement selon le format de date global.');
$GLOBALS['TL_LANG']['tl_calendar_events']['endDate'] = array('Date de fin', 'Laisser vide pour créer un événement d\'une seule journée.');
$GLOBALS['TL_LANG']['tl_calendar_events']['teaser'] = array('Accroche de l\'événement', 'Dans une liste d\'événements, l\'accroche sera affiché à la place du texte de l\'événement. Un lien "En savoir plus..." sera ajouté automatiquement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['details'] = array('Texte de l\'événement', 'Saisir le texte de l\'événement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addImage'] = array('Ajouter une image', 'Ajouter une image à l\'événement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['recurring'] = array('Répéter l\'événement', 'Créer un événement récurrent.');
$GLOBALS['TL_LANG']['tl_calendar_events']['repeatEach'] = array('Intervalle', 'Définir l\'intervalle entre chaque répétition.');
$GLOBALS['TL_LANG']['tl_calendar_events']['recurrences'] = array('Répétitions', 'Mettre à 0 pour un nombre illimité de répétitions.');
$GLOBALS['TL_LANG']['tl_calendar_events']['addEnclosure'] = array('Joindre des fichiers', 'Ajouter un ou plusieurs fichiers à cet événement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['enclosure'] = array('Fichiers', 'Choisir les fichiers que vous voulez joindre.');
$GLOBALS['TL_LANG']['tl_calendar_events']['source'] = array('Page de redirection', 'Vous pouvez remplacer la cible de redirection par défaut par un lien vers une page interne ou externe.');
$GLOBALS['TL_LANG']['tl_calendar_events']['default'] = array('Utiliser la page par défaut', 'En cliquant sur le lien "En savoir plus..." les visiteurs seront redirigés vers la page par défaut du calendrier.');
$GLOBALS['TL_LANG']['tl_calendar_events']['internal'] = array('Page', 'En cliquant sur le lien "En savoir plus..." les visiteurs seront redirigés vers une page interne.');
$GLOBALS['TL_LANG']['tl_calendar_events']['article'] = array('Article', 'En cliquant sur le lien "En savoir plus..." les visiteurs seront redirigés vers un article.');
$GLOBALS['TL_LANG']['tl_calendar_events']['external'] = array('URL externe', 'En cliquant sur le lien "En savoir plus..." les visiteurs seront redirigés vers un site externe.');
$GLOBALS['TL_LANG']['tl_calendar_events']['jumpTo'] = array('Page de redirection', 'Sélectionner la page vers laquelle les visiteurs seront redirigés lorsqu\'ils cliqueront sur l\'événement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['articleId'] = array('Article', 'Choisir l\'article sur lequel les visiteurs seront redirigés lorsqu’ils cliqueront sur l\'événement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['cssClass'] = array('Classe CSS', 'Saisir une ou plusieurs classes CSS.');
$GLOBALS['TL_LANG']['tl_calendar_events']['noComments'] = array('Désactiver les commentaires', 'Ne pas autoriser les commentaires pour cet événement.');
$GLOBALS['TL_LANG']['tl_calendar_events']['published'] = array('Publier l\'événement', 'Rendre l\'événement visible sur le site.');
$GLOBALS['TL_LANG']['tl_calendar_events']['start'] = array('Afficher à partir du', 'Ne plus afficher cet événement sur le site avant ce jour.');
$GLOBALS['TL_LANG']['tl_calendar_events']['stop'] = array('Afficher jusqu\'au', 'Ne plus afficher cet événement sur le site après ce jour.');
$GLOBALS['TL_LANG']['tl_calendar_events']['title_legend'] = 'Titre et auteur';
$GLOBALS['TL_LANG']['tl_calendar_events']['date_legend'] = 'Date et heure';
$GLOBALS['TL_LANG']['tl_calendar_events']['teaser_legend'] = 'Accroche';
$GLOBALS['TL_LANG']['tl_calendar_events']['text_legend'] = 'Texte de l\'événement';
$GLOBALS['TL_LANG']['tl_calendar_events']['image_legend'] = 'Paramètres de l\'image';
$GLOBALS['TL_LANG']['tl_calendar_events']['recurring_legend'] = 'Paramètres de récurrence';
$GLOBALS['TL_LANG']['tl_calendar_events']['enclosure_legend'] = 'Fichiers joints';
$GLOBALS['TL_LANG']['tl_calendar_events']['source_legend'] = 'Cible de redirection';
$GLOBALS['TL_LANG']['tl_calendar_events']['expert_legend'] = 'Paramètres avancés';
$GLOBALS['TL_LANG']['tl_calendar_events']['publish_legend'] = 'Paramètres de publication';
$GLOBALS['TL_LANG']['tl_calendar_events']['days'] = 'jour(s)';
$GLOBALS['TL_LANG']['tl_calendar_events']['weeks'] = 'semaine(s)';
$GLOBALS['TL_LANG']['tl_calendar_events']['months'] = 'mois';
$GLOBALS['TL_LANG']['tl_calendar_events']['years'] = 'année(s)';
$GLOBALS['TL_LANG']['tl_calendar_events']['new'] = array('Nouvel événement', 'Créer un nouvel événement');
$GLOBALS['TL_LANG']['tl_calendar_events']['show'] = array('Détails de l\'événement', 'Afficher les détails de l\'événement ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['edit'] = array('Éditer l\'événement', 'Éditer l\'événement ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['copy'] = array('Dupliquer l\'événement', 'Dupliquer l\'événement ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['cut'] = array('Déplacer l\'événement', 'Déplacer l\'événement ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['delete'] = array('Supprimer l\'événement', 'Supprimer l\'événement ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['toggle'] = array('Publier / dé-publier l\'évènement', 'Publier / dé-publier l\'évènement ID %s');
$GLOBALS['TL_LANG']['tl_calendar_events']['editheader'] = array('Éditer le calendrier', 'Éditer les paramètres du calendrier');
$GLOBALS['TL_LANG']['tl_calendar_events']['pasteafter'] = array('Coller dans ce calendrier', 'Coller après l\'évènement ID %s');

?>