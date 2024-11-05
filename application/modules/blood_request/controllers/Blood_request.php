<?php

/**
 * Created by COLOLO.
 * User: qch
 * Date: 11/21/15
 * Time: 6:40 AM
 */
class Blood_request extends FormController
{
    var $FORM_NAME = 'form_blood_request';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_blood_donation');
        $this->load->model('m_patient_blood_request');
        $this->load->model('m_user');
        $this->load_form_language();
    }

    public function exist($pid)
    {
        $res = $this->m_patient_blood_request->get_by(array(
            'pid' => $pid
        ));
        if ($res != NULL) {
            return $res->blood_request_id;
        }
        return 0;
    }

    public function add($pid)
    {

        $data = array();
        $data['id'] = 0;
        $data['pid'] = $pid;
        $data['default_departament_id'] = '';
        $data['default_service_id'] = '';
        $data['default_request_by'] = '';
        $data['default_authorized_by'] = '';
        $data['default_response_time'] = '';
        $data['default_date_collection'] = '';
        $data['default_date_submission'] = '';
        $data['default_issued_by'] = '';
        $data['default_receved_by'] = '';
        $data['default_request_product'] = '';
        $data['default_quantity'] = '';
        $data['default_patient_gs'] = '';
        $data['default_rhesus'] = '';
        $data['default_status'] = '';
        $data['default_date_process'] = '';
        $data['default_result'] = '';
        $data['default_active'] = '';
        $data['default_remarks'] = '';


        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'pid' => $pid,
                'departament_id' => $this->input->post('departament_id'),
                'service_id' => $this->input->post('service_id'),
                'request_by' => $this->input->post('request_by'),
                'authorized_by' => $this->input->post('authorized_by'),
                'response_time' => $this->input->post('response_time'),
                'date_collection' => $this->input->post('date_collection'),
                'date_submission' => $this->input->post('date_submission'),
                'issued_by' => $this->input->post('issued_by'),
                'receved_by' => $this->input->post('receved_by'),
                'request_product' => $this->input->post('request_product'),
                'quantity' => $this->input->post('quantity'),
                'patient_gs' => $this->input->post('patient_gs'),
                'rhesus' => $this->input->post('rhesus'),
                'status' => $this->input->post('status'),
                'date_process' => $this->input->post('date_process'),
                'result' => $this->input->post('result'),
                'remarks' => $this->input->post('remarks'),
                'active' => $this->input->post('active'),
            );
           /* if ($data['prev_donation'] == '0') {
                $data['number_of_donation'] = NULL;
                $data['prev_place_of_donation'] = NULL;
                $data['prev_donation_date'] = NULL;
            }*/

            $inserted_id = $this->m_patient_blood_request->insert($data);
            $this->session->set_flashdata(
                'msg', 'Criado com sucesso'
            );
            //   $this->redirect_if_no_continue('/preference/load/dador');
            $this->redirect_if_no_continue('/patient/view/'.$pid);
//            $this->redirect_if_no_continue('/blood_donation_result/add/' . $inserted_id);
        }
    }

    public function edit($id)
    {
        $blood_request = $this->m_patient_blood_request->get($id);
//        var_dump($blood_donation);
        if (empty($blood_request))
            die('Id not exist');
        $data['pid'] = $blood_request->pid;

        $data['default_departament_id'] = $blood_request->departament_id;
        $data['default_service_id'] = $blood_request->service_id;
        $data['default_request_by'] = $blood_request->request_by;
        $data['default_authorized_by'] = $blood_request->authorized_by;
        $data['default_response_time'] = $blood_request->response_time;
        $data['default_date_collection'] = $blood_request->date_collection;
        $data['default_date_submission'] = $blood_request->date_submission;
        $data['default_issued_by'] = $blood_request->issued_by;
        $data['default_receved_by'] = $blood_request->receved_by;
        $data['default_request_product'] = $blood_request->request_product;
        $data['default_quantity'] = $blood_request->quantity;
        $data['default_patient_gs'] = $blood_request->patient_gs;
        $data['default_rhesus'] = $blood_request->rhesus;
        $data['default_status'] = $blood_request->status;
        $data['default_date_process'] = $blood_request->date_process;
        $data['default_result'] = $blood_request->result;
        $data['default_active'] = $blood_request->active;
        $data['default_remarks'] = $blood_request->remarks;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'departament_id' => $this->input->post('departament_id'),
                'service_id' => $this->input->post('service_id'),
                'request_by' => $this->input->post('request_by'),
                'authorized_by' => $this->input->post('authorized_by'),
                'response_time' => $this->input->post('response_time'),
                'date_collection' => $this->input->post('date_collection'),
                'date_submission' => $this->input->post('date_submission'),
                'issued_by' => $this->input->post('issued_by'),
                'receved_by' => $this->input->post('receved_by'),
                'request_product' => $this->input->post('request_product'),
                'quantity' => $this->input->post('quantity'),
                'patient_gs' => $this->input->post('patient_gs'),
                'rhesus' => $this->input->post('rhesus'),
                'status' => $this->input->post('status'),
                'date_process' => $this->input->post('date_process'),
                'result' => $this->input->post('result'),
                'remarks' => $this->input->post('remarks'),
                'active' => $this->input->post('active'),
            );
           
            $this->m_patient_blood_request->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Actualizado com sucesso'
            );
            $this->redirect_if_no_continue('/patient/view/'.$blood_request->pid);
        }
    }

    public function d_search() {



$this->set_top_selected_menu('blood_request/search');

        $this->load->model('mpager');
        $this->load->model('m_patient');
        $this->load->model('m_user');
        $pager2 = $this->mpager;

        $pager2->setSql(
            "select blood_donation_id,pid from patient_blood_donation" );
       /* $pager2->setSql(
            "select blood_donation_id,pid, patient_blood_donation.donation_number, patient_blood_donation.donation_type, patient_blood_donation.prev_donation, patient_blood_donation.number_of_donation, patient_blood_donation.prev_place_of_donation, patient_blood_donation.motivation, patient_blood_donation.prev_donation_date, patient_blood_donation.CreateDate,
            CONCAT(user.Name,' ',user.OtherName) AS Created_By
            from patient_blood_donation
            LEFt JOIN user ON user.UID = patient_blood_donation.CreateUser"
        );*/

        $pager2->setDivId('tablecont1'); //important
        $pager2->setDivStyle('width:100%;margin:0 auto;');
        $pager2->setRowid('blood_donation_id');
//        $pager2->setWidth("95%");
     //   $tools = "";
      //  $pager2->setCaption($tools);
        $pager2->setColNames(array("NID",));
        $pager2->setColOption("pid", array("search" => true, "hidden" => false, "height" => 100, "width" => 100));
    
        $pager2->setOrientation_EL("L");
        $data['pager'] = $pager2->render(false);
        $this->qch_template->load_form_layout('search', $data);

    }


public function search()
    {

        if (!Modules::run('permission/check_permission', 'blood_donation', 'print')) {
            die('You do not have permission');
        }

        $this->set_top_selected_menu('blood_donation/search');
        $uid = $this->session->userdata('uid');
        
        $qry = "SELECT 
        patient_blood_donation.blood_donation_id,
        patient.PID,
        CONCAT(patient.Firstname,' ',patient.Name) AS 'Nome do Dador',
        CONCAT(patient_blood_donation.gs,'',patient_blood_donation.rhesus) AS 'Grupo',
        patient_blood_donation.donation_number,
        patient_blood_donation.donation_type,
        patient_blood_donation.prev_donation,
        patient_blood_donation.number_of_donation,
        patient_blood_donation.prev_donation_date, 
        CONCAT(user.Name,' ',user.OtherName) AS 'Criado Por',
        patient_blood_donation.CreateDate
        
        FROM patient_blood_donation
        LEFT JOIN patient ON patient.PID = patient_blood_donation.pid
        LEFT JOIN user ON user.UID = patient_blood_donation.CreateUser";




        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('blood_donation_id');
        $page->setRowid('blood_donation_id');
        $tools = "";
        $page->setCaption($tools);
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("#","NID","Nome do Dador","GS","Num Dador",lang('Donation Type'),'DA','ND',"Última Doação","Criado Por","Criado Em"));


        $page->setRowNum(25);
        $page->setColOption("blood_donation_id", array("search" => true, "hidden" => false, "height" => 100, "width" => 25) );
        $page->setColOption("PID", array("search" => true, "hidden" => false, "height" => 100, "width" => 25) );
        $page->setColOption("Nome do Dador", array("search" => true, "hidden" => false, "height" => 100, "width" => 100));
        $page->setColOption( "Grupo", array("search" => true, "hidden" => false, "height" => 100, "width" => 15));
        $page->setColOption( "donation_number", array("search" => true, "hidden" => false, "height" => 100, "width" => 30));
        $page->setColOption("donation_type", array("search" => true, "hidden" => false, "height" => 100, "width" => 45));
         $page->setColOption('prev_donation',   
          array('stype' => 'select',
            'editoptions' => array(
                'value' => ':TODOS;1:SIM;0:NAO'
            ), 'width' => '50'));
        $page->setColOption("number_of_donation", array("search" => true, "hidden" => false, "height" => 100, "width" => 25));
        $page->setColOption("prev_donation_date", array("search" => true, "hidden" => false, "height" => 100, "width" => 50));
        $page->setColOption("Criado Por", array("search" => true, "hidden" => false, "height" => 100, "width" => 100));
        $page->setColOption("CreateDate", array("search" => true, "hidden" => false, "height" => 100, "width" => 50));


 //set actions
        $action = 'blood_donation_result/add';


$page->setSortname('blood_donation_id');
        $page->gridComplete_JS
            = "function() {

            var c = null;
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                c = $(this).css('background');
                $(this).css({'background':'#FFFFFF','cursor':'pointer'});
            }).mouseout(function(e){
            $(this).css('background',c);
            }).mousedown(function(e){
                var rowId = $(this).attr('id');
                 window.location='" . base_url() . "index.php/blood_donation_result/add/'+rowId;
              
            });
                
            }";

        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->qch_template->load_form_layout('search', $data);
    }



    private function set_common_validation()
    {
        $this->form_validation->set_rules('departament_id', 'O Sector que solicita', 'trim|xss_clean|required');
        $this->form_validation->set_rules('service_id', 'O Serviço que solicita', 'trim|xss_clean|required');
        $this->form_validation->set_rules('request_by', 'O Solicitante', 'trim|xss_clean');
        $this->form_validation->set_rules('authorized_by', 'A pessoa que autoriza', 'trim|xss_clean|required');
        $this->form_validation->set_rules('response_time', 'Tempo de resposta', 'trim|xss_clean');
        $this->form_validation->set_rules('date_collection', 'Data da Colecta', 'trim|xss_clean');
        $this->form_validation->set_rules('date_submission', 'Data de envio', 'trim|xss_clean|required');        
        $this->form_validation->set_rules('issued_by', 'A pessoa que expede', 'trim|xss_clean|required');
        $this->form_validation->set_rules('receved_by', 'A pessoa que recebe', 'trim|xss_clean|required');
        $this->form_validation->set_rules('request_product', 'Tipo de produto pedido', 'trim|xss_clean|required');
        $this->form_validation->set_rules('quantity', 'Quantidade', 'trim|xss_clean|required');
        $this->form_validation->set_rules('patient_gs', 'Grupo Sanguineo do doente', 'trim|xss_clean|required');
        $this->form_validation->set_rules('rhesus', 'Rhesus', 'trim|xss_clean|required');
        $this->form_validation->set_rules('status', 'Estado do pedido', 'trim|xss_clean|required');
        $this->form_validation->set_rules('date_process', 'Data do processamento', 'trim|xss_clean|required');
        $this->form_validation->set_rules('result', 'Resultado', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');
    }

}

