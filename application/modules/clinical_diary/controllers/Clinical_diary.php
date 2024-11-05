<?php

class Clinical_Diary extends FormController
{
    var $_department;
    function __construct()
    {
        parent::__construct();
        // $this->load_form_language();
        $this->load->model('m_medical_history');
        $this->load->model('m_patient_examination');
    }

    public function add($PID = null, $ref_id = null)
    {

        $data = array();
        $data['pid'] = $PID;
        $data['ref_id'] = $ref_id;
         $data['patient_history'] = $this->loadHistory($PID, $ref_id, "ADM");
         $data["exams"] = $this->loadExam($PID, $ref_id, "ADM");

        $this->qch_template->load_form_layout("form_clinical_diary",$data);
        
    }

    private function loadHistory($pid, $ref_id, $ref_type)
    {
        $user_role = $this->get_user_role();
        $history_data = $this->m_medical_history->get_patient_history_by_pid($pid, $ref_type, $user_role);
        if ($this->db->query($history_data)->num_rows() === 0) {
            return;
        }
        $this->load->model('mpager', 'history_page');
        $history_page = $this->history_page;
        $history_page->setSql($history_data);
        $history_page->setDivId("hist_cont"); 
        $history_page->setDivClass('');
        $history_page->setRowid('HID');
        $history_page->setCaption(lang("Clinic History"));
        $history_page->setShowHeaderRow(true);
        $history_page->setShowFilterRow(false);
        $history_page->setShowPager(false);
        $history_page->setColNames(array("", lang("Date"), lang("Complaint"), lang("HistoryOfComplaint"), lang("Clinical"),  lang("Category")));
        $history_page->setRowNum(25);
        $history_page->setColOption("HID", array("search" => false, "hidden" => true));
        $history_page->setColOption("dte", array("search" => false, "hidden" => false));
        $history_page->setColOption("Complaint", array("search" => false, "hidden" => false));
        $history_page->setColOption("HistoryOfComplaint", array("search" => false, "hidden" => false));
        $history_page->setColOption("Doctor", array("search" => false, "hidden" => false));
        $history_page->setColOption("user_role", array("hidden" => false));
        $history_page->gridComplete_JS = "function() {
            $('#hist_cont .jqgrow').mouseover(function(e) {
                 var rowId = $(this).attr('id');
                 $(this).css({'cursor':'pointer'});
             }).mouseout(function(e){
             }).click(function(e){
                 var rowId = $(this).attr('id');
                 $.ajax({
                     url: '" . site_url("patient_history/patient_view_history") . "/' + rowId,
                     type: 'GET',
                     success: function(data) {
                         $('#modalContent').html(data);
                         $('#myModal').modal('show');
                     }
                 });
             });
         }";
        $history_page->setOrientation_EL("L");
        return $history_page->render(false);
    }

    private function loadExam($pid, $ref_id, $ref_type)
    {
        $user_role = $this->get_user_role();
        $exam_data = $this->m_patient_examination->get_patient_exam_by_pid($pid, $ref_type, $user_role);
        if ($this->db->query($exam_data)->num_rows() === 0) {
            return;
        }
        $this->load->model('mpager', 'exam_page');
        $exams_page = $this->exam_page;
        $exams_page->setSql($exam_data);
        $exams_page->setDivId("exami_cont"); 
        $exams_page->setDivClass('');
        $exams_page->setRowid('PATEXAMID');
        $exams_page->setCaption(lang("Examinations"));
        $exams_page->setShowHeaderRow(true);
        $exams_page->setShowFilterRow(false);
        $exams_page->setShowPager(false);
        $exams_page->setColNames(array("", lang("Date"), lang("Sys_BP") . ' / ' . lang("Diast_BP"), lang("Weight"), lang("Height"), lang("Temperature"), lang("Category")));
        $exams_page->setRowNum(25);
        $exams_page->setColOption("PATEXAMID", array("search" => false, "hidden" => true));
        $exams_page->setColOption("dte", array("search" => false, "hidden" => false));
        $exams_page->setColOption("bp", array("search" => false, "hidden" => false));
        $exams_page->setColOption("weight", array("search" => false, "hidden" => false));
        $exams_page->setColOption("user_role", array("hidden" => false));
        
        $exams_page->gridComplete_JS = "function() {
            $('#exami_cont .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                $.ajax({
                    url: '" . site_url("patient_examination/patient_view_exam") . "/' + rowId,
                    type: 'GET',
                    success: function(data) {
                        $('#modalContent').html(data);
                        $('#myModal').modal('show');
                    }
                });
            });
        }";
        $exams_page->setOrientation_EL("L");
        return $exams_page->render(false);
    }

    private function get_user_role()
    {
        $user_group_name = $this->session->userdata('user_group_name');

        if (strpos(strtolower($user_group_name), 'nurse') !== false) {
            return 'Nurse';
        } else {
            return 'Doctor';
        }
    }
}