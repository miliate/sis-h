<?php
if ((isset($previous_prescription_list)) && (!empty($previous_prescription_list))) {
    echo '<div class="panel panel-default" >';
    echo '<div class="panel-heading" ><b>'. lang('Prescription'). '</b></div>';
    echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
    for ($i = 0; $i < count($previous_prescription_list); ++$i) {
        echo '<tr onclick="self.document.location=\'' . site_url("patient_prescription/view/" . $previous_prescription_list[$i]->PrescriptionID) . '?CONTINUE='. $continue . '\';">';
        echo '<td>';
        echo $previous_prescription_list[$i]->CreateDate;
        echo '</td>';
        echo '<td>';
        if (!empty($previous_prescription_list[$i]->order_by)){
            echo lang('Doctor'). ': <b>'.$previous_prescription_list[$i]->order_by->Title.' '.$previous_prescription_list[$i]->order_by->Name. ' '.$previous_prescription_list[$i]->order_by->OtherName. '</b>';
        }
        echo '</td>';

        echo '<td style="text-align: right">';
        switch ($previous_prescription_list[$i]->Status) {
            case 'Pending':
                echo '<span class="glyphicon glyphicon-time"></span>';
                echo '<span style="color: red"> Pendente</span>';
                break;
            case 'Dispensed':
                echo '<span class="glyphicon glyphicon-check"></span>';
                echo '<span style="color: green"> Dispensada</span>';
                break;
        }
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td></td>';
        echo '<td>'. lang('Name').'</td>';
        echo '<td>'. lang('Dosage') .'</td>';
        echo '<td>'. lang('Frequency') .'</td>';
        echo '<td>'. lang('Period') .'</td>';
        echo '</tr>';
        foreach ($previous_prescription_list[$i]->prescription_have_drugs as $prescription_have_drug) {
            echo '<tr>';
            echo '<td>'. $prescription_have_drug->Order .'</td>';
//            var_dump(empty($prescription_have_drug->drug) ? '' : $prescription_have_drug->drug->name);
            echo '<td>'. (empty($prescription_have_drug->drug) ? '' : $prescription_have_drug->drug->name) .'</td>';
            echo '<td>'. (empty($prescription_have_drug->dose) ? '' : $prescription_have_drug->dose->Dosage) .'</td>';
            echo '<td>'. (empty($prescription_have_drug->frequency) ? '' : $prescription_have_drug->frequency->Frequency) .'</td>';
            echo '<td>'. (empty($prescription_have_drug->Period) ?  '' : $prescription_have_drug->Period) .'</td>';
            echo '</tr>';
        }

    }
    echo '</table>';
    echo '</div>';
}
?>