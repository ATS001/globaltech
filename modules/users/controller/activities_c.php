<?php
if(!MInit::crypt_tp('id', null, 'D'))
  {  
   // returne message error red to client 
    exit('0#<br>Les informations pour cette ligne sont erronées contactez l\'administrateur');
 }
view::load('users','activities');


?>

