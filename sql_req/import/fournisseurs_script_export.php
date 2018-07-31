<?php 
//Export Module 'fournisseurs' Date: 01-08-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('fournisseurs', 'Gestion Fournisseurs','gestion_fournisseurs/submodul/fournisseurs','fournisseurs','fournisseurs','gestion_fournisseurs','2', '0', '[-1-2-3-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'fournisseurs' </li>";}
  //Task 'fournisseurs' 'Gestion Fournisseurs'
  if(!$result_task_502 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('fournisseurs', $result_insert_modul, 'fournisseurs','gestion_fournisseurs/submodul/fournisseurs', '1', 'Gestion Fournisseurs', 'user', '1', '0', '0','list', '[-1-2-3-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Fournisseurs' </li>";}
      // Action Task 502 - 'Gestion Fournisseurs'
      if(!$result_action_725 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_502, '6beb279abea6434e3b73229aebadc081', 'Gestion Fournisseurs','fournisseurs', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Fournisseurs' </li>";}
      // Action Task 502 - 'Editer Fournisseur'
      if(!$result_action_730 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_502, 'ff95747f3a590b6539803f2a9a394cd5', 'Editer Fournisseur','editfournisseur', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editfournisseur"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Fournisseur</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Fournisseur' </li>";}
      // Action Task 502 - 'Valider Fournisseur'
      if(!$result_action_731 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_502, 'fea982f5074995d4ccd6211a71ab2680', 'Valider Fournisseur','validfournisseur', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validfournisseur"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Fournisseur</a></li>'."', '0', '[-1-]', '0', '1', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Fournisseur' </li>";}
      // Action Task 502 - 'Désactiver Fournisseur'
      if(!$result_action_732 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_502, '1d0411a0dec15fc28f054f1a79d95618', 'Désactiver Fournisseur','validfournisseur', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validfournisseur"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Fournisseur</a></li>'."', '0', '[-1-]', '1', '0', 'Fournisseur Validé','success','".'<span class="label label-sm label-success">Fournisseur Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver Fournisseur' </li>";}
      // Action Task 502 - 'Détails Fournisseur'
      if(!$result_action_737 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_502, 'a52affdd109b9362ce47ff18aad53e2a', 'Détails Fournisseur','detailsfournisseur', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfournisseur"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Fournisseur</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Fournisseur' </li>";}
      // Action Task 502 - 'Détails  Fournisseur'
      if(!$result_action_738 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_502, 'c6fe5f222dd563204188e8bf0d69bd9e', 'Détails  Fournisseur','detailsfournisseur', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfournisseur"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Fournisseur</a></li>'."', '0', '[-1-]', '1', '0', 'Fournisseur Validé','success','".'<span class="label label-sm label-success">Fournisseur Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails  Fournisseur' </li>";}
      // Action Task 502 - 'Bloquer Fournisseur'
      if(!$result_action_1559 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_502, '3aa071f22e7a133cf9b7a49c6593f817', 'Bloquer Fournisseur','bloquerfournisseur', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="bloquerfournisseur"  ><i class="ace-icon fa fa-ban bigger-100"></i> Bloquer Fournisseur</a></li>'."', '0', '[-1-3-]', '1', '0', 'Fournisseur Validé','success','".'<span class="label label-sm label-success">Fournisseur Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Bloquer Fournisseur' </li>";}
      // Action Task 502 - 'Détails Fournisseur'
      if(!$result_action_1560 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_502, 'eebdb52b245b1c6d6f502b8a6da82532', 'Détails Fournisseur','detailsfournisseur', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfournisseur"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Fournisseur</a></li>'."', '0', '[-1-3-]', '2', '0', 'Fournisseur Bloqué','danger','".'<span class="label label-sm label-danger">Fournisseur Bloqué</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Fournisseur' </li>";}
  //Task 'addfournisseur' 'Ajouter Fournisseur'
  if(!$result_task_503 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addfournisseur', $result_insert_modul, 'addfournisseur','gestion_fournisseurs/submodul/fournisseurs', '1', 'Ajouter Fournisseur', 'user', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Fournisseur' </li>";}
      // Action Task 503 - 'Ajouter Fournisseur'
      if(!$result_action_726 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_503, 'd644015625a9603adb2fcc36167aeb73', 'Ajouter Fournisseur','addfournisseur', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Fournisseur' </li>";}
  //Task 'editfournisseur' 'Editer Fournisseur'
  if(!$result_task_504 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editfournisseur', $result_insert_modul, 'editfournisseur','gestion_fournisseurs/submodul/fournisseurs', '1', 'Editer Fournisseur', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Fournisseur' </li>";}
      // Action Task 504 - 'Editer Fournisseur'
      if(!$result_action_727 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_504, '58c6694abfd3228d927a5d5a06d40b94', 'Editer Fournisseur','editfournisseur', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Fournisseur' </li>";}
  //Task 'deletefournisseur' 'Supprimer Fournisseur'
  if(!$result_task_505 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletefournisseur', $result_insert_modul, 'deletefournisseur','gestion_fournisseurs/submodul/fournisseurs', '1', 'Supprimer Fournisseur', 'trash', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Fournisseur' </li>";}
      // Action Task 505 - 'Supprimer Fournisseur'
      if(!$result_action_728 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_505, 'd072f81cd779e4b0152953241d713ca3', 'Supprimer Fournisseur','deletefournisseur', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Fournisseur' </li>";}
  //Task 'validfournisseur' 'Valider Fournisseur'
  if(!$result_task_506 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validfournisseur', $result_insert_modul, 'validfournisseur','gestion_fournisseurs/submodul/fournisseurs', '1', 'Valider Fournisseur', 'lock', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider Fournisseur' </li>";}
      // Action Task 506 - 'Valider Fournisseur'
      if(!$result_action_729 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_506, '657351ce5aa227513e3b50dea77db918', 'Valider Fournisseur','validfournisseur', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Fournisseur' </li>";}
  //Task 'detailsfournisseur' 'Détails Fournisseur'
  if(!$result_task_508 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailsfournisseur', $result_insert_modul, 'detailsfournisseur','gestion_fournisseurs/submodul/fournisseurs', '1', 'Détails Fournisseur', 'eye', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détails Fournisseur' </li>";}
      // Action Task 508 - 'Détails Fournisseur'
      if(!$result_action_736 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_508, '83b693fe35a1be29edafe4f6170641aa', 'Détails Fournisseur','detailsfournisseur', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Fournisseur' </li>";}
  //Task 'bloquerfournisseur' 'Bloquer Fournisseur'
  if(!$result_task_930 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('bloquerfournisseur', $result_insert_modul, 'bloquerfournisseur','gestion_fournisseurs/submodul/fournisseurs', '1', 'Bloquer Fournisseur', 'ban', '1', '0', '0','formadd', '[-1-2-3-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Bloquer Fournisseur' </li>";}
      // Action Task 930 - 'Bloquer Fournisseur'
      if(!$result_action_1558 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_930, '351c30cb46c5c2d00066315c15c756bc', 'Bloquer Fournisseur','bloquerfournisseur', NULL, '".NULL."', '1', '[-1-3-]', '0', '0', 'Fournisseur Validé','success','".'<span class="label label-sm label-success">Fournisseur Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Bloquer Fournisseur' </li>";}
