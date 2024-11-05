<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?php echo lang('patient_anamnese_psychological_part2'); ?></title>
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
                $form_generator = new MY_Form(lang('patient_anamnese_psychological'));
                $form_generator->form_open_current_url();

                $form_generator->dropdown(
                    '*' . lang('patient_alcohol_frequency'),
                    'frequency_alcohol',
                    [
                        'Nunca' => lang('Nunca'),
                        '1 a 2 vezes por semana' => lang('one_or_two_doses'),
                        'Diariamente' => lang('daily')
                    ],
                    $data["default_frequency_alcohol"]
                );

                $form_generator->dropdown(
                    '*' . lang('patient_alcohol_quantity'),
                    'alcohol_quantity',
                    [
                        '1 ou 2 doses' => lang('one_or_two_doses'),
                        'Mais de 3 doses' => lang('more_than_three_doses')
                    ],
                    $data["default_alcohol_quantity"]
                );

                $form_generator->radio(
                    lang('patient_alcohol_increase_covid'),
                    'increased_alcohol',
                    array('Não' => lang('No'), 'Sim' => lang('Yes')),
                    $data["default_increased_alcohol"]
                );

                $form_generator->radio(
                    lang('patient_thoughts_influenced'),
                    'external_influence',
                    array('Sim' => lang('Yes'), 'Não tem certeza' => lang('not_sure'), 'Não' => lang('No')),
                    $data["default_external_influence"]
                );

                $form_generator->radio(
                    lang('patient_conspiracy'),
                    'conspiracy',
                    array('Sim' => lang('Yes'), 'Não tem certeza' => lang('not_sure'), 'Não' => lang('No')),
                    $data["default_conspiracy"]
                );

                $form_generator->radio(
                    lang('patient_hearing_voices'),
                    'hearing_voices',
                    array('Sim' => lang('Yes'), 'Não tem certeza' => lang('not_sure'), 'Não' => lang('No')),
                    $data["default_hearing_voices"]
                );

                $form_generator->radio(
                    lang('patient_suicide_thoughts'),
                    'suicidal_thoughts',
                    array('Não' => lang('No'), 'Sim' => lang('Yes')),
                    $data["default_suicidal_thoughts"]
                );

                $form_generator->radio(
                    lang('patient_suicide_plans'),
                    'suicide_plan',
                    array('Não' => lang('No'), 'Sim' => lang('Yes')),
                    $data["default_suicide_plan"]
                );

                $form_generator->text_area(
                    lang('patient_brief_evaluation_result'),
                    'evaluation_result',
                    $data["default_evaluation_result"],
                    ' '
                );

                $form_generator->text_area(
                    lang('patient_intervention'),
                    'intervention_done',
                    $data["default_intervention_done"],
                    ' '
                );

                $form_generator->radio(
                    lang('patient_referred'),
                    'referred',
                    array('Não' => lang('No'), 'Sim' => lang('Yes')),
                    $data["default_referred"]
                );

                echo '<div class="form-group">';
                $form_generator->input(lang('patient_referred_to'), 'referred_to', $data["default_referred_to"], ' ');
                echo '</div>';

                echo '<div class="form-group text-right">';
                $form_generator->button_submit_reset(lang('submit'), lang('reset'));
                echo '</div>';

                $form_generator->form_close();
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>