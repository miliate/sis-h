<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 23-Oct-15
 * Time: 9:53 PM
 */
class m_medical_history extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'medical_history';
        $this->primary_key = 'HID';
    }

    // public function get_CreateUser($pid)
    // {
    //     $this->db->select('CreateUser');
    //     $this->db->from('medical_history');
    //     $this->db->where('PID', $pid);
    //     $query = $this->db->get();
    //     if ($query->num_rows() > 0) {
    //         return $query->row()->CreateUser;
    //     }
    //     return false;
    // }
    // public function get_created_by($history_id)
    // {
    //     $this->db->select('UID');
    //     $this->db->from('user');
    //     $this->db->where('UID', $history_id);
    //     $query = $this->db->get();
    //     if ($query->num_rows() > 0) {
    //         return $query->row()->UID;
    //     }
    //     return false;
    // }

    public function get_created_by($history_id)
    {
        $this->db->select('CreateUser');
        $this->db->from('medical_history');
        $this->db->where('HID', $history_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->CreateUser;
        }
        return false;
    }

    public function get_doctor_observation_report($start_date, $end_date)
    {
        $sql = "
          SELECT
        Motivo,
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
            user.Name, 
            patient.Gender,
                    WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 25 AND 49 THEN '25-49'
                    WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 50 AND 59 THEN '50-59'
                    ELSE '60+'
            CASE
                WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 0 AND 14 THEN '0-14'
                    WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 15 AND 24 THEN '15-24'
            END AS Faixa_Etaria,
            icd10.Name AS Motivo,
            patient_diagnosis.RefType AS Tipo_Referencia,
            medical_history.CreateDate
        FROM 
            user
        JOIN 
            medical_history ON user.UID = medical_history.CreateUser
        JOIN 
            patient ON patient.PID = medical_history.PID
        JOIN 
            patient_diagnosis ON patient.PID = patient_diagnosis.PID
        JOIN 
            icd10 ON patient_diagnosis.patient_diagnosis_id = icd10.ICDID
        WHERE 
            medical_history.CreateDate BETWEEN ? AND ?  -- Ajuste as datas conforme necessário
    ) AS T
    GROUP BY T.Motivo
    ORDER BY T.Motivo;
      
        ";

        $query = $this->db->query($sql, [
            $start_date,
            $end_date
        ]);

        return $query->result();
    }

    public function get_dgraphyc_medical_report($start_date, $end_date, $period)
    {
        $sql = "
        SELECT 
    Diagnostico, 
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
        user.Name, 
        patient.Gender, 
        CASE 
            WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 0 AND 14 THEN '0-14' 
            WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 15 AND 24 THEN '15-24' 
            WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 25 AND 49 THEN '25-49' 
            WHEN YEAR(CURDATE()) - YEAR(patient.DateOfBirth) BETWEEN 50 AND 59 THEN '50-59' 
            ELSE '60+' 
        END AS Faixa_Etaria, 
        icd10.Name AS Diagnostico, 
        patient_diagnosis.RefType AS Tipo_Referencia, 
        medical_history.CreateDate 
    FROM 
        user 
        JOIN medical_history ON user.UID = medical_history.CreateUser 
        JOIN patient ON patient.PID = medical_history.PID 
        JOIN patient_diagnosis ON patient.PID = patient_diagnosis.PID 
        JOIN icd10 ON patient_diagnosis.patient_diagnosis_id = icd10.ICDID 
    WHERE 
        medical_history.CreateDate BETWEEN ? AND ?  -- Substitua as datas conforme necessário
) AS T
GROUP BY T.Diagnostico 
ORDER BY T.Diagnostico
      
        ";

        $query = $this->db->query($sql, [
            $start_date,
            $end_date
        ]);

        return $query->result();
    }
  
    public function get_patient_history($pid)
    {
        $sql = "
            SELECT HID, 
                   CreateDate, 
                   COALESCE(NULLIF(Complaint, ''), 
                             (SELECT Complaint 
                              FROM emergency_admission 
                              WHERE PID = ? 
                              AND DATE(CreateDate) = DATE(medical_history.CreateDate) 
                              LIMIT 1)) AS Complaint,
                   HistoryOfComplaint,
                   DietHistory,
                   CASE 
                       WHEN Complaint IS NULL OR Complaint = '' THEN 
                           (SELECT CONCAT(u.Name, ' ', u.OtherName) 
                            FROM user u 
                            JOIN emergency_admission ea ON ea.ObservationDoctorUID = u.UID 
                            WHERE ea.PID = ? 
                            AND DATE(ea.CreateDate) = DATE(medical_history.CreateDate) 
                            LIMIT 1)
                       ELSE Doctor 
                   END AS Doctor
            FROM medical_history 
            WHERE PID = ?
        ";

        // Execute the query with the provided patient ID
        $query = $this->db->query($sql, array($pid, $pid, $pid));
        return $query->result_array(); // Returns an array of results
    }

    public function get_patient_history_by_pid($pid, $ref_type, $user_role)
    {
        $pid = $this->db->escape($pid);
        $ref_type = $this->db->escape($ref_type);
        $user_role = $this->db->escape($user_role);

        return "
            SELECT HID, 
                SUBSTRING(CreateDate, 1, 10) AS dte, 
                Complaint, 
                HistoryOfComplaint, 
                Doctor, 
                user_role
            FROM medical_history
            WHERE PID = $pid 
            AND Active = 1 
            AND user_role = $user_role
        ";
    }
}
