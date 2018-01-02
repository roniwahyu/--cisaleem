
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
                    text: 'Login Type and Time Execution'
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
		    colors:['#4572a7','#aa4643','#89a54e','#77a1e5', '#c42525', '#a6c96a'],
		    title: {
		        text: 'Success Registration Execution Time'
		    },
		    subtitle: {
		        text: 'Miliseconds success registration execution '
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
		
		
		$('.aesone').highcharts({
		    chart: {
		        type: 'column'
		    },
		    colors: ['#1aadce','#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a'],
		    title: {
		        text: 'AES with different Key Sizes'
		    },
		    subtitle: {
		        text: 'Single User width different key size'
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
		    colors:['#77a1e5', '#c42525', '#a6c96a'],		    
		    title: {
		        text: 'Processing Overhead of AES, Hash & Proposed Method'
		    },
		    subtitle: {
		        text: 'Processing Overhead Chart for different method'
		    },
		    xAxis: {
		        categories: [
		            ' '
		           
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
		$('.aeshashhybrid1').highcharts({
		    chart: {
		        type: 'column'
		    },
		    colors:['#77a1e5', '#c42525', '#a6c96a'],		    
		    title: {
		        text: 'Processing Overhead of AES, Hash & Proposed Method'
		    },
		    subtitle: {
		        text: 'Processing Overhead Chart for different method'
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
		    series: ".$this->getloginsuccessallscheme1()."
		});
		$('.hybrid').highcharts({
		    chart: {
		        type: 'column'
		    },
		    colors:['#77a1e5', '#c42525', '#a6c96a'],		    
		    title: {
		        text: 'Processing Overhead of Proposal Method'
		    },
		    subtitle: {
		        text: 'Processing Overhead comparison of Proposal Method and Khari & Kumar Method'
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
		    series: ".$this->getloginsuccesshybrid()."
		});
		",'embed');
		// $loginlist=$this->homedb->loginlist(3);
		// print_r($loginlist);
		$this->template->set_layout('default');
		$this->template->load_view('chart');
	}
	function showdata(){
		print_r($this->getloginsuccessallscheme());
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

	function getlog($act,$scheme,$stat,$bit=128,$num=3){
		$arr=$this->homedb->userlog($act,$scheme,$stat,$bit,$num);
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
	function getlogaesavg($act,$scheme,$stat,$bit=128,$num=3){
		$arr=$this->homedb->userlogaesavg($act,$scheme,$stat,$bit,$num);
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
	function getlogavg($act,$scheme,$stat,$bit=128,$num=3){
		$arr=$this->homedb->userlogaesavg($act,$scheme,$stat,$bit,$num);
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
		$data['avg']=$this->getlogaesavg('login aes success user128',3,'1');
		$data['min']=$this->getlogaesmin('login aes success user128',3,'1');
		$data['max']=$this->getlogaesmax('login aes success user128',3,'1');
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
		$b=$this->homedb->avg_timelog('login aes success user128',3,'1');
		$c=$this->homedb->avg_timelog('login aes success user192',3,'1');
		$d=$this->homedb->avg_timelog('login aes success user256',3,'1');
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
			array('name'=>'Proposed Method',
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
		
			array('name'=>'aes',
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
			array('name'=>'Proposed Method',
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
			
			array('name'=>'aes',
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
			array('name'=>'Proposed Method',
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
		$b_success=$this->getlog('login aes success user128',3,'1',128,3);
		$c_success=$this->getlog('login aes success user192',3,'1',192,3);
		$d_success=$this->getlog('login aes success user256',3,'1',256,3);
		$e_success=$this->getlog('hash success',4,'1');
		$f_success=$this->getlog('hybrid success',5,'1');
		// $c_success=$this->getlog('aes success',3,'1');
		// $d_success=$this->getlog('aes success',3,'1');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			array('name'=>'AES128','data'=>$b_success),
			array('name'=>'AES192','data'=>$c_success),
			array('name'=>'AES256','data'=>$d_success),
			array('name'=>'Hash','data'=>$e_success),
			array('name'=>'Proposed Method','data'=>$f_success),
			// array('name'=>'Hash Login','data'=>$c_success),
			// array('name'=>'Hybrid Login (Proposed)','data'=>$d_success),
			);
		return (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function getloginsuccessaesavg(){
		$b_success=$this->getlogaesavg('login aes128',3,'1');
		$c_success=$this->getlogaesavg('login aes192',3,'1');
		$d_success=$this->getlogaesavg('login aes256',3,'1');
		// $d_success=$this->getlog('login aes success user',5,'1');

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
	function getloginsuccessaes($num=3){
		$b_success=$this->getlog('login aes128',3,'1',128,$num);
		$c_success=$this->getlog('login aes192',3,'1',192,$num);
		$d_success=$this->getlog('login aes256',3,'1',256,$num);
		// $d_success=$this->getlog('login aes success user',5,'1');

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
		$b_success=$this->getlogaesavg('login aes success user128_3',3,'1',128,3);
		// $x_success=$this->getlogaesavg('login aes success user128_4',3,'1',128,4);
		// $j_success=$this->getlogaesavg('login aes success user128_9',3,'1',128,9);
		// $x_success=$this->getlogaesmax('login aes success user128_01',3,'1');
		$c_success=$this->getlogaesavg('login aes success user192_3',3,'1',192,3);
		// $y_success=$this->getlogaesavg('login aes success user192_4',3,'1',192,4);
		// $k_success=$this->getlogaesavg('login aes success user192_9',3,'1',192,9);
		// $y_success=$this->getlogaesmax('login aes success user192_01',3,'1');
		$d_success=$this->getlogaesavg('login aes success user256_3',3,'1',256,3);
		// $z_success=$this->getlogaesavg('login aes success user256_4',3,'1',256,4);
		// $l_success=$this->getlogaesavg('login aes success user256_9',3,'1',256,9);
		// $z_success=$this->getlogaesmax('login aes success user256_01',3,'1');
		// $d_success=$this->getlog('login aes success user',5,'1');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'AES128','data'=>$b_success),
			// array('name'=>'AES128 4','data'=>$x_success),
			// array('name'=>'AES128 9','data'=>$j_success),
			array('name'=>'AES192','data'=>$c_success),
			// array('name'=>'AES192 4','data'=>$y_success),
			// array('name'=>'AES192 9','data'=>$k_success),
			array('name'=>'AES256','data'=>$d_success),
			// array('name'=>'AES256 4','data'=>$z_success),
			// array('name'=>'AES256 9','data'=>$l_success),
			// array('name'=>'Hybrid Login (Proposed)','data'=>$d_success),
			);
		return (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function getloginsuccessallscheme(){
		$a_success=$this->getlog('data success',3,'1',128,3);
		$b_success=$this->getlog('login hash 128 3 digit data',4,'1',128,3);
		$c_success=$this->getlog('login hybrid 128 3 digit data',5,'1',128,3);
		// $d_success=$this->getlog('login aes success user',5,'1');
		array_splice($a_success,0,4);
		array_splice($b_success,0,4);
		array_splice($c_success,0,4);
		// unset($a_success[0]);
		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'AES','data'=>$a_success),
			array('name'=>'Hash','data'=>$b_success),
			array('name'=>'Proposed Method','data'=>$c_success),
			// array('name'=>'Hybrid Login (Proposed)','data'=>$d_success),
			);
		return (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function getloginsuccessallscheme1(){
		$a_success=$this->getlog('data success',3,'1',128,3);
		$b_success=$this->getlog('login hash 128 3 digit data',4,'1',128,3);
		$c_success=$this->getlog('login hybrid 128 3 digit data',5,'1',128,3);
		// $d_success=$this->getlog('login aes success user',5,'1');
		// array_splice($a_success,0,4);
		// array_splice($b_success,0,4);
		// array_splice($c_success,0,4);
		// unset($a_success[0]);
		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'AES','data'=>$a_success),
			array('name'=>'Hash','data'=>$b_success),
			array('name'=>'Proposed Method','data'=>$c_success),
			// array('name'=>'Hybrid Login (Proposed)','data'=>$d_success),
			);
		return (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function getloginsuccesshybrid(){
		$a_success=$this->getlog('login hybrid 128 3 digit data',5,'1',128,3);
		$b_success=$this->getlog('login hybrid 128 4 digit data',5,'1',128,4);
		// $c_success=$this->getlog('login hybrid 128 6 digit data',5,'1',128,6);
		// $d_success=$this->getlog('login hybrid 128 9 digit data',5,'1',128,9);
		// $d_success=$this->getlog('login aes success user',5,'1');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'Proposed Method','data'=>$a_success),
			array('name'=>'Khari & Kumar Method','data'=>$b_success),
			// array('name'=>'6 Digit','data'=>$c_success),
			// array('name'=>'9 Digit','data'=>$d_success),
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
			array('name'=>'Proposed Method Login','data'=>$d_failed),
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