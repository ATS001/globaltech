<?php 
//Export Module 'stock_actuel' Date: 14-05-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('stock_actuel', 'Stock Actuel','stock/submodul/stock_actuel','mouvements_stock','stock_actuel','stock','2', '0', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'stock_actuel' </li>";}
  //Task 'stock_actuel' 'Stock Actuel'
  if(!$result_task_886 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('stock_actuel', $result_insert_modul, 'stock_actuel','stock/submodul/stock_actuel', '1', 'Stock Actuel', 'external-link', '1', '0', '0','list', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Stock Actuel' </li>";}
      // Action Task 886 - 'Stock Actuel'
      if(!$result_action_1456 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_886, 'c88249c68efc019ecd2ba5a245337841', 'Stock Actuel','stock_actuel', NULL, '".NULL."', '1', '[-1-2-3-5-]', '0', '0', 'Stock Actuel','success','".'<span class="label label-sm label-success">Stock Actuel</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Stock Actuel' </li>";}
