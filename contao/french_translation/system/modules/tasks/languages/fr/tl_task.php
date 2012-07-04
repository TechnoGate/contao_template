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

$GLOBALS['TL_LANG']['tl_task']['title'] = array('Titre', 'Saisir le titre de la tâche.');
$GLOBALS['TL_LANG']['tl_task']['deadline'] = array('Date limite', 'Saisir une date limite pour cette tâche.');
$GLOBALS['TL_LANG']['tl_task']['assignTo'] = array('Assignée à', 'Assigner cette tâche à un utilisateur.');
$GLOBALS['TL_LANG']['tl_task']['notify'] = array('Notifier l\'utilisateur', 'Notifier l\'utilisateur par e-mail.');
$GLOBALS['TL_LANG']['tl_task']['status'] = array('Statut', 'Définir le statut de cette tâche.');
$GLOBALS['TL_LANG']['tl_task']['progress'] = array('Progression', 'Choisir la progression courante en %.');
$GLOBALS['TL_LANG']['tl_task']['comment'] = array('Commentaire', 'Ajouter un commentaire.');
$GLOBALS['TL_LANG']['tl_task']['date'] = 'Date';
$GLOBALS['TL_LANG']['tl_task']['assignedTo'] = 'Assignée à';
$GLOBALS['TL_LANG']['tl_task']['createdBy'] = 'créé par %s';
$GLOBALS['TL_LANG']['tl_task']['creator'] = 'Auteur';
$GLOBALS['TL_LANG']['tl_task']['noTasks'] = 'Il n\'y a pas de tâches actuellement.';
$GLOBALS['TL_LANG']['tl_task']['delConfirm'] = 'Voulez-vous vraiment supprimer la tâche ID %s?';
$GLOBALS['TL_LANG']['tl_task']['message'] = '--- Cette tâche a été assignée par %s. %s';
$GLOBALS['TL_LANG']['tl_task']['history'] = 'Historique';
$GLOBALS['TL_LANG']['tl_task']['createSubmit'] = 'Créer la tâche';
$GLOBALS['TL_LANG']['tl_task']['editSubmit'] = 'Mettre à jour';
$GLOBALS['TL_LANG']['tl_task']['new'] = array('Nouvelle tâche', 'Créer une nouvelle tâche');
$GLOBALS['TL_LANG']['tl_task']['edit'] = array('Éditer la tâche', 'Éditer la tâche ID %s');
$GLOBALS['TL_LANG']['tl_task']['delete'] = array('Supprimer la tâche', 'Supprimer la tâche ID %s');
$GLOBALS['TL_LANG']['tl_task_status']['created'] = 'Créée';
$GLOBALS['TL_LANG']['tl_task_status']['inProcess'] = 'En cours';
$GLOBALS['TL_LANG']['tl_task_status']['completed'] = 'Accomplie';
$GLOBALS['TL_LANG']['tl_task_status']['forwarded'] = 'Transférée';
$GLOBALS['TL_LANG']['tl_task_status']['declined'] = 'Rejetée';

?>