<?php

/**
 * This model works with information in table "user_group" in database.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */
class m_who_drug_count extends MY_CRUD
{

    public $movimentOperator = [
        'Entries' => '+',
        'Positive Adjustment' => '+',
        'Negative Adjustment' => '-',
        'Waste' => '-',
        'Consumption' => '-',
        'Dispense' => '-'
    ];
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'who_drug_count';
        $this->primary_key = 'who_drug_count_id';
        parent::__construct();
        $this->load->model("m_who_drug");
    }



    /**
     * Get drug count by wd_id.
     *
     * @param int $wd_id
     * @return array
     */
    public function get_existing_stock_sum_by_wd_id($wd_id)
    {
        $this->db->select_sum('ExistingStock');
        $this->db->where('batch_deadline >', date('Y-m-d'));
        $this->db->where_in('Type', ['Entries', 'Positive Adjustment']);
        $this->db->where('wd_id',$wd_id);
        $this->db->where('ExistingStock >', 0);
        $query = $this->db->get($this->_table);
        return $query->row()->ExistingStock;
    }

    /**
     * Get the sum of ExistingStock by batch.
     *
     * @param string $batch
     * @return int
     */
    public function get_existing_stock_sum_by_batch($batch)
    {
        $this->db->select_sum('ExistingStock');
        $this->db->where('batch', $batch);
        $this->db->group_by('batch');
        $this->db->order_by('CreateDate', 'DESC'); // Replace CreateDate with your actual datetime or timestamp column
        $this->db->limit(1);
        $query = $this->db->get($this->_table);
        $result = $query->row();
        return isset($result->ExistingStock) ? $result->ExistingStock : 0;
    }

    /**
     * Get the sum of ExistingStock by batch.
     *
     * @param string $batch
     * @return int
     */
    public function get_existing_stock_by_batch($batch, $drug_id)
    {
        $this->db->select('ExistingStock');
        $this->db->where('batch', $batch);
        $this->db->where('wd_id', $drug_id);
        $this->db->order_by('CreateDate', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($this->_table);
        $result = $query->row();
        return isset($result->ExistingStock) ? $result->ExistingStock : 0;
    }

    public function get_valid_batches_by_wd_id($wd_id)
    {
        $this->db->select('batch, ExistingStock, batch_deadline, CreateDate');
        $this->db->where('wd_id', $wd_id);
        $this->db->order_by('CreateDate', 'ASC'); // Order by CreateDate to ensure the first inserted is checked first
        $query = $this->db->get($this->_table);

        $dropdown_options = array();
        $current_date = date('Y-m-d');
        $added_batches = array();

        // Iterate through the query results to validate each batch
        foreach ($query->result() as $row) {
            // Check if the batch is already added
            if (!array_key_exists($row->batch, $added_batches)) {
                // Validate expiration date and existing stock
                if (strtotime($row->batch_deadline) > strtotime($current_date) && $row->ExistingStock > 0) {
                    // Add the valid batch to dropdown options and mark it as added
                    $dropdown_options[$row->batch] = $row->batch;
                    $added_batches[$row->batch] = true; // Use array key for quick lookup
                }
            }
        }

        return $dropdown_options;
    }




    /**
     * Get a list of existing batches by wd_id.
     *
     * @param int $wd_id
     * @return array
     */
    public function get_existing_batches_by_wd_id($wd_id)
    {
        $this->db->select('batch, ExistingStock, batch_deadline');
        $this->db->where('wd_id', $wd_id);
        $this->db->where_in('Type', ['Entries', 'Positive Adjustment']);
        $query = $this->db->get($this->_table);

        $dropdown_options = array('' => ''); // Initialize with an empty option
        $current_date = date('Y-m-d');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // Validate expiration date and existing stock
                if (strtotime($row->batch_deadline) > strtotime($current_date) && $row->ExistingStock > 0) {
                    $dropdown_options[$row->batch] = $row->batch;
                }
            }
        }
        return $dropdown_options;
    }


    public function update_or_insert_row($wd_id, $data)
    {
        $type = $data['Type'];

        $data['QuantityCreateDate'] =  $this->get_existing_stock_sum_by_wd_id($wd_id);
        $this->db->insert('who_drug_count', $data);
        $this->m_who_drug->update_count($wd_id, $data['Quantity'], $type);

        if ($type == 'Entries' || $type == 'Positive Adjustment') {
            return;
        }
        $this->update_existing_stock_by_batch($wd_id, $data['batch'], $data['Quantity']);
    }


    public function get_existing_stock_by_batch_and_wd_id($batch, $wd_id)
    {
        $this->db->where('batch', $batch);
        $this->db->where('wd_id', $wd_id);
        $query = $this->db->get($this->_table);

        return $query->row(); // Return the row directly
    }


    public function get_batch_deadline_by_batch($batch)
    {
        $this->db->select('batch_deadline');
        $this->db->where('batch', $batch);
        $query = $this->db->get($this->_table);
        $result = $query->row();

        return $result ? $result->batch_deadline : null;
    }

    public function get_batch_deadline($wd_id)
    {
        $this->db->where('wd_id', $wd_id);
        $query = $this->db->get($this->_table);

        $combined = [];
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $combined[] = [
                    'batch' =>  $row->batch,
                    'deadline' =>  $row->batch_deadline
                ];
            }
        }
        return $combined;
    }

    public function dispense_drug($wd_id, $batch, $quantity)
    {


        $this->m_who_drug->update_count($wd_id, $quantity, 'Dispense');
        $this->update_existing_stock_by_batch($wd_id, $batch, $quantity);
    }

    /**
     * Update existing stock by batch.
     *
     * @param string $batch
     * @param int $newStock
     * @return bool
     */
    public function update_existing_stock_by_batch($wd_id, $batch, $quantity)
    {

        $this->db->trans_start();

        $query = $this->db->where('wd_id', $wd_id)
            ->where('batch', $batch)
            ->where_in('Type', ['Entries', 'Positive Adjustment'])
            ->get($this->_table);

        foreach ($query->result() as $row) {
            if ($row->ExistingStock >= $quantity) {
                $this->db->update($this->_table, ['ExistingStock' => $row->ExistingStock - $quantity], ['who_drug_count_id' => $row->who_drug_count_id]);
                $quantity = 0;
                break;
            } else {
                $this->db->update($this->_table, ['ExistingStock' => 0], ['who_drug_count_id' => $row->who_drug_count_id]);
                $quantity -= $row->ExistingStock;
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
