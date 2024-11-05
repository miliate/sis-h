<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Tabela de Dispense Drug -->
            <div class="panel panel-info">
                <!-- Default panel contents -->
                <div class="panel-heading"><?php echo lang('Dispense Drug') ?></div>
                <div id="message5" class="alert alert-danger" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span id="message_text"><?php echo lang('Please insert all data'); ?></span>
                </div>
                <div id="success_message" class="alert alert-success" style="display:none;"></div>
                <div id="message1" class="alert alert-danger" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span id="message_text"><?php echo lang('Please insert all data'); ?></span>
                </div>
                <div id="message2" class="alert alert-danger" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span id="message_text"><?php echo lang('The quantity for all medications must be greater than zero'); ?></span>
                </div>
                <div id="message3" class="alert alert-danger" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span id="message_text"><?php echo lang('The Duration of Treatment (days) must be greater than zero'); ?></span>
                </div>
                <div id="message4" class="alert alert-danger" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span id="message_text"><?php echo lang('Medicine with empty stock'); ?></span>
                </div>


                <form id="dispense_form" role="form" action="<?php echo site_url('patient_external_prescription/dispense_drug'); ?>" method="post">
                    <!-- Dados do Paciente -->
                    <div class="patient-info">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="patient_name"><?php echo '*' . lang('Patient Name'); ?></label>
                                <input class="form-control form-control-sm" type="text" name="patient_name" id="patient_name" value="<?php echo set_value('patient_name'); ?>" />
                                <?php echo form_error('patient_name', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="patient_nid"><?php echo '*' . lang('NID'); ?></label>
                                <input class="form-control form-control-sm" type="text" name="patient_nid" id="patient_nid" value="<?php echo set_value('patient_nid'); ?>" />
                                <?php echo form_error('patient_nid', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="health_unit"><?php echo '*' . lang('Health Unit'); ?></label>
                                <?php echo Modules::run('patient_external_prescription/view_select_health_unit'); ?>
                                <?php echo form_error('health_unit', '<div class="text-danger">', '</div>'); ?>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="patient_category"><?php echo '*' . lang('Patient Category'); ?></label>
                                <?php echo Modules::run('patient_external_prescription/view_select_patient_category'); ?>
                                <?php echo form_error('patient_category', '<div class="text-danger">', '</div>'); ?>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="amount_paid"><?php echo '*' . lang('Amount Paid'); ?></label>
                                <div class="input-group">
                                    <input class="form-control form-control-sm" type="number" step="0.5" name="amount_paid" id="amount_paid" value="<?php echo set_value('amount_paid'); ?>" />
                                    <span class="input-group-addon">MT</span>
                                </div>
                                <?php echo form_error('amount_paid', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>
                        <br />

                        <br /> <br />

                    </div>


                    <!-- Tabs -->
                    <div>

                        <ul class="nav nav-tabs">
                            <li class="nav-item " style="background: lightgrey;">
                                <a class="active " style="color: Black"> <?= lang('All Medicines') ?></a>
                            </li>
                        </ul>



                        <table class="table input-xs">
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th width="150px"><?php echo '*' . lang('Name') ?></th>
                                    <!-- <th><?php echo lang('Pharmaceutical Form') ?></th>                                 -->
                                    <th><?php echo '*' . lang('Route Administration') ?></th>
                                    <th><?php echo '*' . lang('Dosage') ?></th>
                                    <th width="100px"><?php echo '*' . lang('Posology') ?></th>
                                    <th width="200px"><?php echo '*' . lang('Duration of treatment') ?></th>
                                    <th width="150px"><?php echo '*' . lang('Quantity prescribed') ?></th>
                                    <!-- <th><?php echo '*' . lang('Period') ?></th> -->
                                    <th></th>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td><?php echo Modules::run('drug/view_select_drug') ?></td>
                                    <td style="width:140px;"><?php echo Modules::run('drug/view_route_administration') ?></td>
                                    <td><?php echo '<input type="text" name="dose" id="dose" class="form-control form-control-sm" ">' ?></td>

                                    <td style="width:100px;"><?php echo Modules::run('drug/view_select_frequency') ?></td>
                                    <!-- <td><?php echo Modules::run('drug/view_select_period') ?></td> -->
                                    <td><?php echo '<input type="number" name="tempo_total" id="tempo_total" class="form-control form-control-sm">' ?></td>
                                    <td><?php echo '<input type="number" name="dose_total" id="dose_total" class="form-control form-control-sm""> ' ?></td>
                                    <td style="vertical-align: middle;">


                                        <button type="button" class="btn btn-info btn-sm" id="add_drug_button" onclick="add_drug()">
                                            <span class="glyphicon glyphicon-plus-sign"></span>
                                        </button>

                                    </td>
                                </tr>

                            </tbody>
                        </table>




                        <div class="panel panel-default" id="prescription_section" style="display: none;">
                            <!-- Table -->
                            <table class="table input-xs" id="table_drug" style="margin-bottom: 0px; ">
                                <thead>
                                    <tr>
                                        <th><b>#</b></th>
                                        <th><b><?php echo lang('FNM') ?></b></th>
                                        <th><b><?php echo lang('Name') ?></b></th>
                                        <th><b><?php echo lang('Dosage') ?></b></th>
                                        <th><b><?php echo lang('Route Administration') ?></b></th>
                                        <th><b><?php echo lang('Posology') ?></b></th>
                                        <th><b><?php echo lang('Duration of treatment') ?></b></th>
                                        <!-- <th><b><?php echo lang('Total Dosage') ?></b></th> -->
                                        <th><b><?php echo lang('Quantity prescribed') ?></b></th>
                                        <th><b><?php echo lang('Batch') ?></b></th>
                                        <th><b><?php echo lang('Confirm Drug') ?></b></th>
                                        <th><b><?php echo lang('Note') ?></b></th>
                                        <th><b><?php echo lang('Stock') ?></b></th>
                                        <th><b><?php echo lang('Actions') ?></b></th> <!-- Coluna para o botão de ação -->
                                    </tr>
                                </thead>
                                <tbody id="tbody_drug">

                                </tbody>
                            </table>
                            <table style="width: 100%;">
                                <tbody>
                                    <tr>

                                        <td style="width: 50%; vertical-align: top;padding:40px;">
                                            <label>
                                                <?php
                                                echo lang('The Pharmacist') . ': ';
                                                $name = $this->session->userdata('name');
                                                $othername = $this->session->userdata('othername');
                                                echo $name . ' ' . $othername;
                                                ?>
                                            </label>
                                            <br>
                                            <label for=""><?php echo date("d/m/Y"); ?></label>
                                        </td>


                                        <td style="text-align: left; width: 50%; vertical-align: top;padding:40px;">
                                            <label for="prescription_obs"><?php echo lang('Prescription Observations'); ?></label>
                                            <textarea name="prescription_obs" class="form-control form-control-sm" id="prescription_obs" class="form-control" style="width: 100%;"></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Botões -->
                    <div class="form-group" style="text-align: right; padding: 10px">
                        <button type="button" class="btn btn-warning" onclick="window.history.back()"><?php echo lang('Back') ?></button>
                        <button type="button" class="btn btn-primary" onclick="validateAndSubmit()"><?php echo lang('Dispense') ?></button>

                    </div>
                </form>
            </div>
        </div>

        <!-- Fim Tabs -->
    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#drug_select").select2({
            width: '300px'
        });
        $("#route_administration_select").select2({
            width: '120px'
        });
        $("#frequency_select").select2({
            width: '120px'
        });
    });

    let index = <?php echo isset($drug_list) ? count($drug_list) : 0; ?>;

    function add_drug() {
        index++;

        const selected_drug_text = $("#drug_select :selected").text();
        var drug_parts = selected_drug_text.split(" ");
        var fnm_text = drug_parts[0];
        var remaining_parts_selected_drug_text = drug_parts.slice(1).join(" ");

        var input_dose_value = $("#dose").val();
        var selected_route_administration_text = $("#route_administration_select :selected").text();
        const selected_frequency_text = $("#frequency_select :selected").text();
        var input_total_time_value = $("#tempo_total").val();
        var input_total_dosage_value = $("#dose_total").val();

        if (input_dose_value === '' || selected_route_administration_text === '' || selected_frequency_text === '' || input_total_time_value === '' || input_total_dosage_value === '') {
            $("#message1").fadeIn();
            return;
        } else if (input_total_dosage_value <= 0) {
            $("#message2").fadeIn();
            return;
        } else if (input_total_time_value <= 0) {
            $("#message3").fadeIn();
            return;
        }

        let drugId = $("#drug_select").val()

        $.ajax({
            url: "<?php echo site_url('patient_external_prescription/get_drug_stock_and_batch'); ?>",
            type: "GET",
            data: {
                drug_id: drugId
            },
            success: function(data) {
                const stock = data.stock || 0;

                if (stock <= 0) {
                    $("#message4").fadeIn();
                    return;
                }

                let html = '<tr>';
                html += '<td>' + index + '</td>';
                html += '<td><input type="hidden" class="form-control form-control-sm"   name="fnm[' + index + ']" value="' + fnm_text + '">' + fnm_text + '</td>';
                html += '<td><input type="hidden" class=" form-control form-control-sm"   name="drug_id[' + index + ']" value="' + drugId + '">' + selected_drug_text + '</td>';
                html += '<td><input type="hidden" class=" form-control form-control-sm"   name="dose[' + index + ']" value="' + input_dose_value + '">' + input_dose_value + '</td>';
                html += '<td><input type="hidden"  class="form-control-sm"    name="route_administration[' + index + ']" value="' + selected_route_administration_text + '">' + selected_route_administration_text + '</td>';
                html += '<td><input type="hidden"  class=" form-control form-control-sm"  name="frequency[' + index + ']" value="' + selected_frequency_text + '">' + selected_frequency_text + '</td>';
                html += '<td><input type="hidden"  class=" form-control form-control-sm"  name="total_time[' + index + ']" value="' + input_total_time_value + '">' + input_total_time_value + '</td>';
                html += '<td><input type="hidden" class="form-control form-control-sm"   name="total_dosage[' + index + ']" value="' + input_total_dosage_value + '">' + input_total_dosage_value + '</td>';
                html += '<td><select class="form-control form-control-sm"   name="drug_batch[' + index + ']" class="drug_batch" style="width:70px;">';

                $.each(data.batches, function(index, batch) {
                    html += '<option value="' + batch + '">' + batch + '</option>';
                });

                html += '</select></td>';
                html += '<td><input type="hidden" name="confirm_drug[' + index + ']" value="No"><input type="checkbox" name="confirm_drug[' + index + ']" value="Yes" checked></td>';
                html += '<td><textarea class="form-control " name="note[' + index + ']" rows="1" cols="20"></textarea></td>';
                html += '<td ><input type="hidden" name="stock[' + index + ']" value="' + stock + '">' + stock + '</td>';
                html += '<td><button type="button" class="btn btn-danger btn-danger-xs btn_delete_drug"><span class="glyphicon glyphicon-trash"></span></button></td>';
                html += '</tr>';

                $('#tbody_drug').append(html);
                clearFields();
                togglePrescriptionSection();
            }
        });

        function clearFields() {
            $("#drug_select").val('').trigger('change');
            $("#route_administration_select").val('').trigger('change');
            $("#dose").val('');
            $("#frequency_select").val('').trigger('change');
            $("#tempo_total").val('');
            $("#dose_total").val('');
            $("#message1").fadeOut();
        }

    }

    $('#table_drug').on('click', '.btn_delete_drug', function() {
        $(this).closest('tr').remove();
    });

    function validateAndSubmit() {
        const patientName = document.getElementById('patient_name').value.trim();
        const patientNID = document.getElementById('patient_nid').value.trim();
        const healthUnitElement = document.getElementById('health_unit');
        const amountPaid = document.getElementById('amount_paid').value.trim();
        const successMessage = document.getElementById('success_message');

        const healthUnit = healthUnitElement.options[healthUnitElement.selectedIndex].text.trim();

        if (patientName === '' || patientNID === '' || healthUnit === '' || amountPaid === '') {
            $("#message5").fadeIn();
            return;
        }

        const batchSelects = document.querySelectorAll('.drug_batch');
        for (let i = 0; i < batchSelects.length; i++) {
            if (batchSelects[i].value === '') {
                $("#message5").fadeIn();
                return;
            }
        }

        successMessage.innerHTML = 'Prescrição criada com sucesso';
        successMessage.style.display = 'block';

        document.getElementById('dispense_form').submit();
    }

    // Event delegation for dynamically added buttons
    $(document).on('click', '.btn_delete_drug', function() {
        $(this).closest('tr').remove();
        togglePrescriptionSection();
    });

    function togglePrescriptionSection() {
        var rowCount = $("#table_drug tr").length;
        if (rowCount > 1) { // Row count greater than 1 means there are drugs in the table (excluding the header row)
            $("#prescription_section").show();
        } else {
            $("#prescription_section").hide();
        }
    }
</script>


<style>
    .patient-info {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 5px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .alert {
        margin-top: 10px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9em;
    }

    .table th,
    .table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .table th {
        color: #333;
        font-weight: bold;
    }

    .table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table input,
    .table select,
    .table textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 4px;
        font-size: 0.9em;
    }
</style>