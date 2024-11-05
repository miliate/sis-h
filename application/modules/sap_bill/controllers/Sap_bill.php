<?php

/**
 * Created by @jordao.cololo.
 * User: qch
 * Date: 11/21/15
 * Time: 6:40 AM
 */
class Sap_bill extends FormController
{
    var $FORM_NAME = 'form_sap_bill';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_sap_bill');
        $this->load->model('m_sap_bill_item');
        $this->load->model('m_patient');
        $this->load->model('m_patient_active_list');
        $this->load->model('m_user');
        $this->load->model('m_sap_procedure_type');
    }

    public function create()
    {

        $data = array();
        $data['id'] = 0;
        $data['default_Total'] = '';
        $data['default_TotalPaid'] = '';
        $data['default_BillNumber'] = '';
        $data['default_Remarks'] = '';
        $data['default_PayMode'] = '';
        
        
        $data['dropdown_PayMode'] = $this->get_dropdown_paymode();
      //  $paymode_options = $this->get_dropdown_paymode(); 
        $data['default_Active'] = '';

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'bill_number' => $this->input->post('bill_number'),
                'pay_mode' => $this->input->post('pay_mode'),
                'total' => $this->input->post('total'),
                'total_paid' => $this->input->post('total_paid'),
                'Remarks' => $this->input->post('Remarks'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_sap_bill->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('/preference/load/sap_bill');
        }
    }

    public function edit($id)
    {
        $bill = $this->m_sap_bill->get($id);
        if (empty($bill))
            die('Id not exist'); 
        $data['id'] = $id;
        $data['default_PID'] = $bill->PID;
        $data['default_Total'] = $bill->total;
        $data['default_TotalPaid'] = $bill->total_paid;
        $data['default_BillNumber'] = $bill->bill_number;
        $data['default_Remarks'] = $bill->Remarks;
        $data['default_PayMode'] = $bill->pay_mode;

        if (isset($bill->pay_mode)&&($bill->pay_mode !='')) {
            $data['default_PayMode'] = '1';
            $data['dropdown_PayMode'] = $this->get_dropdown_paymode();
            $data['default_PayMode'] = $bill->pay_mode;
        }   else {
            $data['default_PayMode'] = '1';
            $data['dropdown_PayMode'] = $this->get_dropdown_paymode();
            $data['default_PayMode'] = '';
        }
        $data['default_PayMode'] = $bill->pay_mode;
        $data['default_Active'] = $bill->Active;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'bill_number' => $this->input->post('bill_number'),
                'pay_mode' => $this->input->post('pay_mode'),
                'total' => $this->input->post('total'),
                'total_paid' => $this->input->post('total_paid'),
                'Remarks' => $this->input->post('Remarks'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_sap_bill->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/sap_bill');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('bill_number', 'Numero da Factura', 'trim|xss_clean|required');
        $this->form_validation->set_rules('pay_mode', 'Forma de Pagamento', 'trim|xss_clean|required');
        $this->form_validation->set_rules('total', 'Preco de Refrencia', 'trim|xss_clean|required');
        $this->form_validation->set_rules('total_paid', 'Preco', 'trim|xss_clean|required');
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

    public function get_dropdown_paymode()

    {

        $paymode_options = array(
            'Cash' => 'Numerário',
            'POS' => 'POS',
            'Cheque'=>'Cheque',
        ); 
        return $paymode_options;
    }

}
