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
$form = new Mform('add_client', 'add_client', '', 'devis', '0', 'is_modal');
$form->input_hidden('tkn_frm', Mreq::tp('tkn'));
$form->alert_message('Le client est temporaire,<br> il faut compléter le profile avant la facturation ','warning');
$denomination_array[]  = array('minlength', '2', 'Minimum 2 caractères' );
$denomination_array[]  = array('required', 'true', 'Insérer La Dénomination' );
$form->input('Dénomination', 'denomination', 'text' ,6 , null, $denomination_array);
$form->input('Adresse', 'adresse', 'text', 6, null, null);

$tel_array[]  = array('minlength', '8', 'Le N° de téléphone doit contenir au moins 8 chiffres' );
$tel_array[]  = array('number', 'true', 'Entrez un N° Téléphone Valid' );
$form->input('N° Téléphone', 'tel', 'text', 6, null, $tel_array);

$mail_array[]  = array('email', 'true', 'Adresse Email non valide' );
$form->input('Email ', 'email', 'text', 6, null, $mail_array);

//Form render
$form->render();
?>

		
<script type="text/javascript">
$(document).ready(function() {
    
$('.send_modal').on('click', function () {

        if(!$('#add_client').valid())
        {
            e.preventDefault();

        }else{

            $.ajax({
                cache: false,
                url  : '?_tsk=add_client_diver&ajax=1',
                type : 'POST',
                data : $('#add_client').serialize(),
                dataType:"JSON",
                success: function(data)
                {
                    


                    if(data['error'] == false){
                        ajax_loadmessage(data['mess'] ,'nok',5000);
                        return false;
                    }else{
                          
                        $('#id_client')
                        .append($("<option selected=\"selected\"></option>")
                        .attr("value",data['id'])
                        .text(data['nom'])); 
                        
                        $('#id_client').trigger("chosen:updated");
                        $('.close_modal').trigger('click');
                        ajax_loadmessage(data['mess'] ,'ok',3000);

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

		