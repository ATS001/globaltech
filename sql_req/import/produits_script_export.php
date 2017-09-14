<?php 
//Export Module 'produits' Date: 14-09-2017
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
      if(!$result_action_562 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_360, '6b087b20929483bb07f8862b39e41f07', 'Désactiver produit','validproduit', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver produit</a></li>'."', '0', '[-1-]', '1', '0', 'Produit validé','success','".'<span class="label label-sm label-success">Produit validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver produit' </li>";}
      // Action Task 360 - 'Achat produit'
      if(!$result_action_597 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_360, '3fe9362cc0a931940b8d5dd40338c9c8', 'Achat produit','buyproducts', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="buyproducts"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Achat produit</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Achat produit' </li>";}
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
  //Task 'buyproducts' 'Achat produit'
  if(!$result_task_394 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('buyproducts', $result_insert_modul, 'buyproducts','produits', '1', 'Achat produit', 'cogs', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Achat produit' </li>";}
      // Action Task 394 - 'Achat produit'
      if(!$result_action_587 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, '8da585a04e918c256bd26f0c03f1390d', 'Achat produit','buyproducts', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Achat produit' </li>";}
      // Action Task 394 - 'Modifier achat'
      if(!$result_action_593 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, 'f8c9a7413089566d1db20dcc5ca17e03', 'Modifier achat','editbuyproduct', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier achat</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier achat' </li>";}
      // Action Task 394 - 'Détail achat'
      if(!$result_action_594 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, '682b4328ee832101a44dac86b22d5757', 'Détail achat','detailbuyproduct', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Détail achat</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail achat' </li>";}
      // Action Task 394 - 'Valider achat'
      if(!$result_action_615 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, 'd1ebf1c5482ddf06721b11ec64afb744', 'Valider achat','validbuyproduct', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider achat</a></li>'."', '0', '[-1-]', '0', '1', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider achat' </li>";}
      // Action Task 394 - 'Désactiver achat'
      if(!$result_action_616 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_394, '368a1e91fc63e263eb01d85a34ecd89b', 'Désactiver achat','validbuyproduct', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver achat</a></li>'."', '0', '[-1-]', '1', '0', 'Achat validé','success','".'<span class="label label-sm label-success">Achat validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver achat' </li>";}
  //Task 'addbuyproduct' 'Ajouter achat'
  if(!$result_task_395 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addbuyproduct', $result_insert_modul, 'addbuyproduct','produits', '1', 'Ajouter achat', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter achat' </li>";}
      // Action Task 395 - 'Ajoute achat'
      if(!$result_action_588 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_395, '659be5cd86a12eba7e59c52d60198a36', 'Ajoute achat','addbuyproduct', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajoute achat' </li>";}
  //Task 'editbuyproduct' 'Modifier achat'
  if(!$result_task_396 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editbuyproduct', $result_insert_modul, 'editbuyproduct','produits', '1', 'Modifier achat', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier achat' </li>";}
      // Action Task 396 - 'Modifier achat'
      if(!$result_action_589 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_396, '8415336a17e8ca26f3eca5741863f3b2', 'Modifier achat','editbuproduct', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier achat' </li>";}
  //Task 'deletebuyproduct' 'Supprimer achat'
  if(!$result_task_397 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletebuyproduct', $result_insert_modul, 'deletebuyproduct','produits', '1', 'Supprimer achat', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer achat' </li>";}
      // Action Task 397 - 'Supprimer achat'
      if(!$result_action_590 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_397, '2c3b4875b72f7da6a87b5c0d7e85f51d', 'Supprimer achat','deletebuyproduct', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer achat' </li>";}
  //Task 'detailbuyproduct' 'Détail achat'
  if(!$result_task_398 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailbuyproduct', $result_insert_modul, 'detailbuyproduct','produits', '1', 'Détail achat', 'cogs', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détail achat' </li>";}
      // Action Task 398 - 'Détail achat'
      if(!$result_action_591 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_398, 'd4180eb7a4ff86c598f441ffd4543f36', 'Détail achat','detailbuyproduct', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail achat' </li>";}
  //Task 'validbuyproduct' 'Valider achat'
  if(!$result_task_412 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validbuyproduct', $result_insert_modul, 'validbuyproduct','produits', '1', 'Valider achat', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider achat' </li>";}
      // Action Task 412 - 'Valider achat'
      if(!$result_action_614 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_412, '4a4c9b096bad58a96d5ea6f93d66e81c', 'Valider achat','validbuyproduct', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider achat' </li>";}
