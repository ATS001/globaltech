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

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
     var $Table_head  = null;
     var $Table_body  = null;
     var $info_devis  = array();
     var $info_ste    = array();
     
	//Page header
	public function Header() {
		//writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true) {
		
		// Logo
		$image_file = MPATH_IMG.MCfg::get('logo');
		$this->writeHTMLCell(50, 25, '', '', '' , 1, 0, 0, true, 'C', true);
		$this->Image($image_file, 22, 6, 30, 23, 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);
		
		$ste = '<div class="form-group>"><address><strong>Data Connect Tchad SARL</strong><br>795 Folsom Ave, Suite 600<br>San Francisco, CA 94107<br><abbr title="Phone">Tél:</abbr>(123) 456-7890<br>Email: contact@dctchad.com</address></div>';
		$this->writeHTMLCell(0, 0, '', 30, $ste , '', 0, 0, true, 'L', true);
		$this->SetTextColor(0, 50, 127);
		// Set font
		$this->SetFont('helvetica', 'B', 22);
		//Ste
		
		// Title
		$titre_doc = '<h1 style="letter-spacing: 10px;">DEVIS</h1>';
		$this->writeHTMLCell(0, 0, 140, 10, $titre_doc , 'B', 0, 0, true, 'C', true);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('helvetica', '', 9);
		$detail_devis = '<table cellspacing="3" cellpadding="2" border="0">
		<tr>
		<td style="width:35%;"><strong>Réf Devis</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec;">'.$this->info_devis['reference'].'</td>
		</tr> 
		<tr>
		<td style="width:35%;"><strong>Date</strong></td>
		<td style="width:5%;">:</td>
		<td style="width:60%; background-color: #eeecec; ">'.$this->info_devis['date_devis'].'</td>
		</tr>
		</table>';
		$this->writeHTMLCell(0, 0, 140, 23, $detail_devis, '', 0, 0, true, 'L', true);
	    //Info Client
		$detail_client = '<table cellspacing="3" cellpadding="2" border="0">
		<tbody>
		<tr style="background-color:#4245f4; font-size:14; font-weight:bold; color:#fff;">
		<td colspan="3"><strong>Info. client</strong></td>
		</tr>
		<tr>
		<td style="width: 30%;">Dénomination</td>
		<td style="width: 5%;">:</td>
		<td style="width: 65%; background-color: #eeecec;"><strong>'.$this->info_devis['denomination'].'</strong></td>
		</tr>
		<tr>
		<td style="width: 30%;">Adresse</td>
		<td style="width: 5%;">:</td>
		<td style="width: 65%; background-color: #eeecec;">'.$this->info_devis['adresse'].' BP'.$this->info_devis['bp'].' '.$this->info_devis['ville'].' '.$this->info_devis['pays'].'</td>
		</tr>
		<tr>
		<td style="width: 30%;">Contact</td>
		<td style="width: 5%;">:</td>
		<td style="width: 65%; background-color: #eeecec;">Tél.'.$this->info_devis['tel'].' Email.'.$this->info_devis['email'].'</td>
		</tr>
		<tr>
		<td style="width: 30%;">NIF</td>
		<td style="width: 5%;">:</td>
		<td style="width: 65%; background-color: #eeecec;">'.$this->info_devis['nif'].'</td>
		</tr>
		</tbody>
		</table>';
		$this->writeHTMLCell(100, 0, 99, 40, $detail_client, 1, 0, 0, true, 'L', true);
		//Info général
		$tableau_head = $this->Table_head;
		$this->writeHTMLCell('', '', 15, 83, $tableau_head, 0, 0, 0, true, 'L', true);
		//$pdf->writeHTMLCell('', '','' , '', $html , 0, 0, 0, true, 'L', true);

	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}

	
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->Table_head = $tableau_head;
$pdf->info_devis = $devis_info;
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
            Arrêté le présent Devis à la somme de :<br>
            <strong>'.$ttc_lettre.' </strong>
        </td>
        <td width="50%">
           <table class="table" cellspacing="2" cellpadding="2"  style="width: 300px; border:1pt solid black;" >
            <tbody>
                <tr>
                    <td style="width:35%;"><strong>Total HT</strong></td>
                    <td style="width:5%;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>'.$pdf->info_devis['totalht'].'</strong></td>
                </tr>
                <tr>
                    <td style="width:35%;"><strong>Remise:</strong></td>
                    <td style="width:5%;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>'.$remise_valeur.'</strong></td>
                </tr>
                <tr>
                    <td style="width:35%;"><strong>TVA 18%</strong></td>
                    <td style="width:5%;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>'.$pdf->info_devis['totaltva'].'</strong></td>
                </tr>
                <tr>
                    <td style="width:35%;"><strong>Total TTC</strong></td>
                    <td style="width:5%;">:</td>
                    <td class="alignRight" style="width:60%; background-color: #eeecec;"><strong>'.$pdf->info_devis['totalttc'].'</strong></td>
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
        '.$this->info_devis['claus_comercial'].'
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
$html .= $block_sum;
$pdf->writeHTML($html, true, false, true, false, '');
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output($file_export,'F');

//============================================================+
// END OF FILE
//============================================================+

