<?php

/**
 * Created by @jordao.cololo.
 * User: qch
 * Date: 11/21/15
 * Time: 6:40 AM
 */
class Doctor extends FormController
{
    var $FORM_NAME = 'form_doctor';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_doctor');
        $this->load->model('m_user');
    }

    public function create()
    {

        $data = array();
        $data['id'] = 0;
        $data['default_Name'] = '';

          $data['dropdown_Especialidade'] = $this->get_dropdown_services(set_value('entry_department', 2), 'return');
          $data['default_department'] = '2';
          $data['default_Especialidade'] = '5';

        $data['default_Active'] = '';

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Name' => $this->input->post('Name'),
                'Especialidade' => $this->input->post('Especialidade'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_doctor->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('/preference/load/doctor');
        }
    }

    public function edit($id)
    {
        $doctor = $this->m_doctor->get($id);
        if (empty($doctor))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_Name'] = $doctor->Name;

        if (isset($doctor->Especialidade)&&($doctor->Especialidade <>'')) {
            $data['default_department'] = '2';
            $data['dropdown_Especialidade'] = $this->get_dropdown_services(set_value('entry_department', 2), 'return');
            $data['default_Especialidade'] = $doctor->Especialidade;
        }   else {
            $data['default_department'] = '2';
            $data['dropdown_Especialidade'] = $this->get_dropdown_services(set_value('entry_department', 2), 'return');
            $data['default_Especialidade'] = '';
        }

      //  $data['default_Especialidade'] = $doctor->Especialidade;
        $data['default_Active'] = $doctor->Active;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Name' => $this->input->post('Name'),
                'Especialidade' => $this->input->post('Especialidade'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_doctor->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/doctor');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('Name', 'Nome do Clinico', 'trim|xss_clean|required');
        $this->form_validation->set_rules('Especialidade', 'Especialidade do Clinico', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }

    public function get_dropdown_services($department_id = 56, $type = 'json')
    {
        $this->load->model('m_hospital_service');
        $result = $this->m_hospital_service->order_by('abrev')->get_many_by(array('department_id' => $department_id));

        if ($type == 'json') {
            print(json_encode($result));
        } else {
            $drop_down = array();
            $drop_down[''] = '';
            foreach ($result as $item) {
                $drop_down[$item->service_id] = $item->abrev;
            }
            return $drop_down;
        }
    }


    public function get_dropdown_doctors($service_id, $type = 'json')
    {
        $this->load->model('m_doctor');
      //  $result = $this->m_doctor->order_by('Name');
      //  $result = $this->m_doctor->order_by('Name', 'asc')->dropdown('Doctor_ID', 'Name');
        $result = $this->m_doctor->order_by('Name', 'asc')->get_many_by(array('Especialidade' => $service_id));
        if ($type == 'json') {
            print(json_encode($result));
        } else {
            foreach ($result as $item) {
                $drop_down[$item->Doctor_ID] = $item->Name;
            }
            return $drop_down;
        }
    }


}
