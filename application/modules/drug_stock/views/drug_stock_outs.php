<div class="row">
    <div class="col-md-2">
        <?php echo Modules::run('leftmenu/preference'); //runs the available left menu for preference 
        ?>
    </div>
    <div class="col-md-10 ">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php $form_generator = new MY_Form(lang('Drug'));

                $form_generator->get_pharmacy_manager_reports_menu();
    
                ?>
                <h4><?= lang('Stock inventory'); ?></h4>            
                <form method="get" action="" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-10">
                            <?php
                                $form_generator->dropdown(lang('technician'), 'tecnico', $dropdown_technician, $default_technician);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="data_inicio" class="col-sm-2 control-label"><?php echo lang('date'); ?></label>
                        <div class="col-sm-3">
                            <input type="datetime-local" id="data_inicio" name="data_inicio" class="form-control">
                        </div>
                        <label for="data_fim" class="col-sm-2 control-label"><?php echo lang('end'); ?></label>
                        <div class="col-sm-3">
                            <input type="datetime-local" id="data_fim" name="data_fim" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo lang('search'); ?></button>
                    </div>

                    <!-- <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary"><?php echo lang('search'); ?></button>
                        </div>
                    </div> -->
                </form>

                <?php if (!empty($dados)) : ?>
                    <div style="margin-top: 20px;">

                        <hr>
                        <div style="max-height: 480px; overflow-y: auto;">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>FNM</th>
                                        <th>Designação</th>
                                        <th><?php echo lang('quantity'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dados as $indice => $item) : ?>
                                        <?php if ($item['quantity'] > 0) : // Only show items with quantity greater than 0 
                                        ?>
                                            <tr>
                                                <td><?php echo $indice + 1; ?></td>
                                                <td><?php echo htmlspecialchars($item['fnm']); ?></td>
                                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-5 panel-body ">
                                <div class="text-center">
                                    <p><?php echo lang('technician'); ?></p>
                                    <p><?php echo htmlspecialchars($tecnico); ?></p>
                                    <p><?php echo lang('date'); ?> <?php echo date('d.m.Y'); ?></p>
                                </div>

                            </div>
                            <div class="col-sm-5">
                                <div class="text-center">
                                    <p><?php echo lang('total_cost'); ?> <?php echo htmlspecialchars($total_cost); ?> meticais</p>
                                    <p><?php echo lang('period'); ?></p>
                                    <p><?php echo lang('start'); ?> <?php echo htmlspecialchars($data_inicio); ?></p>
                                    <p><?php echo lang('end'); ?> <?php echo htmlspecialchars($data_fim); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>