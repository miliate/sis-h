<?php
    if ((isset($patient_allergy_list)) && (!empty($patient_allergy_list))) {
        echo '<div class="panel  panel-default" >';
            echo '<div class="panel-heading" " ><b>'. lang('Allergies'). '</b></div>';
            echo '<div style="max-height: 160px; overflow-y: auto;">';
            echo '<table class="table table-condensed table-hover"  style="font-size:0.95em;margin-bottom:0px;cursor:pointer;">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>' . lang('Date') . '</th>';
                        echo '<th>' . lang('Prescribed Time') . '</th>';
                        echo '<th>' . lang('Name') . '</th>';
                        echo '<th>' . lang('Remarks') . '</th>';
                        echo '<th>' . lang('Status') . '</th>';
                    echo '</tr>';
                echo '</thead>';
                for ($i = 0; $i < count($patient_allergy_list); ++$i) {

                    echo '<tr onclick="self.document.location=\'' . site_url("patient_allergy/edit/" . $patient_allergy_list[$i]["ALLERGYID"]) . '?CONTINUE=' . $continue . '\';">';
                        echo '<td>' . date('d-m-Y', strtotime($patient_allergy_list[$i]["CreateDate"]))  . '</td>';
                        echo '<td>' . date('H:i', strtotime($patient_allergy_list[$i]["CreateDate"]))  . '</td>';
                        echo '<td>';
                        echo $patient_allergy_list[$i]["Name"];
                        echo '</td>';
                        echo '<td>';
                        echo $patient_allergy_list[$i]["Remarks"];
                        echo '</td>';

                        echo '<td style="text-align: left">';
                            if ($patient_allergy_list[$i]["Status"] == "Current") {
                                echo '<span class="fa fa-check"></span><span style="color: red;"> ' . $patient_allergy_list[$i]["Status"] . '</span>';
                            } else {
                                echo '<span class="fa fa-clock-o"></span><span style="color: green;"> ' . $patient_allergy_list[$i]["Status"] . '</span>';
                            }
                        echo '</td>';
                    echo '</tr>';
                }
            echo '</table>';
        echo '</div>';
        echo '</div>';
    }
?>		