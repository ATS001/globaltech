<?php
/**
* Extend MDevis
* Generate Devis PDF
*/
class MDevis_PDF extends MDevis
{
	var $html = Null;
	function __construct() {
       parent::__construct();
       
    }
	
	Protected function generate_devis_pdf()
	{
		//$this->html = 'Test devis';
		$title = NULL;
		$html = $this->html;
		$html = '<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Modèle de Facture HTML5 (Invoice Model)</title>
  
  
  
      <link rel="stylesheet" href="css/style.css">

  
</head>

<body>
  
<head>
  <meta charset="UTF-8"/>
  <title>Facture José Roux</title>
  <link href="https://fonts.googleapis.com/css?family=Nunito:300|Raleway:200,300" rel="stylesheet" type="text/css"/>
</head>
<body>
  <header>
    <h1>FACTURE
      <h2>José Roux − Interactive Design</h2>
    </h1>
  </header>
  <section class="flex">
    <dl>
      <dt>Facture #</dt>
      <dd>20140603</dd>
      <dt>Date de facturation</dt>
      <dd>03.06.2014</dd>
    </dl>
  </section>
  <section class="flex">
    <dl class="bloc">
      <dt>Facturé à:</dt>
      <dd>
        Company X &amp; Son Inc.<br>
        2789 Some street,<br>
        Big City, Québec, J3X 1J1
        <dl>
          <dt>Attn</dt>
          <dd>Le Big Boss</dd>
          <dt>Téléphone</dt>
          <dd>(450) 555-2663</dd>
          <dt>Courriel</dt>
          <dd>bigboss@bigcompanylonglongemail.com</dd>
        </dl>
      </dd>
    </dl>
    <dl class="bloc">
      <dt>Description de service:</dt>
      <dd>Développement AIR</dd>
      <dt>Période totale:</dt>
      <dd>24 Mai au 2 Juin 2014</dd>
    </dl>
  </section>
  <table>
    <thead>
      <tr> 
        <th>Période</th>
        <th>Description</th>
        <th>Heures</th>
        <th>Taux</th>
        <th>Montant</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>24 Mai au 2 Juin</td>
        <td>Dévelopement du jeu Tomatina</td>
        <td>24&#8202;h</td>
        <td>20&#8202;$/h</td>
        <td>480&#8202;$</td>
      </tr>
    </tbody>
    <tfoot>
      <tr> 
        <td colspan="3">− Faire les chèques payable au nom de moi −</td>
        <td>Total:</td>
        <td>480&#8202;$</td>
      </tr>
    </tfoot>
  </table>
  <footer>
    <p>Moi – Informatique − Développement WEB | <a href="http://joseroux.com">joseroux.com</a></p>
    <p>1777 some street in the woods, Wentworth-Nord, Qc, J0T 1Y0 | Tél. 450-555-1000 | <a href="mailto:mail@me.com">mail@me.com</a></p>
  </footer>
</body>
  
  
</body>
</html>';
		$file_name = 'Devis';
		$file_export = MPATH_TEMP.$file_name.'_test.pdf';
		include_once MPATH_THEMES.'pdf_template/devis_pdf.php';

		
	}

	
}
?>
