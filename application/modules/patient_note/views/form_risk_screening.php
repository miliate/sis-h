<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php
            echo Modules::run('leftmenu/patient', $pid, $ref_id);

            ?>
        </div>
        <div class="col-md-10">
            <?php
            $form_generator = new MY_Form(lang('Risk Screening'));

            ?>
            <?php
            echo Modules::run('patient/banner', $pid);

            $form_generator->form_open_current_url();
            ?>

            <div class="row">
                <div class="col-md-9 form-check-inline">
                    <?php
                    $options = array(
                        'yes' => lang('Yes'),
                        'no' => lang('No')
                    );

                    $options_aux = array(
                        'none' => lang('None'),
                        'stick' => lang('Stick'),
                        'furniture' => lang('Furniture')
                    );

                    $options_walk = array(
                        'normal' => lang('Normal'),
                        'weak' => lang('Weak'),
                        'compromised' => lang('Compromised')
                    );

                    $options_mental = array(
                        'oriented' => lang('Oriented'),
                        'capacity' => lang('Capacity')
                    );

                    $form_generator->radio(lang('Recent drop history'), 'yes_no_hist', $options, '', '', ''); ?>
                </div>

                <div class="col-md-3 form-inline">
                    <?php $form_generator->input('', 'history_classification', '', '', 'readonly'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 form-check-inline">

                    <?php $form_generator->radio(lang('Secondary Diagnose'), 'yes_no_diag', $options, '', '', ''); ?>
                </div>
                <div class="col-md-3 form-inline">
                    <?php $form_generator->input('', 'diagnose_classification', '', '', 'readonly'); ?>
                </div>
            </div>
            <div clss="row">
                <div class="col-md-9 form-check-inline">
                    <?php $form_generator->radio(lang('auxilar walk'), 'auxiliar_walk', $options_aux, '', '', ''); ?>
                </div>
                <div class="col-md-3 form-inline">
                    <?php $form_generator->input('', 'auxiliar_classification', '', '', 'readonly'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 form-check-inline">
                    <?php $form_generator->radio(lang('Therapy'), 'yes_no_therapy', $options, '', '', ''); ?>
                </div>
                <div class="col-md-3 form-inline">
                    <?php $form_generator->input('', 'therapy_classification', '', '', 'readonly'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 form-check-inline">
                    <?php $form_generator->radio(lang('Walk'), 'walk', $options_walk, '', '', ''); ?>
                </div>
                <div class="col-md-3 form-inline">
                    <?php $form_generator->input('', 'walk_classification', '', '', 'readonly'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 form-check-inline">
                    <?php $form_generator->radio(lang('Mental Status'), 'mental', $options_mental, '', '', ''); ?>
                </div>
                <div class="col-md-3 form-inline">
                    <?php $form_generator->input('', 'mental_classification', '', '', 'readonly'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 form-check-inline">
                    <strong>
                        <?php $form_generator->input(lang('Total'), 'total', '', '', 'readonly');
                        $form_generator->input(lang('Classification'), 'classification', '', '', 'readonly' . ',' . '');
                        ?>
                    </strong>
                </div>

            </div>


            <div class="row">
                <?php
                $form_generator->button_submit_reset();
                $form_generator->form_close();
                ?>
            </div>
        </div>

    </div>

</div>

<script>
    $(document).ready(function() {
        $("input[name='yes_no_hist']:radio").change(function() {
            if ($(this).val() == 'yes') {

                $('#history_classification').val(25);

            } else if ($(this).val() == 'no') {

                $('#history_classification').val(0);
            }

            var hist = parseInt($('#history_classification').val() != '' ? $('#history_classification').val() : 0);
            var dig = parseInt($('#diagnose_classification').val() != '' ? $('#diagnose_classification').val() : 0);
            var aux = parseInt($('#auxiliar_classification').val() != '' ? $('#auxiliar_classification').val() : 0);
            var ther = parseInt($('#therapy_classification').val() != '' ? $('#therapy_classification').val() : 0);
            var walk = parseInt($('#walk_classification').val() != '' ? $('#walk_classification').val() : 0);
            var ment = parseInt($('#mental_classification').val() != '' ? $('#mental_classification').val() : 0);
            var total_classification = hist + dig + aux + ther + walk + ment;
            $('#total').val(total_classification);

            if ($('#total').val() == 0) {
                $("#total").attr("style", "background-color:  #198754;");
                $('#classification').val('');
            } else if ($('#total').val() > 1 && $('#total').val() < 41) {
                $('#classification').val('Risco Médio');
                $("#total").attr("style", "background-color: #ffc107;");
            } else if ($('#total').val() >= 41 && $('#total').val() <= 51) {
                $("#total").attr("style", "background-color: #fd7e14;");
                $('#classification').val('Risco Elevado');
            } else if ($('#total').val() > 51) {
                $("#total").attr("style", "background-color: #dc3545;");
                $('#classification').val('Risco Muito Elevado');
            }
        });

        $("input[name='yes_no_diag']:radio").change(function() {
            if ($(this).val() == 'yes') {
                $('#diagnose_classification').val(15);

            } else if ($(this).val() == 'no') {
                $('#diagnose_classification').val(0);
            }

            var hist = parseInt($('#history_classification').val() != '' ? $('#history_classification').val() : 0);
            var dig = parseInt($('#diagnose_classification').val() != '' ? $('#diagnose_classification').val() : 0);
            var aux = parseInt($('#auxiliar_classification').val() != '' ? $('#auxiliar_classification').val() : 0);
            var ther = parseInt($('#therapy_classification').val() != '' ? $('#therapy_classification').val() : 0);
            var walk = parseInt($('#walk_classification').val() != '' ? $('#walk_classification').val() : 0);
            var ment = parseInt($('#mental_classification').val() != '' ? $('#mental_classification').val() : 0);
            var total_classification = hist + dig + aux + ther + walk + ment;
            $('#total').val(total_classification);

            if ($('#total').val() == 0) {
                $("#total").attr("style", "background-color:  #198754;");
                $('#classification').val('');
            } else if ($('#total').val() > 1 && $('#total').val() < 41) {
                $("#total").attr("style", "background-color: #ffc107;");
                $('#classification').val('Risco Médio');
            } else if (($('#total').val() >= 41) && ($('#total').val() <= 51)) {
                $('#classification').val('Risco Elevado');
                $("#total").attr("style", "background-color: #fd7e14;");
            } else if ($('#total').val() > 51) {
                $('#classification').val('Risco Muito Elevado');
                $("#total").attr("style", "background-color: #dc3545;");
            }

        });

        $("input[name='auxiliar_walk']:radio").change(function() {
            if ($(this).val() == 'none') {
                $('#auxiliar_classification').val(0);

            } else if ($(this).val() == 'stick') {
                $('#auxiliar_classification').val(15);
            } else if ($(this).val() == 'furniture') {
                $('#auxiliar_classification').val(30);
            }


            var hist = parseInt($('#history_classification').val() != '' ? $('#history_classification').val() : 0);
            var dig = parseInt($('#diagnose_classification').val() != '' ? $('#diagnose_classification').val() : 0);
            var aux = parseInt($('#auxiliar_classification').val() != '' ? $('#auxiliar_classification').val() : 0);
            var ther = parseInt($('#therapy_classification').val() != '' ? $('#therapy_classification').val() : 0);
            var walk = parseInt($('#walk_classification').val() != '' ? $('#walk_classification').val() : 0);
            var ment = parseInt($('#mental_classification').val() != '' ? $('#mental_classification').val() : 0);
            var total_classification = hist + dig + aux + ther + walk + ment;
            $('#total').val(total_classification);

            if ($('#total').val() == 0) {
                $("#total").attr("style", "background-color:  #198754;");
                $('#classification').val('');
            } else if ($('#total').val() > 1 && $('#total').val() < 41) {
                $("#total").attr("style", "background-color: #ffc107;");
                $('#classification').val('Risco Médio');
            } else if (($('#total').val() >= 41) && ($('#total').val() <= 51)) {
                $("#total").attr("style", "background-color: #fd7e14;");
                $('#classification').val('Risco Elevado');
            } else if ($('#total').val() > 51) {
                $("#total").attr("style", "background-color: #dc3545;");
                $('#classification').val('Risco Muito Elevado');
            }
        });

        $("input[name='yes_no_therapy']:radio").change(function() {
            if ($(this).val() == 'yes') {
                $('#therapy_classification').val(20);

            } else if ($(this).val() == 'no') {
                $('#therapy_classification').val(0);
            }

            var hist = parseInt($('#history_classification').val() != '' ? $('#history_classification').val() : 0);
            var dig = parseInt($('#diagnose_classification').val() != '' ? $('#diagnose_classification').val() : 0);
            var aux = parseInt($('#auxiliar_classification').val() != '' ? $('#auxiliar_classification').val() : 0);
            var ther = parseInt($('#therapy_classification').val() != '' ? $('#therapy_classification').val() : 0);
            var walk = parseInt($('#walk_classification').val() != '' ? $('#walk_classification').val() : 0);
            var ment = parseInt($('#mental_classification').val() != '' ? $('#mental_classification').val() : 0);
            var total_classification = hist + dig + aux + ther + walk + ment;
            $('#total').val(total_classification);

            if ($('#total').val() == 0) {
                $("#total").attr("style", "background-color:  #198754;");
                $('#classification').val('');
            } else if ($('#total').val() > 1 && $('#total').val() < 41) {
                $("#total").attr("style", "background-color: #ffc107;");
                $('#classification').val('Risco Médio');
            } else if (($('#total').val() >= 41) && ($('#total').val() <= 51)) {
                $("#total").attr("style", "background-color: #fd7e14;");
                $('#classification').val('Risco Elevado');
            } else if ($('#total').val() > 51) {
                $("#total").attr("style", "background-color: #dc3545;");
                $('#classification').val('Risco Muito Elevado');
            }
        });

        $("input[name='walk']:radio").change(function() {
            if ($(this).val() == 'normal') {
                $('#walk_classification').val(0);

            } else if ($(this).val() == 'weak') {
                $('#walk_classification').val(10);
            } else if ($(this).val() == 'compromised') {
                $('#walk_classification').val(20);
            }


            var hist = parseInt($('#history_classification').val() != '' ? $('#history_classification').val() : 0);
            var dig = parseInt($('#diagnose_classification').val() != '' ? $('#diagnose_classification').val() : 0);
            var aux = parseInt($('#auxiliar_classification').val() != '' ? $('#auxiliar_classification').val() : 0);
            var ther = parseInt($('#therapy_classification').val() != '' ? $('#therapy_classification').val() : 0);
            var walk = parseInt($('#walk_classification').val() != '' ? $('#walk_classification').val() : 0);
            var ment = parseInt($('#mental_classification').val() != '' ? $('#mental_classification').val() : 0);
            var total_classification = hist + dig + aux + ther + walk + ment;
            $('#total').val(total_classification);

            if ($('#total').val() == 0) {
                $("#total").attr("style", "background-color:  #198754;");
                $('#classification').val('');
            } else if ($('#total').val() > 1 && $('#total').val() < 41) {
                $("#total").attr("style", "background-color: #ffc107;");
                $('#classification').val('Risco Médio');
            } else if (($('#total').val() >= 41) && ($('#total').val() <= 51)) {
                $("#total").attr("style", "background-color: #fd7e14;");
                $('#classification').val('Risco Elevado');
            } else if ($('#total').val() > 51) {
                $("#total").attr("style", "background-color: #dc3545;");
                $('#classification').val('Risco Muito Elevado');

            }
        });

        $("input[name='mental']:radio").change(function() {
            if ($(this).val() == 'oriented') {

                $('#mental_classification').val(0);

            } else if ($(this).val() == 'capacity') {

                $('#mental_classification').val(15);
            }

            var hist = parseInt($('#history_classification').val() != '' ? $('#history_classification').val() : 0);
            var dig = parseInt($('#diagnose_classification').val() != '' ? $('#diagnose_classification').val() : 0);
            var aux = parseInt($('#auxiliar_classification').val() != '' ? $('#auxiliar_classification').val() : 0);
            var ther = parseInt($('#therapy_classification').val() != '' ? $('#therapy_classification').val() : 0);
            var walk = parseInt($('#walk_classification').val() != '' ? $('#walk_classification').val() : 0);
            var ment = parseInt($('#mental_classification').val() != '' ? $('#mental_classification').val() : 0);
            var total_classification = hist + dig + aux + ther + walk + ment;
            $('#total').val(total_classification);

            if ($('#total').val() == 0) {
                $("#total").attr("style", "background-color:  #198754;");
                $('#classification').val('');
            } else if ($('#total').val() > 1 && $('#total').val() < 41) {
                $("#total").attr("style", "background-color: #ffc107;");
                $('#classification').val('Risco Médio');
            } else if (($('#total').val() >= 41) && ($('#total').val() <= 51)) {
                $("#total").attr("style", "background-color: #fd7e14;");
                $('#classification').val('Risco Elevado');
            } else if ($('#total').val() > 51) {
                $("#total").attr("style", "background-color: #dc3545;");
                $('#classification').val('Risco Muito Elevado');
            }

        });

    });
</script>