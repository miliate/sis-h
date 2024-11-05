<?php

/**
 * Created by Jcololo.
 * User: manhdx
 * Date: 30/06/2020
 * Time: 10:29 AM$this->render('view_prescription', $data);
 */
class Patient_hospital_clinic extends FormController
{
    var $_department;

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient');
        $this->load->model('m_patient_active_list');
        $this->load->model('m_doctor');
        $this->load->model('m_patient_costs');
        $this->load->model('m_department');
        $this->load->model('m_patient_hospital_clinic');
        $this->load->model('m_patient_active_nopay');
        $this->load->model('m_admission_type');
        $this->load->model('m_patient_tracker');
        $this->load->model('m_sap_bill');
        $this->load->model('m_sap_bill_item');
        $this->load->model('m_sap_companies_type');
        $this->load->model('m_sap_companies');


        $this->_department = $this->session->userdata('department');
        $this->load_form_language();
    }

    function index()
    {
        $this->set_top_selected_menu('active_list');
        if ($this->_department == 'EMR') {
            $this->search('EMR');
        } elseif ($this->_department == 'OPD') {
            $this->search('OPD');
        } 
        elseif ($this->_department == 'SAP') {
            $this->search('SAP');
        } 
        else {
            $this->show_no_permission();
        }
    }

    public function get_dropdown_services($department_id = 56, $type = 'json')
    {
        $this->load->model('m_hospital_service');
        $result = $this->m_hospital_service->order_by('abrev')->get_many_by(array('department_id' => $department_id));

        if ($type == 'json') {
            print(json_encode($result));
        } else {
            foreach ($result as $item) {
                $drop_down[$item->service_id] = $item->abrev;
            }
            $drop_down[''] = '';
            return $drop_down;
        }
    }


    public function get_price($taxa_id, $type = 'json')
    {
        $this->load->model('m_sap_procedures');
        $result = $this->m_sap_procedures->get_by('id',$taxa_id);

        if ($type == 'json') {
            print(json_encode($result));
        } else {
            foreach ($result as $item) {
                $data[$item->Doctor_ID] = $item->Name;
            }
            return $data;
        }
    }
  

    public function create($pid)
    {

        $this->DEPARTMENT='SAP';
        if (!has_permission('special_clinic', 'create')) {
            $this->show_no_permission();
        }
        
        $data['pid'] = $pid;
        if ($this->DEPARTMENT == 'EMR') {
            $data['default_entry_time'] = date("Y-m-d H:i:s");
        } elseif ($this->DEPARTMENT == 'SAP') {
            $data['default_entry_time'] = date("Y-m-d H:i:s");
        }
        else {
            $data['default_entry_time'] = '';
        }

        $data['default_remarks'] = '';
        $data['default_active'] = '';
        $data['default_department'] = $this->DEPARTMENT;
        $data['default_reason'] = '';
        $data['default_destination'] = 'Consulta';
        $data['default_service'] = '';
        $data['default_patient_costs'] = '';
        $data['default_doctor'] = '';
        $data['default_nopay'] = '';
        $data['default_admission_type'] = '';
        $data['default_company_type'] = '';
        $data['default_company'] = '';
        $data['default_member_pid'] = '';
        $data['default_PayMode'] = '';
        $data['default_price'] = 0;
        $data['default_total'] = 0;

        $data['dropdown_reasons'] = $this->get_dropdown_reasons();
        $data['dropdown_doctor'] = $this->get_dropdown_doctor();
        $data['dropdown_patient_costs'] = $this->get_dropdown_costs();
        $data['dropdown_nopay'] = $this->get_dropdown_nopay();
        $data['dropdown_admission_type'] = $this->get_dropdown_type();
        $data['dropdown_company_type'] = $this->get_dropdown_company_type();
        $data['dropdown_company'] = $this->get_dropdown_company();        
        $data['dropdown_PayMode'] = $this->get_dropdown_paymode();


//Covid-19 TRACKER
        $data['default_temp'] = '';
        $data['default_resp'] = 0;
        $data['default_case'] = 0;

        $this->form_validation->set_rules('entry_time', 'Data da Consulta', 'trim|required|callback_check_entry_time');
        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim');
        $this->form_validation->set_rules('reason', 'Motivo de Hospitalização', 'trim|required');
        $this->form_validation->set_rules('admission_type', lang('Admission Type'), 'trim|required');
        $this->form_validation->set_rules('status', lang('Status'), 'trim|required');
        $this->form_validation->set_rules('service', lang('Service'), 'trim|required');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|required');
        $this->form_validation->set_rules('temperature', 'Temperatura', 'trim|xss_clean|required|numeric|max_length[4]');

        if ($this->DEPARTMENT == 'EMR') {
            $data['dropdown_service'] = $this->get_dropdown_services(1, 'return');
        }
        elseif ($this->DEPARTMENT == 'SAP') {
            $data['dropdown_service'] = $this->get_dropdown_services(2, 'return');
        }
        else {
            $data['dropdown_service'] = $this->get_dropdown_services(2, 'return');
          //b                                                                                                                                                      $this->form_validation->set_rules('doctor', 'Medico', 'trim|required');
        }
        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data_insert = array(
                'PID' => $pid,
                'Department' => $this->DEPARTMENT,
                'EntryTime' => $this->input->post('entry_time'),
                'HospitalizationReason' => $this->input->post('reason'),
                'Destination' => $this->input->post('destination'),
                'Service' => $this->input->post('service'),
                'Doctor_ID' => $this->input->post('doctor'),
                'cost' => $this->input->post('patient_costs'),
                'reason_nopay' => $this->input->post('reason_nopay'),
                
                'admission_type' => $this->input->post('admission_type'),
                'Remarks' => $this->input->post('remarks'),
                'Status' => $this->input->post('status'),
                'Active' => $this->input->post('active'),
                'RegistrationDate' => date("Y-m-d H:i:s")
            );

            $tracked = $this->m_patient->get($pid);
            $taxa = $this->m_sap_procedures->get($this->input->post('patient_costs'));

            if($taxa->price>0) {
                $valor=$taxa->price;
            } else {
                $valor=0;
            }


            if( $consulta =  $this->m_patient_active_list->insert($data_insert)) {
            
                $track_insert = array(
                    'pid' => $pid,
                    'consulta_id' => $consulta,
                    'firstname' => $tracked->Firstname,
                    'lastname' => $tracked->Name,
                    'gender' => $tracked->Gender,
                    'age' => $tracked->DateOfBirth,
                    'comes_from' => $this->input->post('admission_type'),
                    'reference_id' => $this->input->post('admission_type'),
                    'is_visitor' => '0',
                    'service_id' => $this->input->post('service'),
                    'temperature' => $this->input->post('temperature'),
                    'respiratory_chart' => $this->input->post('respiratory_chart'),
                    'covid19_case' => $this->input->post('covid19_case'),
                    'remarks' => $this->input->post('remarks'),
                    'status' => $this->input->post('status'),
                    'criado_em' => date("Y-m-d H:i:s"),
                    'criado_por' => $this->session->userdata('Name'),
                );

                $this->m_patient_tracker->insert($track_insert);

                $bill_insert = array(
                    'PID' => $pid,
                    'active_id' => $consulta,
                    'bill_number' => $consulta.$this->session->userdata('UID').date('dmyhis'),
                    'total' => $valor,
                    'total_paid'  => $valor,
                    'pay_mode' => $this->input->post('pay_mode'),
                    'company_type_id' => $this->input->post('company_type_id'), 
                    'company_id' => $this->input->post('company_id'), 
                    'member_pid' => $this->input->post('member_pid'),              
                    'Remarks' => $this->input->post('remarks'),
                );

                if( $bill =  $this->m_sap_bill->insert($bill_insert)) {

                    foreach ($this->input->post('patient_costs_selected') as $key => $value) {
                        $patient_costs_selected[$key] = $value;
                    }
                    $total_paid = 0;
                    foreach ($this->input->post('price_selected') as $key => $value) {
                        $price_selected[$key] = $value;
                        $total_paid += $value;
                    }
                    foreach ($this->input->post('doctor_taxa_selected') as $key => $value) {
                        $doctor_taxa_selected[$key] = $value;
                    }

                    $bill_item_list = array();
                    foreach ($this->input->post('patient_costs_selected') as $index => $bill_item) {
                        $bill_item_order = array();
                        $bill_item_order['bill_id'] = $bill;
                        $bill_item_order['item_id'] = $patient_costs_selected[$index];
                        $bill_item_order['unit_price'] = $price_selected[$index];
                        $bill_item_order['doctor'] = $doctor_taxa_selected[$index];
                        array_push($bill_item_list, $bill_item_order);
                    }
                    foreach ($bill_item_list as $bill_item_order) {
                        $this->m_sap_bill_item->insert($bill_item_order);
                    }
                    $data_update = array(
                        'total' => $total_paid,
                        'total_paid' => $total_paid
                    );
                    $this->m_sap_bill->update($bill, $data_update);
                }


                $this->session->set_flashdata(
                    'msg', 'REC: ' . ucfirst(strtolower($this->input->post("name"))) . ' Consulta do Paciente '.$consulta.' e Rastreado Com Sucesso!'
                );

               
                $this->redirect_if_no_continue('patient_hospital_clinic/edit/'.$pid);
               /* switch ($this->DEPARTMENT) {
                    case 'EMR':
                        $this->redirect_if_no_continue('active_list');
                        break;
                    case 'OPD':
                        $this->redirect_if_no_continue('active_list');
                        break;
                    case 'SAP':
                        $this->redirect_if_no_continue('patient_hospital_clinic/edit/'.$pid);
                        break;

                };*/

            } //IF consulta Saved
            else { $this->redirect_if_no_continue('patient_hospital_clinic');}

        }
    }

    public function check_entry_time()
    {
        $entry_time = $this->input->post('entry_time');
        $today = date("Y-m-d");
        $max_time = new DateTime();
        $max_time->modify('+300 day');
        $max_time = $max_time->format("Y-m-d");
        if ($entry_time < $today or $entry_time > $max_time) {
            $this->form_validation->set_message('check_entry_time', 'O tempo de entrada estava incorreto');
            return false;
        }
        return true;
    }

    public function delete_bill_item($bill_item_id)
    {
        if ($this->m_sap_bill_item->delete($bill_item_id)) {
            $this->session->set_flashdata('msg', 'Bill item deleted!');
            echo 1;
        }
    }

    public function edit($active_id)
    {
        if (!has_permission('special_clinic', 'edit')) {
            $this->show_no_permission();
        }
        $active_list = $this->m_patient_active_list->get($active_id);
   /*     $active_tracker = $this->m_patient_tracker->get(2);
        echo $active_bill = $this->m_sap_bill->get(2);*/
        $patient = $this->m_patient->get($active_list->PID);
        
        if (empty($active_list))
            die('not found');

        $data['patient'] = $patient;
        $data['active_id'] = $active_id;
        $data['pid'] = $active_list->PID;
        $data['default_entry_time'] = $active_list->EntryTime;
        $data['default_reason'] = $active_list->HospitalizationReason;
        $data['default_destination'] = $active_list->Destination;
        $data['default_remarks'] = $active_list->Remarks;
        $data['default_active'] = $active_list->Active;
        $data['default_department'] = $active_list->Department;
        $data['default_service'] = $active_list->Service;
        $data['default_patient_costs'] = $active_list->cost;
        $data['default_doctor'] = $active_list->Doctor_ID;
        $data['default_nopay'] = $active_list->reason_nopay;
        $data['default_admission_type'] = $active_list->admission_type;




        $data['default_PayMode'] = '';
        $data['default_price'] = 0;

        $data['dropdown_doctor'] = $this->m_doctor->order_by('Name', 'asc')->dropdown('Doctor_ID', 'Name');
        $data['dropdown_reasons'] = $this->get_dropdown_reasons();
        $data['dropdown_patient_costs'] = $this->get_dropdown_costs();
        $data['dropdown_nopay'] = $this->get_dropdown_nopay();
        $data['dropdown_admission_type'] = $this->get_dropdown_type();
        $data['dropdown_PayMode'] = $this->get_dropdown_paymode();
        $data['dropdown_company_type'] = $this->get_dropdown_company_type();
        $data['dropdown_company'] = $this->get_dropdown_company();        


       //Check Covid-19 cases
        if(   $patient_track = $this->m_patient_tracker->get_by(array('consulta_id' => $active_id ))) {
            $data['default_temp'] = $patient_track->temperature;
            $data['default_resp'] = $patient_track->respiratory_chart;
            $data['default_case'] = $patient_track->covid19_case;
        } else {
            $data['default_temp'] = '';
            $data['default_resp'] = '';
            $data['default_case'] ='';
        }

        if ($active_list->Department == 'EMR') {
            $data['dropdown_service'] = $this->get_dropdown_services(1, 'return');
        }
        elseif ($active_list->Department == 'SAP') {
            $data['dropdown_service'] = $this->get_dropdown_services(2, 'return');
        } 
        else {
            $data['dropdown_service'] = $this->get_dropdown_services(2, 'return');
          //  $this->form_validation->set_rules('doctor', lang('Doctor'), 'trim|required');
        }


        $sap_bill = $this->m_sap_bill->get_by(array('active_id' => $active_id));
        $data['total'] = $sap_bill->total_paid;

        $data['default_company_type'] = $sap_bill->company_type_id;
        $data['default_company'] = $sap_bill->company_id;
        $data['default_member_pid'] = $sap_bill->member_pid;

        $bill_item = $this->m_sap_bill_item->get_many_by(array('bill_id' => $sap_bill->id));
        $data['bill_item_list'] = array();
        foreach ($bill_item as $raw_bill_item) {
            $tmp_data = array();

            $tmp_data['bill_item_id'] = $raw_bill_item->id;

            if ($raw_bill_item->unit_price > 0) {
                $tmp_data['price'] = $raw_bill_item->unit_price;
            } else {
                $tmp_data['price'] = 0;
            }

            if ($raw_bill_item->item_id > 0) {
                $sap_procedure = $this->m_sap_procedures->get($raw_bill_item->item_id);
                $tmp_data['patient_costs'] = $sap_procedure->Name;
                $tmp_data['patient_costs_value'] = $raw_bill_item->item_id;
            } else {
                $tmp_data['patient_costs'] = '';
                $tmp_data['patient_costs_value'] = 0;
            }

            if ($raw_bill_item->doctor > 0) {
                $doctor = $this->m_doctor->get($raw_bill_item->doctor);
                $tmp_data['doctor'] = $doctor->Name;
                $tmp_data['doctor_id'] = $raw_bill_item->doctor;
            } else {
                $tmp_data['doctor'] = '';
                $tmp_data['doctor_id'] = 0;
            }

            array_push($data['bill_item_list'], $tmp_data);
        }

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->render('form_edit_patient_hospital_clinic', $data);
        } else {
            $data_update = array(
                'EntryTime' => $this->input->post('entry_time'),
                'HospitalizationReason' => $this->input->post('reason'),
                'Remarks' => $this->input->post('remarks'),
                'Service' => $this->input->post('service'),
                'Doctor_ID' => $this->input->post('doctor'),
                'cost' => $this->input->post('patient_costs'),
                'reason_nopay' => $this->input->post('reason_nopay'),
                'Status' => $this->input->post('status'),
                'Active' => $this->input->post('active')
            );
            $this->m_patient_active_list->update($active_id, $data_update);

            if( $patient_track) {
                $track_insert = array(
                    'consulta_id' => $patient_track->consulta_id,
                    'comes_from' => $this->input->post('admission_type'),
                    'reference_id' => $this->input->post('admission_type'),
                    'service_id' => $this->input->post('service'),
                    'temperature' => $this->input->post('temperature'),
                    'respiratory_chart' => $this->input->post('respiratory_chart'),
                    'covid19_case' => $this->input->post('covid19_case'),
                    'remarks' => $this->input->post('remarks'),
                    'status' => $this->input->post('status'),
                    'actualizado_em' => date("Y-m-d H:i:s"),
                    'actualizado_por' => $this->session->userdata('Name'),
                );

                $this->m_patient_tracker->update($patient_track->id,$track_insert);

            } else {

                $track_insert = array(
                    'consulta_id' => $this->input->post('active_id'),
                    'comes_from' => $this->input->post('admission_type'),
                    'reference_id' => $this->input->post('admission_type'),
                    'service_id' => $this->input->post('service'),
                    'temperature' => $this->input->post('temperature'),
                    'respiratory_chart' => $this->input->post('respiratory_chart'),
                    'covid19_case' => $this->input->post('covid19_case'),
                    'remarks' => $this->input->post('remarks'),
                    'status' => $this->input->post('status'),
                    'actualizado_em' => date("Y-m-d H:i:s"),
                    'actualizado_por' => $this->session->userdata('Name'),
                );

                $this->m_patient_tracker->insert($track_insert);
            }

            //Check BILL Added on 27.09.2020
            $bill = $this->m_sap_bill->get_by(array('active_id' => $active_id));
            if (empty($bill))
                die('Id not exist');

            $data['id'] = $bill->id;
            $data['default_PID'] = $bill->PID;
            $data['default_Total'] = $bill->total;
            $data['default_TotalPaid'] = $bill->total_paid;
            $data['default_BillNumber'] = $bill->bill_number;
            $data['default_Remarks'] = $bill->Remarks;
            $data['default_PayMode'] = $bill->pay_mode;
            $data['default_company_type'] = $bill->company_type_id;
            $data['default_company'] = $bill->company_id;
            $data['default_member_pid'] = $bill->member_pid;
    
           if (isset($bill->pay_mode)&&($bill->pay_mode !='')) {
                $data['default_PayMode'] = '1';
                $data['dropdown_PayMode'] = $this->get_dropdown_paymode();
                $data['default_PayMode'] = $bill->pay_mode;
            }   else {
                $data['default_PayMode'] = '1';
                $data['dropdown_PayMode'] = $this->get_dropdown_paymode();
                $data['default_PayMode'] = '';
            }
//            $data['default_PayMode'] = $bill->pay_mode;
            $data['default_Active'] = $bill->Active;

            $this->set_common_validation();
    
            if ($this->form_validation->run() == FALSE) {
                $this->load_form($data);
            } else {
//                $taxa = $this->m_sap_procedures->get($this->input->post('patient_costs'));
//
//                if($taxa->price>0) { $valor=$taxa->price;} else {$valor=0;}
//
//                $data = array(
//                    'pay_mode' => $this->input->post('pay_mode'),
//                    'total' => $valor,
//                    'total_paid' => $valor,
//                    'pay_mode' => 'Cash',
//                    'Remarks' => $this->input->post('Remarks'),
//                    'Active' => $this->input->post('Active'),
//                );

                // Delete bill item list before inserting new bill item list
                foreach ($bill_item as $raw_bill_item) {
                    $this->m_sap_bill_item->delete($raw_bill_item->id);
                }

                foreach ($this->input->post('patient_costs_selected') as $key => $value) {
                    $patient_costs_selected[$key] = $value;
                }
                $total_paid = 0;
                foreach ($this->input->post('price_selected') as $key => $value) {
                    $price_selected[$key] = $value;
                    $total_paid += $value;
                }
                foreach ($this->input->post('doctor_taxa_selected') as $key => $value) {
                    $doctor_taxa_selected[$key] = $value;
                }

                $bill_item_list = array();
                foreach ($this->input->post('patient_costs_selected') as $index => $bill_item) {
                    $bill_item_order = array();
                    $bill_item_order['bill_id'] = $bill->id;
                    $bill_item_order['item_id'] = $patient_costs_selected[$index];
                    $bill_item_order['unit_price'] = $price_selected[$index];
                    $bill_item_order['doctor'] = $doctor_taxa_selected[$index];
                    array_push($bill_item_list, $bill_item_order);
                }

                // Insert new bill item list after deleting all old bill item list
                foreach ($bill_item_list as $bill_item_order) {
                    $this->m_sap_bill_item->insert($bill_item_order);
                }

                $data_update = array(
                    'total' => $total_paid,
                    'total_paid' => $total_paid,
                    'pay_mode' => $this->input->post('pay_mode'),
                    'company_type_id' => $this->input->post('company_type_id'), 
                    'company_id' => $this->input->post('company_id'), 
                    'member_pid' => $this->input->post('member_pid'),              
                    'Remarks' => $this->input->post('remarks'),
                );
                $this->m_sap_bill->update($bill->id, $data_update);

//                $data_update = array(
//                    'total' => $total_paid,
//                    'total_paid' => $total_paid
//                );
//                $this->m_sap_bill->update($bill, $data_update);
//                $this->m_sap_bill->update($bill->id, $data);
            }

            $this->session->set_flashdata(
                'msg', $valor.'REC: Dados actualizados com Sucesso!'
            );


            $this->redirect_if_no_continue('patient_hospital_clinic');
        }
    }



    public function bill($active_id)
    {
        if (!has_permission('special_clinic', 'edit')) {
            $this->show_no_permission();
        }
        $active_list = $this->m_patient_active_list->get($active_id);
   /*     $active_tracker = $this->m_patient_tracker->get(2);
        echo $active_bill = $this->m_sap_bill->get(2);*/
        $patient = $this->m_patient->get($active_list->PID);
        
        if (empty($active_list))
            die('not found');

        $data['patient'] = $patient;
        $data['active_id'] = $active_id;
        $data['pid'] = $active_list->PID;
        $data['default_entry_time'] = $active_list->EntryTime;
        $data['default_reason'] = $active_list->HospitalizationReason;
        $data['default_destination'] = $active_list->Destination;
        $data['default_remarks'] = $active_list->Remarks;
        $data['default_active'] = $active_list->Active;
        $data['default_department'] = $active_list->Department;
        $data['default_service'] = $active_list->Service;
        $data['default_patient_costs'] = $active_list->cost;
        $data['default_doctor'] = $active_list->Doctor_ID;
        $data['default_nopay'] = $active_list->reason_nopay;
        $data['default_admission_type'] = $active_list->admission_type;




        $data['default_PayMode'] = '';
        $data['default_price'] = 0;

        $data['dropdown_doctor'] = $this->m_doctor->order_by('Name', 'asc')->dropdown('Doctor_ID', 'Name');
        $data['dropdown_reasons'] = $this->get_dropdown_reasons();
        $data['dropdown_patient_costs'] = $this->get_dropdown_costs();
        $data['dropdown_nopay'] = $this->get_dropdown_nopay();
        $data['dropdown_admission_type'] = $this->get_dropdown_type();
        $data['dropdown_PayMode'] = $this->get_dropdown_paymode();
        $data['dropdown_company_type'] = $this->get_dropdown_company_type();
        $data['dropdown_company'] = $this->get_dropdown_company();        


       //Check Covid-19 cases
        if(   $patient_track = $this->m_patient_tracker->get_by(array('consulta_id' => $active_id ))) {
            $data['default_temp'] = $patient_track->temperature;
            $data['default_resp'] = $patient_track->respiratory_chart;
            $data['default_case'] = $patient_track->covid19_case;
        } else {
            $data['default_temp'] = '';
            $data['default_resp'] = '';
            $data['default_case'] ='';
        }

        if ($active_list->Department == 'EMR') {
            $data['dropdown_service'] = $this->get_dropdown_services(1, 'return');
        }
        elseif ($active_list->Department == 'SAP') {
            $data['dropdown_service'] = $this->get_dropdown_services(2, 'return');
        } 
        else {
            $data['dropdown_service'] = $this->get_dropdown_services(2, 'return');
          //  $this->form_validation->set_rules('doctor', lang('Doctor'), 'trim|required');
        }


        $this->form_validation->set_rules('patient_costs', 'Custo da Consulta', 'trim|required');
        $this->form_validation->set_rules('patient_costs_selected[]', 'Custo da Consulta', 'xss_clean|required');
        $this->form_validation->set_rules('price_selected[]', 'Price', 'xss_clean|required');
        $this->form_validation->set_rules('doctor_taxa_selected[]', 'Doctor', 'xss_clean|required');


        $sap_bill = $this->m_sap_bill->get_by(array('active_id' => $active_id));
        $data['total'] = $sap_bill->total_paid;

        $data['default_company_type'] = $sap_bill->company_type_id;
        $data['default_company'] = $sap_bill->company_id;
        $data['default_member_pid'] = $sap_bill->member_pid;

        $bill_item = $this->m_sap_bill_item->get_many_by(array('bill_id' => $sap_bill->id));
        $data['bill_item_list'] = array();
        foreach ($bill_item as $raw_bill_item) {
            $tmp_data = array();

            $tmp_data['bill_item_id'] = $raw_bill_item->id;

            if ($raw_bill_item->unit_price > 0) {
                $tmp_data['price'] = $raw_bill_item->unit_price;
            } else {
                $tmp_data['price'] = 0;
            }

            if ($raw_bill_item->item_id > 0) {
                $sap_procedure = $this->m_sap_procedures->get($raw_bill_item->item_id);
                $tmp_data['patient_costs'] = $sap_procedure->Name;
                $tmp_data['patient_costs_value'] = $raw_bill_item->item_id;
            } else {
                $tmp_data['patient_costs'] = '';
                $tmp_data['patient_costs_value'] = 0;
            }

            if ($raw_bill_item->doctor > 0) {
                $doctor = $this->m_doctor->get($raw_bill_item->doctor);
                $tmp_data['doctor'] = $doctor->Name;
                $tmp_data['doctor_id'] = $raw_bill_item->doctor;
            } else {
                $tmp_data['doctor'] = '';
                $tmp_data['doctor_id'] = 0;
            }

            array_push($data['bill_item_list'], $tmp_data);
        }

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->render('form_bill_patient_hospital_clinic', $data);
        } else {
            $data_update = array(
                'EntryTime' => $this->input->post('entry_time'),
                'HospitalizationReason' => $this->input->post('reason'),
                'Remarks' => $this->input->post('remarks'),
                'Service' => $this->input->post('service'),
                'Doctor_ID' => $this->input->post('doctor'),
                'cost' => $this->input->post('patient_costs'),
                'reason_nopay' => $this->input->post('reason_nopay'),
                'Status' => $this->input->post('status'),
                'Active' => $this->input->post('active')
            );
            $this->m_patient_active_list->update($active_id, $data_update);

            if( $patient_track) {
                $track_insert = array(
                    'consulta_id' => $patient_track->consulta_id,
                    'comes_from' => $this->input->post('admission_type'),
                    'reference_id' => $this->input->post('admission_type'),
                    'service_id' => $this->input->post('service'),
                    'temperature' => $this->input->post('temperature'),
                    'respiratory_chart' => $this->input->post('respiratory_chart'),
                    'covid19_case' => $this->input->post('covid19_case'),
                    'remarks' => $this->input->post('remarks'),
                    'status' => $this->input->post('status'),
                    'actualizado_em' => date("Y-m-d H:i:s"),
                    'actualizado_por' => $this->session->userdata('Name'),
                );

                $this->m_patient_tracker->update($patient_track->id,$track_insert);

            } else {

                $track_insert = array(
                    'consulta_id' => $this->input->post('active_id'),
                    'comes_from' => $this->input->post('admission_type'),
                    'reference_id' => $this->input->post('admission_type'),
                    'service_id' => $this->input->post('service'),
                    'temperature' => $this->input->post('temperature'),
                    'respiratory_chart' => $this->input->post('respiratory_chart'),
                    'covid19_case' => $this->input->post('covid19_case'),
                    'remarks' => $this->input->post('remarks'),
                    'status' => $this->input->post('status'),
                    'actualizado_em' => date("Y-m-d H:i:s"),
                    'actualizado_por' => $this->session->userdata('Name'),
                );

                $this->m_patient_tracker->insert($track_insert);
            }

            //Check BILL Added on 27.09.2020
            $bill = $this->m_sap_bill->get_by(array('active_id' => $active_id));
            if (empty($bill))
                die('Id not exist');

            $data['id'] = $bill->id;
            $data['default_PID'] = $bill->PID;
            $data['default_Total'] = $bill->total;
            $data['default_TotalPaid'] = $bill->total_paid;
            $data['default_BillNumber'] = $bill->bill_number;
            $data['default_Remarks'] = $bill->Remarks;
            $data['default_PayMode'] = $bill->pay_mode;
            $data['default_company_type'] = $bill->company_type_id;
            $data['default_company'] = $bill->company_id;
            $data['default_member_pid'] = $bill->member_pid;
    
           if (isset($bill->pay_mode)&&($bill->pay_mode !='')) {
                $data['default_PayMode'] = '1';
                $data['dropdown_PayMode'] = $this->get_dropdown_paymode();
                $data['default_PayMode'] = $bill->pay_mode;
            }   else {
                $data['default_PayMode'] = '1';
                $data['dropdown_PayMode'] = $this->get_dropdown_paymode();
                $data['default_PayMode'] = '';
            }
//            $data['default_PayMode'] = $bill->pay_mode;
            $data['default_Active'] = $bill->Active;

            $this->set_common_validation();
    
            if ($this->form_validation->run() == FALSE) {
                $this->load_form($data);
            } else {

                // Delete bill item list before inserting new bill item list
                foreach ($bill_item as $raw_bill_item) {
                    $this->m_sap_bill_item->delete($raw_bill_item->id);
                }

                foreach ($this->input->post('patient_costs_selected') as $key => $value) {
                    $patient_costs_selected[$key] = $value;
                }
                $total_paid = 0;
                foreach ($this->input->post('price_selected') as $key => $value) {
                    $price_selected[$key] = $value;
                    $total_paid += $value;
                }
                foreach ($this->input->post('doctor_taxa_selected') as $key => $value) {
                    $doctor_taxa_selected[$key] = $value;
                }

                $bill_item_list = array();
                foreach ($this->input->post('patient_costs_selected') as $index => $bill_item) {
                    $bill_item_order = array();
                    $bill_item_order['bill_id'] = $bill->id;
                    $bill_item_order['item_id'] = $patient_costs_selected[$index];
                    $bill_item_order['unit_price'] = $price_selected[$index];
                    $bill_item_order['doctor'] = $doctor_taxa_selected[$index];
                    array_push($bill_item_list, $bill_item_order);
                }

                // Insert new bill item list after deleting all old bill item list
                foreach ($bill_item_list as $bill_item_order) {
                    $this->m_sap_bill_item->insert($bill_item_order);
                }

                $data_update = array(
                    'total' => $total_paid,
                    'total_paid' => $total_paid,
                    'pay_mode' => $this->input->post('pay_mode'),
                    'company_type_id' => $this->input->post('company_type_id'), 
                    'company_id' => $this->input->post('company_id'), 
                    'member_pid' => $this->input->post('member_pid'),              
                    'Remarks' => $this->input->post('remarks'),
                );
                $this->m_sap_bill->update($bill->id, $data_update);
            }

            $this->session->set_flashdata(
                'msg', $valor.'REC: Dados actualizados com Sucesso!'
            );


            $this->redirect_if_no_continue('patient_hospital_clinic');
        }
    }






    public function search($department)
    {

        $department='SAP';
         if ($this->DEPARTMENT == 'EMR') {
            $dropdown_services = $this->get_dropdown_services(1, 'return');
        } elseif ($this->DEPARTMENT == 'SAP') {
            $dropdown_services = $this->get_dropdown_services(3, 'return');
        } else {
            $dropdown_services = $this->get_dropdown_services(2, 'return');
        }


        $option_service = ':All;';
        foreach ($dropdown_services as $service) {
            if (strlen($service) > 0) {
                $option_service .= $service . ':' . $service . ';';
            }
        }
        $dropdown_reason = $this->get_dropdown_nopay();
        $option_reason = ':All;';
        foreach ($dropdown_reason as $reason) {
            if (strlen($reason) > 0) {
                $option_reason .= $reason . ':' . $reason . ';';
            }
        }


        if (!has_permission('special_clinic', 'view')) {
            $this->show_no_permission();
        }
        $qry = "SELECT
                patient_active_list.ACTIVE_ID,
                SUBSTR(patient_active_list.RegistrationDate, 1, 16) as RegistrationDate,
                SUBSTR(patient_active_list.EntryTime, 1, 10) as EntryTime,
                patient.PID,
                CONCAT(patient.Firstname,' ',patient.Name) AS Patient,
                patient_emr_reasons.HospitalizationReason,
                patient_active_list.Destination,
                hospital_services.abrev,
               CONCAT( round(patient_tracker.temperature, 1), '°C'),
              /*  doctor.Name,*/
                CONCAT( round(sap_procedures.price,2),'=>',sap_procedures.Name),
               /* patient_active_list.cost,*/
                patient_active_nopay.name,
                /*patient_active_list.Remarks,*/
                patient_active_list.Status
                FROM patient_active_list
                LEFT JOIN patient ON patient.PID = patient_active_list.PID
                LEFT JOIN hospital_services ON hospital_services.service_id = patient_active_list.Service
                LEFT JOIN patient_emr_reasons On patient_emr_reasons.PEMRRID = patient_active_list.HospitalizationReason
                LEFT JOIN patient_tracker ON patient_tracker.consulta_id = patient_active_list.ACTIVE_ID
              /*  LEFT JOIN doctor ON doctor.Doctor_ID = patient_active_list.Doctor_ID*/
                LEFT JOIN patient_active_nopay ON patient_active_nopay.id = patient_active_list.reason_nopay
                LEFT JOIN sap_procedures ON sap_procedures.id = patient_active_list.cost
                WHERE patient_active_list.Active = 1 AND Department = '" . $department . "'
                ";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setRowid('ACTIVE_ID');
        $page->setSortname('ACTIVE_ID');
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('ACTIVE_ID');
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("Active ID", lang("Time"), lang("VisitDate"), lang("Patient ID"), lang("Patient"),
            lang('Hospitalization Reason'), lang('Destination'), lang("Service"),"Temp", lang("cost"), "Motivo de Isenção", lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("ACTIVE_ID", array("hidden" => true));
        $page->setColOption("PID", array("search" => true, "width" => 200));
        $page->setColOption("RegistrationDate", $page->getDateSelector());
        if ($this->get_session('user_group_id') != 25) {//this is for emr registrar
            $page->setColOption("EntryTime", $page->getDateSelector(date('Y-m-d')));
        } else {
            $page->setColOption("EntryTime", $page->getDateSelector());
        }
        $page->setColOption('abrev', array('stype' => 'select',
            'editoptions' => array(
                'value' => $option_service
            ), 'width' => '120'));

        $page->setColOption('name', array('stype' => 'select',
            'editoptions' => array(
                'value' => $option_reason
            ), 'width' => '70'));

        $page->setColOption('Destination', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':Todos;Alta:Alta;Consulta:Consulta'
            ), 'width' => '120'));


        $page->setColOption('Status', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':All;'.lang('Pending').':'.lang('Pending').';'.lang('Triage').':'.lang('Triage').';'.lang('Observe').':'.lang('Observe')
            ), 'width' => '70'));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
        }
        if (alertText.match(/^.*Pending/)||alertText.match(/^.*Pendente/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*In Progress/))
        {
            $(\'#\'+rowid).css({\'background\':\'#7deaea\'});
        }
        if (alertText.match(/^.*Triage/)||alertText.match(/^.*Triagem/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
        }
        if (alertText.match(/^.*Observe/)||alertText.match(/^.*Em observacao/))
        {
            $(\'#\'+rowid).css({\'background\':\'#00d185\'});
        }
       }');

        if ($department == 'OPD') {

        } elseif ($department == 'EMR') {

        }
        //default group
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                    var patient_id = $(this).find('td:nth-child(4)').text();
                    window.location='" . site_url("/patient_hospital_clinic/edit") . "/'+patient_id+'';
            });
            }";
//        registrar group
        if (Modules::run('permission/check_permission', 'special_clinic', 'edit')) {
            $page->gridComplete_JS
                = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var status = $(this).find('td:nth-child(12)').text();
                var rowId = $(this).attr('id');
                window.location='" . site_url("/patient_hospital_clinic/edit") . "/'+rowId+'';
            });
            }";
        }
//        EMR triage doctor group & OPD doctor group
        if (Modules::run('permission/check_permission', 'add_patient_from_active_list', 'create')) {
            $page->gridComplete_JS
                = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var status = $(this).find('td:nth-child(13)').text();
                var rowId = $(this).attr('id');
                if (status == 'Pending'|| status == 'Pendente') {
                    var rowId = $(this).attr('id');
                    $('#confirm-modal').modal('show');
                    $('#confirm-create').attr('href','" . site_url("/active_list/start_add_patient") . "/'+rowId+'');
                } else {
                    var patient_id = $(this).find('td:nth-child(4)').text();
                    window.location='" . site_url("active_list/redirect_for_doctor") . "/'+rowId+'';
                }
            });
            }";
        }

        if (is_observe_doctor()) {
            if (Modules::run('permission/check_permission', 'emr_observe', 'create')) {
                $page->gridComplete_JS
                    = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                var status = $(this).find('td:nth-child(13)').text();
                if (status == 'Pending'|| status == 'Pendente') {
                    var rowId = $(this).attr('id');
                    $('#confirm-modal').modal('show');
                    $('#confirm-create').attr('href','" . site_url("/active_list/start_add_patient") . "/'+rowId+'');
                } else if (status == 'Triage') {
                    $('#observe-confirm-modal').modal('show');
                    $('#confirm-observe').attr('href','" . site_url("/active_list/start_add_patient") . "/'+rowId+'');
                } else {
                    window.location='" . site_url("active_list/redirect_for_doctor") . "/'+rowId+'';
                }
            });
            }";
            }
        }


        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $data['department'] = $department;
        $this->render_search($data);
    }

    public function start_add_patient($active_list_id)
    {
        $active_list = $this->m_patient_active_list->get($active_list_id);
        if (empty($active_list_id))
            die ('Not found');
        switch ($active_list->Department) {
            case 'EMR':
                if ($active_list->VisitID == 0) {
                    $this->redirect_if_no_continue('emergency_visit/create/' . $active_list->PID . '/' . $active_list->ACTIVE_ID);
                } else {
                    $this->redirect_if_no_continue('emergency_visit/add_observe/' . $active_list->VisitID);
                }
                break;
            case 'OPD':
                $this->redirect_if_no_continue('opd_visit/create/' . $active_list->PID . '/' . $active_list->ACTIVE_ID);
                break;
            default:
                die('Wrong department');
        }

    }

    public function redirect_for_doctor($active_list_id)
    {
        $active_list = $this->m_patient_active_list->get($active_list_id);
        if (empty($active_list_id))
            die ('Not found');
        switch ($active_list->Department) {
            case 'EMR':
                $this->redirect_if_no_continue('emergency_visit/view/' . $active_list->VisitID);
                break;
            case 'OPD':
                $this->redirect_if_no_continue('opd_visit/view/' . $active_list->VisitID);
                break;
            default:
                die('Wrong department');
        }

    }

    public function is_in_active_list($pid)
    {
        $current_date = date("Y-m-d");
        $sql = 'SELECT * FROM patient_active_list WHERE PID = ? AND Active = 1 AND Status != "Discharged" AND Department = ? AND CreateDate LIKE ?';
        $query = $this->db->query($sql, array($pid, $this->_department, '%' . $current_date . '%'));
//        $result['is_in_active_list'] = $query->num_rows() > 0;
        $result['is_in_active_list'] = FALSE;
        echo json_encode($result);
    }

    public function active_list_click_redirect($id)
    {
        $active_list = $this->m_patient_active_list->get($id);
        if (Modules::run('permission/check_permission', 'emr_active_patient', 'edit') || Modules::run('permission/check_permission', 'opd_active_patient', 'edit')) {
            if ($active_list->Status == 'Pending' || $active_list->Status == 'Pendente') {
                $this->redirect_if_no_continue('/active_list/edit/' + $active_list);
            } else {
                $this->redirect_if_no_continue('/patient/view.' + $active_list->PID);
            }
        }
    }

    public function get_dropdown_reasons($type = 'json')
    {
        $this->load->model('m_emergency_reason');
        $result = $this->m_emergency_reason->order_by('HospitalizationReason')->dropdown('PEMRRID', 'HospitalizationReason');
        $result[''] = '';

        if ($type == 'json') {
            // print(json_encode($result));
        }
        return $result;
    }

    public function get_dropdown_doctor()
    {
        $res = $this->m_doctor->order_by('Name', 'asc')->dropdown('Doctor_ID', 'Name');
        $res[''] = '';
        return $res;
    }

    public function get_dropdown_costs($type = 'json')
    {
        $this->load->model('m_sap_procedures');
        
        $result = $this->m_sap_procedures->order_by('Name')->dropdown('id', 'Name');
        $result[''] = '== Isento ==';
     /*   if ($type == 'json') {
            print(json_encode($result));
        }*/
        return $result;
        /*
        $re = $this->m_patient_costs->order_by('custo', 'asc')->dropdown('custo', 'descricao');
        $re[''] = '';
        return $re;*/
    }

//added on 14.02.2019 by JCOLOLO
    public function get_dropdown_nopay()
    {
        $resultado = $this->m_patient_active_nopay->order_by('name', 'asc')->dropdown('id', 'name');
        $resultado[''] = '';
        return $resultado;
    }

    //added on 14.02.2019 by JCOLOLO
    public function get_dropdown_type()
    {
        $resultado = $this->m_admission_type->order_by('id', 'asc')->dropdown('id', 'name');
        $resultado[''] = '';
        return $resultado;
    }

        //added on 27.09.2020 by JCOLOLO
    public function get_dropdown_paymode()

    {

        $paymode_options = array(
            'Cash' => 'Numerário',
            'POS' => 'POS',
            'Cheque'=>'Cheque',
        ); 
        return $paymode_options;
    }

        //added on 31.10.2021 by JCOLOLO
    public function get_dropdown_company_type()

    {
        $this->load->model('m_sap_companies_type');
        $re = $this->m_sap_companies_type->order_by('id', 'asc')->dropdown('id', 'name');
      //  $re[''] = '';
        return $re;
    }

    public function get_dropdown_company($type_id=3,$type='json')

    {
        $this->load->model('m_sap_companies');
        $re = $this->m_sap_companies->order_by('id', 'asc')->dropdown('id', 'name');

    //    $re[''] = '';

        return $re;
  
    }


    private function set_common_validation()
    {
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
    $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
    $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');
    $this->form_validation->set_rules('service', lang('service'), 'trim|required');
    $this->form_validation->set_rules('admission_type', lang('Admission Type'), 'trim|required');
    }


    public function PatientBill($bid)
    {
      
$data['bid']=$bid;

 $this->render('prints', $data);

    }

}
