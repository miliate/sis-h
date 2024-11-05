<?php
if ((isset($patient_history_list)) && (!empty($patient_history_list))) {			
	echo '<div class="panel panel-default">';
	echo '<div class="panel-heading"><b>' . lang('Past history') . '</b></div>';
	echo '<div style="max-height: 160px; overflow-y: auto;">'; 
	echo '<table class="table table-condensed table-hover" style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';

	echo '<thead>';
	echo '<tr>';
	echo '<th>' . lang('Date') . '</th>';
	echo '<th>' . lang('Main Complaint') . '</th>';
	echo '<th>' . lang('Diet History') . '</th>';
	echo '<th>' . lang('Doctor') . '</th>';
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	for ($i = 0; $i < count($patient_history_list); ++$i) {
		echo '<tr onclick="self.document.location=\'' . site_url("/patient_history/edit/" . $patient_history_list[$i]["HID"]) . '?CONTINUE=' . $continue . '\';">';
		echo '<td>';
		echo $patient_history_list[$i]["CreateDate"];
		echo '</td>';
		echo '<td>';
		echo $patient_history_list[$i]["Complaint"];
		echo '</td>';
		echo '<td>';
		echo $patient_history_list[$i]["DietHistory"];
		echo '</td>';
		echo '<td>';
		echo $patient_history_list[$i]["Doctor"];
		echo '</td>';

		echo '<td>';
		// echo 'By: '.$patient_history_list[$i]["CreateUser"];
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody>'; 
	echo '</table>';
	echo '</div>'; 
	echo '</div>';	
}
?>