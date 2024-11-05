<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div>
        <div class="col-md-8 col-md-offset-1">

            <?php

            $form_generator = new MY_Form(lang('Braden Scale'));
            $form_generator->form_open_current_url();

            $sensory_perception = [
                1 => lang('Totally limited'),
                2 => lang('Very limited'),
                3 => lang('Slightly limited'),
                4 => lang('No limitation')
            ];

            $moisture = [
                1 => lang('Excessive'),
                2 => lang('Much'),
                3 => lang('Occasional'),
                4 => lang('Rare')
            ];

            $activity = [
                1 => lang('Bedridden'),
                2 => lang('Confined to chair'),
                3 => lang('Occasionally ambulatory'),
                4 => lang('Frequently ambulatory')
            ];

            $mobility = [
                1 => lang('Immobile'),
                2 => lang('Very limited'),
                3 => lang('Slight limitation'),
                4 => lang('No limitation')
            ];

            $nutrition = [
                1 => lang('Deficient'),
                2 => lang('Inadequate'),
                3 => lang('Adequate'),
                4 => lang('Excellent')
            ];

            $friction_shear = [
                1 => lang('Problem'),
                2 => lang('Potential problem'),
                3 => lang('No problem')
            ];

            $Classification = [
                1 => lang('No risk'),
                2 => lang('Low risk'),
                3 => lang('Moderate risk'),
                4 => lang('High risk'),
                5 => lang('Very high risk')
            ];

            ?>

            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <label for=""><?php echo lang('Score') . ':' ?></label>
                    <label><span id="score"></span></label>
                </div>
                <div class="col-md-5">
                    <label for=""><?php echo lang('Classification') . ':' ?></label>
                    <label><span id="classification"></span></label>
                </div>
            </div>

            <hr>

            <div class="row">

                <div class="col-md-5">
                    <?php $form_generator->radio(lang('Sensory Perception'), 'sensory_perception', $sensory_perception, '', '', ''); ?>
                </div>
                <div class="col-md-5 col-md-offset-1">
                    <?php $form_generator->radio(lang('Moisture'), 'moisture', $moisture, '', '', ''); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5">
                    <?php $form_generator->radio(lang('Activity'), 'activity', $activity, '', '', ''); ?>
                </div>
                <div class="col-md-5 col-md-offset-1">
                    <?php $form_generator->radio(lang('Mobility'), 'mobility', $mobility, '', '', ''); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5">
                    <?php $form_generator->radio(lang('Nutrition'), 'nutrition', $nutrition, '', '', ''); ?>
                </div>
                <div class="col-md-5 col-md-offset-1 ">
                    <?php $form_generator->radio(lang('Friction and Shear'), 'friction_shear', $friction_shear, '', '', ''); ?>
                </div>
            </div>

            <input type="hidden" name="finalScore">
            <input type="hidden" name="finalClassification">

            <hr>

            <?php

            $form_generator->button_submit_reset();
            $form_generator->form_close();

            ?>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        let counts = {
            count_sensory: 0,
            count_moisture: 0,
            count_activity: 0,
            count_mobility: 0,
            count_nutrition: 0,
            count_friction: 0
        };
        let count_total = 0;

        $('input[name="sensory_perception"]').on('change', function() {
            let valor = parseInt($(this).val()) || 0;
            atualizarTotal("sensory_perception", "count_sensory", valor);
        });
        $('input[name="moisture"]').on('change', function() {
            let valor = parseInt($(this).val()) || 0;
            atualizarTotal("moisture", "count_moisture", valor);
        });
        $('input[name="activity"]').on('change', function() {
            let valor = parseInt($(this).val()) || 0;
            atualizarTotal("activity", "count_activity", valor);
        });
        $('input[name="mobility"]').on('change', function() {
            let valor = parseInt($(this).val()) || 0;
            atualizarTotal("activity", "count_mobility", valor);
        });
        $('input[name="nutrition"]').on('change', function() {
            let valor = parseInt($(this).val()) || 0;
            atualizarTotal("nutrition", "count_nutrition", valor);
        });
        $('input[name="friction_shear"]').on('change', function() {
            let valor = parseInt($(this).val()) || 0;
            atualizarTotal("friction_shear", "count_friction", valor);
        });

        function atualizarTotal(inputName, countKey, valor) {
            count_total -= counts[countKey];
            counts[countKey] = valor;
            count_total += counts[countKey];
            document.getElementById("score").textContent = count_total;
            document.getElementById("classification").textContent = getClassification(count_total);
        }

        function getClassification(score) {
            let Standards;
            if (score >= 19 && score <= 23) {
                Standards = "<?php echo lang('No Risk') ?>";
            } else if (score >= 15 && score <= 18) {
                Standards = "<?php echo lang('Low Risk') ?>";;
            } else if (score >= 13 && score <= 14) {
                Standards = "<?php echo lang('Moderate Risk') ?>";;
            } else if (score >= 10 && score <= 12) {
                Standards = "<?php echo lang('High Risk') ?>";;
            } else if (score <= 9) {
                Standards = "<?php echo lang('Very High Risk') ?>";;
            } else {
                Standards = Classification['6'];
            }
            return Standards;
        }

        $('#resetButton').on('click', function() {
            $('#score').text(''); // Limpa o conteúdo da label "score"
            $('#classification').text(''); // Limpa o conteúdo da label "classification"
        });

    });
</script>