<div class="container-fluid">
    <div class="row">
        <!-- Menu à esquerda -->
        <div class="col-md-2">
            <?php
            echo Modules::run('leftmenu/patient', $PID, $ref_id); 
            ?>
        </div>

        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b><?php echo "<i class='fa fa-group'></i> " . lang('Active List') . " - PNCT"; ?></b>
                </div>
            </div>

            <!-- Menu de formulários ou relatórios -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php 
                    $form_generator = new MY_Form(lang('Reports')); 
                    $form_generator->get_pnct_forms_menu($PID); 
                    ?>
                </div>
            </div>


            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('Characterization of tuberculosis'); ?></div>
                <div class="panel-body">
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table input-sm" id="table_tb_characterization">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo lang('Location'); ?></th>
                                    <th><?php echo lang('Type'); ?></th>
                                    <th><?php echo lang('Bacteriological Confirmation'); ?></th>
                                    <th><?php echo lang('Tests'); ?></th>
                                    <th><?php echo lang('Date'); ?></th>
                                    <th><?php echo lang('Resistance Profile'); ?></th>
                                    <th><?php echo lang('Other'); ?></th>
                                    <th><?php echo lang('Previous Treatment'); ?></th>
                                    <th><?php echo lang('Other'); ?></th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody id="tbody_tb_characterization">
                                <?php if (!empty($tb_characterization)): ?>
                                    <?php foreach ($tb_characterization as $index => $characterization): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $characterization['LocationDescription']; ?></td>
                                            <td><?php echo $characterization['Location']; ?></td>
                                            <td><?php echo $characterization['ConfirmationDescription']; ?></td>
                                            <td><?php echo $characterization['TestDescription']; ?></td>
                                            <td><?php echo $characterization['TDate']; ?></td>
                                            <td><?php echo $characterization['ResistanceProfileDescription']; ?></td>
                                            <td><?php echo $characterization['AnotherResistance']; ?></td> 
                                            <td><?php echo $characterization['PriorTreatmentDescription']; ?></td>
                                            <td><?php echo $characterization['OtherPriorTreatment']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="12"><?php echo lang('No TB characterization found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <!-- Histórico de Tratamento de TB -->
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('TB Treatment History'); ?></div>
                <div class="panel-body">
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-striped input-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo lang('Start Date'); ?></th>
                                    <th><?php echo lang('End Date'); ?></th>
                                    <th><?php echo lang('Regimen'); ?></th>
                                    <th><?php echo lang('Duration (Months)'); ?></th>
                                    <th><?php echo lang('Outcome'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($tb_treatment_history)): ?>
                                    <?php foreach ($tb_treatment_history as $index => $treatment): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $treatment['start_date']; ?></td>
                                            <td><?php echo $treatment['end_date']; ?></td>
                                            <td><?php echo $treatment['regimen']; ?></td>
                                            <td><?php echo $treatment['duration_months']; ?></td>
                                            <td><?php echo $treatment['outcome']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6"><?php echo lang('No treatment history found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Comorbidades -->
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('Comorbidities'); ?></div>
                <div class="panel-body">
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-striped input-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo lang('Patology'); ?></th>
                                    <th><?php echo lang('Date'); ?></th>
                                    <th><?php echo lang('Treatment'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($comorbidities)): ?>
                                    <?php foreach ($comorbidities as $index => $comorbidity): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $comorbidity['patology_name']; ?></td>
                                            <td><?php echo $comorbidity['date']; ?></td>
                                            <td><?php echo $comorbidity['treatment']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5"><?php echo lang('No comorbidities found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Diabetes Mellitus -->
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('Diabetes Mellitus Screening'); ?></div>
                <div class="panel-body">
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-striped input-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo lang('Screening Date'); ?></th>
                                    <th><?php echo lang('Fasting Glucose'); ?></th>
                                    <th><?php echo lang('Diagnosis'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($diabetes_screening)): ?>
                                    <?php foreach ($diabetes_screening as $index => $screening): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $screening['date']; ?></td>
                                            <td><?php echo $screening['fasting_glucose']; ?></td>
                                            <td><?php echo $screening['diagnosis']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4"><?php echo lang('No screenings found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Teste de HIV -->
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('HIV Screening Records'); ?></div>
                <div class="panel-body">
                    <div style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-striped input-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo lang('Test Date'); ?></th>
                                    <th><?php echo lang('Test Result'); ?></th>
                                    <th><?php echo lang('Currently on TARV'); ?></th>
                                    <th><?php echo lang('NID TARV'); ?></th>
                                    <th><?php echo lang('TARV Start Date'); ?></th>
                                    <th><?php echo lang('Current TARV'); ?></th>
                                    <th><?php echo lang('TPC'); ?></th>
                                    <th><?php echo lang('Treatment'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="tbody_hiv_screening">
                                <?php if (!empty($screenings)): ?>
                                    <?php foreach ($screenings as $index => $screening): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $screening['test_date']; ?></td>
                                            <td><?php echo $screening['test_result']; ?></td>
                                            <td><?php echo $screening['is_tarv_started'] == 1 ? 'Sim' : 'Não'; ?></td>
                                            <td><?php echo $screening['NIDTARV']; ?></td>
                                            <td><?php echo $screening['tarv_start_date']; ?></td>
                                            <td><?php echo $screening['current_tarv']; ?></td>
                                            <td><?php echo $screening['tpc']; ?></td>
                                            <td><?php echo $screening['type_tpc']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9"><?php echo lang('No screenings found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
