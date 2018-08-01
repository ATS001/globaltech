<?php 
//Export Module 'clients' Date: 01-08-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('clients', 'Gestion Clients','clients','clients','clients',NULL,'0', '0', '[-1-2-3-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'clients' </li>";}
  //Task 'clients' 'Gestion Clients'
  if(!$result_task_394 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('clients', $result_insert_modul, 'clients','clients', '1', 'Gestion Clients', 'users', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Clients' </li>";}
      // Action Task 394 - 'Gestion Clients'
      if(!$result_action_553 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, 'f12fb1c50aedc49c3fa3dfa2bd297bd3', 'Gestion Clients','clients', NULL, '".' '."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Clients' </li>";}
      // Action Task 394 - 'Editer Client'
      if(!$result_action_554 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, 'fdd6ccbf51fec97c33d4b191f4d2fb96', 'Editer Client','editclient', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editclient"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Editer Client</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Client' </li>";}
      // Action Task 394 - 'Valider Client'
      if(!$result_action_555 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, 'df6651ea69337bc976dfc6459ef5e722', 'Valider Client','validclient', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validclient"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider Client</a></li>'."', '0', '[-1-]', '0', '1', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Client' </li>";}
      // Action Task 394 - 'Désactiver Client'
      if(!$result_action_556 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, '18ace52052f2551099ecaabf049ffaec', 'Désactiver Client','validclient', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validclient"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Client</a></li>'."', '0', '[-1-]', '1', '0', 'Client Validé','success','".'<span class="label label-sm label-success">Client Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver Client' </li>";}
      // Action Task 394 - 'Détails Client'
      if(!$result_action_557 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, '493f9e55fc0340763e07514c1900685a', 'Détails Client','detailsclient', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsclient"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Client</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Client' </li>";}
      // Action Task 394 - 'Détails  Client'
      if(!$result_action_558 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, '03b4f949b088e41fc9a1f3f23b7906a8', 'Détails  Client','detailsclient', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsclient"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails  Client</a></li>'."', '0', '[-1-]', '1', '0', 'Client Validé','success','".'<span class="label label-sm label-success">Client Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails  Client' </li>";}
      // Action Task 394 - 'Bloquer Client'
      if(!$result_action_1555 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, 'd4edc2522137690bd7e167efbd61f39d', 'Bloquer Client','bloquerclient', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="bloquerclient"  ><i class="ace-icon fa fa-ban bigger-100"></i> Bloquer Client</a></li>'."', '0', '[-1-3-]', '1', '0', 'Client Validé','success','".'<span class="label label-sm label-success">Client Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Bloquer Client' </li>";}
      // Action Task 394 - 'Détails Client'
      if(!$result_action_1556 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, '572d0c3a8ebd0ebac50da23141188d68', 'Détails Client','detailsclient', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsclient"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Client</a></li>'."', '0', '[-1-3-]', '2', '0', 'Client Bloqué','danger','".'<span class="label label-sm label-danger">Client Bloqué</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Client' </li>";}
  //Task 'addclient' 'Ajouter Client'
  if(!$result_task_395 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addclient', $result_insert_modul, 'addclient','clients', '1', 'Ajouter Client', 'users', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Client' </li>";}
      // Action Task 395 - 'Ajouter Client'
      if(!$result_action_559 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_395, '2b9d8bb8f752d1c35fb681c33e38b42b', 'Ajouter Client','addclient', NULL, '".' '."', '1', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Client' </li>";}
  //Task 'editclient' 'Editer Client'
  if(!$result_task_396 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editclient', $result_insert_modul, 'editclient','clients', '1', 'Editer Client', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Client' </li>";}
      // Action Task 396 - 'Editer Client'
      if(!$result_action_560 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_396, '54aa9121e05f5e698d354022a8eab71d', 'Editer Client','editclient', NULL, '".' '."', '1', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Client' </li>";}
  //Task 'deleteclient' 'Supprimer Client'
  if(!$result_task_397 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deleteclient', $result_insert_modul, 'deleteclient','clients', '1', 'Supprimer Client', 'trash', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Client' </li>";}
      // Action Task 397 - 'Supprimer Client'
      if(!$result_action_561 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_397, '4eaf650e8c2221d590fac5a6a6952231', 'Supprimer Client','deleteclient', NULL, '".' '."', '1', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Client' </li>";}
  //Task 'validclient' 'Valider Client'
  if(!$result_task_398 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validclient', $result_insert_modul, 'validclient','clients', '1', 'Valider Client', 'lock', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider Client' </li>";}
      // Action Task 398 - 'Valider Client'
      if(!$result_action_562 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_398, '534cd4b17fb8a371d3a20565ab8fd96e', 'Valider Client','validclient', NULL, '".' '."', '1', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Client' </li>";}
  //Task 'detailsclient' 'Détails Client'
  if(!$result_task_399 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailsclient', $result_insert_modul, 'detailsclient','clients', '1', 'Détails Client', 'eye', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détails Client' </li>";}
      // Action Task 399 - 'Détails Client'
      if(!$result_action_563 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_399, '95bb6aa696ef630a335aa84e1e425e2c', 'Détails Client','detailsclient', NULL, '".' '."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Client' </li>";}
  //Task 'bloquerclient' 'Bloquer Client'
  if(!$result_task_928 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('bloquerclient', $result_insert_modul, 'bloquerclient','clients', '1', 'Bloquer Client', 'ban', '1', '0', '0','list', '[-1-2-3-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Bloquer Client' </li>";}
      // Action Task 928 - 'Bloquer Client'
      if(!$result_action_1554 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_928, 'da5b1a4c2f52d63947a906c629139720', 'Bloquer Client','bloquerclient', NULL, '".NULL."', '1', '[-1-2-3-]', '0', '0', 'Client bloqué','danger','".'<span class="label label-sm label-danger">Client bloqué</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Bloquer Client' </li>";}