<?php 
//Export Module 'devis' Date: 14-09-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('devis', 'Gestion Devis','vente/submodul/devis','devis','devis','vente','2', '0', '[-1-2-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'devis' </li>";}
  //Task 'devis' 'Gestion Devis'
  if(!$result_task_385 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('devis', $result_insert_modul, 'devis','vente/submodul/devis', '1', 'Gestion Devis', 'paper-plane-o', '1', '0', '0','list', '[-1-2-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Devis' </li>";}
      // Action Task 385 - 'Gestion Devis'
      if(!$result_action_586 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_385, '0e79510db7f03b9b6266fc7b4a612153', 'Gestion Devis','devis', NULL, '".NULL."', '0', '[-1-2-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Devis' </li>";}
      // Action Task 385 - 'Modifier Devis'
      if(!$result_action_590 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_385, 'c15b00a1e37657336df8b6aa0eea2db5', 'Modifier Devis','editdevis', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editdevis"  ><i class="ace-icon fa fa-pencil-square-o blue bigger-100"></i> Modifier Devis</a></li>'."', '0', '[-1-2-]', '0', '1', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier Devis' </li>";}
  //Task 'adddevis' 'Ajouter Devis'
  if(!$result_task_388 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('adddevis', $result_insert_modul, 'adddevis','vente/submodul/devis', '1', 'Ajouter Devis', 'plus', '1', '0', '0','form', '[-1-2-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Devis' </li>";}
      // Action Task 388 - 'Ajouter Devis'
      if(!$result_action_587 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_388, 'd9eeb330625c1b87e0df00986a47be01', 'Ajouter Devis','adddevis', NULL, '".NULL."', '0', '[-1-2-]', '0', '0', 'Brouillon','success','".'<span class="label label-sm label-success">Brouillon</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Devis' </li>";}
  //Task 'add_detaildevis' 'Ajouter détail devis'
  if(!$result_task_389 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('add_detaildevis', $result_insert_modul, 'add_detaildevis','vente/submodul/devis', '1', 'Ajouter détail devis', 'plus', '1', '0', '0','form', '[-1-2-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter détail devis' </li>";}
      // Action Task 389 - 'Ajouter détail devis'
      if(!$result_action_588 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_389, 'da93cdb05137e15aed9c4c18bddd746a', 'Ajouter détail devis','add_detaildevis', NULL, '".NULL."', '0', '[-1-2-]', '0', '0', 'Attente validation','success','".'<span class="label label-sm label-success">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter détail devis' </li>";}
  //Task 'editdevis' 'Modifier Devis'
  if(!$result_task_390 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editdevis', $result_insert_modul, 'editdevis','vente/submodul/devis', '1', 'Modifier Devis', 'pen', '1', '0', '0','form', '[-1-2-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier Devis' </li>";}
      // Action Task 390 - 'Modifier Devis'
      if(!$result_action_589 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_390, 'f9f3c299f9bd0fec014f6bd3f0e06adb', 'Modifier Devis','editdevis', NULL, '".NULL."', '0', '[-1-2-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier Devis' </li>";}
  //Task 'deletedevis' 'Supprimer Devis'
  if(!$result_task_391 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletedevis', $result_insert_modul, 'deletedevis','vente/submodul/devis', '1', 'Supprimer Devis', 'trash red', '1', '0', '0','exec', '[-1-2-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Devis' </li>";}
      // Action Task 391 - 'Supprimer Devis'
      if(!$result_action_591 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_391, 'e14cce6f1faf7784adb327581c516b90', 'Supprimer Devis','deletedevis', NULL, '".NULL."', '0', '[-1-3-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Devis' </li>";}
  //Task 'edit_detaildevis' 'Modifier détail Devis'
  if(!$result_task_392 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('edit_detaildevis', $result_insert_modul, 'edit_detaildevis','vente/submodul/devis', '1', 'Modifier détail Devis', 'pen', '1', '0', '0','form', '[-1-2-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier détail Devis' </li>";}
      // Action Task 392 - 'Modifier détail Devis'
      if(!$result_action_592 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_392, '38f10871792c133ebcc6040e9a11cde8', 'Modifier détail Devis','edit_detaildevis', NULL, '".NULL."', '0', '[-1-2-3-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier détail Devis' </li>";}
