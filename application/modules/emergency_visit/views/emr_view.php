<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/emr', $ID, $PID, $visit_info); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-10 ">
            <!--Patient Info-->
            <?php echo Modules::run('patient/banner_full', $PID) ?>
            <!--End Patient Info-->

            <!-- Contact Person -->
            <div class="panel panel-info">
                <div id="contact_person" style='padding: 5px;'><?php echo Modules::run('patient/contact_person', $PID) ?></div>
            </div>
            <!-- END Contact Person -->

            <!-- EMR INFO-->
            <?php echo Modules::run('emergency_visit/info', $ID); ?>
            <!-- END EMR INFO-->

            <!-- LAB-->
            <?php echo Modules::run('patient_lab_order/get_previous_lab', $PID, 'emergency_visit/view/' . $visit_info["EMRID"], "HTML"); ?>
            <!-- END LAB-->

            <!-- Radiology-->
            <?php echo Modules::run('patient_radiology_order/get_previous', $PID, 'emergency_visit/view/' . $visit_info["EMRID"], "HTML"); ?>
            <!-- END Radiology-->

            <!-- TREATMENT-->
            <?php echo Modules::run('treatment_order/get_previous_treatment_list', 'emr', $visit_info["EMRID"], 'emergency_visit/view/' . $visit_info["EMRID"], "HTML"); ?>
            <!-- END TREATMENT-->

            <!-- Injection-->
            <?php echo Modules::run('patient_injection/get_previous_injection', $PID, 'emergency_visit/view/' . $visit_info["EMRID"], "HTML"); ?>
            <!-- END Injection-->

            <!-- PRESCRIPTION-->
            <?php
            echo Modules::run('patient_prescription/get_previous_prescription', 'emr', $visit_info["EMRID"], 'emergency_visit/view/' . $visit_info["EMRID"], "HTML");
            ?>

            <!-- NOTES-->
            <?php echo Modules::run('patient_note/get_previous_notes_list', $PID, 'emr', $visit_info["EMRID"], 'emergency_visit/view/' . $visit_info["EMRID"], "HTML"); ?>
            <!-- END NOTES-->

            <!-- ALLERGY-->
            <?php echo Modules::run('patient_allergy/get_previous_allergy', $PID, 'emergency_visit/view/' . $visit_info["EMRID"], "HTML"); ?>
            <!-- END ALLERGY-->

            <!-- PAST HISTORY-->
            <?php echo Modules::run('patient_history/get_previous_history', $PID, 'emergency_visit/view/' . $visit_info["EMRID"], "HTML"); ?>
            <!-- END PAST HISTORY-->

            <!-- EXAMINATION-->
            <?php echo Modules::run('patient_examination/get_previous_exams', $PID, 'emergency_visit/view/' . $visit_info["EMRID"], "HTML"); ?>
            <!-- END EXAMINATION-->

             <!-- DIAGNOSIS-->
             <?php echo Modules::run('patient_diagnosis/get_previous_diagnosis', 'emr', $visit_info["EMRID"], 'emergency_visit/view/' . $visit_info["EMRID"], "HTML"); ?>
            <!-- END DIAGNOSIS-->

              <!-- PAST ANAMNESE-->
            <?php echo Modules::run('patient_anamnese/get_previous_anamnese', $PID, 'emergency_visit/view/' . $visit_info["EMRID"], "HTML"); ?>
              <!-- END ANAMNESE-->
        </div>
    </div>
</div>
