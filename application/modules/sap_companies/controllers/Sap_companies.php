<?php

/**
 * Created by @jordao.cololo.
 * User: qch
 * Date: 11/21/15
 * Time: 6:40 AM
 */
class Sap_companies extends FormController
{
    var $FORM_NAME = 'form_sap_companies';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_sap_companies');
      //  $this->load->model('m_doctor');
        $this->load->model('m_user');
    }

    public function create()
    {

        $data = array();
        $data['id'] = 0;
        $data['default_Name'] = '';
        $data['default_Abrev'] = '';
        $data['default_Address'] = '';
        $data['default_Phone'] = '';
        $data['default_Mobile'] = '';
        $data['default_Registration'] = '';
        $data['default_Remarks'] = '';

        $data['dropdown_Type'] = $this->get_dropdown_type();
        $data['default_TypeId'] = '1';
        $data['default_Active'] = '';

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Name' => $this->input->post('Name'),
                'type_id' => $this->input->post('type_id'),
                'abrev' => $this->input->post('abrev'),
                'address' => $this->input->post('address'),
                'phone_number' => $this->input->post('phone_number'),
                'mobile_number' => $this->input->post('mobile_number'),
                'registration_number' => $this->input->post('registration_number'),
                'Remarks' => $this->input->post('Remarks'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_sap_companies->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('/preference/load/sap_companies');
        }
    }

    public function edit($id)
    {
        $company = $this->m_sap_companies->get($id);
        if (empty($company))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_Name'] = $company->Name;
        $data['default_TypeId'] = $company->type_id;
        $data['default_Abrev'] = $company->abrev;
        $data['default_Address'] = $company->address;
        $data['default_Phone'] = $company->phone_number;
        $data['default_Mobile'] = $company->mobile_number;
        $data['default_Registration'] = $company->registration_number;
        $data['default_Remarks'] = $company->Remarks;



        if (isset($company->type_id)&&($company->type_id !='')) {
            $data['default_TypeId'] = '1';
            $data['dropdown_Type'] = $this->get_dropdown_type();
            $data['default_TypeId'] = $company->type_id;
        }   else {
            $data['default_TypeId'] = '1';
            $data['dropdown_Type'] = $this->get_dropdown_type();
            $data['default_TypeId'] = '';
        }
        $data['default_TypeId'] = $company->type_id;
        $data['default_Active'] = $company->Active;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Name' => $this->input->post('Name'),
                'type_id' => $this->input->post('type_id'),
                'abrev' => $this->input->post('abrev'),
                'address' => $this->input->post('address'),
                'phone_number' => $this->input->post('phone_number'),
                'mobile_number' => $this->input->post('mobile_number'),
                'registration_number' => $this->input->post('registration_number'),
                'Remarks' => $this->input->post('Remarks'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_sap_companies->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/sap_companies');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('Name', 'Nome do Empresa', 'trim|xss_clean|required');
        $this->form_validation->set_rules('type_id', 'Tipo de Empresa', 'trim|xss_clean|required');
        $this->form_validation->set_rules('abrev', 'Abreviatura', 'trim|xss_clean|required');
        $this->form_validation->set_rules('registration_number', 'NUIT', 'trim|xss_clean|required');
        $this->form_validation->set_rules('address', 'Morada', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }

    public function get_dropdown_types($type_id = 1, $type = 'json')
    {
        $this->load->model('m_sap_companies_type');
        $result = $this->m_sap_companies_type->order_by('name')->get_many_by(array('id' => $type_id));

        if ($type == 'json') {
            print(json_encode($result));
        } else {
            $drop_down = array();
            $drop_down[''] = '';
            foreach ($result as $item) {
                $drop_down[$item->id] = $item->name;
            }
            return $drop_down;
        }
    }

    public function get_dropdown_type()

    {
        $this->load->model('m_sap_companies_type');
        $re = $this->m_sap_companies_type->order_by('name', 'asc')->dropdown('id', 'name');
        $re[''] = '';
        return $re;
    }

}
