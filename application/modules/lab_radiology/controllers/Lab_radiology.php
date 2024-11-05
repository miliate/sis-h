<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Lab_Radiology extends LoginCheckController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_radiology_group');
        $this->load->model('m_radiology');
    }

    public function create_lab_test_group()
    {
        $data = array();
        $data['id'] = 0;
        $data['default_name'] = '';
        $data['default_active'] = '';

        $this->form_validation->set_rules('name', 'Group Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_radiology_group', $data);
        } else {
            $data = array(
                'Group_Name' => $this->input->post('name'),
                'Active' => $this->input->post('active'),
            );
            $id = $this->m_radiology_group->insert($data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('preference/load/lab_radiology_group');
        }
    }

    public function edit_lab_test_group($id)
    {
        $radiology_group = $this->m_radiology_group->get($id);
        if (empty($radiology_group)) {
            die('Invalid ID');
        }
        $data = array();
        $data['id'] = 0;
        $data['default_name'] = $radiology_group->Group_Name;
        $data['default_active'] = $radiology_group->Active;

        $this->form_validation->set_rules('name', 'Lab Test Group Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_radiology_group', $data);
        } else {
            $data = array(
                'Group_Name' => $this->input->post('name'),
                'Active' => $this->input->post('active'),
            );
            $this->m_radiology_group->update($id, $data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('preference/load/lab_radiology_group');
        }
    }

    public function create_lab_test()
    {
     
        foreach($this->m_radiology_group->get_all() as $group) {
            $data['group_options'][$group->Group_Name] = $group->Group_Name;
        }
        // $data['default_department'] = '';
        $data['default_group'] = '';
        $data['default_name'] = '';
        // $data['default_abrev'] = '';
        // $data['default_unit'] = '';
        $data['default_ref_value'] = '';
        $data['default_active'] = '';

        $this->form_validation->set_rules('name', 'Radiology Name', 'trim|xss_clean|required');
        // $this->form_validation->set_rules('department', 'Department', 'trim|xss_clean|required');
        $this->form_validation->set_rules('group', 'Group', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_radiology_test', $data);
        } else {
            $data = array(
                'parent_group' => $this->input->post('group'),
                'RefValue' => $this->input->post('ref_value'),
                'name' => $this->input->post('name'),
                'Active' => $this->input->post('active'),
            );
            $id = $this->m_radiology->insert($data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('preference/load/lab_radiology');
        }
    }

    public function edit_lab_test($id)
    {
        $radiology = $this->m_radiology->get($id);
        if (empty($radiology)) {
            die('Invalid ID');
        }
        foreach($this->m_radiology_group->get_all() as $group) {
            $data['group_options'][$group->Group_Name] = $group->Group_Name;
        }
        $data['default_group'] = $radiology->parent_group;
        $data['default_name'] = $radiology->name;
        $data['default_ref_value'] = $radiology->RefValue;
        $data['default_active'] = $radiology->Active;

        $this->form_validation->set_rules('name', 'Lab Test Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('group', 'Group', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->qch_template->load_form_layout('form_radiology_test', $data);
        } else {
            $data = array(
                'parent_group' => $this->input->post('group'),
                'name' => $this->input->post('name'),
                'RefValue' => $this->input->post('ref_value'),
                'Active' => $this->input->post('active'),
            );
            $this->m_radiology->update($id, $data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('preference/load/lab_radiology');
        }
    }
}