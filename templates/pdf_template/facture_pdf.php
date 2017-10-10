<?php

//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+
//Get all info Devis from model
$facture = new Mfacture();
$facture->id_facture = Mreq::tp('id');

if (!MInit::crypt_tp('id', null, 'D') or ! $facture->get_facture()) {
    // returne message error red to devis 
    exit('0#<br>Les informations pour cette template sont erronées, contactez l\'administrateur');
}



//Execute Pdf render

if (!$facture->Get_detail_facture_pdf()) {
    exit("0#" . $facture->log);
}
global $db;
$headers = array(
    'Item' => '5[#]center',
    'Réf' => '10[#]center',
    'Description' => '45[#]',
    'Qte' => '5[#]center',
    'P.U' => '10[#]alignRight',
    'Re' => '5[#]center',
    'Total TTC' => '15[#]alignRight',
);

$headers2 = array(
    'ID' => '5[#]center',
    'Désignation' => '30[#]center',
    'Type' => '15[#]',
    'Montant' => '10[#]center',
   );

$devis_info = $facture->devis_info;
$tableau_head = MySQL::make_table_head($headers);
$tableau_body = $db->GetMTable_pdf($headers);
//var_dump($tableau_body);

$facture->get_complement_by_facture();
//$facture->get_complement_by_facture();
$complement_info=$facture->complement_info;
$tableau_head2 = MySQL::make_table_head($headers2);
$tableau_body2 = $db->GetMTable_pdf($headers2);

//var_dump($db);

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    var $Table_head = null;
    var $Table_body = null;
    var $Table_head2 = null;
    var $Table_body2 = null;
    var $info_devis = array();
    var $info_ste = array();
    var $info_facture = array();
    var $info_complement = array();

    //Page header
    public function Header() {
        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
        // Logo
        $image_file = MPATH_IMG . MCfg::get('logo');
        $this->writeHTMLCell(50, 25, '', '', '', 1, 0, 0, true, 'C', true);
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
        $titre_doc = '<h3>FACTURE</h3>';
        $this->writeHTMLCell(0, 0, 140, 10, $titre_doc, 'B', 0, 0, true, 'C', true);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 9);
        $detail_devis = '<table cellspacing="3" cellpadding="2" border="0">
		<tr>
		<td style="width:35%;"><strong>Référence</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec;">' . $this->info_facture['ref'] . '</td>
		</tr> 
		<tr>
		<td style="width:35%;"><strong>Date</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec; ">' . $this->info_facture['date_facture'] . '</td>
		</tr>
                <tr>
		<td style="width:35%;"><strong>Réf devis</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec; ">' . $this->info_devis['reference'] . '</td>
		</tr>
                <tr>
		<td style="width:35%;"><strong>Date</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec; ">' . $this->info_devis['date_devis'] . '</td>
		</tr>
		</table>';
        $this->writeHTMLCell(0, 0, 140, 23, $detail_devis, '', 0, 0, true, 'L', true);
        $height = $this->getLastH();
       
        $this->SetTopMargin($height + $this->GetY());
        //Info Client
        $detail_client = '<table cellspacing="3" cellpadding="2" border="0">
		<tbody>
		<tr style="background-color:#4245f4; font-size:14; font-weight:bold; color:#fff;">
		<td colspan="3"><strong>Info. client</strong></td>
		</tr>
		<tr>
		<td style="width: 30%;">Dénomination</td>
		<td style="width: 5%;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>' . $this->info_devis['denomination'] . '</strong></td>
		</tr>
		<tr>
		<td style="width: 30%;">Adresse</td>
		<td style="width: 5%;">:</td>
		<td style="width: 65%; background-color: #eeecec;">' . $this->info_devis['adresse'] . ' BP' . $this->info_devis['bp'] . ' ' . $this->info_devis['ville'] . ' ' . $this->info_devis['pays'] . '</td>
		</tr>
		<tr>
		<td style="width: 30%;">Contact</td>
		<td style="width: 5%;">:</td>
		<td style="width: 65%; background-color: #eeecec;">Tél.' . $this->info_devis['tel'] . ' Email.' . $this->info_devis['email'] . '</td>
		</tr>
		<tr>
		<td style="width: 30%;">NIF</td>
		<td style="width: 5%;">:</td>
		<td style="width: 65%; background-color: #eeecec;">' . $this->info_devis['nif'] . '</td>
		</tr>
		</tbody>
		</table>';

        $this->writeHTMLCell(100, 0, 98, '', $detail_client, 1, 0, 0, true, 'L', true);
        $height = $this->getLastH();
       
        $this->SetTopMargin($height + $this->GetY()+4);
               
        //Info général
        $tableau_head = $this->Table_head;
        $this->writeHTMLCell('', '', 15,'', $tableau_head, 0, 0, 0, true, 'L', true);
        $height = $this->getLastH();
       
        $this->SetTopMargin($height + $this->GetY());
        
        
        //$pdf->writeHTMLCell('', '','' , '', $html , 0, 0, 0, true, 'L', true);
    }

    // Page footer
    public function Footer() {
        $ste_c = new MSte_info();
        $this->SetY(-30);
        $ste = $ste_c->get_ste_info_report_footer(1);
        $this->writeHTMLCell(0, 0, '', '', $ste, '', 0, 0, true, 'C', true);
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->Table_head = $tableau_head;
$pdf->Table_head2=$tableau_head2;
$pdf->info_devis = $devis_info;
$pdf->info_facture = $facture->facture_info;
$pdf->info_complement=$facture->complement_info;


// set document information
$pdf->SetCreator(MCfg::get('sys_titre'));
$pdf->SetAuthor(session::get('username'));
$pdf->SetTitle('devis');
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
$pdf->SetAutoPageBreak(TRUE, 60);


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

// Print text using writeHTMLCell()
$pdf->Table_body = $tableau_body;
$html = $pdf->Table_body;
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Table_body2 = $tableau_body2;
$html2 = $pdf->Table_body2;
$height = $pdf->getLastH();
       
$pdf->SetTopMargin($height + $pdf->GetY());

if($pdf->info_complement != null)
{
$pdf->writeHTML('<strong>Tableau des compléments</strong>', true, false, true, false, '');
$pdf->writeHTMLCell('', '', 15,'', $tableau_head2, 0, 0, 0, true, 'L', true);
$height = $pdf->getLastH();
       
$pdf->SetY($height + $pdf->GetY());
$pdf->SetX(16);
$pdf->writeHTML($html2, true, false, true, false, '');
}
// ---------------------------------------------------------
//$pdf->writeHTMLCell('', '','' , '', $html , 0, 0, 0, true, 'L', true);

$pdf->lastPage();
$obj = new nuts($pdf->info_devis['totalttc'], "FCFA");
$ttc_lettre = $obj->convert("fr-FR");
$remise_valeur = $pdf->info_devis['valeur_remise'] == null ? '-' : $pdf->info_devis['valeur_remise'];
$block_sum = '<div></div>
<table style="width: 685px;" cellpadding="2">
    <tr align="right">
        <td width="50%" align="left" style="background-color: #eeecec;">
            Arrêté la présente Facture à la somme de :<br>
            <strong>' . $ttc_lettre . ' </strong>
        </td>
        <td width="50%">
           <table class="table" cellspacing="2" cellpadding="2"  style="width: 300px; border:1pt solid black;" >
            <tbody>
            <tr>
                    <td style="width:35%;"><strong>Total TTC Initial</strong></td>
                    <td style="width:5%;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>' . $pdf->info_facture['total_ttc_initial'] . '</strong></td>
                </tr>
                <tr>
                    <td style="width:35%;"><strong>Total HT</strong></td>
                    <td style="width:5%;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>' . $pdf->info_facture['total_ht'] . '</strong></td>
                </tr>
                                
                <tr>
                    <td style="width:35%;"><strong>Total Tva</strong></td>
                    <td style="width:5%;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>' . $pdf->info_facture['total_tva'] . '</strong></td>
                </tr>
                <tr>
                    <td style="width:35%;"><strong>Total TTC</strong></td>
                    <td style="width:5%;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>' . $pdf->info_facture['total_ttc'] . '</strong></td>
                </tr>
                <tr>
                    <td style="width:35%;"><strong>Total payé</strong></td>
                    <td style="width:5%;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>' . $pdf->info_facture['total_paye'] . '</strong></td>
                </tr>
                <tr>
                    <td style="width:35%;"><strong>Reste à payer</strong></td>
                    <td style="width:5%;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>' . $pdf->info_facture['reste'] . '</strong></td>
                </tr>
                
            </tbody>
        </table> 
    </td>
</tr>
<tr>
    <td colspan="2">
        
        <strong>Conditions générales:</strong>
        
    </td>
</tr>
<tr>
    <td colspan="2" style="width: 650px; border:1pt solid black; background-color: #eeecec; padding: 5px;">
        ' . $pdf->info_devis['claus_comercial'] . '
     <br>
     Merci de nous avoir consulter.
 </td>
</tr>

<tr>
    <td colspan="2" align="right" style="font: underline; padding-right: 200px;">
        <br><br><br><br>
        <strong>Responsable Commercial</strong>
    </td>
</tr>
</table>';
//$html .= $block_sum;
$pdf->writeHTML($block_sum, true, false, true, false, '');
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export, 'F');

//============================================================+
// END OF FILE
//============================================================+

