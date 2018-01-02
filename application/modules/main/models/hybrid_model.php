<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Hybrid_model extends CI_Model {

	public function getuser($username)
	{
		$this->db->select('id as identifier,username,password,username_hash,password_hash,tokenkey,salt_before,salt_after')->from('users');
		$this->db->where('username',$username);
		// $this->db->where('password',$password);
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->row_array();
		}else{
			return array();
		}
	}
	public function login($username,$password,$username_hash,$password_hash)
	{
		$this->db->select('id as identifier,username,password,username_hash,password_hash')->from('users');
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$this->db->where('password_hash',$password_hash);
		$this->db->where('username_hash',$username_hash);
		$query=$this->db->get();
		if($query->num_rows()>0){
		
			return TRUE;
		}else{
			
			return FALSE;
		}
	}
	public function username_exists($username)
	{
		$this->db->select('username,password,enc_username,enc_password')->from('users');
		$this->db->where('username',$username);
	
		$query=$this->db->get();
		if($query->num_rows()>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

/* End of file secretkey_model.php */
/* Location: ./application/modules/secretkey/models/secretkey_model.php */

 ?>