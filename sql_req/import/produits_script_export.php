<?php 
//Export Module 'produits' Date: 06-09-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('produits', 'Gestion des produits','produits','produits, ref_unites_vente, ref_categories_produits, ref_types_produits','produits',NULL,'0', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'produits' </li>";}
  //Task 'produits' 'Gestion des produits'
  if(!$result_task_360 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('produits', $result_insert_modul, 'produits','produits', '1', 'Gestion des produits', 'cogs', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion des produits' </li>";}
      // Action Task 360 - 'Gestion des produits'
      if(!$result_action_531 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_360, '192715027870a4a612fd44d562e2752f', 'Gestion des produits','produits', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion des produits' </li>";}
      // Action Task 360 - 'Modifier produit'
      if(!$result_action_537 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_360, 'ed13b17897a396c0633d7989f2bc644f', 'Modifier produit','editproduit', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier produit</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier produit' </li>";}
      // Action Task 360 - 'Détail produit'
      if(!$result_action_538 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_360, '96df3c4057988c54a7d468e5664dba10', 'Détail produit','detailproduit', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Détail produit</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail produit' </li>";}
      // Action Task 360 - 'Valider produit'
      if(!$result_action_539 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_360, 'eb5b51394e164f00ce8c998310e3a8ba', 'Valider produit','validproduit', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider produit</a></li>'."', '0', '[-1-]', '0', '1', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider produit' </li>";}
      // Action Task 360 - 'Désactiver produit'
      if(!$result_action_562 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_360, '6b087b20929483bb07f8862b39e41f07', 'Désactiver produit','validproduit', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver produit</a></li>'."', '0', '[-1-]', '1', '0', 'Valide','success','".'<span class="label label-sm label-success">Valide</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver produit' </li>";}
  //Task 'addproduit' 'Ajouter produit'
  if(!$result_task_361 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addproduit', $result_insert_modul, 'addproduit','produits', '1', 'Ajouter produit', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter produit' </li>";}
      // Action Task 361 - 'Ajouter produit'
      if(!$result_action_532 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_361, '93e893c307a6fa63e392f78751ec70ce', 'Ajouter produit','addproduit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter produit' </li>";}
  //Task 'editproduit' 'Modifier produit'
  if(!$result_task_362 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editproduit', $result_insert_modul, 'editproduit','produits', '1', 'Modifier produit', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier produit' </li>";}
      // Action Task 362 - 'Modifier produit'
      if(!$result_action_533 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_362, 'bcf3beada4a98e8145af2d4fbb744f01', 'Modifier produit','editproduit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier produit' </li>";}
  //Task 'detailproduit' 'Detail produit'
  if(!$result_task_363 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailproduit', $result_insert_modul, 'detailproduit','produits', '1', 'Detail produit', 'cogs', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Detail produit' </li>";}
      // Action Task 363 - 'Detail produit'
      if(!$result_action_534 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_363, '796427ec57f7c13d6b737055ae686b34', 'Detail produit','detailproduit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Detail produit' </li>";}
  //Task 'validproduit' 'Valider produit'
  if(!$result_task_364 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validproduit', $result_insert_modul, 'validproduit','produits', '1', 'Valider produit', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider produit' </li>";}
      // Action Task 364 - 'Valider produit'
      if(!$result_action_535 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_364, '1fb8cd1a179be07586fa7db05013dd37', 'Valider produit','validproduit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider produit' </li>";}
  //Task 'deleteproduit' 'Supprimer produit'
  if(!$result_task_365 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deleteproduit', $result_insert_modul, 'deleteproduit','produits', '1', 'Supprimer produit', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer produit' </li>";}
      // Action Task 365 - 'Supprimer produit'
      if(!$result_action_536 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_365, '7779e98d2111faedf458f7aeb548294e', 'Supprimer produit','deleteproduit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer produit' </li>";}
