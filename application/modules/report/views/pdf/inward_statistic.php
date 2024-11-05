<?php
// Criado por Msc JORDAO COLOLO 829397690, 27.11.2018

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
$pdf->SetFont('helvetica', '', 20);
// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Example of text layout using Multicell()', '', 0, 'L', true, 0, false, false, 0);

//$pdf->Ln(5);

$pdf->SetFont('times', '', 9.5);

//$pdf->SetCellPadding(0);
//$pdf->SetLineWidth(2);

// set color for background
$pdf->SetFillColor(255, 255, 200);
// set the barcode content and type
$barcodeobj = new TCPDFBarcode('http://www.misau.gov.mz', 'C128');

// output the barcode as HTML object
$text1= $barcodeobj->getBarcodeHTML(2, 30, 'black');

//1. Number of admitted patients yesterday
$query1="SELECT COUNT(*) as c
FROM admission
WHERE (IsDischarged = 0 OR (ADMID IN 
(SELECT RefID FROM discharge_order WHERE DischargeDate >= '".$date." 00:00:00' AND RefType = 'ADM')))
AND AdmissionDate < '".$date." 00:00:00' AND Ward = ".$ward;
$result1=$this->db->query($query1);
$yesterday_admitted_patient=$result1->first_row();
$statistic1 = $yesterday_admitted_patient->c;

//2. Number of Admitted patients
$query2="SELECT COUNT(*) as c
FROM admission
WHERE DATE(AdmissionDate) LIKE '".$date."' AND Ward = ".$ward;
$result2=$this->db->query($query2);
$admitted_patient=$result2->first_row();
$statistic2 = $admitted_patient->c;

//2.1. Number of admitted male patients
$query2_1="SELECT COUNT(*) as c
FROM admission as A, patient as P
WHERE A.PID = P.PID AND P.Gender = 'M' AND
DATE(A.AdmissionDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result2_1=$this->db->query($query2_1);
$admitted_male_patient=$result2_1->first_row();
$statistic2_1 = $admitted_male_patient->c;

//2.2. Number of admitted female patients
$query2_2="SELECT COUNT(*) as c
FROM admission as A, patient as P
WHERE A.PID = P.PID AND P.Gender = 'F' AND
DATE(A.AdmissionDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result2_2=$this->db->query($query2_2);
$admitted_female_patient=$result2_2->first_row();
$statistic2_2 = $admitted_female_patient->c;

//3. Number of discharged patients
$query3="SELECT COUNT(*) as c
FROM discharge_order as D, admission as A
WHERE D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result3=$this->db->query($query3);
$discharged_patient=$result3->first_row();
$statistic3 = $discharged_patient->c;

//3.1. Number of discharged male patients
$query3_1="SELECT COUNT(*) as c
FROM discharge_order as D, patient as P, admission as A
WHERE D.PID = P.PID AND P.Gender = 'M' AND D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result3_1=$this->db->query($query3_1);
$discharged_male_patient=$result3_1->first_row();
$statistic3_1 = $discharged_male_patient->c;

//3.2. Number of discharged female patients
$query3_2="SELECT COUNT(*) as c
FROM discharge_order as D, patient as P, admission as A
WHERE D.PID = P.PID AND P.Gender = 'F' AND D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result3_2=$this->db->query($query3_2);
$discharged_female_patient=$result3_2->first_row();
$statistic3_2 = $discharged_female_patient->c;

//4. Total days of admission
$query4="Select TIMESTAMPDIFF(day, MIN(AdmissionDate), '".$date." 23:59:59') as c
FROM admission
WHERE (IsDischarged = 0 OR (ADMID IN 
(SELECT RefID FROM discharge_order WHERE DischargeDate > '".$date." 23:59:59' AND RefType = 'ADM')))
AND AdmissionDate < '".$date." 23:59:59' AND Ward = ".$ward;
$result4=$this->db->query($query4);
$total_days_admission=$result4->first_row();
$statistic4 = $total_days_admission->c;

//5. Number of transit patients
$query5="SELECT COUNT(*) as c
FROM admission
WHERE (IsDischarged = 0 OR (ADMID IN 
(SELECT RefID FROM discharge_order WHERE DischargeDate >= '".$date." 07:00:00' AND RefType = 'ADM')))
AND AdmissionDate < '".$date." 07:00:00' AND Ward = ".$ward;
$result5=$this->db->query($query5);
$transit_patient=$result5->first_row();
$statistic5 = $transit_patient->c;

//6. Number of died patients (Obito)
$query6="SELECT COUNT(*) as c
FROM discharge_order as D, admission as A
WHERE D.OutCome = 'Obito' AND D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DatetimeDie) LIKE '".$date."' AND A.Ward = ".$ward;
$result6=$this->db->query($query6);
$died_patient=$result6->first_row();
$statistic6 = $died_patient->c;

//6.1. Number of died male patients (Obito)
$query6_1="SELECT COUNT(*) as c
FROM discharge_order as D, patient as P, admission as A
WHERE D.OutCome = 'Obito' AND D.PID = P.PID AND P.Gender = 'M' AND 
D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DatetimeDie) LIKE '".$date."' AND A.Ward = ".$ward;
$result6_1=$this->db->query($query6_1);
$died_male_patient=$result6_1->first_row();
$statistic6_1 = $died_male_patient->c;

//6.2. Number of died female patients (Obito)
$query6_2="SELECT COUNT(*) as c
FROM discharge_order as D, patient as P, admission as A
WHERE D.OutCome = 'Obito' AND D.PID = P.PID AND P.Gender = 'F' AND 
D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DatetimeDie) LIKE '".$date."' AND A.Ward = ".$ward;
$result6_2=$this->db->query($query6_2);
$died_female_patient=$result6_2->first_row();
$statistic6_2 = $died_female_patient->c;

//6.3. Number of died patients < 48h (Obito)
$query6_3="SELECT COUNT(*) as c
FROM admission as A, discharge_order as D
WHERE A.ADMID = D.RefID
AND D.RefType = 'ADM'
AND D.OutCome = 'Obito'
AND (TIMESTAMPDIFF(second, A.AdmissionDate, D.DatetimeDie) - 172800) < 0
AND TIMESTAMPDIFF(second, A.AdmissionDate, D.DatetimeDie) > 0
AND DATE(D.DatetimeDie) LIKE '".$date."' AND A.Ward = ".$ward;
$result6_3=$this->db->query($query6_3);
$died_patient_l48h=$result6_3->first_row();
$statistic6_3 = $died_patient_l48h->c;

//6.4. Number of died patients > 48h (Obito)
$query6_4="SELECT COUNT(*) as c
FROM admission as A, discharge_order as D
WHERE A.ADMID = D.RefID
AND D.RefType = 'ADM'
AND D.OutCome = 'Obito'
AND (TIMESTAMPDIFF(second, A.AdmissionDate, D.DatetimeDie) - 172800) > 0
AND TIMESTAMPDIFF(second, A.AdmissionDate, D.DatetimeDie) > 0
AND DATE(D.DatetimeDie) LIKE '".$date."' AND A.Ward = ".$ward;
$result6_4=$this->db->query($query6_4);
$died_patient_m48h=$result6_4->first_row();
$statistic6_4 = $died_patient_m48h->c;

//7. Number of transferred patients (Transferencia para o Seguinte Estabelecimento)
$query7="SELECT COUNT(*) as c
FROM discharge_order as D, admission as A
WHERE D.OutCome = 'Transferencia para o Seguinte Estabelecimento' 
AND D.RefType = 'ADM' AND D.RefID = A.ADMID 
AND DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result7=$this->db->query($query7);
$transferred_patient=$result7->first_row();
$statistic7 = $transferred_patient->c;

//7.1. Number of transferred male patients (Transferencia para o Seguinte Estabelecimento)
$query7_1="SELECT COUNT(*) as c
FROM discharge_order as D, patient as P, admission as A
WHERE D.OutCome = 'Transferencia para o Seguinte Estabelecimento' 
AND D.PID = P.PID AND P.Gender = 'M' AND D.RefType = 'ADM' AND D.RefID = A.ADMID 
AND DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result7_1=$this->db->query($query7_1);
$transferred_male_patient=$result7_1->first_row();
$statistic7_1 = $transferred_male_patient->c;

//7.2. Number of transferred female patients (Transferencia para o Seguinte Estabelecimento)
$query7_2="SELECT COUNT(*) as c
FROM discharge_order as D, patient as P, admission as A
WHERE D.OutCome = 'Transferencia para o Seguinte Estabelecimento' 
AND D.PID = P.PID AND P.Gender = 'F' AND D.RefType = 'ADM' AND D.RefID = A.ADMID 
AND DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result7_2=$this->db->query($query7_2);
$transferred_female_patient=$result7_2->first_row();
$statistic7_2 = $transferred_female_patient->c;

//8. Number of abandon patients (Por Abandono)
$query8="SELECT COUNT(*) as c
FROM discharge_order as D, admission as A
WHERE D.OutCome = 'Por Abandono' AND D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result8=$this->db->query($query8);
$abandon_patient=$result8->first_row();
$statistic8 = $abandon_patient->c;

//8.1. Number of abandon male patients (Por Abandono)
$query8_1="SELECT COUNT(*) as c
FROM discharge_order as D, patient as P, admission as A
WHERE D.OutCome = 'Por Abandono' AND D.PID = P.PID AND P.Gender = 'M' 
AND D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result8_1=$this->db->query($query8_1);
$abandon_male_patient=$result8_1->first_row();
$statistic8_1 = $abandon_male_patient->c;

//8.2. Number of abandon female patients (Por Abandono)
$query8_2="SELECT COUNT(*) as c
FROM discharge_order as D, patient as P, admission as A
WHERE D.OutCome = 'Por Abandono' AND D.PID = P.PID AND P.Gender = 'F' 
AND D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result8_2=$this->db->query($query8_2);
$abandon_female_patient=$result8_2->first_row();
$statistic8_2 = $abandon_female_patient->c;

//9. Table
$query9="Select I.Name as Diagnosis, Count(if(P.Gender='M',1,NULL)) as Male, 
Count(if(P.Gender='F',1,NULL)) as Female, 
Count(if(D.OutCome='Alta Clinica',1,NULL)) as Clinic,
Count(if(D.OutCome='Obito' AND 
(TIMESTAMPDIFF(second, A.AdmissionDate, D.DatetimeDie) - 172800) <= 0,1,NULL)) as Obito_lessthan48h,
Count(if(D.OutCome='Obito' AND 
(TIMESTAMPDIFF(second, A.AdmissionDate, D.DatetimeDie) - 172800) > 0,1,NULL)) as Obito_morethan48h,
Count(if(D.OutCome='Por Abandono',1,NULL)) as Abandon, 
Count(if(D.OutCome='Transferencia para o Seguinte Estabelecimento',1,NULL)) as Transfer,
Count(if(D.OutCome='A Pedido',1,NULL)) as Others
from discharge_order as D, patient as P, admission as A, icd10 as I
where D.PID = P.PID
AND A.ADMID = D.RefID
AND I.ICDID = D.DirectDiagnosis
AND D.RefType = 'ADM'
AND DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward."
Group by I.Name";
$result9=$this->db->query($query9);

//10. Number of clinic patients (Por Abandono)
$query10="SELECT COUNT(*) as c
FROM discharge_order as D, admission as A
WHERE D.OutCome = 'Alta Clinica' AND D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result10=$this->db->query($query10);
$clinic_patient=$result10->first_row();
$statistic10 = $clinic_patient->c;

//11. Number of other patients (Por Abandono)
$query11="SELECT COUNT(*) as c
FROM discharge_order as D, admission as A
WHERE D.OutCome = 'A Pedido' AND D.RefType = 'ADM' AND D.RefID = A.ADMID AND
DATE(D.DischargeDate) LIKE '".$date."' AND A.Ward = ".$ward;
$result11=$this->db->query($query11);
$other_patient=$result11->first_row();
$statistic11 = $other_patient->c;

$this->load->model('mpersistent');
$this->load->model('m_patient');

$hospital_name = config_item('hospital_name');


$text = '<table width="90%" border="0" align="center">
      <tr>
        <td align="center"><img src="images/hcq.png" alt="" width="95"> <br>
          <strong>' . $hospital_name . '</strong></td>
      </tr>
      <tr>
        <td align="center"><p>Serviço de '.$ward_name.'<br>
          Data '.$date.'<br>
          <br>
          <strong>RELATÓRIO DIÁRIO</strong></p></td>
      </tr>
      <tr>
        <td><table width="75%" border="0" align="center">
          <tr>
            <td width="24%" align="right"><strong>Doentes as 0 horas</strong></td>
            <td width="13%">'.$statistic1.'</td>
            <td width="29%">&nbsp;</td>
            <td width="22%" align="right"><strong>Óbitos</strong></td>
            <td width="12%">'.$statistic6.'</td>
          </tr>
          <tr>
            <td align="right"><strong>Doentes Admitidos</strong></td>
            <td>'.$statistic2.'</td>
            <td>&nbsp;</td>
            <td align="right">Masculinos</td>
            <td>'.$statistic6_1.'</td>
          </tr>
          <tr>
            <td align="right">Masculinos</td>
            <td>'.$statistic2_1.'</td>
            <td>&nbsp;</td>
            <td align="right">Femininos</td>
            <td>'.$statistic6_2.'</td>
          </tr>
          <tr>
            <td align="right">Femininos</td>
            <td>'.$statistic2_2.'</td>
            <td>&nbsp;</td>
            <td align="right">dos quais -48 horas</td>
            <td>'.$statistic6_3.'</td>
          </tr>
          <tr>
            <td align="right"><strong>Altas</strong></td>
            <td>'.$statistic3.'</td>
            <td>&nbsp;</td>
            <td align="right">dos quais +48 horas</td>
            <td>'.$statistic6_4.'</td>
          </tr>
          <tr>
            <td align="right">Masculinos</td>
            <td>'.$statistic3_1.'</td>
            <td>&nbsp;</td>
            <td align="right"><strong>Transferências</strong></td>
            <td>'.$statistic7.'</td>
          </tr>
          <tr>
            <td align="right">Femininas</td>
            <td>'.$statistic3_2.'</td>
            <td>&nbsp;</td>
            <td align="right">Masculinos</td>
            <td>'.$statistic7_1.'</td>
          </tr>
          <tr>
            <td align="right"><strong>Total Dias Internamento</strong></td>
            <td>'.$statistic4.'</td>
            <td>&nbsp;</td>
            <td align="right">Femininos</td>
            <td>'.$statistic7_2.'</td>
          </tr>
          <tr>
            <td align="right"><strong>Transitam</strong></td>
            <td>'.$statistic5.'</td>
            <td>&nbsp;</td>
            <td align="right"><strong>Abandonos</strong></td>
            <td>'.$statistic8.'</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">Masculinos</td>
            <td>'.$statistic8_1.'</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">Femininos</td>
            <td>'.$statistic8_2.'</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="75%" border="1" align="center">
          <tr>
            <td width="4%" rowspan="3" align="center"><strong>Nº</strong></td>
            <td width="40%" rowspan="3" align="center"><strong>Diagnóstico</strong></td>
            <td colspan="2" rowspan="2" align="center"><strong>Sexo</strong></td>
            <td colspan="5" align="center"><strong>Tipo de Alta</strong></td>
            <td rowspan="3"><strong>Outras</strong></td>
          </tr>
          <tr>
            <td rowspan="2" align="center"><strong>Clínica</strong></td>
            <td colspan="2" align="center"><strong>Óbito</strong></td>
            <td rowspan="2" align="center"><strong>Abandono</strong></td>
            <td rowspan="2" align="center"><strong>Tranf. Extrenas</strong></td>
          </tr>
          <tr>
            <td align="center"><strong>M</strong></td>
            <td align="center"><strong>F</strong></td>
            <td align="center"><strong>-48h</strong></td>
            <td align="center"><strong>+48h</strong></td>
          </tr>';
$number = 1;
foreach ($result9->result_array() as $row) {
    $text .= '<tr>
                <td align=\"center\">'.$number.'</td>
                <td>'.$row["Diagnosis"].'</td>
                <td>'.$row["Male"].'</td>
                <td>'.$row["Female"].'</td>
                <td>'.$row["Clinic"].'</td>
                <td>'.$row["Obito_lessthan48h"].'</td>
                <td>'.$row["Obito_morethan48h"].'</td>
                <td>'.$row["Abandon"].'</td>
                <td>'.$row["Transfer"].'</td>
                <td>'.$row["Others"].'</td>
              </tr>';
    $number++;
}

$text .= '<tr>
            <td colspan="2" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
            <td bgcolor="#CCCCCC">'.$statistic3_1.'</td>
            <td bgcolor="#CCCCCC">'.$statistic3_2.'</td>
            <td bgcolor="#CCCCCC">'.$statistic10.'</td>
            <td bgcolor="#CCCCCC">'.$statistic6_3.'</td>
            <td bgcolor="#CCCCCC">'.$statistic6_4.'</td>
            <td bgcolor="#CCCCCC">'.$statistic8.'</td>
            <td bgcolor="#CCCCCC">'.$statistic7.'</td>
            <td bgcolor="#CCCCCC">'.$statistic11.'</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center"><p> <font color="#666666" size="-1">Este documento é de uso interno, exclusivamente para o ' .  $hospital_name. '<br>
        Documento produzido por Computador. Nome do Utilizador:___________________________          <?= date(\'d/m/Y H:i:s\')?></font></p> </td>
  </tr>
    </table>
    

<p>&nbsp;</p>
';




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

//Close and output PDF document
$pdf->Output('.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
