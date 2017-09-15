<?php 
//Export Module 'pays' Date: 15-09-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('pays', 'Gestion Pays','Systeme/settings/pays','ref_pays','pays','Systeme','1', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'pays' </li>";}
  //Task 'pays' 'Gestion Pays'
  if(!$result_task_475 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('pays', $result_insert_modul, 'pays','Systeme/settings/pays', '1', 'Gestion Pays', 'flag', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Pays' </li>";}
      // Action Task 475 - 'Gestion Pays'
      if(!$result_action_684 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_475, '605450f3d7c84701b986fa31e1e9fa43', 'Gestion Pays','pays', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Pays' </li>";}
      // Action Task 475 - 'Editer Pays'
      if(!$result_action_689 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_475, '29ba6cc689eca63dbafb109ec58bc4d6', 'Editer Pays','editpays', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editpays"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Pays</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Pays' </li>";}
      // Action Task 475 - 'Valider Pays'
      if(!$result_action_690 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_475, '763fe13212b4324590518773cd9a36fa', 'Valider Pays','validpays', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validpays"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Pays</a></li>'."', '0', '[-1-]', '0', '1', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Pays' </li>";}
      // Action Task 475 - 'Désactiver Pays'
      if(!$result_action_691 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_475, '3c8427c7313d35219b17572efd380b17', 'Désactiver Pays','validpays', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validpays"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Pays</a></li>'."', '0', '[-1-]', '1', '0', 'Pays Validé','success','".'<span class="label label-sm label-success">Pays Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver Pays' </li>";}
  //Task 'addpays' 'Ajouter Pays'
  if(!$result_task_476 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addpays', $result_insert_modul, 'addpays','Systeme/settings/pays', '1', 'Ajouter Pays', 'flag', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Pays' </li>";}
      // Action Task 476 - 'Ajouter Pays'
      if(!$result_action_685 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_476, '3cd55a55307615d72aae84c6b5cf99bc', 'Ajouter Pays','addpays', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Pays' </li>";}
  //Task 'editpays' 'Editer Pays'
  if(!$result_task_477 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editpays', $result_insert_modul, 'editpays','Systeme/settings/pays', '1', 'Editer Pays', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Pays' </li>";}
      // Action Task 477 - 'Editer Pays'
      if(!$result_action_686 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_477, 'cfe617d7bc6a9c7d8b86c468f21396f2', 'Editer Pays','editpays', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Pays' </li>";}
  //Task 'deletepays' 'Supprimer Pays'
  if(!$result_task_478 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletepays', $result_insert_modul, 'deletepays','Systeme/settings/pays', '1', 'Supprimer Pays', 'trash', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Pays' </li>";}
      // Action Task 478 - 'Supprimer Pays'
      if(!$result_action_687 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_478, 'b768486aeb655c48cc411c11fa60e150', 'Supprimer Pays','deletepays', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Pays' </li>";}
  //Task 'validpays' 'Valider Pays'
  if(!$result_task_479 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validpays', $result_insert_modul, 'validpays','Systeme/settings/pays', '1', 'Valider Pays', 'lock', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider Pays' </li>";}
      // Action Task 479 - 'Valider Pays'
      if(!$result_action_688 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_479, '15e4e24f320daa9d563ae62acff9e586', 'Valider Pays','validpays', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Pays' </li>";}
