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

            <!-- Mensagens de Sucesso e Erro -->
            <div id="message1" class="alert alert-danger" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span id="message_text"><?php echo lang('Please insert all data'); ?></span>
            </div>
            <?php if ($this->session->flashdata('success_message')): ?>
                <div id="success_message" class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo $this->session->flashdata('success_message'); ?>
                </div>
            <?php endif; ?>

            <!-- Tabela de Histórico de Tratamento -->
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('TB Treatment History'); ?></div>
                <div class="panel-body">
                    <div style="height: 200px; overflow-y: auto;">
                        <table class="table input-sm" id="table_treatment_history">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo lang('Start Date'); ?></th>
                                    <th><?php echo lang('End Date'); ?></th>
                                    <th><?php echo lang('Regimen'); ?></th>
                                    <th><?php echo lang('Duration (Months)'); ?></th>
                                    <th><?php echo lang('Outcome'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tbody_treatment_history">
                                <?php if (!empty($treatments)): ?>
                                    <?php foreach ($treatments as $index => $treatment): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $treatment['start_date']; ?></td>
                                            <td><?php echo $treatment['end_date']; ?></td>
                                            <td><?php echo $treatment['regimen']; ?></td>
                                            <td><?php echo $treatment['duration_months']; ?></td>
                                            <td><?php echo $treatment['outcome']; ?></td>
                                            <td align="center">
                                                <a href="<?php echo site_url('tb_treatment_history/invalidate/' . $treatment['id']); ?>" class="btn btn-warning" onclick="return confirm('<?php echo lang('Are you sure you want to invalidate this treatment history?'); ?>');"><?php echo lang('Invalidate'); ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7"><?php echo lang('No treatment history found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Formulário de Histórico de Tratamento -->
            <form id="treatment_history_form" role="form" action="<?php echo site_url('tb_treatment_history/create'); ?>" method="post">
                <input type="hidden" name="PID" value="<?php echo $PID; ?>" />

                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="start_date"><?php echo lang('Start Date'); ?></label>
                        <input type="date" class="form-control form-control-sm" name="start_date" id="start_date" value="<?php echo set_value('start_date'); ?>" />
                        <?php echo form_error('start_date', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="end_date"><?php echo lang('End Date'); ?></label>
                        <input type="date" class="form-control form-control-sm" name="end_date" id="end_date" value="<?php echo set_value('end_date'); ?>" />
                        <?php echo form_error('end_date', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="regimen"><?php echo lang('Regimen'); ?></label>
                        <input type="text" class="form-control form-control-sm" name="regimen" id="regimen" value="<?php echo set_value('regimen'); ?>" />
                        <?php echo form_error('regimen', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="duration_months"><?php echo lang('Duration (Months)'); ?></label>
                        <input type="number" class="form-control form-control-sm" name="duration_months" id="duration_months" value="<?php echo set_value('duration_months'); ?>" />
                        <?php echo form_error('duration_months', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="outcome"><?php echo lang('Outcome'); ?></label>
                        <select class="form-control form-control-sm" name="outcome" id="outcome">
                            <option value="Curado" <?php echo set_select('outcome', 'Curado'); ?>><?php echo lang('Cured')?></option>
                            <option value="Tratamento Completo" <?php echo set_select('outcome', 'Tratamento Completo'); ?>><?php echo lang('Completed Treatment')?></option>
                            <option value="Perda de Seguimento" <?php echo set_select('outcome', 'Perda de Seguimento'); ?>><?php echo lang('Loss to Follow-Up')?></option>
                            <option value="Falência ao Tratamento" <?php echo set_select('outcome', 'Falência ao Tratamento'); ?>><?php echo lang('Treatment Failure')?></option>
                        </select>
                        <?php echo form_error('outcome', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-1 text-center" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary"><?php echo lang('Save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        if ($('#success_message').length) {
            setTimeout(function() {
                $('#success_message').fadeOut('slow');
            }, 3000); 
        }
    });
</script>
