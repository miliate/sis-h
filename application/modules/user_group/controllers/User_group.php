<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class User_Group extends FormController
{
    var $FORM_NAME = 'form_user_group';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user_group');
        $this->load->model('m_department');
    }

    public function create()
    {
        $data = array();
        $data['id'] = 0;
        $data['default_name'] = '';
        $data['default_dep_id'] = '';
        $data['default_active'] = '';
        $data['default_remarks'] = '';
        $data['default_main_menu'] = '';

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->show_form($data);
        } else {
            $this->insert();
        }
    }

    public function edit($id)
    {
        $current_user_group = $this->m_user_group->get($id);
        if (empty($current_user_group))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_name'] = $current_user_group->Name;
        $data['default_dep_id'] = $current_user_group->DEPID;
        $data['default_active'] = $current_user_group->Active;
        $data['default_remarks'] = $current_user_group->Remarks;
        $data['default_main_menu'] = $current_user_group->MainMenu;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->show_form($data);
        } else {
            $this->update($id);
        }
    }

    public function ajax() {
        $raw_data = $this->m_user_group->get_all();
        $return = array();
        $return['data'] = array();
        foreach ($raw_data as $row) {
            $row_return = array();
            array_push($row_return, $row->UGID);
            array_push($row_return, $row->Name);
            array_push($row_return, $row->DEPID);
            array_push($row_return, $row->Active);
            array_push($row_return, $row->MainMenu);
            array_push($row_return, $row->Remarks);
            array_push($return['data'], $row_return);
        }
        header('Content-Type: text/plain');
        header('Access-Control-Allow-Origin: *');
        echo json_encode($return);
    }

    public function show_all() {
        $qry = "SELECT
	  opd_treatment.OPDTREATMENTID,
	  opd_treatment.CreateDate,
	  patient.PID as PID,
	  CONCAT(patient.Full_Name_Registered,' ', patient.Personal_Used_Name) as patient_name ,
	  opd_treatment.Treatment,
	  opd_treatment.Status
	  from opd_treatment
	  LEFT JOIN `opd_visits` ON opd_visits.OPDID = opd_treatment.OPDID
	  LEFT JOIN `patient` ON patient.PID = opd_visits.PID
	  where (opd_treatment.Active =1)";
        $this->load->model('mpager',"page");

        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('OPDTREATMENTID');
        $page->setCaption("Procedure order list");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("","Date", "ID", "Patient","Treatment","Status"));
        $page->setRowNum(25);
        $page->setColOption("OPDTREATMENTID", array("search" => false, "hidden" => true));
        $page->setColOption("PID", array("search" => true, "hidden" => false));
        $page->setColOption("CreateDate", array("search" => true, "hidden" => false ));
        $page->setColOption("patient_name", array("search" => true, "hidden" => false));

        $page->setColOption("Status", array("search" => false, "hidden" => false));
        $page->gridComplete_JS
            = "function() {
        $('#patient_list .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
            window.location='".site_url("/form/edit/opd_treatment_update")."/'+rowId+'?CONTINUE=search/procedures_order';
        });
        }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->load->vars($data);
        $this->load->view('search/procedures_order');
    }

    private function set_common_validation() {
        $this->form_validation->set_rules('name', 'User Group Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('dep_id', 'Department', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        $this->form_validation->set_rules('main_menu', 'Main Menu', 'trim|xss_clean');
        $this->form_validation->set_rules('scan_redirect', 'Scan Redirect', 'trim|xss_clean');
    }

    private function show_form($data)
    {
        $data['option_department'] = array();
        foreach ($this->m_department->get_all() as $department) {
            $data['option_department'][$department->DEPID] = $department->Name;
        }
        $this->load_form($data);
    }

    private function insert()
    {
        $data = array(
            'Name' => $this->input->post('name'),
            'DEPID' => $this->input->post('dep_id'),
            'Active' => $this->input->post('active'),
            'Remarks' => $this->input->post('remarks'),
            'MainMenu' => $this->input->post('main_menu'),
        );
        $id = $this->m_user_group->insert($data);
        //redirect
        $this->session->set_flashdata(
            'msg', 'REC: ' . ucfirst(strtolower($this->input->post("name"))) . ' created'
        );
        $this->redirect_if_no_continue('preference/load/user_group');
    }

    private function update($id) {
        $data = array(
            'Name' => $this->input->post('name'),
            'DEPID' => $this->input->post('dep_id'),
            'Active' => $this->input->post('active'),
            'Remarks' => $this->input->post('remarks'),
            'MainMenu' => $this->input->post('main_menu'),
        );
        $this->m_user_group->update($id, $data);
        $this->session->set_flashdata(
            'msg', 'REC: ' . ucfirst(strtolower($this->input->post("name"))) . ' updated'
        );
        $this->redirect_if_no_continue('preference/load/user_group');
    }
}