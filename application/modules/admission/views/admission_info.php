<div class="panel panel-success ">
    <div class="panel-heading"><b><?php echo lang('Admission Information')?></b></div>
    <table class="table table-condensed">
        <tbody>
        <tr>
<!--            <td>BHT: <b></b></td>-->
            <td><?= lang('DateTime of Admission'); ?>: <?php echo $admission->AdmissionDate ?></td>
<!--            <td>Onset Date:</td>-->
<!--            <td>Doctor: <input type="button" class="btn btn-xs btn-warning pull-right"-->
<!--                               onclick="self.document.location='http://203.241.247.126/QCH/index.php/form/edit/admission/15'"-->
<!--                               value="Edit"></td>-->
            <td ><?= lang ('Complaint')?>: <b><?php echo $admission->Complaint?></b></td>
            <td ></td>
        </tr>
        <tr>
            <td ><?= lang ('Ward')?>: <a href="#"><?= $admission->Ward["Name"];?></a></td>
            <td ><?= lang ('Room')?>: <a href="#"><?= $admission->RoomNo->Name;?></a></td>
            <td><?= lang ('Bed')?>: <a href="#"><?= $admission->BedNo->BedNo;?></a></td>

        </tr>

        <tr>
            <td ><?= lang('Remarks'); ?>: <?php echo $admission->Remarks?></td>
            <td>
            
                <?php 
                    echo lang('User') . ': <b>';
                    $this->load->model('m_user'); 
                    $user_name = $this->m_user->get_name_by_uid($admission->CreateUser); 

                    echo $user_name ? $user_name : 'Desconhecido';
                    echo '</b>';
                ?>
            </td>

            
        </tr>
        </tbody>
    </table>
</div>
