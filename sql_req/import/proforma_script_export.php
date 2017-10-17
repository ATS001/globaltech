<?php 
//Export Module 'proforma' Date: 15-10-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('proforma', 'Gestion Proforma','vente/submodul/proforma','proforma','proforma','vente','2', '0', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'proforma' </li>";}
  //Task 'proforma' 'Gestion Proforma'
  if(!$result_task_519 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('proforma', $result_insert_modul, 'proforma','vente/submodul/proforma', '1', 'Gestion Proforma', 'book', '1', '0', '0','list', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Proforma' </li>";}
      // Action Task 519 - 'Gestion Proforma'
      if(!$result_action_758 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_519, '1eb847d87adcad78d5e951e6110061e5', 'Gestion Proforma','proforma', NULL, '".NULL."', '0', '[-1-2-3-5-4-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Proforma' </li>";}
      // Action Task 519 - 'Editer proforma'
      if(!$result_action_759 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_519, '44ef6849d8d5d17d8e0535187e923d32', 'Editer proforma','editproforma', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editproforma"  ><i class="ace-icon fa fa-pen blue bigger-100"></i> Editer proforma</a></li>'."', '0', '[-1-2-3-5-]', '0', '1', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer proforma' </li>";}
      // Action Task 519 - 'Valider Proforma'
      if(!$result_action_760 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_519, 'b7ce06be726011362a271678547a803c', 'Valider Proforma','validproforma', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validproforma"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider Proforma</a></li>'."', '0', '[-1-3-5-]', '0', '1', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Proforma' </li>";}
      // Action Task 519 - 'Détail Proforma'
      if(!$result_action_761 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_519, 'abd8c50f1d2ef4beeeddb68a72973587', 'Détail Proforma','viewproforma', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="viewproforma"  ><i class="ace-icon fa fa-eye blue bigger-100"></i> Détail Proforma</a></li>'."', '0', '[-1-2-3-5-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail Proforma' </li>";}
      // Action Task 519 - 'Détail Proforma'
      if(!$result_action_762 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_519, '35a88b5c359908b063ac98cafc622987', 'Détail Proforma','viewproforma', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="viewproforma"  ><i class="ace-icon fa fa-eye blue bigger-100"></i> Détail Proforma</a></li>'."', '0', '[-1-2-3-5-]', '2', '0', 'Proforma envoyée au client','success','".'<span class="label label-sm label-success">Proforma envoyée au client</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail Proforma' </li>";}
      // Action Task 519 - 'Détail Proforma'
      if(!$result_action_1013 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_519, 'e20d83df90355eca2a65f56a2556601f', 'Détail Proforma','viewproforma', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="viewproforma"  ><i class="ace-icon fa fa-eye blue bigger-100"></i> Détail Proforma</a></li>'."', '0', '[-1-2-3-5-]', '1', '0', 'Attente Expédition','info','".'<span class="label label-sm label-info">Attente Expédition</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail Proforma' </li>";}
      // Action Task 519 - 'Envoi proforma au client'
      if(!$result_action_1015 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_519, '252ed64d8956e20fb88c1be41688f74a', 'Envoi proforma au client','validproforma', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validproforma"  ><i class="ace-icon fa fa-envelope bigger-100"></i> Envoi proforma au client</a></li>'."', '0', '[-1-2-]', '1', '1', 'Attente Expédition','info','".'<span class="label label-sm label-info">Attente Expédition</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Envoi proforma au client' </li>";}
  //Task 'addproforma' 'Ajouter pro-forma'
  if(!$result_task_520 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addproforma', $result_insert_modul, 'addproforma','vente/submodul/proforma', '1', 'Ajouter pro-forma', 'plus', '1', '0', '0','form', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter pro-forma' </li>";}
      // Action Task 520 - 'Ajouter pro-forma'
      if(!$result_action_763 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_520, 'd5a6338765b9eab63104b59f01c06114', 'Ajouter pro-forma','addproforma', NULL, '".NULL."', '0', '[-1-2-3-5-]', '0', '0', 'Brouillon','warning','".'<span class="label label-sm label-warning">Brouillon</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter pro-forma' </li>";}
  //Task 'editproforma' 'Editer proforma'
  if(!$result_task_521 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editproforma', $result_insert_modul, 'editproforma','vente/submodul/proforma', '1', 'Editer proforma', 'pen', '1', '0', '0','form', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer proforma' </li>";}
      // Action Task 521 - 'Editer proforma'
      if(!$result_action_764 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_521, '95831bde77bc886d6ab4dd5e734de743', 'Editer proforma','editproforma', NULL, '".NULL."', '0', '[-1-2-3-5-]', '0', '0', 'Brouillon','warning','".'<span class="label label-sm label-warning">Brouillon</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer proforma' </li>";}
  //Task 'add_detailproforma' 'Ajouter détail proforma'
  if(!$result_task_522 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('add_detailproforma', $result_insert_modul, 'add_detailproforma','vente/submodul/proforma', '1', 'Ajouter détail proforma', 'plus', '1', '0', '0','form', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter détail proforma' </li>";}
      // Action Task 522 - 'Ajouter détail proforma'
      if(!$result_action_765 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_522, 'cbb4e1efa1c05b42d25a3a6bcab038a2', 'Ajouter détail proforma','adddetailproforma', NULL, '".NULL."', '0', '[-1-2-3-5-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter détail proforma' </li>";}
  //Task 'validproforma' 'valider Proforma'
  if(!$result_task_523 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validproforma', $result_insert_modul, 'validproforma','vente/submodul/proforma', '1', 'valider Proforma', 'check', '1', '0', '0','exec', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'valider Proforma' </li>";}
      // Action Task 523 - 'valider Proforma'
      if(!$result_action_766 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_523, 'e9f745054778257a255452c6609461a0', 'valider Proforma','validproforma', NULL, '".NULL."', '0', '[-1-2-3-5-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'valider Proforma' </li>";}
  //Task 'viewproforma' 'Détail Pro-forma'
  if(!$result_task_524 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('viewproforma', $result_insert_modul, 'viewproforma','vente/submodul/proforma', '1', 'Détail Pro-forma', 'eye', '1', '0', '0','profil', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détail Pro-forma' </li>";}
      // Action Task 524 - 'Détail Pro-forma'
      if(!$result_action_767 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_524, 'defef148c404c7e6ac79e4783e0a7ab7', 'Détail Pro-forma','viewproforma', NULL, '".NULL."', '0', '[-1-2-3-5-]', '0', '0', 'Attente validation','warning','".'Attente validation'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail Pro-forma' </li>";}
  //Task 'edit_detailproforma' 'Editer détail proforma'
  if(!$result_task_525 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('edit_detailproforma', $result_insert_modul, 'edit_detailproforma','vente/submodul/proforma', '1', 'Editer détail proforma', 'pen', '1', '0', '0','form', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer détail proforma' </li>";}
      // Action Task 525 - 'Editer détail proforma'
      if(!$result_action_768 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_525, '53008d64edf241c937a06f03eff139aa', 'Editer détail proforma','edit_detailproforma', NULL, '".NULL."', '0', '[-1-2-3-5-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer détail proforma' </li>";}
  //Task 'deleteproforma' 'Supprimer proforma'
  if(!$result_task_652 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deleteproforma', $result_insert_modul, 'deleteproforma','vente/submodul/proforma', '1', 'Supprimer proforma', 'trash', '1', '0', '0','exec', '[-1-2-3-5-4-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer proforma' </li>";}
      // Action Task 652 - 'Supprimer proforma'
      if(!$result_action_1011 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_652, '30e9d3d1ad17eb7f1fc0d5cbb9b58482', 'Supprimer proforma','deleteproforma', NULL, '".NULL."', '1', '[-1-2-3-5-]', '0', '0', 'Attente validation','warning','".'<span class="label label-sm label-warning">Attente validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer proforma' </li>";}