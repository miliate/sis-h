<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Lab_Test extends LoginCheckController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_lab_test');
        $this->load->model('m_lab_test_group');
        $this->load->model('m_lab_test_department');
    }

    public function create_lab_test_group()
    {
        $data = array();
        $data['id'] = 0;
        $data['default_name'] = '';
        $data['default_active'] = '';

        $this->form_validation->set_rules('name', 'Lab Test Group Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_lab_test_group', $data);
        } else {
            $data = array(
                'Name' => $this->input->post('name'),
                'Active' => $this->input->post('active'),
            );
            $id = $this->m_lab_test_group->insert($data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('preference/load/lab_test_group');
        }
    }

    public function edit_lab_test_group($id)
    {
        $lab_test_group = $this->m_lab_test_group->get($id);
        if (empty($lab_test_group)) {
            die('Invalid ID');
        }
        $data = array();
        $data['id'] = 0;
        $data['default_name'] = $lab_test_group->Name;
        $data['default_active'] = $lab_test_group->Active;

        $this->form_validation->set_rules('name', 'Lab Test Group Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_lab_test_group', $data);
        } else {
            $data = array(
                'Name' => $this->input->post('name'),
                'Active' => $this->input->post('active'),
            );
            $this->m_lab_test_group->update($id, $data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('preference/load/lab_test_group');
        }
    }

    public function create_lab_test_department()
    {
        $data = array();
        $data['id'] = 0;
        $data['default_name'] = '';
        $data['default_active'] = '';

        $this->form_validation->set_rules('name', 'Lab Test Department Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_lab_test_department', $data);
        } else {
            $data = array(
                'Name' => $this->input->post('name'),
                'Active' => $this->input->post('active'),
            );
            $id = $this->m_lab_test_department->insert($data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('preference/load/lab_test_department');
        }
    }

    public function edit_lab_test_department($id)
    {
        $lab_test_department = $this->m_lab_test_department->get($id);
        if (empty($lab_test_department)) {
            die('Invalid ID');
        }
        $data = array();
        $data['id'] = 0;
        $data['default_name'] = $lab_test_department->Name;
        $data['default_active'] = $lab_test_department->Active;

        $this->form_validation->set_rules('name', 'Lab Test Group Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_lab_test_department', $data);
        } else {
            $data = array(
                'Name' => $this->input->post('name'),
                'Active' => $this->input->post('active'),
            );
            $this->m_lab_test_department->update($id, $data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('preference/load/lab_test_department');
        }
    }

    public function create_lab_test()
    {
        foreach($this->m_lab_test_department->get_all() as $department) {
            $data['department_options'][$department->LABDEPTID] = $department->Name;
        }
        foreach($this->m_lab_test_group->get_all() as $group) {
            $data['group_options'][$group->LABGRPTID] = $group->Name;
        }
        $data['default_department'] = '';
        $data['default_group'] = '';
        $data['default_name'] = '';
        $data['default_abrev'] = '';
        $data['default_unit'] = '';
        $data['default_ref_value'] = '';
        $data['default_active'] = '';

        $this->form_validation->set_rules('name', 'Lab Test Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('department', 'Department', 'trim|xss_clean|required');
        $this->form_validation->set_rules('group', 'Group', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_lab_test', $data);
        } else {
            $data = array(
                'DepID' => $this->input->post('department'),
                'GroupID' => $this->input->post('group'),
                'Name' => $this->input->post('name'),
                'Abrev' => $this->input->post('Abrev'),
                'Unit' => $this->input->post('Unit'),
                'RefValue' => $this->input->post('ref_value'),
                'Active' => $this->input->post('active'),
            );
            $id = $this->m_lab_test->insert($data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('preference/load/lab_tests');
        }
    }

    public function edit_lab_test($id)
    {
        $lab_test = $this->m_lab_test->get($id);
        if (empty($lab_test)) {
            die('Invalid ID');
        }
        foreach($this->m_lab_test_department->get_all() as $department) {
            $data['department_options'][$department->LABDEPTID] = $department->Name;
        }
        foreach($this->m_lab_test_group->get_all() as $group) {
            $data['group_options'][$group->LABGRPTID] = $group->Name;
        }
        $data['default_department'] = $lab_test->DepID;
        $data['default_group'] = $lab_test->GroupID;
        $data['default_name'] = $lab_test->Name;
        $data['default_abrev'] = $lab_test->Abrev;
        $data['default_unit'] = $lab_test->Unit;
        $data['default_ref_value'] = $lab_test->RefValue;
        $data['default_active'] = $lab_test->Active;

        $this->form_validation->set_rules('name', 'Lab Test Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('department', 'Department', 'trim|xss_clean|required');
        $this->form_validation->set_rules('group', 'Group', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_lab_test', $data);
        } else {
            $data = array(
                'DepID' => $this->input->post('department'),
                'GroupID' => $this->input->post('group'),
                'Name' => $this->input->post('name'),
                'Abrev' => $this->input->post('Abrev'),
                'Unit' => $this->input->post('Unit'),
                'RefValue' => $this->input->post('ref_value'),
                'Active' => $this->input->post('active'),
            );
            $this->m_lab_test->update($id, $data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('preference/load/lab_tests');
        }
    }
}