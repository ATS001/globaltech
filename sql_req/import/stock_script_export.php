<?php 
//Export Module 'stock' Date: 14-05-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('stock', 'Gestion de Stock','stock/main','stock','stock',NULL,'0', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'stock' </li>";}
  //Task 'stock' 'Gestion de Stock'
  if(!$result_task_858 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('stock', $result_insert_modul, 'stock','stock/main', '1', 'Gestion de Stock', 'barcode ', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion de Stock' </li>";}
      // Action Task 858 - 'Gestion de Stock'
      if(!$result_action_1408 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_858, '79c262a9849332f387662790b8da4399', 'Gestion de Stock','stock', NULL, '".NULL."', '1', '[-1-]', '0', '0', 'Article en stock','success','".'<span class="label label-sm label-success">Article en stock</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion de Stock' </li>";}
