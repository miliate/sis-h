<?php

/**
 * Created by COLOLO.
 * User: qch
 * Date: 11/21/15
 * Time: 6:40 AM
 */
class Blood_donation extends FormController
{
    var $FORM_NAME = 'form_blood_donation';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_blood_donation');
        $this->load->model('m_user');
        $this->load_form_language();
    }

    public function exist($pid)
    {
        $res = $this->m_patient_blood_donation->get_by(array(
            'pid' => $pid
        ));
        if ($res != NULL) {
            return $res->blood_donation_id;
        }
        return 0;
    }

    public function add($pid)
    {

        $data = array();
        $data['id'] = 0;
        $data['pid'] = $pid;
        $data['default_donation_number'] = '';
        $data['default_donation_type'] = '';
        $data['default_number_of_donation'] = '';
        $data['default_prev_donation'] = '';
        $data['default_prev_place_of_donation'] = '';
        $data['default_prev_donation_date'] = '';
        $data['default_motivation'] = '';
        $data['default_gs'] = '';
        $data['default_rhesus'] = '';

        $data['default_active'] = '';
        $data['default_remarks'] = '';


        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'pid' => $pid,
                'donation_number' => $this->input->post('donation_number'),
                'donation_type' => $this->input->post('donation_type'),
                'gs' => $this->input->post('gs'),
                'rhesus' => $this->input->post('rhesus'),
                'number_of_donation' => $this->input->post('number_of_donation'),
                'prev_donation' => $this->input->post('prev_donation'),
                'prev_place_of_donation' => $this->input->post('prev_place_of_donation'),
                'prev_donation_date' => $this->input->post('prev_donation_date'),
                'motivation' => $this->input->post('motivation'),
                'remarks' => $this->input->post('remarks'),
                'active' => $this->input->post('active'),
            );
            if ($data['prev_donation'] == '0') {
                $data['number_of_donation'] = NULL;
                $data['prev_place_of_donation'] = NULL;
                $data['prev_donation_date'] = NULL;
            }

            $inserted_id = $this->m_patient_blood_donation->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            //   $this->redirect_if_no_continue('/preference/load/dador');
            $this->redirect_if_no_continue('/patient/view/'.$pid);
//            $this->redirect_if_no_continue('/blood_donation_result/add/' . $inserted_id);
        }
    }

    public function edit($id)
    {
        $blood_donation = $this->m_patient_blood_donation->get($id);
//        var_dump($blood_donation);
        if (empty($blood_donation))
            die('Id not exist');
        $data['pid'] = $blood_donation->pid;
        $data['default_donation_number'] = $blood_donation->donation_number;
        $data['default_donation_type'] = $blood_donation->donation_type;
        $data['default_number_of_donation'] = $blood_donation->number_of_donation;
        $data['default_prev_donation'] = $blood_donation->prev_donation;
        $data['default_prev_place_of_donation'] = $blood_donation->prev_place_of_donation;
        $data['default_motivation'] = $blood_donation->motivation;
        $data['default_prev_donation_date'] = $blood_donation->prev_donation_date;
        $data['default_gs'] = $blood_donation->gs;
        $data['default_rhesus'] = $blood_donation->rhesus;
        $data['default_active'] = $blood_donation->active;
        $data['default_remarks'] = $blood_donation->remarks;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'donation_number' => $this->input->post('donation_number'),
                'gs' => $this->input->post('gs'),
                'rhesus' => $this->input->post('rhesus'),
                'donation_type' => $this->input->post('donation_type'),
                'prev_donation' => $this->input->post('prev_donation'),
                'number_of_donation' => $this->input->post('number_of_donation'),
                'prev_place_of_donation' => $this->input->post('prev_place_of_donation'),
                'prev_donation_date' => $this->input->post('prev_donation_date'),
                'motivation' => $this->input->post('motivation'),
                'remarks' => $this->input->post('remarks'),
                'active' => $this->input->post('active'),
            );
            if ($data['prev_donation'] == '0') {
                $data['number_of_donation'] = NULL;
                $data['prev_place_of_donation'] = NULL;
                $data['prev_donation_date'] = NULL;
            }
            $this->m_patient_blood_donation->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/patient/view/'.$$blood_donation->pid);
        }
    }

    public function d_search() {



$this->set_top_selected_menu('blood_donation/search');

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
        $this->form_validation->set_rules('donation_number', 'Numero do Dador', 'trim|xss_clean|required');
        $this->form_validation->set_rules('gs', 'Grupo Sanguineo', 'trim|xss_clean|required');
        $this->form_validation->set_rules('rhesus', 'Rhesus', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');
    }

}

