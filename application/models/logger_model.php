
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class logger_model extends CI_Model {

	public function log($act,$time,$stat,$scheme,$bit,$numsalt)
	{
		$data=array(
			'datetime'=>date('Y-m-d H:i:s'),
			'activity'=>$act,
			'bit'=>$bit,
			'saltnum'=>$numsalt,
			'timeval'=>$time,
			'status'=>$stat,
			'scheme'=>$scheme,
			);
		$this->db->insert('log',$data);
		return $this->db->insert_id();
	}
}

/* End of file logger_model.php */
/* Location: ./application/models/logger_model.php */

 ?>