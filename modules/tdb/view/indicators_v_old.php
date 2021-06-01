<div class="row">
  <div class="col-xs-12">        <!-- Small boxes (Stat box) -->
    <div class="row">
      <?php 
        Mobjectif_mensuel::indicator_objectif_mensuel();
        Mobjectif_service::indicator_objectif_service();
        Mdevis::indicator_nbr_devis(); 
        Mdevis::indicator_nbr_facture_non_paye();
      ?> 
    </div>
  </div>
</div>