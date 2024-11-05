<?php
/**
 * Created by @jordao.cololo.
 * User: qch
 * Date: 11/21/15
 * Time: 9:35 AM
 */

class Drug_Frequency extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_drug_frequency');
    }

    public function create()
    {
        $data = array();
        $data['id'] = 0;
        $data['default_Frequency'] = '';
        $data['default_Factor'] = '';
        $data['default_Active'] = '';


        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Frequency' => $this->input->post('Frequency'),
                'Factor' => $this->input->post('Factor'),
                'Active' => $this->input->post('Active'),
            );
            $id = $this->m_drug_frequency->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('preference/load/drugs_frequency');
        }
    }


    public function edit($id)
    {
        $drug_frequency = $this->m_drug_frequency->get($id);
        if (empty($drug_frequency))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_Frequency'] = $drug_frequency->Frequency;
        $data['default_Factor'] = $drug_frequency->Factor;
        $data['default_Active'] = $drug_frequency->Active;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Frequency' => $this->input->post('Frequency'),
                'Factor' => $this->input->post('Factor'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_drug_frequency->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/drugs_frequency');
        }
    }

    private function set_common_validation() {
        $this->form_validation->set_rules('Frequency', 'Frequency Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('Factor', 'Factor', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }

}