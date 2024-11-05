<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php
            echo Modules::run('leftmenu/patient', $pid, $ref_id);

            ?>
        </div>
        <div class="col-md-10 ">

            <?php
            $title = array(
                'Care' => 'Nursing Cares',
                'Procedure' => 'Nursing Procedures',
                'Treatment' => 'Treatment'
            );

            $form_generator = new MY_Form(lang($title[$treatment_type]));
            $form_generator->get_nursing_notes_tab($pid, $ref_id) ?>
            <?php
            echo Modules::run('patient/banner', $pid);


            $form_generator->form_open_current_url();
            ?>
            <div class="row">


                <div class="row">
                    <div class="col-md-5 form-inline ">
                        <label for="start_date" class="col-sm-3 control-label"><?php echo lang('Start Date Time'); ?></label>
                        <?php $form_generator->input_date_and_time('', 'start_date', $default_date,  '', $extra = '') ?>

                    </div>
                    <div class=" col-sm-6 ">
                        <div class="form-group ">
                            <label for="treatment" class="col-sm-2 control-label"><?php echo lang('Name'); ?></label>

                            <div class="col-sm-10 ">
                                <select class="form-control" id="cares" name="cares">
                                    <?php foreach ($nursing_cares as $cares): ?>
                                        <option value="<?= htmlspecialchars($cares['TREATMENTID']) ?>">
                                            <?= htmlspecialchars($cares['Treatment']) ?>
                                        <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-5 form-inline">
                        <label for="end_date" class="col-sm-3 control-label"><?php echo lang('End Date Time'); ?></label>
                        <?php $form_generator->input_date_and_time('', 'end_date', $default_date,  '', $extra = '') ?>

                    </div>

                    <div class=" col-sm-6 ">

                        <div class=" form-group ">
                            <label for=" remarks" class="col-sm-2 control-label"><?php echo lang('Remarks'); ?></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="remarks" name="remarks"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1" style="padding-left: 30px;">
                        <button type="button" class="btn btn-sm btn-primary" id="addCares"><span class="glyphicon glyphicon-plus"></span></button>
                    </div>
                    <input type="hidden" id="selectedCares" name="selectedCares" value="[]">
                </div>
            </div>

            <div id="div_provided_cares" class="col-md-12" style="margin-top: 15px; display:none">
                <table class="table " id="tb_provided_cares">
                    <thead>
                        <tr>
                            <th><?php echo lang('Treatment Name'); ?></th>
                            <th><?php echo lang('Start Date'); ?></th>
                            <th><?php echo lang('End Date'); ?></th>
                            <th><?php echo lang('Remarks'); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="row">


                    <div class="col-md-6 col-md-offset-7" style="padding: 30px;">
                        <?php
                        $form_generator->button_submit_reset();
                        $form_generator->form_close();
                        ?>

                    </div>
                </div>
            </div>

            <?php
            echo Modules::run('template/footer');
            ?>


            <script>
                $(document).ready(function() {
                    var selectedCares = [];

                    $('#addCares').click(function() {

                        var careId = $('#cares').val();
                        var careName = $('#cares option:selected').text();
                        var remarks = $('#remarks').val();
                        var start_date = $('#start_date').val();
                        var start_time = $('#start_date_time').val();
                        var end_date = $('#end_date').val();
                        var end_time = $('#end_date_time').val();


                        if (careId) {
                            var nursing_care = {
                                TreatmentID: careId,
                                Remarks_Nurse: remarks
                            };

                            selectedCares.push(nursing_care);

                            $('#div_provided_cares').fadeIn('3000');

                            $('#tb_provided_cares').find('tbody').append($('<tr>'))
                                .append($('<td>').append(careName))
                                .append($('<td>').append(start_date + " " + start_time))
                                .append($('<td>').append(end_date + " " + end_time))
                                .append($('<td>').append(remarks))
                            $('#selectedCares').val(JSON.stringify(selectedCares));

                            // Clear the input fields
                            $('#remarks').val('');
                        } else {
                            $("#message1").fadeIn();
                        }
                    });

                    $('form').submit(function(event) {
                        if (selectedCares.length === 0) {
                            $("#message2").fadeIn();
                            event.preventDefault();
                        }
                    });
                });
            </script>