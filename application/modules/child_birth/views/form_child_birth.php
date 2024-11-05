<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading"><?=lang('Mother Information')?></div>
                <div class="panel-body"><?php echo Modules::run('patient/banner', $mother_id); ?></div>
                <div class="panel-heading"><?=lang('Child Information')?></div>
                <div class="panel-body"><?php echo Modules::run('patient/banner', $child_id); ?></div>
            </div>

            <?php
            $form_generator = new MY_Form(lang('Child Birth'));
            $form_generator->form_open_current_url();
            $form_generator->legend('Perinatal');
            $form_generator->input_date('*' . lang('Date of Birth'), 'dob', $default_dob, '');
            $form_generator->input('*' . lang('Weight') . ' (gr)', 'weight', $default_weight, '');
            $form_generator->dropdown('*' . lang('Place of Birth'), 'place_of_birth', array(
                'Em casa' => 'Em case',
                'A caminho' => 'A caminho',
                'Na maternidade' => 'Na maternidade'
            ), $default_place_of_birth, '');
            $form_generator->dropdown('*' . lang('Birth Type'), 'birth_type', array(
                'Normal' => 'Normal',
                'Ventosa' => 'Ventosa',
                'Cesariana' => 'Cesariana',
                'Pelvico' => 'Pelvico',
            ), $default_birth_type, '');
            $form_generator->input(lang('Birth Type Cause'), 'birth_type_cause', $default_birth_type_cause, '');
            $form_generator->input_with_unit('weeks', '*' . lang('Pregnant Time'), 'pregnant_time', $default_pregnant_time, '');
            $form_generator->dropdown('*' . lang('Apgar Index'), 'apgar_index', array(
                '01 min' => '01 min',
                '05 min' => '05 min',
            ), $default_apgar_index);

            $form_generator->input_with_unit('cm', '*' . lang('Cranial Perimeter'), 'cranial_perimeter', $default_cranial_perimeter, '');
            $form_generator->input_with_unit('cm', '*' . lang('Length'), 'length', $default_length, '');

            $form_generator->legend('Complicacoes');
            $form_generator->input(lang('Complaint at Pregnant Time'), 'complaint_preg_time', $default_complaint_preg_time, '');
            $form_generator->input(lang('Complaint at Birth Time'), 'complaint_birth_time', $default_complaint_birth_time, '');
            $form_generator->input(lang('Complaint at Neonatal Time'), 'complaint_neo_time', $default_complaint_neo_time, '');

            $form_generator->legend('Historia Familiar');
            $form_generator->checkboxes('Historia Familiar', 'history_checks', array(
                '1' => 'Doença alergica',
                '2' => 'Anemia crónica',
                '3' => 'Asma brônquica',
                '4' => 'Diabetes Mellitus',
                '5' => 'Doenças cardiacas',
                '6' => 'Hipertensão Arterial',
                '7' => 'Doença do Sistema',
                '8' => 'Nervoso Central',
                '9' => 'Tuberculose',
            ), $default_history_checks);
            $form_generator->text_area(lang('Other History'), 'history_other', $default_history_other, '');
            $form_generator->input(lang('Number of Alive'), 'history_n_alive', $default_history_n_alive, '');
            $form_generator->input(lang('Number of Dead'), 'history_n_dead', $default_history_n_dead, '');
            $form_generator->text_area(lang('Cause of Dead'), 'history_cause_dead', $default_history_cause_dead, '');
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, '');
            $form_generator->button_submit_reset($id);
            $form_generator->form_close();

            ?>
        </div>
    </div>
</div>
