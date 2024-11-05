<?php

/**
 * Created by PhpStorm.
 * User: manhdx
 * Date: 11/20/15
 * Time: 10:29 AM
 */
class Medical_certificates extends FormController
{
    var $_department;

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_medical_certificate');
        $this->load->model('m_patient');
        $this->load->model('m_doctor');      
        $this->load->model('m_department');
        $this->load->model('m_patient_active_list');
        $this->_department = $this->session->userdata('department');
        $this->load_form_language();
    }

      function search()
    {
       
        $department="EMR";
        $data['department'] = $department;
        $this->render_search($data);




    }

}