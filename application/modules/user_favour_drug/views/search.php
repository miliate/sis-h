<div>
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/preference'); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Minha Lista de Medicamentos <input type='button' class='btn btn-xs btn-success' onclick=self.document.location="<?=site_url('/user_favour_drug/create')?>" value='Adicionar'></b></div>
                <div id="patient_list">
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>