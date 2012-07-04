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

$GLOBALS['TL_LANG']['tl_page']['title'] = array('Nom de la page', 'Saisir le nom de la page.');
$GLOBALS['TL_LANG']['tl_page']['alias'] = array('Alias de la page', 'Un alias de page est une référence unique qui remplace l\'ID numérique de la page.');
$GLOBALS['TL_LANG']['tl_page']['type'] = array('Type de page', 'Choisir le type de page.');
$GLOBALS['TL_LANG']['tl_page']['pageTitle'] = array('Titre de la page', 'Saisir le titre de la page.');
$GLOBALS['TL_LANG']['tl_page']['language'] = array('Langue', 'Saisir la langue de la page selon la norme ISO-639-1 (ex: "en" pour anglais).');
$GLOBALS['TL_LANG']['tl_page']['robots'] = array('Balises Robots', 'Définir la façon dont les moteurs de recherche traiteront la page.');
$GLOBALS['TL_LANG']['tl_page']['description'] = array('Description de la page', 'Saisir une courte description de la page qui sera utilisée par les moteurs de recherche tels que Google ou Yahoo. Généralement entre 150 et 300 caractères.');
$GLOBALS['TL_LANG']['tl_page']['redirect'] = array('Type de redirection', 'Choisir un type de redirection.');
$GLOBALS['TL_LANG']['tl_page']['jumpTo'] = array('Aller à', 'Choisir la page vers laquelle les visiteurs seront redirigés. Laissez vide pour rediriger vers la première sous-page simple.');
$GLOBALS['TL_LANG']['tl_page']['fallback'] = array('Langue de secours', 'Afficher la page dans cette langue si la page n\'est pas disponible dans la langue du visiteur.');
$GLOBALS['TL_LANG']['tl_page']['dns'] = array('Nom de domaine', 'Restreindre l\'accès au site à un nom de domaine.');
$GLOBALS['TL_LANG']['tl_page']['adminEmail'] = array('Adresse e-mail de l\'administrateur du site', 'Les messages générés automatiquement (ex: les e-mails de confirmation d\'inscription) seront envoyés à cette adresse.');
$GLOBALS['TL_LANG']['tl_page']['dateFormat'] = array('Format de la date', 'Le format de la date sera traité par la fonction PHP date().');
$GLOBALS['TL_LANG']['tl_page']['timeFormat'] = array('Format de l\'heure', 'Le format de l\'heure sera traité par la fonction PHP date().');
$GLOBALS['TL_LANG']['tl_page']['datimFormat'] = array('Format de la date et de l\'heure', 'Le format de la date et de l\'heure sera traité par la fonction PHP date().');
$GLOBALS['TL_LANG']['tl_page']['createSitemap'] = array('Créer un plan de site XML Google', 'Créer un plan de site XML Google dans le répertoire racine du site.');
$GLOBALS['TL_LANG']['tl_page']['sitemapName'] = array('Nom de fichier du plan de site', 'Saisir un nom pour le fichier (sans extension).');
$GLOBALS['TL_LANG']['tl_page']['useSSL'] = array('Utiliser HTTPS dans les sitemaps', 'Générer les URLs des sitemaps du site internet avec <em>https://</em>.');
$GLOBALS['TL_LANG']['tl_page']['autoforward'] = array('Aller vers une autre page', 'Rediriger les visiteurs vers une autre page (ex: une page de connexion).');
$GLOBALS['TL_LANG']['tl_page']['protected'] = array('Page protégée', 'Limiter l\'accès de cette page à certains groupes de membres.');
$GLOBALS['TL_LANG']['tl_page']['groups'] = array('Groupes de membres autorisés', 'Ces groupes seront en mesure d\'accéder à la page.');
$GLOBALS['TL_LANG']['tl_page']['includeLayout'] = array('Attribuer une présentation', 'Attribuer une présentation de page à la page et ses sous-pages.');
$GLOBALS['TL_LANG']['tl_page']['layout'] = array('Présentation de la page', 'Vous pouvez gérer les présentations avec le module <em>Présentations de page</em>.');
$GLOBALS['TL_LANG']['tl_page']['includeCache'] = array('Mise en cache', 'Autoriser la mise en cache de la page et ses sous-pages.');
$GLOBALS['TL_LANG']['tl_page']['cache'] = array('Durée de vie du cache', 'Après cette période, la version mise en cache de la page expirera.');
$GLOBALS['TL_LANG']['tl_page']['includeChmod'] = array('Attribution des droits d\'accès', 'Les droits d\'accès déterminent ce que les utilisateurs peuvent faire avec la page.');
$GLOBALS['TL_LANG']['tl_page']['cuser'] = array('Propriétaire', 'Sélectionner un utilisateur qui sera propriétaire de la page.');
$GLOBALS['TL_LANG']['tl_page']['cgroup'] = array('Groupe', 'Sélectionner un groupe qui sera propriétaire de la page.');
$GLOBALS['TL_LANG']['tl_page']['chmod'] = array('Droits d\'accès', 'Attribuer les droits d\'accès pour la page et ses sous-pages.');
$GLOBALS['TL_LANG']['tl_page']['noSearch'] = array('Ne pas rechercher cette page', 'Exclure la page de l\'index de recherche du site.');
$GLOBALS['TL_LANG']['tl_page']['cssClass'] = array('Classe(s) CSS', 'La (les) classe(s) seront utilisées dans le menu de navigation et la balise body.');
$GLOBALS['TL_LANG']['tl_page']['sitemap'] = array('Afficher dans le plan de site', 'Définir si la page est affichée dans le plan de site.');
$GLOBALS['TL_LANG']['tl_page']['hide'] = array('Ne pas afficher dans la navigation', 'Masquer la page dans le menu de navigation.');
$GLOBALS['TL_LANG']['tl_page']['guests'] = array('Montrer aux invités seulement', 'Masquer la page si un membre est connecté.');
$GLOBALS['TL_LANG']['tl_page']['tabindex'] = array('Ordre des tabulations', 'La position de l\'élément de navigation dans l\'ordre des tabulations.');
$GLOBALS['TL_LANG']['tl_page']['accesskey'] = array('Touche d\'accès', 'Un élément de navigation peut prendre le focus en appuyant sur la touche [ALT] ou [CTRL] enfoncée et la touche d\'accès simultanément.');
$GLOBALS['TL_LANG']['tl_page']['published'] = array('Publier la page', 'Rendre la page visible aux visiteurs de votre site.');
$GLOBALS['TL_LANG']['tl_page']['start'] = array('Afficher à partir du', 'Ne pas afficher la page sur le site avant ce jour.');
$GLOBALS['TL_LANG']['tl_page']['stop'] = array('Afficher jusqu\'au', 'Ne plus afficher la page sur le site après ce jour.');
$GLOBALS['TL_LANG']['tl_page']['title_legend'] = 'Nom et type';
$GLOBALS['TL_LANG']['tl_page']['meta_legend'] = 'Méta informations';
$GLOBALS['TL_LANG']['tl_page']['system_legend'] = 'Paramètres du système';
$GLOBALS['TL_LANG']['tl_page']['redirect_legend'] = 'Paramètres de redirection';
$GLOBALS['TL_LANG']['tl_page']['dns_legend'] = 'Paramètres DNS';
$GLOBALS['TL_LANG']['tl_page']['sitemap_legend'] = 'Plan de site XML';
$GLOBALS['TL_LANG']['tl_page']['forward_legend'] = 'Redirection automatique';
$GLOBALS['TL_LANG']['tl_page']['protected_legend'] = 'Protection d\'accès';
$GLOBALS['TL_LANG']['tl_page']['layout_legend'] = 'Paramètres de présentation';
$GLOBALS['TL_LANG']['tl_page']['cache_legend'] = 'Paramètres du cache';
$GLOBALS['TL_LANG']['tl_page']['chmod_legend'] = 'Droits d\'accès';
$GLOBALS['TL_LANG']['tl_page']['search_legend'] = 'Paramètres de la recherche';
$GLOBALS['TL_LANG']['tl_page']['expert_legend'] = 'Paramètres avancés';
$GLOBALS['TL_LANG']['tl_page']['tabnav_legend'] = 'Navigation au clavier';
$GLOBALS['TL_LANG']['tl_page']['publish_legend'] = 'Paramètres de publication';
$GLOBALS['TL_LANG']['tl_page']['permanent'] = 'Redirection permanente (301)';
$GLOBALS['TL_LANG']['tl_page']['temporary'] = 'Redirection temporaire (302)';
$GLOBALS['TL_LANG']['tl_page']['map_default'] = 'Défaut';
$GLOBALS['TL_LANG']['tl_page']['map_always'] = 'Toujours afficher';
$GLOBALS['TL_LANG']['tl_page']['map_never'] = 'Ne jamais afficher';
$GLOBALS['TL_LANG']['tl_page']['new'] = array('Nouvelle page', 'Créer une nouvelle page');
$GLOBALS['TL_LANG']['tl_page']['show'] = array('Détails de la page', 'Afficher les détails de la page ID %s');
$GLOBALS['TL_LANG']['tl_page']['edit'] = array('Éditer la page', 'Éditer la page ID %s');
$GLOBALS['TL_LANG']['tl_page']['cut'] = array('Déplacer la page', 'Déplacer la page ID %s');
$GLOBALS['TL_LANG']['tl_page']['copy'] = array('Dupliquer la page', 'Dupliquer la page ID %s');
$GLOBALS['TL_LANG']['tl_page']['copyChilds'] = array('Dupliquer la page avec ses sous-pages', 'Dupliquer la page ID %s avec ses sous-pages');
$GLOBALS['TL_LANG']['tl_page']['delete'] = array('Supprimer la page', 'Supprimer la page ID %s');
$GLOBALS['TL_LANG']['tl_page']['toggle'] = array('Publier / dé-publier la page', 'Publier / dé-publier la page ID %s');
$GLOBALS['TL_LANG']['tl_page']['pasteafter'] = array('Coller après', 'Coller après la page ID %s');
$GLOBALS['TL_LANG']['tl_page']['pasteinto'] = array('Coller dedans', 'Coller dans la page ID %s');
$GLOBALS['TL_LANG']['tl_page']['articles'] = array('Éditer les articles', 'Éditer les articles de la page ID %s');
$GLOBALS['TL_LANG']['CACHE'][0] = 'Ne pas mettre en cache';
$GLOBALS['TL_LANG']['CACHE'][5] = '5 secondes';
$GLOBALS['TL_LANG']['CACHE'][15] = '15 secondes';
$GLOBALS['TL_LANG']['CACHE'][30] = '30 secondes';
$GLOBALS['TL_LANG']['CACHE'][60] = '1 minute';
$GLOBALS['TL_LANG']['CACHE'][300] = '5 minutes';
$GLOBALS['TL_LANG']['CACHE'][900] = '15 minutes';
$GLOBALS['TL_LANG']['CACHE'][1800] = '30 minutes';
$GLOBALS['TL_LANG']['CACHE'][3600] = '60 minutes';
$GLOBALS['TL_LANG']['CACHE'][10800] = '3 heures';
$GLOBALS['TL_LANG']['CACHE'][21600] = '6 heures';
$GLOBALS['TL_LANG']['CACHE'][43200] = '12 heures';
$GLOBALS['TL_LANG']['CACHE'][86400] = '24 heures';
$GLOBALS['TL_LANG']['CACHE'][259200] = '3 jours';
$GLOBALS['TL_LANG']['CACHE'][604800] = '7 jours';
$GLOBALS['TL_LANG']['CACHE'][2592000] = '30 jours';

?>