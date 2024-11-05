<?php

/**
 * Created by COLOLO.
 * User: qch
 * Date: 06/12/2018
 * Time: 15:10 AM
 */
class Processo_clinico extends FormController
{
    var $FORM_NAME = 'form_processo_clinico';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_processo_clinico');
        $this->load->model('m_user');
        $this->load_form_language();
    }

    public function exist($pid)
    {
        $res = $this->m_patient_processo_clinico->get_by(array(
            'pid' => $pid
        ));
        if ($res != NULL) {
            return $res->id;
        }
        return 0;
    }

    public function add($pid)
    {

        $data = array();
        $data['id'] = 0;
        $data['pid'] = $pid;
        $data['default_data'] =  date("Y-m-d H:i:s");
        $data['default_expede'] = '';
        $data['default_recebe'] = '';
        $data['default_active'] = '';
        $data['default_remarks'] = '';


        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'pid' => $pid,
                'data_entrada' => $this->input->post('data_entrada'),
                'expede' => $this->input->post('expede'),
                'recebe' => $this->input->post('recebe'),
                'remarks' => $this->input->post('remarks'),
                'active' => $this->input->post('active'),
            );

            $inserted_id = $this->m_patient_processo_clinico->insert($data);
            $this->session->set_flashdata(
                'msg', 'Processo Clínico Salvo com Sucesso'
            );
            //   $this->redirect_if_no_continue('/preference/load/dador');
            $this->redirect_if_no_continue('/patient/view/'.$pid);
//            $this->redirect_if_no_continue('/processo_clinico_result/add/' . $inserted_id);
        }
    }

    public function edit($id)
    {
        $processo_clinico = $this->m_patient_processo_clinico->get($id);
        if (empty($processo_clinico))
            die('Id not exist');
        $data = array();
        $data['id'] = $this->m_patient_processo_clinico->as_array()->get($id);
        $data['pid'] = $data['id']['PID'];
        $data['default_data'] =  $data['id']['data_entrada'];
        $data['default_expede'] = $data['id']['expede'];
        $data['default_recebe'] = $data['id']['recebe'];
        $data['default_active']  = $data['id']['active'];
        $data['default_remarks'] = $data['id']['remarks'];


        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
              'data_entrada' => $this->input->post('data_entrada'),
              'expede' => $this->input->post('expede'),
              'recebe' => $this->input->post('recebe'),
              'remarks' => $this->input->post('remarks'),
              'active' => $this->input->post('active'),
            );
            $this->m_patient_processo_clinico->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/patient/view/'.$$processo_clinico->pid);
        }
    }

    public function d_search() {



$this->set_top_selected_menu('processo_clinico/search');

        $this->load->model('mpager');
        $this->load->model('m_patient');
        $this->load->model('patient_processo_clinico');
        $this->load->model('m_user');
        $pager2 = $this->mpager;

        $pager2->setSql(
            "select id,pid from patient_processo_clinico" );
        $pager2->setDivId('tablecont1'); //important
        $pager2->setDivStyle('width:100%;margin:0 auto;');
        $pager2->setRowid('id');
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

      /*  if (!Modules::run('permission/check_permission', 'processo_clinico', 'print')) {
            die('You do not have permission');
        }*/

        $this->set_top_selected_menu('processo_clinico/search');
        $uid = $this->session->userdata('uid');

        $qry = "SELECT
        patient_processo_clinico.id,
        patient.PID,
        CONCAT(patient.Firstname,' ',patient.Name) AS 'Nome do Paciente',
        patient_processo_clinico.data_entrada,
        patient_processo_clinico.expede,
        patient_processo_clinico.recebe,
        patient_processo_clinico.remarks,
        patient_processo_clinico.active,
        CONCAT(user.Name,' ',user.OtherName) AS 'Criado Por',

patient_processo_clinico.CreateDate
        FROM patient_processo_clinico
        LEFT JOIN patient ON patient.PID = patient_processo_clinico.pid
        LEFT JOIN user ON user.UID = patient_processo_clinico.CreateUser";

        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('id');
        $page->setRowid('id');
        $tools = "";
        $page->setCaption($tools);
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("#","NID","Nome do Paciente","Data Entrada","Expede","Recebe","Observação","Status","Criado Por","Criado Em"));

        $page->setRowNum(25);
      /*  $page->setColOption("id", array("search" => true, "hidden" => false, "height" => 100, "width" => 25) );
        $page->setColOption("PID", array("search" => true, "hidden" => false, "height" => 100, "width" => 25) );
        $page->setColOption("Nome do Paciente", array("search" => true, "hidden" => false, "height" => 100, "width" => 100) );
        $page->setColOption("remarks", array("search" => true, "hidden" => false, "height" => 100, "width" => 100) );
        $page->setColOption("active", array("search" => true, "hidden" => false, "height" => 100, "width" => 10) );
        $page->setColOption("Criado Por", array("search" => true, "hidden" => false, "height" => 100, "width" => 10));
        $page->setColOption("CreateDate", array("search" => true, "hidden" => false, "height" => 100, "width" => 50));
*/

 //set actions
        $action = 'arquivo_clinico/edit/';


$page->setSortname('id');
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
                 window.location='" . base_url() . "index.php/arquivo_clinico/edit/'+rowId;

            });

            }";





        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->qch_template->load_form_layout('search', $data);
    }



    private function set_common_validation()
    {
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');
    }

}
