<?php 
$form = new Mform('addecheance_contrat', 'addecheance_contrat', '', 'contrats', '0', 'is_modal');
//token main form
$form->input_hidden('tkn_frm', Mreq::tp('tkn'));

//Date échéance
$array_date_echeance[]= array('required', 'true', 'Insérer la date échéance');
$form->input_date('Date échéance', 'date_echeance', 4, date('d-m-Y'), $array_date_echeance);


//Commentaire
$form->input_editor('Commentaire', 'commentaire', 8, $clauses=NULL , $js_array = null,  $input_height = 50);



//Form render
$form->render();

?>
<script type="text/javascript">
//On change produit get all informations.
$(document).ready(function() {
	 //called when key is pressed in textbox     	
    
    $('.send_modal').on('click', function () {
        if(!$('#addecheance_contrat').valid())
        {
            e.preventDefault();
        }else{
            $.ajax({
                cache: false,
                url  : '?_tsk=addecheance_contrat&ajax=1',
                type : 'POST',
                data : $('#addecheance_contrat').serialize(),
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