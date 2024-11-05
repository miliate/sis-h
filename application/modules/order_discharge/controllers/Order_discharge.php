<?php

use ___PHPSTORM_HELPERS\object;

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Order_Discharge extends FormController
{
    const TYPE_DIE = '1';
    const TYPE_ABANDON = '2';
    const TYPE_REQUESTED_BY_FAMILY = '3';
    const TYPE_GO_HOME = '4';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_opd_visit');
        $this->load->model('m_emergency_admission');
        $this->load->model('m_discharge_order');
        $this->load->model('m_admission');
        $this->load->model('m_patient_active_list');
        $this->load->model('m_patient');
        $this->load->model('m_discharge_outcome');
        $this->load->model('m_ward_beds');
        $this->load_form_language();
    }

    public function index()
    {
        $this->search($this->session->userdata('department'));
    }

    public function outcome_is_die($outcome)
    {
        if ($outcome == 'Obito') {
            return true;
        }
        return false;
    }


    public function create_adm_discharge($adm_id)
    {
        $visit = $this->m_admission->get($adm_id);
        $pid = $visit->PID;
        $this->create($pid, 'ADM', $adm_id);
    }

    public function create_emr_discharge($emr_id)
    {
        $emr_visit = $this->m_emergency_admission->get($emr_id);
        $pid = $emr_visit->PID;
        $emrid = $emr_visit->EMRID;
        $this->create($pid, 'EMR', $emr_id);
    }

    public function create_opd_discharge($opd_id)
    {
        $opd_visit = $this->m_opd_visit->get($opd_id);
        $pid = $opd_visit->PID;
        $this->create($pid, 'OPD', $opd_id);
    }

    public function check_pass2($pass2)
    {
        require 'application/config/database.php';
        if ($pass2 != $db['default']['password_2']) {
            $this->form_validation->set_message('check_pass2', 'The password 2 you supplied does not match your existing password 2.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function create($pid, $ref_type, $ref_id)
    {
        $data = array();
        $data['order_discharge_id'] = '0';
        $data['pid'] = $pid;
        $data['ref_type'] = $ref_type;
        $data['ref_id'] = $ref_id;
        $data['id'] = 0;
        $data['default_date'] = date('Y-m-d h:m');
        $data['default_remarks'] = '';
        $data['default_out_come'] = lang('Normal Discharge');
        $data['default_active'] = '';
        $data['default_status'] = 'Pending';
        $data['default_die_type'] = '';
        $data['default_alta_type'] = '';
        $data['default_transfer_to'] = '';
        $data['default_die_moment'] = '';

        $data['default_global_result'] = '';

        $data['default_direct_diagnosis'] = '';
        $data['default_direct_diagnosis_anos'] = '';
        $data['default_direct_diagnosis_meses'] = '';
        $data['default_direct_diagnosis_dias'] = '';
        $data['default_direct_diagnosis_horas'] = '';

        $data['default_medium_diagnosis'] = '';
        $data['default_medium_diagnosis_anos'] = '';
        $data['default_medium_diagnosis_meses'] = '';
        $data['default_medium_diagnosis_dias'] = '';
        $data['default_medium_diagnosis_horas'] = '';

        $data['default_basic_diagnosis'] = '';
        $data['default_basic_diagnosis_anos'] = '';
        $data['default_basic_diagnosis_meses'] = '';
        $data['default_basic_diagnosis_dias'] = '';
        $data['default_basic_diagnosis_horas'] = '';

        $data['default_basic_diagnosis2'] = '';


        $data['default_diagnosis_confirmed_by'] = '';
        $data['default_diagnosis_assigned_by'] = '';

        switch ($ref_type) {
            case ('ADM'):
                $data['admission'] = $this->m_admission->as_array()->get($ref_id);
                break;
            case ('EMR');
                $data["visit_info"] = $this->m_emergency_admission->as_array()->get($ref_id);
                break;
            case ('OPD'):
                $data["opd_visits_info"] = $this->m_opd_visit->as_array()->get($ref_id);
                $data["is_discharged"] = $data["opd_visits_info"]["discharge_order"];
                break;
        }

        //       $this->form_validation->set_rules('password2', 'Password 2', 'trim|required|callback_check_pass2');
        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'PID' => $pid,
                'RefType' => $ref_type,
                'RefId' => $ref_id,
                'DischargeDate' => $this->input->post('date'),
                'OutCome' => $this->input->post('out_come'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks'),
                'Status' => 'Pending',
                'DieOption' => null,
                'DischargeOption' => null,
                'PlaceOfDie' => null,
                'DatetimeDie' => null,
                'DieMoment' => null,
                'MotherID' => null,
                'DieCode' => null,
                'NumberOfBaby' => null,
                'NumberOfAbortion' => null,
                'NumberOfPregnant' => null,
                'ProbablyAbandonDatetime' => null,
                'ConfirmedAbandonDatetime' => null,
                'RequesterName' => null,
                'RequesterAddress' => null,
                'RequesterContact' => null,
                'RequesterReason' => null,
                'RequesterTransportation' => null,
                'GlobalResult' => $this->input->post('global_result'),

                'DiagnosisConfirmedBy' => $this->input->post('diagnosis_confirmed_by'),
                'MadicoQueAssinouAtendeuAoFalecido' => $this->input->post('diagnosis_assigned_by'),
                'DirectDiagnosis' => $this->input->post('direct_diagnosis'),
                'MediumDiagnosis' => $this->input->post('medium_diagnosis'),
                'BasicDiagnosis' => $this->input->post('basic_diagnosis'),

                'TempoCausaDirecta_anos' => $this->input->post('TempoCausaDirecta_anos'),
                'TempoCausaDirecta_meses' => $this->input->post('TempoCausaDirecta_meses'),
                'TempoCausaDirecta_dias' => $this->input->post('TempoCausaDirecta_dias'),
                'TempoCausaDirecta_horas' => $this->input->post('TempoCausaDirecta_horas'),


                'TempoCausaIntermedia_anos' => $this->input->post('TempoCausaIntermedia_anos'),
                'TempoCausaIntermedia_meses' => $this->input->post('TempoCausaIntermedia_meses'),
                'TempoCausaIntermedia_dias' => $this->input->post('TempoCausaIntermedia_dias'),
                'TempoCausaIntermedia_horas' => $this->input->post('TempoCausaIntermedia_horas'),


                'TempoCausaBasica_anos' => $this->input->post('TempoCausaBasica_anos'),
                'TempoCausaBasica_meses' => $this->input->post('TempoCausaBasica_meses'),
                'TempoCausaBasica_dias' => $this->input->post('TempoCausaBasica_dias'),
                'TempoCausaBasica_horas' => $this->input->post('TempoCausaBasica_horas'),


                'BasicDiagnosis2' => $this->input->post('basic_diagnosis2'),

            );
            if ($this->outcome_is_die($data['OutCome'])) {
                $data['DieOption'] = $this->input->post('die_type');
                $data['PlaceOfDie'] = $this->input->post('place_of_die');
                $data['DatetimeDie'] = $this->parse_datetime_die();
                $data['DieMoment'] = $this->input->post('die_moment');
                $data['MediumDiagnosis'] = $this->input->post('medium_diagnosis_o');
                $data['BasicDiagnosis'] = $this->input->post('basic_diagnosis_o');
                $data['DieCode'] = $this->input->post('die_code');
                if ($data['DieOption'] == 'Neonatal') {
                    $data['MotherID'] = $this->input->post('mother_id');
                } elseif ($data['DieOption'] == 'Materna') {
                    $data['NumberOfBaby'] = $this->input->post('number_of_baby');
                    $data['NumberOfAbortion'] = $this->input->post('number_of_abortion');
                    $data['NumberOfPregnant'] = $this->input->post('number_of_pregnant');
                }
            } elseif ($data['OutCome'] == 'Abandono') {
                $data['ProbablyAbandonDatetime'] = $this->input->post('probably_abandon_datetime');
                $data['ConfirmedAbandonDatetime'] = $this->input->post('confirmed_abandon_datetime');
            } elseif ($data['OutCome'] == 'A Pedido') {
                $data['RequesterName'] = $this->input->post('requester_name');
                $data['RequesterAddress'] = $this->input->post('requester_address');
                $data['RequesterContact'] = $this->input->post('requester_contact');
                $data['RequesterReason'] = $this->input->post('request_reason');
                $data['RequesterTransportation'] = $this->input->post('request_transportation');
            } else {
            }

            $id = $this->m_discharge_order->insert($data);



            // uncomment from here
            //            if ($id > 0) {
            //
            //                $id = (int)$id;
            //                var_dump($id);
            //
            //                if ($ref_type == 'OPD') {
            //                    $formatados = $this->formatar_opd($id);
            //                } else {
            //                    $formatados = $this->formatar_ward($id);
            //                }
            //
            //var_dump($formatados);
            //var_dump("sent");
            //                $result = $this->export_dados($formatados);
            //                var_dump($result);
            //                $this->session->set_flashdata(
            //                    'msg',
            //                    $result . 'Alta Clínica exportada com sucesso'
            //                );
            //            }
            // uncomment to here

            switch ($ref_type) {
                case 'EMR':
                    $visitID = $this->m_patient_active_list->get_last_active_id_by_pid($pid);
                    $this->m_patient_active_list->update($visitID, array('Status' => 'Discharge'));
                    $this->m_emergency_admission->update($ref_id, array('discharge_order' => $id));
                    $this->redirect_if_no_continue('emergency_visit/view/' . $ref_id);
                    break;
                case 'OPD':
                    $visit = $this->m_opd_visit->get($ref_id);
                    $this->m_patient_active_list->update($visit->ActiveListID, array('Status' => 'Discharge'));
                    $this->m_opd_visit->update($ref_id, array('discharge_order' => $id));
                    $this->m_ward_beds->
                    $this->redirect_if_no_continue('opd_visit/view/' . $ref_id);
                    break;
                case 'ADM':
                    $update_data = array(
                        'discharge_order' => $id,
                        'IsDischarged' => 1
                    );
                    $this->m_admission->update($ref_id, $update_data);
                    $this->redirect_if_no_continue('admission_visit/view/' . $ref_id);
                    break;
                default:
                    echo 'wrong department';
            }
        }
    }

    public function parse_datetime_die()
    {
        $date = $this->input->post('datetime_die');
        $time = $this->input->post('datetime_die_time');
        return $date . ' ' . $time;
    }

    public function edit_created($id)
    {
        $order = $this->m_discharge_order->get($id);
        //        var_dump($order);
        $data = array();
        $data['order_discharge_id'] = $order->DischargeID;
        $data['pid'] = $order->PID;
        $data['ref_type'] = $order->RefType;
        $data['ref_id'] = $order->RefID;
        $data['id'] = $id;
        $data['default_date'] = $order->CreateDate;
        $data['default_remarks'] = $order->Remarks;
        $data['default_out_come'] = $order->OutCome;
        $data['default_active'] = $order->Active;
        $data['default_status'] = $order->Status;
        $data['default_die_type'] = $order->DieOption;
        $data['default_alta_type'] = $order->DischargeOption;
        $data['default_transfer_to'] = $order->TransferTo;
        $data['default_die_moment'] = $order->DieMoment;

        $data['default_global_result'] = $order->GlobalResult;

        $data['default_direct_diagnosis'] = $order->DirectDiagnosis;
        $data['default_direct_diagnosis_anos'] = $order->TempoCausaDirecta_anos;
        $data['default_direct_diagnosis_meses'] = $order->TempoCausaDirecta_meses;
        $data['default_direct_diagnosis_dias'] = $order->TempoCausaDirecta_dias;
        $data['default_direct_diagnosis_horas'] = $order->TempoCausaDirecta_horas;

        $data['default_medium_diagnosis'] = $order->MediumDiagnosis;
        $data['default_medium_diagnosis_anos'] = $order->TempoCausaIntermedia_anos;
        $data['default_medium_diagnosis_meses'] = $order->TempoCausaIntermedia_meses;
        $data['default_medium_diagnosis_dias'] = $order->TempoCausaIntermedia_dias;
        $data['default_medium_diagnosis_horas'] = $order->TempoCausaIntermedia_horas;

        $data['default_basic_diagnosis'] = $order->BasicDiagnosis;
        $data['default_basic_diagnosis_anos'] = $order->TempoCausaBasica_anos;
        $data['default_basic_diagnosis_meses'] = $order->TempoCausaBasica_meses;
        $data['default_basic_diagnosis_dias'] = $order->TempoCausaBasica_dias;
        $data['default_basic_diagnosis_horas'] = $order->TempoCausaBasica_horas;

        $data['default_basic_diagnosis2'] = $order->BasicDiagnosis2;


        $data['default_diagnosis_confirmed_by'] = $order->DiagnosisConfirmedBy;
        $data['default_diagnosis_assigned_by'] = $order->MadicoQueAssinouAtendeuAoFalecido;


        $this->set_common_validation();

        if ($this->form_validation->run($this) == FALSE) {
            $this->render('form_order_discharge', $data);
        } else {
            //            var_dump($_POST);
            $data = array(
                'DischargeDate' => $this->input->post('date'),
                'OutCome' => $this->input->post('out_come'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks'),
                'Status' => 'Pending',
                'DieOption' => null,
                'DischargeOption' => null,
                'TransferTo' => null,
                'PlaceOfDie' => null,
                'DatetimeDie' => $this->input->post('datetime_die') . ' ' . $this->input->post('datetime_die_time'),
                'DieMoment' => null,
                'MotherID' => null,
                'DieCode' => null,
                'NumberOfBaby' => null,
                'NumberOfAbortion' => null,
                'NumberOfPregnant' => null,
                'ProbablyAbandonDatetime' => null,
                'ConfirmedAbandonDatetime' => null,
                'RequesterName' => null,
                'RequesterAddress' => null,
                'RequesterContact' => null,
                'RequesterReason' => null,
                'RequesterTransportation' => null,
                'GlobalResult' => $this->input->post('global_result'),
                'DiagnosisConfirmedBy' => $this->input->post('diagnosis_confirmed_by'),
                'MadicoQueAssinouAtendeuAoFalecido' => $this->input->post('diagnosis_assigned_by'),
                'DirectDiagnosis' => $this->input->post('direct_diagnosis'),
                'TempoCausaDirecta_anos' => $this->input->post('TempoCausaDirecta_anos'),
                'TempoCausaDirecta_meses' => $this->input->post('TempoCausaDirecta_meses'),
                'TempoCausaDirecta_dias' => $this->input->post('TempoCausaDirecta_dias'),
                'TempoCausaDirecta_horas' => $this->input->post('TempoCausaDirecta_horas'),

                'MediumDiagnosis' => $this->input->post("medium_diagnosis"),
                'TempoCausaIntermedia_anos' => $this->input->post('TempoCausaIntermedia_anos'),
                'TempoCausaIntermedia_meses' => $this->input->post('TempoCausaIntermedia_meses'),
                'TempoCausaIntermedia_dias' => $this->input->post('TempoCausaIntermedia_dias'),
                'TempoCausaIntermedia_horas' => $this->input->post('TempoCausaIntermedia_horas'),

                'BasicDiagnosis' => $this->input->post('basic_diagnosis'),
                'TempoCausaBasica_anos' => $this->input->post('TempoCausaBasica_anos'),
                'TempoCausaBasica_meses' => $this->input->post('TempoCausaBasica_meses'),
                'TempoCausaBasica_dias' => $this->input->post('TempoCausaBasica_dias'),
                'TempoCausaBasica_horas' => $this->input->post('TempoCausaBasica_horas'),


                'BasicDiagnosis2' => $this->input->post('basic_diagnosis2'),
            );
            if ($this->outcome_is_die($data['OutCome'])) {
                $data['DieOption'] = $this->input->post('die_type');
                $data['PlaceOfDie'] = $this->input->post('place_of_die');
                $data['DatetimeDie'] = $this->parse_datetime_die();
                $data['DieMoment'] = $this->input->post('die_moment');
                $data['DieCode'] = $this->input->post('die_code');
                if ($data['DieOption'] == 'Neonatal') {
                    $data['MotherID'] = $this->input->post('mother_id');
                } elseif ($data['DieOption'] == 'Materna') {
                    $data['NumberOfBaby'] = $this->input->post('number_of_baby');
                    $data['NumberOfAbortion'] = $this->input->post('number_of_abortion');
                    $data['NumberOfPregnant'] = $this->input->post('number_of_pregnant');
                }
            } elseif ($data['OutCome'] == 'Abandono') {
                $data['ProbablyAbandonDatetime'] = $this->input->post('probably_abandon_datetime');
                $data['ConfirmedAbandonDatetime'] = $this->input->post('confirmed_abandon_datetime');
            } elseif ($data['OutCome'] == 'A Pedido') {
                $data['RequesterName'] = $this->input->post('requester_name');
                $data['RequesterName'] = $this->input->post('requester_address');
                $data['RequesterContact'] = $this->input->post('requester_contact');
                $data['RequesterReason'] = $this->input->post('request_reason');
                $data['RequesterTransportation'] = $this->input->post('request_transportation');
            } else {
            }
            $this->m_discharge_order->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Actualizado com Sucesso'
            );
            if ($this->input->post('active') == 0) {
                $update_data = array(
                    'discharge_order' => 0
                );
                switch ($order->RefType) {
                    case 'EMR':
                        $this->m_emergency_admission->update($order->RefID, $update_data);
                        $visit = $this->m_emergency_admission->get($order->RefID);
                        $this->m_patient_active_list->update($visit->ActiveListID, array('Status' => 'Discharge'));
                        $this->redirect_if_no_continue('emergency_visit/view/' . $order->RefID);
                        break;
                    case 'OPD':
                        $this->m_opd_visit->update($order->RefID, $update_data);
                        $visit = $this->m_opd_visit->get($order->RefID);
                        $this->m_patient_active_list->update($visit->ActiveListID, array('Status' => 'Discharge'));
                        $this->redirect_if_no_continue('opd_visit/view/' . $order->RefID);
                        break;
                    case 'ADM':
                        $this->m_admission->update($order->RefID, $update_data);
                        $this->redirect_if_no_continue('admission_visit/view/' . $order->RefID);
                        break;
                    default:
                        echo 'wrong department';
                }
            } else {
                switch ($order->RefType) {
                    case 'EMR':
                        $this->redirect_if_no_continue('emergency_visit/view/' . $order->RefID);
                        break;
                    case 'OPD':
                        $this->redirect_if_no_continue('opd_visit/view/' . $order->RefID);
                        break;
                    case 'ADM':
                        $this->redirect_if_no_continue('admission_visit/view/' . $order->RefID);
                        break;
                    default:
                        echo 'wrong department';
                }
            }
        }
    }

    public function edit_status($id)
    {
        $order = $this->m_discharge_order->get($id);
        $data = array();
        $data['pid'] = $order->PID;
        $data['ref_type'] = $order->RefType;
        $data['ref_id'] = $order->RefID;
        $data['id'] = $id;
        $data['default_date'] = $order->CreateDate;
        $data['default_remarks'] = $order->Remarks;
        $data['default_out_come'] = $order->OutCome;
        $data['default_active'] = $order->Active;
        $data['default_status'] = 'Done';
        $data['default_confirm'] = '';

        $this->form_validation->set_rules('confirm', 'Confirm', 'trim|xss_clean|required');


        if ($this->form_validation->run() == FALSE) {
            //            $this->load_form($data);
            $this->qch_template->load_form_layout('form_confirm_discharge', $data);
        } else {
            $data = array(
                'Remarks' => $this->input->post('remarks'),
                'Status' => 'Done',
                'ConfirmBy' => $this->session->userdata('uid')
            );
            $this->m_discharge_order->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Actualizado com Sucesso'
            );
            switch ($order->RefType) {
                case 'EMR':
                    $visit = $this->m_emergency_admission->get($order->RefID);
                    $this->m_patient_active_list->update($visit->ActiveListID, array('Status' => 'Discharged'));
                    $this->redirect_if_no_continue('order_discharge/search/EMR');
                    break;
                case 'OPD':
                    $visit = $this->m_opd_visit->get($order->RefID);
                    $this->m_patient_active_list->update($visit->ActiveListID, array('Status' => 'Discharged'));
                    $this->redirect_if_no_continue('order_discharge/search/OPD');
                    break;
                case 'ADM':
                    $this->redirect_if_no_continue('order_discharge/search/OPD');
                    break;
                default:
                    echo 'wrong department';
            }
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('out_come', 'Out Come', 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
        $this->form_validation->set_rules('direct_diagnosis', lang('Direct Diagnosis'), 'trim|xss_clean|required');
        //        $this->form_validation->set_rules('order_confirm_password', 'Order Password', 'xss_clean|callback_confirm_password_check');

        if ($this->outcome_is_die($this->input->post('out_come'))) {
            $this->form_validation->set_rules('place_of_die', lang('Place of Die'), 'trim|xss_clean|required');
            $this->form_validation->set_rules('datetime_die', lang('Date and Time of Death'), 'trim|xss_clean|required');
            $this->form_validation->set_rules('datetime_die_time', lang('Date and Time of Death'), 'trim|xss_clean|required');
            $this->form_validation->set_rules('die_moment', lang('Die Moment'), 'trim|xss_clean|required');
            //            $this->form_validation->set_rules('die_code', lang('Die Code'), 'trim|xss_clean|required');

            if ($this->input->post('die_type') == 'Neonatal') {
                $this->form_validation->set_rules('mother_id', lang('Mother ID'), 'trim|xss_clean|required');
            } elseif ($this->input->post('die_type') == 'Materna') {
            } else {
            }
        }
    }

    public function search($department)
    {
        $qry = "SELECT
                DischargeID,
                DischargeDate,
                discharge_order.RefType,
                discharge_order.PID,
                CONCAT(patient.Name,' ',patient.OtherName) AS Patient,
                discharge_order.OutCome,
                discharge_order.Status
                FROM discharge_order
                LEFT JOIN patient ON patient.PID = discharge_order.PID
                WHERE discharge_order.Active = 1 AND discharge_order.RefType = '" . $department . "'";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('DischargeID');
        $page->setCaption(lang("List patient"));
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("ID", lang("Time"), lang("Department"), lang("Patient ID"), lang("Patient Name"), lang("Out Come"), lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("DischargeID", array('hidden' => true));
        $page->setColOption("DischargeDate", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption("PID", array("search" => true, "hidden" => false, "width" => "100"));
        $page->setColOption('Status', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':All;Pending:Pending;Done:Done'
            )
        ));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
        }
        if (alertText.match(/^.*Pending/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*Done/))
        {
            $(\'#\'+rowid).css({\'background\':\'#7deaea\'});
        }
        }');
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});

            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                window.location='" . site_url("/order_discharge/edit_status/") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $data['department'] = $department;
        switch ($department) {
            case 'EMR':
                break;
            case 'OPD':
                break;
            case 'ADM':
                break;
            default:
                echo 'wrong';
        }
        $this->render_search($data);
    }

    public function render_extra_form($discharge_order_id, $type1, $type2)
    {
        //        var_dump($type1);
        //        var_dump($type2);
        $default_datetime_die = '';
        $default_place_of_die = '';
        $default_mother_id = '';
        $default_die_code = '';
        $default_die_moment = '';
        $default_number_of_baby = '';
        $default_number_of_abortion = '';
        $default_number_of_pregnant = '';
        $default_probably_abandon_datetime = '';
        $default_confirmed_abandon_datetime = '';
        $default_requester_name = '';
        $default_requester_address = '';
        $default_requester_contact = '';
        $default_request_reason = '';
        $default_request_transportation = '';


        if ($discharge_order_id > 0) {
            $order = $this->m_discharge_order->get($discharge_order_id);
            $default_datetime_die = $order->DatetimeDie;
            $default_die_moment = $order->DieMoment;
            $default_place_of_die = $order->PlaceOfDie;
            $default_mother_id = $order->MotherID;
            $default_die_code = $order->DieCode;
            $default_number_of_baby = $order->NumberOfBaby;
            $default_number_of_abortion = $order->NumberOfAbortion;
            $default_number_of_pregnant = $order->NumberOfPregnant;
            $default_probably_abandon_datetime = $order->ProbablyAbandonDatetime;
            $default_confirmed_abandon_datetime = $order->ConfirmedAbandonDatetime;
            $default_requester_name = $order->RequesterName;
            $default_requester_address = $order->RequesterAddress;
            $default_requester_contact = $order->RequesterContact;
            $default_request_reason = $order->RequesterReason;
            $default_request_transportation = $order->RequesterTransportation;
        }

        $form_generator = new MY_Form();
        $form_generator->legend($type1);
        if ($type1 == 'Obito') {
            $form_generator->input('COD', 'die_code', $default_die_code, '');
            if ($type2 == 'Neonatal') {
                $form_generator->input_with_search(lang('Mother ID'), 'mother_id', $default_mother_id);
                $form_generator->dropdown(lang('Place of Die'), 'place_of_die', array(
                    'US do SNS' => 'US do SNS',
                    'US Privada' => 'US Privada',
                    'A caminho da US' => 'A caminho da US',
                    'Domicílio' => 'Domicílio',
                    'Via_Publica' => 'Via Pública',
                    'Local_Trabalho' => 'Local de Trabalho',
                    'Outros' => 'Outros'
                ), $default_place_of_die);
                $form_generator->input_date_and_time(lang('Date and Time of Death'), 'datetime_die', $default_datetime_die);
                $form_generator->dropdown(lang('Die Moment'), 'die_moment', array(
                    'Parto' => 'Parto',
                    '< 24H' => '< 24H',
                    '24 H e < 7 dias (entre 1º-6º dia)' => '24 H e < 7 dias (entre 1º-6º dia)',
                    '7 dias e 28 dias (entre 7º-27º dia)' => '7 dias e 28 dias (entre 7º-27º dia)'
                ), $default_die_moment);
                $js = '<script type="text/javascript">
                            function load_mother_id() {
                                mother_id = $("#mother_id").val();
                                if (mother_id.length > 0) {
                                    $.ajax({
                                        url: "' . base_url() . 'index.php/order_discharge/mother_id/' . '"+ mother_id,
                                        type: "GET"
                                    }).done(function (response) {
                                        console.log(response);
                                        $("#mother_id_search_result").html(response);
                                    }).fail(function () {
                                        alert(\'Error\');
                                    });
                                }
                            }
                            $("#mother_id_btn_search").click(function () {
                                load_mother_id();
                            });
                        </script>';
                echo $js;
            } elseif ($type2 == 'Materna') {


                // for maternal woman
                $form_generator->input_date_and_time(lang('Date and Time of Death'), 'datetime_die', $default_datetime_die);
                $form_generator->input(lang('Number of Baby'), 'number_of_baby', $default_number_of_baby, '');
                $form_generator->input(lang('Number of Abortion'), 'number_of_abortion', $default_number_of_abortion, '');
                $form_generator->input(lang('Number of Pregnant'), 'number_of_pregnant', $default_number_of_pregnant, '');
                $form_generator->dropdown(lang('Place of Die'), 'place_of_die', array(
                    'US do SNS' => 'US do SNS',
                    'US Privada' => 'US Privada',
                    'A caminho da US' => 'A caminho da US',
                    'Domicilio' => 'Domicílio',
                    'Via Publica' => 'Via Pública',
                    'Local de Trabalho' => 'Local de Trabalho',
                    'Outros' => 'Outros'
                ), $default_place_of_die);
                $form_generator->dropdown(lang('Die Moment'), 'die_moment', array(
                    'Gravidez' => 'Gravidez',
                    'Parto' => 'Parto',
                    'Pos-parto imediato (24hs)' => 'Pos-parto imediato (24hs)',
                    'Pos-parto imediato (7 dias)' => 'Pos-parto imediato (7 dias)',
                    'Pos-parto tardio (ate 42)' => 'Pos-parto tardio (ate 42)',
                    'Aborto/pos-aborto' => 'Aborto/pos-aborto'
                ), $default_die_moment);
            } else {
                $form_generator->input_date_and_time(lang('Date and Time of Death'), 'datetime_die', $default_datetime_die);
                $form_generator->dropdown(lang('Place of Die'), 'place_of_die', array(
                    'US do SNS' => 'US do SNS',
                    'US Privada' => 'US Privada',
                    'A caminho da US' => 'A caminho da US',
                    'Domicílio' => 'Domicílio',
                    'Via Publica' => 'Via Pública',
                    'Local de Trabalho' => 'Local de Trabalho',
                    'Outros' => 'Outros'
                ), $default_place_of_die);
                $form_generator->dropdown(lang('Die Moment'), 'die_moment', array(
                    'Falecimento antes de 48 horas de internamento' => 'Antes de 48h',
                    'Falecimento apos de 48 horas de internamento' => 'Depois de 48h'
                ), $default_die_moment);
            }
        } elseif ($type1 == 'Abandono') {
            $form_generator->input_date_and_time(lang('Probably Abandon Time'), 'probably_abandon_datetime', $default_probably_abandon_datetime);
            $form_generator->input_date_and_time(lang('Confirmed Abandon Time'), 'confirmed_abandon_datetime', $default_confirmed_abandon_datetime);
        } elseif ($type1 == 'A Pedido') {
            $form_generator->input(lang('Requester Name'), 'requester_name', $default_requester_name);
            $form_generator->input(lang('Requester Address'), 'requester_address', $default_requester_address);
            $form_generator->input(lang('Requester Contact'), 'requester_contact', $default_requester_contact);
            $form_generator->input(lang('Request Reason'), 'request_reason', $default_request_reason);
            $form_generator->input(lang('Request Transportation'), 'request_transportation', $default_request_transportation);
        } else {
        }
    }

    public function load_additional_form($discharge_order_id)
    {
        $type1 = $this->input->post('type_1');
        $type2 = $this->input->post('type_2');
        $this->render_extra_form($discharge_order_id, $type1, $type2);
    }

    public function mother_id($id)
    {
        $patient = $this->m_patient->get($id);
        if (!empty($patient)) {
            echo Modules::run('patient/banner', $id);
            //            echo '<div class="label-danger">'. $patient->Personal_Title. ' '. $patient->Name. ' '. $patient->Firstname. '</div>';
        } else {
            echo '<div id="message1" class="alert alert-danger"">';
            echo '    <button type="button" class="close" data-dismiss="alert">&times;</button>';
            echo '    <span id="message_text">' . lang('Patient not found') . '</span>';
            echo '</div>';
        }
    }


    public function export_dados($data)
    {

        //if($id>0){
        //API URL (server)
        //    $url = 'glpi.hispmoz.org/api/sish/pending';
        // $url = 'https://glpi.hispmoz.org/api/sish/internamento/multi';
        //  $url = 'https://glpi.hispmoz.org/api/sish/internamento/pending';
        $url = 'https://glpi.hispmoz.org/api/sish/cexternas/pending';
        //create a new cURL resource


        $ch = curl_init($url);

        //setup request to send json via POST
        //  $data = "http://localhost/sis-h/export.php";
        //  $data = "http://172.20.10.8:8000/api/sish/pending/";

        //attach encoded JSON string to the POST fields
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode(json_decode($data, true), JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES)
        );

        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


        $sent = 0;
        $this->session->set_flashdata(
            'msg',
            $sent . 'Processo de evio de Dados iniciado com sucesso'
        );


        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjJiZTVkNGQzYWJjMTY4NDQ4ODA3MDU1OWQxNjk3YmU1OTllMTQzMzc2N2JmOTg5MDJhOTRkZWY2ZWYyOTQ0NjQ1ZWIyYTk4YmI5OThlODEzIn0.eyJhdWQiOiI1YzQ5YmE5ZjdkZTRiYzE3M2Q0YmQ5NTIiLCJqdGkiOiIyYmU1ZDRkM2FiYzE2ODQ0ODgwNzA1NTlkMTY5N2JlNTk5ZTE0MzM3NjdiZjk4OTAyYTk0ZGVmNmVmMjk0NDY0NWViMmE5OGJiOTk4ZTgxMyIsImlhdCI6MTU0ODQyNzAwNywibmJmIjoxNTQ4NDI3MDA3LCJleHAiOjE1Nzk5NjMwMDcsInN1YiI6IjVjNDlhNzVlN2RlNGJjNmU2MDczYTQxMiIsInNjb3BlcyI6W119.r0ybuQrDvYHkVjW01T2sRbni2u0AdaWmJZzzI7cNH9t-OCiWS1mhnhgegwkn-8469Dd9IUs4lgmx_8HSElGNMIe2GXSMjz7Q2GUmLjDcYP2WwMSv0oj5ulJ9NFQ4kmSFggqjJAmMvsIvbfoAx-d1ez6NNheiyqpPFT3j73sF3S0P0sRnQatMTNXHrLWHB21vmAPTicdy8k7jVeMMMdN-YQSXwSaT4kVhmzq-4edoplgBNjUrnoPLuD9JMHv32ulG1WgE-0lrZvsMoAIaXAq7UldgEOyTcLBFcoUwvWwtJFGxWjsHhboGXy9GABR5eYGvV-jvk0uCRohuP21pX9zAk0Gkkks77TY508XVkDrX7lvreLZxNGLNbeOA86nbduqqyFDu54X9KfCquo3TZWrZp78zcXB39PBOYLGIc1Ssx0N2YyuNx4wTduCGZcWJ2EA0B4Q_VohEDsCbbKm4jEjwiAOA0P9Bf2ZzjhmRlD87GxCllH64oSeEuEdrNIc7yiKfcad9yRjDtFEOA0grIGduf3eK7HInBZbnj_fS39RhXAv8FONuH4wBjHXuefyBn6OTbeJQ9NZka5ogyxsWVOQahy6VE8PfxENH-cbCBPIf7Ngq9D1YmLokx0T9QmkKWfDr9HyaHRI7AftabeBU7IsPyDgRuoYcTLkscgdpeY4T3Tg'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);
        //uncomment from here (1) (only in local)
        // var_dump($result);
        //var_dump($data);
        //uncomment from here (1)
        $result = 1;
        $sent = 0;
        //uncomment from here (2)
        // if($result) {
        // $sent=1;
        //   $this->session->set_flashdata(
        //       'msg', $sent.'Dados Enviados para Modulo Hospitalar com sucesso'
        //   );
        // return TRUE;
        // } else {
        //   $this->session->set_flashdata(
        //       'msg', $sent.'Não foi possível enviar dados'
        //   );

        //   return FALSE;
        // }

        // $$this->session->set_flashdata(
        //     'msg',
        //     $sent . 'Processo de envio de Dados terminado com sucesso'
        // );
        //uncomment to here (2)

        //close cURL resource
        curl_close($ch);
    }


    public function Sexo($data)
    {
        $data == 'M' ? $data = 'Masculino' : $data = 'Feminino';
        return $data;
    }


    public function idade($birthDate)
    {
        $birthDate = explode("-", $birthDate);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));
        return date("Y") - $age;
    }


    //    public function formatar_ward($ids)
    //    {
    //
    //        $conn = new mysqli("localhost", "root", "311", "hhimsv2_1");
    //        //$result=array();
    //        $table_first = 'his_sisma_ward';
    //
    //
    ////if(isset($_REQUEST['id'])&&($_REQUEST['id']>0)) { $id=$_REQUEST['id'];} else {$id=218;}
    //
    //        // $query = "SELECT * FROM $table_first WHERE DischargeID='$id'";
    //        $query = "SELECT * FROM $table_first WHERE DischargeID='" . $ids . "' ORDER BY DischargeID DESC LIMIT 1";
    //        $result = mysqli_query($conn, $query); // usernames result from DB.
    //
    //        $json = "{"; //Create variable with prepended bracket ready to append to.
    //        $i = 0; // Index to manage where our commas go.
    //        $time = date('Y-m-d H:i:s');
    //        $json .= '"_id": "' . $time . '",';
    //        $json .= '"internamentos": [
    //   {';
    //
    //        while ($row = $result->fetch_assoc()) {
    //            $sexo = "null";
    //            if ($row["Gender"] == "M") {
    //                $sexo = "Masculno";
    //            } else {
    //                $sexo = "Feminino";
    //            }
    //
    //
    //            $json .= '"unidadeSanitaria": "c6STuTMrowf",
    //          "dataDaAlta": "' . $row["DischargeDate"] . '",';
    //            $json .= '"valores": [';
    //
    //            $json .= '{  "name": "PIG - NID",
    //                                      "value": "' . $row["PID"] . '"
    //                                  },
    //                                  {
    //                                      "name": "PIG - Nome",
    //                                      "value": "' . $row["Firstname"] . '"
    //                                  },
    //                                  {
    //                                      "name": "PIG - Apelido",
    //                                      "value": "' . $row["Name"] . '"
    //                                  },
    //                                  {
    //                                      "name": "PIG - Sexo",
    //                                      "value": "' . $sexo . '"
    //                                  },
    //                                  {
    //                                      "name": "PIG - Dia de Admissão",
    //                                      "value": "' . substr($row['AdmissionDate'], 0, 10) . '"
    //                                  },
    //                                  {
    //                                      "name": "CO - Data de certificacao",
    //                                      "value": null
    //                                  },
    //                                  {
    //                                      "name": "PIG - Tipo de Admissão",
    //                                      "value": null
    //                                  },
    //                                  {
    //                                      "name": "PIG - Motivo de Admissão",
    //                                      "value": null
    //                                  },
    //                                  {
    //                                      "name": "PIG - Tipo de Alta",
    //                                      "value": "' . $row['Tipo_Alta'] . '"
    //                                  },
    //                                  {
    //                                      "name": "PIG - Resultado Global",
    //                                      "value": "' . $row['ResultadoGlobal'] . '"
    //                                  },
    //                    {
    //                        "name": "PIG - Servico de Internamento",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Admissão Compulsiva",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Apelido de Pessoa de Referencia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Bairro",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Data de Nascimento",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "eROH - Dias de Hospitalizacao",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Endereco",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Hora da Alta",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Hora de Admissão",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG  - Idade",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Província - residência habitual",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Localidade",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Nome de Pessoa de Referencia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Numero de Identificacão",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Tipo de Documento",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Outro Tipo de Admissão",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Telefone de Pessoa de Referencia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Minutos da Alta",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Minutos de Admissão",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico de alta principal",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnistico de alta secundario 2",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico de alta secundario 3",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico de alta secundario 4",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Duração da gestação",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Endereço da ocorrência, se fora da US ou domicílio - Bairro",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Endereço da ocorrência, se fora da US ou domicílio - Distrito/Cidade",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Endereço da ocorrência, se fora da US ou domicílio - País",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Endereço da ocorrência, se fora da US ou domicílio - Província",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Endereço da ocorrência, se fora da US ou domicílio - Rua/Av",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Escolaridade da mãe",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Idade da mãe",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Local da ocorrência",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Local da Ocorrência - Código da US",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Local da Ocorrência - Departamento",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Local da Ocorrência - NID",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Local da Ocorrência - Serviço",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Morte durante gravidez, parto ou aborto",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Morte ocorrida após parto",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Número de filhos nascidos mortos",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Número de filhos nascidos vivos",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Ocupação habitual ou ramo de actividade da mãe",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Peso do feto/bebé ao nascer (em gramas)",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Tipo de gravidez",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Tipo de parto",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Causa Básica da morte",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Causa Directa da morte",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Causa Intermédia da morte",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Cidade - residência habitual",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Contacto do sector do Trabalho",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Célula - residência habitual",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Código da US",
    //                        "value": "1040101"
    //                    },
    //                    {
    //                        "name": "CO - Data do Óbito ou aparececimento do cadáver",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Distrito - residência habitual",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Fonte da informação do Óbito",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Morte por acidente de trabalho",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Médico que assinou atendeu ao falecido",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Método de confirmação do diagnóstico (Autópsia)",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Nome do Médico",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Número de casa/CEP- residência habitual",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Pais - residência habitual",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Quarteirão - residência habitual",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tipo de morte não natural",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO -Bairro - residência habitual",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Escolaridade",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Estado Civil",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Nacionalidade",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Naturalidade",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tipo de Óbito",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Nome da mãe",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Nome do Pai",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Ocupacao",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Raca",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Transferido para",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Data de Nascimento Desconhecida",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Autopsia realizada",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Básica - anos",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Directa -  anos",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Intermedia - anos",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Pais - residência",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Horas de Hospitalizacao",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Registo Clinico de Admissao - Departamento",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG -  Serviços - Clinica Especial",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Serviços - Ginecologia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Serviços - Medicinas",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Serviços - Obstetrícia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Serviços - Ortopedia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Serviços - Pediatria",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Serviços - SUR",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Serviços - Cirurgia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Básica -  dias",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Básica - horas",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Básica - meses",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Básica - minutos",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Directa - dias",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Directa - horas",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Directa - meses",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Directa - minutos",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Intermedia - dias",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Intermedia - horas",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Intermedia - meses",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Tempo Causa Intermedia - minutos",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Serviços - Medicinas",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Serviços - Obstetrícia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Serviços - Ortopedia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Serviços - Pediatria",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Serviços - SUR",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Serviços - Ginecologia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Posto administrativo - residência habitual",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Serviços - Clinica Especial",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Serviços - Cirurgia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Idade - Unidade",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico principal Alternativo - Codigo",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico principal Alternativo - Descricao",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico Secundario 1 Alternativo - Descricao",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico Secudnario 2 Alternativo - Descricao",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico Secudnario 3 Alternativo - Descricao",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico Secundario 1 Alternativo - Cod",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico Secundario 2 Alternativo",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Diagnostico Secundario 3 Alternativo",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Causa Basica Alternativa",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Causa Basica Alternativa - Descricao",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Causa Directa Alternativa",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Causa Directa Alternativa - Descricao",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Causa Intermedia Alternativa",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO - Causa Intermedia Alternativa - Descricao",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CO  - Endereço da ocorrência - País - moz_outro",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "PIG - Enfermaria",
    //                        "value": null
    //                    }';
    //
    //
    //            $json .= "]";
    //        }
    //
    //        $json .= "}]";
    //        $json .= "}"; // Finally, close the json with the last square bracket.    $conn->close();
    //        return $json;
    //    }
    //
    //    public function formatar_opd($ids)
    //    {
    //
    //        $conn = new mysqli("localhost", "root", "311", "hhimsv2_1");
    //        $result = array();
    //        $table_first = 'his_sisma_opd';
    //
    //        //if(isset($_REQUEST['id'])&&($_REQUEST['id']>0)) { $id=$_REQUEST['id'];} else {$id=84;}
    //
    //        $query = "SELECT * FROM $table_first WHERE DischargeId='" . $ids . "' ORDER BY DischargeID DESC LIMIT 1";
    //        $result = mysqli_query($conn, $query); // usernames result from DB.
    //
    //        $json = "{"; //Create variable with prepended bracket ready to append to.
    //        $i = 0; // Index to manage where our commas go.
    //        $time = date('Y-m-s H:i:s');
    //        $json .= '"_id": "' . $time . '",';
    //        $json .= '"cexternas": [
    //       {';
    //
    //        while ($row = $result->fetch_assoc()) {
    //            $json .= '"unidadeSanitaria": "c6STuTMrowf",
    //              "dataConsulta": "' . $row["DatetimeDie"] . '",';
    //            $json .= '"valores": [';
    //
    //            $json .= ' {
    //                        "name": "CE - NID do Paciente",
    //                        "value": "' . $row["PID"] . '"
    //                    },
    //                    {
    //                        "name": "CE - Nome do Paciente",
    //                        "value": "' . $row["Firstname"] . '"
    //                    },
    //                    {
    //                        "name": "CE - Apelido do Paciente",
    //                        "value": "' . $row["Name"] . '"
    //                    },
    //                    {
    //                        "name": "CE - Sexo",
    //                        "value": "' . $row["Gender"] == 'M' ? 'Masculino' : 'Feminino' . '"
    //                    },
    //                    {
    //                        "name": "CE - Idade",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE-Glice-Glicémia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Esfregaço Sanguíneo (HTZ)",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Referencia/Transferencia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Observações",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Estado Vacinal da Criança",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Teste Rápido de Malária (TDR)",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - População Chave",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Diagnostico",
    //                        "value": "' . $row["DirectDiagnosis"] . '"
    //                    },
    //                    {
    //                        "name": "CE-Resultado Exame TDR",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Consultas",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Hemograma (HG)",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE-Sif-Sifilis",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Tratamento",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Hemoglobina (HG)",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Glu-Glucosúria",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Resultado HTZ",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE-Urina-Urina fita",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Referencia",
    //                        "value": null
    //                    },
    //                    {
    //                        "name": "CE - Unidade Sanitaria",
    //                        "value": null
    //                    }';
    //
    //            $json .= "]";
    //        }
    //
    //        $json .= "}]";
    //        $json .= "}"; // Finally, close the json with the last square bracket.
    //        $conn->close();
    //        return $json;
    //    }


}
