<div>
    <div class="row">
        <div class="col-md-2 ">
      <?php echo Modules::run('leftmenu/arquivo_clinico'); //runs the available left menu for preferance ?>
            <?php //echo Modules::run('leftmenu/active_list', $department); //runs the available left menu for preferance ?>
        </div>
    
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Lista de Arquivos</b></div>
                <div id="patient_list">
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>
