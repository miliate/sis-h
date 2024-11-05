<div class="container-fluid">
    <div class="row" class="col-md-12">
        <div class="col-md-2">
            <?php
            switch ($ref_type) {
                case 'EMR':
                    echo Modules::run('leftmenu/emr', $ref_id, $PID, $visits_info);
                    break;
                case 'ADM':
                    echo Modules::run('leftmenu/admission', $visits_info, $ref_id);
                    break;
                case 'OPD':
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

            <div class="row" style="margin: 10px;">


            <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <?= lang('Cardex Prescription') ?>
                                    </div>

             <div>

                <table class="table table-striped table-hover">
                    <?php
                    if (isset($prescriptions)) {
                        foreach ($prescriptions as $prescription) {
                            echo '<tr>';
                            echo '<td>';
                            echo '<strong>' . lang('Elaborated At') . ': ' . '</strong>' . date('d-m-Y', strtotime($prescription->CreateDate));
                            echo '</td>';
                            echo '<td >';
                            echo '<strong>' . lang('Time') . ': ' . ' </strong>' . date('H:i', strtotime($prescription->CreateDate));
                            echo '</td>';
                            echo '<td  >';
                            echo '<strong>' . lang('Department') . ': ' . ' </strong>' .  $prescription->RefType;
                            echo '</td>';
                            echo '<td>';
                            echo '<strong>' . lang('Remarks') . ': ' . '</strong>' . $prescription->Remarks;
                            echo '</td>';
                            echo '<td>';
                            echo '<strong>' . lang('Created By') . ': ' . '</strong>' . $prescription->Name . " " . $prescription->OtherName;
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </table>
        
            </div>

            <div class="row" style="margin: 20px;">

                <?php
                function shouldDisplayColumn($userGroupId)
                {
                    return !in_array($userGroupId, [15, 21]);
                }

                $userGroupId = $this->session->userdata('user_group_id');
                ?>
                <div class="col-md-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?= lang('Cardex Prescribed Medications') ?>
                        </div>

                        <table class="table table-condensed table-hover" id="tb-cardex">
                            <thead>
                                <th>#</th>
                                <th width="3px"></th>
                                <th width="3px"></th>
                                <th><?php echo lang('Name') ?></th>
                                <th><?php echo lang('Route Administration') ?></th>
                                <th><?php echo lang('Dose') ?></th>
                                <th><?php echo lang('Posology') ?></th>
                                <th><?php echo lang('User') ?></th>
                                <th><?php echo lang('Prescribed') ?></th>
                                <th><?php echo lang('Duration of Treatment') ?></th>
                                <th><?php echo lang('Note') ?></th>
                                <th style="display: none;"><?php echo lang('DrugID') ?></th>
                                <th><?php if (shouldDisplayColumn($userGroupId)) echo lang('Times'); ?></th>
                                <th><?php if (shouldDisplayColumn($userGroupId)) echo lang('Observation'); ?></th>
                                <th></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div style="padding-top: 10px;"> </div>
                        <div id="div-cardex"></div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin: 20px;">
                <div class="col-md-12">

                <div class="panel panel-default">
                        <div class="panel-heading">
                            <?= lang('Administered Medications') ?>
                        </div>

 


                    <table class="table table-striped table-condensed" id="tb-taken-drugs" style=" overflow-y: auto;height:10px">
                        <thead>
                            <th>#</th>

                            <th><?php echo lang('Name') ?></th>
                            <th><?php echo lang('Dose Date') ?></th>
                            <th><?php echo lang('Dose Time') ?></th>
                            <th><?php echo lang('Result') ?></th>
                            <th><?php echo lang('Dispensed By') ?></th>

                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>    </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        let prescriptionId = "<?= $prescription->PrescriptionID ?>";
        loadDrugs(prescriptionId);

    });


    function loadDrugs(id) {

        var ugid = <?php echo $this->session->userdata('user_group_id'); ?>;
        var ref_type = "<?php echo strtolower($ref_type); ?>";
        var ref_id = <?php echo $ref_id; ?>;

        var translations = {
            'Existing and Confirmed Medication': "<?php echo lang('Existing and Confirmed Medication'); ?>",
            'Medication Administered': "<?php echo lang('Medication Administered'); ?>",
            'Patient Absent at Medication Time': "<?php echo lang('Patient Absent at Medication Time'); ?>",
            'Medication Administered but Patient Vomited': "<?php echo lang('Medication Administered but Patient Vomited'); ?>",
            'Medication Not Existing': "<?php echo lang('Medication Not Existing'); ?>",
            'Patient Refused Medication': "<?php echo lang('Patient Refused Medication'); ?>"
        };

        var actions = [{
                Action: translations['Existing and Confirmed Medication'],
                Icon: 'fa-check'
            },
            {
                Action: translations['Medication Administered'],
                Icon: 'fa-save'
            },
            {
                Action: translations['Patient Absent at Medication Time'],
                Icon: 'fa fa-cog'
            },
            {
                Action: translations['Medication Administered but Patient Vomited'],
                Icon: 'fa fa-cog'
            },
            {
                Action: translations['Medication Not Existing'],
                Icon: 'fa fa-cog'
            },
            {
                Action: translations['Patient Refused Medication'],
                Icon: 'fa fa-cog'
            }
        ];

        $.get(`../../get_prescription_itens/${id}`, function(data, status) {
            $('#tb-cardex tbody').empty();

            $.each(data, function(key, item) {
                var select = $('<select class = "form-control input-sm">');
                actions.forEach(function(option) {
                    var optionElement = $('<option data-icon = "fa-user">').val(option.Action).text(option.Action).html('<i class = "fa fa-user"></i>' + option.Action)

                    select.append(optionElement);
                });

                var button = $('<button class = "btn btn-sm btn-primary">')
                    .text("<?php echo lang('Register'); ?>")
                    .attr('id', 'administration-' + key)
                    .on('click', function() {

                        var row = $(this).closest('tr');
                        var prescriptionId = row.find('td:eq(1) input').val()
                        var patientId = row.find('td:eq(2) input').val();;
                        var drugName = row.find('td:eq(3)').text();

                        var takenTime = row.find('td:eq(12) input').val();
                        var status = row.find('td:eq(13) select').val();

                        let takenDateTime = new Date();
                        takenDateString = takenDateTime.toISOString().split('T')[0];
                        let [hours, minutes] = takenTime.split(':');

                        takenDateTime.setHours(hours);
                        takenDateTime.setMinutes(minutes);

                        var takenDrug = {
                            TakenDateTime: `${takenDateString} ${hours}:${minutes}`,
                            HaveDrugID: item.DrugID,
                            Status: status,
                            Drug: drugName,
                            PID: patientId,
                            RefID: "<?= $prescription->RefID ?>"
                        }

                        $.ajax({
                            url: "<?= site_url('patient_prescription/take_drug') ?>",
                            type: 'POST',
                            data: {
                                takenDrug: JSON.stringify(takenDrug)
                            }
                        })
                        listTakenDrugs(id);


                    })

                let drugName = [item.fnm, item.name, item.pharmaceutical_form, item.dosage].filter(Boolean).join('-');

                $("#tb-cardex").find('tbody').append($('<tr>')
                    .append($('<td>').append(key + 1))
                    .append($('<td>').append($('<input type="hidden">').val(item.ID)))
                    .append($('<td>').append($('<input type="hidden">').val(item.PID)))
                    .append($('<td>').append(drugName))
                    .append($('<td>').append(item.RouteAdministration))
                    .append($('<td>').append(item.Dose))
                    .append($('<td>').append(item.Period))
                    .append($('<td>').append(
                        item.UserName + ' ' + item.OtherName
                    ))

                    .append($('<td>').append(item.CreateDate))
                    .append($('<td>').append(item.TimeTotal))
                    .append($('<td>').append(item.Note))
                    .append($('<td>').css('display', 'none').append(item.DrugID))
                    .append($('<td>').append('<input type="time" class="form-control input-sm time-input">')
                        .val(new Date().toTimeString().split(' ')[0].slice(0, 5)))
                    .append($('<td>').append(select))
                    .append($('<td>').append(button))

                );

                // Hide elements based on ugid value
                if (ugid == 15 || ugid == 21) {
                    $("#tb-cardex tbody tr:last-child").find('.time-input, select, button').hide();
                    $("#tb-cardex tbody tr:last-child").find('.btn-danger').show();
                } else if (ugid == 22) {

                    $("#tb-cardex tbody tr:last-child").find('.time-input, select, button').show();
                    $("#tb-cardex tbody tr:last-child").find('.btn-danger').hide();
                }

            });

            // Add a button to send all table data
            if (ugid == 15 || ugid == 21) {
                var sendAllButton = $('<button class="btn btn-sm btn-primary">')
                    .text("<?php echo lang('Get Cardex Drug'); ?>")
                    .on('click', function() {
                        // Collect all table rows data
                        var tableData = [];
                        $('#tb-cardex tbody tr').each(function() {
                            var row = $(this);
                            var rowData = {
                                PrescriptionID: row.find('td:eq(1) input').val(),
                                PID: row.find('td:eq(2) input').val(),
                                DrugName: row.find('td:eq(3)').text(),
                                RouteAdministration: row.find('td:eq(4)').text(),
                                Dose: row.find('td:eq(5)').text(),
                                Period: row.find('td:eq(6)').text(),
                                CreateDate: row.find('td:eq(7)').text(),
                                TimeTotal: row.find('td:eq(8)').text(),
                                Note: row.find('td:eq(9)').text(),
                                DrugID: row.find('td:eq(10)').text()
                            };
                            tableData.push(rowData);
                        });

                    
                        // Send the entire table data to the controller
                        $.ajax({
                            url: "<?= site_url('patient_prescription/get_cardex_drug') ?>",
                            type: 'POST',
                            data: {
                                cardexData: JSON.stringify(tableData),
                                ref_type: JSON.stringify('<?= $ref_type ?>'),
                                ref_id: JSON.stringify(<?= $ref_id ?>)
                            },
                            success: function(response) {
                                window.location.href = "<?= site_url('patient_prescription/cardex_prescription') ?>/" +ref_type + "/" + <?= $ref_id ?> + "/true";
                            }
                        });
                    });

                // Append the sendAllButton outside the table
                $('#div-cardex').after(sendAllButton);
            }
        });

        listTakenDrugs(id)

    }

    function listTakenDrugs($ref_id) {

        $('#tb-taken-drugs tbody').empty();

        $.ajax({
            url: "<?= site_url('patient_prescription/taken_drugs') ?>" + "/" + <?php echo $ref_id; ?>,
            type: 'GET',

            success: function(data) {

                $.each(data, function(key, item) {

                    takenDate = item.TakenDateTime.split(' ')[0];
                    takenHours = item.TakenDateTime.split(' ')[1];
                    let [hours, minutes] = takenHours.split(':');

                    $("#tb-taken-drugs").find('tbody')
                        .append($('<tr>')
                            .append($('<td>').append(key + 1))
                            .append($('<td>').append(item.Drug))
                            .append($('<td>').append(takenDate))
                            .append($('<td>').append(`${hours}:${minutes}`))
                            .append($('<td>').append(item.Status))
                            .append($('<td>').append(`${item.Name} ${item.OtherName}`))
                        );

                });

            }
        })
    }
</script>

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