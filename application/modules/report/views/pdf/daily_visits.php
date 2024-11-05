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
$pdf->addPage();

$pdf->writeTitle($hospital);
$pdf->writeSubTitle('OPD Attendances ' . $date);

 
$query = "
    SELECT
	SUBSTR(opd_visits.CreateDate, 1, 16) as CreateTime,
    CONCAT(user.Name,' ',user.OtherName) AS Doctor,
    hospital_services.abrev,
    patient.PID,
	CONCAT(patient.Personal_Title, ' ', patient.Firstname,' ',patient.Name) AS Patient,
    patient.Gender,
    patient.DateOfBirth,
    opd_visits.Complaint
    FROM opd_visits
    LEFT JOIN patient ON opd_visits.PID = patient.PID
    LEFT JOIN user ON user.UID = opd_visits.Doctor
    LEFT JOIN patient_active_list ON patient_active_list.ACTIVE_ID = opd_visits.ActiveListID
    LEFT JOIN hospital_services ON hospital_services.service_id = patient_active_list.Service
    WHERE opd_visits.CreateDate like '". $date. "%'";
unset($result);
$result = $this->db->query($query);
$count = $result->num_rows();

if ($count != 0) {
    $pdf->Ln();
    $pdf->SetWidths(array(30, 30, 30, 20, 50, 10, 22, 70));
    $pdf->Row(array('Time', 'Medico', 'Servico', 'NID','Paciente', 'Sexo', 'Idade', 'Queixa'), TRUE);
    foreach ($result->result_array() as $row) {
        $pdf->Row(array($row['CreateTime'], $row["Doctor"], $row["abrev"], $row['PID'], utf8_decode($row['Patient']), $row['Gender'], get_age_str($row['DateOfBirth'], $row['CreateTime']), utf8_decode($row['Complaint'])));
    }
    $pdf->MultiCell(0, 6, "Total : $count");
}
$pdf->Output('opd_visits' . $date, 'I');
?>
