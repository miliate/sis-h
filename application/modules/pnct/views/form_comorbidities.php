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
            <div id="success_message" class="alert alert-success" style="display:none;"><?php echo lang('Comorbidity added successfully.'); ?></div>

            <!-- Tabela de Comorbidades -->
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('Existing Comorbidities'); ?></div>
                <div class="panel-body">
                    <div style="height: 200px; overflow-y: auto;">
                        <table class="table input-sm" id="table_comorbidity">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo lang('Patology'); ?></th>
                                    <th><?php echo lang('Date'); ?></th>
                                    <th><?php echo lang('Treatment'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tbody_comorbidity">
                                <?php if (!empty($comorbidities)): ?>
                                    <?php foreach ($comorbidities as $index => $comorbidity): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $comorbidity['patology_name']; ?></td>
                                            <td><?php echo $comorbidity['date']; ?></td>
                                            <td><?php echo $comorbidity['treatment']; ?></td>
                                            <td align="center">
                                            <a href="<?php echo site_url('pnct/invalidate/' . $comorbidity['id']); ?>" class="btn btn-warning" onclick="return confirm('<?php echo lang('Are you sure you want to invalidate this screening?'); ?>');"><?php echo lang('Invalidate'); ?></a>
                                            </td>
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

            <!-- Formulário de Comorbidades -->
            <form id="comorbidity_form" role="form" action="<?php echo site_url('pnct/create'); ?>" method="post">
                <input type="hidden" name="PID" value="<?php echo $PID; ?>" />

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="patology_id"><?php echo lang('Patology'); ?></label>
                        <select class="form-control form-control-sm" name="patology_id" id="patology_id">
                            <?php foreach ($patologies as $patology): ?>
                                <option value="<?php echo $patology['id']; ?>"><?php echo $patology['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo form_error('patology_id', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="date"><?php echo lang('Date'); ?></label>
                        <input type="date" class="form-control form-control-sm" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" />
                        <?php echo form_error('date', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="treatment"><?php echo lang('Treatment'); ?></label>
                        <textarea class="form-control form-control-sm" name="treatment" id="treatment" rows="1"><?php echo set_value('treatment'); ?></textarea>
                        <?php echo form_error('treatment', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group col-md-1 text-center" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary"><?php echo lang('Save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
