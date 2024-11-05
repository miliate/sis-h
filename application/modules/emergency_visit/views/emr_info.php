<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 30-Oct-15
 * Time: 10:29 AM
 */
?>
<div class="panel panel-danger">
    <div class="panel-heading">
        <b><?=lang("Emergency visit information");?></b>
        <!-- <button class="btn btn-warning pull-right btn-sm"
                onclick="document.location='<?=site_url('/emergency_visit/edit/'. $visit_info["EMRID"] .'/?CONTINUE=emergency_visit/view/'. $visit_info['EMRID'])?>'">
            <?=lang('Edit')?>
        </button> -->
    </div>

    <!-- <input type="hidden" class="" name="area" id="area" value="<?php echo $visit_info['Destination']; ?>"> -->
    <!-- <input type="hidden" class="" name="severity" id="severity" value="<?php echo $visit_info['Severity']; ?>"> -->
    <table class="table table-condensed">
        <tr>
            <td colspan="2"><?= lang('Datetime Visit') ?>: <?php echo $visit_info["CreateDate"]; ?></td>
            <!-- <td colspan="2"><?= lang('Status') ?>: <b><?php echo $visit_info["Status"]; ?></b></td> -->
        </tr>
        <tr>
            <td colspan="2">
                <?= lang('Complaint / Injury') ?>:<b><?php echo $visit_info['Complaint']; ?></b>
            </td>
            <!-- <td colspan="2">
                <?= lang('Observation Doctor') ?>: <?php echo $observed_doctor; ?>
            </td> -->
        </tr>

        <tr>
            <td><?= lang('Weight in KG') ?>: <?php echo $visit_info['Weight'] ?></td>
            <td><?= lang('Height in M') ?>: <?php echo $visit_info['Height'] ?></td>
            <td><?= lang('sys BP') ?>: <?php echo $visit_info['sys_BP'] ?></td>
            <td><?= lang('diast BP') ?>: <?php echo $visit_info['diast_BP'] ?></td>
        </tr>
        <tr>
            <td><?= lang('Temperature in *C') ?>: <?php echo $visit_info['Temperature'] ?></td>
            <td><?= lang('Pulse') ?>: <?php echo $visit_info['heart_rate'] ?></td>
            <!-- <td><?= lang('Saturation') ?>: <?php echo $visit_info['Saturation'] ?></td> -->
            <td><?= lang('Respiratory') ?>: <?php echo $visit_info['respiratory_frequency'] ?></td>
        </tr>
        <!-- <tr>
            <td><?= lang('Voice') ?>: <?php echo render_yes_no($visit_info['Voice']) ?></td>
            <td><?= lang('Pain') ?>: <?php echo render_yes_no($visit_info['Pain']) ?></td>
            <td><?= lang('Un-Responsive') ?>: <?php echo render_yes_no($visit_info['UNR']) ?></td>
            <td><?= lang('Remarks') ?>: <?php echo $visit_info['Remarks'] ?></td>
        </tr> -->

        <!-- <tr>
            <td><span><?=lang('Area in Emergency Department');?>:</span></td>
            <td><span id="area1"><?=lang('Observation area');?>:</span></td>
            <td><?= lang('Triage') ?>:</td>
            <td><span id="severity1" style="color:red;">Emergency</span></td>
        </tr> -->
        <!-- <tr style="border: 1px  gray;">
            <td style="border: 1px  gray;"></td>
            <td style="border: 1px  gray;"><span id="area2"><?=lang('Resuscitation area');?></span></td>
            <td style="border: 1px  gray;"></td>
            <td style="border: 1px  gray;"><span id="severity2" style="color:orange;">Very urgent</span></td>
        </tr>
        <tr style="border: 1px  gray;">
            <td style="border: 1px  gray;"></td>
            <td style="border: 1px gray;"><span id="area3"><?=lang('Waiting area');?></span></td>
            <td style="border: 1px  gray;"></td>
            <td style="border: 1px  gray;"><span id="severity3" style="color:#c2c20a;">Urgent</span></td>
        </tr>
        <tr style="border: 1px  gray;">
            <td style="border: 1px gray;"></td>
            <td style="border: 1px  gray;"><span id="area4"><?=lang('Do not need medical care anymore');?></span></td>
            <td style="border: 1px  gray;"></td>
            <td style="border: 1px  gray;"><span id="severity4" style="color:green;">Non-urgent</span></td>
        </tr>
        <tr style="border: 1px  gray;">
            <td style="border: 1px gray;"></td>
            <td style="border: 1px  gray;"></td>
            <td style="border: 1px  gray;"></td>
            <td style="border: 1px  gray;"><span id="severity5" style="color:blue;">Deceased</span></td>
        </tr> -->

        <!-- <?php
        if ($visit_info['refer_to_adm_id'] > 0) { ?>
            <tr class="warning">
                <td><b>Referred to admission</b></td>
                <td><b>Refer Status: </b><?php echo $refer_to_adm->Status ?></td>
                <td colspan="2"><?php
                    if ($refer_to_adm->AdmissionID > 0) {
                        echo '<b>Admission ID: </b>' . $refer_to_adm->AdmissionID;
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        if ($visit_info['discharge_order'] > 0) { ?>
            <span class="label label-warning" style="font-size: 100%">
                <span class="glyphicon glyphicon-info-sign"></span>
                &nbsp;Ordered discharge. Status: <span style="color: red">
                <?php
                $this->load->model('m_discharge_order');
                $discharge_order = $this->m_discharge_order->get($visit_info['discharge_order']);
                echo $discharge_order->Status;
                ?>
                </span>.
                <?php
                if (Modules::run('permission/check_permission', 'order_discharge', 'edit') && $discharge_order->Status == 'Pending') {
                    echo 'Click <a href="' . base_url() . 'index.php/order_discharge/edit_created/' . $visit_info['discharge_order'] . '">here</a> to edit discharge order.';
                }
                ?>
            </span>
            <?php
        }
        ?> -->
    </table>
</div>


<script>
    //    function Check_Area(val){
    //        var element=document.getElementById('destination1');
    val = document.getElementById('severity').value;
    if (val == 'Emergencia') {
        $('#severity1').attr('style', 'font-weight: bold; font-size: 15px; color:red;');
        document.getElementById("severity1").innerHTML = '☑ Emergencia';
        document.getElementById("severity2").innerHTML = '☐ Muito Urgente';
        document.getElementById("severity3").innerHTML = '☐ Urgente';
        document.getElementById("severity4").innerHTML = '☐ Nao-Urgente';
        document.getElementById("severity5").innerHTML = '☐ Obito';
    }
    else if (val == 'Muito Urgente') {
        $('#severity2').attr('style', 'font-weight: bold; font-size: 15px; color:orange;');
        document.getElementById("severity1").innerHTML = '☐ Emergencia';
        document.getElementById("severity2").innerHTML = '☑ Muito Urgente';
        document.getElementById("severity3").innerHTML = '☐ Urgente';
        document.getElementById("severity4").innerHTML = '☐ Nao-Urgente';
        document.getElementById("severity5").innerHTML = '☐ Obito';
    }
    else if (val == 'Urgente') {
        $('#severity3').attr('style', 'font-weight: bold; font-size: 15px; color:#c2c20a;');
        document.getElementById("severity1").innerHTML = '☐ Emergencia';
        document.getElementById("severity2").innerHTML = '☐ Muito Urgente';
        document.getElementById("severity3").innerHTML = '☑ Urgente';
        document.getElementById("severity4").innerHTML = '☐ Nao-Urgente';
        document.getElementById("severity5").innerHTML = '☐ Obito';
    } else if (val == 'Nao-Urgente') {
        $('#severity4').attr('style', 'font-weight: bold; font-size: 15px; color:green;');
        document.getElementById("severity1").innerHTML = '☐ Emergencia';
        document.getElementById("severity2").innerHTML = '☐ Muito Urgente';
        document.getElementById("severity3").innerHTML = '☐ Urgente';
        document.getElementById("severity4").innerHTML = '☑ Nao-Urgente';
        document.getElementById("severity5").innerHTML = '☐ Obito';
    } else if (val == 'Obito') {
        $('#severity5').attr('style', 'font-weight: bold; font-size: 15px; color:blue;');
        document.getElementById("severity1").innerHTML = '☐ Emergencia';
        document.getElementById("severity2").innerHTML = '☐ Muito Urgente';
        document.getElementById("severity3").innerHTML = '☐ Urgente';
        document.getElementById("severity4").innerHTML = '☐ Nao-Urgente';
        document.getElementById("severity5").innerHTML = '☑ Obito';
    }

    val = document.getElementById('area').value;
    if (val == 'Observation area') {
        $('#area1').attr('style', 'font-weight: bold; font-size: 15px;');
        document.getElementById("area1").innerHTML = '☑ Observation area';
        document.getElementById("area2").innerHTML = '☐ Resuscitation area';
        document.getElementById("area3").innerHTML = '☐ Waiting area';
        document.getElementById("area4").innerHTML = '☐ Do not need medical care anymore';
    }
    else if (val == 'Resuscitation area') {
        $('#area2').attr('style', 'font-weight: bold; font-size: 15px;');
        document.getElementById("area1").innerHTML = '☐ Observation area';
        document.getElementById("area2").innerHTML = '☑ Resuscitation area';
        document.getElementById("area3").innerHTML = '☐ Waiting area';
        document.getElementById("area4").innerHTML = '☐ Do not need medical care anymore';
    }
    else if (val == 'Waiting area') {
        $('#area3').attr('style', 'font-weight: bold; font-size: 15px;');
        document.getElementById("area1").innerHTML = '☐ Observation area';
        document.getElementById("area2").innerHTML = '☐ Resuscitation area';
        document.getElementById("area3").innerHTML = '☑ Waiting area';
        document.getElementById("area4").innerHTML = '☐ Do not need medical care anymore';
    } else if (val == 'Do not need medical care anymore') {
        $('#area4').attr('style', 'font-weight: bold; font-size: 15px; font-size: 15px');
        document.getElementById("area1").innerHTML = '☐ Observation area';
        document.getElementById("area2").innerHTML = '☐ Resuscitation area';
        document.getElementById("area3").innerHTML = '☐ Waiting area';
        document.getElementById("area4").innerHTML = '☑ Do not need medical care anymore';
    }
</script>
