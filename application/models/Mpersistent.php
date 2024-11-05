<?php


class Mpersistent extends CI_Model
{
    public $table_name = NULL;
    public $fields = array();
    public $obj_field = NULL;
    private $is_open = FALSE;

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function load($table)
    {
        if ($table == "") die("Table not found");
        $this->table_name = $table;
        $fields = $this->db->field_data($this->table_name);
        foreach ($fields as $field) {
            $this->fields[$field->name] = "";
            if ($field->primary_key == 1) {
                $this->obj_field = $field->name;
            }
        }
    }

    function open_id($id = NULL, $table = null, $key_field = null)
    {
        if (!$id) return "";
        if (!$table) return "";
        if (!$key_field) return "";
        //if (!is_numeric($id)) return "";

        $data = array();

        $qry = "select * from " . $table . ' where ' . $key_field . ' = "' . $id . '" ';
        $query = $this->db->query($qry);
        if ($query->num_rows() == 1) {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }

    function update($table = null, $key_field = null, $id = null, $data)
    {

        if (!$id) return FALSE;
        if (!$table) return FALSE;
        if (!$key_field) return FALSE;
        $data["LastUpDate"] = date("Y-m-d H:i:s");
        $data["LastUpDateUser"] = $this->session->userdata("FullName");
        $this->db->where($key_field, $id);
        $this->db->update($table, $data);
        return $id;
    }

    function delete($id = null, $table = null, $key_field = null)
    {
        if (!$id) return FALSE;
        if (!$table) return FALSE;
        if (!$key_field) return FALSE;
        $this->db->where($key_field, $id);
        $this->db->delete($table);
        return $id;
    }

    function create($table = null, $data)
    {
        if (!$table) return FALSE;
        if (!$data) return FALSE;
        $data["CreateDate"] = date("Y-m-d H:i:s");
        $data["CreateUser"] = $this->session->userdata("FullName");
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    function insert_batch($table = null, $data)
    {
        if (!$table) return FALSE;
        if (!$data) return FALSE;
        $this->db->insert_batch($table, $data);
        if ($this->db->_error_message()) {
            return 0;
        } else {
            return 1;
        }
    }

    function get_value($name = NULL)
    {
        if (!$name) die("name not found");
        if (array_key_exists($name, $this->fields)) {
            return $this->fields[$name];
        } else {
            return "Field $name Not Found";
        }
    }

    function get_id()
    {
        if (array_key_exists($this->obj_field, $this->fields)) {
            return $this->fields[$this->obj_field];
        } else {
            return "Field $name Not Found";
        }
    }

    function table_select($sql)
    {
        $dataset = array();
        $Q = $this->db->query($sql);
        if ($Q->num_rows() > 0) {
            // foreach ($Q->result_array() as $row){
            $dataset = $Q->result_array();
            //}
        }
        $Q->free_result();
        return $dataset;
    }
}

