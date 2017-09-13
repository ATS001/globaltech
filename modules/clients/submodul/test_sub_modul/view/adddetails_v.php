<?php 
$form = new Mform('adddetails', 'adddetails', '', 'produits', '0', 'is_modal');

//Produit

$cat_array[]  = array('required', 'true', 'Choisir un Produit / Service');
$form->select_table('Produit / Service', 'produit', 8, 'produits', 'id', 'designation' , 'designation', $indx = '------' ,$selected=NULL,$multi=NULL, $where=NULL, $cat_array);
$hard_code_pri_u_ht = '<label>Prix Unité HT: </label><input id="prix_u_ht" name="prix_u_ht" class="input-large" type="text">';
//Réference
$form->input('Réference', 'ref', 'text' ,3, '0', Null, $hard_code_pri_u_ht);
//Quantité
$prix_array[]  = array('required', 'true', 'Insérez une Quantité' );
$qte_array[]  = array('minlength', '1', 'Minimum 1 caractères' );
$form->input('Quantité', 'qte', 'text' ,'2 is-number', null, $qte_array);
//Prix 
$prix_array[]  = array('required', 'true', 'Insérez le Prix' );
$prix_array[]  = array('number', 'true', 'Montant invalid' );

														 
//Input Inline
$prix_array[]  = array('required', 'true', 'Insérer le prix' );


$hard_code_prix = '<label>Prix TTC: </label><input id="prix_ttc" name="prix_ttc" class="input-large" type="text">';
$form->input('Prix', 'prix', 'text' ,'3 is-number' , null, $prix_array, $hard_code_prix);
//Remise
$form->input('Remise', 'remis', 'text' ,'1 is-number', '0', Null);
//Form render
$form->render();

?>
<script type="text/javascript">
//On change produit get all informations.
$(document).ready(function() {
	 //called when key is pressed in textbox
      
	 $('#produit').change(function(e) {
	 	var $id_produit = $(this).val();

	 	if($id_produit == null){
	 		return true;
	 	}
	 	$.ajax({

	 		cache: false,
	 		url  : '?_tsk=adddetails&ajax=1',
	 		type : 'POST',
	 		data : '&act=1&id='+$id_produit,
	 		dataType:"html",
	 		success: function(data){
	 			var data_arry = data.split("#");
	 			if(data_arry[0]==0){
	 				ajax_loadmessage(data_arry[1],'nok',5000)
	 			}else{
	 				var arr = new Array();
	 				arr = JSON.parse(data);

	 				$('#ref').val(arr['ref']);
	 				$('#prix').val(arr['prix']);
	 				$('.returned_span').remove();
	 				$('#ref').after(arr['prix_base']);
	 			}

	 		}
	 	})

	 	var validator = $('#adddetails').validate();
	 	validator.resetForm();

	 });
	 $('#qte, #prix, #remis').on('input',function() {
	 	var qty = parseInt($('#qte').val());
	 	var price = parseFloat($('#prix').val());
	 	var remis = parseFloat($('#remis').val()) ? parseFloat($('#remis').val()) : 0;
	 	var prix_remi = price - ((price*remis) / 100) ? price - ((price*remis) / 100) : 0;

	 	$('#prix_ttc').val((qty * prix_remi ? qty * prix_remi : 0).toFixed(2));
	 });
	});	

</script>