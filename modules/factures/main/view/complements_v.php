
    <?php
    $info_facture = new Mfacture();
    $info_facture->id_facture = Mreq::tp('id');
    $info_facture->get_facture();


    if (!MInit::crypt_tp('id', null, 'D') or ! $info_facture->get_facture()) {

        exit('0#' . $info_facture->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
    }

    $id_facture = Mreq::tp('id');
    $id_facture_c = MInit::crypt_tp('id', $id_facture);
    ?> 

    <div class="pull-right tableTools-container">
        <div class="btn-group btn-overlap">

            <?php TableTools::btn_add('factures', 'Liste des factures', Null, $exec = NULL, 'reply'); ?>


        </div>
    </div> 

    <div class="page-header">
        <h1>
            Gestion des compléments facture : <?php $info_facture->printattribute_fact('reference'); ?>
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
                        <?php {
                            TableTools::btn_add('addcomplement', 'Ajouter complément', MInit::crypt_tp('id', Mreq::tp('id')));
                        }
                        ?>  
                        <?php TableTools::btn_csv('complements', 'Exporter Liste'); ?>
                        <?php TableTools::btn_pdf('complements', 'Exporter Liste'); ?>

                    </div>
                </div>
            </div>

            <div class="table-header">
                Liste "complément" 
            </div>
            <div>
                <table id="cmpl_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
                    <thead>
                        <tr>

                            <th>
                                ID
                            </th>
                            <th>
                                Désignation
                            </th>
                            <th>
                                Facture
                            </th>
                            <th>
                                Montant
                            </th>
                            <th>
                                Type
                            </th>
                             <th>
                                Date
                            </th>
                            <th>
                                Etat
                            </th>
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

            var table = $('#cmpl_grid').DataTable({
                bProcessing: true,
                notifcol: 5,
                serverSide: true,

                ajax_url: "complements",
                extra_data: "id=<?php echo Mreq::tp('id'); ?>",

                aoColumns: [
                    {"sClass": "center", "sWidth": "5%"}, // Identifiant 
                    {"sClass": "left", "sWidth": "25%"},
                    {"sClass": "left", "sWidth": "20%"},
                     {"sClass": "left", "sWidth": "15%"},
                      {"sClass": "left", "sWidth": "15%"},
                      {"sClass": "left", "sWidth": "10%"},
                    {"sClass": "left", "sWidth": "10%"},
                    {"sClass": "center", "sWidth": "5%"}, // Action
                ],
            });







            $('.export_csv').on('click', function () {
                csv_export(table, 'csv');
            });
            $('.export_pdf').on('click', function () {
                csv_export(table, 'pdf');
            });

            $('#cmpl_grid').on('click', 'tr button', function () {
                var $row = $(this).closest('tr')
                append_drop_menu('complements', table.cell($row, 0).data(), '.btn_action')
            });

            $('#id_search').on('keyup', function () {
                table.column(0).search($(this).val())
                        .draw();

            });



        });

    </script>