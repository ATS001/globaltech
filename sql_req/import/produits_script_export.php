<?php 
//Export Module 'produits' Date: 13-10-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('produits', 'Gestion des produits','produits','produits, ref_unites_vente, ref_categories_produits, ref_types_produits','produits',NULL,'0', '0', '[-1-2-3-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'produits' </li>";}
  //Task 'produits' 'Gestion des produits'
  if(!$result_task_625 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('produits', $result_insert_modul, 'produits','produits', '1', 'Gestion des produits', 'cogs', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion des produits' </li>";}
      // Action Task 625 - 'Gestion des produits'
      if(!$result_action_956 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_625, '192715027870a4a612fd44d562e2752f', 'Gestion des produits','produits', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion des produits' </li>";}
      // Action Task 625 - 'Modifier produit'
      if(!$result_action_957 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_625, 'ed13b17897a396c0633d7989f2bc644f', 'Modifier produit','editproduit', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier produit</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier produit' </li>";}
      // Action Task 625 - 'Détail produit'
      if(!$result_action_958 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_625, '96df3c4057988c54a7d468e5664dba10', 'Détail produit','detailproduit', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Détail produit</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail produit' </li>";}
      // Action Task 625 - 'Valider produit'
      if(!$result_action_959 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_625, 'eb5b51394e164f00ce8c998310e3a8ba', 'Valider produit','validproduit', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider produit</a></li>'."', '0', '[-1-]', '0', '1', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider produit' </li>";}
      // Action Task 625 - 'Désactiver produit'
      if(!$result_action_960 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_625, '6b087b20929483bb07f8862b39e41f07', 'Désactiver produit','validproduit', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validproduit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver produit</a></li>'."', '0', '[-1-]', '1', '0', 'Produit validé','success','".'<span class="label label-sm label-success">Produit validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver produit' </li>";}
      // Action Task 625 - 'Achat produit'
      if(!$result_action_961 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_625, '3fe9362cc0a931940b8d5dd40338c9c8', 'Achat produit','buyproducts', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="buyproducts"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Achat produit</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Achat produit' </li>";}
      // Action Task 625 - 'Détails Produit'
      if(!$result_action_962 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_625, '41b9c4b7028269d4540915d6ec14ee79', 'Détails Produit','detailproduit', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailproduit"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Produit</a></li>'."', '0', '[-1-]', '1', '0', 'Produit Validé','success','".'<span class="label label-sm label-success">Produit Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Produit' </li>";}
  //Task 'addproduit' 'Ajouter produit'
  if(!$result_task_626 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addproduit', $result_insert_modul, 'addproduit','produits', '1', 'Ajouter produit', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter produit' </li>";}
      // Action Task 626 - 'Ajouter produit'
      if(!$result_action_963 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_626, '93e893c307a6fa63e392f78751ec70ce', 'Ajouter produit','addproduit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter produit' </li>";}
  //Task 'editproduit' 'Modifier produit'
  if(!$result_task_627 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editproduit', $result_insert_modul, 'editproduit','produits', '1', 'Modifier produit', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier produit' </li>";}
      // Action Task 627 - 'Modifier produit'
      if(!$result_action_964 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_627, 'bcf3beada4a98e8145af2d4fbb744f01', 'Modifier produit','editproduit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier produit' </li>";}
  //Task 'detailproduit' 'Detail produit'
  if(!$result_task_628 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailproduit', $result_insert_modul, 'detailproduit','produits', '1', 'Detail produit', 'cogs', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Detail produit' </li>";}
      // Action Task 628 - 'Detail produit'
      if(!$result_action_965 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_628, '796427ec57f7c13d6b737055ae686b34', 'Detail produit','detailproduit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Detail produit' </li>";}
  //Task 'validproduit' 'Valider produit'
  if(!$result_task_629 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validproduit', $result_insert_modul, 'validproduit','produits', '1', 'Valider produit', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider produit' </li>";}
      // Action Task 629 - 'Valider produit'
      if(!$result_action_966 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_629, '1fb8cd1a179be07586fa7db05013dd37', 'Valider produit','validproduit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider produit' </li>";}
  //Task 'deleteproduit' 'Supprimer produit'
  if(!$result_task_630 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deleteproduit', $result_insert_modul, 'deleteproduit','produits', '1', 'Supprimer produit', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer produit' </li>";}
      // Action Task 630 - 'Supprimer produit'
      if(!$result_action_967 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_630, '7779e98d2111faedf458f7aeb548294e', 'Supprimer produit','deleteproduit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer produit' </li>";}
  //Task 'buyproducts' 'Achat produit'
  if(!$result_task_631 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('buyproducts', $result_insert_modul, 'buyproducts','produits', '1', 'Achat produit', 'cogs', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Achat produit' </li>";}
      // Action Task 631 - 'Achat produit'
      if(!$result_action_968 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_631, '8da585a04e918c256bd26f0c03f1390d', 'Achat produit','buyproducts', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Achat produit' </li>";}
      // Action Task 631 - 'Modifier achat'
      if(!$result_action_969 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_631, 'f8c9a7413089566d1db20dcc5ca17e03', 'Modifier achat','editbuyproduct', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier achat</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier achat' </li>";}
      // Action Task 631 - 'Détail achat'
      if(!$result_action_970 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_631, '682b4328ee832101a44dac86b22d5757', 'Détail achat','detailbuyproduct', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Détail achat</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail achat' </li>";}
      // Action Task 631 - 'Valider achat'
      if(!$result_action_971 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_631, 'd1ebf1c5482ddf06721b11ec64afb744', 'Valider achat','validbuyproduct', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider achat</a></li>'."', '0', '[-1-]', '0', '1', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider achat' </li>";}
      // Action Task 631 - 'Désactiver achat'
      if(!$result_action_972 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_631, '368a1e91fc63e263eb01d85a34ecd89b', 'Désactiver achat','validbuyproduct', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validbuyproduct"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver achat</a></li>'."', '0', '[-1-]', '1', '0', 'Achat validé','success','".'<span class="label label-sm label-success">Achat validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver achat' </li>";}
  //Task 'addbuyproduct' 'Ajouter achat'
  if(!$result_task_632 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addbuyproduct', $result_insert_modul, 'addbuyproduct','produits', '1', 'Ajouter achat', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter achat' </li>";}
      // Action Task 632 - 'Ajoute achat'
      if(!$result_action_973 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_632, '659be5cd86a12eba7e59c52d60198a36', 'Ajoute achat','addbuyproduct', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajoute achat' </li>";}
  //Task 'editbuyproduct' 'Modifier achat'
  if(!$result_task_633 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editbuyproduct', $result_insert_modul, 'editbuyproduct','produits', '1', 'Modifier achat', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier achat' </li>";}
      // Action Task 633 - 'Modifier achat'
      if(!$result_action_974 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_633, '8415336a17e8ca26f3eca5741863f3b2', 'Modifier achat','editbuproduct', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier achat' </li>";}
  //Task 'deletebuyproduct' 'Supprimer achat'
  if(!$result_task_634 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletebuyproduct', $result_insert_modul, 'deletebuyproduct','produits', '1', 'Supprimer achat', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer achat' </li>";}
      // Action Task 634 - 'Supprimer achat'
      if(!$result_action_975 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_634, '2c3b4875b72f7da6a87b5c0d7e85f51d', 'Supprimer achat','deletebuyproduct', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer achat' </li>";}
  //Task 'detailbuyproduct' 'Détail achat'
  if(!$result_task_635 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailbuyproduct', $result_insert_modul, 'detailbuyproduct','produits', '1', 'Détail achat', 'cogs', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détail achat' </li>";}
      // Action Task 635 - 'Détail achat'
      if(!$result_action_976 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_635, 'd4180eb7a4ff86c598f441ffd4543f36', 'Détail achat','detailbuyproduct', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail achat' </li>";}
  //Task 'validbuyproduct' 'Valider achat'
  if(!$result_task_636 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validbuyproduct', $result_insert_modul, 'validbuyproduct','produits', '1', 'Valider achat', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider achat' </li>";}
      // Action Task 636 - 'Valider achat'
      if(!$result_action_977 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_636, '4a4c9b096bad58a96d5ea6f93d66e81c', 'Valider achat','validbuyproduct', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider achat' </li>";}
