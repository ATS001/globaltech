<?php 
//Export Module 'vente' Date: 14-09-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('vente', 'Gestion Vente','vente/main','devis','vente',NULL,'0', '0', '[-1-2-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'vente' </li>";}
  //Task 'vente' 'Gestion Vente'
  if(!$result_task_384 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('vente', $result_insert_modul, 'vente','vente/main', '1', 'Gestion Vente', 'money', '1', '0', '0','list', '[-1-2-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Vente' </li>";}
      // Action Task 384 - 'Gestion Vente'
      if(!$result_action_585 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_384, '3d4eaa53061f51b0c4435bd8e4b89c17', 'Gestion Vente','vente', NULL, '".NULL."', '0', '[-1-2-]', '0', '0', 'Actif','success','".'<span class="label label-sm label-success">Actif</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Vente' </li>";}
