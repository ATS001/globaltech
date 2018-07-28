<?php 
//Export Module 'factures' Date: 28-07-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('factures', 'Gestion des factures','factures/main','factures','factures',NULL,'0', '0', '[-1-2-3-5-7-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'factures' </li>";}
  //Task 'factures' 'Gestion des factures'
  if(!$result_task_797 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('factures', $result_insert_modul, 'factures','factures/main', '1', 'Gestion des factures', 'file', '1', '0', '0','list', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Gestion des factures' </li>";}
      // Action Task 797 - 'Gestion des factures'
      if(!$result_action_1294 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '4c924acb9adc87d8389e8f9842a965c5', 'Gestion des factures','factures', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion des factures' </li>";}
      // Action Task 797 - 'Liste complément'
      if(!$result_action_1295 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '98a697ec628778765b25e02ba2929d38', 'Liste complément','complements', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="complements"  ><i class="ace-icon fa fa-circle bigger-100"></i> Liste complément</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Liste complément' </li>";}
      // Action Task 797 - 'Liste encaissements'
      if(!$result_action_1296 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, 'f8b20f7fec99b45b967a431d64b7f061', 'Liste encaissements','encaissements', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="encaissements"  ><i class="ace-icon fa fa-euro bigger-100"></i> Liste encaissements</a></li>'."', '0', '[-1-]', '2', '0', 'Attente Paiement','info','".'<span class="label label-sm label-info">Attente Paiement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Liste encaissements' </li>";}
      // Action Task 797 - 'Valider facture'
      if(!$result_action_1297 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '9a51fb5298e39a28af3ad6272fc51177', 'Valider facture','validfacture', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validfacture"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider facture</a></li>'."', '0', '[-1-]', '0', '1', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider facture' </li>";}
      // Action Task 797 - 'Désactiver facture'
      if(!$result_action_1298 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '851f1d4c13f6025f69f5b9315321d350', 'Désactiver facture','rejectfacture', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="rejectfacture"  ><i class="ace-icon fa fa-unlock bigger-100"></i> Désactiver facture</a></li>'."', '0', '[-1-]', '1', '0', 'Attente Envoi Client','success','".'<span class="label label-sm label-success">Attente Envoi Client</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver facture' </li>";}
      // Action Task 797 - 'Détail facture'
      if(!$result_action_1299 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '5c79105956d28b5cac52f85784039919', 'Détail facture','detailsfacture', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détail facture</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail facture' </li>";}
      // Action Task 797 - 'Détails Facture'
      if(!$result_action_1300 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '7892721423af84a0b54e90250cf27ee3', 'Détails Facture','detailsfacture', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Facture</a></li>'."', '0', '[-1-]', '1', '0', 'Attente Envoi Client','success','".'<span class="label label-sm label-success">Attente Envoi Client</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Facture' </li>";}
      // Action Task 797 - 'Envoyer au client  '
      if(!$result_action_1301 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '4b69240b3dd04f7a29457008b31d1248', 'Envoyer au client  ','sendfacture', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="sendfacture"  ><i class="ace-icon fa fa-fighter-jet  bigger-100"></i> Envoyer au client  </a></li>'."', '0', '[-1-]', '1', '0', 'Attente Envoi Client','success','".'<span class="label label-sm label-success">Attente Envoi Client</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Envoyer au client  ' </li>";}
      // Action Task 797 - 'Détails facture'
      if(!$result_action_1302 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '80a4b2643b95c2836e968411811d3c21', 'Détails facture','detailsfacture', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails facture</a></li>'."', '0', '[-1-]', '2', '0', 'Attente Paiement','info','".'<span class="label label-sm label-info">Attente Paiement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails facture' </li>";}
      // Action Task 797 - 'Détails facture'
      if(!$result_action_1303 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '2f679be3c0d7b88529209f86745f9028', 'Détails facture','detailsfacture', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails facture</a></li>'."', '0', '[-1-]', '3', '0', 'Payé Partiellement','inverse','".'<span class="label label-sm label-inverse">Payé Partiellement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails facture' </li>";}
      // Action Task 797 - 'Détails facture'
      if(!$result_action_1304 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '429558e9a1e899c11051ea5c9a4f7942', 'Détails facture','detailsfacture', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails facture</a></li>'."', '0', '[-1-]', '4', '0', 'Facture payée','danger','".'<span class="label label-sm label-danger">Facture payée</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails facture' </li>";}
      // Action Task 797 - 'Liste encaissements'
      if(!$result_action_1305 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '3acd11d8d74fb7e1ba8d5721e96f91bd', 'Liste encaissements','encaissements', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="encaissements"  ><i class="ace-icon fa fa-euro bigger-100"></i> Liste encaissements</a></li>'."', '0', '[-1-]', '3', '0', 'Payé partiellement','inverse','".'<span class="label label-sm label-inverse">Payé partiellement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Liste encaissements' </li>";}
      // Action Task 797 - 'Liste encaissements'
      if(!$result_action_1425 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '59a242065449b98b6ef4a79206696c80', 'Liste encaissements','encaissements', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="encaissements"  ><i class="ace-icon fa fa-euro bigger-100"></i> Liste encaissements</a></li>'."', '0', '[-1-]', '4', '0', 'Facture payée','danger','".'<span class="label label-sm label-danger">Facture payée</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Liste encaissements' </li>";}
      // Action Task 797 - 'Archiver Facture'
      if(!$result_action_1463 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_797, '99fcbf875d6f192e5f9bdf5c8ecf1e66', 'Archiver Facture','archivefacture', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="archivefacture"  ><i class="ace-icon fa fa-envelope-square bigger-100"></i> Archiver Facture</a></li>'."', '0', '[-1-]', '4', '0', 'Facture payée','danger','".'<span class="label label-sm label-danger">Facture payée</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Archiver Facture' </li>";}
  //Task 'complements' 'complements'
  if(!$result_task_798 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('complements', $result_insert_modul, 'complements','factures/main', '1', 'complements', 'cogs', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'complements' </li>";}
      // Action Task 798 - 'complements'
      if(!$result_action_1306 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_798, '55c3c5d2d93143b315513b7401043c8b', 'complements','complements', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Complément Ajouté','warning','".'<span class="label label-sm label-warning">Complément Ajouté</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'complements' </li>";}
      // Action Task 798 - 'Modifier complément'
      if(!$result_action_1307 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_798, 'dfc4772cc03cf0b92a47f54fc6a2326e', 'Modifier complément','editcomplement', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editcomplement"  ><i class="ace-icon fa fa-cogs bigger-100"></i> Modifier complément</a></li>'."', '0', '[-1-]', '0', '0', 'Complément Ajouté','warning','".'<span class="label label-sm label-warning">Complément Ajouté</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier complément' </li>";}
  //Task 'addcomplement' 'Ajouter complément'
  if(!$result_task_799 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addcomplement', $result_insert_modul, 'addcomplement','factures/main', '1', 'Ajouter complément', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter complément' </li>";}
      // Action Task 799 - 'Ajouter complément'
      if(!$result_action_1308 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_799, '03a18bdd5201e433a3c523a2b34d059a', 'Ajouter complément','addcomplement', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Complément Ajouté','warning','".'<span class="label label-sm label-warning">Complément Ajouté</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter complément' </li>";}
  //Task 'encaissements' 'Encaissement'
  if(!$result_task_800 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('encaissements', $result_insert_modul, 'encaissements','factures/main', '1', 'Encaissement', 'cogs', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Encaissement' </li>";}
      // Action Task 800 - 'Encaissement'
      if(!$result_action_1309 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_800, '88d9bc979cd1102eb8196e7f5e6042ca', 'Encaissement','encaissements', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Encaissement' </li>";}
      // Action Task 800 - 'Modifier encaissement'
      if(!$result_action_1310 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_800, 'c690cc68f5257c0c225b8b8e6126ea56', 'Modifier encaissement','editencaissement', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editencaissement"  ><i class="ace-icon fa fa-pencil-square-o bigger-100"></i> Modifier encaissement</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier encaissement' </li>";}
      // Action Task 800 - 'Détails encaissement'
      if(!$result_action_1311 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_800, '1dc06f602e8630f273d44aa2751b2127', 'Détails encaissement','detailsencaissement', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsencaissement"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails encaissement</a></li>'."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails encaissement' </li>";}
      // Action Task 800 - 'Valider encaissement'
      if(!$result_action_1312 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_800, '6567dc21b9b744ea7dbcbcbf83df4ac5', 'Valider encaissement','validencaissement', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validencaissement"  ><i class="ace-icon fa fa-check-square-o bigger-100"></i> Valider encaissement</a></li>'."', '0', '[-1-]', '0', '1', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider encaissement' </li>";}
      // Action Task 800 - 'Détails encaissement'
      if(!$result_action_1313 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_800, 'bc335bbc5e0debff602b4e5325c89a99', 'Détails encaissement','detailsencaissement', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsencaissement"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails encaissement</a></li>'."', '0', '[-1-]', '1', '0', 'Encaissement Validé','success','".'<span class="label label-sm label-success">Encaissement Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails encaissement' </li>";}
  //Task 'addencaissements' 'Ajouter encaissement'
  if(!$result_task_801 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addencaissements', $result_insert_modul, 'addencaissements','factures/main', '1', 'Ajouter encaissement', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter encaissement' </li>";}
      // Action Task 801 - 'Ajouter encaissement'
      if(!$result_action_1314 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_801, 'e4866b292dbc3c9c5d9cc37273a5b498', 'Ajouter encaissement','addencaissement', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter encaissement' </li>";}
  //Task 'editcomplement' 'Modifier complément'
  if(!$result_task_802 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editcomplement', $result_insert_modul, 'editcomplement','factures/main', '1', 'Modifier complément', 'cogs', '1', '0', '0','form', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier complément' </li>";}
      // Action Task 802 - 'Modifier complément'
      if(!$result_action_1315 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_802, '8665be10959f39df4f149962eb70041f', 'Modifier complément','editcomplement', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Complément Ajouté','warning','".'<span class="label label-sm label-warning">Complément Ajouté</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier complément' </li>";}
  //Task 'editencaissement' 'Modifier encaissement'
  if(!$result_task_803 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editencaissement', $result_insert_modul, 'editencaissement','factures/main', '1', 'Modifier encaissement', 'cogs', '1', '0', '0','list', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier encaissement' </li>";}
      // Action Task 803 - 'Modifier encaissement'
      if(!$result_action_1316 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_803, '585d411904bf7d9e83d21b2810ff1d6c', 'Modifier encaissement','editencaissement', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier encaissement' </li>";}
  //Task 'deletecomplement' 'Supprimer complément'
  if(!$result_task_804 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletecomplement', $result_insert_modul, 'deletecomplement','factures/main', '1', 'Supprimer complément', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer complément' </li>";}
      // Action Task 804 - 'Supprimer complément'
      if(!$result_action_1317 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_804, '8c8b058a4d030cdc8b49c9008abb2e92', 'Supprimer complément','deletecomplement', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Complément Ajouté','warning','".'Complément Ajouté'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer complément' </li>";}
  //Task 'deleteencaissement' 'Supprimer encaissement'
  if(!$result_task_805 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deleteencaissement', $result_insert_modul, 'deleteencaissement','factures/main', '1', 'Supprimer encaissement', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer encaissement' </li>";}
      // Action Task 805 - 'Supprimer encaissement'
      if(!$result_action_1318 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_805, '6bf7d5180940f03567a5d711e8563ba4', 'Supprimer encaissement','deleteencaissement', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer encaissement' </li>";}
  //Task 'validfacture' 'Valider facture'
  if(!$result_task_806 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validfacture', $result_insert_modul, 'validfacture','factures/main', '1', 'Valider facture', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider facture' </li>";}
      // Action Task 806 - 'Valider facture'
      if(!$result_action_1319 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_806, '256abad0ec8e3bc8ed1c0653ff177255', 'Valider facture','validfacture', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider facture' </li>";}
  //Task 'detailsencaissement' 'Détail encaissement'
  if(!$result_task_807 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailsencaissement', $result_insert_modul, 'detailsencaissement','factures/main', '1', 'Détail encaissement', 'eye', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détail encaissement' </li>";}
      // Action Task 807 - 'Détail encaissement'
      if(!$result_action_1320 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_807, 'b5dc5719c1f96df7334f371dcf51a5b6', 'Détail encaissement','detailsencaissement', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail encaissement' </li>";}
  //Task 'detailsfacture' 'Détails facture'
  if(!$result_task_808 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailsfacture', $result_insert_modul, 'detailsfacture','factures/main', '1', 'Détails facture', 'eye', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détails facture' </li>";}
      // Action Task 808 - 'Détails facture'
      if(!$result_action_1321 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_808, '16fbf6fdcbb72f863bcf7e4ef28d8e75', 'Détails facture','detailsfacture', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails facture' </li>";}
  //Task 'rejectfacture' 'Désactiver Facture'
  if(!$result_task_809 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('rejectfacture', $result_insert_modul, 'rejectfacture','factures/main', '1', 'Désactiver Facture', 'remove', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Désactiver Facture' </li>";}
      // Action Task 809 - 'Désactiver Facture'
      if(!$result_action_1322 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_809, '5efdeb41007109ca99f23f0756217827', 'Désactiver Facture','rejectfacture', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Facture Validée','success','".'<span class="label label-sm label-success">Facture Validée</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Désactiver Facture' </li>";}
  //Task 'validencaissement' 'Valider encaissement'
  if(!$result_task_810 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validencaissement', $result_insert_modul, 'validencaissement','factures/main', '1', 'Valider encaissement', 'cogs', '1', '0', '0','exec', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider encaissement' </li>";}
      // Action Task 810 - 'Valider encaissement'
      if(!$result_action_1323 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_810, '1127d08fb22f425fd7913c3df1b9884f', 'Valider encaissement','validencaissement', NULL, '".NULL."', '1', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider encaissement' </li>";}
  //Task 'sendfacture' 'Envoyer Facture'
  if(!$result_task_811 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('sendfacture', $result_insert_modul, 'sendfacture','factures/main', '1', 'Envoyer Facture', 'cogs', '1', '0', '0','exec', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Envoyer Facture' </li>";}
      // Action Task 811 - 'Envoyer Facture'
      if(!$result_action_1324 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_811, '1bacb05aca2d17b42b1de767a8ad45de', 'Envoyer Facture','sendfacture', NULL, '".NULL."', '1', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Envoyer Facture' </li>";}
  //Task 'archivefacture' 'Archiver facture'
  if(!$result_task_885 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('archivefacture', $result_insert_modul, 'archivefacture','factures/main', '1', 'Archiver facture', 'envelope-square', '1', '0', '0','exec', '[-1-2-3-5-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Archiver facture' </li>";}
      // Action Task 885 - 'Archiver facture'
      if(!$result_action_1461 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_885, 'fb808579b1477544f2d1f5d1ce60a35b', 'Archiver facture','archivefacture', NULL, '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Archiver facture' </li>";}
  //Task 'facturearchive' 'Facture Archive'
  if(!$result_task_886 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('facturearchive', $result_insert_modul, 'facturearchive','factures/main', '1', 'Facture Archive', 'cogs', '1', '0', '0','list', '[-1-2-3-5-7-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Facture Archive' </li>";}
      // Action Task 886 - 'Facture Archive'
      if(!$result_action_1462 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_886, 'f5b4b45eeee7f1678dedab10c8f96726', 'Facture Archive','facturearchive', NULL, '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Facture Archive' </li>";}
      // Action Task 886 - 'Détail facture'
      if(!$result_action_1465 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_886, 'e9669b6c74c24963390bd8e864ce2af9', 'Détail facture','detailsfacture', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailsfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détail facture</a></li>'."', '0', '[-1-]', '100', '0', 'Facture Archivée','inverse','".'<span class="label label-sm label-inverse">Facture Archivée</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail facture' </li>";}
