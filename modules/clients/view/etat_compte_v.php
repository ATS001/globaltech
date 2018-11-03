<div class="widget-box transparent">
    <div class="widget-header widget-header-large">
        <h3 class="widget-title grey lighter">
            <i class="ace-icon fa fa-adress-card-o green"></i>
            Etat de compte du client : <?php $client->s('denomination'); ?>
        </h3>

        <!-- #section:pages/invoice.info -->



        <div class="widget-toolbar hidden-480">

            <a href="#" class="report_tplt hide" rel="<?php echo MInit::crypt_tp('tplt', 'compte_client') ?>" data="<?php echo MInit::crypt_tp('id', $client->id_client) ?>">
                <i class="ace-icon fa fa-print"></i>
            </a>

        </div>       



        <!-- /section:pages/invoice.info -->
    </div>

    <div class="widget-body">
        <div class="widget-main padding-24">
            <!-- Search Form -->

            <?php //echo Mform::date_x('range', 'Période', 3, true);?>
            <form id="form_search_range" action="#" class="form-horizontal">
                <div class="space-2"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Période du:</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix input-group">
                            <input name="date_debut" id="date_debut" class="form-control col-xs-12" type="text">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar bigger-110"></i>
                            </span>
                        </div>
                    </div>
                    <label class="control-label col-xs-12 col-sm-1" for="email">Et :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix input-group">
                            <input name="date_fin" id="date_fin" class="form-control col-xs-12 col-sm-3" type="text">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar bigger-110"></i>
                            </span>

                            <a href="#" class="input-group-addon btn-primary send_range_date">
                                <i class="fa fa-search bigger-110"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
    

            <!-- End Search Form -->


            <div class="space"></div>

            <div class="zone_table">



            </div>

            
            
        </div>
    </div>
</div>
<script type="text/javascript">
var $id_client=<?php echo $client->s('id'); ?>;
    $('#date_debut, #date_fin').datepicker().next().on(ace.click_event, function () {
        $(this).prev().focus();
    });
    $(document).on('click', '.send_range_date', function () {
        $data = $('#form_search_range').serialize();

        //var sUrl = 'modules/clients/controller/compte_client_c.php';
              
        $.ajax({
            cache: false,
            url  : '?_tsk=compte_client&ajax=1',
            //url: sUrl + '',
            type: 'POST',
            data: $data+'&id='+$id_client ,
            //data: $data , 
            dataType: 'JSON',
            success: function (data) {
                $('.zone_table').empty().append(data['tab']);
                //$('.add_text').empty().append(data['tab']);
                $('.report_tplt').show().removeClass('hide');
                $('.report_tplt').attr('data', $data+'&id='+$id_client + '&pdf=1');
            }
        });


    });
</script>
 