<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/admission', $admission, $PID); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-10 ">
            <?php echo Modules::run('patient/banner_full', $PID) ?>
            <?php echo Modules::run('admission/info', $admission["ADMID"]) ?>
            <?php echo Modules::run('admission/get_previous_ward_transfer', $admission["ADMID"], 'admission/view/' . $admission["ADMID"], "HTML"); ?>

            <!-- LAB-->
            <?php echo Modules::run('patient_lab_order/get_previous_lab', $PID, 'admission/view/' . $admission["ADMID"], "HTML"); ?>
            <!-- END LAB-->

            <!-- Radiology-->
            <?php echo Modules::run('patient_radiology_order/get_previous', $PID, 'emergency_visit/view/' . $admission["ADMID"], "HTML"); ?>
            <!-- END Radiology-->

            <?php echo Modules::run('patient_injection/get_previous_injection', $PID, 'admission/view/' . $admission["ADMID"], "HTML"); ?>

            <?php echo Modules::run('treatment_order/get_previous_treatment_list', 'adm', $admission["ADMID"], 'admission/view/' . $admission["ADMID"], "HTML"); ?>

            <!-- PRESCRIPTION-->
            <?php echo Modules::run('patient_prescription/get_previous_prescription', 'adm', $admission["ADMID"], 'cardex/view/' . $admission["ADMID"], "HTML"); ?>
            <!-- END PRESCRIPTION-->

            <!-- PAST HISTORY-->
            <?php echo Modules::run('patient_note/get_previous_notes_list', $PID, 'adm', $admission["ADMID"], 'admission/view/' . $admission["ADMID"], "HTML"); ?>
            <!-- END PAST HISTORY-->

            <!-- ALLERGY-->
            <?php echo Modules::run('patient_allergy/get_previous_allergy', $PID, 'admission/view/' . $admission["ADMID"], "HTML"); ?>
            <!-- END ALLERGY-->

            <!-- PAST HISTORY-->
            <!--        --><?php //echo Modules::run('patient/get_previous_history', $patient_info["PID"], 'admission/view/' . $admission_info["ADMID"], "HTML"); ?>
            <?php echo Modules::run('patient_history/get_previous_history', $PID, 'admission/view/' . $admission["ADMID"], "HTML"); ?>
            <!-- END PAST HISTORY-->

            <!-- EXAMINATION-->
            <?php echo Modules::run('patient_examination/get_previous_exams', $PID, 'admission/view/' . $admission["ADMID"], "HTML"); ?>
            <!-- END EXAMINATION-->



        </div>
    </div>
</div>
