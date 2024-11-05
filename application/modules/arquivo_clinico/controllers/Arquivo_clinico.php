<?php

/**
 * Created by COLOLO.
 * User: qch
 * Date: 06/12/2018
 * Time: 15:10 AM
 */
class Arquivo_clinico extends FormController
{
    var $FORM_NAME = 'form_arquivo_clinico';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_arquivo_clinico');
        $this->load->model('m_user');
        $this->load->model( 'm_hospital_department');
        $this->load->model( 'm_hospital_service');
        $this->load_form_language();
    }

    public function exist($pid)
    {
        $res = $this->m_patient_arquivo_clinico->get_by(array(
            'pid' => $pid
        ));
        if ($res != NULL) {
            return $res->id;
        }
        return 0;
    }

    public function view($pid)
    {

        if (!has_permission('clinical_storage', 'print')) {
            $this->show_no_permission();
        }
            if (!$this->exist($pid)) 
                die('not found');

        $res = $this->m_patient_arquivo_clinico->get_by(array(
            'PID' => $pid
        ));
        if ($res != NULL) {
          /*  $data = array();
            $data['PID'] = $res->PID;*/

            $this->load->model('mpersistent');
            
           
            $data["patient_info"] = $this->mpersistent->open_id($pid, "patient", "PID");
    
            if (empty($data["patient_info"])) {
                $data["error"] = "Patient not found";
                $this->load->vars($data);
                $this->load->view('patient_error');
            }
            if (isset($data["patient_info"]["DateOfBirth"])) {
              /*  $this->load->model('m_patient');
                $data["patient_info"]["Age"] = $this->get_age($data["patient_info"]["DateOfBirth"]);*/
            }
           /* $data["patient_info"]["HIN"] = $this->print_hin($data["patient_info"]["HIN"]);*/
            $data["PID"] = $pid;
            $this->load->vars($data);
       

            $this->render('archive_view', $data);
            //return $res->id;
        }
        

    }  


    public function upload($pid) {

        
        $data = array();
        $data[ 'id'] = 0;
        $data[ 'pid'] = $pid;
        $data[ 'default_data_entrada'] = date('Y-m-d');
        $data[ 'default_department'] = '';
        $data[ 'default_service'] = '';
        $data[ 'default_remetente'] = '';
        $data[ 'default_autorizado_por'] = '';
        $data[ 'default_tipo_alta'] = '';
        $data[ 'default_diagnostico_alta'] = '';
        $data[ 'default_cid10_alta'] = '';
        $data[ 'default_data_alta'] = '';
        $data[ 'default_recebido_por'] = '';
        $data[ 'default_recebido_em'] = date('Y-m-d');
        $data[ 'default_estado_arquivo'] = '';
        $data[ 'default_active'] = '';
        $data[ 'default_remarks'] = '';

       // $patient_id = $this->input->post('patient_id'); // Get patient ID from form submission

        // Validate patient ID and other necessary input fields

        // Load Upload library with configuration
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx';
        $config['max_size'] = 1024 * 5; // 5MB limit
        $this->load->library('upload', $config);
    

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->render('form_upload', $data);
        } else {

            $data = [
                'patient_id' => $id,
                'filename' => $file['file_name'],
                'filepath' => $patient_folder . '/' . $file['file_name'],
                'filetype' => $file['file_type'],
                // Add other relevant fields as needed
            ];
            $this->db->insert('files', $data);
            



        }
    

    }

    private function store_file_info($uploaded_data, $patient_id) {
        // Implement your logic to store file information in the database using your chosen model
        foreach ($uploaded_data as $file) {
            // Example using a generic `files` table:
            $data = [
                'patient_id' => $patient_id,
                'filename' => $file['file_name'],
                'filepath' => './uploads/'.$patient_id.'/'. $file['file_name'],
                'filetype' => $file['file_type'],
                // Add other relevant fields as needed
            ];
            $this->db->insert('files', $data);
        }
    }

    public function add($pid)
    {

        $data = array();
        $data[ 'id'] = 0;
        $data[ 'pid'] = $pid;
        $data[ 'default_data_entrada'] = date('Y-m-d');
        $data[ 'default_department'] = '';
        $data[ 'default_service'] = '';
        $data[ 'default_remetente'] = '';
        $data[ 'default_autorizado_por'] = '';
        $data[ 'default_tipo_alta'] = '';
        $data[ 'default_diagnostico_alta'] = '';
        $data[ 'default_cid10_alta'] = '';
        $data[ 'default_data_alta'] = '';
        $data[ 'default_recebido_por'] = '';
        $data[ 'default_recebido_em'] = date('Y-m-d');
        $data[ 'default_estado_arquivo'] = '';
        $data[ 'default_active'] = '';
        $data[ 'default_remarks'] = '';

        //set departments & Services
        $data[ 'dropdown_department'] = $this-> get_dropdown_departments( 'return');
        if ($this->DEPARTMENT == 'OPD') {
            $data['dropdown_service'] = $this->get_dropdown_services(set_value( 'department', 2), 'return');
            $data['default_department'] = '2';
            $data['default_service'] = '5';
        } else {
            $data['default_department'] = '1';
            $data['dropdown_service'] = $this->get_dropdown_services(set_value( 'department', 1), 'return');
            $data['default_service'] = '1';
        }


        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'PID' => $pid,
                'DataEntrada' => $this->input->post( 'data_entrada'),
                'Departamento' => $this->input->post( 'department'),
                'Servico' => $this->input->post( 'service'),
                'Remetente' => $this->input->post( 'remetente'),
                'Autorizador' => $this->input->post( 'autorizado_por'),
                'TipoAlta' => $this->input->post( 'tipo_alta'),
                'DataAlta' => $this->input->post('data_alta'),
                'DiagnosticoAlta' => $this->input->post('diagnostico_alta'),
                'Cid10Alta' => $this->input->post('cid10_alta'),
                'RecebidoPor' => $this->input->post('recebido_por'),
                'RecebidoEm' => $this->input->post( 'recebido_em'),
                'EstadoArquivo' => $this->input->post( 'estado_arquivo'),
                'Remarks' => $this->input->post('remarks'),
                'Active' => $this->input->post('active'),
            );


            $inserted_id = $this->m_patient_arquivo_clinico->insert($data);
            $this->session->set_flashdata(
                'msg', 'Dados Salvos com Sucesso'
            );
            //   $this->redirect_if_no_continue('/preference/load/dador');
          //  $this->redirect_if_no_continue('/patient/view/'.$pid);
            $this->redirect_if_no_continue( '/arquivo_clinico/search/');
//            $this->redirect_if_no_continue('/blood_donation_result/add/' . $inserted_id);
        }
    }


    public function search()
        {

            if (!Modules::run('permission/check_permission', 'clinical_storage', 'print')) {
                die('You do not have permission');
            }

            $this->set_top_selected_menu('arquivo_clinico/search');
            $uid = $this->session->userdata('uid');


            $dropdown_services = $this->get_dropdown_all_services ('return');
          $option_service = ':All;';
          foreach ($dropdown_services as $service) {
              if (strlen($service) > 0) {
                  $option_service .= $service . ':' . $service . ';';
              }
          }

          $dropdown_departments = $this->get_dropdown_departments ('return');
          $option_department = ':All;';
          foreach ($dropdown_departments as $department) {
              if (strlen($department) > 0) {
                  $option_department = ':All;';
                  $option_department .= $department . ':' . $department . ';';
              }
          }

            $qry = "SELECT
            patient_arquivo_clinico.id,
            patient.PID,
            CONCAT(patient.Firstname,' ',patient.Name) AS 'Nome do Paciente',
            patient_arquivo_clinico.DataEntrada,
            CONCAT(hospital_departments.name) AS 'Departamento',
            CONCAT(hospital_services.name) AS 'Serviço',
         /*   patient_arquivo_clinico.Remarks,*/
            patient_arquivo_clinico.EstadoArquivo,
          /*  CONCAT(user.Name,' ',user.OtherName) AS 'Criado Por',*/

            patient_arquivo_clinico.CreateDate
            FROM patient_arquivo_clinico
            LEFT JOIN patient ON patient.PID = patient_arquivo_clinico.pid
            LEFT JOIN hospital_departments ON hospital_departments.department_id = patient_arquivo_clinico.Departamento
            LEFT JOIN hospital_services ON hospital_services.service_id = patient_arquivo_clinico.Servico
            LEFT JOIN user ON user.UID = patient_arquivo_clinico.CreateUser";

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
            $page->setColNames(array("#","NID","Nome do Paciente","Data Entrada","Departamento","Serviço","Estado Arquivo","Criado Em"));

            $page->setRowNum(25);
            $page->setColOption("id", array("search" => true, "hidden" => true, "height" => 100) );
            $page->setColOption("PID", array("search" => true, "hidden" => false, "height" => 100, "width" => 75) );
            $page->setColOption("DataEntrada", array("search" => true, "hidden" => false, "height" => 100, "width" => 75) );
            $page->setColOption("Nome do Paciente", array("search" => true, "hidden" => false, "height" => 100, "width" => 200) );
            $page->setColOption('EstadoArquivo', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':Todos;Arquivado:Arquivado;Requisitado:Requisitado;Desaparecido:Desaparecido;No Arquivo Morto:No Arquivo Morto;Sem Informação:Sem Informação;Outro:Outro'
            ), 'width' => '120'));
         //   $page->setColOption('Serviço', array('stype' => 'select','editoptions' => array('value' => $option_service), 'width' => '120'));
     //       $page->setColOption('Departamento', array('stype' => 'select','editoptions' => array('value' => $dropdown_departments), 'width' => '120'));
          /*  $page->setColOption("remarks", array("search" => true, "hidden" => false, "height" => 100, "width" => 100) );
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



    public function edit($arquivo_id)
    {
        if (!has_permission('clinical_storage', 'edit')) {
            $this->show_no_permission();
        }
        $arquivo_clinico = $this->m_patient_arquivo_clinico->get( $arquivo_id);
        if (empty( $arquivo_clinico))
            die('not found');



        $data['id'] = $arquivo_clinico->id;
        $data['pid'] = $arquivo_clinico->PID;
        $data['default_data_entrada'] = $arquivo_clinico->DataEntrada;
        $data['default_department'] = $arquivo_clinico->Departamento;
        $data['default_service'] = $arquivo_clinico->Servico; 
        $data['default_remetente'] = $arquivo_clinico->Remetente;
        $data['default_autorizado_por'] = $arquivo_clinico->Autorizador;
        $data['default_tipo_alta'] = $arquivo_clinico->TipoAlta;
	    $data['default_diagnostico_alta'] = $arquivo_clinico->DiagnosticoAlta;
        $data['default_cid10_alta'] = $arquivo_clinico->Cid10Alta;
        $data['default_data_alta'] = $arquivo_clinico->DataAlta;
        $data['default_recebido_por'] = $arquivo_clinico->RecebidoPor;
        $data['default_recebido_em'] = $arquivo_clinico->RecebidoEm;
        $data['default_estado_arquivo'] = $arquivo_clinico->EstadoArquivo;
        $data['default_active'] = $arquivo_clinico->Remarks;
        $data['default_remarks'] = $arquivo_clinico->Remarks;

        $data['dropdown_department'] = $this->get_dropdown_departments('return');
        $data['dropdown_service'] = $this->get_dropdown_services(set_value('department', $arquivo_clinico->Departamento), 'return');


        $this->form_validation->set_rules('data_entrada', 'Data de Entrada do Processo', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');

        if ($arquivo_clinico->Departamento == 'EMR') {
            $data['dropdown_service'] = $this->get_dropdown_services(1, 'return');
        } else {
            $data['dropdown_service'] = $this->get_dropdown_services(2, 'return');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data_update = array(
                'DataEntrada' => $this->input->post('data_entrada'),
                'Departamento' => $this->input->post('department'),
                'Servico' => $this->input->post('service'),
                'Remetente' => $this->input->post('remetente'),
                'Autorizador' => $this->input->post('autorizado_por'),
                'TipoAlta' => $this->input->post('tipo_alta'),
                'DataAlta' => $this->input->post('data_alta'),
                'DiagnosticoAlta' => $this->input->post('diagnostico_alta'),
                'Cid10Alta' => $this->input->post('cid10_alta'),
                'RecebidoPor' => $this->input->post('recebido_por'),
                'RecebidoEm' => $this->input->post('recebido_em'),
                'EstadoArquivo' => $this->input->post('estado_arquivo'),
                'Remarks' => $this->input->post('remarks'),
                'Active' => $this->input->post('active'),
            );
            $this->m_patient_arquivo_clinico->update($arquivo_id, $data_update);

            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue( 'arquivo_clinico');


        }
    }




    public function get_dropdown_departments($type = 'json')
    {
        $this->load->model( 'm_hospital_department');
        $result = $this->m_hospital_department->order_by('department_id')->dropdown('department_id', 'abrev');
        if ($type == 'json') {
            print(json_encode($result));
        }
        return $result;
    }

    public function get_dropdown_all_services($type = 'json')
    {
        $this->load->model( 'm_hospital_service');
        $result = $this->m_hospital_service->order_by('service_id')->dropdown('name', 'name');
        if ($type == 'json') {
            print(json_encode($result));
        }
        return $result;
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

   



    private function set_common_validation()
    {
        $this->form_validation->set_rules( 'data_entrada', 'Data de Entrada do Processo', 'trim|xss_clean|required');
        $this->form_validation->set_rules('PID', 'NID', 'trim|xss_clean|unique');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');
    }

}

