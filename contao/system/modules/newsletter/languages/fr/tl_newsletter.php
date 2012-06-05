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

$GLOBALS['TL_LANG']['tl_newsletter']['subject'] = array('Sujet', 'Saisir le titre du bulletin d\'information.');
$GLOBALS['TL_LANG']['tl_newsletter']['alias'] = array('Alias du bulletin d\'information', 'L\'alias du bulletin d\'information est une référence unique qui remplace l\'ID du bulletin d\'information.');
$GLOBALS['TL_LANG']['tl_newsletter']['content'] = array('Contenu HTML', 'Saisir le contenu HTML du bulletin d\'information. Utiliser le joker <em>##email##</em> pour insérer l\'adresse e-mail du destinataire.');
$GLOBALS['TL_LANG']['tl_newsletter']['text'] = array('Contenu texte', 'Saisir le contenu texte du bulletin d\'information. Utiliser le joker <em>##email##</em> pour insérer l\'adresse e-mail du destinataire.');
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'] = array('Ajout de fichier joint', 'Attacher un ou plusieurs fichiers avec le bulletin d\'information.');
$GLOBALS['TL_LANG']['tl_newsletter']['files'] = array('Fichiers joints', 'Choisir les fichiers à attacher au bulletin d\'information.');
$GLOBALS['TL_LANG']['tl_newsletter']['template'] = array('Modèle d\'e-mail', 'Choisir un modèle d\'e-mail.');
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'] = array('Envoyer comme texte brut', 'Envoyer le bulletin d\'information en texte brut, sans le contenu HTML.');
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'] = array('Images externes', 'Ne pas inclure des images dans les bulletins HTML.');
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'] = array('Nom de l\'expéditeur', 'Saisir le nom de l\'expéditeur.');
$GLOBALS['TL_LANG']['tl_newsletter']['sender'] = array('Adresse de l\'expéditeur', 'Saisir l\'adresse e-mail de l\'expéditeur.');
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'] = array('Nombre d\'e-mails par cycle', 'Afin de protéger le script de coupures intempestives, le processus d\'envoi est décomposé en plusieurs cycles.');
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'] = array('Délai en secondes', 'Modifier le délai entre chaque cycle d\'envoi, afin de contrôler le nombre d\'e-mails envoyés à la minute.');
$GLOBALS['TL_LANG']['tl_newsletter']['start'] = array('Décalage', 'Dans le cas où le processus d\'envoi est interrompu, saisir une valeur numérique de décalage pour continuer avec un destinataire particulier. Vous pouvez vérifier le nombre de mails qui ont été envoyés dans le fichier <em>/system/logs/log newsletter_*.log</em>. Par exemple, si 120 mails ont été envoyés, saisir "120" pour continuer avec le 121 ème destinataire (le compteur commançant à 0).');
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'] = array('Envoyer un aperçu', 'Envoyer un aperçu du bulletin d\'information à cette adresse e-mail.');
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = 'Titre et sujet';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'Contenu HTML';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'Contenu texte';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = 'Fichiers attachés';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'Paramètres des modèles';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = 'Paramètres avancés';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = 'Envoyé';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = 'Envoyé le %s';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = 'Pas encore envoyé';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'Date du mailing';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'Le bulletin d\'information a été envoyé à %s destinataires.';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%s adresse(s) e-mail(s) non valide(s) a/ont été désactivée(s).';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'Il n\'y a pas d\'abonnés actifs dans cette liste de diffusion.';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = 'De';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = 'Fichiers joints';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'Envoyer un aperçu';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'Voulez-vous vraiment envoyer le bulletin d\'information ?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'] = array('Nouveau bulletin', 'Créer un nouveau bulletin d\'information.');
$GLOBALS['TL_LANG']['tl_newsletter']['show'] = array('Détails du bulletin', 'Afficher les détails du bulletin d\'information ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['edit'] = array('Éditer le bulletin', 'Éditer le bulletin d\'information ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['copy'] = array('Dupliquer le bulletin ID %s', 'Dupliquer le bulletin d\'information ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['cut'] = array('Déplacer le bulletin', 'Déplacer le bulletin d\'information ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['delete'] = array('Supprimer le bulletin ID %s', 'Supprimer le bulletin d\'information ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'] = array('Éditer la liste de diffusion', 'Éditer les paramètres de la liste de diffusion');
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'] = array('Coller dans cette liste de diffusion', 'Coller après le bulletin ID %s');
$GLOBALS['TL_LANG']['tl_newsletter']['send'] = array('Envoyer le bulletin', 'Envoyer le bulletin d\'information ID %s');

?>