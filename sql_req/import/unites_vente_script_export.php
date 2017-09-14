<?php 
//Export Module 'unites_vente' Date: 15-09-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('unites_vente', 'Gestion des unités de vente','produits/settings/unites_vente','ref_unites_vente','unites_vente','produits','1', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'unites_vente' </li>";}
  //Task 'unites_vente' 'Gestion des unités de vente'
  if(!$result_task_376 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('unites_vente', $result_insert_modul, 'unites_vente','produits/settings/unites_vente', '1', 'Gestion des unités de vente', 'cogs', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion des unités de vente' </li>";}
      // Action Task 376 - 'Gestion des unités de vente'
      if(!$result_action_554 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_376, '55ecbb545a49c70c0b728bb0c7951077', 'Gestion des unités de vente','unites_vente', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion des unités de vente' </li>";}
      // Action Task 376 - 'Modifier unité '
      if(!$result_action_559 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_376, '67acd70eb04242b7091d9dcbb08295d7', 'Modifier unité ','editunite_vente', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editunite_vente"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier unité </a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier unité ' </li>";}
      // Action Task 376 - 'Valider unité'
      if(!$result_action_560 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_376, '7363022ed5ad047bfe86d3de4b75b1f4', 'Valider unité','validunite_vente', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validunite_vente"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider unité</a></li>'."', '0', '[-1-]', '0', '1', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider unité' </li>";}
      // Action Task 376 - 'Désactiver unité'
      if(!$result_action_565 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_376, 'ec77eb95736c27bfc269cbffc8e113f1', 'Désactiver unité','validunite_vente', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validunite_vente"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver unité</a></li>'."', '0', '[-1-]', '1', '0', 'Unité de vente validé','success','".'<span class="label label-sm label-success">Unité de vente validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver unité' </li>";}
  //Task 'addunite_vente' 'ajouter une unité'
  if(!$result_task_377 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addunite_vente', $result_insert_modul, 'addunite_vente','produits/settings/unites_vente', '1', 'ajouter une unité', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'ajouter une unité' </li>";}
      // Action Task 377 - 'ajouter une unité'
      if(!$result_action_555 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_377, '3a5e8dfe211121eda706f8b6d548d111', 'ajouter une unité','addunite_vente', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'ajouter une unité' </li>";}
  //Task 'editunite_vente' 'Modifier une unité'
  if(!$result_task_378 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editunite_vente', $result_insert_modul, 'editunite_vente','produits/settings/unites_vente', '1', 'Modifier une unité', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier une unité' </li>";}
      // Action Task 378 - 'Modifier une unité'
      if(!$result_action_556 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_378, '9b7a578981de699286376903e96bc3c7', 'Modifier une unité','editunite_vente', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier une unité' </li>";}
  //Task 'validunite_vente' 'Valider une unité'
  if(!$result_task_379 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validunite_vente', $result_insert_modul, 'validunite_vente','produits/settings/unites_vente', '1', 'Valider une unité', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider une unité' </li>";}
      // Action Task 379 - 'Valider une unité'
      if(!$result_action_557 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_379, '62355588366c13ddbadc7a7ca1d226ad', 'Valider une unité','validunite_vente', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider une unité' </li>";}
  //Task 'deleteunite_vente' 'Supprimer une unité'
  if(!$result_task_380 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deleteunite_vente', $result_insert_modul, 'deleteunite_vente','produits/settings/unites_vente', '1', 'Supprimer une unité', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer une unité' </li>";}
      // Action Task 380 - 'Supprimer une unité'
      if(!$result_action_558 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_380, 'e5f53a3aaa324415d781156396938101', 'Supprimer une unité','deleteunite_vente', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer une unité' </li>";}
