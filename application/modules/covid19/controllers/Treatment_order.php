<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Covid19 extends FormController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient');
        $this->load->model('m_covid19');
        $this->load_form_language();
    }

    public function index()
    {
        return view('welcome_message');
    }

    private function get_list_treatment($type)
    {
        $all_treatment = $this->m_treatment->order_by('Treatment')->get_many_by(array('Active' => true));
        $result = array();
        $result[''] = '';
        foreach ($all_treatment as $treatment) {
            if (strcasecmp($type, 'all') === 0 || strcasecmp($treatment->Type, 'All') === 0 || strcasecmp($treatment->Type, $type) === 0) {
                $result[$treatment->TREATMENTID] = $treatment->Treatment;
            }
        }
        return $result;
    }

    public function create_adm_treatment($adm_id)
    {
        $this->load->model('m_admission');
        $opd_visit = $this->m_admission->get($adm_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'ADM', $adm_id);
    }

    public function create_emr_treatment($emr_id)
    {
        $this->load->model('m_emergency_admission');
        $emr = $this->m_emergency_admission->get($emr_id);
        $this->create($emr->PID, 'EMR', $emr_id);
    }

    public function create_opd_treatment($opd_id)
    {
        $this->load->model('m_opd_visit');
        $opd_visit = $this->m_opd_visit->get($opd_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'OPD', $opd_id);
    }

    private function create($pid, $type, $ref_id)
    {
        $data = array();
        $data['id'] = 0;
        $data['treatment_options'] = $this->get_list_treatment($type);
        $data['default_treatment'] = '';
        $data['default_remarks'] = '';
        $data['default_active'] = '';
        $data['pid'] = $pid;

        $data['ref_type'] = $type;
        $data['ref_id'] = $ref_id;

        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'RefType' => $type,
                'RefID' => $ref_id,
                'TreatmentId' => $this->input->post('treatment'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks'),
                'Status' => 'Pending',
                'PID' => $pid,
            );
            $id = $this->m_treatment_order->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            switch ($type) {
                case 'OPD':
                    $this->redirect_if_no_continue('opd_visit/view/' . $ref_id);
                    break;
                case 'EMR':
                    $this->redirect_if_no_continue('emergency_visit/view/' . $ref_id);
                    break;
                case 'ADM':
                    $this->redirect_if_no_continue('admission/view/' . $ref_id);
                    break;
            }
        }
    }

    public function edit_created($id)
    {
        $treatment_order = $this->m_treatment_order->get($id);
        if (empty($treatment_order))
            die('Id not exist');
        $data['id'] = $id;
        $data['treatment_options'] = $this->get_list_treatment($treatment_order->RefType);
        $data['default_treatment'] = $treatment_order->TreatmentID;
        $data['default_active'] = $treatment_order->Active;
        $data['default_remarks'] = $treatment_order->Remarks;
        $data['pid'] = $treatment_order->PID;
        $data['ref_type'] = $treatment_order->RefType;
        $data['ref_id'] = $treatment_order->RefID;
        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'TreatmentId' => $this->input->post('treatment'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks'),
            );
            $this->m_treatment_order->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/treatment');
        }
    }

    public function edit_status($id)
    {
        $treatment_order = $this->m_treatment_order->with('treatment')->get($id);
        if (empty($treatment_order))
            die('Id not exist');
        $data['pid'] = $treatment_order->PID;
        $data['id'] = $id;
        $data['default_treatment'] = $treatment_order->treatment->Treatment;
        $data['default_status'] = $treatment_order->Status;
        $data['default_remarks'] = $treatment_order->Remarks;

        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->render('form_treatment_order_status', $data);
        } else {
            $data = array(
                'Status' => $this->input->post('status'),
                'Remarks' => $this->input->post('remarks'),
            );
            $this->m_treatment_order->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/treatment_order/search');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('treatment', 'Treatment', 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
//        $this->form_validation->set_rules('order_confirm_password', 'Order Password', 'xss_clean|callback_confirm_password_check');
    }

    public function get_previous_treatment_list($ref_type, $ref_id = 0, $continue = '#', $mode = '')
    {
        $data = array();
        $data['type'] = $ref_type;
        $data["patient_treatment_list"] = $this->m_treatment_order->as_array()->order_by('CreateDate', 'DESC')->with('treatment')->get_many_by(array('RefType' => $ref_type, 'RefID' => $ref_id, 'Active' => 1));
//        var_dump($data["patient_treatment_list"]);
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_treatment_order');
        } else {
            return $data["patient_treatment_list"];
        }
    }

    public function search()
    {
        $this->set_top_selected_menu('treatment_order/search');
        $department = $this->session->userdata('department');
        $qry = "SELECT
                treatment_order.CreateDate,
                OrderTreatmentID,
                RefType,
                patient.PID,
                CONCAT(patient.Name,' ',patient.OtherName) AS Patient,
                Treatment,
                treatment_order.Status
                FROM treatment_order
                LEFT JOIN treatment ON treatment.TREATMENTID = treatment_order.TreatmentID
                LEFT JOIN patient ON patient.PID = treatment_order.PID
                LEFT JOIN user ON user.UID = treatment_order.OrderBy
                WHERE (treatment_order.Active = 1) AND treatment_order.RefType = '" . $department . "'";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('OrderTreatmentID');
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array(lang("Time"), lang("Order ID"), lang("Department"), lang("Patient ID"), lang("Patient Name"), lang("Treatment"), lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("OrderTreatmentID", array("search" => true, "hidden" => false, "width" => 100));
        $page->setColOption("RefType", array("search" => true, "hidden" => false, "width" => 50));
        $page->setColOption("PID", array("search" => true, "hidden" => false, "width" => 50));
        $page->setColOption("Treatment", array("search" => true, "hidden" => false));
        $page->setColOption("CreateDate", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption('Status', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':All;Pending:Pending;Done:Done'
            )));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
        }
        if (alertText.match(/^.*Pending/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*Done/))
        {
            $(\'#\'+rowid).css({\'background\':\'#7deaea\'});
        }
       }');
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . site_url("/treatment_order/edit_status/") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->render('treatment_order', $data);
    }
}