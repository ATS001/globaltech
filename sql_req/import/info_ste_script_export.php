<?php 
//Export Module 'info_ste' Date: 02-04-2020
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('info_ste', 'Information société','Systeme/settings/info_ste','ste_info','info_ste','Systeme','1', '0', '[-1-2-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'info_ste' </li>";}
  //Task 'info_ste' 'Information société'
  if(!$result_task_542 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('info_ste', $result_insert_modul, 'info_ste','Systeme/settings/info_ste', '1', 'Information société', 'credit-card', '1', '0', '0','list', '[-1-3-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Information société' </li>";}
      // Action Task 542 - 'Information société'
      if(!$result_action_810 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_542, '72db1c2280dc3eb6405908c1c5b6c815', 'Information société','info_ste', NULL, '".NULL."', '0', '[-1-3-]', '0', '0', 'Confirmé','success','".'<span class="label label-sm label-success">Confirmé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Information société' </li>";}
