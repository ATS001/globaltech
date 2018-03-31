<ul class="dropdown-menu dropdown-menu-right">
<?php

$id  = Mreq::tp('id');
$idc = MInit::crypt_tp('id',$id);




	echo '<li><a href="#" class="edt_ctr"  data="'.$idc.'" rel="editecheance_contrat" ><i class="ace-icon fa fa-pencil bigger-100"></i> Modifier  </a></li>';
	
	echo '<li><a href="#" class="del_ctr" data="'.$idc.'" rel="addecheance_contrat" ><i class="ace-icon fa fa-trash red bigger-100"></i> Supprimer </a></li>';

 ?>
</ul>