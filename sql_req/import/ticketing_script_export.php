<?php 
//Export Module 'ticketing' Date: 02-08-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('ticketing', 'Gestion Tickets','ticketing/main','tickets','ticketing',NULL,'0', '0', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'ticketing' </li>";}
  //Task 'ticketing' 'Gestion Tickets'
  if(!$result_task_887 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('ticketing', $result_insert_modul, 'ticketing','ticketing/main', '1', 'Gestion Tickets', 'ticket', '1', '0', '0','list', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Tickets' </li>";}
      // Action Task 887 - 'Gestion Tickets'
      if(!$result_action_1466 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_887, '8533505d138266008d6d8a4066daa545', 'Gestion Tickets','ticketing', NULL, '".NULL."', '1', '[-1-2-3-5-4-7-6-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Tickets' </li>";}
