<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php echo Modules::run('patient/banner', $pid); ?>
            <?php
            switch ($ref_type) {
                case 'EMR':
                    echo Modules::run('emergency_visit/info', $ref_id);
                    break;
                case 'ADM':
                    echo Modules::run('admission/info', $ref_id);
                    break;
                case 'OPD':
                    echo Modules::run('opd_visit/info', $ref_id);
                    break;
                default:
                    echo 'Wrong department';
                    break;
            }
            ?>
            <div class="panel panel-info">
                <!-- Default panel contents -->
                <div class="panel-heading"><?php echo lang('Dispense Drug')?></div>
                <form action="" method="post" role="form">
                    <!-- Table -->
                    <table class="table input-sm" id="table_drug" style="margin-bottom: 0px">
                        <tbody>
                        <tr bgcolor="#e2e2e2">
                            <th><b>#</b></th>
                            <th><b><?php echo lang('Name')?></b></th>
                            <th><b><?php echo lang('Dose')?></b></th>
                            <th><b><?php echo lang('Frequency')?></b></th>
                            <th><b><?php echo lang('Period')?></b></th>
                            <th><b><?php echo lang('Quantity')?></b></th>
                        </tr>
                        </tbody>
                        <tbody>
                        <?php
                        echo '<tr>' . validation_errors() . '</tr>';
                        foreach ($drug_list as $drug_order) {
                            echo '<tr>';
                            echo '<td>' . $drug_order['order'] . '</td>';
                            echo '<td>' . $drug_order['drug_info'] . '</td>';
                            echo '<td>' . $drug_order['dose'] . '</td>';
                            echo '<td>' . $drug_order['frequency'] . '</td>';
                            echo '<td>' . $drug_order['period'] . '</td>';
                            if (isset($drug_order['quantity'])&&$drug_order['quantity']>0) {
                                echo '<td>' . $drug_order['quantity'] . '</td>';
                            } else {
                            echo '<td>' . '<input type="number" name="quantity[' . $drug_order['order'] . ']" min="0" max="130" value="' . set_value("quantity[" . $drug_order['order'] . "]", $drug_order['quantity']) . '" step="1"></td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                    if ($patient_prescription->Status === 'Dispensed') {
                        ?>
                        <div class="form-group" style="text-align: center">
                            <button type="button" class="btn btn-primary" onclick="window.history.back()">Back</button>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="form-group" style="text-align: center">
                            <button type="submit" class="btn btn-primary"><?php echo lang('Dispense')?></button>
                            <button type="button" class="btn btn-primary" onclick="window.history.back()"><?php echo lang('Back')?></button>
                        </div>
                        <?php
                    }
                    ?>

                </form>
            </div>
        </div>
    </div>
</div>