<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

class Ward extends FormController
{

    /* function __construct(){
         parent::__construct();
         $this->check_login();
         $this->load->library('session');

         if(isset($_GET["mid"])){
             $this->session->set_userdata('mid', $_GET["mid"]);
         }
      }*/


    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_ward');
    }

    public function index()
    {
        //$this->load->view('patient');
        $this->ward_search();
    }

    public function nurse_chart($wid, $dte = null)
    {
        if (!isset($wid) || (!is_numeric($wid))) {
            $data["error"] = "Ward  not found";
            $this->load->vars($data);
            $this->load->view('admission/admission_error');
            return;
        }
        if (!$dte) $dte = date("Y-m-d");
        $this->load->model('mward');
        $this->load->model('mpersistent');
        $data["ward_info"] = $this->mpersistent->open_id($wid, "ward", "WID"); //get the ward info
        $data["patient_list"] = $this->mward->get_patient_list($wid); //get the list of active admission/patient
        if (!empty($data["patient_list"])) {
            for ($i = 0; $i < count($data["patient_list"]); ++$i) {
                $data["prescribe_items_list"][$data["patient_list"][$i]["admission_prescription_id"]] = $this->mward->get_prescribe_items($data["patient_list"][$i]["admission_prescription_id"], null);

                if (!empty($data["prescribe_items_list"][$data["patient_list"][$i]["admission_prescription_id"]])) {
                    for ($j = 0; $j < count($data["prescribe_items_list"][$data["patient_list"][$i]["admission_prescription_id"]]); ++$j) {
                        $data["prescribe_items_list"][$data["patient_list"][$i]["admission_prescription_id"]][$j]["dispence_info"] = $this->mward->get_dispense_info($data["prescribe_items_list"][$data["patient_list"][$i]["admission_prescription_id"]][$j]["prescribe_items_id"], $dte);
                    }
                }
            }
        }
        //print_r($data["patient_list"]);
        //print_r($data["prescribe_items_list"]);
        //exit;
        $this->load->vars($data);

        $this->load->view('ward_nurse_chart');
    }

    public function view($wid, $ops = null)
    {
        $this->load->model('mpersistent');
        $data["ward_info"] = $this->mpersistent->open_id($wid, "ward", "WID");
        $qry = "SELECT
        admission.ADMID,
        admission.AdmissionDate,
        admission.PID,
        patient.Name,
        patient.FirstName,
        admission.Complaint,
        admission.RoomNo,
        admission.BedNo,
        admission.IsDischarged
        from admission
        LEFT JOIN `patient` ON patient.PID = admission.PID
        where (admission.Active =1) and (admission.Ward= '$wid')
            ";
        if ($ops == "discharged") {
            $qry .= " and (admission.OutCome != '') ";
        }

        $this->load->model('mpager', "page");

        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('ADMID');
        $page->setCaption("Patient list");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("", "Admission Date", "Patient ID", "Sur Name", "First Name", "Complaint", "Room No", "Bed No", "Discharged"));
        $page->setRowNum(25);
        $page->setColOption("ADMID", array("search" => false, "hidden" => true));
        $page->setColOption("AdmissionDate", array("width" => 75));
        $page->setColOption("PID", array("width" => 28));
        $page->setColOption("Name", array("width" => 75));
        $page->setColOption("FirstName", array("width" => 90));
        $page->setColOption("Complaint", array("width" => 280));
        $page->setColOption("RoomNo", array("width" => 35));
        $page->setColOption("BedNo", array("width" => 28));
        $page->setColOption('IsDischarged', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':All;0:Inward;1:Discharged'
            ),
            'width' => '28'
        ));

        if ($ops != "discharged") {
            //		    $page->setColOption("DischargeDate", array("search" => true, "hidden" => true));
            //            $page->setColOption("OutCome", array("search" => true, "hidden" => true));
        }
        $page->gridComplete_JS
            = "function() {
        $('#patient_list .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
            window.location='" . site_url("/admission/view") . "/'+rowId+'?BACK=ward/view/$wid';
        });
        }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->render('patient_list', $data);
    }

    public function ward_search()
    {

        $new_page = base_url() . "index.php/search/ward/";
        header("Status: 200");
        header("Location: " . $new_page);
    }

    //Create a Ward

    public function create()
    {
        $data = array();
        $data['id'] = 0;
        $data['default_Name'] = '';
        $data['default_Type'] = '';
        $data['default_Telephone'] = '';
        $data['default_BedCount'] = '';
        $data['default_Remarks'] = '';
        $data['default_Active'] = '';

        $this->set_common_validation();


        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Type' => $this->input->post('Type'),
                'Name' => $this->input->post('Name'),
                'Telephone' => $this->input->post('Telephone'),
                'BedCount' => $this->input->post('BedCount'),
                'Remarks' => $this->input->post('Remarks'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_ward->insert($data);
            $this->session->set_flashdata(
                'msg',
                'Created'
            );
            $this->redirect_if_no_continue('/preference/load/ward');
        }
    }

    public function edit($id)
    {
        $wards = $this->m_ward->get($id);
        if (empty($wards))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_Name'] = $wards->Name;
        $data['default_Type'] = $wards->Type;
        $data['default_Telephone'] = $wards->Telephone;
        $data['default_BedCount'] = $wards->BedCount;
        $data['default_Remarks'] = $wards->Remarks;
        $data['default_Active'] = $wards->Active;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Type' => $this->input->post('Type'),
                'Name' => $this->input->post('Name'),
                'Telephone' => $this->input->post('Telephone'),
                'BedCount' => $this->input->post('BedCount'),
                'Remarks' => $this->input->post('Remarks'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_ward->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/ward');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('Name', 'Wards Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }

    public function search()
    {
        $this->set_top_selected_menu('ward/search');
        $qry = "SELECT
                WID,
                Name,
                Telephone,
                Remarks
                FROM ward
                WHERE Active = 1
                ORDER BY Name ASC";

        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setRowid('WID');
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setCaption("Ward List");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("WID", "Name", "Telephone", "BedCount", "Remarks"));
        $page->setRowNum(25);
        $page->setColOption("WID", array("hidden" => true));
        //default group
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
            	var rowId = $(this).attr('id');
			   	window.location='" . site_url("/ward/view") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");

        $wards = $this->db->query($qry)->result_array();
        
        foreach ($wards as &$ward) {
            $ward_bed_info = $this->m_ward->get_bed_statistics_in_ward($ward['WID']);
            $ward['TotalBeds'] = $ward_bed_info['total_beds'];
            $ward['OccupiedBeds'] = $ward_bed_info['beds_un'];
            $ward['FreeBeds'] = $ward_bed_info['beds_av'];
        }

        $data['wards'] = $wards;

        $data['pager'] = $page->render(false);
        $this->render_search($data);
    }


    public function wardView($id)
    {
        $this->set_top_selected_menu('ward/search');

        $id = $this->db->escape_str($id);
        if (!is_numeric($id)) {
            show_error('Invalid ward ID', 400);
            return;
        }

        $qry = "SELECT
                admission.ADMID,
                patient.PID,
                admission.AdmissionDate AS Data,
                CONCAT(patient.Firstname, ' ', patient.Name) AS Nome,
                patient.Gender AS Sexo,
                patient.DateOfBirth AS dataNascimento,
                TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) AS Idade,
                ward_rooms.Name AS Quarto,
                ward_beds.BedNo AS Cama
            FROM 
                admission
            JOIN 
                patient ON patient.PID = admission.PID
            JOIN 
                ward_beds ON ward_beds.BID = admission.BedNo
            JOIN 
                ward_rooms ON ward_rooms.RID = admission.RoomNo
            WHERE 
                admission.Ward = '{$id}' 
                AND admission.IsDischarged = 0
                AND admission.Active = 1";

        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setRowid('ADMID');
        $page->setDivId("wards_list"); //important
        $page->setDivClass('');
        $page->setCaption(lang("Ward List"));
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array(
            "ADMID",
            lang("PID"),
            lang("Date"),
            lang("Name"),
            lang("Gender"),
            lang("Date of Birth"),
            lang("Age"),
            lang("Room"),
            lang("Bed")
        ));

        $page->setRowNum(25);
        $page->setColOption("ADMID", array("hidden" => true));
        //default group
        $page->gridComplete_JS
            = "function() {
            $('#wards_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . site_url("/admission/view") . "/'+rowId+'';
            });
        }";

        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        // $this->render_search($data);
        $this->load->vars($data);
        $this->qch_template->load_form_layout('view');
    }



    public function wardCount($wid)
    {
        $this->load->model('mpersistent');
        //  $this->load->model('mward');
        $data["ward_info"] = $this->mpersistent->open_id($wid, "ward", "WID");
        $qry = "SELECT SELECT
    count(admission.ADMID)
   /* admission.AdmissionDate,
    admission.PID,
    patient.Name,
    patient.FirstName,
    admission.Complaint,
    admission.RoomNo,
    admission.BedNo,
    admission.IsDischarged */
    FROM admission
    WHERE (admission.Active =1) and (admission.Ward= '$wid')
        ";
        /*if ($ops == "discharged") {
        $qry .= " and (admission.OutCome != '') ";
    }*/
        //var_dump($qry);

    }
}


//////////////////////////////////////////

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
