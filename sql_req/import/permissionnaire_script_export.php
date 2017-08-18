<?php 
//Export Module 'permissionnaire' Date: 13-06-2017z
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('permissionnaire', 'Permissionnaires','permissionnaire','prm_permissionnaires','prm',NULL,'0', '0', '[-1-2-17-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'permissionnaire' </li>";}
  //Task 'prm' 'Permissionnaires'
  if(!$result_task_204 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('prm', $result_insert_modul, 'prm','permissionnaire', '1', 'Permissionnaires', 'th_list', '1', '0', '0','list', '[-1-2-17-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Permissionnaires' </li>";}
      // Action Task 204 - 'Permissionnaires'
      if(!$result_action_289 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_204, '2a7b7c239dc0352b32239b4c2c52d434', 'Permissionnaires', '".NULL."', '1', '-1-2-', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Permissionnaires' </li>";}
      // Action Task 204 - 'Editer Permissionnaire'
      if(!$result_action_290 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_204, '5545d9b708ef4335d089000884190f8e', 'Editer Permissionnaire', '".'<li><a href="#" class="this_url" redi="prm" data="%id%" rel="editprm" item="%id%" >
     <i class="ace-icon fa fa-pencil bigger-100"></i> Editer Permissionnaire
   </a></li>'."', '0', '-1-2-', '0', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Permissionnaire' </li>";}
      // Action Task 204 - 'Activation Permissionnaire'
      if(!$result_action_291 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_204, '4010e287a33f590302194abe583c2769', 'Activation Permissionnaire', '".'<li><a href="#" class="this_exec" data="%id%" rel="active_prm"  ><i class="ace-icon fa fa-lock bigger-100"></i> Activation Permissionnaire</a></li>'."', '0', '-1-2-', '0', '1', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Activation Permissionnaire' </li>";}
      // Action Task 204 - 'Désactivation Permissionnaire'
      if(!$result_action_292 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_204, '9f2f505f733491e365e6288ef0ddaf97', 'Désactivation Permissionnaire', '".'<li><a href="#" class="this_exec" data="%id%" rel="active_prm"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactivation Permissionnaire</a></li>'."', '0', '-1-2-', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactivation Permissionnaire' </li>";}
      // Action Task 204 - 'Détails permissionnaire'
      if(!$result_action_293 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_204, 'bac2fcf983191a463ef4cc57599b513f', 'Détails permissionnaire', '".'<li><a href="#" class="this_url" data="%id%" rel="consultprm"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails permissionnaire</a></li>'."', '0', '[-1-]', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails permissionnaire' </li>";}
      // Action Task 204 - 'Détails permissionnaire'
      if(!$result_action_294 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_204, '17e144faf4fced3c25db531a3732b99a', 'Détails permissionnaire', '".'<li><a href="#" class="this_url" data="%id%" rel="consultprm"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails permissionnaire</a></li>'."', '0', '[-1-]', '0', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails permissionnaire' </li>";}
  //Task 'addprm' 'Ajouter Permissionnaire'
  if(!$result_task_205 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addprm', $result_insert_modul, 'addprm','permissionnaire', '1', 'Ajouter Permissionnaire', '0', '1', '0', '0','form', NULL)")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Permissionnaire' </li>";}
      // Action Task 205 - 'Ajouter Permissionnaire'
      if(!$result_action_295 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_205, '468b54d82ba4133713f256835b6e4cd5', 'Ajouter Permissionnaire', '".NULL."', '1', '-1-2-', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Permissionnaire' </li>";}
  //Task 'editprm' 'Editer  Permissionnaire'
  if(!$result_task_206 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editprm', $result_insert_modul, 'editprm','permissionnaire', '1', 'Editer  Permissionnaire', '0', '1', '0', '0','form', NULL)")){$this->error = false; $this->log .= "<li> Error Import task 'Editer  Permissionnaire' </li>";}
      // Action Task 206 - 'Editer Permissionnaire'
      if(!$result_action_296 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_206, 'dd5043298578056c8d838731615da45c', 'Editer Permissionnaire', '".NULL."', '1', '-1-2-', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Permissionnaire' </li>";}
  //Task 'deleteprm' 'Supprimer Permissionnaire'
  if(!$result_task_207 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deleteprm', $result_insert_modul, 'deleteprm','permissionnaire', '1', 'Supprimer Permissionnaire', '0', '1', '1', '0','exec', NULL)")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Permissionnaire' </li>";}
      // Action Task 207 - 'Suprimer Permissionnaire'
      if(!$result_action_297 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_207, '6b071dcc20a2dac1ecd36b7db711174c', 'Suprimer Permissionnaire', '".'<li><a href="#" class="this_exec" data="id=%id%&idc=%idc%" rel="deleteprm" item="%id%" >
      <i class="ace-icon glyphicon glyphicon-remove"></i> Supprimer Permissionnaire
    </a></li>'."', '0', '-1-2-', '0', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Suprimer Permissionnaire' </li>";}
  //Task 'active_prm' 'Activation Permissionnaire'
  if(!$result_task_208 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('active_prm', $result_insert_modul, 'active_prm','permissionnaire', '1', 'Activation Permissionnaire', NULL, '1', '0', '0','exec', '[-1-2-17-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Activation Permissionnaire' </li>";}
      // Action Task 208 - 'Activation Permissionnaire'
      if(!$result_action_298 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_208, '3da72b5d644c08e3dd8bd31c35736629', 'Activation Permissionnaire', '".NULL."', '1', '-1-2-', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Activation Permissionnaire' </li>";}
  //Task 'consultprm' 'Détails permissionnaire'
  if(!$result_task_209 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('consultprm', $result_insert_modul, 'consultprm','permissionnaire', '1', 'Détails permissionnaire', 'eye', '1', '0', '0','profil', '[-1-2-17-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détails permissionnaire' </li>";}
      // Action Task 209 - 'Consulter permissionnaire'
      if(!$result_action_299 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc)VALUES($result_task_209, '6b1066b72e3c44ca6a91fd6fe4cf924e', 'Consulter permissionnaire', '".NULL."', '1', '[-1-]', '1', '0', NULL)")){$this->error = false; $this->log .= "<li> Error Import task_action 'Consulter permissionnaire' </li>";}
