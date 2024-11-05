<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 05-Nov-15
 * Time: 4:02 PM
 */
class m_patient_diagnosis extends MY_CRUD
{
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'patient_diagnosis';
        $this->primary_key = 'patient_diagnosis_id';
        $this->belongs_to = array(
            'diagnosis' => array('model' => 'm_icd10', 'primary_key' => 'diagnosis_id'),
            'created_by' => array('model' => 'm_user', 'primary_key' => 'CreateUser')
        );
    }

    public function get_created_by($p_diagnosis_id)
    {
        $this->db->select('CreateUser');
        $this->db->from('patient_diagnosis');
        $this->db->where('patient_diagnosis_id', $p_diagnosis_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->CreateUser;
        }
        return false;
    }

    public function get_diagnosis_report($start_date, $end_date)
    {
        $sql = "
            SELECT
                    Motivo.diagnosis_name AS Motivo,
                    SUM(CASE WHEN Motivo.Gender = 'M' AND Motivo.faixa_etaria = '0-14' THEN 1 ELSE 0 END) AS M_0_14,
                    SUM(CASE WHEN Motivo.Gender = 'M' AND Motivo.faixa_etaria = '15-24' THEN 1 ELSE 0 END) AS M_15_24,
                    SUM(CASE WHEN Motivo.Gender = 'M' AND Motivo.faixa_etaria = '25-49' THEN 1 ELSE 0 END) AS M_25_49,
                    SUM(CASE WHEN Motivo.Gender = 'M' AND Motivo.faixa_etaria = '50-59' THEN 1 ELSE 0 END) AS M_50_59,
                    SUM(CASE WHEN Motivo.Gender = 'M' AND Motivo.faixa_etaria = '60+' THEN 1 ELSE 0 END) AS M_60_plus,
                    SUM(CASE WHEN Motivo.Gender = 'F' AND Motivo.faixa_etaria = '0-14' THEN 1 ELSE 0 END) AS F_0_14,
                    SUM(CASE WHEN Motivo.Gender = 'F' AND Motivo.faixa_etaria = '15-24' THEN 1 ELSE 0 END) AS F_15_24,
                    SUM(CASE WHEN Motivo.Gender = 'F' AND Motivo.faixa_etaria = '25-49' THEN 1 ELSE 0 END) AS F_25_49,
                    SUM(CASE WHEN Motivo.Gender = 'F' AND Motivo.faixa_etaria = '50-59' THEN 1 ELSE 0 END) AS F_50_59,
                    SUM(CASE WHEN Motivo.Gender = 'F' AND Motivo.faixa_etaria = '60+' THEN 1 ELSE 0 END) AS F_60_plus,
                    SUM(CASE WHEN Motivo.Gender = 'M' THEN 1 ELSE 0 END) AS Total_M,
                    SUM(CASE WHEN Motivo.Gender = 'F' THEN 1 ELSE 0 END) AS Total_F,
                    SUM(CASE WHEN Motivo.Gender IN ('M', 'F') THEN 1 ELSE 0 END) AS Total_Geral
                FROM (
                    SELECT
                        CASE 
                            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 0 AND 14 THEN '0-14'
                            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 15 AND 24 THEN '15-24'
                            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 25 AND 49 THEN '25-49'
                            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 50 AND 59 THEN '50-59'
                            ELSE '60+'
                        END AS faixa_etaria,
                        patient.Gender, 
                        icd10.Name AS diagnosis_name,
                        pd.RefType
                    FROM 
                        patient
                    JOIN 
                        patient_diagnosis pd ON patient.PID = pd.PID
                    JOIN 
                        icd10 ON pd.diagnosis_id = icd10.ICDID
                    WHERE 
                        pd.CreateDate BETWEEN ? AND ?  -- Aqui você deve substituir pelas datas que deseja
                    GROUP BY 
                        pd.RefType,
                        faixa_etaria, 
                        patient.Gender, 
                        icd10.Name
                ) AS Motivo
                GROUP BY Motivo.diagnosis_name
                ORDER BY Motivo.diagnosis_name;

        ";

        $query = $this->db->query($sql, [
            $start_date,
            $end_date
        ]);

        return $query->result();
    }



    public function get_surveillance_report($start_date, $end_date)
    {
        $sql = "
    SELECT 
    Address_Street,
    Diagnostico,
    
    SUM(CASE WHEN Gender = 'M' AND AgeGroup = '0-11 meses' THEN 1 ELSE 0 END) AS M_0_11_meses,
    SUM(CASE WHEN Gender = 'M' AND AgeGroup = '1-4 anos' THEN 1 ELSE 0 END) AS M_1_4_anos,
    SUM(CASE WHEN Gender = 'M' AND AgeGroup = '5-14 anos' THEN 1 ELSE 0 END) AS M_5_14_anos,
    SUM(CASE WHEN Gender = 'M' AND AgeGroup = '15-24 anos' THEN 1 ELSE 0 END) AS M_15_24_anos,
    SUM(CASE WHEN Gender = 'M' AND AgeGroup = '25-49 anos' THEN 1 ELSE 0 END) AS M_25_49_anos,
    SUM(CASE WHEN Gender = 'M' AND AgeGroup = '50-59 anos' THEN 1 ELSE 0 END) AS M_50_59_anos,
    SUM(CASE WHEN Gender = 'M' AND AgeGroup = '60+ anos' THEN 1 ELSE 0 END) AS M_60_plus,
    
    SUM(CASE WHEN Gender = 'F' AND AgeGroup = '0-11 meses' THEN 1 ELSE 0 END) AS F_0_11_meses,
    SUM(CASE WHEN Gender = 'F' AND AgeGroup = '1-4 anos' THEN 1 ELSE 0 END) AS F_1_4_anos,
    SUM(CASE WHEN Gender = 'F' AND AgeGroup = '5-14 anos' THEN 1 ELSE 0 END) AS F_5_14_anos,
    SUM(CASE WHEN Gender = 'F' AND AgeGroup = '15-24 anos' THEN 1 ELSE 0 END) AS F_15_24_anos,
    SUM(CASE WHEN Gender = 'F' AND AgeGroup = '25-49 anos' THEN 1 ELSE 0 END) AS F_25_49_anos,
    SUM(CASE WHEN Gender = 'F' AND AgeGroup = '50-59 anos' THEN 1 ELSE 0 END) AS F_50_59_anos,
    SUM(CASE WHEN Gender = 'F' AND AgeGroup = '60+ anos' THEN 1 ELSE 0 END) AS F_60_plus,
    
    SUM(CASE WHEN Gender = 'M' THEN 1 ELSE 0 END) AS Total_M,
    SUM(CASE WHEN Gender = 'F' THEN 1 ELSE 0 END) AS Total_F,
    SUM(CASE WHEN Gender IN ('M', 'F') THEN 1 ELSE 0 END) AS Total_Geral
FROM (
    SELECT 
        patient.Address_Street, 
        patient.Gender,
        CASE 
            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) < 1 THEN '0-11 meses'
            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 1 AND 4 THEN '1-4 anos'
            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 5 AND 14 THEN '5-14 anos'
            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 15 AND 24 THEN '15-24 anos'
            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 25 AND 49 THEN '25-49 anos'
            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 50 AND 59 THEN '50-59 anos'
            ELSE '60+ anos'
        END AS AgeGroup,
        icd10.Name AS Diagnostico
    FROM 
        patient
    JOIN 
        patient_diagnosis pd ON patient.PID = pd.PID
    JOIN 
        icd10 ON icd10.ICDID = pd.patient_diagnosis_id
    WHERE 
        pd.CreateDate BETWEEN '2024-01-01' AND '2024-12-31'  -- Intervalo de datas predefinido
) AS subquery
GROUP BY 
    Address_Street, Diagnostico
ORDER BY 
    Diagnostico, Address_Street

    ";

        $query = $this->db->query($sql, [
            $start_date,
            $end_date
        ]);

        return $query->result();
    }

    public function get_last_diagnosis_id_by_refid($refid)
    {
        $this->db->select('*');
        $this->db->from('patient_diagnosis');
        $this->db->where('RefID', $refid);
        $this->db->order_by('new_diagnisi_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get()->row();
        return $query;
    }

    public function get_diagnosis_name($id)
    {
        $this->db->select('Name');
        $this->db->from('diagnosis_type');
        $this->db->where('id', $id);
        $query = $this->db->get()->result_array();
        return $query;
    }
}
