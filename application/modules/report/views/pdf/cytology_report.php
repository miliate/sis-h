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
$result = $this->db->query($query);

$count = $result->num_rows();

$text = '<style>
    td {border-bottom: 2px solid #8ebf42;}
</style>';

$hospital_name = config_item('hospital_name');

$text = '<table width="100%" align="center">
      <tr class="line">
        <td align="center" colspan="1"><img src="images/hcq.png" alt="" width="120"></td>
        <td colspan="3">
            <strong style="font-size: 18px;">' . $hospital_name . '</strong><br>
            <strong style="font-size: 18px;">SERVICO DE ANATOMIA PATOLOGICA</strong><br>
            <strong style="font-size: 18px;">CITOLOGIA (Exclui Citologia Cervico-Varginal)</strong><br>
            <strong style="font-size: 10px;">(Av. Da FPLM, Bairro de Kamavota, cell: 258-21 463055)</strong>
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
        <td>Colhido por: '. $collected_by .'</td>
        <td>Data: '. $PA_order->CollectionDateTime .'</td>
      </tr>
    </table>
    <br><hr><div></div>
    <table width="100%" align="left">
      <tr>
        <td colspan="4" style="font-size: 16px; font-weight: bold">CITOLOGIA DE LÍQUIDOS</td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="1"><input type="checkbox" name="Ascitic_Liquid" value="Ascitic_Liquid" 
            checked="'. (in_array("Ascitic_Liquid", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="Ascitic_Liquid">Líquido Ascítico</label></td>
        <td colspan="1"><input type="checkbox" name="Pleural_Fluid" value="Ascitic_Liquid" 
            checked="'. (in_array("Pleural_Fluid", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="Pleural_Fluid">Líquido Pleural</label></td>
        <td colspan="2"><input type="checkbox" name="Washes" value="Washes" 
            checked="'. (in_array("Washes", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="Washes">Lavagens:  '. $cytology_order->Washes_Info .'</label>
        </td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="1"><input type="checkbox" name="Pericardial_Fluid" value="Pericardial_Fluid" 
            checked="'. (in_array("Pericardial_Fluid", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="Pericardial_Fluid">Líquido Pericárdico</label></td>
        <td colspan="1"><input type="checkbox" name="Urine" value="Urine" 
            checked="'. (in_array("Urine", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="Urine">Urina</label></td>
        <td colspan="1"><input type="checkbox" name="Expectoration" value="Expectoration" 
            checked="'. (in_array("Expectoration", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="Expectoration">Expectoração</label></td>
        <td colspan="1"><input type="checkbox" name="LCR" value="LCR" 
            checked="'. (in_array("LCR", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="LCR">LCR</label></td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="4"><input type="checkbox" name="Others_Liquid" value="Others_Liquid" 
            checked="'. (in_array("Others_Liquid", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="LCR">Outros (especificar):  '. $cytology_order->Others_Liquid_Info .'</label></td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="4"><label>Diagnóstico Clínico:  '. $cytology_order->Clinical_Diagnosis_Liquid .'</label></td>
      </tr>
    </table>
    <br><hr><div></div>
    <table width="100%" align="left">
      <tr>
        <td colspan="4" style="font-size: 16px; font-weight: bold">FUNÇÃO ASPIRATIVA POR AGULHA FINA (PAAF)</td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="1"><input type="checkbox" name="Breast" value="Breast" 
            checked="'. (in_array("Breast", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Breast">Mama (nódulo)</label></td>
        <td colspan="1"><input type="checkbox" name="Nipple_Discharge" value="Nipple_Discharge" 
            checked="'. (in_array("Nipple_Discharge", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Nipple_Discharge">Corrimento Mamilar</label></td>
        <td colspan="1"><input type="checkbox" name="Thyroid" value="Thyroid" 
            checked="'. (in_array("Thyroid", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Thyroid">Tireoide</label></td>
        <td colspan="1"><input type="checkbox" name="Salivary_Gland" value="Salivary_Gland" 
            checked="'. (in_array("Salivary_Gland", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Salivary_Gland">Glândula Salivar</label></td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="4"><input type="checkbox" name="Ganglion" value="Ganglion" 
            checked="'. (in_array("Ganglion", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Ganglion">Gânglio (especificar localização):  '. $cytology_order->Ganglion_Info .'</label>
        </td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="4"><input type="checkbox" name="Soft_Tissues" value="Soft_Tissues" 
            checked="'. (in_array("Soft_Tissues", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Soft_Tissues">Tecidos Moles (especificar localização):  '. $cytology_order->Soft_Tissues_Info .'</label>
        </td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="4"><input type="checkbox" name="Others_PAAF" value="Others_PAAF" 
            checked="'. (in_array("Others_PAAF", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Others_PAAF">Outros (especificar):  '. $cytology_order->Others_PAAF_Info .'</label>
        </td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="4"><label>Informação / Diagnóstico Clínico:  '. $cytology_order->Clinical_Diagnosis_Liquid .'</label></td>
      </tr>
    </table>
    <br><hr><div></div>
    <table width="100%" align="left">
      <tr>
        <td colspan="4" style="font-size: 14px; font-weight: bold">TEM ANALISES ANTERIORES?  '. ($cytology_order->Previous_PA ? "Yes" : "No") .'</td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="2">Indicar no da análise: '. $count .'  </td>
        <td colspan="2">Resultado:  '. $cytology_order->Result .'</td>
      </tr>
      <tr><td colspan="4"></td></tr>
      <tr>
        <td colspan="3"></td><td colspan="1" style="font-size: 14px; font-weight: bold">O MÉDICO, </td>
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
?>