<?php
/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12/1/15
 * Time: 12:50 AM
 */
class m_discharge_order extends MY_CRUD {
    public function __construct() {
        parent::__construct();
        $this->_table = 'discharge_order';
        $this->primary_key = 'DischargeID';
    }

    public function get_discharge_report($start_date, $end_date) {
        $sql = "
                SELECT
                    subquery.Motivo,
                    SUM(CASE WHEN subquery.Gender = 'M' AND subquery.faixa_etaria = '0-14' THEN 1 ELSE 0 END) AS M_0_14,
                    SUM(CASE WHEN subquery.Gender = 'M' AND subquery.faixa_etaria = '15-24' THEN 1 ELSE 0 END) AS M_15_24,
                    SUM(CASE WHEN subquery.Gender = 'M' AND subquery.faixa_etaria = '25-49' THEN 1 ELSE 0 END) AS M_25_49,
                    SUM(CASE WHEN subquery.Gender = 'M' AND subquery.faixa_etaria = '50-59' THEN 1 ELSE 0 END) AS M_50_59,
                    SUM(CASE WHEN subquery.Gender = 'M' AND subquery.faixa_etaria = '60+' THEN 1 ELSE 0 END) AS M_60_plus,
                    SUM(CASE WHEN subquery.Gender = 'F' AND subquery.faixa_etaria = '0-14' THEN 1 ELSE 0 END) AS F_0_14,
                    SUM(CASE WHEN subquery.Gender = 'F' AND subquery.faixa_etaria = '15-24' THEN 1 ELSE 0 END) AS F_15_24,
                    SUM(CASE WHEN subquery.Gender = 'F' AND subquery.faixa_etaria = '25-49' THEN 1 ELSE 0 END) AS F_25_49,
                    SUM(CASE WHEN subquery.Gender = 'F' AND subquery.faixa_etaria = '50-59' THEN 1 ELSE 0 END) AS F_50_59,
                    SUM(CASE WHEN subquery.Gender = 'F' AND subquery.faixa_etaria = '60+' THEN 1 ELSE 0 END) AS F_60_plus,
                    -- Total por sexo
                    SUM(CASE WHEN subquery.Gender = 'M' THEN 1 ELSE 0 END) AS Total_M,
                    SUM(CASE WHEN subquery.Gender = 'F' THEN 1 ELSE 0 END) AS Total_F,
                    -- Total geral (ambos os sexos)
                    SUM(CASE WHEN subquery.Gender IN ('M', 'F') THEN 1 ELSE 0 END) AS Total_Geral
                FROM (
                    SELECT 
                        discharge_order.OutCome AS Motivo,
                        patient.Gender,
                        CASE
                            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 0 AND 14 THEN '0-14'
                            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 15 AND 24 THEN '15-24'
                            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 25 AND 49 THEN '25-49'
                            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) BETWEEN 50 AND 59 THEN '50-59'
                            WHEN TIMESTAMPDIFF(YEAR, patient.DateOfBirth, CURDATE()) >= 60 THEN '60+'
                            ELSE 'Unknown'
                        END AS faixa_etaria
                    FROM 
                        patient
                    JOIN 
                        discharge_order ON patient.PID = discharge_order.PID 
                    WHERE 
                        discharge_order.CreateDate BETWEEN ? AND ?
                ) AS subquery
                GROUP BY 
                    subquery.Motivo
                ORDER BY 
                    subquery.Motivo;



      
        ";
        $query = $this->db->query($sql, [
            $start_date,
            $end_date
        ]);
    
        return $query->result();
    }

}
