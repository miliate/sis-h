<div>
    <div class="row">
        <div class="col-md-2 ">
            <?php  echo Modules::run('leftmenu/active_list', $department); //runs the available left menu for preferance ?>

          

<!--
<div class="col-md-12">
    <div class="panel panel-success">
        <div class="panel-heading"><b>Resumo Diário</b></div>

          <?php
          $this->load->model('m_patient_active_nopay');
       $ret = $this->m_patient_active_nopay->order_by('id', 'asc')->dropdown('id', 'name');

       $i=0;
          foreach ($ret as $service) {
              if (strlen($service) > 0) {

                  echo '<span class="label label-warning">'.++$i.'</span>';
                  echo '-'.$service.'<span class="label label-info">5</span><br>';
              }

          }

echo '<span class="label label-warning">25</span>-Outros';
           ?>

    </div>
</div>
        -->

        </div>

        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><b><?php echo "<i class='fa fa-group'></i> ".lang('Active List- Pathological Anatomy')." "; ?></b></div>
                <div id="patient_list">
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="observe-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel1"><?=lang('Observer Patient')?></h4>
            </div>

            <div class="modal-body">
                <p><?=lang('Do you want to observe this patient?')?></p>
                <p class="debug-url"></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('Cancel')?></button>
                <a id="confirm-observe" class="btn btn-danger btn-ok"><?=lang('OK')?></a>
            </div>
        </div>
    </div>
</div>
