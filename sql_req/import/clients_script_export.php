<?php 
//Export Module 'clients' Date: 02-09-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('clients', 'Gestion Clients','clients','clients','clients',NULL,'0', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'clients' </li>";}
  //Task 'clients' 'Gestion Clients'
  if(!$result_task_332 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('clients', $result_insert_modul, 'clients','clients', '1', 'Gestion Clients', 'users', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Clients' </li>";}
      // Action Task 332 - 'Gestion Clients'
      if(!$result_action_496 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_332, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 'Gestion Clients', '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','danger',''<span class="label label-sm label-danger">Attente Validation</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Clients' </li>";}
      // Action Task 332 - 'Editer Client'
      if(!$result_action_509 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_332, 'dd3d5980299911ea854af4fa6f2e7309', 'Editer Client', '".'<li><a href="#" class="this_url" data="%id%" rel="editclient"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Client</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','danger',''<span class="label label-sm label-danger">Attente Validation</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Client' </li>";}
      // Action Task 332 - 'Valider Client'
      if(!$result_action_510 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_332, '3c5c04a20d49ad010557a64c8cdac1ce', 'Valider Client', '".'<li><a href="#" class="this_exec" data="%id%" rel="validclient"  ><i class="ace-icon fa fa-lock bigger-100"></i> Valider Client</a></li>'."', '0', '[-1-]', '0', '1', 'Attente Validation','danger',''<span class="label label-sm label-danger">Attente Validation</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Client' </li>";}
      // Action Task 332 - 'Désactiver Client'
      if(!$result_action_511 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_332, '18ace52052f2551099ecaabf049ffaec', 'Désactiver Client', '".'<li><a href="#" class="this_exec" data="%id%" rel="validclient"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Client</a></li>'."', '0', '[-1-]', '1', '0', 'Client Validé','success',''<span class="label label-sm label-success">Client Validé</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver Client' </li>";}
      // Action Task 332 - 'Détails Client'
      if(!$result_action_513 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_332, '493f9e55fc0340763e07514c1900685a', 'Détails Client', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsclient"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Client</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','danger',''<span class="label label-sm label-danger">Attente Validation</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Client' </li>";}
      // Action Task 332 - 'Détails  Client'
      if(!$result_action_515 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_332, '03b4f949b088e41fc9a1f3f23b7906a8', 'Détails  Client', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsclient"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Client</a></li>'."', '0', '[-1-]', '1', '0', 'Client Validé','success',''<span class="label label-sm label-success">Client Validé</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails  Client' </li>";}
  //Task 'addclient' 'Ajouter Client'
  if(!$result_task_338 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addclient', $result_insert_modul, 'addclient','clients', '1', 'Ajouter Client', 'users', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Client' </li>";}
      // Action Task 338 - 'Ajouter Client'
      if(!$result_action_502 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_338, '2b9d8bb8f752d1c35fb681c33e38b42b', 'Ajouter Client', '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','danger',''<span class="label label-sm label-danger">Attente Validation</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Client' </li>";}
  //Task 'editclient' 'Editer Client'
  if(!$result_task_339 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editclient', $result_insert_modul, 'editclient','clients', '1', 'Editer Client', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Client' </li>";}
      // Action Task 339 - 'Editer Client'
      if(!$result_action_503 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_339, '54aa9121e05f5e698d354022a8eab71d', 'Editer Client', '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','danger',''<span class="label label-sm label-danger">Attente Validation</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Client' </li>";}
  //Task 'deleteclient' 'Supprimer Client'
  if(!$result_task_340 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deleteclient', $result_insert_modul, 'deleteclient','clients', '1', 'Supprimer Client', 'trash', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Client' </li>";}
      // Action Task 340 - 'Supprimer Client'
      if(!$result_action_504 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_340, '4eaf650e8c2221d590fac5a6a6952231', 'Supprimer Client', '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','danger',''<span class="label label-sm label-danger">Attente Validation</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Client' </li>";}
  //Task 'validclient' 'Valider Client'
  if(!$result_task_341 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validclient', $result_insert_modul, 'validclient','clients', '1', 'Valider Client', 'lock', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider Client' </li>";}
      // Action Task 341 - 'Valider Client'
      if(!$result_action_505 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_341, '534cd4b17fb8a371d3a20565ab8fd96e', 'Valider Client', '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','danger',''<span class="label label-sm label-danger">Attente Validation</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Client' </li>";}
  //Task 'detailsclient' 'Détails Client'
  if(!$result_task_342 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailsclient', $result_insert_modul, 'detailsclient','clients', '1', 'Détails Client', 'eye', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détails Client' </li>";}
      // Action Task 342 - 'Détails Client'
      if(!$result_action_512 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_342, '95bb6aa696ef630a335aa84e1e425e2c', 'Détails Client', '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','danger',''<span class="label label-sm label-danger">Attente Validation</span>'')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Client' </li>";}
