<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Injection extends FormController
{
    var $FORM_NAME = 'form_injection';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_injection');
        $this->load_form_language();
    }

    public function create()
    {
        $data = array();
        $data['id'] = 0;
        $data['default_name'] = '';
        $data['default_dosage'] = '';
        $data['default_route'] = '';
        $data['default_active'] = '';
        $data['default_remarks'] = '';

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'dosage' => $this->input->post('dosage'),
                'route' => $this->input->post('route'),
                'Active' => $this->input->post('active'),
                'remarks' => $this->input->post('remarks'),
            );
            $id = $this->m_injection->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('preference/load/injection');
        }
    }

    public function edit($id)
    {
        $injection = $this->m_injection->get($id);
        if (empty($injection))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_name'] = $injection->name;
        $data['default_dosage'] = $injection->dosage;
        $data['default_route'] = $injection->route;
        $data['default_active'] = $injection->Active;
        $data['default_remarks'] = $injection->remarks;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'dosage' => $this->input->post('dosage'),
                'route' => $this->input->post('route'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks'),
            );
            $this->m_injection->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('preference/load/injection');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('name', lang('Injection Name'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('dosage', lang('Injection Dosage'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('route', 'Route', 'trim|xss_clean');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }
}