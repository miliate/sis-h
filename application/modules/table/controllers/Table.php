<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Table extends LoginCheckController
{

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {

        parent::__construct();
        $this->load->library('encrypt');
    }

    public function index()
    {
        $this->load->view("table_render");
    }

    public function ajaxBacking()
    {

        $page = (int)$this->sanitize($_POST['page'], false, true);
        $rp = $this->sanitize($_POST['rows'], false, true);
        $sortname = $this->sanitize($_POST['sidx'], false, true);
        $sortorder = $this->sanitize($_POST['sord'], false, true);
        $ddtype = $this->sanitize($_POST['cell']);
        $rowid = $this->sanitize($_POST['rowid'], false, true);
        $query = $this->sanitize($_POST['exec']);
        if (isset($_POST['filters'])) {
            $filters = $this->sanitize($_POST['filters'], false, false);
            $filters = json_decode(trim($filters));
        } else {
            $filters = null;
        }


        $searchFields = array();
        if (isset($filters->rules)) {
            foreach ($filters->rules as $rule) {
                $searchFields[$rule->field] = $rule->data;
            }
        }

        $ddtype = json_decode(trim($ddtype));
        $ddtypes = array();
        if (is_array($ddtype)) {
            foreach ($ddtype as $value) {
                if (isset($value->name)) {
                    $ddtypes[$value->name] = array("value" => $value->value, "table" => $value->table,
                        "column" => $value->column);
                }
            }
        }

        $where = '';
        $split = preg_split("/GROUP BY/i", $query);
        if (is_array($split)) {
            $query = isset($split[0]) ? $split[0] : '';
            $groupBy = isset($split[1]) ? $split[1] : '';
            if ($groupBy != '') {
                $groupBy = " GROUP BY $groupBy";
            }
        }

        if (stripos($query, 'where') == false) {
            $where .= ' where ';
        } else {
            $where .= ' and ';
        }

        foreach ($ddtypes as $key => $value) {

            unset($searchField);
            if (isset($searchFields[$key])) {
                $searchField = $searchFields[$key];
            }

            if ($sortname == $key) {
                $sortname = $value['column'];
            }
            if ($value["table"] != '') {
                $key = $value["table"] . '.' . '`' . $value['column'] . '`';
                //PP Configuration
                $table = $value["table"];

            } else {
                $key = '' . $value['column'] . '';
            }
            if (isset($searchField) && $searchField != '') {

                if ($value["value"] == "DDTYPE") {

                    // PP Configuration
                    if ($key == $table . ".`" . "Delete`" || $key == $table . ".`" . "Edit`") {
                        //jump this where clause because Delete and Edit are not a DB column
                    } else {
                        $where .= "$key like '%$searchField%' and ";
                    }
                } else {
                    if ($value["value"] == "DSTYPE") {
                        $where .= "$key='$searchField' and ";
                    }
                }
            }

        }


        if (strcasecmp(trim($where), 'where') == 0) {
            $where = '';
        } else {
            $where = substr($where, 0, -4);
        }


        $query .= " $where ";

        $query = trim($query);


//meta data
        unset($result);
        $result = $this->db->query($query);

        $total = $result->num_rows();
        if (!$sortname) {
            $meta = mysqli_fetch_field($result->result_id);
            $sortname = $meta->name;
        }
        if (!$rowid) {
            $meta = mysqli_fetch_field($result->result_id);
            $rowid = $meta->name;
        }
        if (!$sortorder) {
            $sortorder = 'desc';
        }


        $sort = "ORDER BY `$sortname` $sortorder";
        if (!$page) {
            $page = 1;
        }
        if (!$rp) {
            $rp = 10;
        }
        $start = (($page - 1) * $rp);

        $limit = "LIMIT $start, $rp";
        header("Content-type: text/x-json");
        $i = 0;

        //Fields are filled from the DB
        $fields = array();
        $finfo = mysqli_fetch_fields($result->result_id);
        foreach ($finfo as $field) {
            array_push($fields, $field->name);
        }
//        while ($field = mysqli_fetch_field($result->result_id)) {
//            array_push($fields, $field->name);
//        }
//        var_dump($fields);
        //total calc
        $query .= "$groupBy $sort ";

        $total_pages = 0;
        if ($total > 0) {
            $total_pages = ceil($total / $rp);
        }

        $query .= "$limit ";
        unset($result);
        $result = $this->db->query($query);
        $response = new stdClass();
        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $total;

        $i = 0;
        while ($row = mysqli_fetch_array($result->result_id)) {
            $response->rows[$i]['id'] = isset($row[$rowid]) ? $row[$rowid] : '';

            $cell = array();
            foreach ($fields as $field) {
                array_push($cell, $row[$field]);
            }

            $response->rows[$i]['cell'] = $cell;
            $i++;

        }
        echo json_encode($response, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    //PP Configuration
    function edit_row()
    {
        $table = $_GET["table"];
        $row = $_GET["row"];
        $new_page = base_url() . "index.php/form/edit/?table=" . $table . "&row=" . $row;
        header("Location: " . $new_page);
    }

    //PP Configuration
    function delete_row()
    {
        $table = $_GET["table"];
        $row = $_GET["row"];
        $new_page = base_url() . "index.php/form/delete/?table=" . $table . "&row=" . $row;
        header("Location: " . $new_page);
    }


    function decrypt($string)
    {

        $encrypted_string = urldecode($string);
        $encrypted_string = base64_decode($encrypted_string);
        $plaintext_string = $this->encrypt->decode($encrypted_string);
        $output = trim($plaintext_string);

        return $output;
    }

    function sanitize($data, $enc = true, $param = false)
    {

        if ($param) {
            $data = trim($data);
            $data = htmlspecialchars($data);
            $data = stripslashes($data);
        }
        if ($enc) {
            $data = $this->decrypt($data);
        }

        return $data;
    }

    function printPager()
    {
        $dat_d = date('y-m-d');
        $sortname = $this->sanitize($_POST['sidx'], false, true);
        $sortorder = $this->sanitize($_POST['sord'], false, true);
        $ddtype = $this->sanitize($_POST['cell']);


        $title = '';
        if (isset($_POST['title'])) {
            $title = $this->sanitize($_POST['title']);
        }

        $orientation = 'P';
        if (isset($_POST['orientation'])) {
            $orientation = $this->sanitize($_POST['orientation']);
        }

        $saveAsName = $this->sanitize($_POST['save']) . '_' . $dat_d;

        $colHeaders = array();
        if (isset($_POST['colHeaders'])) {
            $colHeaders = $this->sanitize($_POST['colHeaders']);
        }

        $widths = array();
        if (isset($_POST['widths'])) {
            $widths = $this->sanitize($_POST['widths']);;
        }

        $rowid = null;
        if (isset($_POST['rowid'])) {
            $rowid = $this->sanitize($_POST['rowid'], false, true);
        }

        $query = $this->sanitize($_POST['exec']);

        $hospitalName = "Demo Hospital";
        if (isset($_SESSION["Hospital"])) {
            $hospitalName = $_SESSION["Hospital"];
        }


        if (!($orientation == 'p' || $orientation == 'P' || $orientation == 'l' || $orientation == 'L')) {
            $orientation = 'P';
        }
        $colHeaders = str_replace('[', '', $colHeaders);
        $colHeaders = str_replace(']', '', $colHeaders);
        $colHeaders = str_replace("'", '', $colHeaders);
        $colHeaders = explode(',', $colHeaders);

        $widths = str_replace('[', '', $widths);
        $widths = str_replace(']', '', $widths);
        $widths = str_replace("'", '', $widths);
        $widths = explode(',', $widths);


        $ddtype = json_decode($ddtype);
        $ddtypes = array();
        foreach ($ddtype as $value) {
            $ddtypes[$value->name] = array("value" => $value->value, "table" => $value->table,
                "column" => $value->column);
        }

        $where = '';
        $split = preg_split("/GROUP BY/i", $query);
        $query = isset($split[0]) ? $split[0] : null;
        $groupBy = isset($split[1]) ? $split[1] : null;
        if ($groupBy != '') {
            $groupBy = " GROUP BY $groupBy";
        }
        if (stripos($query, 'where') == false) {
            $where .= ' where ';
        } else {
            $where .= ' and ';
        }
        foreach ($ddtypes as $key => $value) {
            unset($searchField);
            if (isset($_POST[$key])) {
                $searchField = $_POST[$key];
            }
            if ($sortname == $key) {
                $sortname = $value['column'];
            }
            if ($value["table"] != '') {
                $key = $value["table"] . '.' . '`' . $value['column'] . '`';
            } else {
                $key = '' . $value['column'] . '';
            }
            if (isset($searchField)) {
                if ($value["value"] == "DDTYPE") {
                    $where .= "$key like '%$searchField%' and ";
                } else {
                    if ($value["value"] == "DSTYPE") {
                        $where .= "$key='$searchField' and ";
                    }
                }
            }
        }
        if (strcasecmp(trim($where), 'where') == 0) {
            $where = '';
        } else {
            $where = substr($where, 0, -4);
        }

        $query .= " $where ";
//meta data
        unset($result);
        $result = $this->db->query($query);
        if (!$sortname) {
            $meta = mysqli_fetch_field($result->result_id);
            $sortname = $meta->name;
        }
        if (!$rowid) {
            $meta = mysqli_fetch_field($result->result_id);
            $rowid = $meta->name;
        }
        if (!$sortorder) {
            $sortorder = 'desc';
        }


        $sort = "ORDER BY `$sortname` $sortorder";

        $query .= "$groupBy $sort ";
        header("Content-type: application/pdf");
        $this->load->library(
            'class/MDSReporter',
            array('orientation' => $orientation, 'unit' => 'mm', 'format' => 'A4', 'footer' => true)
        );
        $pdf = $this->mdsreporter;
        $pdf->writeTitle($hospitalName);
        $pdf->writeSubTitle($title);
        $this->load->model('mpager');
        $pdf = $this->mpager->mysqlReport($pdf, $query, $colHeaders, $widths);
        $pdf->Output($saveAsName, 'I');
        exit;
    }


}

