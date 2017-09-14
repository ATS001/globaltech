
<?php
$info_produit = new Mproduit();
$info_produit->id_produit = Mreq::tp('id');
$info_produit->get_produit();


if (!MInit::crypt_tp('id', null, 'D') or ! $info_produit->get_produit()) {

    exit('0#' . $info_produit->log . '<br>Les informations pour cette ligne sont erronées contactez l\'administrateur ');
}

$id_produit = Mreq::tp('id');
$id_produit_c = MInit::crypt_tp('id', $id_produit);
?> 

<div class="pull-right tableTools-container">
    <div class="btn-group btn-overlap">

        <?php TableTools::btn_add('produits', 'Liste des produits', Null, $exec = NULL, 'reply'); ?>


    </div>
</div> 

<div class="page-header">
    <h1>
        Gestion des achats
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
                        TableTools::btn_add('addbuyproduct', 'Ajouter achat', MInit::crypt_tp('id', Mreq::tp('id')));
                    }
                    ?>     


                    <?php TableTools::btn_csv('buyproduct', 'Exporter Liste'); ?>
                    <?php TableTools::btn_pdf('buyproduct', 'Exporter Liste'); ?>

                </div>
            </div>
        </div>

        <div class="table-header">
            Liste "Achats" 
        </div>
        <div>
            <table id="achat_grid" class="table table-bordered table-condensed table-hover table-striped dataTable no-footer">
                <thead>
                    <tr>

                        <th>
                            ID
                        </th>
                        <th>
                            Produit
                        </th>
                        <th>
                            Qte
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

        var table = $('#achat_grid').DataTable({
            bProcessing: true,
            notifcol: 3,
            serverSide: true,

            ajax_url: "buyproducts",
            extra_data: "id=<?php echo Mreq::tp('id'); ?>",

            aoColumns: [
                {"sClass": "center", "sWidth": "5%"}, // Identifiant 
                {"sClass": "left", "sWidth": "25%"},
                {"sClass": "left", "sWidth": "20%"},
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

        $('#achat_grid').on('click', 'tr button', function () {
            var $row = $(this).closest('tr')
            append_drop_menu('buyproducts', table.cell($row, 0).data(), '.btn_action')
        });

        $('#id_search').on('keyup', function () {
            table.column(0).search($(this).val())
                    .draw();

        });



    });

</script>