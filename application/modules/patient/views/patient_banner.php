<?php
echo '<a target="_blank" href="'.site_url("patient/view/".$patient_info["PID"]).'"><div class="alert alert-info" style="margin-bottom:1px;padding-top:8px;padding-bottom:8px">';
	echo '<div>';
    echo '<b style="font-size:16px;">';
        // echo $patient_info["Personal_Title"];
        if (!empty($patient_info["Firstname"])) {
        	echo ' '. $patient_info["Firstname"];
        }
        echo ' ' . $patient_info["Name"];
    
	echo '</b>';
    echo '</div>';
    echo '<div>';
		if ($patient_info["Age"]["years"]>0){
			echo  $patient_info["Age"]["years"]." Anos&nbsp;";
			echo  $patient_info["Age"]["months"]." Meses e&nbsp;";
			echo  $patient_info["Age"]["days"]." Dias&nbsp;";
		}
		
	echo '&nbsp;/&nbsp;';
    echo  $patient_info["Gender"];
    echo '&nbsp;/&nbsp;';
    echo $patient_info["race"];
    echo '&nbsp;/&nbsp;';
	echo  $patient_info["Personal_Civil_Status"];
    echo '</div>';
    echo '<div>';
    echo lang('Country of Origin').' : '.$patient_info["Country_name"];
    echo '&nbsp;/&nbsp;';
    echo lang('Residence').' : '.$patient_info["Address_Street"];
    echo '</div>';
    // echo '<div>';
	// echo  $patient_info["Address_Street"];
	// echo  '<span class="pull-right"><b>'. lang('Patient ID') . ': 1110141'.substr($patient_info["CreateDate"],0,4).$patient_info["PID"].'</b></span>';
    // echo '</div>';
	echo '</div></a>';
?>
