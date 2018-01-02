
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
				redirect('simpleauth/login2');
				// User is NOT logged in
			}
		// $this->load->view('simple_view');
	}
	function logout(){
		$this->myauth->logout();
		redirect('home');
	}
public function _example_output($output = null)
	{
		// $this->load->view('example.php',(array)$output);
		$this->template->load_view('grocery/example',(array)$output);
	}
	function users(){
		if (!($this->myauth->is_loggedin())){
			redirect('simpleauth/login2');
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
	/*[{
                    name: 'Manufacturing',
                    data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
                }, {
                    name: 'Sales & Distribution',
                    data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387]
                }, {
                    name: 'Project Development',
                    data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227]
                }, {
                    name: 'Other',
                    data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
                }
                ]*/
             /*   [
                {"name":"Login Failed","data":["0.0840051","0.00300002","0.00300002","0.00300002"
,"0.00499988","0.00300097","0.00300002","0.00399995","0.00299978"]},{"name":"Login Success","data":["0
.00300097","0.00400019"]}]*/
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
                        text: 'Microtime'
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
                        text: 'Microtime'
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
		        text: 'Microtime succes login execution '
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
		            text: 'Microtime'
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
		        text: 'Microtime succes login execution '
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
		            text: 'Microtime'
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
		",'embed');
		// $loginlist=$this->homedb->loginlist(3);
		// print_r($loginlist);
		$this->template->set_layout('default');
		$this->template->load_view('chart');
	}/*

	series: [{'name':'Simple','data':{'min':0.003000020980835,'max':0.004000186920166,'avg':0.0034002304077148
}}]
	[{
		        name: 'Simple',
		        data: [12, 12, 12]

		    }, {
		        name: 'Secret Key',
		        data: [83.6, 78.8, 98.5]

		    }, {
		        name: 'Hash',
		        data: [48.9, 38.8, 39.3]

		    }, {
		        name: 'Hybrid (Proposed)',
		        data: [42.4, 33.2, 34.5]

		    }]*/

	function cekarray(){
		$arr=array(
			array('name'=>'a','data'=>array('121',123,334123,123,123,123,123)),
			array('name'=>'b','data'=>array('121',123,334123,123,)),
			array('name'=>'c','data'=>array('121',123,334123,123,123)),
			array('name'=>'d','data'=>array('121',123,334123,123,123,123)),
			);
		return json_encode($arr,JSON_NUMERIC_CHECK);
		// $a_success=$this->getlog('login',2,'1');
		
		// $a_failed=$this->getlog('login',2,'0');
		// print_r(json_encode($a_success));
	}
	function getlogs(){
		// $arr=$this->homedb->userlog('login',4,'1');
		// print_r($arr);
		// print_r($this->getlog('login',4,'1'));
		echo $this->getdata();
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
	function getavg(){
		$a=$this->homedb->avg_timelog('login',2,'1');
		$b=$this->homedb->avg_timelog('login',3,'1');
		$c=$this->homedb->avg_timelog('login',4,'1');
		$d=$this->homedb->avg_timelog('login',5,'1');
		$arr=array(
			array('name'=>'Simple',
				'data'=>array(
					0=>$a[0]['mintime'],
					1=>$a[0]['maxtime'],
					2=>$a[0]['avgtime'])
					),
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
	function getfailedavg(){
		$a=$this->homedb->avg_timelog('login',2,'0');
		$b=$this->homedb->avg_timelog('login',3,'0');
		$c=$this->homedb->avg_timelog('login',4,'0');
		$d=$this->homedb->avg_timelog('login',5,'0');
		$arr=array(
			array('name'=>'Simple',
				'data'=>array(
					0=>$a[0]['mintime'],
					1=>$a[0]['maxtime'],
					2=>$a[0]['avgtime'])
					),
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
	function getdata(){
		$a_success=$this->getlog('login',2,'1');
		$b_success=$this->getlog('login',3,'1');
		$c_success=$this->getlog('login',4,'1');
		$d_success=$this->getlog('login',5,'1');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'Simple Login','data'=>$a_success),
			array('name'=>'Secret Login','data'=>$b_success),
			array('name'=>'Hash Login','data'=>$c_success),
			array('name'=>'Hybrid Login (Proposed)','data'=>$d_success),
			);
		return (str_replace('"',"'",json_encode($arr,JSON_NUMERIC_CHECK)));
	}
	function getloginfailed(){
		$a_failed=$this->getlog('login',2,'0');
		$b_failed=$this->getlog('login',3,'0');
		$c_failed=$this->getlog('login',4,'0');
		$d_failed=$this->getlog('login',5,'0');

		// $a_failed=$this->getlog('login',2,'0');
		$arr=array(
			// array('name'=>'Login Failed','data'=>$a_failed),
			array('name'=>'Simple Login','data'=>$a_failed),
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