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

$GLOBALS['TL_LANG']['ERR']['form'] = 'Le formulaire n\'a pas pu être envoyé';
$GLOBALS['TL_LANG']['ERR']['captcha'] = 'Veuillez répondre à la question de sécurité!';
$GLOBALS['TL_LANG']['SEC']['question1'] = 'Veuillez additionner %d et %d.';
$GLOBALS['TL_LANG']['SEC']['question2'] = 'Quelle est la somme de %d et %d?';
$GLOBALS['TL_LANG']['SEC']['question3'] = 'Veuillez calculer %d plus %d.';
$GLOBALS['TL_LANG']['CTE']['texts'] = 'Éléments texte';
$GLOBALS['TL_LANG']['CTE']['headline'] = array('Titre', 'Génère un titre (h1 - h6).');
$GLOBALS['TL_LANG']['CTE']['text'] = array('Texte', 'Génère un élément contenant du texte riche.');
$GLOBALS['TL_LANG']['CTE']['html'] = array('HTML', 'Permet d\'ajouter du code HTML personnalisé.');
$GLOBALS['TL_LANG']['CTE']['list'] = array('Liste', 'Génère une liste numérotée ou une liste simple.');
$GLOBALS['TL_LANG']['CTE']['table'] = array('Tableau', 'Génère un tableau qui peut-être trié.');
$GLOBALS['TL_LANG']['CTE']['accordion'] = array('Accordéon', 'Génère un élément accordéon "mootools".');
$GLOBALS['TL_LANG']['CTE']['code'] = array('Code', 'Ajoute la coloration syntaxique sur des extraits de code.');
$GLOBALS['TL_LANG']['CTE']['links'] = 'Éléments lien';
$GLOBALS['TL_LANG']['CTE']['hyperlink'] = array('Lien hypertexte', 'Génère un lien hypertexte vers un autre site internet.');
$GLOBALS['TL_LANG']['CTE']['toplink'] = array('Lien vers le haut de page', 'Génère un lien pour retourner en haut de page.');
$GLOBALS['TL_LANG']['CTE']['images'] = 'Éléments image';
$GLOBALS['TL_LANG']['CTE']['image'] = array('Image', 'Génère une image.');
$GLOBALS['TL_LANG']['CTE']['gallery'] = array('Galerie d\'images', 'Génère une galerie d\'image avec effet "lightbox".');
$GLOBALS['TL_LANG']['CTE']['files'] = 'Éléments fichier';
$GLOBALS['TL_LANG']['CTE']['download'] = array('Téléchargement', 'Génère un lien hypertexte vers un fichier à télécharger.');
$GLOBALS['TL_LANG']['CTE']['downloads'] = array('Téléchargements', 'Génère des liens multiples vers des fichiers à télécharger.');
$GLOBALS['TL_LANG']['CTE']['includes'] = 'Éléments inclus';
$GLOBALS['TL_LANG']['CTE']['article'] = array('Contenu d\'un article', 'Insère un article dans un autre article.');
$GLOBALS['TL_LANG']['CTE']['alias'] = array('Élément de contenu', 'Insère un élément de contenu existant.');
$GLOBALS['TL_LANG']['CTE']['form'] = array('Formulaire', 'Insère un formulaire.');
$GLOBALS['TL_LANG']['CTE']['module'] = array('Module', 'Insère un module.');
$GLOBALS['TL_LANG']['CTE']['teaser'] = array('Accroche d\'un article', 'Affiche l\'accroche d\'un article.');
$GLOBALS['TL_LANG']['MSC']['go'] = 'Go';
$GLOBALS['TL_LANG']['MSC']['quicknav'] = 'Navigation rapide';
$GLOBALS['TL_LANG']['MSC']['quicklink'] = 'Lien rapide';
$GLOBALS['TL_LANG']['MSC']['username'] = 'Identifiant';
$GLOBALS['TL_LANG']['MSC']['login'] = 'Se connecter';
$GLOBALS['TL_LANG']['MSC']['logout'] = 'Se déconnecter';
$GLOBALS['TL_LANG']['MSC']['loggedInAs'] = 'Vous êtes connecté en tant que %s.';
$GLOBALS['TL_LANG']['MSC']['emptyField'] = 'Veuillez saisir votre identifiant et votre mot de passe!';
$GLOBALS['TL_LANG']['MSC']['confirmation'] = 'Confirmation';
$GLOBALS['TL_LANG']['MSC']['sMatches'] = '%s occurrences pour %s';
$GLOBALS['TL_LANG']['MSC']['sEmpty'] = 'Aucune correspondance pour <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['sResults'] = 'Résultats %s - %s de %s pour <strong>%s</strong>';
$GLOBALS['TL_LANG']['MSC']['sNoResult'] = 'Votre recherche <strong>%s</strong> n\'a retourné aucun résultat.';
$GLOBALS['TL_LANG']['MSC']['seconds'] = 'secondes';
$GLOBALS['TL_LANG']['MSC']['up'] = 'En haut';
$GLOBALS['TL_LANG']['MSC']['first'] = '« Première';
$GLOBALS['TL_LANG']['MSC']['previous'] = 'Précédente';
$GLOBALS['TL_LANG']['MSC']['next'] = 'Suivante';
$GLOBALS['TL_LANG']['MSC']['last'] = 'Dernière »';
$GLOBALS['TL_LANG']['MSC']['goToPage'] = 'Aller à la page %s';
$GLOBALS['TL_LANG']['MSC']['totalPages'] = 'Page %s de %s';
$GLOBALS['TL_LANG']['MSC']['fileUploaded'] = 'Le fichier %s a été transféré avec succès';
$GLOBALS['TL_LANG']['MSC']['fileExceeds'] = 'L\'image %s a été envoyée avec succès, mais elle est trop grande pour être redimensionnée automatiquement.';
$GLOBALS['TL_LANG']['MSC']['fileResized'] = 'Le fichier % s a été transféré et réduit aux dimensions maximales';
$GLOBALS['TL_LANG']['MSC']['searchLabel'] = 'Rechercher';
$GLOBALS['TL_LANG']['MSC']['keywords'] = 'Mots clés';
$GLOBALS['TL_LANG']['MSC']['options'] = 'Options';
$GLOBALS['TL_LANG']['MSC']['matchAll'] = 'tous les mots';
$GLOBALS['TL_LANG']['MSC']['matchAny'] = 'chaque mot';
$GLOBALS['TL_LANG']['MSC']['saveData'] = 'Sauvegarder les données';
$GLOBALS['TL_LANG']['MSC']['printPage'] = 'Imprimer cette page';
$GLOBALS['TL_LANG']['MSC']['printAsPdf'] = 'Imprimer l\'article en PDF';
$GLOBALS['TL_LANG']['MSC']['facebookShare'] = 'Partager sur Facebook';
$GLOBALS['TL_LANG']['MSC']['twitterShare'] = 'Partager sur Twitter';
$GLOBALS['TL_LANG']['MSC']['pleaseWait'] = 'Veuillez patienter';
$GLOBALS['TL_LANG']['MSC']['loading'] = 'Chargement...';
$GLOBALS['TL_LANG']['MSC']['more'] = 'En savoir plus...';
$GLOBALS['TL_LANG']['MSC']['readMore'] = 'Lire l\'article: %s';
$GLOBALS['TL_LANG']['MSC']['targetPage'] = 'Page cible';
$GLOBALS['TL_LANG']['MSC']['com_name'] = 'Nom';
$GLOBALS['TL_LANG']['MSC']['com_email'] = 'E-mail (non publié)';
$GLOBALS['TL_LANG']['MSC']['com_website'] = 'Site internet';
$GLOBALS['TL_LANG']['MSC']['com_comment'] = 'Commentaire';
$GLOBALS['TL_LANG']['MSC']['com_submit'] = 'Envoyer le commentaire';
$GLOBALS['TL_LANG']['MSC']['comment_by'] = 'Commentaire de';
$GLOBALS['TL_LANG']['MSC']['reply_by'] = 'Réponse';
$GLOBALS['TL_LANG']['MSC']['com_quote'] = '%s a écrit :';
$GLOBALS['TL_LANG']['MSC']['com_code'] = 'Code :';
$GLOBALS['TL_LANG']['MSC']['com_subject'] = 'Contao :: nouveau commentaire sur %s';
$GLOBALS['TL_LANG']['MSC']['com_message'] = "% s a créé un nouveau commentaire sur votre site web. \n\n---\n\n %s \n\n---\n\n Voir: %s\n Éditer: %s\n\n Si vous modérez vos commentaires, vous devez ouvrir une session dans le back office pour publier le commentaire.";
$GLOBALS['TL_LANG']['MSC']['com_confirm'] = 'Votre commentaire a été ajouté et est maintenant en attente d\'approbation.';
$GLOBALS['TL_LANG']['MSC']['invalidPage'] = 'Désolé, la page "%s" n\'existe pas.';
$GLOBALS['TL_LANG']['MSC']['list_orderBy'] = 'Trier par %s';
$GLOBALS['TL_LANG']['MSC']['list_perPage'] = 'Résultats par page';
$GLOBALS['TL_LANG']['MSC']['published'] = 'Publié';
$GLOBALS['TL_LANG']['MSC']['unpublished'] = 'Non publié';
$GLOBALS['TL_LANG']['MSC']['addComment'] = 'Ajouter un commentaire';
$GLOBALS['TL_LANG']['MSC']['autologin'] = 'Rester connecté';
$GLOBALS['TL_LANG']['MSC']['relevance'] = '%s pertinent';

?>