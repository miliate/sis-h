<div class="container-fluid">
    <div class="row">
        <!-- Menu à esquerda -->
        <div class="col-md-2">
            <?php
            echo Modules::run('leftmenu/patient', $PID); 
            ?>
        </div>

        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b><?php echo "<i class='fa fa-group'></i> " . lang('Active List') . " - PNCT"; ?></b>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">
                    <?php 
                    $form_generator = new MY_Form(); 
                    $form_generator->get_pnct_forms_menu($PID); 
                    ?>
                </div>
            </div>

            <!-- Tabela e Formulário de Diabetes Screening -->
            <div id="message1" class="alert alert-danger" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span id="message_text"><?php echo lang('Please insert all data'); ?></span>
            </div>
            <div id="success_message" class="alert alert-success" style="display:none;"><?php echo lang('Diabetes screening added successfully.'); ?></div>

            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('Diabetes Screening'); ?></div>
                <div class="panel-body">
                    <div style="height: 200px; overflow-y: auto;">
                        <table class="table table-striped input-sm" id="table_diabetes_screening">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo lang('Screening Date'); ?></th>
                                    <th><?php echo lang('Fasting Glucose'); ?></th>
                                    <th><?php echo lang('Diagnosis'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tbody_diabetes_screening">
                                <?php if (!empty($screenings)): ?>
                                    <?php foreach ($screenings as $index => $screening): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $screening['date']; ?></td>
                                            <td><?php echo $screening['fasting_glucose']; ?></td>
                                            <td><?php echo $screening['diagnosis']; ?></td>
                                            <td align="center">
                                                <a href="<?php echo site_url('diabetes_screening/invalidate/' . $screening['id']); ?>" class="btn btn-warning btn-sm" onclick="return confirm('<?php echo lang('Are you sure you want to invalidate this screening?'); ?>');"><?php echo lang('Invalidate'); ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5"><?php echo lang('No screenings found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <form id="diabetes_screening_form" role="form" action="<?php echo site_url('diabetes_screening/create'); ?>" method="post">
                <input type="hidden" name="PID" value="<?php echo $PID; ?>" />

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="screening_date"><?php echo lang('Screening Date'); ?></label>
                        <input type="date" class="form-control form-control-sm" name="screening_date" id="screening_date" value="<?php echo set_value('screening_date'); ?>" />
                        <?php echo form_error('screening_date', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="fasting_glucose"><?php echo lang('Fasting Glucose'); ?></label>
                        <input type="number" class="form-control form-control-sm" name="fasting_glucose" id="fasting_glucose" value="<?php echo set_value('fasting_glucose'); ?>" step="0.01" />
                        <?php echo form_error('fasting_glucose', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="diagnosis"><?php echo lang('Diagnosis'); ?></label>
                        <select class="form-control form-control-sm" name="diagnosis" id="diagnosis">
                            <option value="Sim"><?php echo lang('Yes'); ?></option>
                            <option value="Não"><?php echo lang('No'); ?></option>
                        </select>
                        <?php echo form_error('diagnosis', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-1 text-center" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary"><?php echo lang('Save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
