<?php 
//Export Module 'gestion_fournisseurs' Date: 01-08-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('gestion_fournisseurs', 'Fournisseurs','gestion_fournisseurs/main','fournisseurs','gestion_fournisseurs',NULL,'0', '0', '[-1-2-3-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'gestion_fournisseurs' </li>";}
  //Task 'gestion_fournisseurs' 'Fournisseurs'
  if(!$result_task_931 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('gestion_fournisseurs', $result_insert_modul, 'gestion_fournisseurs','gestion_fournisseurs/main', '1', 'Fournisseurs', 'user', '1', '0', '0','list', '[-1-2-3-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Fournisseurs' </li>";}
      // Action Task 931 - 'Fournisseurs'
      if(!$result_action_1561 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_931, 'c606662f182f7cf5a5fb89168fcee485', 'Fournisseurs','gestion_fournisseurs', NULL, '".NULL."', '1', '[-1-2-3-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Fournisseurs' </li>";}
