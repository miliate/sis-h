<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Copyright (C) 2016 CNL, Inje University - cnl.inje.ac.kr
 */
class MY_CRUD extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->before_create = array('created_timestamps');
        $this->before_update = array('update_timestamps');
    }

    protected function created_timestamps($row)
    {
        $row['CreateDate'] = date('Y-m-d H:i:s');
        $row["CreateUser"] = $this->session->userdata("uid");
        return $row;
    }

    protected function update_timestamps($row)
    {
        $row['LastUpDate'] = date('Y-m-d H:i:s');
        $row["LastUpDateUser"] = $this->session->userdata("uid");
        return $row;
    }

    /**
     * Get all data rows based on $sql command.
     */
    protected function table_select($sql)
    {
        $dataset = array();
        $Q = $this->db->query($sql);
        if ($Q->num_rows() > 0) {
            $dataset = $Q->result_array();
        }
        $Q->free_result();
        return $dataset;
    }
}