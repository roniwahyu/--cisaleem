<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Home_model extends CI_Model {

	public function avg_timelog($act,$scheme=2,$stat='1')
	{
		$this->db->select('scheme,status,min(timeval) as mintime,max(timeval) as maxtime,avg(timeval) as avgtime,')
			->from('log')
			->where('scheme',$scheme)
			->where('status',$stat)
			->where("`activity` like '%".$act."%'",NULL,FALSE);

			// ->group_by('scheme');
		$query=$this->db->get();
		if($query->num_rows()==1){
			$result=$query->result_array();
		}elseif($query->num_rows()>1){
			$result=$query->result_array();
		}else{
			$result=array();
		}
		return $result;
	
	}
	function userlog($act,$scheme,$stat,$bit=128,$num=3){
		$this->db->select('id,activity,timeval,datetime')->from('log')->where('scheme',$scheme);
		$this->db->where("`activity` like '%".$act."%'",NULL,FALSE);
		$this->db->where('status',$stat);
		$this->db->where('bit',$bit);
		$this->db->where('saltnum',$num);

		// $this->db->like('activity','login',FALSE);
		// $this->db->escape();
		// $this->db->or_like('activity',$act,'none');

		$query=$this->db->get();
		// echo $this->db->last_query();
		if($query->num_rows()==1){
			$result=$query->result_array();
		}elseif($query->num_rows()>1){
			$result=$query->result_array();
		}else{
			$result=array();
		}
		return $result;
	}
	function userlogavg($act,$scheme,$stat,$bit=128,$num=3){
		$this->db->select('id,activity,avg(timeval) as timevalavg,count(id) as num,datetime')->from('log')->where('scheme',$scheme);
		$this->db->where('status',$stat);
		$this->db->where('bit',$bit);
		$this->db->where('saltnum',$num);
		$this->db->where("`activity` like '%".$act."%'",NULL,FALSE);

		// $this->db->like('activity','login',FALSE);
		// $this->db->escape();
		// $this->db->or_like('activity',$act,'none');

		$query=$this->db->get();
		// echo $this->db->last_query();
		if($query->num_rows()==1){
			$result=$query->result_array();
		}elseif($query->num_rows()>1){
			$result=$query->result_array();
		}else{
			$result=array();
		}
		return $result;
	}
	function userlogaesavg($act,$scheme,$stat,$bit,$num){
		$this->db->select('id,activity,avg(timeval) as timevalavg,count(id) as num,mid(activity,24,2) as datanum,datetime')->from('log')->where('scheme',$scheme);
		$this->db->where('status',$stat);
		$this->db->where('bit',$bit);
		$this->db->where('saltnum',$num);
		$this->db->where("`activity` like '%".$act."%'",NULL,FALSE);
		$this->db->group_by('mid(activity,24,2)');

		// $this->db->like('activity','login',FALSE);
		// $this->db->escape();
		// $this->db->or_like('activity',$act,'none');

		$query=$this->db->get();
		// echo $this->db->last_query();
		if($query->num_rows()==1){
			$result=$query->result_array();
		}elseif($query->num_rows()>1){
			$result=$query->result_array();
		}else{
			$result=array();
		}
		return $result;
	}

	function userlogmin($act,$scheme,$stat,$bit=128,$num=3){
		$this->db->select('id,activity,min(timeval) as timevalmin,count(id) as num,datetime')->from('log')->where('scheme',$scheme);
		$this->db->where('status',$stat);
		$this->db->where('bit',$bit);
		$this->db->where('saltnum',$num);
		$this->db->where("`activity` like '%".$act."%'",NULL,FALSE);

		// $this->db->like('activity','login',FALSE);
		// $this->db->escape();
		// $this->db->or_like('activity',$act,'none');

		$query=$this->db->get();
		// echo $this->db->last_query();
		if($query->num_rows()==1){
			$result=$query->result_array();
		}elseif($query->num_rows()>1){
			$result=$query->result_array();
		}else{
			$result=array();
		}
		return $result;
	}
	function userlogmax($act,$scheme,$stat,$bit=128,$num=3){
		$this->db->select('id,activity,max(timeval) as timevalmax,count(id) as num,datetime')->from('log')->where('scheme',$scheme);
		$this->db->where('status',$stat);
		$this->db->where('bit',$bit);
		$this->db->where('saltnum',$num);
		$this->db->where("`activity` like '%".$act."%'",NULL,FALSE);

		// $this->db->like('activity','login',FALSE);
		// $this->db->escape();
		// $this->db->or_like('activity',$act,'none');

		$query=$this->db->get();
		// echo $this->db->last_query();
		if($query->num_rows()==1){
			$result=$query->result_array();
		}elseif($query->num_rows()>1){
			$result=$query->result_array();
		}else{
			$result=array();
		}
		return $result;
	}
	function loginlist($scheme){
		$this->db->select('id,activity,timeval,datetime')->from('log');
		$this->db->where('scheme',$scheme);
		$this->db->like('activity','login');
		$this->db->or_like('activity','login success');
		$this->db->not_like('activity','login failed');
		$query=$this->db->get();
		// print_r($query);
		if($query->num_rows()==1){
			$result=$query->row_array();
		}elseif($query->num_rows()>1){
			$result=$query->result_array();
		}else{
			$result=array();
		}
		// echo "<pre>";
		// print_r($result);
		// echo "</pre>";
		return $result;
	}
}

/* End of file home_model.php */
/* Location: ./application/models/home_model.php */
 ?>