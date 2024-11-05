<?php
// Created by Jordao Cololo

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/tcpdf_barcodes_1d.php');


// extend TCPF with custom functions
class MYPDF extends TCPDF {

	public function MultiRow($left, $right) {
		// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)

		$page_start = $this->getPage();
		$y_start = $this->GetY();

		// write the left cell
		$this->MultiCell(40, 0, $left, 1, 'R', 1, 2, '', '', true, 0);

		$page_end_1 = $this->getPage();
		$y_end_1 = $this->GetY();

		$this->setPage($page_start);

		// write the right cell
		$this->MultiCell(0, 0, $right, 1, 'J', 0, 1, $this->GetX() ,$y_start, true, 0);

		$page_end_2 = $this->getPage();
		$y_end_2 = $this->GetY();

		// set the new row position by case
		if (max($page_end_1,$page_end_2) == $page_start) {
			$ynew = max($y_end_1, $y_end_2);
		} elseif ($page_end_1 == $page_end_2) {
			$ynew = max($y_end_1, $y_end_2);
		} elseif ($page_end_1 > $page_end_2) {
			$ynew = $y_end_1;
		} else {
			$ynew = $y_end_2;
		}

		$this->setPage(max($page_end_1,$page_end_2));
		$this->SetXY($this->GetX(),$ynew);
	}

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf = new MYPDF(['orientation' => 'L']);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 22);
// add a page
$pdf->AddPage('L');

//$pdf->Write(0, 'Example of text layout using Multicell()', '', 0, 'L', true, 0, false, false, 0);

//$pdf->Ln(5);

$pdf->SetFont('courier', '', 9);

//$pdf->SetCellPadding(0);
//$pdf->SetLineWidth(2);

// set color for background

$pdf->SetFillColor(255, 255, 200);
// set the barcode content and type
$barcodeobj = new TCPDFBarcode('http://www.misau.gov.mz', 'C128');

// output the barcode as HTML object
$text1= $barcodeobj->getBarcodeHTML(2, 30, 'black');

$active_id=(int)$active_id;
$barcode_pdf = $pdf->serializeTCPDFtagParameters(array($active_id, 'C128', '', '', 35, 18, 0.4, $style, 'N'));
$barcode1 = '<tcpdf method="write1DBarcode" params="'.$barcode_pdf.'" />';



//$result=$this->db->query($query);
//$user_printer = $this->session->userdata('title') . ' ' . $this->session->userdata('name') . ' ' . $this->session->userdata('other_name');

$text = '<table autosize="1" border="1">
<tr>
  <td colspan="12" align="center" bgcolor="#999999"><div style="color:#000; font-weight:bold"> SERVIÇO NACIONAL DE SAÚDE</div></td>
</tr>
<tr>
  <td width="1%" rowspan="12" bgcolor="#000000">&nbsp; </td>
  <td width="12%" rowspan="3" align="center"><img src="images/moz.png" width="45px" height="45px"><br>REPÚBLICA DE MOÇAMBIQUE</td>
  <td colspan="2">Nº: </td>
  <td align="right">US:</td>
  <td colspan="5" align="right">&nbsp;</td>
  <td colspan="2">Data:'.$date.'</td>
</tr>
<tr>
  <td align="right">Nome do Doente:</td>
  <td colspan="4">&nbsp;</td>
  <td>Idade:</td>
  <td width="6%">NID: '.$pid.'</td>
  <td width="5%">&nbsp;</td>
  <td width="5%">&nbsp;</td>
  <td width="5%">&nbsp;</td>
</tr>
<tr>
  <td width="25%" align="right">Morada:</td>
  <td colspan="9">&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td colspan="3" align="center">Descrição</td>
  <td width="5%" rowspan="2" align="center">Possologia</td>
  <td width="5%" rowspan="2" align="center">Duração Tratamento</td>
  <td width="5%" rowspan="2" align="center">Quantidade receitada</td>
  <td width="6%" rowspan="2" align="center">Aviado</td>
  <td colspan="3" rowspan="5" align="center">Receita sujeita a taxa única</td>
</tr>
<tr>
  <td align="center">FNN</td>
  <td width="25%" align="center">Nome Genérico</td>
  <td width="8%" align="center">Dosagem</td>
  <td width="8%" align="center">Forma Farmacéutica</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="3" rowspan="3" align="center" valign="top"> Prescritor
    <br> Jordao Cololo <br><br>
  Assinatura</td>
  <td colspan="2" rowspan="3" align="center" valign="top">Farmácia <br> Jordao Cololo <br><br>
    Assinatura
    
  </td>
  <td colspan="3" align="right">Valor Total:</td>
  <td width="5%">&nbsp;</td>
  <td width="5%">&nbsp;</td>
  <td width="5%">&nbsp;</td>
</tr>
<tr>
  <td colspan="3" align="right">Valor Subsidiado:</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="3" align="right">Valor Cobrado:</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="12" align="center" valign="top">Trazer sempre esta receita médica à novas consultas</td>
</tr>
</table>    

<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>';

/*$pdf->SetHeader('Document Title');
$pdf->SetFooter('Document Title');*/
$pdf->Ln();
$pdf->SetAuthor('Jordao Cololo');
$pdf->SetTitle('receita_medica_'.$active_id.''.$hospital);
$pdf->SetFooterData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
//require_once(dirname(__FILE__).'/tcpdf_barcodes_1d_include.php');

// set the barcode content and type

// print some rows just as example
//for ($i = 0; $i < 2; ++$i) {
//$pdf->MultiCell(0, 0, $text."\n", 1, 'J', 1, 1, '', '', true, 0, false, true, 0);
$pdf->writeHTMLCell(0, 0, '', '', $text."\n", 0, 1, 0, true, '', true);

$pdf->writeHTMLCell(0, 0, '', '', $text."\n", 0, 1, 0, true, '', true);
//}

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('receitamedica'.$active_id.'_'.date("dmYH:m:s").'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
