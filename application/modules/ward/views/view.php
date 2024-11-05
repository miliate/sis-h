<div>
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/ward'); // Exibe o menu à esquerda ?>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><b><?= lang('Ward_of'); ?></b></div>
                <div id="wards_list">
                    <?php echo $pager;?>
                </div>
            </div>
        </div>
    </div>
</div>
