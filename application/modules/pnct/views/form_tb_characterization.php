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

            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('Characterization of tuberculosis'); ?></div>
                <div class="panel-body">
                    <div style="height: 200px; overflow-y: auto;">
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
                                            <td><?php echo isset($default_tb_location[$characterization['TbLocation']]) ? $default_tb_location[$characterization['TbLocation']] : ''; ?></td>
                                            <td><?php echo $characterization['Location']; ?></td>
                                            <td><?php echo isset($default_bacteriological_confirmation[$characterization['Bacteriological']]) ? $default_bacteriological_confirmation[$characterization['Bacteriological']] : ''; ?></td>
                                            <td><?php echo isset($default_tests[$characterization['Tests']]) ? $default_tests[$characterization['Tests']] : ''; ?></td>
                                            <td><?php echo $characterization['TestDate']; ?></td>
                                            <td><?php echo isset($default_resistance_profile[$characterization['Resistance']]) ? $default_resistance_profile[$characterization['Resistance']] : ''; ?></td>
                                            <td><?php echo $characterization['AnotherResistance']; ?></td>
                                            <td><?php echo isset($default_pretreatment_tb[$characterization['PriorTreatment']]) ? $default_pretreatment_tb[$characterization['PriorTreatment']] : ''; ?></td>
                                            <td><?php echo $characterization['OtherPriorTreatment']; ?></td>
                                            <td align="center">
                                                <a href="<?php echo site_url('pnct/tb_char_invalidate/' . $characterization['id']); ?>" class="btn btn-warning" onclick="return confirm('<?php echo lang('Are you sure you want to invalidate this screening?'); ?>');"><?php echo lang('Invalidate'); ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11"><?php echo lang('No TB characterization found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="tb_characterization_form" role="form" action="<?php echo site_url('pnct/create_tb_characterization/' . $PID); ?>" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="tb_location"><?php echo lang('Location'); ?></label>
                                    <select class="form-control input-sm" name="tb_location" id="tb_location">
                                        <?php foreach ($default_tb_location as $key => $value) : ?>
                                            <option value="<?php echo $key; ?>" <?php echo set_select('tb_location', $key); ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('tb_location'); ?>
                                </div>

                                <div id="dropdown_location" class="form-group" style="display: none;">
                                    <label class="control-label"><?php echo lang('Type'); ?></label>
                                    <div class="well well-sm" style="background: white;">
                                        <?php foreach (['P. Severa' => 'P. Severa', 'P. nao Severa' => 'P. nao Severa'] as $key => $value) : ?>
                                            <div class="radio">
                                                <label><input type="radio" name="location_description" value="<?php echo $key; ?>" <?php echo set_radio('location_description', $key); ?>> <?php echo $value; ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php echo form_error('location_description'); ?>
                                </div>

                                <div id="input_location" class="form-group" style="display: none;">
                                    <label class="control-label" for="local"><?php echo lang('Type'); ?></label>
                                    <input type="text" class="form-control input-sm" name="local" id="local" value="<?php echo set_value('local', $default_local); ?>">
                                    <?php echo form_error('local'); ?>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="Resistance_Profile"><?php echo lang('Resistance Profile'); ?></label>
                                    <select class="form-control input-sm" name="Resistance_Profile" id="Resistance_Profile">
                                        <?php foreach ($default_resistance_profile as $key => $value) : ?>
                                            <option value="<?php echo $key; ?>" <?php echo set_select('Resistance_Profile', $key); ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('Resistance_Profile'); ?>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="another_resistance_profile"><?php echo lang('Other'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-sm" name="another_resistance_profile" id="another_resistance_profile" value="<?php echo set_value('another_resistance_profile', $default_other_resistance_profile); ?>" placeholder="<?php echo lang('Other'); ?>">
                                        <span class="input-group-addon">
                                            <input type="checkbox" name="another_resistance_profile_checkbox" id="another_resistance_profile_checkbox" value="1" <?php echo set_checkbox('another_resistance_profile_checkbox', '1'); ?>> <?php echo lang('Another Resistance Profile'); ?>
                                        </span>
                                    </div>
                                    <?php echo form_error('another_resistance_profile'); ?>
                                    <?php echo form_error('another_resistance_profile_checkbox'); ?>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label" for="default_selected_date"><?php echo lang('Date'); ?></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control input-sm" name="default_selected_date" id="default_selected_date" value="<?php echo set_value('default_selected_date', date('Y-m-d')); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d', strtotime('-150 years')); ?>">
                                    </div>
                                    <?php echo form_error('default_selected_date'); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="Bacteriological_Confirmation"><?php echo lang('Bacteriological Confirmation'); ?></label>
                                    <select class="form-control input-sm" name="Bacteriological_Confirmation" id="Bacteriological_Confirmation">
                                        <?php foreach ($default_bacteriological_confirmation as $key => $value) : ?>
                                            <option value="<?php echo $key; ?>" <?php echo set_select('Bacteriological_Confirmation', $key); ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('Bacteriological_Confirmation'); ?>
                                </div>

                                <div id="vaccines" class="form-group" style="display: none;">
                                    <label class="control-label"><?php echo lang('Tests'); ?></label>
                                    <div class="well well-sm" style="background: white;">
                                        <?php foreach ($default_tests as $key => $value) : ?>
                                            <div class="radio">
                                                <label><input type="radio" name="tests" value="<?php echo $key; ?>" <?php echo set_radio('tests', $key); ?>> <?php echo $value; ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php echo form_error('tests'); ?>
                                </div>

 
                                <div class="form-group">
                                    <label class="control-label" for="TB_prior_treatment"><?php echo lang('Previous Treatment'); ?></label>
                                    <select class="form-control input-sm" name="TB_prior_treatment" id="TB_prior_treatment">
                                        <?php foreach ($default_pretreatment_tb as $key => $value) : ?>
                                            <option value="<?php echo $key; ?>" <?php echo set_select('TB_prior_treatment', $key); ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('TB_prior_treatment'); ?>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="default_other_tb_pretreatment"><?php echo lang('Other'); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-sm" name="default_other_tb_pretreatment" id="default_other_tb_pretreatment" value="<?php echo set_value('default_other_tb_pretreatment', $default_other_tb_pretreatment); ?>" placeholder="<?php echo lang('Other'); ?>">
                                        <span class="input-group-addon">
                                            <input type="checkbox" name="default_other_tb_pretreatment_checkbox" id="default_other_tb_pretreatment_checkbox" value="1" <?php echo set_checkbox('default_other_tb_pretreatment_checkbox', '1'); ?>> <?php echo lang('Other TB Pretreatment'); ?>
                                        </span>
                                    </div>
                                    <?php echo form_error('default_other_tb_pretreatment'); ?>
                                    <?php echo form_error('default_other_tb_pretreatment_checkbox'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#another_resistance_profile').prop('disabled', true);
        $('#another_resistance_profile_text').prop('disabled', true);
        $('#another_resistance_profile_checkbox').change(function () {
            const isChecked = $(this).is(':checked');
            $('#another_resistance_profile').prop('disabled', !isChecked);
            $('#another_resistance_profile_text').prop('disabled', !isChecked);
        });

        $('#default_other_tb_pretreatment').prop('disabled', true);

        $('#default_other_tb_pretreatment_checkbox').change(function () {
            const isChecked = $(this).is(':checked');
            $('#default_other_tb_pretreatment').prop('disabled', !isChecked);
        });

        $('#dropdown_location').hide();
        $('#input_location').hide();
        $('#vaccines').hide();

        $('#tb_location').change(function () {
            const tbLocationVal = $(this).val();
            if (tbLocationVal === 'Pulmonar') {
                $("#dropdown_location").show();
                $("#input_location").hide();
            } else if (tbLocationVal === 'Extrapulmonar') {
                $("#dropdown_location").hide();
                $("#input_location").show();
            } else {
                $("#dropdown_location").hide();
                $("#input_location").hide();
            }
        });

        $('#Bacteriological_Confirmation').change(function () {
            const confirmVal = $(this).val();
            if (confirmVal === 'Bacteriologicamente confirmada') {
                $("#vaccines").show();
            } else {
                $("#vaccines").hide();
            }
        });

        const initialConfirmVal = $('#Bacteriological_Confirmation').val();
        if (initialConfirmVal === 'Bacteriological_Confirmation') {
            $("#vaccines").show();
        }
    });
</script>
