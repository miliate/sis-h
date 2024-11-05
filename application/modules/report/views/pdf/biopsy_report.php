<?php
// Created by Trung Hoang

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/tcpdf_barcodes_1d.php');
//define('K_TCPDF_CALLS_IN_HTML', true);

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
$pdf->AddPage();

//$pdf->Write(0, 'Example of text layout using Multicell()', '', 0, 'L', true, 0, false, false, 0);

//$pdf->Ln(5);

$pdf->SetFont('times', '', 11.5);

// set color for background
$pdf->SetFillColor(255, 255, 200);
// set the barcode content and type
$barcodeobj = new TCPDFBarcode('123456', 'C128');

// output the barcode as HTML object
$barcode= $barcodeobj->getBarcodeHTML(2, 30, 'black');

$hospital_name = config_item('hospital_name');

$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);

$barcode_pdf = $pdf->serializeTCPDFtagParameters(array($PA_order->PA_order_ID, 'C128', '', '', 35, 18, 0.4, $style, 'N'));
$barcode1 = '<tcpdf method="write1DBarcode" params="'.$barcode_pdf.'" />';

$query="SELECT *
        FROM pathological_anatomy_order
        WHERE PID = ".$patient->PID;
$count=$this->db->query($query);

$text = '<style>
    td {border-bottom: 2px solid #8ebf42;}
</style>';

$text = '<table width="100%" align="center">
      <tr class="line">
        <td align="center" colspan="1"><img src="images/hcq.png" alt="" width="120"></td>
        <td colspan="3">
            <strong style="font-size: 18px;">'. $hospital_name . '</strong><br>
            <strong style="font-size: 18px;">SERVICO DE ANATOMIA PATOLOGICA</strong><br>
            <strong style="font-size: 18px;">BIOPSIAS E PEÇAS CIRÚRGICAS</strong><br>
            <strong style="font-size: 10px;">(Av. Da FPLM, Bairro Kamavota, cell: 258-21 463055)</strong>
        </td>
        <td align="center" colspan="1">'. $barcode1 .'</td>
      </tr>
    </table>
    <br><hr>
    <div></div>
    <table width="100%" align="left">
      <tr>
        <td colspan="2">Num. da Analise: '. $PA_order->PA_order_ID .'</td>
        <td colspan="2">NID: '. $patient->BI_ID .'</td>
        <td colspan="3">Data da requisicao: '. $PA_order->SampleRequestDate .'</td>
      </tr>
      <tr>
        <td colspan="4">Nome: '. $patient->Personal_Title.' '.$patient->Firstname. ' '. $patient->Name .'</td>
        <td colspan="3">Apelido: </td>
      </tr>
      <tr>
        <td colspan="2">Sexo: '. $patient->Gender .'</td>
        <td colspan="2">Raca: </td>
        <td colspan="3">Idade: '. $patient->DateOfBirth .'</td>
      </tr>
      <tr>
        <td colspan="2">Profissao: '. $patient->Profession .'</td>
        <td>Servico: '. $active_list->Service .'</td>
      </tr>
    </table>
    <br><hr><div></div>
    <table width="100%" align="left">
      <tr>
        <td colspan="2">Natureza do produto: '. $biopsy_order->Kind_of_Product .'</td>
      </tr>
      <tr>
        <td colspan="2">Sede da lesao: '. $biopsy_order->Wound_Centre .'</td>
      </tr>
      <tr>
        <td>Colhido por: '. $collected_by .'</td>
        <td>Data: '. $PA_order->CollectionDateTime .'</td>
      </tr>
    </table>
    <br><hr><div></div>
    <table width="100%" align="left">
      <tr>
        <td>Diagnostico Clinico ou Sintomas Principals: '. $PA_visit->Complaint .'</td>
      </tr>
      <tr>
        <td>Numeros das Analises Anteriores: '. $count->num_rows() .'</td>
      </tr>
      <tr>
        <td>Observacoes </td>
      </tr>
    </table>
    <br><hr><div></div>
    <table width="100%" align="left">
      <tr>
        <td colspan="2" style="height:150px;">Exame  Macroscopico: '. $biopsy_order->Macroscopic .'</td>
      </tr>
      <tr>
        <td colspan="2" style="height:120px;">Exame  Microscopico: '. $biopsy_order->Microscopic .'</td>
      </tr>
      <tr>
        <td colspan="2" style="height:50px;">Diagnostico Anatomo-Patologico: <b>'. $biopsy_order->PA_Diagnosis .'</b></td>
      </tr>
      <tr>
        <td colspan="2" style="height:50px;">Notas: '. $biopsy_order->Remarks .'</td>
      </tr>
      <tr>
        <td>Topografia: '. $biopsy_order->Topography .'</td>
        <td>Morifologia: '. $biopsy_order->Morphology .'</td>
      </tr>
    </table>
    <br><hr><div></div>
    <table width="100%" align="left">
      <tr>
        <td>Queliman</td>
        <td align="center">O Medico</td>
      </tr>
      <tr><td></td></tr>
      <tr><td></td></tr>
      <tr>
        <td>Digitador</td>
        <td><hr></td>
      </tr>
    </table>
    
';

$pdf->writeHTMLCell(0, 0, '', '', $text, 0, 1, 0, true, '', true);

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
