
<?php
$info_uhf_vhf_stations = new Muhf_vhf_stations();
$info_uhf_vhf_stations->id_uhf_vhf_stations = Mreq::tp('id');
$info_uhf_vhf_stations->get_uhf_vhf_stations();
//$result = $info_uhf_vhf_stations->check_nbr_clients();

if (!MInit::crypt_tp('id', null, 'D') or ! $info_uhf_vhf_stations->get_uhf_vhf_stations()) {

    exit('0#' . $info_uhf_vhf_stations->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
}

$id_uhf_vhf_stations = Mreq::tp('id');
$id_uhf_vhf_stations_c = MInit::crypt_tp('id', $id_uhf_vhf_stations);
?> 

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php
        TableTools::btn_add('uhf_vhf_stations', 'Liste des stations', Null, $exec = NULL, 'reply');
        ?>


    </div>
</div>
<div class="page-header">
    <h1>
        Gestion des clients UHF/VHF
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <div class="clearfix">
            <div class="pull-right tableTools-container">
                <div class="btn-group btn-overlap">



                    <?php
                    TableTools::btn_add('adduhf_vhf_clt_fixe', 'Ajouter station Fixe', MInit::crypt_tp('id', Mreq::tp('id')));
                    ?>
                    <?php
                    TableTools::btn_add('adduhf_vhf_clt_mobile', 'Ajouter station Mobile', MInit::crypt_tp('id', Mreq::tp('id')));
                    ?>
                    <?php
                    TableTools::btn_add('adduhf_vhf_clt_handset', 'Ajouter station Handset', MInit::crypt_tp('id', Mreq::tp('id')));
                    ?>

                    <?php TableTools::btn_csv('uhf_vhf_clients', 'Exporter Liste'); ?>
                    <?php TableTools::btn_pdf('uhf_vhf', 'Exporter Liste'); ?>

                </div>
            </div>
        </div>

        <div class="table-header">
            Liste des clients UHF/VHF
        </div>
        <div>
            <table id="uhf_vhf_clients_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
                <thead>
                    <tr>

                        <th>
                            ID
                        </th>
                        <th>
                            Site
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            N° Serie
                        </th>

                        <th>
                            Active
                        </th>
<!--                        <th>
                            Etat
                        </th>-->

                        <th>
                            #
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">


    $(document).ready(function () {

        var table = $('#uhf_vhf_clients_grid').DataTable({
            bProcessing: true,
            notifcol: 5,
            serverSide: true,

            ajax_url: "uhf_vhf_clients",
            extra_data: "id=<?php echo Mreq::tp('id'); ?>",

            aoColumns: [
                {"sClass": "center", "sWidth": "5%"}, //ID
                {"sClass": "left", "sWidth": "15%"}, //Site
                {"sClass": "left", "sWidth": "15%"}, //Permissionnaire
                {"sClass": "left", "sWidth": "15%"}, //Site
                {"sClass": "left", "sWidth": "10%"}, //Ville
                //{"sClass": "center", "sWidth": "10%"}, //Etat
                {"sClass": "center", "sWidth": "10%"}, //#
            ],
        });


        $('.export_csv').on('click', function () {
            csv_export(table, 'csv');
        });
        $('.export_pdf').on('click', function () {
            csv_export(table, 'pdf');
        });

        $('#uhf_vhf_clients_grid').on('click', 'tr button', function () {
            var $row = $(this).closest('tr')
            //alert(table.cell($row, 0).data());
            append_drop_menu('uhf_vhf_clients', table.cell($row, 0).data(), '.btn_action')
        });


    });

</script>