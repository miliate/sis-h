<?php
if ((isset($previous_injection_list))&&(!empty($previous_injection_list))){			
	echo '<div class="panel panel-default">';
	echo '<div class="panel-heading"><b>'. lang('Previous Injections'). '</b></div>';
	echo '<div style="max-height: 160px; overflow-y: auto;">';

		echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
		for ($i=0;$i<count($previous_injection_list); ++$i){
			echo '<tr';
			if ($previous_injection_list[$i]->status == "Pending"){
				echo ' onclick = self.document.location="'.site_url("patient_injection/edit_created/".$previous_injection_list[$i]->patient_injection_id).'?CONTINUE='.$continue.'"';
			}
			echo '>';
			echo '<td width="10px">';
			echo '<a title="Click here to open the related record" class="btn btn-xs ';
			if ($this->uri->segment(3) == $previous_injection_list[$i]->RefID){
				echo ' btn-warning"';
			}
			else{
				echo ' btn-default"';
			}
            switch ($previous_injection_list[$i]->RefType) {
                case 'emr':
                    echo ' href="'.site_url("emergency_visit/view/".$previous_injection_list[$i]->RefID).'" ';
                    break;
                default:
                    echo ' href="'.site_url("opd_visit/view/".$previous_injection_list[$i]->RefID).'" ';
                    break;
            }
			echo '>'.$previous_injection_list[$i]->RefType.'</a>';
			echo '</td>';
			echo '<td>';
			echo $previous_injection_list[$i]->CreateDate;
			echo '</td>';
			echo '<td>';
			echo $previous_injection_list[$i]->injection->name;
			echo '</td>';
//			echo '<td>';
//			echo $previous_injection_list[$i]->injection->dosage;
//			echo '</td>';
//			echo '<td>';
//			echo $previous_injection_list[$i]->injection->route;
//			echo '</td>';
			echo '<td style="text-align: right">';
            if ($previous_injection_list[$i]->status === 'Pending') {
                echo '<span class="glyphicon glyphicon-time"></span>';
                echo '<span style="color: red"> '. $previous_injection_list[$i]->status . '</span>';
            }   else {
                echo '<span class="glyphicon glyphicon-check"></span>';
                echo '<span style="color: green"> '. $previous_injection_list[$i]->status . '</span>';
            }

			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
	echo '</div>';	
	echo '</div>';
}
?>