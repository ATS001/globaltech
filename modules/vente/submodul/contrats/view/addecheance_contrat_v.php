<div class="table-header">
            Abonnement Du: 
            <?php 
            $date_debut=Mreq::tp('dat_ef'); 
            echo $date_debut; ?>
            Au: <?php 
            $date_fin=Mreq::tp('dat_fn');  
            echo $date_fin; ?>
</div>
<?php 
$form = new Mform('addecheance_contrat', 'addecheance_contrat', '', 'contrats', '0', 'is_modal');
//token main form
$form->input_hidden('tkn_frm',Mreq::tp('tkn'));
$form->input_hidden('dat_ef', Mreq::tp('dat_ef'));
$form->input_hidden('dat_fn', Mreq::tp('dat_fn'));


/*$new_contrat1 = new Mcontrat($posted_data);
$new_contrat1->get_total_devis($posted_data['iddevis']);

var_dump($new_contrat1->Shw_type('totalttc',1));
*/
$new_contrat2 = new Mcontrat();
$new_contrat2->get_total_echeances(Mreq::tp('tkn'));

var_dump($new_contrat2->Shw_type('montant_total', 1));

//Date échéance
$array_date_echeance[]= array('required', 'true', 'Insérer la date échéance');
$form->input_date('Date échéance', 'date_echeance', 4, date('d-m-Y'), $array_date_echeance);


//Montant
$array_montant[]= array('required', 'true', 'Insérer le montant à facturer');
$array_montant[]= array('number', 'true', 'Montant invalid' );
$form->input('Montant TTC', 'montant', 'text' ,3, '100000', $array_montant);


//Commentaire
$form->input_editor('Commentaire', 'commentaire', 4, $clauses=NULL , $js_array = null,  $input_height = 200);



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
                        //$('#idtype_echeance').prop('disabled', 'disabled');
                        //$('#idtype_echeance').prop('disabled', true).trigger("chosen:updated");
                        //$('#idtype_echeance_chosen').hide();
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