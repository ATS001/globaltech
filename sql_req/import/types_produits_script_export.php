<?php 
//Export Module 'types_produits' Date: 31-08-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('types_produits', 'Gestion des types de produits','produits/settings/types_produits','ref_types_produits','types_produits','produits','1', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'types_produits' </li>";}
  //Task 'types_produits' 'Gestion des types de produits'
  if(!$result_task_371 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('types_produits', $result_insert_modul, 'types_produits','produits/settings/types_produits', '1', 'Gestion des types de produits', 'cogs', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion des types de produits' </li>";}
      // Action Task 371 - 'Gestion des types de produits'
      if(!$result_action_547 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_371, 'b6b6bfbd070b5b3dd84acedae7b854e9', 'Gestion des types de produits', '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','<span class="label label-sm label-warning">Attente de validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion des types de produits' </li>";}
      // Action Task 371 - 'Modifier type'
      if(!$result_action_552 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_371, '3c5400b775264499825a039d66aa9c90', 'Modifier type', '".'<li><a href="#" class="this_url" data="%id%" rel="edittype_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier type</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','<span class="label label-sm label-warning">Attente de validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier type' </li>";}
      // Action Task 371 - 'Valider type'
      if(!$result_action_553 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_371, 'dcf55bc300d690af4c81e4d2335e60e5', 'Valider type', '".'<li><a href="#" class="this_exec" data="%id%" rel="validtype_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider type</a></li>'."', '0', '[-1-]', '0', '1', 'Attente de validation','warning','<span class="label label-sm label-warning">Attente de validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider type' </li>";}
      // Action Task 371 - 'Désactiver type'
      if(!$result_action_564 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_371, '230b9554d37da1c71986af94962cb340', 'Désactiver type', '".'<li><a href="#" class="this_exec" data="%id%" rel="validtype_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver type</a></li>'."', '0', '[-1-]', '1', '0', 'Valide','success','<span class="label label-sm label-success">Valide</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver type' </li>";}
  //Task 'addtype_produit' 'Ajouter un type'
  if(!$result_task_372 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addtype_produit', $result_insert_modul, 'addtype_produit','produits/settings/types_produits', '1', 'Ajouter un type', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter un type' </li>";}
      // Action Task 372 - 'Ajouter un type'
      if(!$result_action_548 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_372, 'e0d163499b4ba11d6d7a648bc6fc6de6', 'Ajouter un type', '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','<span class="label label-sm label-warning">Attente de validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter un type' </li>";}
  //Task 'edittype_produit' 'Modifier type'
  if(!$result_task_373 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('edittype_produit', $result_insert_modul, 'edittype_produit','produits/settings/types_produits', '1', 'Modifier type', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier type' </li>";}
      // Action Task 373 - 'Modifier type'
      if(!$result_action_549 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_373, 'ac5a6d087b3c8db7501fa5137a47773e', 'Modifier type', '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','<span class="label label-sm label-warning">Attente de validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier type' </li>";}
  //Task 'validtype_produit' 'Valider type'
  if(!$result_task_374 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validtype_produit', $result_insert_modul, 'validtype_produit','produits/settings/types_produits', '1', 'Valider type', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider type' </li>";}
      // Action Task 374 - 'Valider type'
      if(!$result_action_550 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_374, '2e8242a93a62a264ad7cfc953967f575', 'Valider type', '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','<span class="label label-sm label-warning">Attente de validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider type' </li>";}
  //Task 'deletetype_produit' 'Supprimer type'
  if(!$result_task_375 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletetype_produit', $result_insert_modul, 'deletetype_produit','produits/settings/types_produits', '1', 'Supprimer type', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer type' </li>";}
      // Action Task 375 - 'Supprimer type'
      if(!$result_action_551 = $db->Query("INSERT INTO task_action (appid, idf, descrip, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_375, 'e3725ba15ca483b9278f68553eca5918', 'Supprimer type', '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','<span class="label label-sm label-warning">Attente de validation</span>')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer type' </li>";}
