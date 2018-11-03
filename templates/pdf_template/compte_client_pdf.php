<?php

//============================================================+
// File name   : compte_client_pdf.php
// Last Update : 02/11/2018
//
// Description : All info compte client
//

//============================================================+
//Get all info Compte client from model
$compte_client = new Mclients();
$compte_client->id_client = Mreq::tp('id');

$id_client=Mreq::tp('id');
$date_debut=Mreq::tp('date_debut');
$date_fin=Mreq::tp('date_fin');


/*
if (!MInit::crypt_tp('id', null, 'D') or ! $compte_client->get_client()) {
    // returne message error red to facture 
    
    exit('0#<br>Les informationss pour cette template sont erronées, contactez l\'administrateur');
}
 * 
 */

//Execute Pdf render

if(!$compte_client->Get_detail_client_show($date_debut,$date_fin,$id_client))
{
	exit("0#".$compte_client->log);

}
global $db;
$tableau_body = null;

$headers = array(
    'Id'  => '6[#]C',
    'Date' => '10[#]C',
    'Description' => '43[#]L',
    'Débit' => '10[#]R',
    'Crédit' => '10[#]R',
    'Solde' => '10[#]R',
    'Reference'=>'12[#]R',
    'Solde Final'=>'10[#]R',
);


$compte_client_info = $compte_client->compte_client_info;
//var_dump($compte_client_info);

$tableau_head_product = MySQL::make_table_head($headers);
$tableau_body_product = $db->GetMTable_pdf($headers);


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    var $Table_head = null;
    var $Table_body = null;
    var $Table_head2 = null;
    var $Table_body2 = null;
    var $info_ste = array();
    var $info_client = array();
    var $compte_client_info = array();
    var $periode = null;
    var $qr = false;
    var $no_tabl_head = true;

    //Page header
    public function Header() {
        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
        // Logo
        $image_file = MPATH_IMG . MCfg::get('logo');
        $this->writeHTMLCell(50, 25, '', '', '', 0, 0, 0, true, 'C', true);
        $this->Image($image_file, 22, 6, 30, 23, 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);

        //Get info ste from DB
        $ste_c = new MSte_info();

        $ste = $ste_c->get_ste_info_report_head(1);
        $this->writeHTMLCell(0, 0, '', 30, $ste, '', 0, 0, true, 'L', true);
        $this->SetTextColor(0, 50, 127);
        // Set font
        $this->SetFont('helvetica', 'B', 22);
        //Ste
        // Title
        $titre_doc = '<h1 style="letter-spacing: 2px;color;#495375;font-size: 20pt;">ETAT DE COMPTE</h1>';
        $this->writeHTMLCell(0, 0, 140, 10, $titre_doc, 'B', 0, 0, true, 'R', true);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 9);
        $date_ref=date("M Y");  
       
        $detail_client = '<table cellspacing="3" cellpadding="2" border="0">
		<tr>
                <td style="width:25%; color:#A1A0A0;"><strong>REFERENCE ETAT DE COMPTE 
                </strong></td>
                <td style="width:5%;">:</td>
                <td style="width:75%; background-color: #eeecec; "> GT-EC/' .$this->compte_client_info['id'].'/'.$date_ref.'</td>
                </tr>
                <tr>
		<td style="width:25%; color:#A1A0A0;"><strong>REFERENCE CLIENT</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:75%; background-color: #eeecec;">' . $this->compte_client_info['reference'] . '</td>
		</tr> 
		<tr>
		<td style="width:25%; color:#A1A0A0;"><strong>MONTANT DÛ</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:75%; background-color: #eeecec; ">' . $this->compte_client_info['solde_final'] . '</td>
		</tr>
                <tr>
                <td style="width:25%; color:#A1A0A0;"><strong>DELAI DE PAIEMENT
                </strong></td>
                <td style="width:5%;">:</td>
                <td style="width:75%; background-color: #eeecec; "> 10 Jours </td>
                </tr>
                </table>';
        

        $this->writeHTMLCell(0, 0, 105, 23, $detail_client, '', 0, 0, true, 'L', true);
    
        //$this->Ln();
        //Comment fati 04/03 pour probleme tableau complement
        $this->setCellPadding(0);
        $height = $this->getLastH() + $this->GetY();
        //$this->SetTopMargin(10 + $this->GetY());
        //Info général
        $tableau_head = $this->Table_head;
        if ($this->no_tabl_head) {
            $this->writeHTMLCell('', '', 15, $height, $tableau_head, 0, 0, 0, true, 'L', true);
            $height = $this->getLastH();
            $this->SetTopMargin($height + $this->GetY());
            //end comment fati
        }
  
    }

    // Page footer
    public function Footer() {
        //if($this->qr == true){
// QRCODE,H : QR-CODE Best error correction
        //$qr_content = $this->info_facture['reference'] . "\n" . $this->info_devis['denomination'] . "\n" . $this->info_facture['date_facture'];
        $style = array(
            'border' => 1,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(260,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        //write2DBarcode($code, $type, $x='', $y='', $w='', $h='', $style='', $align='', $distort=false)
        $this->SetY(-30);
       // $this->write2DBarcode($qr_content, 'QRCODE,H', 15, '', 25, 25, $style, 'N');
        //}
        $ste_c = new MSte_info();
        $this->SetY(-30);
        $ste = $ste_c->get_ste_info_report_footer(1);
        $this->writeHTMLCell(0, 0, '', '', $ste, '', 0, 0, true, 'C', true);
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }

    public function writeHTMLTogether($html, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '') {
        $cp = $this->getPage();
        $this->startTransaction();

        $this->writeHTML($html, $ln, $fill, $reseth, $cell, $align);

        if ($this->getPage() > $cp) {
            $this->rollbackTransaction(true); //true is very important
            $this->AddPage();
            $this->writeHTML($html, $ln, $fill, $reseth, $cell, $align);
        } else {
            $this->commitTransaction();
        }
    }

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->Table_head = $tableau_head_product;
$pdf->info_client = $compte_client_info;
$pdf->compte_client_info=$compte_client_info;


// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor(session::get('username'));
$pdf->SetTitle('compte_client');
$pdf->SetSubject(MCfg::get('client_titre'));
$pdf->SetKeywords(MCfg::get('client_titre'));


// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 90, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

$pdf->SetFooterMargin(30);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 30);


// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
// set font
$pdf->SetFont('helvetica', '', 9);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
//If is generated to stored the QR is need
// Print text using writeHTMLCell()
$pdf->Table_body = $tableau_body_product;
$html = $pdf->Table_body;
// ---------------------------------------------------------




//$obj = new nuts($pdf->info_facture['total_ttc'], $pdf->info_devis['devise']);
//$ttc_lettre = $obj->convert("fr-FR");
//$total_no_remise = $pdf->info_facture['total_ht'];

/* $block_remise = '<tr>
  <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong>Remise '.$pdf->info_facture['valeur_remise'].' %</strong></td>
  <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
  <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>'.$pdf->info_facture['total_remise'].'  '.$pdf->info_devis['devise'].'</strong></td>
  </tr>'; */
$block_ttc = '<tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong>TVA 18%</strong></td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>' . "XXXXXXX" .  '</strong></td>
                </tr>
                <tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong>Total TTC</strong></td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>' . "YYYYYYYYYY" . '</strong></td>
                </tr>';
//$block_remise = $pdf->info_facture['valeur_remise'] == 0 ? null : $block_remise; 
//$block_tt_no_remise = $pdf->info_facture['valeur_remise'] == 0 ? null : $block_tt_no_remise;  
//$block_ttc = $pdf->info_facture['total_tva'] == 0 ? null : $block_ttc;
//$titl_ht = $pdf->info_facture['total_tva'] == 0 ? 'Total à payer' : 'Total HT';
//$signature = $pdf->info_proforma['comercial']; 

$signature = 'La Direction';

$block_sum = '<div></div>
<style>
p {
    line-height: 0.6;
    .row0
				{
					background-color: #eaebed;
					border:1pt solid black;
				}
				.row1{
					border:1px solid black;
				}
				.alignRight { text-align: right; }
				.center{ text-align: center; }
}


</style>
<table style="width: 685px;" cellpadding="2">
    <tr>
        <td width="50%" align="left">
            
        </td>
        <td width="50%">
           <table class="table" cellspacing="2" cellpadding="2"  style="width: 300px; border:1pt solid black;" >
            <tbody>
                XXXXXXXX
                <tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong> XXXXXXXXX </strong></td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong> XXXXXXXXXX  XXXXXXXXXXXX</strong></td>
                </tr>

               XXXXXXXXXX
                <tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong>Total payé </strong></td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>XXXXXXXXXX  XXXXXXXXXX </strong></td>
                </tr>
                 <tr>
                    <td style="width:35%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;"><strong> Reste à payer </strong></td>
                    <td style="width:5%;color: #E99222;font-family: sans-serif;font-weight: bold;font-size: 9pt;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>XXXXXXXXXX XXXXXXXXXX </strong></td>
                </tr>
            </tbody>
        </table> 
    </td>
</tr>
<tr>
    <td colspan="2" style="color: #E99222;font-family: sans-serif;font-weight: bold;">
        Arrêté la présente Facture à la somme de :
    </td>
</tr>
<tr>
    <td colspan="2" style="color:#6B6868; width: 650px; border:1pt solid black; background-color: #eeecec; padding: 5px;">
        <strong>XXXXXXXXXX</strong>    
    </td>
</tr>
<tr>
    <td colspan="2" style="color: #E99222;font-family: sans-serif;font-weight: bold;">       
        <strong>Conditions générales:</strong>        
    </td>
</tr>

<tr>
    <td colspan="2" style="color:#6B6868; width: 650px; border:1pt solid black; background-color: #eeecec; padding: 5px;">
        XXXXXXXXXX
    </td>
</tr>

<tr>
    <td colspan="2" align="right" style="font: underline; width: 550px;  padding-right: 200px;">
        <br><br><br><br><br>
        <strong>' . $signature . '</strong>
    </td>
</tr>';
//$pdf->lastPage(); 
	
$block_sum .= '
<tr>
<td colspan="2" align="right" style="font: underline; width: 620px;  padding-right: 200px;">
        <br>
        <span class="profile-picture">
			<img width="150" height="150" class="editable img-responsive" alt="logo_global.png" id="avatar2" src="./upload/signature/signature_ali.jpg" />
		</span>	

    </td>
</tr>
</table>';




$pdf->writeHTML($html, true, false, true, false, '');
/* $y = $pdf->GetY();
  //No space for Sum Blok then AddPage
  if($y > 190)
  {
  $pdf->no_tabl_head = false;
  $pdf->AddPage();
  $pdf->writeHTML($block_sum, true, false, true, false, '');

  }else{
  $pdf->writeHTML($block_sum, true, false, true, false, '');
  } */
$pdf->writeHTMLTogether($block_sum, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '');
/* $block_sum1 = 'Y: '.$y;
  $pdf->writeHTML($block_sum1, true, false, true, false, '');
  $pdf->writeHTML($block_sum, true, false, true, false, '');
  $y = $pdf->GetY();


  $block_sum1 = 'Y: '.$y; */

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export, 'F');


//============================================================+
// END OF FILE
//============================================================+

