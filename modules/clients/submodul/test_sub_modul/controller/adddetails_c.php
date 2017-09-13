<?php 
defined('_MEXEC') or die;
if(MInit::form_verif('adddetails',false))
{
	$posted_data = array(
   'ref'           => Mreq::tp('ref') ,
   'idcategorie'   => Mreq::tp('idcategorie') ,
   
   );
   $tester = $posted_data['ref'];	
	if($tester == 'test'){
		exit("1#form submited ".$posted_data['ref']);
	}else{
		exit("0#form submited ".$posted_data['ref']);
	}
	
}

view::load_view('adddetails');




