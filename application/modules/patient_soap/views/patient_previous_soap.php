<?php
if ((isset($previous_soap_list))&&(!empty($previous_soap_list))){
	echo '<div class="panel panel-default">';
	echo '<div class="panel-heading"><b>'. lang('Previous SOAP'). '</b></div>';

		echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
		for ($i=0; $i<count($previous_soap_list); ++$i){
			echo '<tr';
            echo ' onclick = self.document.location="'.site_url("patient_soap/edit_created/".$previous_soap_list[$i]->patient_soap_id).'?CONTINUE='.$continue.'"';
			echo '>';
			echo '<td width="10px">';
			echo '<a title="Click here to open the related record" class="btn btn-xs ';
			if ($this->uri->segment(3) == $previous_soap_list[$i]->RefID){
				echo ' btn-warning"';
			}
			else{
				echo ' btn-default"';
			}
            switch ($previous_soap_list[$i]->RefType) {
                case 'emr':
                    echo ' href="'.site_url("emergency_visit/view/".$previous_soap_list[$i]->RefID).'" ';
                    break;
                default:
                    echo ' href="'.site_url("opd_visit/view/".$previous_soap_list[$i]->RefID).'" ';
                    break;
            }
			echo '>'.$previous_soap_list[$i]->RefType.'</a>';
			echo '</td>';
			echo '<td width="100px">';
			echo $previous_soap_list[$i]->CreateDate;
			echo '</td>';
			echo '<td> '. lang('Subjective'). '</td>';
			echo '<td>'. $previous_soap_list[$i]->subjective. '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td></td><td></td>';
			echo '<td> '. lang('Objective'). '</td>';
			echo '<td>'. $previous_soap_list[$i]->objective. '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td></td><td></td>';
			echo '<td> '. lang('Assessment'). '</td>';
			echo '<td>'. $previous_soap_list[$i]->assessment. '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td></td><td></td>';
			echo '<td> '. lang('Plan'). '</td>';
			echo '<td>'. $previous_soap_list[$i]->plan. '</td>';
			echo '</tr>';
		}
		echo '</table>';
	echo '</div>';	
}
?>