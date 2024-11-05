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

// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Example of text layout using Multicell()', '', 0, 'L', true, 0, false, false, 0);

//$pdf->Ln(5);

$pdf->SetFont('times', '', 12);

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
            <strong style="font-size: 18px;">'. $hospital_name . ' </strong><br>
            <strong style="font-size: 18px;">SERVICO DE ANATOMIA PATOLOGICA</strong><br>
            <strong style="font-size: 18px;">CITOLOGIA CERVICO-VARGINAL</strong><br>
            <strong style="font-size: 10px;">(Av. Da FPLM, Bairro de Kamavota, cell: 258-21 463055)</strong>
        </td>
        <td align="center" colspan="1">'. $barcode1 .'</td>
      </tr>
    </table>
    <br><hr><div></div>
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
        <td colspan="5" style="font-size: 14px; font-weight: bold">Descrição da Análise:</td>
      </tr>
      <tr>
        <td colspan="2"><input type="radio" name="First_Analysis" value="First_Analysis" 
            checked="'. ($cv_cytology_order->Analysis_Description == 0 ? "checked" : "") .'" readonly="true"/>
            <label for="First_Analysis" style="font-weight: bold">Primeira análise</label></td>
        <td colspan="1"><input type="radio" name="Repetition" value="Repetition" 
            checked="'. ($cv_cytology_order->Analysis_Description == 1 ? "checked" : "") .'" readonly="true"/>
            <label for="Repetition" style="font-weight: bold">Repetição</label></td>
        <td colspan="2"><input type="radio" name="Investigation" value="Investigation" 
            checked="'. ($cv_cytology_order->Analysis_Description == 2 ? "checked" : "") .'" readonly="true"/>
            <label for="Investigation" style="font-weight: bold">Investigação</label>
        </td>
      </tr>
      <tr><td colspan="5"></td></tr>
      <tr>
        <td colspan="1">Esfregado de:</td>
        <td colspan="1"><input type="radio" name="Pericardial_Fluid" value="Pericardial_Fluid" 
            checked="'. ($cv_cytology_order->Scrubbed_From == 0 ? "checked" : "") .'" readonly="true"/>
            <label for="Pericardial_Fluid">Exocervix</label></td>
        <td colspan="1"><input type="radio" name="Vagina" value="Vagina" 
            checked="'. ($cv_cytology_order->Scrubbed_From == 1 ? "checked" : "") .'" readonly="true"/>
            <label for="Vagina">Vagina</label></td>
        <td colspan="2"><input type="radio" name="Endocervix" value="Endocervix" 
            checked="'. ($cv_cytology_order->Scrubbed_From == 2 ? "checked" : "") .'" readonly="true"/>
            <label for="Endocervix">Endocervix</label></td>
      </tr>
      <tr>
        <td colspan="1">Amostra colhida por:</td>
        <td colspan="1"><input type="radio" name="Ayres" value="Ayres" 
            checked="'. ($cv_cytology_order->Sample_Taken_By == 0 ? "checked" : "") .'" readonly="true"/>
            <label for="Ayres">Espátula Ayres</label></td>
        <td colspan="1"><input type="radio" name="Cervix" value="Cervix" 
            checked="'. ($cv_cytology_order->Sample_Taken_By == 1 ? "checked" : "") .'" readonly="true"/>
            <label for="Cervix">Cervix Brush</label></td>
        <td colspan="2"><input type="radio" name="Outro" value="Outro" 
            checked="'. ($cv_cytology_order->Sample_Taken_By == 2 ? "checked" : "") .'" readonly="true"/>
            <label for="Outro">Outro: '. $cv_cytology_order->Sample_Taken_By_Info .'</label></td>
      </tr>
      <tr>
        <td colspan="2">Investigação requerida:</td>
        <td colspan="1"><input type="radio" name="Rotina" value="Rotina" 
            checked="'. ($cv_cytology_order->Research_Required == 0 ? "checked" : "") .'" readonly="true"/>
            <label for="Rotina">Rotina</label></td>
        <td colspan="2"><input type="radio" name="Hormonal" value="Hormonal" 
            checked="'. ($cv_cytology_order->Research_Required == 1 ? "checked" : "") .'" readonly="true"/>
            <label for="Hormonal">Hormonal</label></td>
      </tr>
    </table>
    <br><hr><div></div>
    <table width="100%" align="left">
      <tr>
        <td colspan="6" style="font-size: 14px; font-weight: bold">Informação Clínica</td>
      </tr>
      <tr><td colspan="6"></td></tr>
      <tr>
        <td colspan="6">Gesta: '. $cv_cytology_order->Pregnancy .', Paridade: '. $cv_cytology_order->Parity .' . Está Grávida Actualmente? '. ($cv_cytology_order->Pregnant ? "Yes" : "No") .'</td>
      </tr>
      <tr><td colspan="6"></td></tr>
      <tr>
        <td colspan="3">Menopausa (Se Sim há, quantos Anos): '. ($cv_cytology_order->Menopause_Phase ? "Yes" : "No") .', '. $cv_cytology_order->Menopause_Phase_Info .'</td>
        <td colspan="3">Data do U.P.M: '. $cv_cytology_order->Menstrual_Period .'</td>        
      </tr>
      <tr>
        <td colspan="6">Fumadora: '. ($cv_cytology_order->Smoker ? "Yes" : "No") .'</td>
      </tr>
      <tr><td colspan="6"></td></tr>
      <tr>
        <td colspan="2">Aspecto do Cervix: <input type="checkbox" name="Normal" value="Normal" 
            checked="'. (in_array("Normal", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="Normal">Normal</label></td>
        <td colspan="1"><input type="checkbox" name="Ectopia" value="Ectopia" 
            checked="'. (in_array("Ectopy", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="Ectopia">Ectopia</label></td>
        <td colspan="2"><input type="checkbox" name="Malignidade" value="Malignidade" 
            checked="'. (in_array("Suspected_Malignity", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="Malignidade">Suspeito de Malignidade</label></td>
        <td colspan="1"><input type="checkbox" name="Polyp" value="Polyp" 
            checked="'. (in_array("Polyp", $checked1) ? "checked" : "") .'" readonly="true"/>
            <label for="Polyp">Pólipo</label></td>
      </tr>
      <tr>
        <td colspan="2">Infeções Por: <input type="checkbox" name="Herpes" value="Herpes" 
            checked="'. (in_array("Herpes", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Herpes">Herpes</label>
            <input type="checkbox" name="Candida" value="Candida" 
            checked="'. (in_array("Candida", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Candida" style="font-size: 14px;">Cândida</label>
        </td>
        <td colspan="1"><input type="checkbox" name="Tricomonas" value="Tricomonas" 
            checked="'. (in_array("Trichomonas", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Tricomonas">Tricomonas</label></td>
        <td colspan="2"><input type="checkbox" name="Vaginite" value="Vaginite" 
            checked="'. (in_array("Bacterial_Vaginitis", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Vaginite">Vaginite bacteriana</label>
            <input type="checkbox" name="HPV" value="HPV" 
            checked="'. (in_array("HPV", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="HPV">HPV</label>
        </td>
        <td colspan="1"><input type="checkbox" name="Clamidea" value="Clamidea" 
            checked="'. (in_array("Chlamydia", $checked2) ? "checked" : "") .'" readonly="true"/>
            <label for="Clamidea">Clamídea</label></td>
      </tr>
      <tr>
        <td colspan="2">Contracepção: <input type="checkbox" name="Hormonal" value="Hormonal" 
            checked="'. (in_array("Hormonal", $checked3) ? "checked" : "") .'" readonly="true"/>
            <label for="Hormonal">Hormonal</label>
        </td>
        <td colspan="1"><input type="checkbox" name="DIU" value="DIU" 
            checked="'. (in_array("DIU", $checked3) ? "checked" : "") .'" readonly="true"/>
            <label for="DIU">DIU</label>
        </td>
        <td colspan="1"><input type="checkbox" name="Esterilizacao" value="Esterilizacao" 
            checked="'. (in_array("Sterilization", $checked3) ? "checked" : "") .'" readonly="true"/>
            <label for="Esterilizacao">Esterilização</label>
        </td>
        <td colspan="2"><input type="checkbox" name="Outro" value="Outro" 
            checked="'. (in_array("Contraception_Other", $checked3) ? "checked" : "") .'" readonly="true"/>
            <label for="Outro">Outro: '. $cv_cytology_order->Contraception_Other_Info .'</label>
        </td>
      </tr>
      <tr><td colspan="6"></td></tr>
      <tr><td colspan="6">Terapêutica de Substituição Hormonal: '. ($cv_cytology_order->Hormone_Replacement_Therapy ? "Yes" : "No") .'</td></tr>
      <tr>
      <tr>
        <td colspan="6"><label>Diagnóstico Clínico:  '. $cv_cytology_order->Clinical_Diagnosis .'</label></td>
      </tr>
      <tr><td colspan="6"></td></tr>
      <tr>
        <td colspan="6">Tem Análises Anteriores (Citopatologia Ou Hostopatologia)?  '. ($cv_cytology_order->Previous_PA ? "Yes" : "No") .'</td>
      </tr>
      <tr>
        <td colspan="3">Se sim: No da(s) análise(s)  '. $count .'</td>
        <td colspan="3">Resultado(s):  '. $cv_cytology_order->Result .'</td>
      </tr>
    </table>
    <br><hr><div></div>
    <table width="100%" align="left">
      <tr>
        <td colspan="2" style="font-size: 14px; font-weight: bold">Tratamento Anterior: </td>
        <td colspan="3"><input type="checkbox" name="Cirurgico" value="Cirurgico" 
            checked="'. (in_array("Surgical", $checked4) ? "checked" : "") .'" readonly="true"/>
            <label for="Cirurgico">Cirúrgico</label></td>
        <td colspan="2"><input type="checkbox" name="Laser" value="Laser" 
            checked="'. (in_array("Laser", $checked4) ? "checked" : "") .'" readonly="true"/>
            <label for="Laser">Laser</label></td>
        <td colspan="3"><input type="checkbox" name="Cauterizacao" value="Cauterizacao" 
            checked="'. (in_array("Cauterization", $checked4) ? "checked" : "") .'" readonly="true"/>
            <label for="Cauterizacao">Cauterização</label></td>
        <td colspan="3"><input type="checkbox" name="Radioterapia" value="Radioterapia" 
            checked="'. (in_array("Radiotherapy", $checked4) ? "checked" : "") .'" readonly="true"/>
            <label for="Radioterapia">Radioterapia</label></td>
        <td colspan="5"><input type="checkbox" name="Outro" value="Outro" 
            checked="'. (in_array("Tratamento_Anterior_Other", $checked4) ? "checked" : "") .'" readonly="true"/>
            <label for="Outro">Outro: '. $cv_cytology_order->Tratamento_Anterior_Other_Info .'</label></td>
      </tr>
    </table>
    <br><hr><div></div>
    <table width="100%" align="left">
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