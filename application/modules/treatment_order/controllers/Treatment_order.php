<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Treatment_Order extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_treatment');
        $this->load->model('m_admission');
        $this->load->model('m_treatment_order');
        $this->load_form_language();
    }

    private function get_list_treatment($type)
    {
        $result = $this->m_treatment->order_by('Treatment')->dropdown('TREATMENTID', 'Treatment');
        return $result;
    }



    public function create_adm_treatment($adm_id)
    {
        $this->load->model('m_emergency_admission');
        $this->load->model('m_admission');
        $opd_visit = $this->m_admission->get($adm_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'ADM', $adm_id);
    }

    public function create_emr_treatment($emr_id)
    {
        $this->load->model('m_emergency_admission');
        $emr = $this->m_emergency_admission->get($emr_id);
        $pid = $emr->PID;
        $this->create($pid, 'EMR', $emr_id);
    }

    public function create_opd_treatment($opd_id)
    {
        $this->load->model('m_opd_visit');
        $opd_visit = $this->m_opd_visit->get($opd_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'OPD', $opd_id);
    }

    public function create($pid, $type, $ref_id)
    {
        // Load treatment options and other initial data
        $data = array(
            'id' => 0,
            'treatment_options' => $this->get_list_treatment($type),
            'default_remarks' => '',
            'default_active' => '',
            'pid' => $pid,
            'ref_type' => $type,
            'ref_id' => $ref_id,
        );

        switch ($type) {
            case ('ADM'):
                $data['admission'] = $this->m_admission->as_array()->get($ref_id);
                break;
            case ('EMR');
                $data["visit_info"] = $this->m_emergency_admission->as_array()->get($ref_id);
                break;
            case ('OPD'):
                $data["opd_visits_info"] = $this->m_opd_visit->as_array()->get($ref_id);
                $data["is_discharged"] = $data["opd_visits_info"]["discharge_order"];
                break;
        }
        // Set common validation rules
        $this->set_common_validation();

        // If form validation fails, reload the form with existing data
        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            // Generate a unique TreatmentOrderItemID using uniqid()
            $treatmentOrderItemID = uniqid('TOID_', true);

            // If form validation succeeds, process the submitted data
            $selected_treatments = json_decode($this->input->post('selected_treatments'), true);

            // Ensure selected treatments data is valid
            if (is_array($selected_treatments)) {
                // Insert each selected treatment into the database with the unique TreatmentOrderItemID
                foreach ($selected_treatments as $treatment) {
                    if (isset($treatment['treatment_id']) && isset($treatment['remarks'])) {
                        $insert_data = array(
                            'RefType' => $type,
                            'RefID' => $ref_id,
                            'TreatmentID' => $treatment['treatment_id'],
                            'TreatmentOrderItemID' => $treatmentOrderItemID, // Use the unique code
                            'Remarks' => $treatment['remarks'],
                            'Status' => 'Pending',
                            'PID' => $pid,

                        );

                        $this->m_treatment_order->insert($insert_data);
                    }
                }

                // Set flash message indicating successful creation
                $this->session->set_flashdata('msg', 'Created');

                // Redirect to the appropriate view based on reference type
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
                    default:
                        // Default redirection if type is unrecognized
                        $this->redirect_if_no_continue('dashboard'); // Redirect to a generic dashboard or homepage
                        break;
                }
            } else {
                // Handle invalid selected_treatments data
                $this->session->set_flashdata('error', 'Invalid treatment selection data.');
                $this->load_form($data);
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
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/treatment');
        }
    }

    public function edit_status($item_id)
    {
        // Obter tratamentos pelo TreatmentOrderItemID
        $treatments = $this->m_treatment_order->get_treatments_by_item_id($item_id);
        if (empty($treatments)) {
            // Use a proper error handling method
            $this->session->set_flashdata('error', 'ID does not exist.');
            redirect('/treatment_order/search');
        }

        $data['pid'] = $treatments[0]->PID;
        $data['item_id'] = $item_id;
        $data['default_status'] = $treatments[0]->Status;
        $data['default_remarks_nurse'] = '';
        $data['treatments'] = $treatments;

        // Set validation rules
        $this->form_validation->set_rules('status[]', lang('Status'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim|xss_clean');
        $this->form_validation->set_rules('remarks_nurse[]', lang('Remarks Nurse'), 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            // Redisplay the form with validation errors
            $this->render('form_treatment_order_status', $data);
        } else {
            $remarks_nurse = $this->input->post('remarks_nurse');
            $statuses = $this->input->post('status');

            // Atualizar cada tratamento com o mesmo TreatmentOrderItemID
            foreach ($treatments as $treatment) {
                if ($treatment->Status != 'Done') {
                    $update_data = array(
                        'Status' => isset($statuses[$treatment->OrderTreatmentID]) ? $statuses[$treatment->OrderTreatmentID] : $treatment->Status,
                        'Remarks_Nurse' => isset($remarks_nurse[$treatment->OrderTreatmentID]) ? $remarks_nurse[$treatment->OrderTreatmentID] : $treatment->Remarks_Nurse
                    );
                    $this->m_treatment_order->update($treatment->OrderTreatmentID, $update_data);
                }
            }

            $this->session->set_flashdata('msg', 'Updated successfully.');
            redirect('/treatment_order/search');
        }
    }

    public function nursing_care($ref_id,  $treatment_type = '')
    {
        $emr_id = htmlspecialchars($ref_id);
        $treatmentType = htmlspecialchars($treatment_type);

        $this->load->model('m_emergency_admission');
        $emr = $this->m_emergency_admission->get($emr_id);
        $pid = $emr->PID;

        $datetime = date("Y-m-d") . " " . date('H:i');
        $refType = $this->session->userdata('department');



        $data = array(
            'id' => 0,
            'nursing_cares' => $this->m_treatment->get_type_in([$treatmentType]),
            'default_remarks' => '',
            'default_date' => $datetime,
            'pid' => $pid,
            'ref_type' => $refType,
            'ref_id' => $emr_id,
            'treatment_type' => $treatmentType
        );


        $data["visit_info"] = $this->m_emergency_admission->as_array()->get($emr_id);

        $this->form_validation->set_rules('selectedCares', lang('Nursing Cares'), 'trim|xss_clean');
        $this->form_validation->set_rules('selectedCares', lang('Nursing Cares'), 'trim|xss_clean');




        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_nursing_care', $data);
        } else {

            $nursing_cares = json_decode($this->input->post('selectedCares'), true);
            $data = [];
            if (isset($nursing_cares)) {

                foreach ($nursing_cares as $cares) {
                    $cares['PID'] = $pid;
                    $cares['RefType'] = $refType;
                    $cares['Active'] = "1";
                    $cares['RefID'] = $emr_id;
                    $cares['Status'] = 'Done';
                    $cares['TreatmentOrderItemID'] = uniqid('TOID_', true);
                    $cares['CreateUser'] = $this->session->userdata('uid');
                    $cares['CreateDate'] = date('Y-m-d H:i:s');
                    $data[] = $cares;
                }

                $this->m_treatment_order->insert_batch($data);
            }

            $this->session->set_flashdata('msg', 'Entrada adicionada com sucesso.');
            redirect('patient_note/nursing_diary/' . $pid . '/' . $emr_id);
        }
    }




    private function set_common_validation()
    {
        // $this->form_validation->set_rules('treatment', 'Treatment', 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim|xss_clean');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|xss_clean');
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


    public function get_diary_treatments($ref_id = '', $type = '', $date, $mode = 'HTML')
    {

        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $data = array();
        $data['type'] = $type;
        $data["patient_treatments"] = $this->m_treatment_order->get_by_type_and_date(htmlspecialchars($ref_id), htmlspecialchars($type), htmlspecialchars($date));

        if ($mode == "HTML") {
            $this->load->vars($data);
            
            $this->load->view('patient_treatment_list');
        } else {
            return $data["patient_treatment_list"];
        }
    }

    public function search()
    {
        $this->set_top_selected_menu('treatment_order/search');
        $department = $this->session->userdata('department');
        $qry = $this->m_treatment_order->search_treatment_orders($department);
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list");
        $page->setDivClass('');
        $page->setRowid('TreatmentOrderItemID'); // Set to TreatmentOrderItemID for editing
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array(lang("Treatment Order Item ID"), lang("Name"), lang("Time"), lang("Department"), lang("Patient ID"), lang("Patient Name"), lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("TreatmentOrderItemID", array("search" => true, "hidden" => true, "width" => 140));
        $page->setColOption("TreatmentName", array("search" => true, "hidden" => false, "width" => 50));
        $page->setColOption("CreateDate", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption("RefType", array("search" => true, "hidden" => false, "width" => 50));
        $page->setColOption("PID", array("search" => true, "hidden" => false, "width" => 50));
       
        $page->setColOption('Status', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':All;' . lang('Pending') . ':' . lang('Pending') . ';' . lang('Done') . ':' . lang('Done')
            )
        ));
        $page->setAfterInsertRow('function(rowid, data){
            var alertText = \'\';
            for (property in data) {
                alertText += data[property];
            }
            if (alertText.match(/^.*Pending/) || alertText.match(/^.*Pendente/))
            {
                $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
            }
            if (alertText.match(/^.*Done/) || alertText.match(/^.*Concluido/))
            {
                $(\'#\'+rowid).css({\'background\':\'#7deaea\'});
            }
        }');
        $page->gridComplete_JS = "function() {
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
