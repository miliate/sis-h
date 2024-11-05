<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


</head>

<body>
    <div class="container-fluid">
        <?php if ($this->session->flashdata('error_message')) : ?>
            <div class="alert alert-danger mt-3">
                <?php echo $this->session->flashdata('error_message'); ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-12 ">
                        <?php echo Modules::run('patient_prescription/banner', $pid); ?>

                        <div style="padding:20px">
                            <div class="panel panel-default">
                                <!-- Default panel contents -->
                                <div class="panel-heading">
                                    <h5 class="font-weight-bold m-0"><?php echo lang('Dispense Drug') ?></h4>
                                </div>
                                <form action="" method="post" role="form">
                                    <!-- Table -->
                                    <table class="table input-sm" id="table_drug">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo lang('FNM') ?></th>
                                                <th><?php echo lang('Name') ?></th>
                                                <th><?php echo lang('Dose') ?></th>
                                                <th><?php echo lang('Route Administration') ?></th>
                                                <th><?php echo lang('Posology') ?></th>
                                                <th><?php echo lang('Duration of Treatment') ?></th>
                                                <th><?php echo lang('Quantity prescribed') ?></th>
                                                <th><?php echo lang('Quantity') ?></th>
                                                <th style="Width:150px;"><?php echo lang('Batch') ?></th>
                                                <th><?php echo lang('Confirm Drug') ?></th>
                                                <th><?php echo lang('Comment') ?></th>
                                                <th class="stock-header"><?php echo lang('Stock') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            echo '<tr>' . validation_errors() . '</tr>';
                                            foreach ($drug_list as $drug_order) {
                                                echo '<tr>';
                                                echo '<td>' . $drug_order['order'] . '</td>';
                                                echo '<td>' . (isset($drug_order['fnm']) ? $drug_order['fnm'] : '') . '</td>';
                                                echo '<td>' . $drug_order['drug_info'] . '</td>';
                                                echo '<td>' . $drug_order['dose'] . '</td>';
                                                echo '<td>' . $drug_order['routeAdministration'] . '</td>';
                                                echo '<td>' . $drug_order['frequency'] . '</td>';
                                                echo '<td style ="text-align: center">' . $drug_order['timeTotal'] . '</td>';
                                                echo '<td style ="text-align: center">' . $drug_order['doseTotal'] . '</td>';
                                                echo '<td>';
                                                if (isset($drug_order['quantity']) && $drug_order['quantity'] > 0) {
                                                    echo $drug_order['quantity'];
                                                } else {
                                                    echo '<input  class="form-control form-control-sm" type="number" name="quantity[' . $drug_order['order'] . ']" min="0" max="130" value="' . set_value("quantity[" . $drug_order['quantity'] . "]", $drug_order['quantity']) . '" step="1">';
                                                }
                                                echo '</td>';
                                                echo '<td>';

                                                if ($patient_prescription->Status === 'Dispensed') {
                                                    foreach ($drug_order['batch'] as $batch) {
                                                        echo   $batch;
                                                    }
                                                } else {
                                                    echo '<select class="form-control form-control-sm" style ="text-align: center"name="drug_batch[' . $drug_order['order'] . ']">';
                                                    foreach ($drug_order['batch'] as $batch) {
                                                        echo '<option value="' . $batch . '">' . $batch . '</option>';
                                                    }
                                                    echo '</select>';
                                                }

                                                echo '</td>';
                                                echo '<td>';
                                                if ($patient_prescription->Status === 'Dispensed') {

                                                    echo lang($drug_order['dispensed']);
                                                } else {
                                                    echo  '<input type="checkbox"   name="confirm_drug[' . $drug_order['order'] . ']" value="1"' . ($drug_order['dispensed'] ? ' checked' : '') . '></td>';
                                                }
                                                echo '<td>';
                                                if ($patient_prescription->Status === 'Dispensed') {
                                                    echo  $drug_order['note'];
                                                } else {
                                                    echo '<textarea class="form-control form-control-sm" name="note[' . $drug_order['order'] . ']" rows="1" cols="20">' . set_value("note[" . $drug_order['note'] . "]", $drug_order['note']) . '</textarea>';
                                                }

                                                echo '</td>';
                                                echo '<td class="stock-cell" style ="text-align: center">' . (isset($drug_order['stock']) ? $drug_order['stock'] : 0) . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class='row'>

                                        <div class='col-md-7'>
                                            <table style="width: 100%;margin-top:2%;">
                                                <tr>
                                                    <td style="text-align: center; padding-left: 20%;">
                                                        <b><?php echo lang('The Clinic') ?></b>
                                                    </td>
                                                    <td style="text-align: center; padding-right: 20%;">
                                                        <b> <?php echo lang('The Pharmacist'); ?></b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center; padding-left: 20%;">
                                                        <?php echo $doctor_name; ?>
                                                    </td>
                                                    <td style="text-align: center; padding-right: 20%;">

                                                        <?php
                                                        $name = $this->session->userdata('name');
                                                        $othername = $this->session->userdata('othername');
                                                        echo $name . ' ' . $othername;
                                                        ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center; padding-left: 20%;">
                                                        <?php echo date("d/m/Y", strtotime($prescription_date)); ?>
                                                    </td>
                                                    <td style="text-align: center; padding-right: 20%;">
                                                        <?php echo date("d/m/Y"); ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class='col-md-5' style="padding-right: 15px;">

                                            <div style="display: flex; flex-direction: column; ">
                                                <div style="display: flex;  padding: 5px 0; background-color: #e2e2e2; border-bottom: 1px solid #ccc;">
                                                    <div style="flex: 1; text-align: left; padding-left:20px"><?php echo lang('Patient Type') ?></div>
                                                    <div style="width: 20px;"></div>
                                                    <div style="flex: 1; text-align: left;  "><?php echo lang('Cost') ?></div>

                                                </div>

                                                <?php
                                                $drug_order_get_patient = '';
                                                $drug_order_get_cost = '';
                                                foreach ($drug_list as $drug_order) {
                                                    $drug_order_get_patient = $drug_order['patient_type'];
                                                    $drug_order_get_cost =  $drug_order['cost'];

                                                    echo '<div style="display: flex; align-items: center; padding:  0; border-bottom: 1px solid #f5f5f5;">';

                                                    // Patient Type Column
                                                    if (isset($drug_order['patient_type']) && $drug_order['patient_type'] > 0) {
                                                        echo '<div style="flex: 1; text-align: left; padding-left: 20px;">' . $drug_order_get_patient . '</div>';
                                                    } else {
                                                        echo '<div style="flex: 1; text-align: left; padding-left: 20px;">' . $drug_order_get_patient . '</div>';
                                                    }

                                                    echo '<div style="width: 100px;"></div>';
                                                    if (isset($drug_order['cost']) && $drug_order['cost'] < 0) {
                                                        echo '<div style="flex: 1; text-align: left; ">' . $drug_order_get_cost . '</div>';
                                                        echo '<div style="flex: 1; text-align: left; ">MZN</div>';
                                                    } else {

                                                        if ($patient_prescription->Status === 'Dispensed') {
                                                            echo '<div style="flex: 1; text-align: left; padding: 5px;">' .
                                                                $drug_order_get_cost .
                                                                ' MZN</div>';
                                                        } else {
                                                            echo '<div style="flex: 1; text-align: left; padding: 5px;">' .
                                                                '<input class="form-control form-control-sm" type="number" name="cost" min="0" max="50" value="'
                                                                . set_value($drug_order_get_cost, $drug_order_get_cost) . '" step="1"></div>';
                                                            echo '<div style="flex: 1; text-align: left; padding: 5px;">MZN</div>';
                                                        }
                                                    }

                                                    echo '</div>';
                                                }
                                                ?>
                                            </div>
                                        </div>

                                    </div>
                                    <?php
                                    if ($patient_prescription->Status === 'Dispensed') {
                                    ?>
                                        <div class="form-group" style="text-align: right">

                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="form-group" style="text-align: right; padding:15px">
                                            <button type="submit" class="btn btn-primary"><?php echo lang('Dispense') ?></button>
                                            <button type="button" class="btn btn-primary" onclick="window.history.back()"><?php echo lang('Back') ?></button>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                </form>
                                <?php
                                if ($patient_prescription->Status === 'Dispensed') {
                                ?>
                                    <div class="form-group" style="text-align: right; padding:30px">
                                        <button type="button" class="btn btn-primary" onclick="window.history.back()">
                                            <i class="fa fa-arrow-left"></i> <?php echo lang('Back'); ?>
                                        </button>
                                        <form id="printForm" action="<?php echo site_url('/report/pdf/internalPrescription/print/'); ?>" method="post" target="_blank" style="display: inline;">
                                            <input type="hidden" name="print_prescription" value="<?php echo $patient_prescription->PrescriptionID; ?>">
                                            <button type="button" class="btn btn-primary" onclick="submitPrintForm()">
                                                <i class="fa fa-print"></i> <?php echo lang('Print'); ?>
                                            </button>
                                        </form>
                                    </div>
                                <?php
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>


                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelectorAll('input[type=checkbox]').forEach(function(checkbox) {
                            checkbox.value = checkbox.checked ? 'yes' : 'no';
                            checkbox.addEventListener('click', function() {
                                this.value = this.checked ? 'yes' : 'no';
                            });
                        });
                    });

                    function submitPrintForm() {
                        document.getElementById('printForm').submit();
                    }
                </script>

            </div>
</body>

<style>
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

</html>