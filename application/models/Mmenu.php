<?php
/**
 * This model works with information in database, which relates to menu.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */

class Mmenu extends CI_Model
{

    function __construct()
    {
        parent::__construct();
		$this->load->database();
		
    }

    function get_menu_list($ug){
        $dataset = array();
		
		$sql  = "SELECT Name, Link,UMID ";
		$sql .= " FROM user_menu WHERE Active = TRUE and UserGroup REGEXP '".$ug."'  " ;
		$sql .= " ORDER BY MenuOrder";

        $Q =  $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        if ($Q->num_rows() > 0){
            foreach ($Q->result_array() as $row){
                $dataset[] = $row;
            }
        }
        $Q->free_result();    
        //print_r($data);
        return $dataset;    
    }

    //PP confiduration, the top menu will be different
    function get_menu_list_for_PP($ug){
        $dataset = array();
		
		$sql  = "SELECT Name, Link,UMID ";
		$sql .= " FROM user_menu WHERE Active = TRUE and UserGroup REGEXP '".$ug."'  " ;
		$sql .= "AND PP_Menu = 1 ORDER BY MenuOrder";
		
        $Q =  $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        if ($Q->num_rows() > 0){
            foreach ($Q->result_array() as $row){
                $dataset[] = $row;
            }
        }
        $Q->free_result();    
        //print_r($data);
        return $dataset;    
    }
    
	
	function get_home_menu($ug){
        $data = array();
		$sql  = "SELECT MainMenu ";
		$sql .= " FROM user_group WHERE Active = TRUE and Name ='".$ug."'  " ;
	// die(print_r($sql));
        $Q =  $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        if ($Q->num_rows() > 0){
                $data= $Q->row_array();;
        }
        $Q->free_result();    
        //print_r($data);
        return $data;    
    }
}
