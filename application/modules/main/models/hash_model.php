<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Hash_model extends CI_Model {

	public function getuser($username)
	{
		$this->db->select('id as identifier,username,password,username_hash,password_hash,tokenkey')->from('users');
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
		$this->db->where('left(password_hash,32)',$password_hash);
		$this->db->where('left(username_hash,32)',$username_hash);
		$query=$this->db->get();
		if($query->num_rows()>0){
			/*return array(
				'data'=>$query->row_array(),
				'query'=>$this->db->last_query(),
				);*/
			// return $query->row_array();
			return TRUE;
		}else{
			// return array();
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