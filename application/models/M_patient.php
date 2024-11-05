<?php

/**
 * This model works with information in table "patients" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_patient extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'patient';
        $this->primary_key = 'PID';
        $this->belongs_to = array(
            'province' => array('model' => 'm_who_provinces', 'primary_key' => 'who_province_id'),
            'district' => array('model' => 'm_who_district', 'primary_key' => 'who_district_id'),
            'health_unit' => array('model' => 'm_who_health_unit', 'primary_key' => 'who_health_unit_id')
        );
    }

    public function generate_hin($id)
    {
        $result = "";
        for ($number_of_digit = 0; $number_of_digit < 9; $number_of_digit++) {
            if ($id > 0) {
                $last_digit = $id % 10;
                $id = $id / 10;
                $result = strval($last_digit) . $result;
            } else {
                $result = "0" . $result;
            }
        }
        return $result;
    }

    public function generate_present_hin($hin)
    {
        // $his is string
        $result = "";
        $len = strlen($hin) - 1;
        $count = 0;
        while ($len >= 0) {
            $count++;
            $result = strval($hin[$len]) . $result;
            if ($count == 3) {
                $count = 0;
                if ($len > 0) {
                    $result = "-" . $result;
                }
            }
            $len -= 1;
        }
        return $result;
    }

    public function get_patient($id)
    {
        $patient = $this->as_array()
            ->with('province')
            ->with('district')
            ->with('health_unit')
            ->get($id);
        $patient['HIN'] = $this->generate_hin($id);
        $patient['Present_HIN'] = $this->generate_present_hin($patient['HIN']);
//        var_dump($patient);
        return $patient;
    }

    public function get_patient_by_pid($pid)
    {
        $this->db->where('PID', $pid);
        $query = $this->db->get($this->_table);
        $result = $query->row();
        
        // Check if the result is empty and return null if it is
        return empty($result) ? null : $result;
    }


    public function get_total_patients() {
        // Query to count total patient
        $query = $this->db->count_all('patient');
              
    // Execute the query and return the result
        return $query;
    }

    public function get_patient_info($PID) {
        // Consulta combinada usando INNER JOIN
        $sql = "
            SELECT 
                p.*, 
                pphd.DrugID, 
                wd.name AS drug_name, 
                t.TreatmentID, 
                t.Treatment AS treatment_name, 
                roh.radiology_order_id, 
                ro.*, 
                r.name AS radiology_name, 
                icd.name AS diagnosis_name, 
                lo.LAB_ORDER_ID, 
                loi.LABID, 
                lt.Name AS lab_name, 
                wd2.name AS district_name, 
                pec.*, 
                per.HospitalizationReason AS HospitalizationReason,
                ea.CreateDate AS entry_time,
                ea.LastUpDate AS exit_time
            FROM patient p
            LEFT JOIN patient_prescription_have_drug pphd ON p.PID = pphd.PID
            LEFT JOIN who_drug wd ON pphd.DrugID = wd.wd_id
            LEFT JOIN treatment_order to_table ON p.PID = to_table.PID
            LEFT JOIN treatment t ON to_table.TreatmentID = t.TREATMENTID
            LEFT JOIN radiology_order_have_radiology roh ON p.PID = roh.PID
            LEFT JOIN radiology_order ro ON roh.radiology_order_id = ro.radiology_order_id
            LEFT JOIN radiology r ON ro.radiology_order_id = r.radiology_id
            LEFT JOIN patient_diagnosis pd ON p.PID = pd.PID
            LEFT JOIN icd10 icd ON pd.diagnosis_id = icd.ICDID
            LEFT JOIN lab_order lo ON p.PID = lo.PID
            LEFT JOIN lab_order_items loi ON lo.LAB_ORDER_ID = loi.LAB_ORDER_ID
            LEFT JOIN lab_tests lt ON loi.LABID = lt.LABID
            LEFT JOIN who_districts wd2 ON p.who_district_id = wd2.district_code
            LEFT JOIN patient_emr_contacts pec ON p.PID = pec.PID
            LEFT JOIN patient_active_list pal ON p.PID = pal.PID
            LEFT JOIN patient_emr_reasons per ON pal.HospitalizationReason = per.PEMRRID
            LEFT JOIN emergency_admission ea ON p.PID = ea.PID
            WHERE p.PID = $PID
            ORDER BY ea.CreateDate ASC;
            ";

    
    
    
    // Executa a consulta
    $query = $this->db->query($sql);
    return $query->result_array();
    }
    

}