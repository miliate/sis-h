<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-2">
                <?php
                switch ($ref_type) {
                    case 'emr':
                        echo Modules::run('leftmenu/emr', $ref_id, $PID, $visits_info);
                        break;
                    case 'adm':
                        echo Modules::run('leftmenu/admission', $visits_info, $ref_id);
                        break;
                    case 'opd':
                        echo Modules::run('leftmenu/opd', $ref_id, $PID, $visits_info, $is_discharged);
                        break;
                    default:
                        echo 'wrong department';
                        break;
                }
                ?>
            </div>
            <div class="col-md-10">

                <?php
                echo Modules::run('patient/banner', $PID);
                ?>

                <?php if (Modules::run('permission/check_permission', 'prescribe_drug_cardex', 'create')): ?>
                    <div class="row" id="cardex_create">


                        <div class="col-md-12" style="padding: 12px;">

                            <div id="messageRequired" class="alert alert-danger" style="display:none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <span id="message_text"><?php echo lang('Please insert all data'); ?></span>
                            </div>

                            <div id="message2" class="alert alert-danger" style="display:none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <span id="message_text"><?php echo lang('Please insert valid numbers'); ?></span>
                            </div>


                            <div id="message1" class="alert alert-danger" style="display:none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <span id="message_text"><?php echo lang('No new record was added'); ?></span>
                            </div>

                            <div id="messageDrugSuspended" class="alert alert-success" style="display:none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <span id="message_text"><?php echo lang('Prescribed drug was suspended successfully'); ?></span>
                            </div>

                            <div id="messageDrugNotSuspended" class="alert alert-error" style="display:none;">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <span id="message_text"><?php echo lang('Prescribed drug was not suspended successfully'); ?></span>
                            </div>


                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?= lang('Prescription') ?>
                                </div>
                                <table class="table">
                                    <thead>
                                        <th><?php echo lang('Name') ?></th>
                                        <th><?php echo lang('Route Administration') ?></th>
                                        <th><?php echo lang('Dose') ?></th>
                                        <th><?php echo lang('Posology') ?></th>
                                        <th><?php echo lang('Duration of Treatment') ?></th>
                                        <th><?php echo lang('Note') ?></th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo Modules::run('drug/view_select_drug') ?></td>
                                            <td><?php echo Modules::run('drug/view_route_administration') ?></td>
                                            <td><?php echo '<input type="text" name="dose" id="dose" style="width:80px;" class="form-control">' ?></td>
                                            <td>
                                                <select id="select_hour" multiple></select>
                                            </td>
                                            <td><?php echo '<input type="number" name="tempo_total" id="tempo_total" class="form-control">' ?></td>
                                            <td><textarea name="note" id="note" rows="1" cols="20" style="width:80px;" class="form-control"></textarea></td>
                                            <td align="left" style="vertical-align: left;">
                                                <button type="button" class="btn btn-info btn-sm" id="add_drug">
                                                    <span class="glyphicon glyphicon-plus-sign"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div id="div_cardex" style="display:none;" class="col-md-12">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <?= lang('Cardex Prescription') ?>
                                    </div>

                                    <table id="table_cardex" class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo lang('Name') ?></th>
                                                <th><?php echo lang('Route Administration') ?></th>
                                                <th><?php echo lang('Dose') ?></th>
                                                <th><?php echo lang('Posology') ?></th>
                                                <th><?php echo lang('Duration of Treatment') ?></th>
                                                <th><?php echo lang('Note') ?></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($cardex_data)): ?>
                                                <script type="text/javascript">
                                                    $(document).ready(function() {
                                                        $('#div_cardex').fadeIn(); // Show the cardex section
                                                    });
                                                </script>
                                                <?php foreach ($cardex_data as $index => $drug): ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" id="DrugId" name="DrugID[]" value="<?php echo htmlspecialchars($drug['DrugID']); ?>">
                                                        </td>
                                                        <td><?php echo htmlspecialchars($drug['DrugName']); ?></td>
                                                        <td><?php echo htmlspecialchars($drug['RouteAdministration']); ?></td>
                                                        <td><?php echo htmlspecialchars($drug['Dose']); ?></td>
                                                        <td><?php echo htmlspecialchars($drug['Period']); ?></td>
                                                        <td><?php echo htmlspecialchars($drug['TimeTotal']); ?></td>
                                                        <td><?php echo htmlspecialchars($drug['Note']); ?></td>
                                                        <td>
                                                            <?php if (empty($drug['PrescriptionID'])): ?>
                                                                <button class="btn btn-danger remove-drug btn-xs" data-index="<?php echo $index; ?>">

                                                                    <span class="glyphicon glyphicon-trash"></span> <?php echo lang('remove'); ?>
                                                                </button>
                                                            <?php else: ?>

                                                                <button class="btn btn-danger suspend-drug btn-xs" data-index="<?php echo $index; ?>">
                                                                    <span class="glyphicon glyphicon-stop"></span> <?php echo lang('suspend'); ?>
                                                                </button>

                                                            <?php endif; ?>
                                                            <input type="hidden" id="PrescriptionID" name="PrescriptionID" value="<?php echo htmlspecialchars($drug['PrescriptionID']); ?>">


                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <!--tr>
                                                <td colspan="7" class="text-center"><?php echo lang('No cardex data available.'); ?></td>
                                            </tr-->
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                                    <div class="col-md-6" style="padding-top: 15px;">
                                        <label><?php echo lang('Remarks'); ?></label>
                                        <textarea class="form-control" id="remarks" name="remarks"></textarea>
                                    </div>

                                    <div class="row-ward" style="margin-top: 100px;">
                                        <div class="col-md-3" id="ward_row">
                                            <div>
                                                <label for="ward_name"><?php echo lang('Select Ward'); ?></label>
                                                <input type="hidden" id="ward_id" name="ward_id" value="<?php echo isset($ward_info->Ward) ? $ward_info->Ward : ''; ?>">
                                                <input type="text" id="ward_select" name="ward_select" class="form-control" value="<?php echo isset($ward_info->Ward) ? $ward_info->Ward : 'Default Ward Name'; ?>" readonly>
                                                <small class="form-text text-muted">Ward information will be populated automatically.</small>
                                            </div>
                                        </div>

                                        <div class="col-md-3" id="room_row">
                                            <div>
                                                <label for="room_select"><?php echo lang('Select Room'); ?></label>
                                                <input type="hidden" id="room_id" name="room_id" value="<?php echo isset($ward_info->RoomNo) ? $ward_info->RoomNo : ''; ?>">
                                                <input type="text" id="room_select" name="room_select" class="form-control" value="" readonly>
                                                <small class="form-text text-muted">Room will be filled in automatically based on the selected ward.</small>
                                            </div>
                                        </div>

                                        <div class="col-md-3" id="bed_row">
                                            <div>
                                                <label for="bed_select"><?php echo lang('Select Bed'); ?></label>
                                                <input type="hidden" id="bed_id" name="bed_id" value="<?php echo isset($ward_info->BedNo) ? $ward_info->BedNo : ''; ?>">
                                                <input type="text" id="bed_select" name="bed_select" class="form-control" value="" readonly>
                                                <small class="form-text text-muted">Bed will be filled in automatically based on the selected room.</small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-9">
                                            </div>
                                            <div class="col-md-3" style="padding:15px; text-align:right">
                                                <button type="button" id="btn_registar" class="btn btn-success"><?php echo lang('Save'); ?></button>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <script type="text/javascript">
                                    $(document).ready(function() {

                                        attachSuspendButtonHandler();
                                        $("#ward_row").hide();
                                        $("#room_row").hide();
                                        $("#bed_row").hide();
                                        $("#drug_select").select2({
                                            width: '250px'
                                        });
                                        $("#route_administration_select").select2({
                                            width: '120px'
                                        });
                                        $("#frequency_select").select2({
                                            width: '120px'
                                        });
                                        $("#drug_select_2").select2({
                                            width: '300px'
                                        });
                                        $("#route_administration_2").select2({
                                            width: '120px'
                                        });


                                        fillHours();
                                        $("#select_hour").multiselect({
                                            buttonWidth: '120px',
                                            maxHeight: 400,
                                            selectAllText: "<?= lang('Select All'); ?>",
                                            nonSelectedText: "<?= lang('Times'); ?>"
                                        });

                                        $('#add_drug').click(function() {
                                            $('#div_cardex').fadeIn();

                                            var selected_drug_text = $("#drug_select :selected").text();
                                            var drug_parts = selected_drug_text.split(" ");
                                            var remaining_parts_selected_drug_text = drug_parts.slice(1, -2).join(" ");
                                            var fnm_text = drug_parts[0];

                                            var selected_route_administration_text = $("#route_administration_select :selected").text();
                                            var input_dose_value = $("#dose").val();
                                            var selected_frequency_text = $("#frequency_select :selected").text();
                                            var input_time_total_value = $("#tempo_total").val();
                                            var selected_hour = $("#select_hour :selected").text();

                                            if (selected_route_administration_text === '' || input_dose_value === '' || selected_hour === '' || input_time_total_value === '') {
                                                $("#messageRequired").fadeIn();
                                                return;
                                            } else if (input_time_total_value <= 0) {
                                                $("#message2").fadeIn();
                                                return;
                                            } else {

                                                addTableRow();
                                                clearForm();
                                            }
                                        });

                                        $(document).on('click', '.remove-drug', function() {
                                            var rowIndex = $(this).data('index');
                                            $(this).closest('tr').remove();

                                        });

                                        $(document).on('click', '.remove-drug', function() {
                                            var rowIndex = $(this).data('index');
                                            $(this).closest('tr').remove();

                                        });

                                        $('#btn_registar').click(function() {

                                            const keys = ['DrugID', 'drugName', 'RouteAdministration', 'Dose', 'Period', 'TimeTotal', 'Note'];
                                            let prescriptionsData = [];


                                            $('#table_cardex tbody tr').each(function(rowIndex, row) {

                                                var prescriptionId = $(row).find('#PrescriptionID').val();
                                                // only take new rows that was aded during the update and ignore others
                                                if (prescriptionId === undefined) {

                                                    let rowData = {};
                                                    $(row).find('td').not(':last').each(function(cellIndex, cell) {
                                                        let id = $(cell).find('input').val();
                                                        if (id) {
                                                            rowData[keys[cellIndex]] = id;
                                                            return;
                                                        }
                                                        rowData[keys[cellIndex]] = $(cell).text();
                                                    });
                                                    prescriptionsData.push(rowData);
                                                }
                                            });

                                            if (prescriptionsData.length === 0) {
                                                $("#message1").fadeIn();
                                                return;

                                            } else {

                                                // check if exists prescription, if exists update with adding new precribed drugs otherwise create new prescripio 
                                                $.get(`../../../get_prescription_by_patient_id/` + "<?= $ref_type . '/' . $ref_id ?>", function(data, status) {



                                                    if (data.length === 0) {

                                                        $.ajax({
                                                            url: "<?= site_url('patient_prescription/add_cardex') ?>",
                                                            type: 'POST',
                                                            data: {
                                                                prescriptions: JSON.stringify(prescriptionsData),
                                                                ref_type: "<?= $ref_type ?>",
                                                                ref_id: "<?= $ref_id ?>",
                                                                PID: "<?= $PID ?>",
                                                                Remarks: $('#remarks').val(),
                                                                ward_select: $('#ward_id').val(),
                                                                room_select: $('#room_id').val(),
                                                                bed_select: $('#bed_id').val()
                                                            },
                                                            success: function(response) {
                                                                $('#div_cardex').fadeOut();
                                                                $('#table_cardex tbody').empty();

                                                                //remove cardex_data from userdata
                                                                <?php $this->session->unset_userdata('cardex_data'); ?>
                                                                window.location.href = "<?= site_url('patient_prescription/cardex') ?>/" + "<?= $PID ?>" + "/" + <?= $ref_id ?>;

                                                                //  location.reload();
                                                            }
                                                        });

                                                    } else {


                                                        $.ajax({
                                                            url: "<?= site_url('patient_prescription/add_cardex_items') ?>",
                                                            type: 'POST',
                                                            data: {
                                                                prescriptionsItems: JSON.stringify(prescriptionsData),
                                                                PID: "<?= $PID ?>",
                                                                PrescriptionID: data[0].PrescriptionID,
                                                            },
                                                            success: function(response) {
                                                                $('#div_cardex').fadeOut();
                                                                $('#table_cardex tbody').empty();

                                                                //remove cardex_data from userdata
                                                                <?php $this->session->unset_userdata('cardex_data'); ?>

                                                                // location.reload();
                                                            }

                                                        });
                                                    }

                                                });

                                                // 
                                            }

                                            console.log(prescriptionsData);

                                        });
                                    });

                                    function addTableRow() {
                                        let noteValue = $('#note').val();
                                        let formattedNote = '';
                                        for (let i = 0; i < noteValue.length; i += 30) {
                                            formattedNote += noteValue.substring(i, i + 30) + '<br>';
                                        }

                                        $('#table_cardex').append($('<tr>')
                                            .append($('<td>').append($('<input>').attr({
                                                value: $('#drug_select option:selected').val(),
                                                type: 'hidden'
                                            })))
                                            .append($('<td>').append($('#drug_select option:selected').text()))
                                            .append($('<td>').append($('#route_administration_select option:selected').text()))
                                            .append($('<td>').append($('#dose').val()))
                                            .append($('<td>').append($('#select_hour option:selected').text().replace(/h/g, 'h, ').slice(0, -2)))
                                            .append($('<td>').append($('#tempo_total').val()))
                                            .append($('<td>').html(formattedNote))
                                            .append($('<td>').append($('<button>').addClass('btn btn-danger btn-xs remove-drug').append($('<span>').addClass('glyphicon glyphicon-trash')).append(" <?= lang('remove'); ?>")))
                                        );
                                        attachRemoveButtonHandler();

                                    }

                                    function clearForm() {
                                        $('#drug_select').prop('selectedIndex', -1).change();
                                        $('#route_administration_select').prop('selectedIndex', -1).change();
                                        $('#dose').val('');
                                        $('#note').val('');
                                        $('#tempo_total').val('');
                                        $('#select_hour').multiselect('deselectAll', false);
                                        $('#select_hour').multiselect('updateButtonText');
                                    }

                                    function fillHours() {
                                        for (let i = 0; i <= 23; i++) {
                                            $("#select_hour").append($('<option>', {
                                                value: i,
                                                text: `${i}h`
                                            }));
                                        }
                                    }

                                    function attachRemoveButtonHandler() {
                                        $('.remove-drug').off('click').on('click', function() {
                                            $(this).closest('tr').remove(); // Remove the row
                                        });
                                    }


                                    function attachSuspendButtonHandler() {
                                        $('.suspend-drug').off('click').on('click', function() {
                                            var currentRow = $(this).closest('tr');
                                            var prescriptionId = currentRow.find('#PrescriptionID').val();
                                            var drugId = currentRow.find('#DrugId').val();

                                            const userConfirmed = confirm("Are you sure you want to proceed?");


                                            if (userConfirmed) {
                                                $.ajax({
                                                    url: "<?= site_url('patient_prescription/suspendPrescribedDrug') ?>",
                                                    type: "POST",
                                                    dataType: "json",
                                                    data: {
                                                        prescriptionId: prescriptionId
                                                    },
                                                    success: function(response) {
                                                        if (response.status === 'success') {
                                                            alert(prescriptionId);
                                                            $('#messageDrugSuspended').fadeIn();
                                                            window.location.href = "<?= site_url('patient_prescription/cardex') ?>/" + "<?= $PID ?>" + "/" + <?= $ref_id ?>;
                                                        } else {
                                                            $('#messageDrugNotSuspended').fadeIn();
                                                        }
                                                    },
                                                    error: function() {
                                                        // show error message
                                                        console.log("unexpected error");
                                                    }
                                                });
                                            } else {
                                                // If user clicked "Cancel"
                                                console.log("User canceled the action.");
                                            }
                                        });
                                    }


                                    $(document).ready(function() {
                                        var ward_id = $('#ward_id').val();
                                        var room_id = $('#room_id').val();
                                        var bed_id = $('#bed_id').val();

                                        $.ajax({
                                            url: '<?php echo site_url("patient_prescription/get_ward_details"); ?>',
                                            method: 'GET',
                                            data: {
                                                ward_id: ward_id,
                                                room_id: room_id,
                                                bed_id: bed_id
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                $('#ward_select').val(response.ward.name);

                                                if (response.rooms) {
                                                    $('#room_select').val(response.rooms.name);
                                                } else {
                                                    $('#room_select').val('Nenhum quarto encontrado');
                                                }

                                                if (response.beds) {
                                                    $('#bed_select').val(response.beds.number);
                                                } else {
                                                    $('#bed_select').val('Nenhuma cama encontrada');
                                                }
                                            }
                                        });
                                    });
                                </script>

                            <?php endif; ?>

                            </div>
                        </div>
                    </div>
            </div>

            <style>
                .form-group label {
                    display: block;
                    margin-bottom: 5px;
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
                }
            </style>