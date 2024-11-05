<?php
if ((isset($previous_notes_list))&&(!empty($previous_notes_list))){
    echo '<div class="panel  panel-default"  >';
    echo '<div class="panel-heading"  ><b>'. strtoupper($type) .' Patient Notes</b></div>';
    echo '<div style="max-height: 160px; overflow-y: auto;">'; 

    echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
    for ($i=0;$i<count($previous_notes_list); ++$i){
        echo '<tr onclick="self.document.location=\''.site_url("patient_note/edit/".$previous_notes_list[$i]["patient_notes_id"]).'?CONTINUE='.$continue.'\';">';
        echo '<td>';
        echo $previous_notes_list[$i]["CreateDate"];
        echo '</td>';
        echo '<td>';
        echo $previous_notes_list[$i]["Type"];
        echo '</td>';
        echo '<td>';
        echo $previous_notes_list[$i]["notes"];
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';
    echo '</div>';
}
?>		