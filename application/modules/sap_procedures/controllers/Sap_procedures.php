<?php

/**
 * Created by @jordao.cololo.
 * User: qch
 * Date: 11/21/15
 * Time: 6:40 AM
 */
class Sap_procedures extends FormController
{
    var $FORM_NAME = 'form_sap_procedures';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_sap_procedures');
      //  $this->load->model('m_doctor');
        $this->load->model('m_user');
    }

    public function create()
    {

        $data = array();
        $data['id'] = 0;
        $data['default_Name'] = '';
        $data['default_RefPrice'] = '';
        $data['default_Price'] = '';
        $data['default_Remarks'] = '';

        $data['dropdown_Type'] = $this->get_dropdown_type();
        $data['default_TypeId'] = '3';
        $data['default_Active'] = '';

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Name' => $this->input->post('Name'),
                'type_id' => $this->input->post('type_id'),
                'ref_price' => $this->input->post('ref_price'),
                'price' => $this->input->post('price'),
                'Remarks' => $this->input->post('Remarks'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_sap_procedures->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('/preference/load/sap_procedures');
        }
    }

    public function edit($id)
    {
        $doctor = $this->m_sap_procedures->get($id);
        if (empty($doctor))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_Name'] = $doctor->Name;
        $data['default_TypeId'] = $doctor->type_id;
        $data['default_RefPrice'] = $doctor->ref_price;
        $data['default_Price'] = $doctor->price;
        $data['default_Remarks'] = $doctor->Remarks;



        if (isset($doctor->type_id)&&($doctor->type_id !='')) {
            $data['default_TypeId'] = '1';
            $data['dropdown_Type'] = $this->get_dropdown_type();
            $data['default_TypeId'] = $doctor->type_id;
        }   else {
            $data['default_TypeId'] = '1';
            $data['dropdown_Type'] = $this->get_dropdown_type();
            $data['default_TypeId'] = '';
        }
        $data['default_TypeId'] = $doctor->type_id;
        $data['default_Active'] = $doctor->Active;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Name' => $this->input->post('Name'),
                'type_id' => $this->input->post('type_id'),
                'ref_price' => $this->input->post('ref_price'),
                'price' => $this->input->post('price'),
                'Remarks' => $this->input->post('Remarks'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_sap_procedures->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/sap_procedures');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('Name', 'Nome do Clinico', 'trim|xss_clean|required');
        $this->form_validation->set_rules('type_id', 'Tipo de Procedimento', 'trim|xss_clean|required');
        $this->form_validation->set_rules('price', 'Preco', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }

    public function get_dropdown_types($type_id = 1, $type = 'json')
    {
        $this->load->model('m_sap_procedure_type');
        $result = $this->m_sap_procedure_type->order_by('name')->get_many_by(array('id' => $type_id));

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
        $this->load->model('m_sap_procedure_type');
        $re = $this->m_sap_procedure_type->order_by('name', 'asc')->dropdown('id', 'name');
        $re[''] = '';
        return $re;
    }

}
