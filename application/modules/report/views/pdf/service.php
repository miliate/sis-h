<?php

//  ------------------------------------------------------------------------ //
//                   MDSFoss - Free Patient Record System                    //
//            Copyright (c) 2011 Net Com Technologies (Sri Lanka)            //
//                        <http://www.mdsfoss.org/>                          //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation.                                            //									     									 //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even an implied warranty of            //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to:                               //
//  Free Software  MDSFoss                                                   //
//  C/- Net Com Technologies,                                                //
//  15B Fullerton Estate II,                                                 //
//  Gamagoda, Kalutara, Sri Lanka                                            //
//  ------------------------------------------------------------------------ //
//  Author: Mr. Thurairajasingam Senthilruban   TSRuban[AT]mdsfoss.org       //
//  Consultant: Dr. Denham Pole          	DrPole[AT]gmail.com          //
//  URL: http://www.mdsfoss.org                                              //
// ------------------------------------------------------------------------- //
header("Content-type: application/pdf");
$this->load->library(
    'class/MDSReporter',
    array('orientation' => 'L', 'unit' => 'mm', 'format' => 'A4', 'footer' => true)
);
$pdf = $this->mdsreporter;

if ((!$from_date && !$to_date) or ($from_date > $to_date)) {
    echo 'please spacify valid date ';
    exit();
}
// TSR: commented
//$diff = $from_date->diff($to_date)->format('%a');
//TSR:Added
$startDate = strtotime($from_date);
$endDate = strtotime($to_date);
$diff = intval(abs(($startDate - $endDate) / 86400));
//TSR

$from_date = new DateTime($from_date);
$to_date = new DateTime($to_date);



$pdf->Ln(3);

for ($index = 0; $index <= $diff; $index++) {

    $date = $from_date->format("Y-m-d");
    $pdf->AddPage();
    $pdf->writeTitle($hospital);
    $pdf->writeSubTitle($service_name .' on ' . $date);

    $query = "SELECT
                SUBSTR(patient_active_list.RegistrationDate, 1, 16) as RegistrationDate,
                SUBSTR(patient_active_list.EntryTime, 1, 10) as EntryTime,
                patient.PID,
                CONCAT(patient.Firstname,' ',patient.Name) AS Patient,
                patient.Gender,
                patient.DateOfBirth,
                patient_emr_reasons.HospitalizationReason,
                patient_active_list.Destination,
                hospital_services.abrev,
                doctor.Name as Doctor, 
                patient_active_list.Remarks
                FROM patient_active_list
                LEFT JOIN patient ON patient.PID = patient_active_list.PID
                LEFT JOIN hospital_services ON hospital_services.service_id = patient_active_list.Service
                LEFT JOIN patient_emr_reasons On patient_emr_reasons.PEMRRID = patient_active_list.HospitalizationReason
                LEFT JOIN doctor ON doctor.Doctor_ID = patient_active_list.Doctor_ID
                WHERE patient_active_list.Active = 1 AND patient_active_list.EntryTime LIKE '$date%' AND patient_active_list.Service = $service
                ORDER BY RegistrationDate ASC";
    unset($result);
    $result = $this->db->query($query);
    $count = $result->num_rows();
    $row_num = 1;

    if ($count != 0) {
        $pdf->Ln();
        $pdf->SetWidths(array(10, 30, 15, 50, 5, 15, 22, 22, 30 , 40, 30));
        $pdf->Row(array('#','Data de Registo','NID', 'Paciente', 'G', 'Idade', 'Motivo', 'Destino', utf8_decode('Serviço'), utf8_decode('Médico'), utf8_decode('Mais Observações')), TRUE);

//        var_dump($row['EntryTime']);
        foreach ($result->result_array() as $row) {
            $age_str = get_age_str($row['DateOfBirth'], $row['EntryTime']);
//            var_dump($row['DateOfBirth']);
//            var_dump($age_str);
            $pdf->Row(array($row_num ++, $row['RegistrationDate'], $row['PID'], utf8_decode($row["Patient"]),
                $row['Gender'], $age_str, utf8_decode($row['HospitalizationReason']), utf8_decode($row['Destination']), $row['abrev'], utf8_decode($row['Doctor']), utf8_decode($row['Remarks'])));
        }
        $pdf->MultiCell(0, 6, "Total : $count");
    }

    date_add($from_date, new DateInterval('P1D'));
}

$pdf->Output('service_from ' . $from_date->format("Y-m-d") . ' to ' . $to_date->format("Y-m-d"), 'i');
?>