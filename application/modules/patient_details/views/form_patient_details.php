<div class="container-fluid">
    <div class="row">

        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div>

        <div class="col-md-8 col-md-offset-1">
            <?php
            echo Modules::run('patient/banner', $pid);
            $form_generator = new MY_Form(lang('Socioeconomic Profile'));
            $form_generator->form_open_current_url();

            $school_level_dropdown = array(
                lang('None') => lang('None'),
                lang('Primary') => lang('Primary'),
                lang('Secondary') => lang('Secondary'),
                lang('University') => lang('University'),
            );

            $job_dropdown = array(
                lang('No job') => lang('No job'),
                lang('Informal work') => lang('Informal work'),
                lang('Formal work') => lang('Formal work'),
            );

            $profile_dropdown = array(
                "" => "",
                lang('Unemployed or Indigent') => lang('Unemployed or Indigent'),
                lang('Spouse of the Beneficiary') => lang('Spouse of the Beneficiary'),
                lang('Blood Donor') => lang('Blood Donor'),
                lang('Chronically Ill') => lang('Chronically Ill'),
                lang('Domestic Employee') => lang('Domestic Employee'),
                lang('Boarding School Student') => lang('Boarding School Student'),
                lang('Age 60 or over') => lang('Age 60 or over'),
                lang('Unable to Work') => lang('Unable to Work'),
                lang('Retired') => lang('Retired')
            );

            $form_generator->dropdown(lang('School Level'), 'patient_school_level', $school_level_dropdown, $patient_school_level);
            $form_generator->input(lang('Profession'), 'patient_profession', $patient_profession, lang('Profession'));


            echo '<div class="form-group">';
            echo '    <label class="col-sm-2 control-label">' . lang("Live") . ' </label>';
            echo '    <div class="col-sm-10">';
            echo '        <div class="input-group">';

            if ($patient_lives_alone == 1) {
                echo '<input type="radio" name="patient_lives_alone" value="1" checked>  ' . lang("Live Alone");
                echo '   <input type="radio" name="patient_lives_alone" value="0">  ' . lang("Lives in Aggregate");
            } elseif ($patient_lives_alone == 0) {
                echo '<input type="radio" name="patient_lives_alone" value="1">  ' . lang("Live Alone");
                echo '<input type="radio" name="patient_lives_alone" value="0" checked>  ' . lang("Lives in Aggregate");
            } else {
                echo '<input type="radio" name="patient_lives_alone" value="1">  ' . lang("Live Alone");
                echo '<input type="radio" name="patient_lives_alone" value="0">  ' . lang("Lives in Aggregate");
            }
            echo '        </div>';
            echo '    </div>';
            echo '</div>';

            $form_generator->dropdown(lang('Work'), 'patient_work', $job_dropdown, $patient_work);

            echo '<div class="form-group">';
            echo '    <label class="col-sm-3 control-label">' . lang("Head of household") . '?</label>';
            echo '    <div class="col-sm-6">';
            echo '    <div class="input-group">';
            if ($patient_head_household == 1) {
                echo '                <input type="radio" name="patient_head_household" value="1" checked> ' . lang("Yes");
                echo '                <input type="radio" name="patient_head_household" value="0" > ' . lang("No");
            } elseif ($patient_head_household == 0) {
                echo '                <input type="radio" name="patient_head_household" value="1" > ' . lang("Yes");
                echo '                <input type="radio" name="patient_head_household" value="0" checked> ' . lang("No");
            } else {
                echo '                <input type="radio" name="patient_head_household" value="1" > ' . lang("Yes");
                echo '                <input type="radio" name="patient_head_household" value="0" > ' . lang("No");
            }
            echo '        </div>';
            echo '    </div>';
            echo '</div>';

            $form_generator->input(lang('How many people do you live with') . "?", 'patient_people_live', $patient_people_live, lang('How many people do you live with'));

            echo '<div class="form-group">';
            echo '    <label class="col-sm-3 control-label">' . lang('Have a source of income') . '?</label>';
            echo '    <div class="col-sm-6">';
            echo '        <div class="input-group">';
            if ($patient_source_income == 1) {
                echo '                <input type="radio" name="patient_source_income" value="1" checked> ' . lang("Yes");
                echo '                <input type="radio" name="patient_source_income" value="0" > ' . lang("No");
            } elseif ($patient_source_income == 0) {
                echo '                <input type="radio" name="patient_source_income" value="1" > ' . lang("Yes");
                echo '                <input type="radio" name="patient_source_income" value="0" checked> ' . lang("No");
            } else {
                echo '                <input type="radio" name="patient_source_income" value="1" > ' . lang("Yes");
                echo '                <input type="radio" name="patient_source_income" value="0" > ' . lang("No");
            }
            echo '        </div>';
            echo '    </div>';
            echo '</div>';

            $form_generator->dropdown(lang('Profile'), 'patient_profile', $profile_dropdown, $patient_profile);


            echo '<hr>';
            $form_generator->button_submit_reset($pid);
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>