<?php if (Modules::run('permission/check_permission', 'blood_donation', 'edit')) { ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $pid);
            ?>
            <?php
            $form_generator = new MY_Form('Cadastrar Dador de Sangue');
            $form_generator->form_open_current_url();
            $form_generator->input('*' . lang('Donation Number'), 'donation_number', $default_donation_number, '');
            $form_generator->dropdown('Grupo Sanguíneo', 'gs',
                array(
                    'O' => 'O',
                    'A' => 'A',
                    'B' => 'B',     
                    'AB' => 'AB',
                ),
                $default_gs);
                $form_generator->dropdown('Rhesus', 'rhesus',
                array(
                    '+' => '+',
                    '-' => '-',
                ),
                $default_rhesus);
            $form_generator->dropdown(lang('Donation Type'), 'donation_type',
                array(
                    'Repositor' => 'Repositor',
                    'Palestrado' => 'Palestrado',
                    'Voluntário' => 'Voluntário'
                ),
                $default_donation_type);
            $form_generator->dropdown(lang('Prev Donation'), 'prev_donation', array('1' => 'Sim', '0' => 'Não'), $default_prev_donation);
            $form_generator->input(lang('Number of donation'), 'number_of_donation', $default_number_of_donation);
            $form_generator->input(lang('Prev Place of Donation'), 'prev_place_of_donation', $default_prev_place_of_donation);
            $form_generator->input_date(lang('Prev Donation Date'), 'prev_donation_date', $default_prev_donation_date);
            $form_generator->input(lang('Motivation'), 'motivation', $default_motivation);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, '');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Sim', '0' => 'N&atilde;o'), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    function render() {
        donation_type = $("#prev_donation").val();
        console.log(donation_type);
        if (donation_type == '0') {
            $(':input[name="number_of_donation"]').prop('disabled', true);
            $(':input[name="prev_place_of_donation"]').prop('disabled', true);
            $(':input[name="prev_donation_date"]').prop('disabled', true);
        }   else {
            $(':input[name="number_of_donation"]').prop('disabled', false);
            $(':input[name="prev_place_of_donation"]').prop('disabled', false);
            $(':input[name="prev_donation_date"]').prop('disabled', false);
        }
    }
    $(document).ready(function () {
        render();
        $("#prev_donation").change(function () {
            render();
        });
    });
</script>

<?php } ?>
