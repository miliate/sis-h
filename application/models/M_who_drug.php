<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 30-Oct-15
 * Time: 3:10 PM
 */
class m_who_drug extends MY_CRUD
{
    function __construct()
    {
        parent::__construct();
        $this->_table = 'who_drug';
        $this->primary_key = 'wd_id';
        $this->load->model("m_who_drug_count");
    }

    public function getDrugsCountOutsGroupByType($startDate, $endDate)
    {

        $sql = "SELECT 
        drug.fnm AS fnm,
        drug.name AS name,
        drug.pharmaceutical_form AS form,
        drug.dosage,
        COALESCE(SUM(CASE WHEN (pphd.CreateDate >= ? AND pphd.CreateDate <=  ?) THEN pphd.Quantity ELSE 0 END), 0) AS internals,
        COALESCE(SUM(CASE WHEN (pep.CreateDate >= ? AND pep.CreateDate <= ?) THEN pep.Quantity ELSE 0 END), 0) AS externals,
        COALESCE(SUM(CASE WHEN (wdc.Type = 'Negative Adjustment' AND wdc.CreateDate >= ? AND wdc.CreateDate <= ?) THEN wdc.Quantity ELSE 0 END), 0) AS negatives,
        COALESCE(SUM(CASE WHEN (wdc.Type = 'Waste' AND wdc.CreateDate >= ? AND wdc.CreateDate <= ?) THEN wdc.Quantity ELSE 0 END), 0) AS wastes 
    FROM who_drug drug
    LEFT JOIN patient_prescription_have_drug pphd ON pphd.DrugID = drug.wd_id
    LEFT JOIN patient_external_prescription_have_drug pep ON pep.DrugID = drug.wd_id
    LEFT JOIN who_drug_count wdc ON wdc.wd_id = drug.wd_id
    GROUP BY drug.wd_id, drug.fnm, drug.name, drug.pharmaceutical_form
    ORDER BY drug.name    ";
        $query =  $this->db->query($sql, array($startDate, $endDate, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
        $result = $query->result();
        return $result;
    }

    public function getDrugEntryAndPositiveAdjustmentGroupedByDrug($startDate, $endDate)
    {

        $sql = "SELECT 
        drug.fnm AS fnm,
        drug.name AS name,
        drug.pharmaceutical_form AS form,
        drug.dosage,
        COALESCE(SUM(CASE WHEN (wdc.Type = 'Entries' AND wdc.CreateDate >= ? AND wdc.CreateDate <=  ?) THEN wdc.Quantity ELSE 0 END), 0) AS entries,
        COALESCE(SUM(CASE WHEN (wdc.Type = 'Positive Adjustment' AND wdc.CreateDate >= ? AND wdc.CreateDate <= ?) THEN wdc.Quantity ELSE 0 END), 0) AS adjustments
    FROM who_drug drug
    LEFT JOIN who_drug_count wdc ON wdc.wd_id = drug.wd_id
    GROUP BY drug.wd_id, drug.fnm, drug.name, drug.pharmaceutical_form
    ORDER BY drug.name    ";
        $query =  $this->db->query($sql, array($startDate, $endDate, $startDate, $endDate));
        $result = $query->result();
        return $result;
    }

    function get_drug_row($wd_id)
    {
        $this->db->where('wd_id', $wd_id);
        $query = $this->db->get($this->_table);
        $result = $query->row();
        if ($result) {
            $data = array(
                'fnm' => $result->fnm,
                'name' => $result->name,
                'dosage' => $result->dosage,
                'pharmaceutical_form' => $result->pharmaceutical_form,
                'stock' => $result->count
            );
            echo json_encode($data);
        } else {
            echo json_encode(array('fnm' => '', 'name' => '', 'dosage' => '', 'pharmaceutical_form' => '', 'stock' => ''));
        }
    }




    public function get_drug_name_and_fnm_by_wd_id($wd_id)
    {

        $this->db->where('wd_id', $wd_id);
        $query = $this->db->get($this->_table);

        return $query->row(); // Return the row directly
    }

    public function get_drug_by_wd_id($wd_id)
    {
        $this->db->where('wd_id', $wd_id);
        $query = $this->db->get($this->_table);
        $result = $query->row();
        
        // Check if the result is empty and return null if it is
        return empty($result) ? null : $result;
    }
    

    public function get_drug_report($startDate, $endDate, $wd_id)
    {
        $sql = "
            SELECT
                wdc.wd_id,
                wdc.CreateDate AS DataMovimento,
                CASE
                    WHEN wdc.Type = 'Waste' THEN 'Desperdício'
                    WHEN wdc.Type = 'Consumption' THEN 'Consumo'
                    WHEN wdc.Type = 'Negative Adjustment' THEN wdc.destination
                    WHEN wdc.Type = 'Positive Adjustment' THEN wdc.ComeFrom
                    WHEN wdc.Type = 'Entries' THEN wdc.ComeFrom
                    ELSE 'Outros'
                END AS OrigemDestinoMovimento,
                wdc.DocNo AS NumeroDocumento,
                wd.name AS NomeMedicamento,
                wd.pharmaceutical_form AS FormaFarmaceutica,
                wd.Dosage AS Dosagem,
                CASE WHEN wdc.Type = 'Negative Adjustment' THEN wdc.Quantity ELSE 0 END AS AjustesNegativos,
                CASE WHEN wdc.Type = 'Positive Adjustment' THEN wdc.Quantity ELSE 0 END AS AjustesPositivos,
                CASE WHEN wdc.Type = 'Waste' THEN wdc.Quantity ELSE 0 END AS Desperdicios,
                CASE WHEN wdc.Type = 'Consumption' THEN wdc.Quantity ELSE 0 END AS Consumo,
                CASE WHEN wdc.Type = 'Entries' THEN wdc.Quantity ELSE 0 END AS Entradas,
                0 AS Dispensas,
                CASE WHEN wdc.Type = 'Entries' THEN wdc.QuantityCreateDate ELSE wdc.ExistingStock END AS ExistingStock,
                wdc.Signature AS Rubrica,
                0 AS Pedidos
            FROM
                who_drug_count wdc
            LEFT JOIN
                who_drug wd ON wdc.wd_id = wd.wd_id
            WHERE
                wdc.CreateDate BETWEEN ? AND ?
                AND wdc.wd_id = ?
            
            UNION ALL
            
            SELECT
                wd.wd_id,
                CONCAT('De ', ?, ' ate ', ?) AS DataMovimento,
                'Dispensas' AS OrigemDestinoMovimento,
                NULL AS NumeroDocumento,
                wd.name AS NomeMedicamento,
                wd.pharmaceutical_form AS FormaFarmaceutica,
                wd.Dosage AS Dosagem,
                0 AS AjustesNegativos,
                0 AS AjustesPositivos,
                0 AS Desperdicios,
                0 AS Consumo,
                0 AS Entradas,
                (COALESCE((SELECT SUM(pphd.Quantity) 
                           FROM patient_prescription_have_drug pphd 
                           WHERE pphd.CreateDate BETWEEN ? AND ?
                             AND pphd.DrugID = wd.wd_id), 0)
                + 
                COALESCE((SELECT SUM(pephd.Quantity) 
                          FROM patient_external_prescription_have_drug pephd 
                          WHERE pephd.CreateDate BETWEEN ? AND ?
                            AND pephd.DrugID = wd.wd_id), 0)) AS Dispensas,
                wdc.ExistingStock,
                ' ' AS Rubrica,
                0 AS Pedidos
            FROM
                who_drug wd
            LEFT JOIN
                who_drug_count wdc ON wd.wd_id = wdc.wd_id
            WHERE
                wd.wd_id = ?
            
            UNION ALL
            
            SELECT
                wd.wd_id,
                CONCAT('De ', ?, ' ate ', ?) AS DataMovimento,
                'Pedidos' AS OrigemDestinoMovimento,
                NULL AS NumeroDocumento,
                wd.name AS NomeMedicamento,
                wd.pharmaceutical_form AS FormaFarmaceutica,
                wd.Dosage AS Dosagem,
                0 AS AjustesNegativos,
                0 AS AjustesPositivos,
                0 AS Desperdicios,
                0 AS Consumo,
                0 AS Entradas,
                0 AS Dispensas,
                wdc.ExistingStock,
                ' ' AS Rubrica,
                SUM(ri.requested_quantity) AS Pedidos
            FROM
                request_item ri
            INNER JOIN
                who_drug wd ON ri.who_drugs_id = wd.wd_id
            LEFT JOIN
                who_drug_count wdc ON wd.wd_id = wdc.wd_id
            WHERE
                ri.CreateDate BETWEEN ? AND ?
                AND wd.wd_id = ?
            GROUP BY
                wd.wd_id, wd.name, wd.pharmaceutical_form, wd.Dosage, wdc.ExistingStock;
        ";
    
        $query = $this->db->query($sql, array(
            $startDate, $endDate, $wd_id,
            $startDate, $endDate,
            $startDate, $endDate, $startDate, $endDate, $wd_id,
            $startDate, $endDate, $wd_id,
            $startDate, $endDate
        ));
        return $query->result_array();
    }
    
    
    public function update_count($drug_id, $quantity, $movimentType)
    {

        $operator = $this->m_who_drug_count->movimentOperator[$movimentType];
        $this->db->set('count', 'count  ' . $operator  . (int)  $quantity, FALSE);
        $this->db->where('wd_id', $drug_id);
        $this->db->update('who_drug');
    }
}
