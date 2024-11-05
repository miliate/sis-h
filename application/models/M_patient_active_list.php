<?php
/**
 * This model works with information in table "patient_active_list" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_patient_active_list extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'patient_active_list';
        $this->primary_key = 'ACTIVE_ID';
    }
    public function get_last_visit_id_by_pid($pid) {
        $this->db->select('VisitID');
        $this->db->from('patient_active_list');
        $this->db->where('PID', $pid);
        $this->db->order_by('VisitID', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->VisitID;
        } else {
            return null;
        }
    }

    public function get_last_active_id_by_pid($pid) {
        $this->db->select('ACTIVE_ID');
        $this->db->from('patient_active_list');
        $this->db->where('PID', $pid);
        $this->db->order_by('ACTIVE_ID', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        $result = $query->num_rows() > 0 ? $query->row()->ACTIVE_ID : null;
        return $result;
    }
   

    public function get_admission_report_by_age_gender_and_reason($start_date, $end_date) {
        $sql = "
            SELECT
    HospitalizationReason AS Motivo,
    SUM(CASE WHEN Gender = 'M' AND Faixa_Etaria = '0-14' THEN 1 ELSE 0 END) AS M_0_14,
    SUM(CASE WHEN Gender = 'M' AND Faixa_Etaria = '15-24' THEN 1 ELSE 0 END) AS M_15_24,
    SUM(CASE WHEN Gender = 'M' AND Faixa_Etaria = '25-49' THEN 1 ELSE 0 END) AS M_25_49,
    SUM(CASE WHEN Gender = 'M' AND Faixa_Etaria = '50-59' THEN 1 ELSE 0 END) AS M_50_59,
    SUM(CASE WHEN Gender = 'M' AND Faixa_Etaria = '60+' THEN 1 ELSE 0 END) AS M_60_plus,
    SUM(CASE WHEN Gender = 'F' AND Faixa_Etaria = '0-14' THEN 1 ELSE 0 END) AS F_0_14,
    SUM(CASE WHEN Gender = 'F' AND Faixa_Etaria = '15-24' THEN 1 ELSE 0 END) AS F_15_24,
    SUM(CASE WHEN Gender = 'F' AND Faixa_Etaria = '25-49' THEN 1 ELSE 0 END) AS F_25_49,
    SUM(CASE WHEN Gender = 'F' AND Faixa_Etaria = '50-59' THEN 1 ELSE 0 END) AS F_50_59,
    SUM(CASE WHEN Gender = 'F' AND Faixa_Etaria = '60+' THEN 1 ELSE 0 END) AS F_60_plus,
    SUM(CASE WHEN Gender = 'M' THEN 1 ELSE 0 END) AS Total_M,
    SUM(CASE WHEN Gender = 'F' THEN 1 ELSE 0 END) AS Total_F,
    SUM(CASE WHEN Gender IN ('M', 'F') THEN 1 ELSE 0 END) AS Total_Geral
    
    
FROM (
            SELECT
                patient.Gender,
                CASE
                    WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 0 AND 14 THEN '0-14'
                    WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 15 AND 24 THEN '15-24'
                    WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 25 AND 49 THEN '25-49'
                    WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 50 AND 59 THEN '50-59'
                    ELSE '60+'
                END AS Faixa_Etaria,
                patient_active_list.PID,
                patient_emr_reasons.HospitalizationReason,
                patient_active_list.CreateDate
            FROM patient
            JOIN patient_active_list ON patient.PID = patient_active_list.PID
            JOIN patient_emr_reasons ON patient_emr_reasons.PEMRRID = patient_active_list.HospitalizationReason
            WHERE patient_active_list.CreateDate BETWEEN ? AND ?
        ) AS T
        GROUP BY HospitalizationReason
        ORDER BY HospitalizationReason;

        ";
        $query = $this->db->query($sql, [
            $start_date,
            $end_date
        ]);
    
        return $query->result();
    }

}

