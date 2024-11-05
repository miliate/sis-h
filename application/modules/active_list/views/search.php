<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

<div>
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/active_list', $department); ?>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><b><?php echo lang('Active List') ?></b></div>
                <form method="post" action="<?= site_url('active_list/search/' . $department); ?>" class="form-inline">
                    <div class="form-group">
                        <select id="serviceSelect" name="services[]" multiple class="form-control" disabled>
                            <!-- Options for services will be dynamically populated -->
                        </select>
                    </div>
                    <button type="submit" class="form-control"><?php echo lang('Submit') ?></button>
                </form>

                <div id="patient_list">
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php
                    if ($department == 'EMR') {
                        echo lang('Observer Patient');
                    } elseif ($department == 'OPD') {
                        echo lang('Observer Patient');
                    }
                    ?></h4>
            </div>

            <div class="modal-body">
                <p>
                    <?php
                    if ($department == 'EMR') {
                        echo lang('Do you want to triage this patient?');
                    } elseif ($department == 'OPD') {
                        echo lang('Do you want to observe this patient?');
                    }
                    ?></p>
                <p class="debug-url"></p>
            </div>

            <div class="modal-footer">
                <a id="confirm-Absent" class="btn btn-primary btn-ok pull-left"><?= lang('Absent')?></a>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('No')?></button>
                <a id="confirm-create" class="btn btn-success btn-ok"><?= lang('Yes')?></a>
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

<?php
$this->load->model('m_hospital_service');
if ($department == 'EMR') {
    // Fetch the services
$services = $this->m_hospital_service->get_services_by_department(1);
} elseif ($department == 'OPD') {
    // Fetch the services
$services = $this->m_hospital_service->get_services_by_department(2);
}  


// Combine the abrev and name into a new array
$combined_services = [];
foreach ($services as $service) {
    $combined_services[] = [
        'abrev' => $service['abrev'],
        'name' => $service['name']
    ];
}
?>


<script>
$(document).ready(function() {
   
    $('#serviceSelect').multiselect({
        buttonClass: 'form-control',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 300,
        includeSelectAllOption: true,
        selectAllText: 'Todos Serviços Selecionados',
        filterPlaceholder: 'Pesquisando...',
        nonSelectedText: 'Selecione os Serviços'
    });

    function populateServices(combined_services) {
        var select = $('#serviceSelect');
        select.empty(); // Clear existing options
        
        // Add new options
        $.each(combined_services , function(index, service) {
            select.append('<option value="' + service.abrev + '">' + service.name + '</option>');
        });

        // Refresh the multiselect
        select.multiselect('rebuild');
    }

    // Initial population of services
    populateServices(<?php echo json_encode($combined_services); ?>);
});

</script>