<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div>
        <div class="col-md-10">


            <?php


            $date = date('Y-m-d');
            if ($this->input->post('date')) {
                $date = htmlspecialchars($this->input->post('date'));
            }
            echo Modules::run('patient/banner', $pid);
            ?>



            <form id="dateForm" method="POST" action="">
                <div class="form-inline" style="">
                    <div class="form-group col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
                        <label class="control-label"><?= lang('Select') . " " . lang('Date') ?>:</label>
                        <input type="date" class="form-control" name="date" id="date" style="width: 300px;" value="<?= $date ?>" />
                    </div>
                </div>

            </form>


            <?php
            echo Modules::run('patient_note/get_nursing_notes',  $ref_id, $date);
            echo Modules::run('treatment_order/get_diary_treatments',  $ref_id, 'Care', $date,  "HTML");
            echo Modules::run('treatment_order/get_diary_treatments',  $ref_id, 'Procedure', $date,  "HTML");
            echo Modules::run('patient_note/patient_tempetarature_history', $pid,  $ref_id);

            ?>

        </div>
    </div>
</div>

<script>
    $('#date').change(function() {
        $('#dateForm').submit();
    });
    document.getElementById('date').setAttribute('max', new Date().toISOString().split("T")[0]);
</script>