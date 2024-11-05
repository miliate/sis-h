<?php
/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology Agency of Sri Lanka
<http: www.hhims.org/>
----------------------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify it under the
terms of the GNU Affero General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along
with this program. If not, see <http://www.gnu.org/licenses/> or write to:
Free Software  HHIMS
C/- Lunar Technologies (PVT) Ltd,
15B Fullerton Estate II,
Gamagoda, Kalutara, Sri Lanka
----------------------------------------------------------------------------------
Author: Mr. Thurairajasingam Senthilruban   TSRuban[AT]mdsfoss.org
Consultant: Dr. Denham Pole                 DrPole[AT]gmail.com
URL: http: www.hhims.org
----------------------------------------------------------------------------------
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
//print_r($user_menu);
// $mdsPermission = MDSPermission::GetInstance();

$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Commands");
$menu .= "</a>";

$department = $this->session->userdata('department');

if (Modules::run('permission/check_permission', 'active_patient', 'create')) {
    $menu .= "<a id='add_to_active_list' href='" . base_url() . "index.php/active_list/create/" . $id . "' class='list-group-item'><i class='fa fa-plus-square'></i>&nbsp;" . lang('Add to Active List') . "</a>";
}

if (Modules::run('permission/check_permission', 'clinical_diary', 'create')) {
    $menu .= "<a href='" . base_url() . "index.php/clinical_diary/add/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-book'></span>&nbsp;" . lang('Entry Note') . "</a>";
}


//$menu .= "<a href='" . base_url() . "index.php/emergency_visit/create/" . $id . "' class='list-group-item'><span class='glyphicon glyphicon-eye-open'></span>&nbsp;Create a Emergency Visit</a>";
//$menu .= "<a href='" . base_url() . "index.php/opd_visit/create/" . $id . "' class='list-group-item'><span class='glyphicon glyphicon-eye-open'></span>&nbsp;Create a OPD Visit</a>";
//$menu .= "<a href='" . base_url() . "index.php/form/create/admission/" . $id . "' class='list-group-item'><span class='glyphicon glyphicon-inbox'></span>&nbsp;Give an Admission</a>";
//$menu .= "<a href='" . base_url() . "index.php/patient/clinic/" . $id . "' class='list-group-item '><span class='glyphicon glyphicon-export'></span>&nbsp;Clinic management*</a>";
//$menu .= "<a href='" . base_url() . "index.php/form/create/appointment/" . $id . "/?CONTINUE=patient/view/" . $id . "' class='list-group-item'><span class='glyphicon glyphicon-time'></span>&nbsp;Give an appointment</a>";
//history, allergy, exam and attachement for All

if (Modules::run('permission/check_permission', 'patient_history', 'create')) {
    switch ($department) {
        case ("EMR"):
            $menu .= "<a href='" . base_url() . "index.php/patient_history/create_emr_history/" . $id . '/' . $ref_id . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-header'></span>&nbsp;" . lang('Add History') . "</a>";
            $menu .= "<a href='" . base_url() . "index.php/patient_anamnese/create_emr_anamnese/" . $id . '/' . $ref_id . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-header'></span>&nbsp;" . lang("patient_anamnese_psychological") . "</a>";
            break;
        case ("OPD"):
            $menu .= "<a href='" . base_url() . "index.php/patient_history/create_opd_history/" . $id . '/' . $ref_id . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-header'></span>&nbsp;" . lang('Add History') . "</a>";
            $menu .= "<a href='" . base_url() . "index.php/patient_anamnese/create_opd_anamnese/" . $id . '/' . $ref_id . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-header'></span>&nbsp;" . lang("patient_anamnese_psychological") . "</a>";
            break;
    }
}

if (Modules::run('permission/check_permission', 'patient_examination', 'create')) {
    switch ($department) {
        case ("EMR"):
            $menu .= "<a href='" . base_url() . "index.php/patient_examination/create_emr_examination/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-check'></span>&nbsp;" . lang('Add Examination') . "</a>";
            break;
        case ("OPD"):
            $menu .= "<a href='" . base_url() . "index.php/patient_examination/create_opd_examination/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-check'></span>&nbsp;" . lang('Add Examination') . "</a>";
            break;
    }
}

if (Modules::run('permission/check_permission', 'patient_note', 'create')) {
    $menu .= "<a href='" . base_url() . "index.php/patient_note/add_general_note/" . $id . '/' . $ref_id . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-leaf'></span>&nbsp;" . ($department == "EMR" ? lang('Medical reassessment') : ($department == "ADM" ? lang('Clinical Diary') : '')) . "</a>";
}

if (Modules::run('permission/check_permission', 'nurse_notes', 'create')) {
    $menu .= "<a href='" . base_url() . "index.php/patient_note/view/" . $id . '/' . $ref_id . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-leaf'></span>&nbsp;" . lang('Entry Note') . "</a>";
    $menu .=
        "<a href='" . base_url() . "index.php/patient_note/add_nurse_note/" . $id . '/' . $ref_id . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-leaf'></span>&nbsp;" . lang('Nursing Notes') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/patient_note/nursing_diary/" . $id . '/' . $ref_id . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-leaf'></span>&nbsp;" . lang('Nursing Diary') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/patient_note/risk_screening/" . $id . '/' . $ref_id .  "' class='list-group-item'><span class='glyphicon glyphicon-leaf'></span>&nbsp;" . lang('Risk Sreening') . "</a>";
}

$menu .= "<a href='" . base_url() . "index.php/patient_note/braden_scale/" . $id . '/' . $ref_id . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id . "' class='list-group-item'><span class='glyphicon glyphicon-leaf'></span>&nbsp;" . lang('Braden Scale') . "</a>";

if (Modules::run('permission/check_permission', 'patient_allergy', 'create')) {
    $menu .= "<a href='" . base_url() . "index.php/patient_allergy/add/" . $id . '/' . $ref_id . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id  . "' class='list-group-item'><span class='glyphicon glyphicon-bell'></span>&nbsp;" . lang('Add Allergy') . "</a>";
}

if (Modules::run('permission/check_permission', 'patient_obstetrics', 'create')) {
    if ($patient->Gender == 'F' and ($patient->Personal_Title == 'Sra.' or $patient->Personal_Title == 'Mrs.')) {
        $menu .= "<a href='" . base_url() . "index.php/patient_obstetrics/add/" . $id . '/' . $ref_id  . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id  . "' class='list-group-item'><span class='fa fa-female'></span>&nbsp;" . lang('Obstetric Exams') . "</a>";
    }
}

if (Modules::run('permission/check_permission', 'pnct', 'create')) {
    $menu .= "<a href='" . base_url() . "index.php/patient_pnct/index/" . $id . '/' . $ref_id . "' class='list-group-item'><i class='fa fa-stethoscope'></i>&nbsp;" . lang('PNCT') . "</a>";
}
if (Modules::run('permission/check_permission', 'clinical_storage', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/arquivo_clinico' class='list-group-item'> <i class='fa fa-file'></i> Processos Clinicos</a>";
    $menu .= "<a href='" . base_url() . "index.php/arquivo_clinico/add/" . $id . "' class='list-group-item'> <i class='fa fa-plus'></i> Arquivar Processo</a>";
    $menu .= "<a href='" . base_url() . "index.php/arquivo_clinico/upload/" . $id . "' class='list-group-item'> <i class='fa fa-upload'></i> Anexar Processo</a>";
}

$menu .= "<a href='" . base_url() . "index.php/patient_contact/create/" . $id . '/' . $ref_id  . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id  . "' class='list-group-item'><span class='glyphicon glyphicon-user'></span>&nbsp;" . lang('Add Contact Person') . "</a>";

$menu .= "<a href='" . base_url() . "index.php/patient_details/create/" . $id . '/' . $ref_id  . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id  . "' class='list-group-item'><span class='glyphicon glyphicon-th-list'></span>&nbsp;" . lang('Socioeconomic Profile') . "</a>";

if (Modules::run('permission/check_permission', 'add_diagnosis_statistic', 'create')) {
    $menu .= "<a href='" . base_url() . "index.php/patient_diagnosis/create_diagnosis_for_statistic/" . $id . "' class='list-group-item'><i class='fa fa-plus-square'></i>&nbsp;" . lang('Add Diagnosis') . "</a>";
}

if (Modules::run('permission/check_permission', 'blood_donation', 'create')) {
    $blood_donation_id = Modules::run('blood_donation/exist', $id);
    if ($blood_donation_id > 0) {
        $menu .= "<a href='" . base_url() . "index.php/blood_donation_result/add/" . $blood_donation_id . "/?CONTINUE=patient/view/" . $id . "' class='list-group-item'><span class='glyphicon glyphicon-paperclip'></span>&nbsp;" . lang('Add Blood Donation Result') . "</a>";
    } else {
        $menu .= "<a href='" . base_url() . "index.php/blood_donation/add/" . $id . "/?CONTINUE=patient/view/" . $id . "' class='list-group-item'><span class='glyphicon glyphicon-heart'></span>&nbsp;" . lang('Add Blood Donation') . "</a>";
    }
}

if (Modules::run('permission/check_permission', 'child_birth', 'create')) {
    if ($patient->Gender == 'F' and ($patient->Personal_Title == 'Sra.' or $patient->Personal_Title == 'Mrs.')) {
        $menu .= "<a data-toggle=\"modal\" data-target=\"#addBirthChildModal\" class='list-group-item'><span class='glyphicon glyphicon-tag'></span>&nbsp;" . lang('Add Birth Child') . "</a>";
        $menu .= "<a href='" . base_url() . "index.php/birth/create/" . $id . "' class='list-group-item'><span class='glyphicon glyphicon-picture'></span>&nbsp;" . lang('Add PartoGraph') . "</a>";
    }
}

//$menu .= "<a href='" . base_url() . "index.php/birth/create/" . $id . "' class='list-group-item'><span class='glyphicon glyphicon-tag'></span>&nbsp;" . lang('Add Birth') . "</a>";

if (Modules::run('permission/check_permission', 'special_clinic', 'create')) {
    $menu .= "<a href='" . base_url() . "index.php/patient_hospital_clinic/create/" . $id . "/?CONTINUE=patient/view/" . $id . "' class='list-group-item'><span class='fa fa-plus-square'></span>&nbsp; Marcar Consulta</a>";
}

if (Modules::run('permission/check_permission', 'pathological_anatomy', 'create')) {
    $menu .= "<a href='" . base_url() . "index.php/patient_pathological_anatomy/create/" . $id . '/' . $ref_id  . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id  . "' class='list-group-item'><span class='fa fa-plus-square'></span>&nbsp;" . lang('Add to PA list') . "</a>";
}

if (Modules::run('permission/check_permission', 'patient_operation_order', 'create')) {
    $menu .= "<a href='" . base_url() . "index.php/patient_operation/create/" . $id . '/' . $ref_id  . "/?CONTINUE=patient/view/" . $id . '/' . $ref_id  . "' class='list-group-item'><span class='fa fa-plus-square'></span>&nbsp;" . lang('Add Operating') . "</a>";
}

/*
if (Modules::run('permission/check_permission', 'special_clinic', 'create')) {
    if ($patient->Gender == 'F' and ($patient->Personal_Title == 'Sra.' or $patient->Personal_Title == 'Mrs.')) {
        $menu .= "<a data-toggle=\"modal\" data-target=\"#addBirthChildModal\" class='list-group-item'><span class='glyphicon glyphicon-tag'></span>&nbsp;" . lang('Add Birth Child') . "</a>";
    }
}*/


$menu .= "</div>";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";

$menu .= lang("Prints");
$menu .= "</a>";
// Print patient slip
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "report/pdf/patientSlip/print/$id"
//    ) . "')\" href='#'>Print patient slip</a>";

if (Modules::run('permission/check_permission', 'clinical_storage', 'print')) {

    $menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
        "report/pdf/patientBoletimOpd/print/$id"
    ) . "')\" href='#'><span class='glyphicon glyphicon-print'></span>&nbsp; " . lang('consultation process') . "</a>";
}

if (Modules::run('permission/check_permission', 'active_patient', 'create')) {

    $_department = '';
    $this->_department = $this->session->userdata('department');

    if ($this->_department == 'EMR') {
        $menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
            "report/pdf/patientBoletimSUP/print/$id"
        ) . "')\" href='#'><span class='glyphicon glyphicon-print'></span>&nbsp; " . lang('pediatric emergency bulletin') . " </a>";
    } elseif ($this->_department == 'OPD') {

        $menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
            "report/pdf/patientBoletimOpd/print/$id"
        ) . "')\" href='#'><span class='glyphicon glyphicon-print'></span>&nbsp; Imprimir Boletim</a>";
    }
}

// Print patient card
$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
    "report/pdf/patientCard/print/$id"
) . "')\" href='#'> <span class='glyphicon glyphicon-barcode'></span>&nbsp;" . lang('Print patient card') . "</a>";

// Print patient summery
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "report/pdf/patientSummery/print/$id"
//    ) . "')\" href='#'>Print patient summary</a>";
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "patient/notes/$id"
//    ) . "')\" href='#'>Print nursing notes</a>";
$menu .= "</div>";
$menu .= " </div> \n";
echo $menu;
?>


<!-- Modal -->
<div id="addBirthChildModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= lang('Add Birth Child') ?> da Sra. <?= $patient->Firstname . ' ' . $patient->Name ?></h4>
            </div>
            <div class="modal-body">
                <p>Pesquisar NID do Bebé:</p>
                <div id="custom-search-input">
                    <div class="input-group col-md-12">
                        <input id="child_id" type="text" class="form-control input-medium" placeholder="Pesquisar NID do Bebé" />
                        <span class="input-group-btn">
                            <button id="btn_child_search" class="btn btn-info btn-medium" type="button">
                                <i class="glyphicon glyphicon-search"></i>
                                <?= lang('Search') ?>
                            </button>
                        </span>
                    </div>
                    <div class="" id="child_result">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('Close') ?></button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function child_search() {
        child_id = $("#child_id").val();
        console.log(child_id);
        $.ajax({
            url: "<?php echo base_url() ?>index.php/child_birth/search_child/" + child_id,
            type: "post"
        }).done(function(response) {
            response = JSON.parse(response);
            console.log(response);
            if (response === null) {
                $("#child_result").html("NID inválido");
            } else {
                html = "<div><?= lang('Name') ?>:" + response.Personal_Title + " " + response.Name + " " + response.Firstname + "</div>";
                html += "<div><?= lang('Date of Birth') ?>:" + response.DateOfBirth + "</div>";
                html += "<div><?= lang('Gender') ?>:" + response.Gender + "</div>";
                html += '<a href="<?= base_url() . "index.php/child_birth/add/" . $id . "/" ?>' + response.PID + '" class="btn btn-primary active"><?= lang('Add') ?></a>';
                $("#child_result").html(html);
            }
            //            var html = '';
            //            for (var i = 0; i < response.length; i++) {
            //                console.log(response[i]);
            //                html += '<option value="' + response[i].service_id + '">' + response[i].abrev + '</option>';
            //                if (i == 0) {
            //                    service_id = response[i].service_id;
            //                }
            //            }
            //            $("#entry_service").html(html);

        }).fail(function() {
            alert('Error');
        });
    }

    $(document).ready(function() {
        $('#btn_child_search').click(function() {
            child_search();
        });
    });
</script>