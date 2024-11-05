<?php

echo Modules::run('template/header');
?>

<div class="container" style="width:95%;">
    <table class="table table-striped table-bordered table-condensed" border="1" style="background:#FFFFFF" width="90%"
           align="center">
        <tr>
            <td>

                    <h3><?php echo $this->session->userdata('Hospital'); ?></h3>
                    <hr>
                    <?php //print_r($patient_info); ?>

    Patient:<?php echo $patient_info["Personal_Title"] . ' ' . $patient_info["Personal_Used_Name"] . ' ' . $patient_info["Full_Name_Registered"]; ?>
    <br>
    HIN:<?php echo $patient_info["HIN"]; ?><br>
    Gender:<?php echo $patient_info["Gender"]; ?><br>
    Age:<?php echo $patient_info["Age"]["years"]; ?><br>
<hr>


    <h4>Patient related notes</h4>
    <hr>
    <?php
    if (isset($patient_notes_list)) {
        foreach ($patient_notes_list as $key => $value) {
            echo $value["CreateDate"] . ' : "' . $value["notes"] . '"  ' . $value["CreateUser"] . '<br>';
        }
    }
    ?>

    <hr>
    <h4>OPD related notes</h4>
    <hr>
    <?php
    if (isset($opd_notes_list)) {
        foreach ($opd_notes_list as $key => $value) {
            echo $value["CreateDate"] . ' : "' . $value["notes"] . '"  ' . $value["CreateUser"] . '<br>';
        }
    }
    ?>


</td>
</tr>
</table><a class="btn btn-default" href="javascript:window.open('','_self').close();">close</a>
</div>
