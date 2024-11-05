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
    array('orientation' => 'L', 'unit' => 'mm', 'format' => 'A4', 'footer' => true)
);
$pdf = $this->mdsreporter;

foreach ($all_service as $service) {
    $pdf->addPage();

    $pdf->writeTitle($hospital);
    $pdf->writeSubTitle($service->abrev . ' Reserva de ' . ' ' . $date);


    $query = "SELECT 
	SUBSTR(patient_active_list.CreateDate, 1, 16) as CreateTime,
    SUBSTR(patient_active_list.EntryTime, 1, 10) as EntryTime,
    patient.PID,
	CONCAT(patient.Personal_Title, ' ', patient.Firstname,' ',patient.Name) AS Patient,
    patient.Gender,
    patient.DateOfBirth,
    patient_emr_reasons.HospitalizationReason,
    patient_active_list.Destination,
    hospital_services.abrev,
    doctor.Name as Doctor,
    CONCAT(user.Name,' ',user.OtherName) AS Reception
    FROM patient_active_list
    LEFT JOIN patient ON patient_active_list.PID = patient.PID
    LEFT JOIN user ON user.UID = patient_active_list.CreateUser
    LEFT JOIN hospital_services ON hospital_services.service_id = patient_active_list.Service
    LEFT JOIN patient_emr_reasons ON patient_emr_reasons.PEMRRID = patient_active_list.HospitalizationReason
    LEFT JOIN doctor ON doctor.Doctor_ID = patient_active_list.Doctor_ID
    WHERE patient_active_list.Service = $service->service_id AND patient_active_list.EntryTime like '" . $date . "%'";
    unset($result);
    $result = $this->db->query($query);
    $count = $result->num_rows();

    if ($count != 0) {
        $pdf->Ln();
        $pdf->SetWidths(array(22, 22, 17, 50, 10, 15, 22, 25, 30, 30, 30));
        $pdf->Row(array('Data de Registo', 'Data da Consulta', 'NID', 'Paciente', 'G', 'Idade', utf8_decode('Motivo de Hospitalização'), 'Destino', utf8_decode('Serviço'), utf8_decode('Médico'), 'Criado Por'), TRUE);
        foreach ($result->result_array() as $row) {
            $age_str = get_age_str($row['DateOfBirth'], $row['CreateTime']);
            $pdf->Row(array($row['CreateTime'], $row["EntryTime"], $row['PID'], utf8_decode($row['Patient']), $row['Gender'], $age_str, utf8_decode($row['HospitalizationReason']),
                utf8_decode($row['Destination']), utf8_decode($row['abrev']), utf8_decode($row['Doctor']), utf8_decode($row['Reception'])));
        }
        $pdf->MultiCell(0, 6, "Total : $count");
    }
}
$pdf->Output('Registro de ' . ' ' .$date. '.pdf', 'I');
?>
