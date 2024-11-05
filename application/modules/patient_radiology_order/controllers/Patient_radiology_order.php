<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_Radiology_Order extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_admission');
        $this->load->model('m_radiology');
        $this->load->model('m_radiology_order');
        $this->load->model('m_radiology_order_have_radiology');
        $this->load_form_language();
    }

    public function get_all_radiology_by_group()
    {
        $result = array();
        foreach ($this->m_radiology->get_many_by(array('Active' => true)) as $radiology) {
            if (array_key_exists($radiology->parent_group, $result)) {
                array_push($result[$radiology->parent_group], $radiology);
            } else {
                $result[$radiology->parent_group] = array($radiology);
            }
        }
        return $result;
    }

    public function create_adm_radiology_order($adm_id)
    {
        $this->load->model('m_emergency_admission');
        $this->load->model('m_admission');
        $opd_visit = $this->m_admission->get($adm_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'ADM', $adm_id);
    }

    public function create_emr_radiology_order($emr_id)
    {
        $this->load->model('m_emergency_admission');
        $opd_visit = $this->m_emergency_admission->get($emr_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'EMR', $emr_id);
    }

    public function create_opd_radiology_order($opd_id)
    {
        $this->load->model('m_opd_visit');
        $opd_visit = $this->m_opd_visit->get($opd_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'OPD', $opd_id);
    }

    private function create($pid, $ref_type, $ref_id)
    {
        $data['ref_type'] = $ref_type;
        $data['ref_id'] = $ref_id;
        $data['pid'] = $pid;
        $data['default_active'] = '';
        $data['default_remarks'] = '';
        $data['exam_date'] = '';
        $data['default_exam_date'] = date('Y-m-d');

        $data['radiology_groups'] = $this->get_all_radiology_by_group();
        $this->form_validation->set_rules('radiology[]', 'Radiology', 'required');
        //        $this->form_validation->set_rules('order_confirm_password', 'Order Password', 'xss_clean|callback_confirm_password_check');
        switch ($ref_type) {
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
        // switch ($ref_type) {
        //     case 'EMR':
        //         $this->load->model('m_emergency_admission');
        //         break;
        //     case 'ADM':
        //         // $this->load->model('m_admission');
        //         break;
        //     case 'OPD':

        //         break;
        //     default:
        //         echo 'wrong department';
        //         break;
        // }





        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'PID' => $pid,
                'RefType' => $ref_type,
                'RefId' => $ref_id,
                'ExamDate' => $this->input->POST('exam_date'),
                'Remarks' => $this->input->post('remarks'),
                'OrderBy' => $this->get_session('uid'),
                'Status' => 'Pending',
                'Active' => True
            );
            $radiology_order_id = $this->m_radiology_order->insert($data);
            foreach ($this->input->post('radiology') as $radiology_id) {
                $item = array(
                    'radiology_order_id' => $radiology_order_id,
                    'PID' => $pid,
                    'Status' => 'Pending',
                    'radiology_id' => $radiology_id,
                );
                $this->m_radiology_order_have_radiology->insert($item);
            }
            $this->session->set_flashdata(
                'msg',
                'Created'
            );
            $this->redirect_if_no_continue('opd_visit/view/' . $ref_id);
        }
    }

    public function update($order_id)
    {
        $radiology_order = $this->m_radiology_order->with('order_by')->get($order_id);
        $data['lab_order'] = $radiology_order;
        $data['pid'] = $radiology_order->PID;
        $doctor = $radiology_order->order_by;
        $data['default_order_by'] = $doctor->Title . ' ' . $doctor->Name . ' ' . $doctor->OtherName;
        $data['default_create_time'] = $radiology_order->CreateDate;
        $data['radiology_order_items'] = array();



        foreach ($this->m_radiology_order_have_radiology->with('radiology')->get_many_by(array('radiology_order_id' => $order_id)) as $row) {
            $tmp['ID'] = $row->id;
            $tmp['Name'] = $row->radiology->name;
            $tmp['RefValue'] = $row->radiology->RefValue;
            $tmp['Result'] = $row->result;
            array_push($data['radiology_order_items'], $tmp);
        }
        $this->call_back_items = $data['radiology_order_items'];
        $this->form_validation->set_rules('example', 'Test Result', 'callback_check_result');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load->vars($data);
            $this->load->view('update_radiology_order');
        } else {
            $this->m_radiology_order->update($order_id, array('Status' => 'Done'));
            foreach ($this->input->post('result') as $key => $result) {
                $item = array(
                    'Status' => 'Done',
                    'result' => $result,
                );
                $this->m_radiology_order_have_radiology->update($key, $item);
            }
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('patient_radiology_order/search');
        }
    }

    public function update_result($order_id)
    {
        $radiology_order = $this->m_radiology_order->with('order_by')->get($order_id);
        $data['lab_order'] = $radiology_order;
        $data['pid'] = $radiology_order->PID;
        if ($radiology_order->order_by != null) {
            $doctor = $radiology_order->order_by;
            $data['default_order_by'] = $doctor->Title . ' ' . $doctor->Name . ' ' . $doctor->OtherName;
        } else {
            $data['default_order_by'] = '';
        }
        $data['default_create_time'] = $radiology_order->CreateDate;
        $data['default_exam_date'] = $radiology_order->ExamDate;
        $data['radiology_order_items'] = array();

        foreach ($this->m_radiology_order_have_radiology->with('radiology')->get_many_by(array('radiology_order_id' => $order_id, 'Active' => 1)) as $row) {
            $tmp['ID'] = $row->id;
            $tmp['Name'] = $row->radiology->name;
            $tmp['RefValue'] = $row->radiology->RefValue;
            $tmp['Result'] = $row->result;
            array_push($data['radiology_order_items'], $tmp);
        }
        $this->call_back_items = $data['radiology_order_items'];
        $this->form_validation->set_rules('example', 'Test Result', 'callback_check_result');

        if ($this->form_validation->run($this) == FALSE) {
            $this->render('update_radiology_order', $data);
        } else {
            $this->m_radiology_order->update($order_id, array('Status' => 'Done'));
            foreach ($this->input->post('result') as $key => $result) {
                $item = array(
                    'Status' => 'Done',
                    'result' => $result,
                );
                $this->m_radiology_order_have_radiology->update($key, $item);
            }
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('patient_radiology_order/search');
        }
    }

    public function view_result($order_id)
    {
        $radiology_order = $this->m_radiology_order->with('order_by')->get($order_id);
        $data['lab_order'] = $radiology_order;
        $data['pid'] = $radiology_order->PID;
        if ($radiology_order->order_by != null) {
            $doctor = $radiology_order->order_by;
            $data['default_order_by'] = $doctor->Title . ' ' . $doctor->Name . ' ' . $doctor->OtherName;
        } else {
            $data['default_order_by'] = '';
        }
        $data['default_create_time'] = $radiology_order->CreateDate;
        $data['radiology_order_items'] = array();

        foreach ($this->m_radiology_order_have_radiology->with('radiology')->get_many_by(array('radiology_order_id' => $order_id, 'Active' => 1)) as $row) {
            $tmp['ID'] = $row->id;
            $tmp['Name'] = $row->radiology->name;
            $tmp['RefValue'] = $row->radiology->RefValue;
            $tmp['Result'] = $row->result;
            $tmp['Status'] = $row->Status;
            array_push($data['radiology_order_items'], $tmp);
        }

        $this->render('view_radiology_order', $data);
    }

    public function delete_item($id)
    {
        if ($id) {
            $this->m_radiology_order_have_radiology->update($id, array('Active' => 0));
            $response = ['response' => 'success'];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));

        } else {
            echo 'error';
        }
    }

    public function check_result($str)
    {
        if (!is_array($this->input->post('result'))) {
            $this->form_validation->set_message('check_result', "Result can't empty");
            return FALSE;
        }
        foreach ($this->call_back_items as $item) {
            if (!array_key_exists($item['ID'], $this->input->post('result'))) {
                $this->form_validation->set_message('check_result', 'Not enough result');
                return FALSE;
            }
        }
        foreach ($this->input->post('result') as $id => $value) {
            if (empty($value)) {
                $this->form_validation->set_message('check_result', 'Result can not empty');
                return FALSE;
            }
        }
        return TRUE;
    }

    public function check_lab_test_param($lab_test)
    {
        if (empty($lab_test) || count($lab_test) <= 0) {
            $this->form_validation->set_message('lab_test', 'Please select lab test');
            return FALSE;
        }
        return TRUE;
    }

    public function get_previous($pid, $continue, $mode = 'HTML')
    {
        $data = array();
        $data["patient_radiology_order_list"] = $this->m_radiology_order->with('order_by')->order_by('CreateDate', 'DESC')->get_many_by(array('PID' => $pid));
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous');
        } else {
            return $data["patient_lab_order_list"];
        }
    }

    public function search()
    {
        $this->set_top_selected_menu('patient_radiology_order');
        $qry = "SELECT
                radiology_order.ExamDate,
                radiology_order.CreateDate,
                radiology_order_id,
                RefType,
                patient.PID,
                CONCAT(patient.Name,' ',patient.OtherName) AS Patient,
                CONCAT(user.Title, ' ', user.Name,' ',user.OtherName) AS Doctor,
                radiology_order.Status
                FROM radiology_order
                LEFT JOIN patient ON patient.PID = radiology_order.PID
                LEFT JOIN user ON user.UID = radiology_order.OrderBy
                WHERE (radiology_order.Active = 1)";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('radiology_order_id');
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array(lang("Exam Date"), lang("Time"), lang("Order ID"), lang("Department"), lang("Patient ID"), lang("Patient Name"), lang("Doctor"), lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("ExamDate", $page->getDateSelector());
        $page->setColOption("CreateDate", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption("Patient", array("search" => false, "hidden" => false));
        $page->setColOption("PID", array('width' => '100'));
        $page->setColOption("radiology_order_id", array('width' => '100'));
        $page->setColOption('RefType', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':All;EMR:EMR;OPD:OPD;ADM:ADM'
            ),
            'width' => '50'
        ));
        $page->setColOption('Status', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':'.lang('All').';Pending:'.lang('Pending').';Done:'.lang('Done')
            ),
            'width' => '70'
        ));
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
                window.location='" . site_url("/patient_radiology_order/update_result") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->render_search($data);
    }
}
