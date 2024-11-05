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
                $form_generator = new MY_Form(lang('patient_anamnese_psychological'));
                $form_generator->form_open_current_url();

                $form_generator->input('*' . lang('Main Complaint'), 'main_complaint', $default_main_complaint, 'Ex: Dor na coluna"' . lang('patient_symptoms') . '"');

                $form_generator->radio(
                    lang('Alguma vez foi atendido por um profissional de saúde mental?'),
                    'mental_care',
                    array('Sim' => lang('Sim'), 'Não' => lang('Não')),
                    $default_mental_care
                );

                $form_generator->radio(
                    lang('Existe alguém na sua família com algum problema de saúde mental?'),
                    'family_problem',
                    array('Sim' => lang('Sim'), 'Não' => lang('Não')),
                    $default_family_problem
                );

                $form_generator->input(lang('Especifique'), 'specify', $default_specify, ' ' . lang('Especifique') . '"');

                $form_generator->dropdown('*' . lang('frequencia_triste'), 'frequency_sad', [
                    'Nunca' => lang('Nunca'),
                    'Alguns dias' => lang('Alguns dias'),
                    'Mais da metade dos dias' => lang('Mais da metade dos dias'),
                    'Quase todos os dias' => lang('Quase todos os dias')
                ], $default_frequency_sad);

                $form_generator->dropdown('*' . lang('frequencia_ansioso'), 'frequency_anxious', [
                    'Nunca' => lang('Nunca'),
                    'Alguns dias' => lang('Alguns dias'),
                    'Mais da metade dos dias' => lang('Mais da metade dos dias'),
                    'Quase todos os dias' => lang('Quase todos os dias')
                ], $default_frequency_anxious);

                $form_generator->dropdown('*' . lang('frequencia_inquieto'), 'frequency_restless', [
                    'Nunca' => lang('Nunca'),
                    'Alguns dias' => lang('Alguns dias'),
                    'Mais da metade dos dias' => lang('Mais da metade dos dias'),
                    'Quase todos os dias' => lang('Quase todos os dias')
                ], $default_frequency_restless);

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
