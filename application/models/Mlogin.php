<?php
/**
 * This model works with information in database to do the login, logout tasks.
 * Name of class is always in lowercase, and first letter of file name is always uppercase. For example:
 ****** class name: "m_admission"
 ****** file name: "M_admission.php"
 */

class Mlogin extends CI_Model
{

    function __construct()
    {
        parent::__construct();
		$this->load->database();
    }

    function do_auth($username,$password=NULL){
        $data = array();
		$username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysql_real_escape_string($username);
        $password = md5(mysql_real_escape_string($password));
		$sql=" SELECT *  ";
        $sql .= " FROM user WHERE user.UserName='$username' and user.Password='$password'  " ;
        $sql .= " and user.Active = 1 " ;
		
        $Q =  $this->db->query($sql);
        //echo "<br />".$this->db->last_query();
        if ($Q->num_rows() > 0){
            $data = $Q->row_array();
        }
        $Q->free_result();    
        //print_r($data);
        return $data;    
    }

    function get_hospital($hid){
        $data = array();
		$sql=" SELECT *  ";
        $sql .= " FROM hospital WHERE HID = ".$hid ;
        $Q =  $this->db->query($sql);
        if ($Q->num_rows() > 0){
            $data = $Q->row_array();
        }
        $Q->free_result();    
        return $data;    
    }
	function set_online($uid){
		if (!$uid) return FALSE;
		$data = array("Status"=>"ON_LINE","LastTimeSeen"=>date("Y-m-d H:i:s"));
		$this->db->where("UID", $uid);
		$this->db->update("user", $data); 
		return true;
	}
	function set_offline($uid){
		if (!$uid) return FALSE;
		$data = array("Status"=>"OFF_LINE","LastTimeSeen"=>date("Y-m-d H:i:s"));
		$this->db->where("UID", $uid);
		$this->db->update("user", $data); 
		return true;
	}
	function set_logout($uid){
		if (!$uid) return FALSE;
		$data = array("Status"=>"LOGGED_OUT","LastTimeSeen"=>date("Y-m-d H:i:s"));
		$this->db->where("UID", $uid);
		$this->db->update("user", $data); 
		return true;
	}
}
