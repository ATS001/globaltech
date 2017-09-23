<?php 
//Export Module 'categories_produits' Date: 23-09-2017
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('categories_produits', 'Gestion des catégories de produits','produits/settings/categories_produits','ref_categories_produits','categories_produits','produits','1', '0', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'categories_produits' </li>";}
  //Task 'categories_produits' 'Gestion des catégories de produits'
  if(!$result_task_455 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('categories_produits', $result_insert_modul, 'categories_produits','produits/settings/categories_produits', '1', 'Gestion des catégories de produits', 'cogs', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion des catégories de produits' </li>";}
      // Action Task 455 - 'Gestion des catégories de produits'
      if(!$result_action_652 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_455, 'e69f84a801ee1525f20f34e684688a9b', 'Gestion des catégories de produits','categories_produits', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion des catégories de produits' </li>";}
      // Action Task 455 - 'Modifier catégorie'
      if(!$result_action_653 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_455, '90f6eba3e0ed223e73d250278cb445d5', 'Modifier catégorie','editecategorie_produit', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editecategorie_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier catégorie</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier catégorie' </li>";}
      // Action Task 455 - 'Valider catégorie'
      if(!$result_action_654 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_455, 'c62968a45ae9cfa8b127ac1b5573988a', 'Valider catégorie','validcategorie_produit', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Valider catégorie</a></li>'."', '0', '[-1-]', '0', '1', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider catégorie' </li>";}
      // Action Task 455 - 'Désactiver catégorie'
      if(!$result_action_655 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_455, '6f43a6bcbd293f958aff51953559104e', 'Désactiver catégorie','validcategorie_produit', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validcategorie_produit"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Désactiver catégorie</a></li>'."', '0', '[-1-]', '1', '0', 'Catégorie Validée','success','".'<span class="label label-sm label-success">Catégorie Validée</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver catégorie' </li>";}
  //Task 'addcategorie_produit' 'Ajouter une catégorie'
  if(!$result_task_456 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addcategorie_produit', $result_insert_modul, 'addcategorie_produit','produits/settings/categories_produits', '1', 'Ajouter une catégorie', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter une catégorie' </li>";}
      // Action Task 456 - 'Ajouter une catégorie'
      if(!$result_action_656 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_456, 'd26f5940e88a494c0eb65047aab9a17b', 'Ajouter une catégorie','addcategorie_produit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter une catégorie' </li>";}
  //Task 'editecategorie_produit' 'Modifier une catégorie'
  if(!$result_task_457 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editecategorie_produit', $result_insert_modul, 'editecategorie_produit','produits/settings/categories_produits', '1', 'Modifier une catégorie', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier une catégorie' </li>";}
      // Action Task 457 - 'Modifier une catégorie'
      if(!$result_action_657 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_457, '27957c6d0f6869d4d90287cd50b6dde9', 'Modifier une catégorie','editecategorie_produit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier une catégorie' </li>";}
  //Task 'validcategorie_produit' 'Valider une catégorie'
  if(!$result_task_458 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validcategorie_produit', $result_insert_modul, 'validcategorie_produit','produits/settings/categories_produits', '1', 'Valider une catégorie', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider une catégorie' </li>";}
      // Action Task 458 - 'Valider une catégorie'
      if(!$result_action_658 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_458, '41b48dd567e4f79e35261a47b7bad751', 'Valider une catégorie','validcategorie_produit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider une catégorie' </li>";}
  //Task 'deletecategorie_produit' 'Supprimer une catégorie'
  if(!$result_task_459 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletecategorie_produit', $result_insert_modul, 'deletecategorie_produit','produits/settings/categories_produits', '1', 'Supprimer une catégorie', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer une catégorie' </li>";}
      // Action Task 459 - 'Supprimer une catégorie'
      if(!$result_action_659 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_459, '90dc20c4d1ad7be7fac8ec34d5ac26b3', 'Supprimer une catégorie','deletecategorie_produit', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer une catégorie' </li>";}
