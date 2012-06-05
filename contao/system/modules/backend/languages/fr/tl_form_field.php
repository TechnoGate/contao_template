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

$GLOBALS['TL_LANG']['FFL']['headline'] = array('Titre', 'Champ personnalisé pour insérer un titre de section.');
$GLOBALS['TL_LANG']['FFL']['explanation'] = array('Explication', 'Champ personnalisé pour insérer un texte d\'explication.');
$GLOBALS['TL_LANG']['FFL']['html'] = array('Code HTML', 'Champ personnalisé pour insérer du code HTML.');
$GLOBALS['TL_LANG']['FFL']['fieldset'] = array('Groupe de champs', 'Un conteneur pour grouper les champs, avec une légende facultative.');
$GLOBALS['TL_LANG']['FFL']['text'] = array('Champ texte', 'Champ d\'une ligne pour la saisie d\'un texte court ou moyen.');
$GLOBALS['TL_LANG']['FFL']['password'] = array('Champ "mot de passe"', 'Champ d\'une ligne pour la saisie d\'un mot de passe . Contao ajoute automatiquement un champ de confirmation.');
$GLOBALS['TL_LANG']['FFL']['textarea'] = array('Zone de texte', 'Champ comportant plusieurs lignes pour la saisie d\'un texte moyen ou long.');
$GLOBALS['TL_LANG']['FFL']['select'] = array('Liste déroulante', 'Liste déroulante pour la sélection d\'un ou plusieurs choix.');
$GLOBALS['TL_LANG']['FFL']['radio'] = array('Bouton radio', 'Liste de plusieurs options dont une seule peut être sélectionnée.');
$GLOBALS['TL_LANG']['FFL']['checkbox'] = array('Case à cocher', 'Liste de plusieurs options dont chacune peut être sélectionnée.');
$GLOBALS['TL_LANG']['FFL']['upload'] = array('Envoi de fichier', 'Champ d\'une ligne pour l\'envoi d\'un fichier local vers le serveur.');
$GLOBALS['TL_LANG']['FFL']['hidden'] = array('Champ caché', 'Champ d\'une ligne non visible dans le formulaire.');
$GLOBALS['TL_LANG']['FFL']['captcha'] = array('Question de sécurité', 'Question simple d\'arithmétique pour vérifier que le formulaire est bien soumis par un individu (CAPTCHA).');
$GLOBALS['TL_LANG']['FFL']['submit'] = array('Bouton d\'envoi', 'Bouton d\'envoi du formulaire.');
$GLOBALS['TL_LANG']['tl_form_field']['type'] = array('Type de champ', 'Choisir le type du champ.');
$GLOBALS['TL_LANG']['tl_form_field']['name'] = array('Nom du champ', 'Le nom de champ est un nom unique qui sert à identifier le champ.');
$GLOBALS['TL_LANG']['tl_form_field']['label'] = array('Intitulé du champ', 'L\'intitulé du champ sera visible sur le site, en général devant ou au-dessus du champ.');
$GLOBALS['TL_LANG']['tl_form_field']['text'] = array('Texte', 'Vous pouvez utiliser des balises HTML pour formater le texte.');
$GLOBALS['TL_LANG']['tl_form_field']['html'] = array('HTML', 'Vous pouvez modifier la liste des balises HTML autorisées dans la configuration du back-office.');
$GLOBALS['TL_LANG']['tl_form_field']['options'] = array('Options', 'Si JavaScript est désactivé, pensez à sauvegarder vos modifications avant de changer l\'ordre.');
$GLOBALS['TL_LANG']['tl_form_field']['mandatory'] = array('Champ obligatoire', 'Le formulaire ne pourra être soumis si le champ est vide.');
$GLOBALS['TL_LANG']['tl_form_field']['rgxp'] = array('Validation de la saisie', 'Contrôle de la saisie par rapport à une expression régulière.');
$GLOBALS['TL_LANG']['tl_form_field']['digit'] = array('Caractères numériques', 'Autorise les caractères numériques, le signe moins (-), le point (.) et l\'espace ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['alpha'] = array('Caractères alphabétiques', 'Autorise les caractères alphabétiques, le signe moins (-), le point (.) et l\'espace ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['alnum'] = array('Caractères alphanumériques', 'Autorise les caractères alphabétiques et numériques, le signe moins (-), le point (.) et l\'espace ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['extnd'] = array('Caractères alphanumériques étendus', 'Autorise tout caractère à l\'exception des caractères spéciaux habituellement utilisés pour des raisons de sécurité (#/()).');
$GLOBALS['TL_LANG']['tl_form_field']['date'] = array('Date', 'Contrôle si la saisie correspond au format global de date.');
$GLOBALS['TL_LANG']['tl_form_field']['time'] = array('Heure', 'Contrôle si la saisie correspond au format global d\'heure.');
$GLOBALS['TL_LANG']['tl_form_field']['datim'] = array('Date et heure', 'Contrôle si la saisie correspond au format global de date et heure.');
$GLOBALS['TL_LANG']['tl_form_field']['phone'] = array('Numéro de téléphone', 'Autorise les caractères numériques, le plus (+), le moins (-), la barre oblique (/), les parenthèses () et l\'espace ( ).');
$GLOBALS['TL_LANG']['tl_form_field']['email'] = array('Adresse e-mail', 'Contrôle si la saisie est une adresse e-mail valide.');
$GLOBALS['TL_LANG']['tl_form_field']['url'] = array('Format d\'URL', 'Contrôle si la saisie est une adresse URL valide.');
$GLOBALS['TL_LANG']['tl_form_field']['placeholder'] = array('Placeholder', 'Afficher ce texte tant que le champ est vide (HTML5 requis).');
$GLOBALS['TL_LANG']['tl_form_field']['maxlength'] = array('Longueur maximale', 'Limite la longueur du champ à un certain nombre de caractères (texte) ou octets (chargement de fichiers).');
$GLOBALS['TL_LANG']['tl_form_field']['size'] = array('Lignes et colonnes', 'Le nombre de lignes et de colonnes de la zone de texte.');
$GLOBALS['TL_LANG']['tl_form_field']['multiple'] = array('Sélection multiple', 'Permet au visiteur de sélectionner plus d\'une option.');
$GLOBALS['TL_LANG']['tl_form_field']['mSize'] = array('Taille de la liste', 'Vous pouvez indiquer la taille de la boîte de sélection.');
$GLOBALS['TL_LANG']['tl_form_field']['extensions'] = array('Types de fichier autorisés', 'Une liste des extensions de fichiers autorisées, séparées par une virgule.');
$GLOBALS['TL_LANG']['tl_form_field']['storeFile'] = array('Enregistrer les fichiers envoyés', 'Envoyer les fichiers dans un dossier sur le serveur.');
$GLOBALS['TL_LANG']['tl_form_field']['uploadFolder'] = array('Dossier de destination', 'Choisir le dossier de destination dans le répertoire de fichiers.');
$GLOBALS['TL_LANG']['tl_form_field']['useHomeDir'] = array('Utiliser le répertoire personnel', 'Enregistrer le fichier dans le répertoire personnel si l\'utilisateur est authentifié.');
$GLOBALS['TL_LANG']['tl_form_field']['doNotOverwrite'] = array('Conserver les fichiers existants', 'Ajouter un suffixe numérique au nouveau fichier si le nom de fichier existe déjà.');
$GLOBALS['TL_LANG']['tl_form_field']['fsType'] = array('Mode de fonctionnement', 'Sélectionner le mode de fonctionnement de l\'élément "Groupe de champs".');
$GLOBALS['TL_LANG']['tl_form_field']['fsStart'] = array('Début de l\'enveloppe', 'Marque le début d\'un \'fieldset\' et peut contenir une légende.');
$GLOBALS['TL_LANG']['tl_form_field']['fsStop'] = array('Fin de l\'enveloppe', 'Marque la fin d\'un \'fieldset\'.');
$GLOBALS['TL_LANG']['tl_form_field']['value'] = array('Valeur par défaut', 'Vous pouvez saisir une valeur par défaut pour le champ.');
$GLOBALS['TL_LANG']['tl_form_field']['class'] = array('Classe CSS', 'Vous pouvez saisir une ou plusieurs classes CSS.');
$GLOBALS['TL_LANG']['tl_form_field']['accesskey'] = array('Touche de raccourci', 'Le curseur peut être positionné directement sur un champ de formulaire déterminé en pressant la touche [ALT] ou [CTRL] simultanément avec la touche de raccourci.');
$GLOBALS['TL_LANG']['tl_form_field']['tabindex'] = array('Index de tabulation', 'La position du champ de formulaire dans l\'ordre de tabulation.');
$GLOBALS['TL_LANG']['tl_form_field']['fSize'] = array('Taille du champ', 'Saisir la taille du champ d\'envoi de fichier (attribut size).');
$GLOBALS['TL_LANG']['tl_form_field']['addSubmit'] = array('Ajouter un bouton d\'envoi', 'Ajouter un bouton d\'envoi à côté du champ pour créer un formulaire mono-ligne.');
$GLOBALS['TL_LANG']['tl_form_field']['slabel'] = array('Intitulé du bouton d\'envoi', 'Saisir l\'intitulé du bouton d\'envoi.');
$GLOBALS['TL_LANG']['tl_form_field']['imageSubmit'] = array('Bouton d\'envoi image', 'Utiliser une image comme bouton de soumission au lieu du bouton par défaut.');
$GLOBALS['TL_LANG']['tl_form_field']['singleSRC'] = array('Fichier source', 'Sélectionner une image dans le répertoire de fichiers.');
$GLOBALS['TL_LANG']['tl_form_field']['type_legend'] = 'Type et nom du champ';
$GLOBALS['TL_LANG']['tl_form_field']['text_legend'] = 'Texte / HTML';
$GLOBALS['TL_LANG']['tl_form_field']['fconfig_legend'] = 'Configuration du champ';
$GLOBALS['TL_LANG']['tl_form_field']['options_legend'] = 'Options';
$GLOBALS['TL_LANG']['tl_form_field']['store_legend'] = 'Enregistrer le fichier';
$GLOBALS['TL_LANG']['tl_form_field']['expert_legend'] = 'Paramètres avancés';
$GLOBALS['TL_LANG']['tl_form_field']['submit_legend'] = 'Bouton d\'envoi';
$GLOBALS['TL_LANG']['tl_form_field']['image_legend'] = 'Bouton image';
$GLOBALS['TL_LANG']['tl_form_field']['new'] = array('Nouveau champ', 'Créer un nouveau champ');
$GLOBALS['TL_LANG']['tl_form_field']['show'] = array('Détails du champ', 'Afficher les détails du champ ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['edit'] = array('Éditer le champ', 'Éditer le champ ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['cut'] = array('Déplacer le champ', 'Déplacer le champ ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['copy'] = array('Dupliquer le champ', 'Dupliquer le champ ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['delete'] = array('Supprimer le champ', 'Supprimer le champ ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['editheader'] = array('Éditer le formulaire', 'Éditer les paramètres du formulaire');
$GLOBALS['TL_LANG']['tl_form_field']['pasteafter'] = array('Coller au début', 'Coller après le champ ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['pastenew'] = array('Créer un nouveau champ au début', 'Créer un nouveau champ après le champ ID %s');
$GLOBALS['TL_LANG']['tl_form_field']['toggle'] = array('Basculer la visibilité', 'Basculer la visibilité du champ ID %s');

?>