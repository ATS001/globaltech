<?php 
//Export Module 'mouvements_stock' Date: 14-05-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('mouvements_stock', 'Mouvements de Stock','stock/submodul/mouvements_stock','stock','mouvements_stock','stock','2', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'mouvements_stock' </li>";}
  //Task 'mouvements_stock' 'Mouvements de Stock'
  if(!$result_task_864 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('mouvements_stock', $result_insert_modul, 'mouvements_stock','stock/submodul/mouvements_stock', '1', 'Mouvements de Stock', 'refresh', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Mouvements de Stock' </li>";}
      // Action Task 864 - 'Mouvements de Stock'
      if(!$result_action_1417 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_864, '530dbb6317ed3d348cd55d1f9a09e361', 'Mouvements de Stock','mouvements_stock', NULL, '".NULL."', '1', '[-1-]', '0', '0', 'Entrée Stock','info','".'<span class="label label-sm label-info">Entrée Stock</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Mouvements de Stock' </li>";}
