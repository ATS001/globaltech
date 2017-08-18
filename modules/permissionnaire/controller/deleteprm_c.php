
<?php
 $new_prm = new  Mprms();
 
 $new_prm->id_prm = Mreq::tp('id');

if(!MInit::crypt_tp('id', null, 'D') or !$new_prm->get_prm())
  {  
   // returne message error red to client 
   exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
  }

 if($new_prm->delete_prm())
 {
  exit("1#".$new_prm->log);
 }else
 {
  exit("0#".$new_prm->log);
 }
