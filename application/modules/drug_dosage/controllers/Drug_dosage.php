<?php

/**
 * Created by @jordao.cololo.
 * User: qch
 * Date: 11/21/15
 * Time: 8:13 AM
 */
class Drug_Dosage extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_drug_dosage');
    }

    public function create()
    {
        if (!has_permission('drug_management', 'view')) {
            $this->show_no_permission();
            return;
        }
        else{

        
            $data = array();
            $data['id'] = 0;
            $data['default_Dosage'] = '';
            $data['default_Factor'] = '';
            $data['default_Type'] = '';
            $data['default_Active'] = '';


            $this->set_common_validation();

            if ($this->form_validation->run() == FALSE) {
                $this->load_form($data);
            } else {
                $data = array(
                    'Dosage' => $this->input->post('Dosage'),
                    'Factor' => $this->input->post('Factor'),
                    'Type' => $this->input->post('Type'),
                    'Active' => $this->input->post('Active'),
                );
                $this->m_drug_dosage->insert($data);
                $this->session->set_flashdata(
                    'msg', 'Created'
                );
                $this->redirect_if_no_continue('/preference/load/drug_dosage');
            }
        }    
    }

    public function edit($id)
    {
        $drug_dosage = $this->m_drug_dosage->get($id);
        if (empty($drug_dosage))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_Dosage'] = $drug_dosage->Dosage;
        $data['default_Factor'] = $drug_dosage->Factor;
        $data['default_Type'] = $drug_dosage->Type;
        $data['default_Active'] = $drug_dosage->Active;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Dosage' => $this->input->post('Dosage'),
                'Factor' => $this->input->post('Factor'),
                'Type' => $this->input->post('Type'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_drug_dosage->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/drug_dosage');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('Dosage', 'Dosage Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('Factor', 'Factor', 'trim|xss_clean|required');
        $this->form_validation->set_rules('Type', 'Dosage Type', 'trim|xss_clean');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }

}