<?php 
//Export Module 'categorie_client' Date: 30-08-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('categorie_client', 'Gestion Catégorie Client','clients/settings/categorie_client','categorie_client','categorie_client','clients','1', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'categorie_client' </li>";}
  //Task 'categorie_client' 'Gestion Catégorie Client'
  if(!$result_task_333 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('categorie_client', $result_insert_modul, 'categorie_client','clients/settings/categorie_client', '1', 'Gestion Catégorie Client', 'certificate', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion Catégorie Client' </li>";}
      // Action Task 333 - 'Gestion Catégorie Client'
      if(!$result_action_497 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_333, '6edc543080c65eca3993445c295ff94b', 'Gestion Catégorie Client', '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','danger','<span class="label label-sm label-danger">Attente Validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Catégorie Client' </li>";}
      // Action Task 333 - 'Editer Catégorie Client'
      if(!$result_action_506 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_333, '142a68a109abd0462ea44fcadffe56de', 'Editer Catégorie Client', '".'<li><a href="#" class="this_url" data="%id%" rel="editcategorie_client"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Editer Catégorie Client</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','danger','<span class="label label-sm label-danger">Attente Validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Catégorie Client' </li>";}
      // Action Task 333 - 'Activer Catégorie Client'
      if(!$result_action_507 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_333, '70df89fa2654d8b10d7fc7e75e178b7e', 'Activer Catégorie Client', '".'<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_client"  ><i class="ace-icon fa fa-lock bigger-100"></i> Activer Catégorie Client</a></li>'."', '0', '[-1-]', '0', '1', 'Attente Validation','danger','<span class="label label-sm label-danger">Attente Validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Activer Catégorie Client' </li>";}
      // Action Task 333 - 'Désactiver Catégorie Client'
      if(!$result_action_508 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_333, '109e82d6db5721f63cd827e9fd224216', 'Désactiver Catégorie Client', '".'<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_client"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver Catégorie Client</a></li>'."', '0', '[-1-]', '1', '0', 'Catégorie Validée','success','<span class="label label-sm label-success">Catégorie Validée</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver Catégorie Client' </li>";}
  //Task 'addcategorie_client' 'Ajouter Catégorie Client'
  if(!$result_task_334 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addcategorie_client', $result_insert_modul, 'addcategorie_client','clients/settings/categorie_client', '1', 'Ajouter Catégorie Client', 'certificate', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Catégorie Client' </li>";}
      // Action Task 334 - 'Ajouter Catégorie Client'
      if(!$result_action_498 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_334, 'a5c1bd0dfd87824ff0f57c6b1e1d2c3f', 'Ajouter Catégorie Client', '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','danger','<span class="label label-sm label-danger">Attente Validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter Catégorie Client' </li>";}
  //Task 'editcategorie_client' 'Editer Catégorie Client'
  if(!$result_task_335 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editcategorie_client', $result_insert_modul, 'editcategorie_client','clients/settings/categorie_client', '1', 'Editer Catégorie Client', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Editer Catégorie Client' </li>";}
      // Action Task 335 - 'Editer Catégorie Client'
      if(!$result_action_499 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_335, '8d901f74dfd6ee3a8f44ebd0b83fbfae', 'Editer Catégorie Client', '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','danger','<span class="label label-sm label-danger">Attente Validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Editer Catégorie Client' </li>";}
  //Task 'deletecategorie_client' 'Supprimer Catégorie Client'
  if(!$result_task_336 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletecategorie_client', $result_insert_modul, 'deletecategorie_client','clients/settings/categorie_client', '1', 'Supprimer Catégorie Client', 'trash', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Catégorie Client' </li>";}
      // Action Task 336 - 'Supprimer Catégorie Client'
      if(!$result_action_500 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_336, 'e87327563ce6b659780d6b2c9bf8ac77', 'Supprimer Catégorie Client', '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','danger','<span class="label label-sm label-danger">Attente Validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer Catégorie Client' </li>";}
  //Task 'validcategorie_client' 'Valider Catégorie Client'
  if(!$result_task_337 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validcategorie_client', $result_insert_modul, 'validcategorie_client','clients/settings/categorie_client', '1', 'Valider Catégorie Client', 'cloud', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider Catégorie Client' </li>";}
      // Action Task 337 - 'Valider Catégorie Client'
      if(!$result_action_501 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_337, 'c955da8d244aac06ee7595d08de7d009', 'Valider Catégorie Client', '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','danger','<span class="label label-sm label-danger">Attente Validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Catégorie Client' </li>";}
