<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_Contact extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_contact');
    }

    public function create($pid, $ref_id = null)
    {
        //        if (!Modules::run('permission/check_permission', 'patient_history', 'create'))
        //            die('You do not have permission!');
        $ip = $this->input->ip_address();
        $data = array();
        $data['pid'] = $pid;
        $data['ref_id'] = $ref_id;
        $data['default_contact_name'] = '';
        $data['default_contact_kinship'] = '';
        $data['default_contact_address'] = '';
        $data['default_contact_working'] = '';
        $data['default_contact_telephone'] = '';
        $data['default_contact_email'] = '';
        $data['default_has_contact'] = false;


        if ($this->input->post('has_contact')) {
            $this->redirect_if_no_continue('/active_list/create/' . $pid);
        }
        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'PID' => $pid,
                'ContactPerson' => $this->input->post('contact_name'),
                'ContactKinship' => $this->input->post('contact_kinship'),
                'ContactAddress' => $this->input->post('contact_address'),
                'ContactWorkingPlace' => $this->input->post('contact_working_place'),
                'ContactTelephone' => $this->input->post('contact_telephone'),
                'ContactEmail' => $this->input->post('contact_email'),
                'CreateIP' => $ip
            );
            $this->m_patient_contact->insert($data);
            //redirect
            $this->session->set_flashdata(
                'msg',
                'REC: ' . ucfirst(strtolower($this->input->post("contact_name"))) . ' created'
            );
            $this->redirect_if_no_continue('/active_list/create/' . $pid);
        }
    }

    public function edit($id)
    {
        if (!Modules::run('permission/check_permission', 'patient', 'edit'))
            die('You do not have permission!');
        $contact = $this->m_patient_contact->get($id);
        if (empty($contact)) {
            die('Id wrong');
        }
        $ip = $this->input->ip_address();
        $data = array();
        $data['pid'] = $id;
        $data['pid'] = $contact->PID;
        $data['default_contact_name'] = $contact->ContactPerson;
        $data['default_contact_kinship'] = $contact->ContactKinship;
        $data['default_contact_address'] = $contact->ContactAddress;
        $data['default_contact_working'] = $contact->ContactWorkingPlace;
        $data['default_contact_telephone'] = $contact->ContactTelephone;
        $data['default_contact_email'] = $contact->ContactEmail;
        $data['default_has_contact'] = false;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'PEMRCID' => $id,
                'ContactPerson' => $this->input->post('contact_name'),
                'ContactKinship' => $this->input->post('contact_kinship'),
                'ContactAddress' => $this->input->post('contact_address'),
                'ContactWorkingPlace' => $this->input->post('contact_working_place'),
                'ContactTelephone' => $this->input->post('contact_telephone'),
                'ContactEmail' => $this->input->post('contact_email'),
                'LastUpDateIP' => $ip
            );
            $this->m_patient_contact->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('/patient/view/' . $contact->PID);
        }
    }

    public function load_history($pid)
    {
        $qry
            = "SELECT HID, SUBSTRING(CreateDate,1,10) as dte, HistoryOfComplaint
	FROM medical_history
	where (PID ='" . $pid . "') and (Active = 1)";
        $this->load->model('mpager', 'history_page');
        $history_page = $this->history_page;
        $history_page->setSql($qry);
        $history_page->setDivId("his_cont"); //important
        $history_page->setDivClass('');
        $history_page->setRowid('HID');
        $history_page->setCaption("History");
        $history_page->setShowHeaderRow(false);
        $history_page->setShowFilterRow(false);
        $history_page->setShowPager(false);
        $history_page->setColNames(array("PID", "", ""));
        $history_page->setRowNum(25);
        $history_page->setColOption("HID", array("search" => false, "hidden" => true));
        $history_page->setColOption("dte", array("search" => false, "hidden" => false, "width" => 70));
        $history_page->gridComplete_JS = "function() {
        $('#his_cont .jqgrow').mouseover(function(e) {
            var rowId = $(this).attr('id');
            $(this).css({'cursor':'pointer'});
        }).mouseout(function(e){
        }).click(function(e){
            var rowId = $(this).attr('id');
           window.location='" . site_url("patient_history/edit") . "/'+rowId+'?CONTINUE=patient/view/" . $pid . "';
        });
        }";
        $history_page->setOrientation_EL("L");
        echo $history_page->render(false);
    }

    public function get_previous_history($pid, $continue, $mode = 'HTML')
    {
        $data["patient_history_list"] = $this->m_medical_history->as_array()->order_by('CreateDate', 'DESC')->get_many_by(array('PID' => $pid));
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_history');
        } else {
            return $data["patient_history_list"];
        }
    }

    public function set_common_validation()
    {
        $this->form_validation->set_rules('contact_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('contact_kinship', 'Contact Kinship', 'trim');
        $this->form_validation->set_rules('contact_address', 'Contact address', 'trim');
        $this->form_validation->set_rules('contact_working_place', 'Working place', 'trim');
        $this->form_validation->set_rules('contact_email', 'Email', 'trim|callback_contact_check');
        $this->form_validation->set_rules('contact_telephone', 'Telephone', 'trim');
    }

    public function contact_check()
    {
        if (!($this->input->post('contact_email') || $this->input->post('contact_telephone'))) {
            $this->form_validation->set_message('contact_check', 'Obrigatório Informar o Telefone ou E-mail');
            return false;
        }

        return true;
    }
}
