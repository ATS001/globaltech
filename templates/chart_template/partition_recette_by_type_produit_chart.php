<?php 
if(Mreq::tp('filtr') == 1)
{	
	$id_chart  = Mreq::tp('chart');
	$id_charth = Mreq::tp('charth');
	$id_chartc = Mreq::tp('chartc');
	
	$form = new Mform($id_chart, 'chart', '', '', '0', '');
	$form->input_hidden('chart', $id_chart);
	$form->input_hidden('charth', $id_charth);
	$form->input_hidden('chartc', $id_chartc);
	
	$form->input_hidden('send_filter', 1);
	$form->input_date('Date début', 'date_s', 4, date('d-m-Y'), $js_array = null, $hard_code = null);
	$form->input_date('Date fin', 'date_e', 4, date('d-m-Y'), $js_array = null, $hard_code = null);
    $form->render();
?>
<script type="text/javascript">
$('.send_modal_chart_filter').on('click', function () {
	
        if(!$('#<?php echo $id_chart ?>').valid())
        {
            e.preventDefault();
        }else{
        	var $contnair_chart = '#'+$('#container').val();
            var $widget = '#<?php echo $id_chart ?>_body';
           // alert($widget);
            
            $.ajax({
                cache: false,
                url  : '?_tsk=chart&ajax=1',
                type : 'POST',
                data : $('#<?php echo $id_chart ?>').serialize(),
                dataType:"html",
                success: function(data_f)
                {                	
                    var data_arry = data_f.split("#");
                    if(data_arry[0]==0){
                        ajax_loadmessage(data_arry[1],'nok',3000);
                    }else{
                    	
                        $($widget).empty().html(data_f);   
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
</script>
<?php 	
}else{

	$chart = new MHighchart();
	$chart->titre = 'Partition recette par type produit';
	$chart->id_chart = 'partition_recette_by_type_produit';
	$chart->items = 'Fcfa';
	//Set to true if called from Ajax after filter
	$where = " intvl BETWEEN '".date('Y')."-01-01' AND '".date('Y')."-12-31'";
	if(Mreq::tp('send_filter') == 1)
	{
	    
	    $date_s = date('Y-m-d', strtotime(Mreq::tp('date_s')));
	    $date_e = date('Y-m-d', strtotime(Mreq::tp('date_e')));
	    if(!MInit::compare_date($date_s, $date_e)){
	    	exit('0#Les dates sont incohérents');
	    }
	    $where = " intvl BETWEEN '$date_s' AND '$date_e' ";
	}
	if(Mreq::tp('chart') != NULL)
	{
		$chart->chart_only = true;
	}

$chart->Pie_render('v_sum_best_type_product', 6, $where);
}