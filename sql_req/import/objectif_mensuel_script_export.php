<?php 
//Export Module 'objectif_mensuel' Date: 29-12-2019
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('objectif_mensuel', 'Objectifs mensuels','objectifs/submodul/objectif_mensuel','objectif_mensuels','objectif_mensuel','objectifs','2', '0', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'objectif_mensuel' </li>";}
  //Task 'objectif_mensuel' 'Objectifs mensuels'
  if(!$result_task_1164 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('objectif_mensuel', $result_insert_modul, 'objectif_mensuel','objectifs/submodul/objectif_mensuel', '1', 'Objectifs mensuels', 'chart', '1', '0', '0','list', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Objectifs mensuels' </li>";}
      // Action Task 1164 - 'Objectifs mensuels'
      if(!$result_action_2009 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, 'c3c0b71b5d3aba8740f39f9d1759dfdd', 'Objectifs mensuels','objectif_mensuel', NULL, '".NULL."', '1', '[-1-2-3-7-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Objectifs mensuels' </li>";}
      // Action Task 1164 - 'Editer Objectif mensuel'
      if(!$result_action_2013 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, '6cb2fb5be7e7d0ca5e7885e3ec5f02cb', 'Editer Objectif mensuel','edit_objectif_mensuel', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="edit_objectif_mensuel"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Objectif mensuel</a></li>'."', '0', '[-1-2-3-]', '0', '0', 'Attente démarage','warning','".'<span class="label label-sm label-warning">Attente démarage</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Objectif mensuel' </li>";}
      // Action Task 1164 - 'Détails objectif mensuel'
      if(!$result_action_2016 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, '0520edf8955cc7f20911461369d36474', 'Détails objectif mensuel','details_objectif_mensuel', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="details_objectif_mensuel"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails objectif mensuel</a></li>'."', '0', '[-1-2-3-7-]', '0', '0', 'Attente démarage','warning','".'<span class="label label-sm label-warning">Attente démarage</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails objectif mensuel' </li>";}
      // Action Task 1164 - 'Détails objectif mensuel'
      if(!$result_action_2017 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, '1160de7c0d9b5bd5385cc17710e9d003', 'Détails objectif mensuel','details_objectif_mensuel', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="details_objectif_mensuel"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails objectif mensuel</a></li>'."', '0', '[-1-2-3-7-]', '1', '0', 'Objectif en cours','success','".'<span class="label label-sm label-success">Objectif en cours</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails objectif mensuel' </li>";}
      // Action Task 1164 - 'Détails objectif mensuel'
      if(!$result_action_2018 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, '27949281028bd4973ad19969869e18c6', 'Détails objectif mensuel','details_objectif_mensuel', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="details_objectif_mensuel"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails objectif mensuel</a></li>'."', '0', '[-1-2-3-7-]', '2', '0', 'Objectif terminé','info','".'<span class="label label-sm label-info">Objectif terminé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails objectif mensuel' </li>";}
      // Action Task 1164 - 'Détails objectif mensuel'
      if(!$result_action_2019 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, 'b03314139a15a21be3b0f0b0bd37ca85', 'Détails objectif mensuel','details_objectif_mensuel', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="details_objectif_mensuel"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails objectif mensuel</a></li>'."', '0', '[-1-2-3-7-]', '3', '0', 'Seuil Objectif atteint','inverse','".'<span class="label label-sm label-inverse">Seuil Objectif atteint</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails objectif mensuel' </li>";}
      // Action Task 1164 - 'Détails objectif mensuel'
      if(!$result_action_2020 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, 'f0319cb62eabd3f8d1123e20ab19aaf4', 'Détails objectif mensuel','details_objectif_mensuel', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="details_objectif_mensuel"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails objectif mensuel</a></li>'."', '0', '[-1-2-3-7-]', '4', '0', 'Objectif raté','danger','".'<span class="label label-sm label-danger">Objectif raté</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails objectif mensuel' </li>";}
      // Action Task 1164 - 'Suspendre Objectif'
      if(!$result_action_2021 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, 'e5eb138410f2435b09f384dcc0b53075', 'Suspendre Objectif','suspendre_objectif_mensuel', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="suspendre_objectif_mensuel"  ><i class="ace-icon fa fa-ban bigger-100"></i> Suspendre Objectif</a></li>'."', '0', '[-1-2-3-]', '0', '0', 'Attente démarage','warning','".'<span class="label label-sm label-warning">Attente démarage</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Suspendre Objectif' </li>";}
      // Action Task 1164 - 'Détails objectif mensuel'
      if(!$result_action_2022 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, 'b58b541416a76610060fdba4e72b2e21', 'Détails objectif mensuel','details_objectif_mensuel', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="details_objectif_mensuel"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails objectif mensuel</a></li>'."', '0', '[-1-2-3-]', '6', '0', 'Objectif suspendue','danger','".'<span class="label label-sm label-danger">Objectif suspendue</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails objectif mensuel' </li>";}
      // Action Task 1164 - 'Suspendre Objectif'
      if(!$result_action_2023 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, '42ff901896d57d327dacae3dcdf24d30', 'Suspendre Objectif','suspendre_objectif_mensuel', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="suspendre_objectif_mensuel"  ><i class="ace-icon fa fa-ban bigger-100"></i> Suspendre Objectif</a></li>'."', '0', '[-1-2-3-]', '1', '0', 'Objectif en cours','success','".'<span class="label label-sm label-success">Objectif en cours</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Suspendre Objectif' </li>";}
      // Action Task 1164 - 'Forcer commission'
      if(!$result_action_2025 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, '99d3a5ee0bfa9ec3969dc564d88a4a38', 'Forcer commission','force_commission_object', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="force_commission_object"  ><i class="ace-icon fa fa-dollars bigger-100"></i> Forcer commission</a></li>'."', '0', '[-1-2-3-]', '4', '0', 'Objectif raté','danger','".'<span class="label label-sm label-danger">Objectif raté</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Forcer commission' </li>";}
      // Action Task 1164 - 'Détails objectif mensuel'
      if(!$result_action_2026 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1164, 'b7fa6db5d65532462d68f62d339279a6', 'Détails objectif mensuel','details_objectif_mensuel', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="details_objectif_mensuel"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails objectif mensuel</a></li>'."', '0', '[-1-2-3-7-6-]', '5', '0', 'Commission forcée','inverse','".'<span class="label label-sm label-inverse">Commission forcée</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails objectif mensuel' </li>";}
  //Task 'add_objectif_mensuel' 'Ajouter Objectif mensuel'
  if(!$result_task_1165 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('add_objectif_mensuel', $result_insert_modul, 'add_objectif_mensuel','objectifs/submodul/objectif_mensuel', '1', 'Ajouter Objectif mensuel', 'plus', '1', '0', '0','formadd', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Objectif mensuel' </li>";}
      // Action Task 1165 - 'Ajouter Objectif mensuel'
      if(!$result_action_2010 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1165, '8b3e3aa7cc9e4351b9284cc15354d66a', 'Ajouter Objectif mensuel','add_objectif_mensuel', NULL, '".NULL."', '1', '[-1-2-3-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Objectif mensuel' </li>";}
  //Task 'edit_objectif_mensuel' 'Editer Objectif mensuel'
  if(!$result_task_1166 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('edit_objectif_mensuel', $result_insert_modul, 'edit_objectif_mensuel','objectifs/submodul/objectif_mensuel', '1', 'Editer Objectif mensuel', 'pen', '1', '0', '0','formedit', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Objectif mensuel' </li>";}
      // Action Task 1166 - 'Editer Objectif mensuel'
      if(!$result_action_2011 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1166, '7f596437814697c7a051ae92c2d0dee6', 'Editer Objectif mensuel','edit_objectif_mensuel', NULL, '".NULL."', '1', '[-1-2-3-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Objectif mensuel' </li>";}
  //Task 'details_objectif_mensuel' 'Détails objectif mensuel'
  if(!$result_task_1168 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('details_objectif_mensuel', $result_insert_modul, 'details_objectif_mensuel','objectifs/submodul/objectif_mensuel', '1', 'Détails objectif mensuel', 'eye', '1', '0', '0','profil', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détails objectif mensuel' </li>";}
      // Action Task 1168 - 'Détails objectif mensuel'
      if(!$result_action_2015 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1168, '8b503789312df443678798ef55891969', 'Détails objectif mensuel','details_objectif_mensuel', NULL, '".NULL."', '1', '[-1-2-3-7-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails objectif mensuel' </li>";}
  //Task 'suspendre_objectif_mensuel' 'Suspendre Objectif'
  if(!$result_task_1169 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('suspendre_objectif_mensuel', $result_insert_modul, 'suspendre_objectif_mensuel','objectifs/submodul/objectif_mensuel', '1', 'Suspendre Objectif', 'pause-circle-o', '1', '0', '0','exec', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Suspendre Objectif' </li>";}
  //Task 'force_commission_object' 'Forcer commission'
  if(!$result_task_1170 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('force_commission_object', $result_insert_modul, 'force_commission_object','objectifs/submodul/objectif_mensuel', '1', 'Forcer commission', 'dollar', '1', '0', '0','formpers', '[-1-2-3-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Forcer commission' </li>";}
      // Action Task 1170 - 'Forcer commission'
      if(!$result_action_2024 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1170, '18209f748cb598960deaa97d5bd257fe', 'Forcer commission','force_commission_object', NULL, '".NULL."', '1', 'null', '0', '0', 'Objectif raté','danger','".'<span class="label label-sm label-danger">Objectif raté</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Forcer commission' </li>";}
