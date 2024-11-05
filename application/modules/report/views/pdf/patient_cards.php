<?php

/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology Agency of Sri Lanka
<http: www.hhims.org/>
----------------------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify it under the
terms of the GNU Affero General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along
with this program. If not, see <http://www.gnu.org/licenses/> or write to:
Free Software  HHIMS
C/- Lunar Technologies (PVT) Ltd,
15B Fullerton Estate II,
Gamagoda, Kalutara, Sri Lanka
----------------------------------------------------------------------------------
Author: Mr. Thurairajasingam Senthilruban   TSRuban[AT]mdsfoss.org
Consultant: Dr. Denham Pole                 DrPole[AT]gmail.com
URL: http: www.hhims.org
----------------------------------------------------------------------------------
*/


$this->load->library(
    'class/MDSReporter',
    array('orientation' => 'P', 'unit' => 'mm', 'format' => 'receipt', 'footer' => false)
);
$pdf = $this->mdsreporter;

// Document constants
$pat_nam = 'Nome: ';
$pat_pid = 'Data de nascimento: ';
//$bring = 'Bring this card with you on your next visit';
$bring = utf8_decode('Traga este cartão sempre que vier ao hospital');
$dat = 'Date: ';
$form_title = 'Patient Slip';
$pat_sex = 'Sex: ';
$pat_dob = 'Date of birth: ';
$pat_age = 'Age: ';
$pat_civ = 'Civil status: ';
$pat_nic = 'NIC number: ';
$pat_rem = 'Remarks: ';
$pat_hos = 'Hospital: ';
$pat_add = 'Address: ';

$query="select * from patient where PID=$pid";
$result=$this->db->query($query);
$patient=$result->first_row();
if ($result->num_rows()) {
    $pat_nam_d = utf8_decode($patient->Personal_Title.' '.$patient->Firstname. ' '. $patient->Name) ; //returns the fullname
    $pat_hos_d = "HOSPITAL"; //returns the default hospital
    $pat_sex_d = $patient->Gender;
    $pat_dob_d = $patient->DateOfBirth;
    $pat_civ_d = $patient->Personal_Civil_Status;
    $pat_nic_d = $patient->BI_ID;
    $pat_rem_d = utf8_decode($patient->Remarks);
    $pat_pid_d = $patient->DateOfBirth;
    $barcode = $patient->PID;
    $pat_add_d = $patient->Address_Street;
    $pid = $patient->PID;
}

function showData($x1, $y1, $pat_hos_d, $pat_nam, $pat_pid, $pat_nam_d, $pat_pid_d, $pid, $bring,$barcode,$pdf) {
    $row = 0;
    $pdf->SetAutoPagebreak(0);
    $pdf->SetFont('arial', 'BU', 10);
    $dy=8;
    $pdf->SetXY(5, $y1 + $row*$dy);
    $pdf->write(0, $pat_hos_d);
//
    $row += 1;
    $pdf->SetFont('arial', 'B', 13);
    $pdf->SetXY(0, $y1 + $row*$dy);
    $pdf->MultiCell(0,4,$pat_nam_d,0, 'L');
//
//    $row += 1.5;
//    $pdf->SetXY(0, $y1 + $row*$dy);
//    $pdf->SetFont('arial', '', 10);
//    $pdf->write(0, 'NID:');
//    $pdf->SetFont('arial', 'B', 10);
//    $pdf->SetXY($x1 + 50, $y1 + $row*$dy);
//    $pdf->write(0, $pid);

    $row += 1;
    $pdf->setBarcode($barcode,5,$y1+ $row*$dy, false, 30, true, 0.5, 20);

    $row += 3;
    $pdf->SetXY(0, $y1 + $row*$dy);
    $pdf->SetFont('arial', 'B', 8);
    $pdf->MultiCell(0, 3, $bring, 0, 'L');
}

#.......................................................................................................................................
// Create fpdf object
$pdf->addPage();
// Clean any content of the output buffer
ob_end_clean();

$dx=$pdf->getPageWidth()-$pdf->GetStringWidth($pat_nam_d);
$dx-=140;
$dx/=2;
showData($dx,10, $pat_hos_d, $pat_nam, $pat_pid, $pat_nam_d, $pat_pid_d, $pid, $bring,$barcode,$pdf);
$pdf->Output($pat_pid_d . ' patient_cards.pdf', 'I');
