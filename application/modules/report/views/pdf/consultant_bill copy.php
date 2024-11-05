<?php
// Created by Trung Hoang

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

$pdf->SetFont('courier', '', 9);

//$pdf->SetCellPadding(0);
//$pdf->SetLineWidth(2);

// set color for background
$pdf->SetFillColor(255, 255, 200);
// set the barcode content and type
$barcodeobj = new TCPDFBarcode('http://www.misau.gov.mz', 'C128');

// output the barcode as HTML object
$text1= $barcodeobj->getBarcodeHTML(2, 30, 'black');

//1. Number of admitted patients yesterday
//$query1="SELECT COUNT(*) as c
//FROM admission
//WHERE (IsDischarged = 0 OR (ADMID IN
//(SELECT RefID FROM discharge_order WHERE DischargeDate >= '".$date." 00:00:00' AND RefType = 'ADM')))
//AND AdmissionDate < '".$date." 00:00:00' AND Ward = ".$ward;
//$result1=$this->db->query($query1);
//$yesterday_admitted_patient=$result1->first_row();
//$statistic1 = $yesterday_admitted_patient->c;
//
//$this->load->model('mpersistent');
//$this->load->model('m_patient');
$active_id=(int)$active_id;
$barcode_pdf = $pdf->serializeTCPDFtagParameters(array($active_id, 'C128', '', '', 35, 18, 0.4, $style, 'N'));
$barcode1 = '<tcpdf method="write1DBarcode" params="'.$barcode_pdf.'" />';

$query="SELECT sap_procedures.Name as Consultation, DATE(sap_bill_item.CreateDate) as Date, 
        sap_bill_item.unit_price as Price, doctor.Name as Doctor
        FROM sap_bill_item , sap_bill, sap_procedures, doctor
        WHERE sap_bill_item.bill_id = sap_bill.id
          And sap_bill_item.doctor = doctor.Doctor_ID
          And sap_bill_item.item_id = sap_procedures.id
          And sap_bill.active_id = ". $active_id;

$result=$this->db->query($query);



$dt = new DateTime($entry_time);

$hospital_name = config_item('hospital_name');

$text = '<table width="100%" border="0" align="center">
      <tr align="center">
        <td align="left" colspan="2" valign="top">
          <p>&nbsp;
        <img src="images/hcq.png" alt="" width="155px"> <br>
          <strong style="font-size: 15px;">'.$this->getHid.'</strong>
          </p>
          </td>
        <td colspan="2"  valign="bottom">
        &nbsp;<br>
        <b>SERVI&Ccedil;O DE ATENDIMENTO PERSONALIZADO</b><br>
        RECINTO DO '. $hospital_name . '
        <h3>NUIT 500108797<br>
        Factura N<sup>o</sup>: '.$active_id.'</h3>
        
        </td>
      </tr>
      
      <tr>
        <td align="left" colspan="2">Av.FPLM, Bairro Kamavota</td>
        <td align="right" width="15%">
         Paciente: &nbsp;&nbsp;</td>
        <td align="left">'. $p_name .'</td>
      </tr>
      <tr>
        <td align="left" colspan="2">Mavalane, Kamavota</td>
        <td align="right">NID: &nbsp;&nbsp;</td>
        <td align="left">'.$p_id .'</td>
      </tr>
      <tr>
        <td align="left" colspan="2">Mozambique</td>
        <td align="right">NUIT: &nbsp;&nbsp;</td>
        <td align="left">'. $p_nuit.'</td>      </tr>
      <tr>
        <td align="left" colspan="2">Tel: </td>
        <td align="right">Endere&ccedil;o: &nbsp;&nbsp;</td>
        <td align="left">'. $p_address .'</td>
      </tr>
      <tr>
        <td align="left" colspan="2">Nuit: </td>
        <td align="right">E-mail: &nbsp;&nbsp;</td>
        <td align="left"></td>
      </tr>
      <tr>
        <td align="left" colspan="2">Email:</td>
        <td align="right">Tel: &nbsp;&nbsp;</td>
        <td align="left">'. $p_telephone .'</td>
      </tr>
      
      <tr>
        <td></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td align="left" colspan="2">Date of Consultation: '. $dt->format("Y-m-d") .'</td>
        <td align="left" colspan="2">Date of Invoice: '. date("Y-m-d") .'</td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <table border="0">
            <tr  bgcolor="#cccfff">
                <td width="4%">#</td>
                <td width="32%">Descrição</td>
                <td width="12%">Data</td>
                <td width="14%">Pre&ccedil;o (MZN)</td>
                <td width="24%">Atendido Por</td>
                <td width="14%">Valor Total (MZN)</td>
            </tr>';
$number = 1;
$total =0;
foreach ($result->result_array() as $row) {
    $text .= '<tr style="padding:10px">
                <td align="center">'.$number.'</td>
                <td align="left">'.$row["Consultation"].'</td>
                <td align="center">'.$row["Date"].'</td>
                <td align="right">'.number_format($row["Price"],2).'&nbsp; </td>
                <td align="center">'.$row["Doctor"].'</td>
                <td align="right">'.number_format($row["Price"],2).'&nbsp; </td>
              </tr>';
    $number++;
    $total +=$row["Price"];
}

$text .= '
          <tr>
            <td colspan="6" align="right" style="font-size: 15px"><p></p></td>
            
          </tr>
<tr>
            <td colspan="5" align="right" style="font-size: 15px"><strong>Quantidade Total   </strong></td>
            <td bgcolor="#ccc"  align="right"><b>'. number_format($number,0) .'</b></td>
          </tr>

          <tr>
            <td colspan="5" align="right" style="font-size: 15px"><strong>Antes do Desconto   </strong></td>
            <td bgcolor="#ccc"  align="right"><b>'. number_format($total,2) .'</b></td>
          </tr>

          <tr>
            <td colspan="5" align="right" style="font-size: 15px"><strong>Desconto   </strong></td>
            <td bgcolor="#ccc"  align="right"><b>'. number_format(0,2) .'</b></td>
          </tr>

          <tr>
            <td colspan="5" align="right" style="font-size: 15px">Imposto  </td>
            <td bgcolor="#ccc"  align="right"><b>'. number_format(0,2) .'</b></td>
          </tr>

          <tr>
            <td colspan="5" align="right" style="font-size: 15px"><strong>Total do Documento em (MZN)  </strong></td>
            <td bgcolor="#ccc"  align="right"><b>'. number_format($total,2) .'</b></td>
          </tr>



        </table>
      </tr>
    </table>
    

<p>&nbsp;</p>
';

$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');

//require_once(dirname(__FILE__).'/tcpdf_barcodes_1d_include.php');

// set the barcode content and type

// print some rows just as example
//for ($i = 0; $i < 10; ++$i) {
//$pdf->MultiCell(0, 0, $text."\n", 1, 'J', 1, 1, '', '', true, 0, false, true, 0);
$pdf->writeHTMLCell(0, 0, '', '', $text, 0, 1, 0, true, '', true);
//}

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
