<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HospitalLoader {

    public function load_hospital_name() {
 
        $CI =& get_instance();

        $CI->load->model('m_hospital_names');

     
        $hospital_name = $CI->m_hospital_names->get_hospital_name();

      // Set the hospital name as an affordable global variable anywhere
        $CI->config->set_item('hospital_name', $hospital_name);
    }
}
