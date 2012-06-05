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

$GLOBALS['TL_LANG']['ERR']['general'] = 'Une erreur est survenue !';
$GLOBALS['TL_LANG']['ERR']['unique'] = 'Doublon dans le champ "%s" !';
$GLOBALS['TL_LANG']['ERR']['mandatory'] = 'Le champ "%s" ne doit pas être vide !';
$GLOBALS['TL_LANG']['ERR']['mdtryNoLabel'] = 'Ce champ ne doit pas être vide !';
$GLOBALS['TL_LANG']['ERR']['minlength'] = 'Le champ "%s" doit posséder au moins %d caractères !';
$GLOBALS['TL_LANG']['ERR']['maxlength'] = 'Le champ "%s" ne doit pas contenir plus de %d caractères !';
$GLOBALS['TL_LANG']['ERR']['digit'] = 'Veuillez seulement saisir des chiffres !';
$GLOBALS['TL_LANG']['ERR']['prcnt'] = 'Veuillez saisir une valeur entre 0 et 100% !';
$GLOBALS['TL_LANG']['ERR']['alpha'] = 'Veuillez seulement saisir des caractères alphabétiques !';
$GLOBALS['TL_LANG']['ERR']['alnum'] = 'Veuillez seulement saisir des caractères alphanumériques !';
$GLOBALS['TL_LANG']['ERR']['phone'] = 'Veuillez saisir un numéro de téléphone valide !';
$GLOBALS['TL_LANG']['ERR']['extnd'] = 'Pour des raisons de sécurité n\'utilisez pas ces caractères =<>&/()#';
$GLOBALS['TL_LANG']['ERR']['email'] = 'Veuillez saisir une adresse e-mail valide !';
$GLOBALS['TL_LANG']['ERR']['emails'] = 'Il y a au moins une adresse e-mail non valide !';
$GLOBALS['TL_LANG']['ERR']['url'] = 'Veuillez saisir une URL valide et encodez les caractères spéciaux !';
$GLOBALS['TL_LANG']['ERR']['date'] = 'Veuillez saisir la date "%s" !';
$GLOBALS['TL_LANG']['ERR']['time'] = 'Veuillez saisir l\'heure "%s" !';
$GLOBALS['TL_LANG']['ERR']['dateTime'] = 'Veuillez saisir la date et l\'heure "%s" !';
$GLOBALS['TL_LANG']['ERR']['noSpace'] = 'Le champ "%s" ne doit pas contenir d\'espace !';
$GLOBALS['TL_LANG']['ERR']['filesize'] = 'La taille maximale pour l\'envoi de fichier est %s (Paramètres de Contao ou du fichier php.ini)!';
$GLOBALS['TL_LANG']['ERR']['filetype'] = 'Le fichier de type "%s" n\'est pas autorisé pour l\'envoi !';
$GLOBALS['TL_LANG']['ERR']['filepartial'] = 'Le fichier %s a été envoyé partiellement !';
$GLOBALS['TL_LANG']['ERR']['filewidth'] = 'L\'image %s dépasse la largeur maximale qui est de %d pixels !';
$GLOBALS['TL_LANG']['ERR']['fileheight'] = 'L\'image %s dépasse la hauteur maximale qui est de %d pixels !';
$GLOBALS['TL_LANG']['ERR']['invalidReferer'] = 'Accès interdit ! L\'adresse de l\'hôte courant (%s) est différente de l\'adresse de l\'hôte référent (%s) !';
$GLOBALS['TL_LANG']['ERR']['invalidPass'] = 'Mot de passe incorrect !';
$GLOBALS['TL_LANG']['ERR']['passwordLength'] = 'Un mot de passe doit posséder au moins %d caractères !';
$GLOBALS['TL_LANG']['ERR']['passwordName'] = 'Votre identifiant et votre mot de passe ne correspondent pas !';
$GLOBALS['TL_LANG']['ERR']['passwordMatch'] = 'Les mots de passe sont différents !';
$GLOBALS['TL_LANG']['ERR']['accountLocked'] = 'Ce compte est bloqué ! Essayez de vous connecter dans %d minutes.';
$GLOBALS['TL_LANG']['ERR']['invalidLogin'] = 'La connexion a échoué !';
$GLOBALS['TL_LANG']['ERR']['invalidColor'] = 'Le format de couleur est invalide !';
$GLOBALS['TL_LANG']['ERR']['all_fields'] = 'Veuillez choisir au moins un champ !';
$GLOBALS['TL_LANG']['ERR']['aliasExists'] = 'L\'alias "%s" existe déjà !';
$GLOBALS['TL_LANG']['ERR']['importFolder'] = 'Le répertoire "%s" ne peut être importé !';
$GLOBALS['TL_LANG']['ERR']['notWriteable'] = 'Le fichier "%s" n\'est pas accessible en écriture et ne peut donc pas être mis à jour !';
$GLOBALS['TL_LANG']['ERR']['invalidName'] = 'Le nom de fichier ou de répertoire est invalide !';
$GLOBALS['TL_LANG']['ERR']['invalidFile'] = 'Le fichier ou le répertoire est invalide !';
$GLOBALS['TL_LANG']['ERR']['fileExists'] = 'Le fichier "%s" existe déjà !';
$GLOBALS['TL_LANG']['ERR']['circularReference'] = 'Cible de redirection non valide !';
$GLOBALS['TL_LANG']['ERR']['ie6warning'] = '<strong>ATTENTION !</strong> Votre navigateur %sn\'est pas à jour%s, <strong>certaines fonctionnalités ne seront pas disponibles</strong>.';
$GLOBALS['TL_LANG']['ERR']['noFallbackEmpty'] = 'Aucune des pages racines sans nom de domaine n\'a l\'option de langue de secours configurée, ce qui signifie que ces sites ne sont disponibles que dans la langue que vous avez défini dans les réglages de la page ! Les visiteurs (et moteurs de recherche) qui ne parlent pas cette langue ne seront pas en mesure de naviguer sur votre site.';
$GLOBALS['TL_LANG']['ERR']['noFallbackDns'] = 'Aucune des pages racines de <strong>%s</strong> n\'a l\'option de langue de secours configurée, ce qui signifie que ces sites ne sont disponibles que dans la langue que vous avez défini dans les réglages de la page ! Les visiteurs (et moteurs de recherche) qui ne parlent pas cette langue ne seront pas en mesure de naviguer sur votre site.';
$GLOBALS['TL_LANG']['ERR']['multipleFallback'] = 'Vous pouvez seulement définir une page racine par domaine comme langue de secours.';
$GLOBALS['TL_LANG']['ERR']['topLevelRoot'] = 'Les pages de premier niveau doivent être des pages racines !';
$GLOBALS['TL_LANG']['ERR']['topLevelRegular'] = 'Il y a des pages de premier niveau qui ne sont pas des pages racines. La création de site sans une page racine n\'est plus supporté ,faites en sorte que toutes les pages sont regroupées dans une page racine.';
$GLOBALS['TL_LANG']['PTY']['regular'] = array('Page simple', 'Une page simple contient des articles et des éléments de contenu. C\'est le type de page de défaut.');
$GLOBALS['TL_LANG']['PTY']['redirect'] = array('Rediriger vers une URL externe', 'Cette page réoriente automatiquement vers une URL externe. Cela fonctionne comme un hyperlien.');
$GLOBALS['TL_LANG']['PTY']['forward'] = array('Rediriger vers une autre page du site', 'Ce type de page réoriente automatiquement le visiteur vers une autre page de la structure de site.');
$GLOBALS['TL_LANG']['PTY']['root'] = array('Racine d\'un nouveau site', 'Ce type de page est le point de départ pour créer un site internet dans la structure de site.');
$GLOBALS['TL_LANG']['PTY']['error_403'] = array('Erreur 403 (accès interdit)', 'Si un utilisateur sans permission essaye d\'accéder à une page protégée, une page d\'erreur 403 sera retournée. Si un utilisateur sans permission essaye d\'accéder à une page protégée, une page d\'erreur 403 sera retournée.');
$GLOBALS['TL_LANG']['PTY']['error_404'] = array('Erreur 404 (page non trouvée)', 'Si un utilisateur essaye d\'accéder à une page qui n\'existe pas, une page d\'erreur 404 sera retournée.');
$GLOBALS['TL_LANG']['FOP']['fop'] = array('Permissions sur les fichiers', 'Sélectionnez les opérations sur les fichiers que vous autoriserez.');
$GLOBALS['TL_LANG']['FOP']['f1'] = 'Envoyer les fichiers sur le serveur';
$GLOBALS['TL_LANG']['FOP']['f2'] = 'Éditer, copier ou déplacer des fichiers ou des répertoires';
$GLOBALS['TL_LANG']['FOP']['f3'] = 'Supprimer les fichiers seuls ou les répertoires vides';
$GLOBALS['TL_LANG']['FOP']['f4'] = 'Supprimer les répertoires contenant des fichiers ou des sous-répertoires (!)';
$GLOBALS['TL_LANG']['FOP']['f5'] = 'Éditer ces fichiers dans un éditeur de texte';
$GLOBALS['TL_LANG']['CHMOD']['editpage'] = 'Éditer la page';
$GLOBALS['TL_LANG']['CHMOD']['editnavigation'] = 'Éditer la hiérarchie de la page';
$GLOBALS['TL_LANG']['CHMOD']['deletepage'] = 'Supprimer la page';
$GLOBALS['TL_LANG']['CHMOD']['editarticles'] = 'Éditer les articles';
$GLOBALS['TL_LANG']['CHMOD']['movearticles'] = 'Éditer la hiérarchie de l\'article';
$GLOBALS['TL_LANG']['CHMOD']['deletearticles'] = 'Supprimer les articles';
$GLOBALS['TL_LANG']['CHMOD']['cuser'] = 'Utilisateur';
$GLOBALS['TL_LANG']['CHMOD']['cgroup'] = 'Groupe';
$GLOBALS['TL_LANG']['CHMOD']['cworld'] = 'Tout le monde';
$GLOBALS['TL_LANG']['DAYS'][0] = 'Dimanche';
$GLOBALS['TL_LANG']['DAYS'][1] = 'Lundi';
$GLOBALS['TL_LANG']['DAYS'][2] = 'Mardi';
$GLOBALS['TL_LANG']['DAYS'][3] = 'Mercredi';
$GLOBALS['TL_LANG']['DAYS'][4] = 'Jeudi';
$GLOBALS['TL_LANG']['DAYS'][5] = 'Vendredi';
$GLOBALS['TL_LANG']['DAYS'][6] = 'Samedi';
$GLOBALS['TL_LANG']['DAYS_SHORT'][0] = 'Dim';
$GLOBALS['TL_LANG']['DAYS_SHORT'][1] = 'Lun';
$GLOBALS['TL_LANG']['DAYS_SHORT'][2] = 'Mar';
$GLOBALS['TL_LANG']['DAYS_SHORT'][3] = 'Mer';
$GLOBALS['TL_LANG']['DAYS_SHORT'][4] = 'Jeu';
$GLOBALS['TL_LANG']['DAYS_SHORT'][5] = 'Ven';
$GLOBALS['TL_LANG']['DAYS_SHORT'][6] = 'Sam';
$GLOBALS['TL_LANG']['MONTHS'][0] = 'Janvier';
$GLOBALS['TL_LANG']['MONTHS'][1] = 'Février';
$GLOBALS['TL_LANG']['MONTHS'][2] = 'Mars';
$GLOBALS['TL_LANG']['MONTHS'][3] = 'Avril';
$GLOBALS['TL_LANG']['MONTHS'][4] = 'Mai';
$GLOBALS['TL_LANG']['MONTHS'][5] = 'Juin';
$GLOBALS['TL_LANG']['MONTHS'][6] = 'Juillet';
$GLOBALS['TL_LANG']['MONTHS'][7] = 'Août';
$GLOBALS['TL_LANG']['MONTHS'][8] = 'Septembre';
$GLOBALS['TL_LANG']['MONTHS'][9] = 'Octobre';
$GLOBALS['TL_LANG']['MONTHS'][10] = 'Novembre';
$GLOBALS['TL_LANG']['MONTHS'][11] = 'Décembre';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][0] = 'Jan';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][1] = 'Fév';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][2] = 'Mar';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][3] = 'Avr';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][4] = 'Mai';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][5] = 'Juin';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][6] = 'Juil';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][7] = 'Aoû';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][8] = 'Sep';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][9] = 'Oct';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][10] = 'Nov';
$GLOBALS['TL_LANG']['MONTHS_SHORT'][11] = 'Déc';
$GLOBALS['TL_LANG']['MSC']['dayShortLength'] = '3';
$GLOBALS['TL_LANG']['MSC']['monthShortLength'] = '3';
$GLOBALS['TL_LANG']['MSC']['weekOffset'] = '0';
$GLOBALS['TL_LANG']['MSC']['titleFormat'] = '%B %d%o, %Y';
$GLOBALS['TL_LANG']['MSC']['url'] = array('URL Cible', 'Saisir l\'URL d\'un site (http://...), une adresse e-mail (mailto:..) ou utiliser des balises d\'insertion.');
$GLOBALS['TL_LANG']['MSC']['target'] = array('Ouvrir dans une nouvelle fenêtre', 'Ouvre un lien dans une nouvelle fenêtre.');
$GLOBALS['TL_LANG']['MSC']['decimalSeparator'] = '.';
$GLOBALS['TL_LANG']['MSC']['thousandsSeparator'] = ',';
$GLOBALS['TL_LANG']['MSC']['separator'] = array('Séparateur', 'Sélectionner le caractère séparant les valeurs.');
$GLOBALS['TL_LANG']['MSC']['source'] = array('Fichiers source', 'Choisir le fichier CSV que vous voulez importer depuis le répertoire des fichiers');
$GLOBALS['TL_LANG']['MSC']['comma'] = 'Virgule';
$GLOBALS['TL_LANG']['MSC']['semicolon'] = 'Point virgule';
$GLOBALS['TL_LANG']['MSC']['tabulator'] = 'Tabulation';
$GLOBALS['TL_LANG']['MSC']['linebreak'] = 'Saut de ligne';
$GLOBALS['TL_LANG']['MSC']['lw_import'] = array('Import CSV', 'Import des éléments de liste à partir d\'un fichier CSV');
$GLOBALS['TL_LANG']['MSC']['lw_copy'] = 'Copier l\'élément';
$GLOBALS['TL_LANG']['MSC']['lw_up'] = 'Déplacer l\'élément d\'une position vers le haut';
$GLOBALS['TL_LANG']['MSC']['lw_down'] = 'Déplacer l\'élément d\'une position vers le bas';
$GLOBALS['TL_LANG']['MSC']['lw_delete'] = 'Supprimer l\'élément';
$GLOBALS['TL_LANG']['MSC']['tw_import'] = array('Import CSV', 'Import des éléments de tableau à partir d\'un fichier CSV');
$GLOBALS['TL_LANG']['MSC']['tw_expand'] = 'Augmenter la taille du champ de saisie';
$GLOBALS['TL_LANG']['MSC']['tw_shrink'] = 'Diminuer la taille du champ de saisie';
$GLOBALS['TL_LANG']['MSC']['tw_rcopy'] = 'Dupliquer la ligne';
$GLOBALS['TL_LANG']['MSC']['tw_rup'] = 'Déplacer la ligne d\'une position vers le haut';
$GLOBALS['TL_LANG']['MSC']['tw_rdown'] = 'Déplacer la ligne d\'une position vers le bas';
$GLOBALS['TL_LANG']['MSC']['tw_rdelete'] = 'Supprimer la ligne';
$GLOBALS['TL_LANG']['MSC']['tw_ccopy'] = 'Dupliquer la colonne';
$GLOBALS['TL_LANG']['MSC']['tw_cmovel'] = 'Déplacer la colonne d\'une position vers la gauche';
$GLOBALS['TL_LANG']['MSC']['tw_cmover'] = 'Déplacer la colonne d\'une position vers la droite';
$GLOBALS['TL_LANG']['MSC']['tw_cdelete'] = 'Supprimer la colonne';
$GLOBALS['TL_LANG']['MSC']['ow_copy'] = 'Dupliquer la ligne';
$GLOBALS['TL_LANG']['MSC']['ow_up'] = 'Déplacer la ligne d\'une position vers le haut';
$GLOBALS['TL_LANG']['MSC']['ow_down'] = 'Déplacer la ligne d\'une position vers le bas';
$GLOBALS['TL_LANG']['MSC']['ow_delete'] = 'Supprimer la ligne';
$GLOBALS['TL_LANG']['MSC']['ow_value'] = 'Valeur';
$GLOBALS['TL_LANG']['MSC']['ow_label'] = 'Libellé';
$GLOBALS['TL_LANG']['MSC']['ow_key'] = 'Clé';
$GLOBALS['TL_LANG']['MSC']['ow_default'] = 'Défaut';
$GLOBALS['TL_LANG']['MSC']['ow_group'] = 'Groupe';
$GLOBALS['TL_LANG']['MSC']['mw_copy'] = 'Dupliquer la ligne';
$GLOBALS['TL_LANG']['MSC']['mw_up'] = 'Déplacer la ligne d\'une position vers le haut';
$GLOBALS['TL_LANG']['MSC']['mw_down'] = 'Déplacer la ligne d\'une position vers le bas';
$GLOBALS['TL_LANG']['MSC']['mw_delete'] = 'Supprimer la ligne';
$GLOBALS['TL_LANG']['MSC']['mw_module'] = 'Module';
$GLOBALS['TL_LANG']['MSC']['mw_column'] = 'Colonne';
$GLOBALS['TL_LANG']['MSC']['relative'] = 'Dimensions relatives';
$GLOBALS['TL_LANG']['MSC']['proportional'] = array('Proportionnel', 'Le côté le plus long de l\'image est ajusté aux dimensions données, et l\'image est redimensionnée proportionnellement.');
$GLOBALS['TL_LANG']['MSC']['box'] = array('Étirer', 'Le côté le plus court de l\'image est ajusté aux dimensions données et l\'image est redimensionnée proportionnellement.');
$GLOBALS['TL_LANG']['MSC']['crop'] = 'Dimensions exactes';
$GLOBALS['TL_LANG']['MSC']['left_top'] = array('Haut gauche', 'Préserve la partie gauche d\'une image au format paysage et la partie supérieure d\'une image au format portrait.');
$GLOBALS['TL_LANG']['MSC']['center_top'] = array('Haut centre', 'Préserve la partie centrale d\'une image au format paysage et la partie supérieure d\'une image au format portrait.');
$GLOBALS['TL_LANG']['MSC']['right_top'] = array('Haut droit', 'Préserve la partie droite d\'une image au format paysage et la partie supérieure d\'une image au format portrait.');
$GLOBALS['TL_LANG']['MSC']['left_center'] = array('Centre gauche', 'Préserve la partie gauche d\'une image au format paysage et la partie centrale d\'une image au format portrait.');
$GLOBALS['TL_LANG']['MSC']['center_center'] = array('Centre centre', 'Préserve la partie centrale d\'une image au format paysage et la partie centrale d\'une image au format portrait.');
$GLOBALS['TL_LANG']['MSC']['right_center'] = array('Centre droit', 'Préserve la partie droite d\'une image au format paysage et la partie centrale d\'une image au format portrait.');
$GLOBALS['TL_LANG']['MSC']['left_bottom'] = array('Bas gauche', 'Préserve la partie gauche d\'une image au format paysage et la partie inférieure d\'une image au format portrait.');
$GLOBALS['TL_LANG']['MSC']['center_bottom'] = array('Bas centre', 'Préserve la partie centrale d\'une image au format paysage et la partie inférieure d\'une image au format portrait.');
$GLOBALS['TL_LANG']['MSC']['right_bottom'] = array('Bas droit', 'Préserve la partie droite d\'une image au format paysage et la partie inférieure d\'une image au format portrait.');
$GLOBALS['TL_LANG']['MSC']['id'] = array('ID', 'Changer les ID détruira l\'uniformité des données !');
$GLOBALS['TL_LANG']['MSC']['pid'] = array('ID parent', 'Changer les ID détruira l\'uniformité des données !');
$GLOBALS['TL_LANG']['MSC']['sorting'] = array('Ordre', 'L\'ordre est habituellement choisi automatiquement.');
$GLOBALS['TL_LANG']['MSC']['all'] = array('Edition multiple', 'Éditer plusieurs enregistrements à la fois.');
$GLOBALS['TL_LANG']['MSC']['all_fields'] = array('Champs disponibles', 'Sélectionner les champs que vous voulez éditer.');
$GLOBALS['TL_LANG']['MSC']['password'] = array('Mot de passe', 'Saisir un mot de passe.');
$GLOBALS['TL_LANG']['MSC']['confirm'] = array('Confirmation du mot de passe', 'Confirmer le mot de passe.');
$GLOBALS['TL_LANG']['MSC']['dateAdded'] = array('Date ajoutée', 'Date ajoutée : %s');
$GLOBALS['TL_LANG']['MSC']['lastLogin'] = array('Dernière connexion', 'Dernière connexion : %s');
$GLOBALS['TL_LANG']['MSC']['move_up'] = array('Déplacer vers le haut', 'Déplacer l\'élément d\'une position vers le haut');
$GLOBALS['TL_LANG']['MSC']['move_down'] = array('Déplacer vers le bas', 'Déplacer l\'élément d\'une position vers le bas');
$GLOBALS['TL_LANG']['MSC']['staticFiles'] = array('URL des fichiers', 'L\'URL des fichiers s\'applique au répertoire <em>tl_files</em> et à toutes les images miniatures (optimisation de la vitesse de chargement des pages).');
$GLOBALS['TL_LANG']['MSC']['staticSystem'] = array('URL des scripts', 'L\'URL des scripts s\'applique à tous les fichiers JavaScript et CSS, y compris les images de fond intégrés (optimisation de la vitesse de chargement des pages).');
$GLOBALS['TL_LANG']['MSC']['staticPlugins'] = array('URL des plugins', 'L\'URL des plugins s\'applique à toutes les ressources se trouvant dans le répertoire <em>plugins</em> (optimisation de la vitesse de chargement des pages).');
$GLOBALS['TL_LANG']['MSC']['shortcuts'] = array('Raccourcis clavier du back office', 'En savoir plus sur l\'accélération de votre flux de travail en utilisant des <a href="http://www.contao.fr/raccourcis-clavier.html" target="_blank">raccourcis clavier</a>.');
$GLOBALS['TL_LANG']['MSC']['feLink'] = 'Aller sur le front office';
$GLOBALS['TL_LANG']['MSC']['fePreview'] = 'Aperçu';
$GLOBALS['TL_LANG']['MSC']['feUser'] = 'Membre';
$GLOBALS['TL_LANG']['MSC']['backToTop'] = 'Haut de page';
$GLOBALS['TL_LANG']['MSC']['home'] = 'Accueil';
$GLOBALS['TL_LANG']['MSC']['user'] = 'Utilisateur';
$GLOBALS['TL_LANG']['MSC']['loginTo'] = 'Connexion sur %s';
$GLOBALS['TL_LANG']['MSC']['loginBT'] = 'Connexion';
$GLOBALS['TL_LANG']['MSC']['logoutBT'] = 'Se déconnecter';
$GLOBALS['TL_LANG']['MSC']['backBT'] = 'Revenir';
$GLOBALS['TL_LANG']['MSC']['cancelBT'] = 'Annuler';
$GLOBALS['TL_LANG']['MSC']['deleteConfirm'] = 'Voulez-vous vraiment supprimer cette entrée ID %s ?';
$GLOBALS['TL_LANG']['MSC']['delAllConfirm'] = 'Voulez-vous vraiment supprimer les enregistrements sélectionnés ?';
$GLOBALS['TL_LANG']['MSC']['filterRecords'] = 'Enregistrements';
$GLOBALS['TL_LANG']['MSC']['filterAll'] = 'Tous';
$GLOBALS['TL_LANG']['MSC']['showRecord'] = 'Afficher les détails de l\'enregistrement %s';
$GLOBALS['TL_LANG']['MSC']['editRecord'] = 'Éditer l\'enregistrement %s';
$GLOBALS['TL_LANG']['MSC']['all_info'] = 'Éditer tous les enregistrements actifs de la table %s';
$GLOBALS['TL_LANG']['MSC']['showOnly'] = 'Afficher';
$GLOBALS['TL_LANG']['MSC']['sortBy'] = 'Tri';
$GLOBALS['TL_LANG']['MSC']['filter'] = 'Filtrer';
$GLOBALS['TL_LANG']['MSC']['search'] = 'Chercher';
$GLOBALS['TL_LANG']['MSC']['noResult'] = 'Aucun enregistrement trouvé.';
$GLOBALS['TL_LANG']['MSC']['save'] = 'Sauvegarder';
$GLOBALS['TL_LANG']['MSC']['saveNclose'] = 'Sauvegarder et fermer';
$GLOBALS['TL_LANG']['MSC']['saveNedit'] = 'Sauvegarder et éditer';
$GLOBALS['TL_LANG']['MSC']['saveNback'] = 'Sauvegarder et retourner en arrière';
$GLOBALS['TL_LANG']['MSC']['saveNcreate'] = 'Sauvegarder et nouveau';
$GLOBALS['TL_LANG']['MSC']['continue'] = 'Continuer';
$GLOBALS['TL_LANG']['MSC']['skipNavigation'] = 'Sauter la navigation';
$GLOBALS['TL_LANG']['MSC']['selectAll'] = 'Tout sélectionner';
$GLOBALS['TL_LANG']['MSC']['pw_change'] = 'Saisir un nouveau mot de passe';
$GLOBALS['TL_LANG']['MSC']['pw_changed'] = 'Le mot de passe est mis à jour.';
$GLOBALS['TL_LANG']['MSC']['fallback'] = 'défaut';
$GLOBALS['TL_LANG']['MSC']['view'] = 'Afficher dans une nouvelle fenêtre';
$GLOBALS['TL_LANG']['MSC']['fullsize'] = 'Ouvrir l\'image pleine taille, dans une nouvelle fenêtre';
$GLOBALS['TL_LANG']['MSC']['colorpicker'] = 'Sélectionner une couleur (Javascript requis)';
$GLOBALS['TL_LANG']['MSC']['pagepicker'] = 'Sélectionner une page (Javascript requis)';
$GLOBALS['TL_LANG']['MSC']['filepicker'] = 'Sélectionner un fichier (Javascript requis)';
$GLOBALS['TL_LANG']['MSC']['ppHeadline'] = 'Pages';
$GLOBALS['TL_LANG']['MSC']['fpHeadline'] = 'Fichiers';
$GLOBALS['TL_LANG']['MSC']['yes'] = 'oui';
$GLOBALS['TL_LANG']['MSC']['no'] = 'non';
$GLOBALS['TL_LANG']['MSC']['goBack'] = 'Revenir';
$GLOBALS['TL_LANG']['MSC']['reload'] = 'Recharger';
$GLOBALS['TL_LANG']['MSC']['above'] = 'au-dessus';
$GLOBALS['TL_LANG']['MSC']['below'] = 'au-dessous';
$GLOBALS['TL_LANG']['MSC']['date'] = 'Date';
$GLOBALS['TL_LANG']['MSC']['tstamp'] = 'Date';
$GLOBALS['TL_LANG']['MSC']['entry'] = '%s entrée';
$GLOBALS['TL_LANG']['MSC']['entries'] = '%s entrées';
$GLOBALS['TL_LANG']['MSC']['left'] = 'à gauche';
$GLOBALS['TL_LANG']['MSC']['center'] = 'centrer';
$GLOBALS['TL_LANG']['MSC']['right'] = 'à droite';
$GLOBALS['TL_LANG']['MSC']['justify'] = 'justifié';
$GLOBALS['TL_LANG']['MSC']['filetree'] = 'Répertoire système';
$GLOBALS['TL_LANG']['MSC']['male'] = 'Homme';
$GLOBALS['TL_LANG']['MSC']['female'] = 'Femme';
$GLOBALS['TL_LANG']['MSC']['fileSize'] = 'Taille du fichier';
$GLOBALS['TL_LANG']['MSC']['fileCreated'] = 'Créé';
$GLOBALS['TL_LANG']['MSC']['fileModified'] = 'Modifié';
$GLOBALS['TL_LANG']['MSC']['fileAccessed'] = 'Accédé';
$GLOBALS['TL_LANG']['MSC']['fileDownload'] = 'Télécharger';
$GLOBALS['TL_LANG']['MSC']['fileImageSize'] = 'Largeur / hauteur en pixels';
$GLOBALS['TL_LANG']['MSC']['filePath'] = 'Chemin relatif';
$GLOBALS['TL_LANG']['MSC']['version'] = 'Version';
$GLOBALS['TL_LANG']['MSC']['restore'] = 'Restaurer';
$GLOBALS['TL_LANG']['MSC']['backendModules'] = 'Modules du back office';
$GLOBALS['TL_LANG']['MSC']['welcomeTo'] = '%s administration';
$GLOBALS['TL_LANG']['MSC']['updateVersion'] = 'La version %s de Contao est disponible';
$GLOBALS['TL_LANG']['MSC']['wordWrap'] = 'Retour à la ligne automatique';
$GLOBALS['TL_LANG']['MSC']['saveAlert'] = 'ATTENTION ! Vous allez perdre toutes vos modifications non sauvegardées. Voulez-vous continuer ?';
$GLOBALS['TL_LANG']['MSC']['toggleNodes'] = 'Masquer/Déployer tout';
$GLOBALS['TL_LANG']['MSC']['expandNode'] = 'Déployer';
$GLOBALS['TL_LANG']['MSC']['collapseNode'] = 'Masquer';
$GLOBALS['TL_LANG']['MSC']['loadingData'] = 'Chargement des données';
$GLOBALS['TL_LANG']['MSC']['deleteSelected'] = 'Supprimer';
$GLOBALS['TL_LANG']['MSC']['editSelected'] = 'Éditer';
$GLOBALS['TL_LANG']['MSC']['overrideSelected'] = 'Remplacer';
$GLOBALS['TL_LANG']['MSC']['moveSelected'] = 'Déplacer';
$GLOBALS['TL_LANG']['MSC']['copySelected'] = 'Copier';
$GLOBALS['TL_LANG']['MSC']['changeSelected'] = 'Changer la sélection';
$GLOBALS['TL_LANG']['MSC']['resetSelected'] = 'Réinitialiser la sélection';
$GLOBALS['TL_LANG']['MSC']['fileManager'] = 'Ouvrir l\'explorateur de fichiers dans une fenêtre popup';
$GLOBALS['TL_LANG']['MSC']['systemMessages'] = 'Messages du système';
$GLOBALS['TL_LANG']['MSC']['tasksCur'] = '%s tâche(s) en attente';
$GLOBALS['TL_LANG']['MSC']['tasksNew'] = '%s nouvelle(s) tâche(s)';
$GLOBALS['TL_LANG']['MSC']['tasksDue'] = '%s tâche(s) en retard';
$GLOBALS['TL_LANG']['MSC']['clearClipboard'] = 'Vider le presse-papiers';
$GLOBALS['TL_LANG']['MSC']['hiddenElements'] = 'Éléments non publiés';
$GLOBALS['TL_LANG']['MSC']['hiddenHide'] = 'masquer';
$GLOBALS['TL_LANG']['MSC']['hiddenShow'] = 'afficher';
$GLOBALS['TL_LANG']['MSC']['apply'] = 'Appliquer';
$GLOBALS['TL_LANG']['MSC']['mandatory'] = 'Champ obligatoire';
$GLOBALS['TL_LANG']['MSC']['create'] = 'Créer';
$GLOBALS['TL_LANG']['MSC']['delete'] = 'Supprimer';
$GLOBALS['TL_LANG']['MSC']['protected'] = 'protégé';
$GLOBALS['TL_LANG']['MSC']['guests'] = 'seulement les invités';
$GLOBALS['TL_LANG']['MSC']['updateMode'] = 'Mode de mise à jour';
$GLOBALS['TL_LANG']['MSC']['updateAdd'] = 'Ajouter les groupes sélectionnés';
$GLOBALS['TL_LANG']['MSC']['updateRemove'] = 'Supprimer les groupes sélectionnés';
$GLOBALS['TL_LANG']['MSC']['updateReplace'] = 'Remplacer les données existantes';
$GLOBALS['TL_LANG']['MSC']['ascending'] = 'croissant';
$GLOBALS['TL_LANG']['MSC']['descending'] = 'décroissant';
$GLOBALS['TL_LANG']['MSC']['default'] = 'Défaut';
$GLOBALS['TL_LANG']['MSC']['helpWizard'] = 'Aide';
$GLOBALS['TL_LANG']['MSC']['noCookies'] = 'Vous devez autoriser les cookies pour utiliser Contao.';
$GLOBALS['TL_LANG']['MSC']['copyOf'] = '%s (copie)';
$GLOBALS['TL_LANG']['MSC']['coreOnlyMode'] = 'Contao fonctionne actuellement en <strong>mode sans échec</strong>, dans lequel les modules de base sont chargés. Ce mode de fonctionnement est par ex. activé après une mise à jour avec Live Update pour éviter les incompatibilités possibles avec des extensions tierces. Vous pouvez le désactiver dans les paramètres du back office après avoir vérifié les extensions tierces installées.';
$GLOBALS['TL_LANG']['MSC']['emailAddress'] = 'Adresse e-mail';
$GLOBALS['TL_LANG']['UNITS'][0] = 'Byte';
$GLOBALS['TL_LANG']['UNITS'][1] = 'kB';
$GLOBALS['TL_LANG']['UNITS'][2] = 'MB';
$GLOBALS['TL_LANG']['UNITS'][3] = 'GB';
$GLOBALS['TL_LANG']['UNITS'][4] = 'TB';
$GLOBALS['TL_LANG']['UNITS'][5] = 'PB';
$GLOBALS['TL_LANG']['UNITS'][6] = 'EB';
$GLOBALS['TL_LANG']['UNITS'][7] = 'ZB';
$GLOBALS['TL_LANG']['UNITS'][8] = 'YB';
$GLOBALS['TL_LANG']['DP']['select_a_time'] = 'Sélectionner une heure';
$GLOBALS['TL_LANG']['DP']['use_mouse_wheel'] = 'Utiliser la molette de la souris pour changer rapidement de valeur';
$GLOBALS['TL_LANG']['DP']['time_confirm_button'] = 'OK';
$GLOBALS['TL_LANG']['DP']['apply_range'] = 'Appliquer';
$GLOBALS['TL_LANG']['DP']['cancel'] = 'Annuler';
$GLOBALS['TL_LANG']['DP']['week'] = 'Wk';

?>