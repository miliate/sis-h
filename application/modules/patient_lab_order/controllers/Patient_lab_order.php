<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_Lab_Order extends FormController
{
    var $call_back_items = array();

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_lab_test');
        $this->load->model('m_lab_test_department');
        $this->load->model('m_lab_test_group');
        $this->load->model('m_lab_order');
        $this->load->model('m_lab_order_items');
        $this->load_form_language();
        $this->load->model('m_user');
    }

    public function get_lab_test_by_group($lab_test_group_id)
    {
        if (!$lab_test_group_id) {
            echo '[]';
            return;
        }
        $data = $this->m_lab_test->with('group')->with('department')->get_many_by(array('GroupID' => urldecode($lab_test_group_id)));
        $results = array();
        foreach ($data as $row) {
            $tmp_result['LABID'] = $row->LABID;
            $tmp_result['Name'] = $row->Name;
            $tmp_result['RefValue'] = $row->RefValue;
            $tmp_result['Department'] = $row->department->Name;
            $tmp_result['Group'] = $row->group->Name;
            array_push($results, $tmp_result);
        }
        echo json_encode($results);
    }

    public function create_adm_lab_order($adm_id)
    {
        $this->load->model('m_emergency_admission');
        $this->load->model('m_admission');
        $opd_visit = $this->m_admission->get($adm_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'ADM', $adm_id);
    }

    public function create_emr_lab_order($emr_id)
    {
        $this->load->model('m_emergency_admission');
        $this->load->model('m_admission');
        $opd_visit = $this->m_emergency_admission->get($emr_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'EMR', $emr_id);
    }


    public function create_opd_lab_order($opd_id)
    {
        $this->load->model('m_opd_visit');
        $opd_visit = $this->m_opd_visit->get($opd_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'OPD', $opd_id);
    }

    private function create($pid, $ref_type, $ref_id)
    {
        // Initialize lab test group dropdown options
        $data['lab_test_group'][0] = 'Select...';
        foreach ($this->m_lab_test_group->get_all() as $lab_test_group) {
            $data['lab_test_group'][$lab_test_group->LABGRPTID] = $lab_test_group->Name;
        }
        switch($ref_type) {
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

        // Set default values and parameters
        $data['ref_type'] = $ref_type;
        $data['ref_id'] = $ref_id;
        $data['pid'] = $pid;
        $data['default_priority'] = '';
        $data['default_test_group'] = '';
        $data['exam_date'] = date('Y-m-d');
        $data['default_exam_date'] = date('Y-m-d');

        // Set form validation rules
        $this->form_validation->set_rules('password2', lang('Second Password'), 'trim|required|callback_check_pass2');
        $this->form_validation->set_rules('priority', lang('Priority'), 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // Load form with current data if validation fails
            $this->load_form($data);
        } else {
            // Retrieve selected tests
            $selected_tests = $this->input->post('lab_test');

            if ($selected_tests) {
                // Group the selected tests by their test group
                $test_groups = [];
                foreach ($selected_tests as $test_id) {
                    $test = $this->m_lab_test->get($test_id);
                    if ($test) {
                        if (!isset($test_groups[$test->GroupID])) {
                            $test_groups[$test->GroupID] = [];
                        }
                        $test_groups[$test->GroupID][$test->LABID] = $test;
                    }
                }

                // Insert each group of tests as a separate lab order
                foreach ($test_groups as $group_id => $tests) {
                    $order_data = [
                        'PID' => $pid,
                        'RefType' => $ref_type,
                        'RefId' => $ref_id,
                        'Priority' => $this->input->post('priority'),
                        'TestGroupID' => $group_id,
                        'OrderBy' => $this->get_session('uid'),
                        'OrderDate' => date('Y-m-d H:i:s'),
                        'Status' => 'Pending',
                        'Active' => true,
                        'ExamDate' => $this->input->post('exam_date')
                    ];

                    // Insert lab order and get the order ID
                    $lab_order_id = $this->m_lab_order->insert($order_data);

                    // Insert each test in the group as an item in the lab order
                    foreach ($tests as $test) {
                        $item_data = [
                            'LAB_ORDER_ID' => $lab_order_id,
                            'Status' => 'Pending',
                            'LABID' => $test->LABID,
                            'Active' => true
                        ];
                        $this->m_lab_order_items->insert($item_data);
                    }
                }

                // Set flash message and redirect
                $this->session->set_flashdata('msg', 'Created');
                $this->redirect_if_no_continue('opd_visit/view/' . $ref_id);
            } else {
                // If no tests are selected, reload the form with an error message
                $data['error'] = 'No lab tests selected.';
                $this->load_form($data);
            }
        }
    }




    public function check_pass2($pass2)
    {
        require 'application/config/database.php';
        if ($pass2 != $db['default']['password_2']) {
            $this->form_validation->set_message('check_pass2', 'The password 2 you supplied does not match your existing password 2.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function update($order_id)
    {
        $data = $this->prepare_lab_order_data($order_id);

        $this->form_validation->set_rules('example', 'Test Result', 'callback_check_result');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            // Check if all result fields are populated
            $all_results_populated = true;
            foreach ($this->input->post('result') as $result) {
                if (empty($result)) {
                    $all_results_populated = false;
                    break;
                }
            }

            // Update status only if all result fields are populated
            if ($all_results_populated) {
                $this->m_lab_order->update($order_id, array('Status' => 'Done'));
            }    // document.addEventListener("DOMContentLoaded", function() {
            //     var today = new Date();
            //     var year = today.getFullYear();
            //     var month = String(today.getMonth() + 1).padStart(2, '0');
            //     var day = String(today.getDate()).padStart(2, '0');
            //     var formattedDate = day + '-' + month + '-' + year;
            //     document.getElementById('exam_date').value = formattedDate;
            // });
            $this->redirect_if_no_continue('patient_lab_order/search');
        }
    }

    public function update_result($order_id)
    {
        $data = $this->prepare_lab_order_data($order_id);

        $this->form_validation->set_rules('example', 'Test Result', 'callback_check_result');
        $this->form_validation->set_rules('password2', 'Password 2', 'trim|required|callback_check_pass2');

        if ($this->form_validation->run($this) == FALSE) {
            $this->qch_template->load_form_layout('update_lab_order', $data);
        } else {
            // Check if all result fields are populated
            $all_results_populated = true;
            foreach ($this->input->post('result') as $result) {
                if (empty($result)) {
                    $all_results_populated = false;
                    break;
                }
            }

            // Update status only if all result fields are populated
            if ($all_results_populated) {
                $this->m_lab_order->update($order_id, array('Status' => 'Done'));
            }

            foreach ($this->input->post('result') as $key => $result) {
                $item = array(
                    'Status' => 'Done',
                    'TestValue' => $result,
                );
                $this->m_lab_order_items->update($key, $item);
            }

            $this->session->set_flashdata('msg', 'Updated');
            $this->redirect_if_no_continue('patient_lab_order/search');
        }
    }

    public function check_result($str)
    {
        if (!is_array($this->input->post('result'))) {
            // Result can be empty, no need to show an error
            return TRUE;
        }

        /* foreach ($this->call_back_items as $item) {
            if (!array_key_exists($item['ID'], $this->input->post('result'))) {
                // Not enough result, show error
                $this->form_validation->set_message('check_result', 'Not enough result');
                return FALSE;
            }
        }*/

        return TRUE;
    }

    private function prepare_lab_order_data($order_id)
    {
        $lab_order = $this->m_lab_order->with('group')->get($order_id);
        $data['lab_order'] = $lab_order;
        $data['pid'] = $lab_order->PID;
        $data['default_priority'] = $lab_order->Priority;
        $data['default_test_group'] = $lab_order->group->Name;
        $data['default_create_time'] = $lab_order->CreateDate;
        $data['default_exam_date'] = $lab_order->ExamDate;
        $data['lab_order_items'] = array();
        foreach ($this->m_lab_order_items->with('lab_test')->get_many_by(array('LAB_ORDER_ID' => $order_id, 'Active' => 1)) as $row) {
            $tmp['ID'] = $row->LAB_ORDER_ITEM_ID;
            $tmp['Name'] = $row->lab_test->Name;
            $tmp['TestResult'] = $row->TestValue;
            $tmp['RefValue'] = $row->lab_test->RefValue;
            array_push($data['lab_order_items'], $tmp);
        }
        $this->call_back_items = $data['lab_order_items'];
        return $data;
    }



    public function view_result($order_id)
    {
        $lab_order = $this->m_lab_order->with('group')->get($order_id);
        $data['lab_order'] = $lab_order;
        $data['pid'] = $lab_order->PID;
        $data['default_priority'] = $lab_order->Priority;
        $data['default_test_group'] = $lab_order->group->Name;
        $data['default_create_time'] = $lab_order->CreateDate;
        $data['lab_order_items'] = array();
        foreach ($this->m_lab_order_items->with('lab_test')->get_many_by(array('LAB_ORDER_ID' => $order_id, 'Active' => 1)) as $row) {
            $tmp['ID'] = $row->LAB_ORDER_ITEM_ID;
            $tmp['Name'] = $row->lab_test->Name;
            $tmp['TestResult'] = $row->TestValue;
            $tmp['Status'] = $row->Status;
            $tmp['RefValue'] = $row->lab_test->RefValue;
            array_push($data['lab_order_items'], $tmp);
        }

        $this->call_back_items = $data['lab_order_items'];
        $this->form_validation->set_rules('example', 'Test Result', 'callback_check_result');

        if ($this->form_validation->run($this) == FALSE) {
            $this->qch_template->load_form_layout('view_lab_order', $data);
        } else {
            $this->m_lab_order->update($order_id, array('Status' => 'Done'));
            foreach ($this->input->post('result') as $key => $result) {
                $item = array(
                    'Status' => 'Done',
                    'TestValue' => $result,
                );
                $this->m_lab_order_items->update($key, $item);
            }
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('patient_lab_order/search');
        }
    }

    public function void_exam($id)
    {
        if ($id) {
            $this->m_lab_order_items->update($id, array('Active' => 0));
            $response = ['response' => 'success'];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));

        } else {
            echo 'error';
        }
    }

    public function check_lab_test_param($lab_test)
    {
        if (empty($lab_test) || count($lab_test) <= 0) {
            $this->form_validation->set_message('lab_test', 'Please select lab test');
            return FALSE;
        }
        return TRUE;
    }

    public function get_previous_lab($pid, $continue, $mode = 'HTML')
    {
        $this->load->model("mpatient");
        $data = array();
        $data["patient_lab_order_list"] = $this->m_lab_order->with('group')->with('order_by')->order_by('CreateDate', 'DESC')->get_many_by(array('PID' => $pid));
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_lab');
        } else {
            return $data["patient_lab_order_list"];
        }
    }

    public function search()
    {
        $this->set_top_selected_menu('patient_lab_order/search');
        $qry = "SELECT
                lab_order.CreateDate,
                lab_order.ExamDate,
                LAB_ORDER_ID,
                RefType,
                patient.PID,
                CONCAT(patient.Firstname,' ',patient.Name) AS Patient,
                lab_test_group.Name, 
                CONCAT(user.Title, ' ', user.Name,' ',user.OtherName) AS Doctor,
                Priority,
                lab_order.Status
                FROM lab_order
                LEFT JOIN lab_test_group ON lab_test_group.LABGRPTID = lab_order.TestGroupID
                LEFT JOIN patient ON patient.PID = lab_order.PID
                LEFT JOIN user ON user.UID = lab_order.OrderBy
                WHERE (lab_order.Active = 1)";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('LAB_ORDER_ID');
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array(lang("Time"), lang("Exam Date"), lang("Order ID"), lang("Department"), lang("Patient ID"), lang("Patient Name"), lang("Lab Test Group"), lang("Doctor"), lang("Priority"), lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("ExamDate", $page->getDateSelector());
        $page->setColOption("CreateDate", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption("Patient", array("search" => false, "hidden" => false));
        $page->setColOption("PID", array('width' => '100'));
        $page->setColOption("LAB_ORDER_ID", array('width' => '100'));
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
                'value' => ':All;' . lang('Pending') . ':' . lang('Pending') . ';' . lang('Done') . ':' . lang('Done')
            ),
            'width' => '70'
        ));
        $page->setColOption('Priority', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':All;' . lang('Urgent') . ':' . lang('Urgent') . ';' . lang('Normal') . ':' . lang('Normal')
            ),
            'width' => '70'
        ));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
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
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . site_url("/patient_lab_order/update_result") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->qch_template->load_form_layout('search', $data);
    }
}
