<?php 
//SYS MRN ERP
// Modul: gsm_technologie => Controller
//Check if id is been the correct id compared with idc
  if(!MInit::crypt_tp('id', null, 'D') )
  {  
  //returne message error red to client 
    exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
  }
view::load('gsm','gsm_technologie');


?>
