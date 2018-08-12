<?php 
//Export Module 'contrats' Date: 09-08-2018
global $db;
if(!$result_insert_modul = $db->Query("insert into modul (modul, description, rep_modul, tables, app_modul, modul_setting, is_setting, etat, services)values('contrats', 'Abonnements','vente/submodul/contrats','contrats','contrats','vente','2', '0', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import Modul 'contrats' </li>";}
  //Task 'contrats' 'Abonnements'
  if(!$result_task_1022 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('contrats', $result_insert_modul, 'contrats','vente/submodul/contrats', '1', 'Abonnements', 'cloud', '1', '0', '0','list', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Abonnements' </li>";}
      // Action Task 1022 - 'Gestion Abonnements'
      if(!$result_action_1732 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, '6486df7b20b5671bb9d5a7f1782cd83e', 'Gestion Abonnements','contrats', 'this_url', '".NULL."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Gestion Abonnements' </li>";}
      // Action Task 1022 - 'Modifier Abonnement'
      if(!$result_action_1733 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, '3a334b145cb19dc0c1ba8e09d9b32e01', 'Modifier Abonnement','editcontrats', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="editcontrats"  ><i class="ace-icon fa fa-pencil bigger-100"></i> Modifier Abonnement</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier Abonnement' </li>";}
      // Action Task 1022 - 'Détails Abonnement'
      if(!$result_action_1734 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, 'cec5e9d00fee11cec80d9565802cc5ed', 'Détails Abonnement','detailcontrat', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Abonnement</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Abonnement' </li>";}
      // Action Task 1022 - 'Valider Abonnement'
      if(!$result_action_1735 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, '50e084781cc0f2bdbd13f42b34417fe4', 'Valider Abonnement','validcontrat', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="validcontrat"  ><i class="ace-icon fa fa-check bigger-100"></i> Valider Abonnement</a></li>'."', '0', '[-1-]', '0', '1', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider Abonnement' </li>";}
      // Action Task 1022 - 'Détails Abonnement'
      if(!$result_action_1736 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, 'ccb873a672244752b669505190df3391', 'Détails Abonnement','detailcontrat', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Abonnement</a></li>'."', '0', '[-1-]', '1', '0', 'Abonnement Validé','success','".'<span class="label label-sm label-success">Abonnement Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Abonnement' </li>";}
      // Action Task 1022 - 'Renouveler Abonnement'
      if(!$result_action_1737 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, 'd42c92265d03b24bf616f32e32f8eabf', 'Renouveler Abonnement','renouvelercontrats', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="renouvelercontrats"  ><i class="ace-icon fa fa-share bigger-100"></i> Renouveler Abonnement</a></li>'."', '0', '[-1-]', '2', '1', 'Attente Renouvelement','warning','".'<span class="label label-sm label-warning">Attente Renouvelement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Renouveler Abonnement' </li>";}
      // Action Task 1022 - 'Détails Abonnement'
      if(!$result_action_1738 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, '4e4550c7e12b3c778ce1ae7318571f43', 'Détails Abonnement','detailcontrat', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Abonnement</a></li>'."', '0', '[-1-]', '3', '0', 'Abonnement Expiré','info','".'<span class="label label-sm label-info">Abonnement Expiré</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Abonnement' </li>";}
      // Action Task 1022 - 'Détails Abonnement'
      if(!$result_action_1739 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, 'dc92a66a00e01be190d55a183ad696ca', 'Détails Abonnement','detailcontrat', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Abonnement</a></li>'."', '0', '[-1-]', '2', '0', 'Attente Renouvelement','warning','".'<span class="label label-sm label-warning">Attente Renouvelement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Abonnement' </li>";}
      // Action Task 1022 - 'Détails Abonnement'
      if(!$result_action_1740 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, 'd39edcc7970eb850f7c29eec5d957af5', 'Détails Abonnement','detailcontrat', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Abonnement</a></li>'."', '0', '[-1-]', '4', '0', 'Abonnement Expiré','inverse','".'<span class="label label-sm label-inverse">Abonnement Expiré</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Abonnement' </li>";}
      // Action Task 1022 - 'Renouveler Abonnement'
      if(!$result_action_1741 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, 'abe765108f8e2ab866a3f5240c873510', 'Renouveler Abonnement','renouvelercontrats', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="renouvelercontrats"  ><i class="ace-icon fa fa-share bigger-100"></i> Renouveler Abonnement</a></li>'."', '0', '[-1-]', '3', '0', 'Abonnement Expiré','info','".'<span class="label label-sm label-info">Abonnement Expiré</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Renouveler Abonnement' </li>";}
      // Action Task 1022 - 'Résilier Abonnement'
      if(!$result_action_1742 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, '782d3f0e21f1e813364316ce3837d85c', 'Résilier Abonnement','resiliercontrat', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="resiliercontrat"  ><i class="ace-icon fa fa-ban bigger-100"></i> Résilier Abonnement</a></li>'."', '0', '[-1-]', '1', '0', 'Abonnement Validé','success','".'<span class="label label-sm label-success">Abonnement Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Résilier Abonnement' </li>";}
      // Action Task 1022 - 'Détails Abonnement'
      if(!$result_action_1743 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, 'bbaa083453265592ee17fcd4c99cf476', 'Détails Abonnement','detailcontrat', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Abonnement</a></li>'."', '0', '[-1-]', '5', '0', 'Abonnement Résilié','inverse','".'<span class="label label-sm label-inverse">Abonnement Résilié</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Abonnement' </li>";}
      // Action Task 1022 - 'Echéances'
      if(!$result_action_1744 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, 'e5c2a867baf3429d758742a021d4795c', 'Echéances','echeances', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="echeances"  ><i class="ace-icon fa fa-exchange bigger-100"></i> Echéances</a></li>'."', '0', '[-1-]', '1', '0', 'Abonnement Validé','success','".'<span class="label label-sm label-success">Abonnement Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Echéances' </li>";}
      // Action Task 1022 - 'Echéances'
      if(!$result_action_1745 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, 'dfc236bfafa081e74f23a1c5f631fe78', 'Echéances','echeances', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="echeances"  ><i class="ace-icon fa fa-exchange bigger-100"></i> Echéances</a></li>'."', '0', '[-1-]', '2', '0', 'Attente Renouvelement','warning','".'<span class="label label-sm label-warning">Attente Renouvelement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Echéances' </li>";}
      // Action Task 1022 - 'Détails Abonnement'
      if(!$result_action_1746 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1022, '67ea1a1df98cbc5604f24e512145519f', 'Détails Abonnement','detailcontrat', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="detailcontrat"  ><i class="ace-icon fa fa-eye bigger-100"></i> Détails Abonnement</a></li>'."', '0', '[-1-3-]', '100', '0', 'Abonnement Archivé','inverse','".'<span class="label label-sm label-inverse">Abonnement Archivé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détails Abonnement' </li>";}
  //Task 'addcontrats' 'Ajouter Abonnement'
  if(!$result_task_1023 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addcontrats', $result_insert_modul, 'addcontrat','vente/submodul/contrats', '1', 'Ajouter Abonnement', 'cogs', '1', '0', '0','formadd', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Abonnement' </li>";}
      // Action Task 1023 - 'Ajouter contrat'
      if(!$result_action_1747 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1023, '87f4c3ed4713c3bc9e3fef60a6649055', 'Ajouter contrat','addcontrats', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter contrat' </li>";}
  //Task 'editcontrats' 'Modifier Abonnement'
  if(!$result_task_1024 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editcontrats', $result_insert_modul, 'editcontrat','vente/submodul/contrats', '1', 'Modifier Abonnement', 'cogs', '1', '0', '0','formedit', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier Abonnement' </li>";}
      // Action Task 1024 - 'Modifier contrat'
      if(!$result_action_1748 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1024, '9e49a431d9637544cefa2869fd7278b9', 'Modifier contrat','editcontrats', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier contrat' </li>";}
  //Task 'deletecontrat' 'Supprimer Abonnement'
  if(!$result_task_1025 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('deletecontrat', $result_insert_modul, 'deletecontrat','vente/submodul/contrats', '1', 'Supprimer Abonnement', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Supprimer Abonnement' </li>";}
      // Action Task 1025 - 'Supprimer contrat'
      if(!$result_action_1749 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1025, '1e9395a182a44787e493bc038cd80bbf', 'Supprimer contrat','deletecontrat', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Supprimer contrat' </li>";}
  //Task 'validcontrat' 'Valider Abonnement'
  if(!$result_task_1026 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('validcontrat', $result_insert_modul, 'validcontrat','vente/submodul/contrats', '1', 'Valider Abonnement', 'cogs', '1', '0', '0','exec', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Valider Abonnement' </li>";}
      // Action Task 1026 - 'Valider contrat'
      if(!$result_action_1750 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1026, '460d92834715b149c4db28e1643bd932', 'Valider contrat','validcontrat', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Valider contrat' </li>";}
  //Task 'detailcontrat' 'Détails Abonnement'
  if(!$result_task_1027 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('detailcontrat', $result_insert_modul, 'detailcontrat','vente/submodul/contrats', '1', 'Détails Abonnement', 'cogs', '1', '0', '0','profil', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Détails Abonnement' </li>";}
      // Action Task 1027 - 'Détail contrat'
      if(!$result_action_1751 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1027, 'bbcf2879c2f8f60cfa55fa97c6e79268', 'Détail contrat','detailcontrat', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Détail contrat' </li>";}
  //Task 'addecheance_contrat' 'Ajouter Echéance Abonnement'
  if(!$result_task_1028 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('addecheance_contrat', $result_insert_modul, 'addecheance_contrat','vente/submodul/contrats', '1', 'Ajouter Echéance Abonnement', 'cogs', '1', '0', '0','formadd', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Ajouter Echéance Abonnement' </li>";}
      // Action Task 1028 - 'Ajouter échéance '
      if(!$result_action_1752 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1028, 'fe058ccb890b25a54866be7f24a40363', 'Ajouter échéance ','addecheance_contrat', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Ajouter échéance ' </li>";}
  //Task 'editecheance_contrat' 'Modifier Echéance Abonnement'
  if(!$result_task_1029 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('editecheance_contrat', $result_insert_modul, 'editecheance_contrat','vente/submodul/contrats', '1', 'Modifier Echéance Abonnement', 'cogs', '1', '0', '0','formedit', '[-1-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Modifier Echéance Abonnement' </li>";}
      // Action Task 1029 - 'Modifier échéance contrat'
      if(!$result_action_1753 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1029, '36a248f56a6a80977e5c90a5c59f39d3', 'Modifier échéance contrat','editecheance_contrat', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente de validation','warning','".'<span class="label label-sm label-warning">Attente de validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Modifier échéance contrat' </li>";}
  //Task 'renouvelercontrats' 'Renouveler Abonnement'
  if(!$result_task_1030 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('renouvelercontrats', $result_insert_modul, 'renouvelercontrat','vente/submodul/contrats', '1', 'Renouveler Abonnement', 'exchange', '1', '0', '0','formedit', '[-1-2-3-5-4-7-6-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Renouveler Abonnement' </li>";}
      // Action Task 1030 - 'Renouveler Contrat'
      if(!$result_action_1754 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1030, 'f0567980556249721f24f2fc88ebfed5', 'Renouveler Contrat','renouvelercontrats', NULL, '".NULL."', '0', '[-1-]', '0', '0', 'Attente Renouvelement','danger','".'<span class="label label-sm label-danger">Attente Renouvelement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Renouveler Contrat' </li>";}
  //Task 'resiliercontrat' 'Résilier Abonnement'
  if(!$result_task_1031 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('resiliercontrat', $result_insert_modul, 'resiliercontrat','vente/submodul/contrats', '1', 'Résilier Abonnement', 'trash', '1', '0', '0','exec', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Résilier Abonnement' </li>";}
      // Action Task 1031 - 'Résilier Contrat'
      if(!$result_action_1755 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1031, 'd3fc6f1bcca0a0250c5f6de29fd72b80', 'Résilier Contrat','resiliercontrat', NULL, '".NULL."', '1', '[-1-]', '0', '0', 'Contrat Validé','success','".'<span class="label label-sm label-success">Contrat Validé</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Résilier Contrat' </li>";}
  //Task 'echeances' 'Echéances'
  if(!$result_task_1032 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('echeances', $result_insert_modul, 'echeances','vente/submodul/contrats', '1', 'Echéances', 'exchange', '1', '0', '0','list', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Echéances' </li>";}
      // Action Task 1032 - 'Echéances'
      if(!$result_action_1756 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1032, '428bf9d4c56394d24e15f5458b077990', 'Echéances','echeances', NULL, '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','success','".'<span class="label label-sm label-success">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Echéances' </li>";}
      // Action Task 1032 - 'Générer Facture'
      if(!$result_action_1757 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1032, 'b0cff04f8af9234adbc81e7f679c7176', 'Générer Facture','generatefacture', 'this_exec', '".'<li><a href="#" class="this_exec" data="%id%" rel="generatefacture"  ><i class="ace-icon fa fa-book bigger-100"></i> Générer Facture</a></li>'."', '0', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Générer Facture' </li>";}
      // Action Task 1032 - 'Afficher Facture'
      if(!$result_action_1758 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1032, '1596760bb0380a6a77c784ec92eb6fa7', 'Afficher Facture','afficherfacture', 'this_url', '".'<li><a href="#" class="this_url" data="%id%" rel="afficherfacture"  ><i class="ace-icon fa fa-eye bigger-100"></i> Afficher Facture</a></li>'."', '0', '[-1-]', '1', '0', 'Facture Générée','success','".'<span class="label label-sm label-success">Facture Générée</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Afficher Facture' </li>";}
  //Task 'generatefacture' 'Générer Facture'
  if(!$result_task_1033 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('generatefacture', $result_insert_modul, 'generatefacture','vente/submodul/contrats', '1', 'Générer Facture', 'book', '1', '0', '0','exec', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Générer Facture' </li>";}
      // Action Task 1033 - 'Générer Facture'
      if(!$result_action_1759 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1033, 'b37af8eb31b7082afa5ad48f0d618f3b', 'Générer Facture','generatefacture', NULL, '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Générer Facture' </li>";}
  //Task 'afficherfacture' 'Afficher Facture'
  if(!$result_task_1034 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('afficherfacture', $result_insert_modul, 'afficherfacture','vente/submodul/contrats', '1', 'Afficher Facture', 'eye', '1', '0', '0','profil', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Afficher Facture' </li>";}
      // Action Task 1034 - 'Afficher Facture'
      if(!$result_action_1760 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1034, 'd860c94cc554cc0ff03af97a9248d2de', 'Afficher Facture','afficherfacture', NULL, '".NULL."', '1', '[-1-]', '0', '0', 'Attente Validation','warning','".'<span class="label label-sm label-warning">Attente Validation</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Afficher Facture' </li>";}
  //Task 'archivecontrats' 'Archiver Abonnement'
  if(!$result_task_1035 = $db->Query("INSERT INTO task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat, type_view, services)VALUES('archivecontrats', $result_insert_modul, 'archivecontrats','vente/submodul/contrats', '1', 'Archiver Abonnement', 'download', '1', '0', '0','exec', '[-1-2-3-5-]')")){$this->error = false; $this->log .= "<li> Error Import task 'Archiver Abonnement' </li>";}
      // Action Task 1035 - 'Archiver Abonnement'
      if(!$result_action_1761 = $db->Query("INSERT INTO task_action (appid, idf, descrip, app, mode_exec, code, type, service, etat_line, notif, etat_desc, message_class, message_etat)VALUES($result_task_1035, 'f4a38ba2849f74327c9ad49dcf5ae6a9', 'Archiver Abonnement','archiveabonnement', NULL, '".NULL."', '1', '[-1-3-]', '0', '0', 'Archiver Abonnement','inverse','".'<span class="label label-sm label-inverse">Archiver Abonnement</span>'."')")){$this->error = false; $this->log .= "<li> Error Import task_action 'Archiver Abonnement' </li>";}
