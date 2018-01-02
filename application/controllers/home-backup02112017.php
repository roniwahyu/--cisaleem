
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Home extends MX_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library(array('MyAuth'));
		$this->load->library('grocery_CRUD');
		$this->load->model('grocery/Grocery_crud_model');
		$this->load->model('home_model','homedb',TRUE);
		/*if (!($this->myauth->is_loggedin())){
			redirect('simpleauth/login2');
		}*/
	}
	public function index()
	{
		
		if ($this->myauth->is_loggedin())
			{
				// echo "logedin";
				$this->template->load_view('home');
			} else {
				// echo "not logedin";
				redirect('main/login');
				// User is NOT logged in
			}
		// $this->load->view('simple_view');
	}
	function logout(){
		$this->myauth->logout();
		redirect('home');
	}
	function loginlist(){
		$this->template->add_js('highcharts.js');
		$this->template->add_js('exporting.js');
		$this->template->add_js("$('.chart1').highcharts({
                chart:{
                    type:'line'
                },
                title: {
                    text: 'Success Login Attemps '
                },

                subtitle: {
                    text: 'Login Tipe and Time Execution'
                },

                yAxis: {
                    title: {
                        text: 'Miliseconds'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

              	series: ".$this->getdata()."

            });
		$('.chart2').highcharts({
                chart:{
                    type:'line'
                },
                title: {
                    text: 'Failed Login Attemps '
                },

                subtitle: {
                    text: 'Login Tipe and Time Execution'
                },

                yAxis: {
                    title: {
                        text: 'Miliseconds'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

              	series: ".$this->getloginfailed()."

            });
			$('.chart3').highcharts({
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: 'Success Login Execution Time'
		    },
		    subtitle: {
		        text: 'Miliseconds succes login execution '
		    },
		    xAxis: {
		        categories: [
		            'Minimum','Maximum','Average'
		           
		        ],
		        crosshair: true
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Miliseconds'
		        }
		    },
		   
		    plotOptions: {
		        column: {
		            pointPadding: 0.2,
		            borderWidth: 0
		        }
		    },
		    series: ".$this->getavg()."
		});
		$('.chart4').highcharts({
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: 'Failed Login Execution Time'
		    },
		    subtitle: {
		        text: 'Miliseconds succes login execution '
		    },
		    xAxis: {
		        categories: [
		            'Minimum','Maximum','Average'
		           
		        ],
		        crosshair: true
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Miliseconds'
		        }
		    },
		   
		    plotOptions: {
		        column: {
		            pointPadding: 0.2,
		            borderWidth: 0
		        }
		    },
		    series: ".$this->getfailedavg()."
		});
		$('.chart5').highcharts({
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: 'Success Registration Execution Time'
		    },
		    subtitle: {
		        text: 'Miliseconds succes registration execution '
		    },
		    xAxis: {
		        categories: [
		            'Minimum','Maximum','Average'
		           
		        ],
		        crosshair: true
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Miliseconds'
		        }
		    },
		   
		    plotOptions: {
		        column: {
		            pointPadding: 0.2,
		            borderWidth: 0
		        }
		    },
		    series: ".$this->getavgregsuccess()."
		});
		$('.aes').highcharts({
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: 'Processing Overhead of AES'
		    },
		    subtitle: {
		        text: 'Processing Overhead Chart for different number of users'
		    },
		    xAxis: {
		        categories: [
		            '10','20','30','40','50'
		           
		        ],
		        crosshair: true
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Miliseconds'
		        }
		    },
		   
		    plotOptions: {
		        column: {
		            pointPadding: 0.2,
		            borderWidth: 0
		        }
		    },
		    series: ".$this->getloginsuccessaes()."
		});
		$('.aesone').highcharts({
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: 'AES with different Key Sizes'
		    },
		    subtitle: {
		        text: ''
		    },
		    xAxis: {
		        categories: [
		            ' ',' ',
		           
		        ],
		        crosshair: true
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Miliseconds'
		        }
		    },
		   
		    plotOptions: {
		        column: {
		            pointPadding: 0.2,
		            borderWidth: 0
		        }
		    },
		    series: ".$this->getoneloginsuccessaes()."
		});
		$('.aeshashhybrid').highcharts({
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: 'Processing Overhead Hash & Hybrid'
		    },
		    subtitle: {
		        text: 'Processing Overhead Chart for different number of users'
		    },
		    xAxis: {
		        categories: [
		            '10','20','30','40','50'
		           
		        ],
		        crosshair: true
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Miliseconds'
		        }
		    },
		   
		    plotOptions: {
		        column: {
		            pointPadding: 0.2,
		            borderWidth: 0
		        }
		    },
		    series: ".$this->getloginsuccessallscheme()."
		});
		",'embed');
		// $loginlist=$this->homedb->loginlist(3);
		// print_r($loginlist);
		$this->template->set_layout('default');
		$this->template->load_view('chart');
	}
	public function _example_output($output = null)
	{
		// $this->load->view('example.php',(array)$output);
		$this->template->load_view('grocery/example',(array)$output);
	}
	function users(){
		if (!($this->myauth->is_loggedin())){
			redirect('main/login');
		}
		try{
			$crud = new grocery_CRUD();
			$crud->set_table('users');
			$crud->set_theme('bootstrap');
			$crud->columns('username','password','email','tokenkey');

			$crud->fields('username', 'password');
			$output = $crud->render();
			$this->_example_output($output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}

		// $crud->set_relation_n_n('actors', 'film_actor', 'actor', 'film_id', 'actor_id', 'fullname','priority');
		// $crud->set_relation_n_n('category', 'film_category', 'category', 'film_id', 'category_id', 'name');
		// $crud->unset_columns('special_features','description','actors');



	}

	function getlog($act,$scheme,$stat){
		$arr=$this->homedb->userlog($act,$scheme,$stat);
		if(count($arr)>0){
	
			foreach ($arr as $key => $value) {
				# code...
				$x[]=$value['timeval'];
			}
		}else{
			$x=array();
		}
		return $x;
	}
	function getlogaesmin($act,$scheme,$stat){
		$arr=$this->homedb->userlogmin($act,$scheme,$stat);
		if(count($arr)>0){
	
			foreach ($arr as $key => $value) {
				# code...
				$x[]=$value['timevalmin'];
			}
		}else{
			$x=array();
		}
		return $x;
	}
	function getlogaesmax($act,$scheme,$stat){
		$arr=$this->homedb->userlogmax($act,$scheme,$stat);
		if(count($arr)>0){
	
			foreach ($arr as $key => $value) {
				# code...
				$x[]=$value['timevalmax'];
			}
		}else{
			$x=array();
		}
		return $x;
	}
	function getlogaesavg($act,$scheme,$stat){
		$arr=$this->homedb->userlogaesavg($act,$scheme,$stat);
		// print_r($arr);
		if(count($arr)>0){
	
			foreach ($arr as $key => $value) {
				# code...
				$x[]=$value['timevalavg'];
			}
		}else{
			$x=array();
		}
		return $x;
	}
	function logavg(){
		$data['avg']=$this->getlogaesavg('login secretkey success user128',3,'1');
		$data['min']=$this->getlogaesmin('login secretkey success user128',3,'1');
		$data['max']=$this->getlogaesmax('login secretkey success user128',3,'1');
		echo ((str_replace('"',"'",json_encode($data,JSON_NUMERIC_CHECK))));
	}
	
	function getlogsum($act,$scheme,$stat){
		$arr=$this->homedb->userlog($act,$scheme,$stat);
		if(count($arr)>0){
	
			foreach ($arr as $key => $value) {
				# code...
				$x[]=$value['timeval'];
			}
		}else{
			$x=array();
		}
		return $x;
	}
	function getavg(){
		$b=$this->homedb->avg_timelog('login secretkey success user128',3,'1');
		$c=$this->homedb->avg_timelog('login secretkey success user192',3,'1');
		$d=$this->homedb->avg_timelog('login secretkey success user256',3,'1');
		$e=$this->homedb->avg_timelog('login hash success',4,'1');
		$f=$this->homedb->avg_timelog('login hybrid success',5,'1');
		// $c=$this->homedb->avg_timelog('login',4,'1');
		// $d=$this->homedb->avg_timelog('login',5,'1');
		$arr=array(
		
			array('name'=>'AES128',
				'data'=>array(
					0=>$b[0]['mintime'],
					1=>$b[0]['maxtime'],
					2=>$b[0]['avgtime'])
					),
			array('name'=>'AES192',
				'data'=>array(
					0=>$c[0]['mintime'],
					1=>$c[0]['maxtime'],
					2=>$c[0]['avgtime'])
					),
			array('name'=>'AES256',
				'data'=>array(
					0=>$d[0]['mintime'],
					1=>$d[0]['maxtime'],
					2=>$d[0]['avgtime'])
					),
			array('name'=>'Hash',
				'data'=>array(
					0=>$e[0]['mintime'],
					1=>$e[0]['maxtime'],
					2=>$e[0]['avgtime'])
					),
			array('name'=>'Hybrid',
				'data'=>array(
					0=>$f[0]['mintime'],
					1=>$f[0]['maxtime'],
					2=>$f[0]['avgtime'])
					),

			);
		return ((str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK))));		
	}
	function getaestime(){
		$a=$this->homedb->avg_timelog('login',2,'1');
		$b=$this->homedb->avg_timelog('login',3,'1');
		$c=$this->homedb->avg_timelog('login',4,'1');
		$d=$this->homedb->avg_timelog('login',5,'1');
		$arr=array(
		
			array('name'=>'SecretKey',
				'data'=>array(
					0=>$b[0]['mintime'],
					1=>$b[0]['maxtime'],
					2=>$b[0]['avgtime'])
					),
			array('name'=>'Hash',
				'data'=>array(
					0=>$c[0]['mintime'],
					1=>$c[0]['maxtime'],
					2=>$c[0]['avgtime'])
					),
			array('name'=>'Hybrid',
				'data'=>array(
					0=>$d[0]['mintime'],
					1=>$d[0]['maxtime'],
					2=>$d[0]['avgtime'])
					),

			);
		return ((str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK))));		
	}
	function getavgregsuccess(){
		$a=$this->homedb->avg_timelog('register',9,'1');
	
		$arr=array(
			array('name'=>'All Scheme',
				'data'=>array(
					0=>$a[0]['mintime'],
					1=>$a[0]['maxtime'],
					2=>$a[0]['avgtime'])
					),

			);
		return ((str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK))));		
	}
	function getfailedavg(){
		$a=$this->homedb->avg_timelog('login',2,'0');
		$b=$this->homedb->avg_timelog('login',3,'0');
		$c=$this->homedb->avg_timelog('login',4,'0');
		$d=$this->homedb->avg_timelog('login',5,'0');
		$arr=array(
			
			array('name'=>'SecretKey',
				'data'=>array(
					0=>$b[0]['mintime'],
					1=>$b[0]['maxtime'],
					2=>$b[0]['avgtime'])
					),
			array('name'=>'Hash',
				'data'=>array(
					0=>$c[0]['mintime'],
					1=>$c[0]['maxtime'],
					2=>$c[0]['avgtime'])
					),
			array('name'=>'Hybrid',
				'data'=>array(
					0=>$d[0]['mintime'],
					1=>$d[0]['maxtime'],
					2=>$d[0]['avgtime'])
					),

			);
		return ((str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK))));		
	}
		function getlogs(){
		// $arr=$this->homedb->userlog('login',4,'1');
		// print_r($arr);
		// print_r($this->getlog('login',4,'1'));
		echo $this->getdata();
	}
	function getdata(){
		$b_success=$this->getlog('login secretkey success user128',3,'1');
		$c_success=$this->getlog('login secretkey success user192',3,'1');
		$d_success=$this->getlog('login secretkey success user256',3,'1');
		$e_success=$this->getlog('hash success',4,'1');
		$f_success=$this->getlog('hybrid success',5,'1');
		// $c_success=$this->getlog('secretkey success',3,'1');
		// $d_success=$this->getlog('secretkey success',3,'1');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			array('name'=>'AES128','data'=>$b_success),
			array('name'=>'AES192','data'=>$c_success),
			array('name'=>'AES256','data'=>$d_success),
			array('name'=>'Hash','data'=>$e_success),
			array('name'=>'Hybrid','data'=>$f_success),
			// array('name'=>'Hash Login','data'=>$c_success),
			// array('name'=>'Hybrid Login (Proposed)','data'=>$d_success),
			);
		return (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function getloginsuccessaesavg(){
		$b_success=$this->getlogaesavg('login secretkey aes128',3,'1');
		$c_success=$this->getlogaesavg('login secretkey aes192',3,'1');
		$d_success=$this->getlogaesavg('login secretkey aes256',3,'1');
		// $d_success=$this->getlog('login secretkey success user',5,'1');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'AES128','data'=>$b_success),
			array('name'=>'AES192','data'=>$c_success),
			array('name'=>'AES256','data'=>$d_success),
			// array('name'=>'Hybrid Login (Proposed)','data'=>$d_success),
			);
		echo (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function getloginsuccessaes(){
		$b_success=$this->getlog('login secretkey aes128',3,'1');
		$c_success=$this->getlog('login secretkey aes192',3,'1');
		$d_success=$this->getlog('login secretkey aes256',3,'1');
		// $d_success=$this->getlog('login secretkey success user',5,'1');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'AES128','data'=>$b_success),
			array('name'=>'AES192','data'=>$c_success),
			array('name'=>'AES256','data'=>$d_success),
			// array('name'=>'Hybrid Login (Proposed)','data'=>$d_success),
			);
		return (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function getoneloginsuccessaes(){
		$b_success=$this->getlogaesavg('login secretkey success user128_01',3,'1');
		// $x_success=$this->getlogaesmax('login secretkey success user128_01',3,'1');
		$c_success=$this->getlogaesavg('login secretkey success user192_01',3,'1');
		// $y_success=$this->getlogaesmax('login secretkey success user192_01',3,'1');
		$d_success=$this->getlogaesavg('login secretkey success user256_01',3,'1');
		// $z_success=$this->getlogaesmax('login secretkey success user256_01',3,'1');
		// $d_success=$this->getlog('login secretkey success user',5,'1');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'AES128','data'=>$b_success),
			// array('name'=>'Max AES128','data'=>$x_success),
			array('name'=>'AES192','data'=>$c_success),
			// array('name'=>'Max AES192','data'=>$y_success),
			array('name'=>'AES256','data'=>$d_success),
			// array('name'=>'Max AES256','data'=>$z_success),
			// array('name'=>'Hybrid Login (Proposed)','data'=>$d_success),
			);
		return (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function getloginsuccessallscheme(){
		$a_success=$this->getlogaesavg('data success',3,'1');
		$b_success=$this->getlog('login hash data',4,'1');
		$c_success=$this->getlog('login hybrid data',5,'1');
		// $d_success=$this->getlog('login secretkey success user',5,'1');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'AES','data'=>$a_success),
			array('name'=>'Hash','data'=>$b_success),
			array('name'=>'Hybrid','data'=>$c_success),
			// array('name'=>'Hybrid Login (Proposed)','data'=>$d_success),
			);
		return (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function getloginfailed(){
		$b_failed=$this->getlog('login',3,'0');
		$c_failed=$this->getlog('login',4,'0');
		$d_failed=$this->getlog('login',5,'0');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'Secret Login','data'=>$b_failed),
			array('name'=>'Hash Login','data'=>$c_failed),
			array('name'=>'Hybrid Login (Proposed)','data'=>$d_failed),
			);
		return (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function avg_logsuccess(){
		$q=$this->homedb->avg_timelog(4,1);
		print_r($q);
	}
	function graph(){
		$a=$this->homedb->userlog('login',2,'1');
		foreach ($a as $key => $value) {
			# code...
			$x[]=$value['timeval'];
		}
		print_r(json_encode($x));
		// print_r(json_encode($a));
	}
	
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
 ?>