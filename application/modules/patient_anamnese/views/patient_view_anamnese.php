<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?php echo lang('patient_anamnese_psychological'); ?></title>
</head>
<body>

<div class="container-fluid">
    <div class="row" style="margin: 0px;">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div>

        <div class="col-md-10">
            <?php echo Modules::run('patient/banner', $pid); ?>

            <div>
            <?php
                if ($data["can_edit"] == 1) {
                    // Editable form (as you've implemented)
                    $form_generator = new MY_Form(lang('patient_anamnese_psychological'));
                    $form_generator->form_open_current_url();

                    // Primeira parte do formulário
                    $form_generator->input('*' . lang('Main Complaint'), 'main_complaint', isset($data["default_main_complaint"]) ? $data["default_main_complaint"] : '', 'Ex: Dor na coluna"' . lang('patient_symptoms') . '"');
                    
                    $form_generator->radio(
                        lang('Alguma vez foi atendido por um profissional de saúde mental?'),
                        'mental_care',
                        array('Sim' => lang('Sim'), 'Não' => lang('Não')),
                        isset($data['default_mental_care']) ? $data['default_mental_care'] : ''
                    );

                    $form_generator->radio(
                        lang('Existe alguém na sua família com algum problema de saúde mental?'),
                        'family_problem',
                        array('Sim' => lang('Sim'), 'Não' => lang('Não')),
                        isset($data['default_family_problem']) ? $data['default_family_problem'] : ''
                    );

                    $form_generator->input(lang('Especifique'), 'specify', isset($data['default_specify']) ? $data['default_specify'] : '', ' ' . lang('Especifique') . '"');
                    
                    // Dropdown fields
                    $form_generator->dropdown('*' . lang('frequencia_triste'), 'frequency_sad', [
                        'Nunca' => lang('Nunca'),
                        'Alguns dias' => lang('Alguns dias'),
                        'Mais da metade dos dias' => lang('Mais da metade dos dias'),
                        'Quase todos os dias' => lang('Quase todos os dias')
                    ], isset($data['default_frequency_sad']) ? $data['default_frequency_sad'] : '');
                    
                    $form_generator->dropdown('*' . lang('frequencia_ansioso'), 'frequency_anxious', [
                        'Nunca' => lang('Nunca'),
                        'Alguns dias' => lang('Alguns dias'),
                        'Mais da metade dos dias' => lang('Mais da metade dos dias'),
                        'Quase todos os dias' => lang('Quase todos os dias')
                    ], isset($data['default_frequency_anxious']) ? $data['default_frequency_anxious'] : '');
                    
                    $form_generator->dropdown('*' . lang('frequencia_inquieto'), 'frequency_restless', [
                        'Nunca' => lang('Nunca'),
                        'Alguns dias' => lang('Alguns dias'),
                        'Mais da metade dos dias' => lang('Mais da metade dos dias'),
                        'Quase todos os dias' => lang('Quase todos os dias')
                    ], isset($data['default_frequency_restless']) ? $data['default_frequency_restless'] : '');

                    // Segunda parte do formulário
                    $form_generator->dropdown('*' . lang('patient_alcohol_frequency'), 'frequency_alcohol', [
                        'Nunca' => lang('Nunca'),
                        '1 a 2 vezes por semana' => lang('one_or_two_doses'),
                        'Diariamente' => lang('daily')
                    ], isset($data["default_frequency_alcohol"]) ? $data["default_frequency_alcohol"] : '');

                    $form_generator->dropdown('*' . lang('patient_alcohol_quantity'), 'alcohol_quantity', [
                        '1 ou 2 doses' => lang('one_or_two_doses'),
                        'Mais de 3 doses' => lang('more_than_three_doses')
                    ], isset($data["default_alcohol_quantity"]) ? $data["default_alcohol_quantity"] : '');

                    $form_generator->radio(
                        lang('patient_alcohol_increase_covid'),
                        'increased_alcohol',
                        array('Não' => lang('No'), 'Sim' => lang('Yes')),
                        isset($data["default_increased_alcohol"]) ? $data["default_increased_alcohol"] : ''
                    );

                    $form_generator->radio(
                        lang('patient_thoughts_influenced'),
                        'external_influence',
                        array('Sim' => lang('Yes'), 'Não tem certeza' => lang('not_sure'), 'Não' => lang('No')),
                        isset($data["default_external_influence"]) ? $data["default_external_influence"] : ''
                    );

                    $form_generator->radio(
                        lang('patient_conspiracy'),
                        'conspiracy',
                        array('Sim' => lang('Yes'), 'Não tem certeza' => lang('not_sure'), 'Não' => lang('No')),
                        isset($data["default_conspiracy"]) ? $data["default_conspiracy"] : ''
                    );

                    $form_generator->radio(
                        lang('patient_hearing_voices'),
                        'hearing_voices',
                        array('Sim' => lang('Yes'), 'Não tem certeza' => lang('not_sure'), 'Não' => lang('No')),
                        isset($data["default_hearing_voices"]) ? $data["default_hearing_voices"] : ''
                    );

                    $form_generator->radio(
                        lang('patient_suicide_thoughts'),
                        'suicidal_thoughts',
                        array('Não' => lang('No'), 'Sim' => lang('Yes')),
                        isset($data["default_suicidal_thoughts"]) ? $data["default_suicidal_thoughts"] : ''
                    );

                    $form_generator->radio(
                        lang('patient_suicide_plans'),
                        'suicide_plan',
                        array('Não' => lang('No'), 'Sim' => lang('Yes')),
                        isset($data["default_suicide_plan"]) ? $data["default_suicide_plan"] : ''
                    );

                    $form_generator->text_area(lang('patient_brief_evaluation_result'), 'evaluation_result', isset($data["default_evaluation_result"]) ? $data["default_evaluation_result"] : '', ' ');
                    $form_generator->text_area(lang('patient_intervention'), 'intervention_done', isset($data["default_intervention_done"]) ? $data["default_intervention_done"] : '', ' ');

                    $form_generator->radio(
                        lang('patient_referred'),
                        'referred',
                        array('Não' => lang('No'), 'Sim' => lang('Yes')),
                        isset($data["default_referred"]) ? $data["default_referred"] : ''
                    );

                    echo '<div class="form-group">';
                    $form_generator->input(lang('patient_referred_to'), 'referred_to', isset($data["default_referred_to"]) ? $data["default_referred_to"] : '', ' ');
                    echo '</div>';

                    echo '<div class="form-group text-right">';
                    $form_generator->button_submit_reset(lang('submit'), lang('reset'));
                    echo '</div>';

                    $form_generator->form_close();
                } else {
                    // Non-editable form (disabled state)
                    $form_generator = new MY_Form(lang('patient_anamnese_psychological'));
                    $form_generator->form_open_current_url();

                    // Primeira parte do formulário
                    $form_generator->input('*' . lang('Main Complaint'), 'main_complaint', isset($data["default_main_complaint"]) ? $data["default_main_complaint"] : '', 'Ex: Dor na coluna"' . lang('patient_symptoms') . '"', ['disabled' => 'disabled']);
                    
                    $form_generator->radio(
                        lang('Alguma vez foi atendido por um profissional de saúde mental?'),
                        'mental_care',
                        array('Sim' => lang('Sim'), 'Não' => lang('Não')),
                        isset($data['default_mental_care']) ? $data['default_mental_care'] : '',
                        ['disabled' => 'disabled']
                    );

                    $form_generator->radio(
                        lang('Existe alguém na sua família com algum problema de saúde mental?'),
                        'family_problem',
                        array('Sim' => lang('Sim'), 'Não' => lang('Não')),
                        isset($data['default_family_problem']) ? $data['default_family_problem'] : '',
                        ['disabled' => 'disabled']
                    );

                    $form_generator->input(lang('Especifique'), 'specify', isset($data['default_specify']) ? $data['default_specify'] : '', ' ' . lang('Especifique') . '"', ['disabled' => 'disabled']);
                    
                    // Dropdown fields
                    $form_generator->dropdown('*' . lang('frequencia_triste'), 'frequency_sad', [
                        'Nunca' => lang('Nunca'),
                        'Alguns dias' => lang('Alguns dias'),
                        'Mais da metade dos dias' => lang('Mais da metade dos dias'),
                        'Quase todos os dias' => lang('Quase todos os dias')
                    ], isset($data['default_frequency_sad']) ? $data['default_frequency_sad'] : '', 'disabled');
                    
                    $form_generator->dropdown('*' . lang('frequencia_ansioso'), 'frequency_anxious', [
                        'Nunca' => lang('Nunca'),
                        'Alguns dias' => lang('Alguns dias'),
                        'Mais da metade dos dias' => lang('Mais da metade dos dias'),
                        'Quase todos os dias' => lang('Quase todos os dias')
                    ], isset($data['default_frequency_anxious']) ? $data['default_frequency_anxious'] : '', 'disabled');
                    
                    $form_generator->dropdown('*' . lang('frequencia_inquieto'), 'frequency_restless', [
                        'Nunca' => lang('Nunca'),
                        'Alguns dias' => lang('Alguns dias'),
                        'Mais da metade dos dias' => lang('Mais da metade dos dias'),
                        'Quase todos os dias' => lang('Quase todos os dias')
                    ], isset($data['default_frequency_restless']) ? $data['default_frequency_restless'] : '', 'disabled');

                    // Segunda parte do formulário
                    $form_generator->dropdown('*' . lang('patient_alcohol_frequency'), 'frequency_alcohol', [
                        'Nunca' => lang('Nunca'),
                        '1 a 2 vezes por semana' => lang('one_or_two_doses'),
                        'Diariamente' => lang('daily')
                    ], isset($data["default_frequency_alcohol"]) ? $data["default_frequency_alcohol"] : '','disabled');

                    $form_generator->dropdown('*' . lang('patient_alcohol_quantity'), 'alcohol_quantity', [
                        '1 ou 2 doses' => lang('one_or_two_doses'),
                        'Mais de 3 doses' => lang('more_than_three_doses')
                    ], isset($data["default_alcohol_quantity"]) ? $data["default_alcohol_quantity"] : '','disabled');

                    $form_generator->radio(
                        lang('patient_alcohol_increase_covid'),
                        'increased_alcohol',
                        array('Não' => lang('No'), 'Sim' => lang('Yes')),
                        isset($data["default_increased_alcohol"]) ? $data["default_increased_alcohol"] : '',
                        ['disabled' => 'disabled']
                    );

                    $form_generator->radio(
                        lang('patient_thoughts_influenced'),
                        'external_influence',
                        array('Sim' => lang('Yes'), 'Não tem certeza' => lang('not_sure'), 'Não' => lang('No')),
                        isset($data["default_external_influence"]) ? $data["default_external_influence"] : '',
                        ['disabled' => 'disabled']
                    );

                    $form_generator->radio(
                        lang('patient_conspiracy'),
                        'conspiracy',
                        array('Sim' => lang('Yes'), 'Não tem certeza' => lang('not_sure'), 'Não' => lang('No')),
                        isset($data["default_conspiracy"]) ? $data["default_conspiracy"] : '',
                        ['disabled' => 'disabled']
                    );

                    $form_generator->radio(
                        lang('patient_hearing_voices'),
                        'hearing_voices',
                        array('Sim' => lang('Yes'), 'Não tem certeza' => lang('not_sure'), 'Não' => lang('No')),
                        isset($data["default_hearing_voices"]) ? $data["default_hearing_voices"] : '',
                        ['disabled' => 'disabled']
                    );

                    $form_generator->radio(
                        lang('patient_suicide_thoughts'),
                        'suicidal_thoughts',
                        array('Não' => lang('No'), 'Sim' => lang('Yes')),
                        isset($data["default_suicidal_thoughts"]) ? $data["default_suicidal_thoughts"] : '',
                        ['disabled' => 'disabled']
                    );

                    $form_generator->radio(
                        lang('patient_suicide_plans'),
                        'suicide_plan',
                        array('Não' => lang('No'), 'Sim' => lang('Yes')),
                        isset($data["default_suicide_plan"]) ? $data["default_suicide_plan"] : '',
                        ['disabled' => 'disabled']
                    );

                    $form_generator->text_area(lang('patient_brief_evaluation_result'), 'evaluation_result', isset($data["default_evaluation_result"]) ? $data["default_evaluation_result"] : '', ' ', ['disabled' => 'disabled']);
                    $form_generator->text_area(lang('patient_intervention'), 'intervention_done', isset($data["default_intervention_done"]) ? $data["default_intervention_done"] : '', ' ', ['disabled' => 'disabled']);

                    $form_generator->radio(
                        lang('patient_referred'),
                        'referred',
                        array('Não' => lang('No'), 'Sim' => lang('Yes')),
                        isset($data["default_referred"]) ? $data["default_referred"] : '',
                        ['disabled' => 'disabled']
                    );

                    echo '<div class="form-group">';
                    $form_generator->input(lang('patient_referred_to'), 'referred_to', isset($data["default_referred_to"]) ? $data["default_referred_to"] : '', ' ', ['disabled' => 'disabled']);
                    echo '</div>';

                    // Close the form (no submit/reset buttons for disabled form)
                    $form_generator->form_close();
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>


