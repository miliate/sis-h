<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Opd_Visit extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_patient');
        $this->load->model('m_opd_visit');
        $this->load_form_language();
    }

    public function index()
    {
        return;
    }

    public function create($pid, $active_list_id)
    {
        $data = array();
        $data['pid'] = $pid;
        $data['default_entry_time'] = date("Y-m-d H:i:s");
        $data['default_reason']='';
        $data['default_destination']='';
        $data['default_complaint'] = '';
        $data['default_remarks'] = '';
        $data['default_doctor'] = $this->get_session('name') . ' ' . $this->get_session('other_name');

        $this->form_validation->set_rules('date_time_visit', 'Date Time Of Visit', 'trim|xss_clean|required');
        $this->form_validation->set_rules('complaint', lang('Complaint / Injury'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $insert_data = array(
                'PID' => $pid,
                'DateTimeOfVisit' => $this->input->post('date_time_visit'),
                'Complaint' => $this->input->post('complaint'),
                'EntryTime' => $this->input->post('entry_time'),
                'HospitalizationReason' => $this->input->post('reason'),
                'Destination' => $this->input->post('destination'),
                'Remarks' => $this->input->post('remarks'),
                'ActiveListID' => $active_list_id,
                'Doctor' => $this->get_session('uid'),
            );
            $opd_id = $this->m_opd_visit->insert($insert_data);
            $update_active_list = array(
                'VisitID' => $opd_id,
                'Status' => 'Observe'
            );
            $this->load->model('m_patient_active_list');
            $this->m_patient_active_list->update($active_list_id, $update_active_list);
            $this->session->set_flashdata(
                'msg', 'REC: ' . ucfirst($opd_id . ' created')
            );
            $this->redirect_if_no_continue('opd_visit/view/' . $opd_id);
        }
    }

    public function info($opd_id)
    {
        $data['opd_visits_info'] = $this->m_opd_visit->with('Doctor')->as_array()->get($opd_id);
        if ($data['opd_visits_info']['refer_to_adm_id'] > 0) {
            $this->load->model('m_refer_to_adm');
            $data['refer_to_adm'] = $this->m_refer_to_adm->get($data['opd_visits_info']['refer_to_adm_id']);
        }
        $this->load->vars($data);
        $this->load->view('opd_info');
    }

    public function refer_to_adm($opd_id)
    {
        $opd_visit = $this->m_opd_visit->get($opd_id);
        if (empty($opd_visit)) {
            die('Wrong OPD ID');
        }
        redirect('admission/refer_to_adm/'. $opd_visit->PID. '/OPD/'. $opd_id);
//        echo Modules::run('admission/refer_to_adm', $opd_visit->PID, 'OPD', $opd_id, $opd_visit->Complaint);
    }

    public function my_observe_patient()
    {
        $uid = $this->session->userdata('uid');
        $qry = "SELECT
                      OPDID,
                      DateTimeOfVisit,
                      patient.PID,
                      CONCAT(patient.Name,' ',patient.OtherName) AS Patient,
                      opd_visits.Complaint,
                      opd_visits.Status
                      FROM opd_visits
                      INNER JOIN patient ON patient.PID = opd_visits.PID
                      WHERE (Doctor  = " . $uid . ")
                      ";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('OPDID');
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("", "Time", "Patient ID", "Patient", "Complaint", "Status"));
        $page->setRowNum(25);
        $page->setColOption("OPDID", array("search" => false, "hidden" => true));
        $page->setColOption("PID", array("search" => true, "hidden" => false));
        $page->setColOption("Patient", array("search" => true, "hidden" => false));
        $page->setColOption("DateTimeOfVisit", $page->getDateSelector(date('Y-m-d')));
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . site_url("/opd_visit/view") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->qch_template->load_form_layout('my_observe_patient', $data);
    }

    /****************End of MD******************************/
    public function refers()
    {
        $qry = "SELECT opd_visits.OPDID as OPDID, 
			CONCAT(patient.Full_Name_Registered,' ', patient.Personal_Used_Name) as patient_name , 
			opd_visits.CreateDate , 
			opd_visits.Complaint , 
			visit_type.Name as VisitType 
			from opd_visits 
			LEFT JOIN `patient` ON patient.PID = opd_visits.PID 
			LEFT JOIN `visit_type` ON visit_type.VTYPID = opd_visits.VisitType 
			where opd_visits.is_refered =1
			";
        $this->load->model('mpager', "visit_page");
        $visit_page = $this->visit_page;
        $visit_page->setSql($qry);
        $visit_page->setDivId("patient_list"); //important
        $visit_page->setDivClass('');
        $visit_page->setRowid('OPDID');
        $visit_page->setCaption("Referred patient list");
        $visit_page->setShowHeaderRow(false);
        $visit_page->setShowFilterRow(false);
        $visit_page->setShowPager(false);
        $visit_page->setColNames(array("", "Date", "Patient", "Complaint", "OPD Type"));
        $visit_page->setRowNum(25);
        $visit_page->setColOption("OPDID", array("search" => false, "hidden" => true));
        $visit_page->setColOption("CreateDate", array("search" => true, "hidden" => false, "width" => 75));
        $visit_page->setColOption("patient_name", array("search" => true, "hidden" => false));
        $visit_page->setColOption("VisitType", array("search" => true, "hidden" => false, "width" => 75));
        $visit_page->setColOption("Complaint", array("search" => true, "hidden" => false, "width" => 75));
        $visit_page->gridComplete_JS
            = "function() {
        $('#patient_list .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
            window.location='" . site_url("/admission/proceed") . "/'+rowId;
        });
        }";
        $visit_page->setOrientation_EL("L");
        $data['pager'] = $visit_page->render(false);
        $this->load->vars($data);
        $this->load->view('search/opd_refer_search');

    }

    public function get_previous_prescription($opd_id = null, $stock_id = null)
    {
        if (!$opd_id) {
            echo 0;
        }
        if (!$stock_id) {
            echo 0;
        }
        $this->load->model('mopd');
        $data["last_prescription"] = $this->mopd->get_last_prescription($opd_id);
        if (isset($data["last_prescription"]["PRSID"])) {
            $data["list"] = $this->mopd->get_drug_list($data["last_prescription"]["PRSID"], $stock_id);
            $json = json_encode($data["list"]);
            echo $json;
        } else {
            echo 0;
        }

    }

    public function prescribe_all($prsid, $pid, $opdid)
    {
        if (!$prsid) {
            echo 0;
            return;
        }
        if (!$pid) {
            echo 0;
            return;
        }
        if (!$opdid) {
            echo 0;
            return;
        }

        $this->load->model('mopd');
        $this->load->model('mpersistent');
        $data["list"] = $this->mopd->get_prescribe_items($prsid);
        if (!empty($data["list"])) {
            $pres_data = array(
                'Dept' => "OPD",
                'OPDID' => $opdid,
                'PID' => $pid,
                'PrescribeDate' => date("Y-m-d H:i:s"),
                'PrescribeBy' => $this->session->userdata("FullName"),
                'Status' => "Draft",
                'Active' => 1,
                'GetFrom' => "Stock"
            );
            $PRSID = $this->mpersistent->create("opd_presciption", $pres_data);
            if ($PRSID > 0) {
                $pres_data = array();
                for ($i = 0; $i < count($data["list"]); ++$i) {
                    $pres_item = array(
                        'PRES_ID' => $PRSID,
                        'DRGID' => $data["list"][$i]["DRGID"],
                        'HowLong' => $data["list"][$i]["HowLong"],
                        'Frequency' => $data["list"][$i]["Frequency"],
                        'Dosage' => $data["list"][$i]["Dosage"],
                        'Status' => "Pending",
                        'drug_list' => "who_drug",
                        'Active' => 1,
                        'LastUpDate' => date("Y-m-d H:i:s"),
                        'LastUpDateUser' => $this->session->userdata("FullName")
                    );
                    array_push($pres_data, $pres_item);
                }
                $PRS_ITEM_ID = $this->mpersistent->insert_batch("prescribe_items", $pres_data);
                echo $PRSID;
                return;
            }
            echo 0;
            return;
        }
        echo 0;
        return;
    }

    public function prescription_add_favour()
    {
        if ($_POST["PRSID"] > 0) {
            $prisid = $_POST["PRSID"];
            $favour_data = array(
                'name' => $this->input->post("name"),
                'uid' => $this->session->userdata("UID"),
                'Active' => 1
            );
            $this->load->model('mpersistent');
            $this->load->model('mopd');
            $res = $this->mpersistent->create("user_favour_drug", $favour_data);
            if ($res > 0) {
                $data["prescribe_items_list"] = $this->mopd->get_prescribe_items($prisid);
                $d_items = array();
                for ($i = 0; $i < count($data["prescribe_items_list"]); ++$i) {
                    if ($data["prescribe_items_list"][$i]["drug_list"] == "who_drug") {
                        $item = array(
                            "user_favour_drug_id" => $res,
                            "who_drug_id" => $data["prescribe_items_list"][$i]["DRGID"],
                            "frequency" => $data["prescribe_items_list"][$i]["Frequency"],
                            "how_long" => $data["prescribe_items_list"][$i]["HowLong"],
                            "Active" => 1,
                        );
                        $this->mpersistent->create("user_favour_drug_items", $item);
                    }
                }
                echo 1;
                return;
            }
        }
        echo -1;
    }

    public function check_notify($opd)
    {
        $this->load->model("mnotification");
        $data = array();
        if (isset($opd["Complaint"]) && $opd["Complaint"]) {
            $data["complaint_data"] = $this->mnotification->is_complaint_notify($opd["Complaint"]);
            $data["notification_data"] = $this->mnotification->is_opd_notifed($opd["OPDID"]);
            return $data;
        }
        return null;
    }

    public function save_refer()
    {
        $data = array();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model("mpersistent");
        $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
        $this->form_validation->set_rules("Doctor", "Doctor", "numeric|required");
        $this->form_validation->set_rules("PID", "PID", "numeric|required");
        $this->form_validation->set_rules("referred_id", "referred_id", "numeric|required");

        if ($this->form_validation->run() == FALSE) {
            $this->load->vars($data);
            echo Modules::run('opd/reffer_to_admission', $this->input->post("referred_id"));
        } else {
            $status2 = $this->mpersistent->update('opd_visits', 'OPDID', $this->input->post("referred_id"), array("is_refered" => 1, "Remarks" => '[Referred to admission] ' . $this->input->post("Remarks")));
            $this->session->set_flashdata(
                'msg', 'REC: ' . 'OPD Refered'
            );
            header("Status: 200");
            header("Location: " . site_url('opd_visit/view/' . $this->input->post("referred_id")));
            return;
        }
    }

    public function reffer_to_admission($opdid)
    {
        $data = array();
        if (!isset($opdid) || (!is_numeric($opdid))) {
            $data["error"] = "OPD visit not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        $this->load->model('mpersistent');
        $this->load->model('mopd');
        $this->load->model('mpatient');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data["opd_visits_info"] = $this->mopd->get_info($opdid);

        if ($data["opd_visits_info"]["PID"] > 0) {
            $data["patient_info"] = $this->mpersistent->open_id($data["opd_visits_info"]["PID"], "patient", "PID");
        } else {
            $data["error"] = "OPD Patient  not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        if (empty($data["patient_info"])) {
            $data["error"] = "OPD Patient not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age', $data["patient_info"]["DateOfBirth"]);
        }
        $data["patient_info"]["HIN"] = Modules::run('patient/print_hin', $data["patient_info"]["HIN"]);
        $data["doctor_list"] = $this->mpersistent->table_select("
		SELECT UID,CONCAT(Title,FirstName,' ',OtherName ) as Doctor 
		FROM user WHERE (Active = TRUE) AND ((Post = 'OPD Doctor') OR (Post = 'Consultant'))
		");

        $data["ward_list"] = $this->mpersistent->table_select("
		SELECT WID,Name  as Ward 
		FROM ward WHERE (Active = TRUE)
		 ORDER BY Name 
		");

        $data["PID"] = $data["opd_visits_info"]["PID"];
        $data["OPDID"] = $opdid;

        $this->load->vars($data);
        $this->load->view('opd_reffer_admission');
    }

    public function prescription_item_delete($pres_item_id)
    {
        if ($pres_item_id > 0) {
            $this->load->model("mpersistent");
            $data["pres"] = $this->mpersistent->open_id($pres_item_id, "prescribe_items", "PRS_ITEM_ID");
            if ($data["pres"]["PRES_ID"] > 0) {
                if ($this->mpersistent->delete($pres_item_id, "prescribe_items", "PRS_ITEM_ID")) {
                    $this->session->set_flashdata('msg', 'Drug deleted!');
                    echo 1;
                }
            }
            echo 0;

        }
        echo 0;
    }

    public function prescription_send($prsid)
    {
        $this->load->model("mpersistent");
        $pres_data = array(
            'PrescribeDate' => date("Y-m-d H:i:s"),
            'Status' => "Pending",
            'Active' => 1
        );
        //update($table=null,$key_field=null,$id=null,$data)
        if ($this->mpersistent->update("opd_presciption", "PRSID", $prsid, $pres_data) > 0) {
            $this->session->set_flashdata('msg', 'Prescription sent!');
            echo 1;
        } else {
            echo 0;
        }
    }

    public function add_durg_item()
    {
        //print_r($_POST);
        if ($_POST["PRSID"] > 0) {
            $pres_item_data = array(
                'PRES_ID' => $_POST["PRSID"],
                'DRGID' => $this->input->post("wd_id"),
                'HowLong' => $this->input->post("HowLong"),
                'Frequency' => $this->input->post("Frequency"),
                'Dosage' => $this->input->post("Dose"),
                'Status' => "Pending",
                'drug_list' => "who_drug",
                'Active' => 1
            );
            $PRS_ITEM_ID = $this->mpersistent->create("prescribe_items", $pres_item_data);
            if ($PRS_ITEM_ID > 0) {
                //echo Modules::run('opd/new_prescribe',$this->input->post("OPDID"));
                $this->session->set_flashdata('msg', 'Drug added!');
                //($table=null,$key_field=null,$id=null,$data)

                if ($this->input->post("choose_method")) {
                    $this->mpersistent->update("user", "UID", $this->session->userdata("UID"), array("last_prescription_cmd" => $this->input->post("choose_method")));
                }
                $this->session->set_userdata("choose_method", $this->input->post("choose_method"));
                $new_page = base_url() . "index.php/opd_visit/prescription/" . $_POST["PRSID"] . "?CONTINUE=" . $this->input->post("CONTINUE") . "";
                header("Status: 200");
                header("Location: " . $new_page);
            }
        }
    }

    public function save_prescription()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model("mpersistent");
        $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');

        $this->form_validation->set_rules("OPDID", "OPDID", "numeric|xss_clean");
        $this->form_validation->set_rules("PID", "PID", "numeric|xss_clean");
        $this->form_validation->set_rules("wd_id", "Age", "numeric|xss_clean");
        $data = array();
        //Array ( [PRSID] => [CONTINUE] => opd/view/620 [OPDID] => 620 [PID] => 184 [wd_id] => undefined [Frequency] => qds [HowLong] => For 3 days [drug_stock_id] => 2 [choose_method] => by_favour )
        //print_r($_POST);
        //exit;
        if ($this->form_validation->run() == FALSE) {
            $data["error"] = "Save not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        } else {
            if ($this->input->post("PRSID") > 0) {
                $this->add_durg_item();
                return;
            }
            $pres_data = array(
                'Dept' => "OPD",
                'OPDID' => $this->input->post("OPDID"),
                'PID' => $this->input->post("PID"),
                'PrescribeDate' => date("Y-m-d H:i:s"),
                'PrescribeBy' => $this->session->userdata("FullName"),
                'Status' => "Draft",
                'Active' => 1,
                'GetFrom' => "Stock"
            );
            $PRSID = $this->mpersistent->create("opd_presciption", $pres_data);
            if ($PRSID > 0) {
                $pres_item_data = array(
                    'PRES_ID' => $PRSID,
                    'DRGID' => $this->input->post("wd_id"),
                    'HowLong' => $this->input->post("HowLong"),
                    'Frequency' => $this->input->post("Frequency"),
                    'Dosage' => $this->input->post("Dose"),
                    'Status' => "Pending",
                    'drug_list' => "who_drug",
                    'Active' => 1
                );
                $PRS_ITEM_ID = $this->mpersistent->create("prescribe_items", $pres_item_data);
                if ($PRS_ITEM_ID > 0) {
                    //echo Modules::run('opd/new_prescribe',$this->input->post("OPDID"));
                    $this->session->set_flashdata('msg', 'Prescription created!');
                    $new_page = base_url() . "index.php/opd_visit/prescription/" . $PRSID . "?CONTINUE=" . $this->input->post("CONTINUE") . "";
                    header("Status: 200");
                    header("Location: " . $new_page);
                }
            } else {
                $data["error"] = "Save not found";
                $this->load->vars($data);
                $this->load->view('opd_error');
                return;
            }
        }
    }

    public function prescription($prisid)
    {
        if (!isset($prisid) || (!is_numeric($prisid))) {
            $data["error"] = "Prescription  not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        $this->load->model('mpersistent');
        $this->load->model('mopd');
        $this->load->model('mpatient');
        $this->load->helper('string');
        $data['prisid'] = $prisid;
        $data["opd_presciption_info"] = $this->mpersistent->open_id($prisid, "opd_presciption", "PRSID");
        $data["prescribe_items_list"] = $this->mopd->get_prescribe_items($prisid);
        if (isset($data["prescribe_items_list"])) {
            for ($i = 0; $i < count($data["prescribe_items_list"]); ++$i) {
                if ($data["prescribe_items_list"][$i]["drug_list"] == "who_drug") {
                    $drug_info = $this->mpersistent->open_id($data["prescribe_items_list"][$i]["DRGID"], "who_drug", "wd_id");

                }
                $data["prescribe_items_list"][$i]["drug_name"] = isset($drug_info["name"]) ? $drug_info["name"] : '';
                $data["prescribe_items_list"][$i]["formulation"] = isset($drug_info["formulation"]) ? $drug_info["formulation"] : '';
                $data["prescribe_items_list"][$i]["dose"] = isset($drug_info["dose"]) ? $drug_info["dose"] : '';
            }
        }
        $data["my_favour"] = $this->mopd->get_favour_drug_count($this->session->userdata("UID"));
        $data["user_info"] = $this->mpersistent->open_id($this->session->userdata("UID"), "user", "UID");
        if ($data["opd_presciption_info"]["OPDID"] > 0) {
            $data["opd_visits_info"] = $this->mopd->get_info($data["opd_presciption_info"]["OPDID"]);
        }
        if ($data["opd_visits_info"]["PID"] > 0) {
            $data["patient_info"] = $this->mpersistent->open_id($data["opd_visits_info"]["PID"], "patient", "PID");
            $data["patient_allergy_list"] = $this->mpatient->get_allergy_list($data["opd_visits_info"]["PID"]);
        } else {
            $data["error"] = "OPD Patient  not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        if (empty($data["patient_info"])) {
            $data["error"] = "OPD Patient not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age', $data["patient_info"]["DateOfBirth"]);
        }
        if (isset($data["opd_visits_info"]["visit_type_id"])) {
            $data["stock_info"] = $this->mopd->get_stock_info($data["opd_visits_info"]["visit_type_id"]);
        }
        $data["PID"] = $data["opd_visits_info"]["PID"];
        $data["OPDID"] = $data["opd_presciption_info"]["OPDID"];
        $this->load->vars($data);
        $this->load->view('opd_new_prescribe');
    }

    public function new_prescribe($opdid)
    {

        if (!isset($opdid) || (!is_numeric($opdid))) {
            $data["error"] = "OPD visit not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        $this->load->model('mpersistent');
        $this->load->model('mopd');
        $this->load->model('mpatient');
        $data["opd_visits_info"] = $this->mopd->get_info($opdid);
        if (isset($data["opd_visits_info"]["visit_type_id"])) {
            $data["stock_info"] = $this->mopd->get_stock_info($data["opd_visits_info"]["visit_type_id"]);
        }
        if ($data["opd_visits_info"]["PID"] > 0) {
            $data["patient_info"] = $this->mpersistent->open_id($data["opd_visits_info"]["PID"], "patient", "PID");
            $data["patient_allergy_list"] = $this->mpatient->get_allergy_list($data["opd_visits_info"]["PID"]);
        } else {
            $data["error"] = "OPD Patient  not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        if (empty($data["patient_info"])) {
            $data["error"] = "OPD Patient not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age', $data["patient_info"]["DateOfBirth"]);
        }
        $data["user_info"] = $this->mpersistent->open_id($this->session->userdata("UID"), "user", "UID");
        $data["my_favour"] = $this->mopd->get_favour_drug_count($this->session->userdata("UID"));
        $data["PID"] = $data["opd_visits_info"]["PID"];
        $data["OPDID"] = $opdid;
        $this->load->vars($data);

        $this->load->view('opd_new_prescribe');

    }

    public function get_nursing_notes($opdid, $continue, $mode = 'HTML')
    {
        $this->load->model("mopd");
        $data = array();
        $data["nursing_notes_list"] = $this->mopd->get_previous_notes_list($opdid);
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('opd_nursing_notes');
        } else {
            return $data["nursing_notes_list"];
        }
    }

    public function view($opdid)
    {

        $data = array();
        $this->load->model('mpersistent');
        $this->load->model('mpatient');
        $data["opd_visits_info"] = $this->m_opd_visit->as_array()->get($opdid);
        $visit_date = $data["opd_visits_info"]["DateTimeOfVisit"];
        $today = date("Y-m-d H:i:s");
        $diff = abs(strtotime($today) - strtotime($visit_date));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));;
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $data["opd_visits_info"]["days"] = $days + $months * 30 + $years * 365;
        if (isset($data["opd_visits_info"]["PID"])) {
            $data["patient_info"] = $this->mpersistent->open_id($data["opd_visits_info"]["PID"], "patient", "PID");
            //$data["patient_allergy_list"] = $this->mpatient->get_allergy_list($data["opd_visits_info"]["PID"]);
            //$data["patient_exams_list"] = $this->mpatient->get_exams_list($data["opd_visits_info"]["PID"]);
            //$data["patient_history_list"] = $this->mpatient->get_history_list($data["opd_visits_info"]["PID"]);
            //$data["patient_lab_order_list"] = $this->mpatient->get_lab_order_list($data["opd_visits_info"]["PID"]);
//            $data["patient_prescription_list"] = $this->mpatient->get_prescription_list($opdid);
//            $data["patient_treatment_list"] = $this->mpatient->get_treatment_list($opdid);
            //$data["previous_injection_list"] = $this->mpatient->get_previous_injection_list($data["opd_visits_info"]["PID"]);
        } else {
            $data["error"] = "OPD Patient  not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        if (empty($data["patient_info"])) {
            $data["error"] = "OPD Patient not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age', $data["patient_info"]["DateOfBirth"]);
        }
        $data["patient_info"]["HIN"] = Modules::run('patient/print_hin', $data["patient_info"]["HIN"]);

        $data["PID"] = $data["opd_visits_info"]["PID"];
        $data["is_discharged"] = $data["opd_visits_info"]["discharge_order"];
        $data["OPDID"] = $opdid;

        $this->render('opd_view', $data);
    }

}