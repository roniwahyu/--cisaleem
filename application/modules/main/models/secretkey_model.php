<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Secretkey_model extends CI_Model {

	public function getuser($username)
	{
		$this->db->select('id as identifier,username,password,enc_username,enc_password,tokenkey')->from('users');
		$this->db->where('username',$username);
		// $this->db->where('password',$password);
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->row_array();
		}else{
			return array();
		}
	}
	public function login($username,$password,$enc_username,$enc_password)
	{
		$this->db->select('id as identifier,username,password,enc_username,enc_password')->from('users');
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$this->db->where('enc_password',$enc_password);
		$this->db->where('enc_username',$enc_username);
		$query=$this->db->get();
		if($query->num_rows()>0){
			return array(
				'data'=>$query->row_array(),
				'query'=>$this->db->last_query(),
				);
			// return $query->row_array();
			// return TRUE;
		}else{
			return array();
			// return FALSE;
		}
	}
	public function username_exists($username)
	{
		$this->db->select('username,password,enc_username,enc_password')->from('users');
		$this->db->where('username',$username);
		$query=$this->db->get();
		if($query->num_rows()==1){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function gettoken($username)
	{
		$this->db->select('username,tokenkey')->from('users');
		$this->db->where('username',$username);
		$query=$this->db->get();
		if($query->num_rows()==1){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

/* End of file secretkey_model.php */
/* Location: ./application/modules/secretkey/models/secretkey_model.php */

 ?>