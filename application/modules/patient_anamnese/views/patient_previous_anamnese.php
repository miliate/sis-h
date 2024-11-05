<?php
if ((isset($patient_anamnese_list)) && (!empty($patient_anamnese_list))) {			
	echo '<div class="panel panel-default">';
	echo '<div class="panel-heading"><b>' .lang('patient_anamnese_psychological') . '</b></div>';
	echo '<div style="max-height: 160px; overflow-y: auto;">'; 
	echo '<table class="table table-condensed table-hover" style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';

	echo '<thead>';
	echo '<tr>';
	echo '<th>' . lang('Date and Time') . '</th>';
	echo '<th>' . lang('Main Complaint') . '</th>';
	echo '<th>' .lang('Alguma vez foi atendido por um profissional de saúde mental?') . '</th>';
	echo '<th>' . lang('Doctor') . '</th>';
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	for ($i = 0; $i < count($patient_anamnese_list); ++$i) {
		echo '<tr  onclick="self.document.location=\'' . site_url("/patient_anamnese/edit/" . $patient_anamnese_list[$i]["PAPID"]) . '?CONTINUE=' . $continue . '\';">';
		echo '<td>';
		echo $patient_anamnese_list[$i]["CreateDate"];
		echo '</td>';
		echo '<td>';
		echo $patient_anamnese_list[$i]["MainComplaint"];
		echo '</td>';
		echo '<td>';
		echo $patient_anamnese_list[$i]["MentalCare"];
		echo '</td>';
		echo '<td>';
		echo $patient_anamnese_list[$i]["Doctor"];
		echo '</td>';

		echo '<td>';
		// echo 'By: '.$patient_anamnese_list[$i]["CreateUser"];
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody>'; 
	echo '</table>';
	echo '</div>'; 
	echo '</div>';	
}
?>