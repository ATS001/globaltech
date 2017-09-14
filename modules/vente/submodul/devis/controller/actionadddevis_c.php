<ul class="dropdown-menu dropdown-menu-right">
<?php

$id  = Mreq::tp('id');
$idc = MInit::crypt_tp('id',$id);




	echo '<li><a href="#" class="edt_det"  data="'.$idc.'" rel="edit_detaildevis" ><i class="ace-icon fa fa-pencil bigger-100"></i> Modifier  </a></li>';
	
	echo '<li><a href="#" class="del_det" data="'.$idc.'" rel="add_detaildevis" ><i class="ace-icon fa fa-trash red bigger-100"></i> Supprimer </a></li>';

 ?>
</ul>