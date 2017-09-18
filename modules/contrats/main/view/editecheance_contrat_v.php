<?php
//Get all echance info 
$info_echeance = new Mcontrat();
//Set ID of Module with POST id
$info_echeance->id_echeance_contrat = Mreq::tp('id');

//Check if Post ID <==> Post idc or get_modul return false. 
if (!MInit::crypt_tp('id', null, 'D') or !$info_echeance->get_echeance_contrat()) {
    // returne message error red to client 
    exit('3#' . $info_echeance->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ooooooooo');
}
$form = new Mform('editecheance_contrat', 'editecheance_contrat', '', 'contrats', '0', 'is_modal');
//token main form
$form->input_hidden('id', Mreq::tp('id'));
$form->input_hidden('idc', Mreq::tp('idc'));
$form->input_hidden('idh', Mreq::tp('idh'));
//Check tkn_frm
$form->input_hidden('checker_tkn_frm', MInit::cryptage($info_echeance->h('tkn_frm'), 1));
$form->input_hidden('tkn_frm', $info_echeance->h('tkn_frm'));

//Date échéance
$array_date_echeance[] = array('required', 'true', 'Insérer la date échéance');
$form->input_date('Date échéance', 'date_echeance', 4, $info_echeance->h('date_echeance'), $array_date_echeance);

//Commentaire
$form->input_editor('Commentaire', 'commentaire', 8, $info_echeance->h('commentaire'), $js_array = null, $input_height = 200);

//Form render
$form->render();
?>
<script type="text/javascript">
//On change produit get all informations.
$(document).ready(function() {
	 
    
    $('.send_modal').on('click', function () {
        if(!$('#editecheance_contrat').valid())
        {
            e.preventDefault();
        }else{
            $.ajax({
                cache: false,
                url  : '?_tsk=editecheance_contrat&ajax=1',
                type : 'POST',
                data : $('#editecheance_contrat').serialize(),
                dataType:"html",
                success: function(data_f)
                {

                    var data_arry = data_f.split("#");
                    if(data_arry[0]==0){
                        ajax_loadmessage(data_arry[1],'nok',3000);
                    }else{ 

                        ajax_loadmessage(data_arry[1],'ok',3000);
                        var t1 = $('.dataTable').DataTable().draw();
                        $('.close_modal').trigger('click');
                                               
                    }
                },
                timeout: 30000,
                error: function(){
                    ajax_loadmessage('Délai non attendue','nok',5000)

                }
            });

        }

    });



    
});	

</script>