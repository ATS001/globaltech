<?php 
//First check target no Hack
if(!defined('_MEXEC'))die();
//SYS GLOBAL TECH
// Modul: devis
//Created : 10-01-2018
//View
?>
			
<?php
//$form = new Mform('add_detaildevis', 'add_detaildevis', '', 'devis', '0', 'is_modal'); 
$form = new Mform('add_temp_achat', 'add_temp_achat', '', 'devis', '0', 'is_modal');
$form->input_hidden('id_produit',   Mreq::tp('id_produit'));
$form->input_hidden('qte_exist',   Mreq::tp('qte_exist'));
$form->input_hidden('need',   Mreq::tp('need'));
        
//quantité
$qte_array[]  = array('number', 'true', 'Entrez un nombre valide' );
$form->input('Quantité', 'qte', 'text' ," 6 is-number alignRigth", Mreq::tp('need'), $qte_array);

    
//Form render
$form->render();
?>

		
<script type="text/javascript">
$(document).ready(function() {
    
$('.send_modal').on('click', function () {

        if(!$('#add_temp_achat').valid())
        {
            e.preventDefault();

        }else{

            $.ajax({
                cache: false,
                url  : '?_tsk=validdevisclient&ajax=1&act=1',
                type : 'POST',
                data : $('#add_temp_achat').serialize(),
                dataType:"JSON",
                success: function(data)
                {            
                    if(data['error'] == false){
                        ajax_loadmessage(data['mess'] ,'nok',5000);
                        return false;
                    }else{
                        $('#stok_'+$('#id_produit').val()).text(data['new_stok']).removeClass('badge-danger').addClass('badge-success');
                        $('.close_modal').trigger('click');
                        $('#appro_stok_'+$('#id_produit').val()).remove();
                    }               
                 },
                timeout: 30000,
                error: function (xhr, ajaxOptions, thrownError) {
                    
                    ajax_loadmessage("Erreur opération" ,'nok',5000);
                }
            });

        }

    });   

});
</script>	

		