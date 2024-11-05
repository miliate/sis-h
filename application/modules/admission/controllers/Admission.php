<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admission extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient');
        $this->load->model('m_doctor');
        $this->load->model('m_admission');
        $this->load->model('m_admission_transfer');
        $this->load->model('m_ward');
        $this->load->model('m_refer_to_adm');
        $this->load->model('m_ward_rooms');
        $this->load->model('m_ward_beds');
    }

    public function index()
    {
        return;
    }

    public function get_dropdown_doctor()
    {
        //$res = $this->m_doctor->order_by('Name', 'asc')->dropdown('Mapped_User_ID', 'Name');
        $res = $this->m_doctor->order_by('Name', 'asc')->dropdown('Doctor_ID', 'Name');
        $res[''] = '';
        return $res;
    }



    public function create($refer_id)
    {
        $refer = $this->m_refer_to_adm->get($refer_id);
        if (empty($refer)) {
            die('Wrong ID');
        }
        foreach ($this->m_ward->dropdown('WID', 'Name') as $wid => $name) {
            $data['ward_option'][$wid] = $name;
        }

        $wid = $this->m_ward->get_name_by_wid($refer->ward);
        $rooms = array('' => '');
        foreach ($this->m_ward_rooms->get_all_names($refer->ward) as $room) {
            $rooms[$room['RID']] = $room['Name'];
        }

        $data['pid'] = $refer->PID;
        $data['ref_type'] = $refer->RefType;
        $data['ref_id'] = $refer->RefID;
        $data['default_complaint'] = $refer->Complaint;
        $data['default_remarks'] = $refer->Remarks;
        $data['default_ward']  = $wid['Name'];
        $data['default_doctor'] = $this->session->userdata("name");
        $data['default_room_no'] = $rooms;
        $data['default_bed_no'] = '';
        $data['default_time'] = date("Y-m-d H:i:s");
        $data['ugid'] = $this->get_session('user_group_id');

        // $data['dropdown_doctor'] = $this->get_dropdown_doctor();

        // $this->form_validation->set_rules('doctor', 'Doctor', 'xss_clean|required');
        $this->form_validation->set_rules('room_no', 'Room No', 'trim|xss_clean|required');
        $this->form_validation->set_rules('bed_no', 'Bed No', 'trim|xss_clean|required');
        $this->form_validation->set_rules('ward', 'Ward', 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');

        $ugid = $this->get_session('user_group_id');
        if ($ugid == 21) {
            $doctor = $this->get_session('uid');
        } else {
            $doctor = $this->input->post('doctor');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $insert_data = array(
                'PID' => $refer->PID,
                'refer_from_id' => $refer_id,
                'AdmissionDate' => $this->input->post('date_time'),
                'Complaint' => $refer->Complaint,
                'Remarks' => $this->input->post('remarks'),
                // 'Doctor_ID' => $this->input->post('doctor'),
                'RoomNo' => $this->input->post('room_no'),
                'BedNo' => $this->input->post('bed_no'),
                // 'Ward' => $this->input->post('ward'),
                'Active' => 1,
                'Doctor' => $doctor,
                'ward' => $wid['WID']

            );
            $admission_id = $this->m_admission->insert($insert_data);
            $update_data = array(
                'Status' => 'Referred',
                'AdmissionID' => $admission_id
            );
            $this->m_refer_to_adm->update($refer_id, $update_data);
            $update_status = array(
                'status' => 'Unavailable',
                'LastUpDate' => date('Y-m-d H:i:s'),
                'LastUpDateUser' => $this->get_session('uid')
            );
            $this->m_ward_beds->update($this->input->post('bed_no'), $update_status);
            $this->redirect_if_no_continue('admission/view/' . $admission_id);
        }
    }

    public function get_dropdown_beds($rid)
    {
        $beds = array();
        foreach ($this->m_ward_beds->get_all_beds_by_rid($rid) as $bed) {
            $beds[$bed['BID']] = $bed['BedNo'];
        }

        if (empty($beds)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Room ID não recebido']));
            return;
        }

        $response = ['beds' => $beds];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function view($adm_id)
    {
        $data = array();
        $data['admission'] = $this->m_admission->as_array()->get($adm_id);
        $data['PID'] = $data['admission']['PID'];
        $data['Ward'] = $data['admission']['Ward'];
        $pid = $data['admission']['PID'];
        $BedNumber = $this->m_admission->return_all($pid)->BedNo;
        $status = array(

            'status' => 'Availeble',
            'LastUpDate' => date('Y-m-d H:i:s'),
            'LastUpDateUser' => $this->get_session('uid'),
            'status' => 'Available'

        );
        $this->m_ward_beds->update($BedNumber, $status);

        //  $this->load->model('m_ward');
        //        $this->load->model('mpersistent');
        //        $this->load->model('madmission');
        //        $this->load->model('mpatient');
        //        $data["admission_drug_order"]  = null;
        //        $data["admission_drug_list"]  = null;
        //        $data["admission_info"] = $this->madmission->get_info($adm_id);
        //        if (isset($data["admission_drug_order"]["admission_prescription_id"])){
        //            $data["admission_drug_list"] = $this->madmission->get_drug_order_list($data["admission_drug_order"]["admission_prescription_id"]);
        //        }

        //        if ($data["admission_info"]["PID"] >0){
        //            $data["patient_info"] = $this->mpersistent->open_id($data["admission_info"]["PID"], "patient", "PID");
        //        }
        //        else{
        //            $data["error"] = "Admission Patient  not found";
        //            $this->load->vars($data);
        //            $this->load->view('admission_error');
        //            return;
        //        }
        //        if (empty($data["patient_info"])){
        //            $data["error"] ="Admission Patient not found";
        //            $this->load->vars($data);
        //            $this->load->view('admission_error');
        //            return;
        //        }
        //        if (isset($data["patient_info"]["DateOfBirth"])) {
        //            $data["patient_info"]["Age"] = Modules::run('patient/get_age',$data["patient_info"]["DateOfBirth"]);
        //        }
        //        $data["PID"] = $data["admission_info"]["PID"];
        //        $data['pid'] = $data["admission_info"]["PID"];;
        //        $data["ADMID"] = $adm_id;

        $this->render('admission_view', $data);
    }

    public function info($adm_id)
    {
        $data["admission"] = $this->m_admission->with('Doctor')->get($adm_id);
        $data["admission"]->Ward = $this->m_ward->get_name_by_wid($this->m_admission->with('Doctor')->get($adm_id)->Ward);
        $data["admission"]->RoomNo = $this->m_ward_rooms->get_room_name_by_rid($this->m_admission->with('Doctor')->get($adm_id)->RoomNo);
        $data["admission"]->BedNo = $this->m_ward_beds->bed_number_by($this->m_admission->with('Doctor')->get($adm_id)->BedNo);

        $this->load->view('admission_info', $data);
    }

    public function ward_transfer($adm_id)
    {
        $data = array();
        $data['adm_id'] = $adm_id;
        $data['admission'] = $this->m_admission->get_info_by_refid($adm_id);
        $data['default_name'] = '';
        $data['default_status'] = '';
        foreach ($this->m_ward->dropdown('WID', 'Name') as $wid => $name) {
            if ($wid != $data['admission']['Ward']) {
                $data['ward_options'][$wid] = $name;
            } else {
                $data['from_option'][$wid] = $name;
            }
        }

        $this->form_validation->set_rules('transfer_from', 'Transfer from', 'trim|xss_clean|required');
        $this->form_validation->set_rules('transfer_to', 'Transfer to', 'trim|xss_clean|required');

        if ($this->form_validation->run() == FALSE) {
            $this->render('admission_ward_transfer', $data);
        } else {
            $this->m_admission->update($adm_id, array('Ward' => $this->input->post('transfer_to')));
            $insert_data = array(
                'ADMID' => $adm_id,
                'TransferFrom' => $this->input->post('transfer_from'),
                'TransferTo' => $this->input->post('transfer_to'),
            );
            $this->m_admission_transfer->insert($insert_data);
            $this->session->set_flashdata(
                'msg',
                'Transferred'
            );
            $this->redirect_if_no_continue('/admission/view/' . $adm_id);
        }
    }

    public function get_previous_ward_transfer($adm_id, $continue = '#', $mode = 'HTML')
    {
        $data = array();
        $data["transfer_list"] = $this->m_admission_transfer->order_by('CreateDate', 'DESC')->with('transfer_to')->with('transfer_from')->get_many_by(array('ADMID' => $adm_id));
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('previous_transfer');
        } else {
            return $data["transfer_list"];
        }
    }

    public function refer_to_adm($pid, $ref_type, $ref_id)
    {
        $data['pid'] = $pid;
        $data['ref_type'] = $ref_type;
        $data['ref_id'] = $ref_id;
        if ($ref_type == 'EMR') {
            $this->load->model('m_emergency_admission');
            $visit = $this->m_emergency_admission->get_info_by_refid($ref_id);
            $complaint = $visit[0]['Complaint'];
            $data['visit_info'] = $visit[0];
        } else if ($ref_type == 'OPD') {
            $this->load->model('m_opd_visit');
            $data["opd_visits_info"] = $this->m_opd_visit->get_info_by_refid($ref_id);
            $data["is_discharged"] = $data["opd_visits_info"]["discharge_order"];
            $visit = $this->m_opd_visit->get($ref_id);
            $complaint = $visit->Complaint;
        }

        $data['default_complaint'] = $complaint;
        $data['default_time'] = date("Y-m-d H:i:s");
        $data['default_remarks'] = '';
        $data['default_active'] = '';
        $data['default_adm_diagnosis'] = '';

        $this->form_validation->set_rules('refer_time', 'Refer Time', 'trim|xss_clean|required');
        //      $this->form_validation->set_rules('order_confirm_password', 'Order Password', 'xss_clean|callback_confirm_password_check');

        foreach ($this->m_ward->dropdown('WID', 'Name') as $wid => $name) {
            $data['ward_option'][$wid] = $name;
        }

        if ($this->form_validation->run($this) == FALSE) {
            $this->render('refer_to_adm', $data);
        } else {
            $this->load->model('m_refer_to_adm');
            $insert_data = array(
                'PID' => $pid,
                'RefType' => $ref_type,
                'RefID' => $ref_id,
                'Complaint' => $complaint,
                'RefTime' => $this->input->post('refer_time'),
                //  'AdmDiagnosis' => $this->input->post('adm_diagnosis'),
                'Remarks' => $this->input->post('remarks'),
                'Active' => $this->input->post('active'),
                'Status' => 'Waiting',
                'ReferBy' => $this->get_session('uid'),
                'ward' => $this->input->post('ward')
            );
            $id = $this->m_refer_to_adm->insert($insert_data);
            switch ($ref_type) {
                case 'EMR':
                    $this->load->model('m_emergency_admission');
                    $update_data = array(
                        'refer_to_adm_id' => $id,
                        'Status' => 'Referred'
                    );
                    $this->m_emergency_admission->update($ref_id, $update_data);
                    $this->redirect_if_no_continue('/emergency_visit/view/' . $ref_id);
                    break;
                case 'OPD':
                    $this->load->model('m_opd_visit');
                    $update_data = array(
                        'refer_to_adm_id' => $id,
                        'Status' => 'Referred'
                    );
                    $this->m_opd_visit->update($ref_id, $update_data);
                    $this->redirect_if_no_continue('/opd_visit/view/' . $ref_id);
                    break;
            }
        }
    }

    public function refer_waiting()
    {
        $this->set_top_selected_menu('admission/refer_waiting');
        $qry = "SELECT
                refer_to_adm.ID,
                refer_to_adm.RefTime,
                refer_to_adm.RefType,
                refer_to_adm.PID,
                CONCAT(patient.FirstName,' ',patient.Name) AS Patient,
                TIMESTAMPDIFF(YEAR, COALESCE(NULLIF(patient.DateOfBirth, '0000-00-00'), patient.DateOfBirthReferred), CURDATE()) As DateOfBirth,
                ward.Name,
                refer_to_adm.Remarks
                FROM refer_to_adm
                JOIN ward ON ward.WID = refer_to_adm.ward
                LEFT JOIN `patient` ON patient.PID = refer_to_adm.PID
                WHERE Status = 'Waiting'
	            ";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list");
        $page->setDivClass('');
        $page->setRowid('ID');
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("", lang("Time"), lang("Department"), lang("Patient ID"), lang("Patient Name"), lang("Age"), lang("Wards"), lang("Remarks")));
        $page->setRowNum(25);
        $page->setColOption("ID", array("hidden" => true));
        $page->setColOption("RefTime", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption("RefType", array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':All;EMR:EMR;OPD:OPD'
            )
        ));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';

        for (property in data)
            alertText +=data[property];
        if (alertText.match(/^.*Critical/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        }');
        if (Modules::run('permission/check_permission', 'admission_visit', 'create')) {
            $page->gridComplete_JS
                = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . site_url("/admission/create") . "/'+rowId+'';
            });
            }";
        }
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->render('refer_list', $data);
    }

    public function get_nursing_notes($admid, $continue, $mode = 'HTML')
    {
        $this->load->model("madmission");
        $data = array();
        $data["nursing_notes_list"] = $this->madmission->get_notes_list($admid);
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('admission_nursing_notes');
        } else {
            return $data["nursing_notes_list"];
        }
    }
 
}
