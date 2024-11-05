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
if(isset($_REQUEST['d'])) { 
  $doctor_id=$_REQUEST['d'];
  $query="SELECT sap_procedures.Name as Consultation, DATE(sap_bill_item.CreateDate) as Date, 
   sap_bill.company_type_id as CompType,
  sap_companies_type.name as CompanyType, sap_companies.Name as Company,
  sap_bill_item.unit_price as Price,  doctor.Name as Doctor, pay_mode as PayMode
  FROM sap_bill_item , sap_bill, sap_procedures, doctor,sap_companies,sap_companies_type
  WHERE sap_bill_item.bill_id = sap_bill.id
    And sap_bill_item.doctor =doctor.Doctor_ID
    And sap_bill_item.item_id = sap_procedures.id
    And sap_bill.company_id = sap_companies.id
    And sap_bill.company_type_id = sap_companies_type.id
    And sap_bill_item.doctor = $doctor_id
    And sap_bill.active_id = ". $active_id;

} else {

$query="SELECT sap_procedures.Name as Consultation, DATE(sap_bill_item.CreateDate) as Date, 
 sap_bill.company_type_id as CompType,
 sap_companies_type.name as CompanyType, sap_companies.Name as Company,
        sap_bill_item.unit_price as Price,  doctor.Name as Doctor, pay_mode as PayMode
        FROM sap_bill_item , sap_bill, sap_procedures, doctor,sap_companies,sap_companies_type
        WHERE sap_bill_item.bill_id = sap_bill.id
          And sap_bill_item.doctor = doctor.Doctor_ID
          And sap_bill_item.item_id = sap_procedures.id
          And sap_bill.company_id = sap_companies.id
          And sap_bill.company_type_id = sap_companies_type.id
          And sap_bill.active_id = ". $active_id;
}

$result=$this->db->query($query);
$user_printer = $this->session->userdata('title') . ' ' . $this->session->userdata('name') . ' ' . $this->session->userdata('other_name');

foreach ($result->result_array() as $company) { 
  if($company['CompType']>(int)1) {
  $comp=$company['Company']; 
  $comptype = $company['CompanyType']; 
} else {
  $comp=$p_name;
  $comptype = $company['CompanyType'];
}

 }

$dt = new DateTime($entry_time);

$hospital_name = config_item('hospital_name');


$text = '<table width="100%" border="0" align="center">
      <tr align="center">
        <td align="left" colspan="2" valign="top">
          <p>&nbsp;
        <img src="images/hcq.png" alt="" width="155px"> <br>
          <strong style="font-size: 15px;">'.$this->getHid.'</strong>
        <br>  Av. FPLM <br> 
        Cidade de Maputo<br>
        Mo&ccedil;ambique
          </p>
          </td>
        <td colspan="2"  valign="bottom">
        &nbsp;<br>
        <b>SERVI&Ccedil;O DE ATENDIMENTO PERSONALIZADO</b><br>
        RECINTO DO ' . $hospital_name .' <br>
        <b>NUIT 500005262<br>
        TELEFONE 84  <br>
        <font size="+6">Factura n<sup>o</sup>: '.$active_id.'/'.$dt->format("Y").'</font></b>
        
        </td>
      </tr>
      <tr>
        <td align="center" colspan="2">&nbsp;</td>
        <td align="right" width="15%">
          &nbsp;&nbsp;</td>
        <td align="left">Exmo.(s) Sr.(s)<br><b>'.$comp.'</b><br>'.$comptype.'</td>
      </tr>

     <!-- <tr>
        <td align="left" colspan="2">&nbsp;</td>
        <td align="right">NID: &nbsp;&nbsp;</td>
        <td align="left">'.$p_id .'</td>
      </tr>
      <tr>
        <td align="left" colspan="2">&nbsp;</td>
        <td align="right">NUIT: &nbsp;&nbsp;</td>
        <td align="left">'. $p_nuit.'</td>      </tr>
      <tr>
        <td align="left" colspan="2">&nbsp; </td>
        <td align="right">Endere&ccedil;o: &nbsp;&nbsp;</td>
        <td align="left">'. $p_address .'</td>
      </tr>
      <tr>
        <td align="left" colspan="2">&nbsp; </td>
        <td align="right">E-mail: &nbsp;&nbsp;</td>
        <td align="left"></td>
      </tr>
      <tr>
        <td align="left" colspan="2">&nbsp;</td>
        <td align="right">Tel: &nbsp;&nbsp;</td>
        <td align="left">'. $p_telephone .'</td>
      </tr>
      -->
      
     
      <tr>
        <td></td>
      </tr>
      <tr>
      <table border="0">
      <tr>
      <td align="left">Data da Consulta: '. $dt->format("Y-m-d") .'</td>
      <td align="center">Data de Emiss&atilde;o: '. date("Y-m-d") .'</td>
      <td align="right">Data de Vencimento: '. date("Y-m-d") .'</td>
    </tr>
     </table> 
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
    $paymode=$row["pay_mode"];
}
$text .= '
          <tr>
            <td colspan="6" align="right" style="font-size: 15px"><p></p></td>
            
          </tr>
<tr>
            <td colspan="5" align="right" style="font-size: 15px"><strong>Quantidade Total   </strong></td>
            <td bgcolor="#ccc"  align="right"><b>'. number_format($number-1,0) .'</b></td>
          </tr>

          <tr>
            <td colspan="5" align="right" style="font-size: 15px"><strong>Total da Despesa   </strong></td>
            <td bgcolor="#ccc"  align="right"><b>'. number_format($total,2) .'</b></td>
          </tr>

          <tr>
            <td colspan="5" align="right" style="font-size: 15px"><strong>Desconto   </strong></td>
            <td bgcolor="#ccc"  align="right"><b>'. number_format(0,2) .'</b></td>
          </tr>

          <tr>
          <td colspan="5" align="right" style="font-size: 15px"><strong>IVA   </strong></td>
            <td bgcolor="#ccc"  align="right"><b>'. number_format(0,2) .'</b></td>
          </tr>

          <tr>
            <td colspan="5" align="right" style="font-size: 15px"><strong>Total do Documento em (MZN)  </strong></td>
            <td bgcolor="#ccc"  align="right" style="font-size: 15px"><b>'. number_format($total,2) .'</b></td>
          </tr>
          <tr><td></td></tr>
        </table>
        
        
        <table width="100%" style="border: 1px 0px 0px">
        <tr>
        <td><b>CONDI&Ccedil;&Oacute;ES DE PAGAMENTO</b>
      <br>
Esta factura tem um prazo de 30 Dias e o atraso no pagamento incorrer&aacute; no acr&eacute;scimo da taxa de mora a favor da <b>Cl&iacute;nica Especial do Hospital Central de Nampula.</b>
        </td>
        </tr>
        </table>  
       
        <table width="100%" border="0.5px">
        <tr>
        <td align="left"  bgcolor="#cccfff"> <b><u>DADOS BANC&Aacute;RIOS</u></b> <br>
      <b>BENEFICI&Aacute;RIO: </b>H.C.N - CLINICA ESPECIAL<br>  
      <b>BANCO:</b> FNB - MO&Ccedil;AMBIQUE<br>
      <b>Nº CONTA:</b> 930235710001<br>  
      <b>NIB:</b> 0014.00000.9302357.101.50<br>  
      <b>IBAN:</b> MZ59.0014.00000.9302357.101.50<br>  
      <b>SWIFT/BIC:</b> FIRNMZMX
        </td>

        <td align="left"> <b><u>DADOS DO MEMBRO</u></b> <br>
        <b>N&uacute;mero do Membro: </b> '.$p_id .'<br>  
        <b>Refer&ecirc;ncia do Membro:</b> <br>
        <b>Membro Principal:</b> '. $p_name .'<br>  
        <b>Membro Dependente:</b> <br>  
       
          </td>


        </tr>
        </table>

<p>&nbsp;</p>
        <table width="100%" style="border-top: 1px 0px 0px">
        <tr>
        <td align="left">Processado Por Computador<br>
        <b> &copy;SISH-H.C.N - '.date("Y").'</b>  
        </td>
        <td align="center">Operador do Sistema <br>
        <b>'.$user_printer.'</b>  
        </td>

        <td> Data da Impress&atilde;o<br>
        '.date("d-m-Y H:m:s").' 
        </td>
        
        
        </tr>
        </table>



        <h3 align="center"><i>O Nosso Maior Valor &Eacute; a Vida</i></h3>
      </tr>




    </table>
    

<p>&nbsp;</p>


';

/*$pdf->SetHeader('Document Title');
$pdf->SetFooter('Document Title');*/

$pdf->SetAuthor('Jordao Cololo');
$pdf->SetTitle('sap_hcn_factura_'.$active_id.'_NID_'.$p_id);
$pdf->SetFooterData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
//require_once(dirname(__FILE__).'/tcpdf_barcodes_1d_include.php');

// set the barcode content and type

// print some rows just as example
//for ($i = 0; $i < 2; ++$i) {
//$pdf->MultiCell(0, 0, $text."\n", 1, 'J', 1, 1, '', '', true, 0, false, true, 0);
$pdf->writeHTMLCell(0, 0, '', '', $text."\n", 0, 1, 0, true, '', true);
//}

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('sap_hcn_'.$p_id.'_'.date("dmYH:m:s").'.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+
