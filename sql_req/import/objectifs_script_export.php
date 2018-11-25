<?php 
//Export Module 'objectifs' Date: 25-11-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('objectifs', 'Gestion Objectifs','objectifs/main','objectifs','objectifs',NULL,'0', '0', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'objectifs' </li>";}
  //Task 'objectifs' 'Gestion Objectifs'
  if(!$result_task_1076 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('objectifs', $result_insert_modul, 'objectifs','objectifs/main', '1', 'Gestion Objectifs', 'table', '1', '0', '0','list', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Objectifs' </li>";}
      // Action Task 1076 - 'Gestion Objectifs'
      if(!$result_action_1839 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1076, 'a61e43daaa79c1b964d9bf3ebde7a0d5', 'Gestion Objectifs','objectifs', NULL, '".NULL."', '1', '[-1-2-3-7-]', '0', '0', 'Attente validation','success','".'<span class="label label-sm label-success">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Objectifs' </li>";}
