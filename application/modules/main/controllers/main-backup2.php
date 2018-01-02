<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Main extends MX_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library(array('MyAuth','MyAES'));

		$this->load->model('logger_model','logdb',TRUE); //load model/table 'logger_model' file in model directory
		$this->load->model('secretkey_model','keydb',TRUE);
		$this->load->model('hash_model','hashdb',TRUE);
		$this->load->model('hybrid_model','hibridb',TRUE);
	}
	public function index()
	{
		if ($this->myauth->is_loggedin()){
			redirect('home');
		}else{
			$this->login();
		}
	}
	function loginform($msg=null){
		$token=$this->myauth->generate_tokenreg();		//generate token
		$token64=base64_encode($token); 				//encode token with base64
		$this->session->set_userdata(array('token'=>$token)); //set session token to validate token
		$this->template->load_view('login',array( //load view component 'login.php'
				'title'=>'Login User', //send this string to view
				'post'=>base_url('main/login'), //login form will be post to this url http://localhost/!!cisaleem/main/login
				'reg'=>base_url('main/register'), //register form url --> http://localhost/!!cisaleem/main/register
				'token'=>$token64,
				'msg'=>$msg,
				));
	}
	function login(){
		$submit=$this->input->post('submit',TRUE);			//input submit, true value is xss filtering
		if(isset($submit)){									//if form have submitted
			$password=$this->input->post('password',TRUE);	//input password
			$username=$this->input->post('username',TRUE);	//input username
			$token=$this->input->post('token',TRUE);		//input token
			$decode64=base64_decode($token);				//decode token from base64
			$sestoken=$this->session->userdata('token');	//get real token in session

			$this->form_validation->set_rules('username','Username', 'required|trim|xss_clean');	//validate username input
	        $this->form_validation->set_rules('password','Password','required|trim|xss_clean|min_length[6]|max_length[6]');		//validate password input
	        $this->form_validation->set_rules('token','token','required|trim|xss_clean');			//validate tokenkey
	      		/*print($decode64);
						print($username);
						print($password);
						print($sestoken);*/
			if ($this->form_validation->run() == FALSE){
			  	$this->session->set_flashdata(validation_errors());  
			  	$msg=base64_encode(validation_errors());
				// echo "validation failed"; 
				$this->loginform($msg);
			}else{
				// echo "validation success"; 
				if((isset($decode64)||!empty($decode64)) && ($sestoken==$decode64)){
					//token validation is success
					$this->loginsecretkey($username,$password);
				}else{
					//token validation is failed
					$msg=base64_encode("Wrong security token");		//send this to view
					$this->loginform($msg);
				}
			}
		}else{					//else form not submitted
		
			$this->loginform();
		}
	}
	function testloginsecretx(){
		$this->loginsecretkey('user128_01','@use01');

	}
	function testloginsecret1(){
		$this->loginsecretkey('user128_01','@use01x');
		$this->loginsecretkey('user128_02','@use02x');
		$this->loginsecretkey('user128_03','@use03x');
		$this->loginsecretkey('user128_04','@use04x');
		$this->loginsecretkey('user128_05','@use05x');
		$this->loginsecretkey('user128_06','@use06x');
		$this->loginsecretkey('user128_07','@use07x');
		$this->loginsecretkey('user128_08','@use08x');
		$this->loginsecretkey('user128_09','@use09x');
		$this->loginsecretkey('user128_10','@use10x');
		$this->loginsecretkey('user128_11','@use11x');
		$this->loginsecretkey('user128_12','@use12x');
		$this->loginsecretkey('user128_13','@use13x');
		$this->loginsecretkey('user128_14','@use14x');
		$this->loginsecretkey('user128_15','@use15x');
		$this->loginsecretkey('user128_16','@use16x');
		$this->loginsecretkey('user128_17','@use17x');
		$this->loginsecretkey('user128_18','@use18x');
		$this->loginsecretkey('user128_19','@use19x');
		$this->loginsecretkey('user128_20','@use20x');
		$this->loginsecretkey('user128_21','@use21x');
		$this->loginsecretkey('user128_22','@use22x');
		$this->loginsecretkey('user128_23','@use23x');
		$this->loginsecretkey('user128_24','@use24x');
		$this->loginsecretkey('user128_25','@use25x');
		$this->loginsecretkey('user128_26','@use26x');
		$this->loginsecretkey('user128_27','@use27x');
		$this->loginsecretkey('user128_28','@use28x');
		$this->loginsecretkey('user128_29','@use29x');
		$this->loginsecretkey('user128_30','@use30x');
		$this->loginsecretkey('user128_31','@use31x');
		$this->loginsecretkey('user128_32','@use32x');
		$this->loginsecretkey('user128_33','@use33x');
		$this->loginsecretkey('user128_34','@use34x');
		$this->loginsecretkey('user128_35','@use35x');
		$this->loginsecretkey('user128_36','@use36x');
		$this->loginsecretkey('user128_37','@use37x');
		$this->loginsecretkey('user128_38','@use38x');
		$this->loginsecretkey('user128_39','@use39x');
		$this->loginsecretkey('user128_40','@use40x');
		$this->loginsecretkey('user128_41','@use41x');
		$this->loginsecretkey('user128_42','@use42x');
		$this->loginsecretkey('user128_43','@use43x');
		$this->loginsecretkey('user128_44','@use44x');
		$this->loginsecretkey('user128_45','@use45x');
		$this->loginsecretkey('user128_46','@use46x');
		$this->loginsecretkey('user128_47','@use47x');
		$this->loginsecretkey('user128_48','@use48x');
		$this->loginsecretkey('user128_49','@use49x');
		$this->loginsecretkey('user128_50','@use50x');


	}
	function testloginsecret2(){
		$this->loginsecretkey('user192_01','@use01');
		$this->loginsecretkey('user192_02','@use02');
		$this->loginsecretkey('user192_03','@use03');
		$this->loginsecretkey('user192_04','@use04');
		$this->loginsecretkey('user192_05','@use05');
		$this->loginsecretkey('user192_06','@use06');
		$this->loginsecretkey('user192_07','@use07');
		$this->loginsecretkey('user192_08','@use08');
		$this->loginsecretkey('user192_09','@use09');
		$this->loginsecretkey('user192_10','@use10');
		$this->loginsecretkey('user192_11','@use11');
		$this->loginsecretkey('user192_12','@use12');
		$this->loginsecretkey('user192_13','@use13');
		$this->loginsecretkey('user192_14','@use14');
		$this->loginsecretkey('user192_15','@use15');
		$this->loginsecretkey('user192_16','@use16');
		$this->loginsecretkey('user192_17','@use17');
		$this->loginsecretkey('user192_18','@use18');
		$this->loginsecretkey('user192_19','@use19');
		$this->loginsecretkey('user192_20','@use20');
		$this->loginsecretkey('user192_21','@use21');
		$this->loginsecretkey('user192_22','@use22');
		$this->loginsecretkey('user192_23','@use23');
		$this->loginsecretkey('user192_24','@use24');
		$this->loginsecretkey('user192_25','@use25');
		$this->loginsecretkey('user192_26','@use26');
		$this->loginsecretkey('user192_27','@use27');
		$this->loginsecretkey('user192_28','@use28');
		$this->loginsecretkey('user192_29','@use29');
		$this->loginsecretkey('user192_30','@use30');
		$this->loginsecretkey('user192_31','@use31');
		$this->loginsecretkey('user192_32','@use32');
		$this->loginsecretkey('user192_33','@use33');
		$this->loginsecretkey('user192_34','@use34');
		$this->loginsecretkey('user192_35','@use35');
		$this->loginsecretkey('user192_36','@use36');
		$this->loginsecretkey('user192_37','@use37');
		$this->loginsecretkey('user192_38','@use38');
		$this->loginsecretkey('user192_39','@use39');
		$this->loginsecretkey('user192_40','@use40');
		$this->loginsecretkey('user192_41','@use41');
		$this->loginsecretkey('user192_42','@use42');
		$this->loginsecretkey('user192_43','@use43');
		$this->loginsecretkey('user192_44','@use44');
		$this->loginsecretkey('user192_45','@use45');
		$this->loginsecretkey('user192_46','@use46');
		$this->loginsecretkey('user192_47','@use47');
		$this->loginsecretkey('user192_48','@use48');
		$this->loginsecretkey('user192_49','@use49');
		$this->loginsecretkey('user192_50','@use50');

	}
	function testloginsecretkey($username,$password){
		$start=start();
		$this->myaes->size(128);	//setup AES Encryption 128bit
		if($this->keydb->username_exists($username)==TRUE){
			$userdetail=$this->keydb->getuser($username);		//get  user detail	
			$tokendb=$userdetail['tokenkey'];
			$token=$this->myauth->generate_tokenreg();	
			$enc_username=$userdetail['enc_username'];
			$enc_password=$userdetail['enc_password'];
			$sql=$this->keydb->login($username,$password,$enc_username,$enc_password);

		}else{
			$msg=base64_encode('Username does not exist'); //msg variabel send to view
			$this->loginform($msg);
		}
	
		$data=array(
			'tokendb'=>$tokendb,
			'username'=>$username,
			'password'=>$password,
			'enc_username'=>$enc_username,
			'enc_password'=>$enc_password,
			'sql'=>$sql,

		);
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
	function loginsecretkey($username,$password){
		$start=start();
		$this->myaes->size(128);	//setup AES Encryption 128bit
		if($this->keydb->username_exists($username)==TRUE){
			$userdetail=$this->keydb->getuser($username);		//get  user detail	
			$tokendb=$userdetail['tokenkey'];
			$token=$this->myauth->gentokenreg($username);	
			$enc_username=$userdetail['enc_username'];
			$enc_password=$userdetail['enc_password'];
			
			if($this->keydb->login($username,$password,$enc_username,$enc_password)==TRUE){
				$this->session->set_userdata(array(
						'identifier'=> $userdetail['identifier'], 	//setup session identifier
						'username' 	=> $userdetail['username'],		//setup session username
						'logged_in' => $_SERVER['REQUEST_TIME']		//setup session logged_in
					));
				
				$this->logdb->log('login secretkey success '.$username,endtime($start),'1',3);
				// redirect('home');
				return TRUE;
			}else{
				$this->session->unset_userdata('identifier');  //unset session identifier
				$this->session->unset_userdata('username');  //unset session username
				$this->session->unset_userdata('logged_in');  //unset session logged_in
				
				$this->logdb->log('login secretkey failed '.$username,endtime($start),'0',3);
				// $msg=base64_encode('Encrypted Username/Password Wrong'); //msg variabel send to view
				// $this->loginform($msg);
				return FALSE;
			}
		}else{
			$this->logdb->log('login secretkey failed'.$username,endtime($start),'0',9);
			// $msg=base64_encode('Username does not exist'); //msg variabel send to view
			// $this->loginform($msg);
			return FALSE;
		}
	}
	function loginsecretkeyx($username,$password){
		
		$start=start(); 	//start time logger begin here
		if($this->keydb->username_exists($username)==TRUE){	//if username exist in table
			$userdetail=$this->keydb->getuser($username);		//get  user detail	
			$this->myaes->size(128);	//setup AES Encryption 128bit
			$token=$userdetail['tokenkey'];			//user detail tokenkey
			$enc_user=$userdetail['enc_username'];	//user detail encrypted username
			$enc_pass=$userdetail['enc_password'];	//user detail encrypted password
			$enc_username=$this->myaes->enc($username,$token); 	//encrypt username using token salt
			$enc_password=$this->myaes->enc($password,$token); 	//encrypt password using token salt	
			$dec_username=$this->myaes->dec($enc_username,$token); 	//decrypt enc_username using token salt
			$dec_password=$this->myaes->dec($enc_password,$token); 	//decrypt enc_password using token salt
		
			if((!empty($username)||isset($username))&&(!empty($password)||isset($password))){
				//check up login at users table using these parameters
				$query=$this->keydb->login($username,$password,$enc_user,$enc_pass);
				// echo $this->db->last_query();
				if($query==TRUE){
					// return TRUE;
					$this->session->set_userdata(array(
						'identifier' => $userdetail['identifier'], 	//setup session identifier
						'username' => $userdetail['username'],		//setup session username
						'logged_in' => $_SERVER['REQUEST_TIME']		//setup session logged_in
					));
					// $log=endtime($start);
					$this->logdb->log('login secretkey success',endtime($start),'1',3);
					//end of logger time save 'success' to logger table with these paramters
					// return TRUE;
					redirect('home'); //if success will redirect to 'home'
				}else{
					$this->session->unset_userdata('identifier');  //unset session identifier
					$this->session->unset_userdata('username');  //unset session username
					$this->session->unset_userdata('logged_in');  //unset session logged_in
					$msg=base64_encode('Username/Password Wrong'); //send this message to view
					// $log=endtime($start);
					$this->logdb->log('login secretkey failed',endtime($start),'0',3); //end of logger time save 'failed' to logger table with these paramters
				}
			}else{
					$msg=base64_encode('Username/Password Empty'); //msg variabel send to view
					$this->logdb->log('login secretkey failed',endtime($start),'0',3); //end of logger time save 'failed' to logger table with these paramters
			}
			if(!empty($msg)||isset($msg)){
				$this->template->load_view('login',array(
					'title'=>'Login Scheme with SecretKey', //title variabel send to view
					'post'=>base_url('secretkey/login1'), //this form will be post to url http://localhost/!!cisaleem/secretkey/login1
					'reg'=>base_url('secretkey/register'),//register url http://localhost/!!cisaleem/secretkey/register
					'msg'=>$msg, //send this string to view component
					));
			}
		}else{
			$this->logdb->log('login secretkey failed',endtime($start),'0',3); //end of logger time save 'failed' to logger table with these parameters
			$this->template->load_view('login',array(
					'title'=>'Login Scheme with SecretKey', //title variabel send to view
					'post'=>base_url('secretkey/login1'), //this form will be post to url http://localhost/!!cisaleem/secretkey/login1
					'reg'=>base_url('secretkey/register'), //register url http://localhost/!!cisaleem/secretkey/register
					'msg'=>base64_encode('Username does not exist'), //msg variabel send to view
					));
		}
		// print_r($this->ci->session->userdata('identifier'));
	}
	function cobatestuserloginsecretkey(){

		$this->testuserloginsecretkey('user128_01','@use01');

	}
	function testuserloginsecretkey($username,$password){
		$start=start();
		$this->myaes->size(128);	//setup AES Encryption 128bit
		if($this->keydb->username_exists($username)==TRUE){
			$userdetail=$this->keydb->getuser($username);		//get  user detail	
			$tokendb=$userdetail['tokenkey'];
			$token=$this->myauth->gentokenreg($username);	
			$enc_username=$this->myaes->enc($username,$token); 	//encrypt username using token salt
			$enc_usernametoken=$this->myaes->enc($username,$tokendb); 	//encrypt username using token salt
			$enc_usernamedb=$userdetail['enc_username'];
			$dec_usernamedb=$this->myaes->dec($userdetail['enc_username'],$tokendb);
			$dec_username=$this->myaes->dec($userdetail['enc_username'],$token);
			$enc_password=$this->myaes->enc($password,$token); 	//encrypt username using token salt
			$enc_passwordtoken=$this->myaes->enc($password,$tokendb); 	//encrypt username using token salt
			$enc_passworddb=$userdetail['enc_password'];
			$dec_password=$this->myaes->dec($userdetail['enc_password'],$token);
			$dec_passworddb=$this->myaes->dec($userdetail['enc_password'],$tokendb);
			$dec_inputusernamedb=$this->myaes->dec($enc_usernametoken,$tokendb);
			$dec_inputpassworddb=$this->myaes->dec($enc_passwordtoken,$tokendb);
			$sql=$this->keydb->login($username,$password,$enc_usernametoken,$enc_passwordtoken);
		}else{
			$msg=base64_encode('Username does not exist'); //msg variabel send to view
			$this->loginform($msg);
		}
		$datax=array(
			'token on db'=>$tokendb,
			'token generated'=>$token,
			'input username'=>$username,
			'input password'=>$password,
			
			'encrypt username using generated token'=>$enc_username,
			'encrypted username on db'=>$enc_usernamedb,
			'encrypt username using token on db'=>$enc_usernametoken,
		
			'encrypt password using generated token'=>$enc_password,
			'encrypted password on db'=>$enc_passworddb,
			'encrypt password using token on db'=>$enc_passwordtoken,
		

			'dec_username'=>$dec_username,
			'dec_usernamedb'=>$dec_usernamedb,
			'dec_password'=>$dec_password,
			'dec_passworddb'=>$dec_passworddb,
			'sql'=>$sql,

		);
		$data=array(
			'tokendb'=>$tokendb,
			'username'=>$username,
			'password'=>$password,
			'enc_usernamedb'=>$enc_usernamedb,
			'enc_input_username'=>$enc_usernametoken,
			'enc_passworddb'=>$enc_passworddb,	
			'enc_input_password'=>$enc_passwordtoken,
			'dec_input_username'=>$dec_inputusernamedb,
			'dec_input_password'=>$dec_inputpassworddb,
			'sql'=>$sql,

		);
		echo "<pre>";
		print_r($datax);
		echo "</pre>";
	}

	function registerform($ispost=TRUE,$msg=null){
		$token=$this->myauth->generate_tokenreg();	//generate token
		$token64=base64_encode($token); 			//encode token with base64
		$this->session->set_userdata(array('token'=>$token)); //set session token to validate token
		if(isset($ispost)&&$ispost==TRUE){
			$post=base_url('main/register'); //form register post to url --> http://localhost/!!cisaleem/main/register
		}else{
			$post='';
		}
		$this->template->load_view('register',array( //load view component 'register.php'
				'title'=>'Register User', //send this string to view
				'post'=>$post,
				'loginurl'=>base_url('main/login'), //login form will be post to this url http://localhost/!!cisaleem/main/login
				'token'=>$token64,
				'msg'=>$msg,
			));
	}
	function register(){
		$submit=$this->input->post('submit');	
		if(isset($submit)){		//if form have submitted
			$password=$this->input->post('password',TRUE);	//input password
			$username=$this->input->post('username',TRUE);	//input username
			$token=$this->input->post('token',TRUE);		//input token
			$email=$this->input->post('email',TRUE);		//input token
			$decode64=base64_decode($token);				//decode token from base64
			$sestoken=$this->session->userdata('token');	//get real token in session

			$this->form_validation->set_rules('username','Username', 'required|trim|xss_clean');	//validate username input
			$this->form_validation->set_rules('email','Email', 'required|trim|xss_clean');	//validate username input
	        $this->form_validation->set_rules('password','Password','required|trim|xss_clean|min_length[6]|max_length[6]');		//validate password input
	        $this->form_validation->set_rules('token','token','required|trim|xss_clean');			//validate tokenkey
	      		/*print($decode64);
						print($username);
						print($password);
						print($sestoken);*/
			if ($this->form_validation->run() == FALSE){
			  	$this->session->set_flashdata(validation_errors());  
			  	$msg=base64_encode(validation_errors());
				// echo "validation failed"; 
				// echo validation_errors();
				$this->registerform(TRUE,$msg);
			}else{
				$start=start(); 

				// echo "validation success"; 
				if((isset($decode64)||!empty($decode64)) && ($sestoken==$decode64)){
					//token validation is success
					if($this->myauth->create_allscheme_user($username,$email,$password,$token,256,3)==FALSE){
						$msg=base64_encode("Registration Failed!");		//send this to view
						$this->registerform(FALSE,$msg);
						$this->logdb->log('register allscheme failed',endtime($start),'0',9); //end of logger time save 'failed' to logger table 
						// echo "gagal";
					}else{
						// echo "sukses";
						$msg=base64_encode("Registration Success!");		//send this to view
						$this->logdb->log('register allscheme success',endtime($start),'1',9);
						$this->registerform(TRUE,$msg);
					}
				}else{
					//token validation is failed 
					$msg=base64_encode("Wrong security token");		//send this to view
					$this->registerform(TRUE,$msg);
				}
			}
		}else{					//else form not submitted
			$this->registerform();
		}
	}

	function test_reg_batch128(){
		
		
		//test batch #01
		$start=start();
		$token=$this->myauth->gentokenreg('user128_01');
		$this->myauth->create_allscheme_user('user128_01','user128_01@user128.com','@use01',$token,128,3);
		$this->logdb->log('register allscheme success user128_01',endtime($start),'1',9);
		
		//test batch #02
		$start=start();
		$token=$this->myauth->gentokenreg('user128_02');
		$this->myauth->create_allscheme_user('user128_02','user128_02@user128.com','@use02',$token,128,3);
		$this->logdb->log('register allscheme success user128_02',endtime($start),'1',9);
		
		//test batch #03
		$start=start();
		$token=$this->myauth->gentokenreg('user128_03');
		$this->myauth->create_allscheme_user('user128_03','user128_03@user128.com','@use03',$token,128,3);
		$this->logdb->log('register allscheme success user128_03',endtime($start),'1',9);
		
		//test batch #04
		$start=start();
		$token=$this->myauth->gentokenreg('user128_04');
		$this->myauth->create_allscheme_user('user128_04','user128_04@user128.com','@use04',$token,128,3);
		$this->logdb->log('register allscheme success user128_04',endtime($start),'1',9);
		
		//test batch #05
		$start=start();
		$token=$this->myauth->gentokenreg('user128_05');
		$this->myauth->create_allscheme_user('user128_05','user128_05@user128.com','@use05',$token,128,3);
		$this->logdb->log('register allscheme success user128_05',endtime($start),'1',9);
		
		//test batch #06
		$start=start();
		$token=$this->myauth->gentokenreg('user128_06');
		$this->myauth->create_allscheme_user('user128_06','user128_06@user128.com','@use06',$token,128,3);
		$this->logdb->log('register allscheme success user128_06',endtime($start),'1',9);
		
		//test batch #07
		$start=start();
		$token=$this->myauth->gentokenreg('user128_07');
		$this->myauth->create_allscheme_user('user128_07','user128_07@user128.com','@use07',$token,128,3);
		$this->logdb->log('register allscheme success user128_07',endtime($start),'1',9);
		
		//test batch #08
		$start=start();
		$token=$this->myauth->gentokenreg('user128_08');
		$this->myauth->create_allscheme_user('user128_08','user128_08@user128.com','@use08',$token,128,3);
		$this->logdb->log('register allscheme success user128_08',endtime($start),'1',9);
		
		//test batch #09
		$start=start();
		$token=$this->myauth->gentokenreg('user128_09');
		$this->myauth->create_allscheme_user('user128_09','user128_09@user128.com','@use09',$token,128,3);
		$this->logdb->log('register allscheme success user128_09',endtime($start),'1',9);
		
		//test batch #10
		$start=start();
		$token=$this->myauth->gentokenreg('user128_10');
		$this->myauth->create_allscheme_user('user128_10','user128_10@user128.com','@use10',$token,128,3);
		$this->logdb->log('register allscheme success user128_10',endtime($start),'1',9);
		
		//test batch #11
		$start=start();
		$token=$this->myauth->gentokenreg('user128_11');
		$this->myauth->create_allscheme_user('user128_11','user128_11@user128.com','@use11',$token,128,3);
		$this->logdb->log('register allscheme success user128_11',endtime($start),'1',9);
		
		//test batch #12
		$start=start();
		$token=$this->myauth->gentokenreg('user128_12');
		$this->myauth->create_allscheme_user('user128_12','user128_12@user128.com','@use12',$token,128,3);
		$this->logdb->log('register allscheme success user128_12',endtime($start),'1',9);
		
		//test batch #13
		$start=start();
		$token=$this->myauth->gentokenreg('user128_13');
		$this->myauth->create_allscheme_user('user128_13','user128_13@user128.com','@use13',$token,128,3);
		$this->logdb->log('register allscheme success user128_13',endtime($start),'1',9);
		
		//test batch #14
		$start=start();
		$token=$this->myauth->gentokenreg('user128_14');
		$this->myauth->create_allscheme_user('user128_14','user128_14@user128.com','@use14',$token,128,3);
		$this->logdb->log('register allscheme success user128_14',endtime($start),'1',9);
		
		//test batch #15
		$start=start();
		$token=$this->myauth->gentokenreg('user128_15');
		$this->myauth->create_allscheme_user('user128_15','user128_15@user128.com','@use15',$token,128,3);
		$this->logdb->log('register allscheme success user128_15',endtime($start),'1',9);
		
		//test batch #16
		$start=start();
		$token=$this->myauth->gentokenreg('user128_16');
		$this->myauth->create_allscheme_user('user128_16','user128_16@user128.com','@use16',$token,128,3);
		$this->logdb->log('register allscheme success user128_16',endtime($start),'1',9);
		
		//test batch #17
		$start=start();
		$token=$this->myauth->gentokenreg('user128_17');
		$this->myauth->create_allscheme_user('user128_17','user128_17@user128.com','@use17',$token,128,3);
		$this->logdb->log('register allscheme success user128_17',endtime($start),'1',9);
		
		//test batch #18
		$start=start();
		$token=$this->myauth->gentokenreg('user128_18');
		$this->myauth->create_allscheme_user('user128_18','user128_18@user128.com','@use18',$token,128,3);
		$this->logdb->log('register allscheme success user128_18',endtime($start),'1',9);
		
		//test batch #19
		$start=start();
		$token=$this->myauth->gentokenreg('user128_19');
		$this->myauth->create_allscheme_user('user128_19','user128_19@user128.com','@use19',$token,128,3);
		$this->logdb->log('register allscheme success user128_19',endtime($start),'1',9);
		
		//test batch #20
		$start=start();
		$token=$this->myauth->gentokenreg('user128_20');
		$this->myauth->create_allscheme_user('user128_20','user128_20@user128.com','@use20',$token,128,3);
		$this->logdb->log('register allscheme success user128_20',endtime($start),'1',9);
		
		//test batch #21
		$start=start();
		$token=$this->myauth->gentokenreg('user128_21');
		$this->myauth->create_allscheme_user('user128_21','user128_21@user128.com','@use21',$token,128,3);
		$this->logdb->log('register allscheme success user128_21',endtime($start),'1',9);
		
		//test batch #22
		$start=start();
		$token=$this->myauth->gentokenreg('user128_22');
		$this->myauth->create_allscheme_user('user128_22','user128_22@user128.com','@use22',$token,128,3);
		$this->logdb->log('register allscheme success user128_22',endtime($start),'1',9);
		
		//test batch #23
		$start=start();
		$token=$this->myauth->gentokenreg('user128_23');
		$this->myauth->create_allscheme_user('user128_23','user128_23@user128.com','@use23',$token,128,3);
		$this->logdb->log('register allscheme success user128_23',endtime($start),'1',9);
		
		//test batch #24
		$start=start();
		$token=$this->myauth->gentokenreg('user128_24');
		$this->myauth->create_allscheme_user('user128_24','user128_24@user128.com','@use24',$token,128,3);
		$this->logdb->log('register allscheme success user128_24',endtime($start),'1',9);
		
		//test batch #25
		$start=start();
		$token=$this->myauth->gentokenreg('user128_25');
		$this->myauth->create_allscheme_user('user128_25','user128_25@user128.com','@use25',$token,128,3);
		$this->logdb->log('register allscheme success user128_25',endtime($start),'1',9);
		
		//test batch #26
		$start=start();
		$token=$this->myauth->gentokenreg('user128_26');
		$this->myauth->create_allscheme_user('user128_26','user128_26@user128.com','@use26',$token,128,3);
		$this->logdb->log('register allscheme success user128_26',endtime($start),'1',9);
		
		//test batch #27
		$start=start();
		$token=$this->myauth->gentokenreg('user128_27');
		$this->myauth->create_allscheme_user('user128_27','user128_27@user128.com','@use27',$token,128,3);
		$this->logdb->log('register allscheme success user128_27',endtime($start),'1',9);
		
		//test batch #28
		$start=start();
		$token=$this->myauth->gentokenreg('user128_28');
		$this->myauth->create_allscheme_user('user128_28','user128_28@user128.com','@use28',$token,128,3);
		$this->logdb->log('register allscheme success user128_28',endtime($start),'1',9);
		
		//test batch #29
		$start=start();
		$token=$this->myauth->gentokenreg('user128_29');
		$this->myauth->create_allscheme_user('user128_29','user128_29@user128.com','@use29',$token,128,3);
		$this->logdb->log('register allscheme success user128_29',endtime($start),'1',9);
		
		//test batch #30
		$start=start();
		$token=$this->myauth->gentokenreg('user128_30');
		$this->myauth->create_allscheme_user('user128_30','user128_30@user128.com','@use30',$token,128,3);
		$this->logdb->log('register allscheme success user128_30',endtime($start),'1',9);
		
		//test batch #31
		$start=start();
		$token=$this->myauth->gentokenreg('user128_31');
		$this->myauth->create_allscheme_user('user128_31','user128_31@user128.com','@use31',$token,128,3);
		$this->logdb->log('register allscheme success user128_31',endtime($start),'1',9);
		
		//test batch #32
		$start=start();
		$token=$this->myauth->gentokenreg('user128_32');
		$this->myauth->create_allscheme_user('user128_32','user128_32@user128.com','@use32',$token,128,3);
		$this->logdb->log('register allscheme success user128_32',endtime($start),'1',9);
		
		//test batch #33
		$start=start();
		$token=$this->myauth->gentokenreg('user128_33');
		$this->myauth->create_allscheme_user('user128_33','user128_33@user128.com','@use33',$token,128,3);
		$this->logdb->log('register allscheme success user128_33',endtime($start),'1',9);
		
		//test batch #34
		$start=start();
		$token=$this->myauth->gentokenreg('user128_34');
		$this->myauth->create_allscheme_user('user128_34','user128_34@user128.com','@use34',$token,128,3);
		$this->logdb->log('register allscheme success user128_34',endtime($start),'1',9);
		
		//test batch #35
		$start=start();
		$token=$this->myauth->gentokenreg('user128_35');
		$this->myauth->create_allscheme_user('user128_35','user128_35@user128.com','@use35',$token,128,3);
		$this->logdb->log('register allscheme success user128_35',endtime($start),'1',9);
		
		//test batch #36
		$start=start();
		$token=$this->myauth->gentokenreg('user128_36');
		$this->myauth->create_allscheme_user('user128_36','user128_36@user128.com','@use36',$token,128,3);
		$this->logdb->log('register allscheme success user128_36',endtime($start),'1',9);
		
		//test batch #37
		$start=start();
		$token=$this->myauth->gentokenreg('user128_37');
		$this->myauth->create_allscheme_user('user128_37','user128_37@user128.com','@use37',$token,128,3);
		$this->logdb->log('register allscheme success user128_37',endtime($start),'1',9);
		
		//test batch #38
		$start=start();
		$token=$this->myauth->gentokenreg('user128_38');
		$this->myauth->create_allscheme_user('user128_38','user128_38@user128.com','@use38',$token,128,3);
		$this->logdb->log('register allscheme success user128_38',endtime($start),'1',9);
		
		//test batch #39
		$start=start();
		$token=$this->myauth->gentokenreg('user128_39');
		$this->myauth->create_allscheme_user('user128_39','user128_39@user128.com','@use39',$token,128,3);
		$this->logdb->log('register allscheme success user128_39',endtime($start),'1',9);
		
		//test batch #40
		$start=start();
		$token=$this->myauth->gentokenreg('user128_40');
		$this->myauth->create_allscheme_user('user128_40','user128_40@user128.com','@use40',$token,128,3);
		$this->logdb->log('register allscheme success user128_40',endtime($start),'1',9);
		
		//test batch #41
		$start=start();
		$token=$this->myauth->gentokenreg('user128_41');
		$this->myauth->create_allscheme_user('user128_41','user128_41@user128.com','@use41',$token,128,3);
		$this->logdb->log('register allscheme success user128_41',endtime($start),'1',9);
		
		//test batch #42
		$start=start();
		$token=$this->myauth->gentokenreg('user128_42');
		$this->myauth->create_allscheme_user('user128_42','user128_42@user128.com','@use42',$token,128,3);
		$this->logdb->log('register allscheme success user128_42',endtime($start),'1',9);
		
		//test batch #43
		$start=start();
		$token=$this->myauth->gentokenreg('user128_43');
		$this->myauth->create_allscheme_user('user128_43','user128_43@user128.com','@use43',$token,128,3);
		$this->logdb->log('register allscheme success user128_43',endtime($start),'1',9);
		
		//test batch #44
		$start=start();
		$token=$this->myauth->gentokenreg('user128_44');
		$this->myauth->create_allscheme_user('user128_44','user128_44@user128.com','@use44',$token,128,3);
		$this->logdb->log('register allscheme success user128_44',endtime($start),'1',9);
		
		//test batch #45
		$start=start();
		$token=$this->myauth->gentokenreg('user128_45');
		$this->myauth->create_allscheme_user('user128_45','user128_45@user128.com','@use45',$token,128,3);
		$this->logdb->log('register allscheme success user128_45',endtime($start),'1',9);
		
		//test batch #46
		$start=start();
		$token=$this->myauth->gentokenreg('user128_46');
		$this->myauth->create_allscheme_user('user128_46','user128_46@user128.com','@use46',$token,128,3);
		$this->logdb->log('register allscheme success user128_46',endtime($start),'1',9);
		
		//test batch #47
		$start=start();
		$token=$this->myauth->gentokenreg('user128_47');
		$this->myauth->create_allscheme_user('user128_47','user128_47@user128.com','@use47',$token,128,3);
		$this->logdb->log('register allscheme success user128_47',endtime($start),'1',9);
		
		//test batch #48
		$start=start();
		$token=$this->myauth->gentokenreg('user128_48');
		$this->myauth->create_allscheme_user('user128_48','user128_48@user128.com','@use48',$token,128,3);
		$this->logdb->log('register allscheme success user128_48',endtime($start),'1',9);
		
		//test batch #49
		$start=start();
		$token=$this->myauth->gentokenreg('user128_49');
		$this->myauth->create_allscheme_user('user128_49','user128_49@user128.com','@use49',$token,128,3);
		$this->logdb->log('register allscheme success user128_49',endtime($start),'1',9);
		
		//test batch #50
		$start=start();
		$token=$this->myauth->gentokenreg('user128_50');
		$this->myauth->create_allscheme_user('user128_50','user128_50@user128.com','@use50',$token,128,3);
		$this->logdb->log('register allscheme success user128_50',endtime($start),'1',9);

	}

	function test_reg_batch192(){
		$token=$this->myauth->generate_tokenreg();
		
		//test batch #01
		$start=start();
		$this->myauth->create_allscheme_user('user192_01','user192_01@user192.com','@use01',$token,192,3);
		$this->logdb->log('register allscheme success user192_01',endtime($start),'1',9);
		
		//test batch #02
		$start=start();
		$this->myauth->create_allscheme_user('user192_02','user192_02@user192.com','@use02',$token,192,3);
		$this->logdb->log('register allscheme success user192_02',endtime($start),'1',9);
		
		//test batch #03
		$start=start();
		$this->myauth->create_allscheme_user('user192_03','user192_03@user192.com','@use03',$token,192,3);
		$this->logdb->log('register allscheme success user192_03',endtime($start),'1',9);
		
		//test batch #04
		$start=start();
		$this->myauth->create_allscheme_user('user192_04','user192_04@user192.com','@use04',$token,192,3);
		$this->logdb->log('register allscheme success user192_04',endtime($start),'1',9);
		
		//test batch #05
		$start=start();
		$this->myauth->create_allscheme_user('user192_05','user192_05@user192.com','@use05',$token,192,3);
		$this->logdb->log('register allscheme success user192_05',endtime($start),'1',9);
		
		//test batch #06
		$start=start();
		$this->myauth->create_allscheme_user('user192_06','user192_06@user192.com','@use06',$token,192,3);
		$this->logdb->log('register allscheme success user192_06',endtime($start),'1',9);
		
		//test batch #07
		$start=start();
		$this->myauth->create_allscheme_user('user192_07','user192_07@user192.com','@use07',$token,192,3);
		$this->logdb->log('register allscheme success user192_07',endtime($start),'1',9);
		
		//test batch #08
		$start=start();
		$this->myauth->create_allscheme_user('user192_08','user192_08@user192.com','@use08',$token,192,3);
		$this->logdb->log('register allscheme success user192_08',endtime($start),'1',9);
		
		//test batch #09
		$start=start();
		$this->myauth->create_allscheme_user('user192_09','user192_09@user192.com','@use09',$token,192,3);
		$this->logdb->log('register allscheme success user192_09',endtime($start),'1',9);
		
		//test batch #10
		$start=start();
		$this->myauth->create_allscheme_user('user192_10','user192_10@user192.com','@use10',$token,192,3);
		$this->logdb->log('register allscheme success user192_10',endtime($start),'1',9);
		
		//test batch #11
		$start=start();
		$this->myauth->create_allscheme_user('user192_11','user192_11@user192.com','@use11',$token,192,3);
		$this->logdb->log('register allscheme success user192_11',endtime($start),'1',9);
		
		//test batch #12
		$start=start();
		$this->myauth->create_allscheme_user('user192_12','user192_12@user192.com','@use12',$token,192,3);
		$this->logdb->log('register allscheme success user192_12',endtime($start),'1',9);
		
		//test batch #13
		$start=start();
		$this->myauth->create_allscheme_user('user192_13','user192_13@user192.com','@use13',$token,192,3);
		$this->logdb->log('register allscheme success user192_13',endtime($start),'1',9);
		
		//test batch #14
		$start=start();
		$this->myauth->create_allscheme_user('user192_14','user192_14@user192.com','@use14',$token,192,3);
		$this->logdb->log('register allscheme success user192_14',endtime($start),'1',9);
		
		//test batch #15
		$start=start();
		$this->myauth->create_allscheme_user('user192_15','user192_15@user192.com','@use15',$token,192,3);
		$this->logdb->log('register allscheme success user192_15',endtime($start),'1',9);
		
		//test batch #16
		$start=start();
		$this->myauth->create_allscheme_user('user192_16','user192_16@user192.com','@use16',$token,192,3);
		$this->logdb->log('register allscheme success user192_16',endtime($start),'1',9);
		
		//test batch #17
		$start=start();
		$this->myauth->create_allscheme_user('user192_17','user192_17@user192.com','@use17',$token,192,3);
		$this->logdb->log('register allscheme success user192_17',endtime($start),'1',9);
		
		//test batch #18
		$start=start();
		$this->myauth->create_allscheme_user('user192_18','user192_18@user192.com','@use18',$token,192,3);
		$this->logdb->log('register allscheme success user192_18',endtime($start),'1',9);
		
		//test batch #19
		$start=start();
		$this->myauth->create_allscheme_user('user192_19','user192_19@user192.com','@use19',$token,192,3);
		$this->logdb->log('register allscheme success user192_19',endtime($start),'1',9);
		
		//test batch #20
		$start=start();
		$this->myauth->create_allscheme_user('user192_20','user192_20@user192.com','@use20',$token,192,3);
		$this->logdb->log('register allscheme success user192_20',endtime($start),'1',9);
		
		//test batch #21
		$start=start();
		$this->myauth->create_allscheme_user('user192_21','user192_21@user192.com','@use21',$token,192,3);
		$this->logdb->log('register allscheme success user192_21',endtime($start),'1',9);
		
		//test batch #22
		$start=start();
		$this->myauth->create_allscheme_user('user192_22','user192_22@user192.com','@use22',$token,192,3);
		$this->logdb->log('register allscheme success user192_22',endtime($start),'1',9);
		
		//test batch #23
		$start=start();
		$this->myauth->create_allscheme_user('user192_23','user192_23@user192.com','@use23',$token,192,3);
		$this->logdb->log('register allscheme success user192_23',endtime($start),'1',9);
		
		//test batch #24
		$start=start();
		$this->myauth->create_allscheme_user('user192_24','user192_24@user192.com','@use24',$token,192,3);
		$this->logdb->log('register allscheme success user192_24',endtime($start),'1',9);
		
		//test batch #25
		$start=start();
		$this->myauth->create_allscheme_user('user192_25','user192_25@user192.com','@use25',$token,192,3);
		$this->logdb->log('register allscheme success user192_25',endtime($start),'1',9);
		
		//test batch #26
		$start=start();
		$this->myauth->create_allscheme_user('user192_26','user192_26@user192.com','@use26',$token,192,3);
		$this->logdb->log('register allscheme success user192_26',endtime($start),'1',9);
		
		//test batch #27
		$start=start();
		$this->myauth->create_allscheme_user('user192_27','user192_27@user192.com','@use27',$token,192,3);
		$this->logdb->log('register allscheme success user192_27',endtime($start),'1',9);
		
		//test batch #28
		$start=start();
		$this->myauth->create_allscheme_user('user192_28','user192_28@user192.com','@use28',$token,192,3);
		$this->logdb->log('register allscheme success user192_28',endtime($start),'1',9);
		
		//test batch #29
		$start=start();
		$this->myauth->create_allscheme_user('user192_29','user192_29@user192.com','@use29',$token,192,3);
		$this->logdb->log('register allscheme success user192_29',endtime($start),'1',9);
		
		//test batch #30
		$start=start();
		$this->myauth->create_allscheme_user('user192_30','user192_30@user192.com','@use30',$token,192,3);
		$this->logdb->log('register allscheme success user192_30',endtime($start),'1',9);
		
		//test batch #31
		$start=start();
		$this->myauth->create_allscheme_user('user192_31','user192_31@user192.com','@use31',$token,192,3);
		$this->logdb->log('register allscheme success user192_31',endtime($start),'1',9);
		
		//test batch #32
		$start=start();
		$this->myauth->create_allscheme_user('user192_32','user192_32@user192.com','@use32',$token,192,3);
		$this->logdb->log('register allscheme success user192_32',endtime($start),'1',9);
		
		//test batch #33
		$start=start();
		$this->myauth->create_allscheme_user('user192_33','user192_33@user192.com','@use33',$token,192,3);
		$this->logdb->log('register allscheme success user192_33',endtime($start),'1',9);
		
		//test batch #34
		$start=start();
		$this->myauth->create_allscheme_user('user192_34','user192_34@user192.com','@use34',$token,192,3);
		$this->logdb->log('register allscheme success user192_34',endtime($start),'1',9);
		
		//test batch #35
		$start=start();
		$this->myauth->create_allscheme_user('user192_35','user192_35@user192.com','@use35',$token,192,3);
		$this->logdb->log('register allscheme success user192_35',endtime($start),'1',9);
		
		//test batch #36
		$start=start();
		$this->myauth->create_allscheme_user('user192_36','user192_36@user192.com','@use36',$token,192,3);
		$this->logdb->log('register allscheme success user192_36',endtime($start),'1',9);
		
		//test batch #37
		$start=start();
		$this->myauth->create_allscheme_user('user192_37','user192_37@user192.com','@use37',$token,192,3);
		$this->logdb->log('register allscheme success user192_37',endtime($start),'1',9);
		
		//test batch #38
		$start=start();
		$this->myauth->create_allscheme_user('user192_38','user192_38@user192.com','@use38',$token,192,3);
		$this->logdb->log('register allscheme success user192_38',endtime($start),'1',9);
		
		//test batch #39
		$start=start();
		$this->myauth->create_allscheme_user('user192_39','user192_39@user192.com','@use39',$token,192,3);
		$this->logdb->log('register allscheme success user192_39',endtime($start),'1',9);
		
		//test batch #40
		$start=start();
		$this->myauth->create_allscheme_user('user192_40','user192_40@user192.com','@use40',$token,192,3);
		$this->logdb->log('register allscheme success user192_40',endtime($start),'1',9);
		
		//test batch #41
		$start=start();
		$this->myauth->create_allscheme_user('user192_41','user192_41@user192.com','@use41',$token,192,3);
		$this->logdb->log('register allscheme success user192_41',endtime($start),'1',9);
		
		//test batch #42
		$start=start();
		$this->myauth->create_allscheme_user('user192_42','user192_42@user192.com','@use42',$token,192,3);
		$this->logdb->log('register allscheme success user192_42',endtime($start),'1',9);
		
		//test batch #43
		$start=start();
		$this->myauth->create_allscheme_user('user192_43','user192_43@user192.com','@use43',$token,192,3);
		$this->logdb->log('register allscheme success user192_43',endtime($start),'1',9);
		
		//test batch #44
		$start=start();
		$this->myauth->create_allscheme_user('user192_44','user192_44@user192.com','@use44',$token,192,3);
		$this->logdb->log('register allscheme success user192_44',endtime($start),'1',9);
		
		//test batch #45
		$start=start();
		$this->myauth->create_allscheme_user('user192_45','user192_45@user192.com','@use45',$token,192,3);
		$this->logdb->log('register allscheme success user192_45',endtime($start),'1',9);
		
		//test batch #46
		$start=start();
		$this->myauth->create_allscheme_user('user192_46','user192_46@user192.com','@use46',$token,192,3);
		$this->logdb->log('register allscheme success user192_46',endtime($start),'1',9);
		
		//test batch #47
		$start=start();
		$this->myauth->create_allscheme_user('user192_47','user192_47@user192.com','@use47',$token,192,3);
		$this->logdb->log('register allscheme success user192_47',endtime($start),'1',9);
		
		//test batch #48
		$start=start();
		$this->myauth->create_allscheme_user('user192_48','user192_48@user192.com','@use48',$token,192,3);
		$this->logdb->log('register allscheme success user192_48',endtime($start),'1',9);
		
		//test batch #49
		$start=start();
		$this->myauth->create_allscheme_user('user192_49','user192_49@user192.com','@use49',$token,192,3);
		$this->logdb->log('register allscheme success user192_49',endtime($start),'1',9);
		
		//test batch #50
		$start=start();
		$this->myauth->create_allscheme_user('user192_50','user192_50@user192.com','@use50',$token,192,3);
		$this->logdb->log('register allscheme success user192_50',endtime($start),'1',9);

	}
	function test_reg_batch256(){
		$token=$this->myauth->generate_tokenreg();
		
		//test batch #01
		$start=start();
		$this->myauth->create_allscheme_user('user256_01','user256_01@user256.com','@use01',$token,256,3);
		$this->logdb->log('register allscheme success user256_01',endtime($start),'1',9);
		
		//test batch #02
		$start=start();
		$this->myauth->create_allscheme_user('user256_02','user256_02@user256.com','@use02',$token,256,3);
		$this->logdb->log('register allscheme success user256_02',endtime($start),'1',9);
		
		//test batch #03
		$start=start();
		$this->myauth->create_allscheme_user('user256_03','user256_03@user256.com','@use03',$token,256,3);
		$this->logdb->log('register allscheme success user256_03',endtime($start),'1',9);
		
		//test batch #04
		$start=start();
		$this->myauth->create_allscheme_user('user256_04','user256_04@user256.com','@use04',$token,256,3);
		$this->logdb->log('register allscheme success user256_04',endtime($start),'1',9);
		
		//test batch #05
		$start=start();
		$this->myauth->create_allscheme_user('user256_05','user256_05@user256.com','@use05',$token,256,3);
		$this->logdb->log('register allscheme success user256_05',endtime($start),'1',9);
		
		//test batch #06
		$start=start();
		$this->myauth->create_allscheme_user('user256_06','user256_06@user256.com','@use06',$token,256,3);
		$this->logdb->log('register allscheme success user256_06',endtime($start),'1',9);
		
		//test batch #07
		$start=start();
		$this->myauth->create_allscheme_user('user256_07','user256_07@user256.com','@use07',$token,256,3);
		$this->logdb->log('register allscheme success user256_07',endtime($start),'1',9);
		
		//test batch #08
		$start=start();
		$this->myauth->create_allscheme_user('user256_08','user256_08@user256.com','@use08',$token,256,3);
		$this->logdb->log('register allscheme success user256_08',endtime($start),'1',9);
		
		//test batch #09
		$start=start();
		$this->myauth->create_allscheme_user('user256_09','user256_09@user256.com','@use09',$token,256,3);
		$this->logdb->log('register allscheme success user256_09',endtime($start),'1',9);
		
		//test batch #10
		$start=start();
		$this->myauth->create_allscheme_user('user256_10','user256_10@user256.com','@use10',$token,256,3);
		$this->logdb->log('register allscheme success user256_10',endtime($start),'1',9);
		
		//test batch #11
		$start=start();
		$this->myauth->create_allscheme_user('user256_11','user256_11@user256.com','@use11',$token,256,3);
		$this->logdb->log('register allscheme success user256_11',endtime($start),'1',9);
		
		//test batch #12
		$start=start();
		$this->myauth->create_allscheme_user('user256_12','user256_12@user256.com','@use12',$token,256,3);
		$this->logdb->log('register allscheme success user256_12',endtime($start),'1',9);
		
		//test batch #13
		$start=start();
		$this->myauth->create_allscheme_user('user256_13','user256_13@user256.com','@use13',$token,256,3);
		$this->logdb->log('register allscheme success user256_13',endtime($start),'1',9);
		
		//test batch #14
		$start=start();
		$this->myauth->create_allscheme_user('user256_14','user256_14@user256.com','@use14',$token,256,3);
		$this->logdb->log('register allscheme success user256_14',endtime($start),'1',9);
		
		//test batch #15
		$start=start();
		$this->myauth->create_allscheme_user('user256_15','user256_15@user256.com','@use15',$token,256,3);
		$this->logdb->log('register allscheme success user256_15',endtime($start),'1',9);
		
		//test batch #16
		$start=start();
		$this->myauth->create_allscheme_user('user256_16','user256_16@user256.com','@use16',$token,256,3);
		$this->logdb->log('register allscheme success user256_16',endtime($start),'1',9);
		
		//test batch #17
		$start=start();
		$this->myauth->create_allscheme_user('user256_17','user256_17@user256.com','@use17',$token,256,3);
		$this->logdb->log('register allscheme success user256_17',endtime($start),'1',9);
		
		//test batch #18
		$start=start();
		$this->myauth->create_allscheme_user('user256_18','user256_18@user256.com','@use18',$token,256,3);
		$this->logdb->log('register allscheme success user256_18',endtime($start),'1',9);
		
		//test batch #19
		$start=start();
		$this->myauth->create_allscheme_user('user256_19','user256_19@user256.com','@use19',$token,256,3);
		$this->logdb->log('register allscheme success user256_19',endtime($start),'1',9);
		
		//test batch #20
		$start=start();
		$this->myauth->create_allscheme_user('user256_20','user256_20@user256.com','@use20',$token,256,3);
		$this->logdb->log('register allscheme success user256_20',endtime($start),'1',9);
		
		//test batch #21
		$start=start();
		$this->myauth->create_allscheme_user('user256_21','user256_21@user256.com','@use21',$token,256,3);
		$this->logdb->log('register allscheme success user256_21',endtime($start),'1',9);
		
		//test batch #22
		$start=start();
		$this->myauth->create_allscheme_user('user256_22','user256_22@user256.com','@use22',$token,256,3);
		$this->logdb->log('register allscheme success user256_22',endtime($start),'1',9);
		
		//test batch #23
		$start=start();
		$this->myauth->create_allscheme_user('user256_23','user256_23@user256.com','@use23',$token,256,3);
		$this->logdb->log('register allscheme success user256_23',endtime($start),'1',9);
		
		//test batch #24
		$start=start();
		$this->myauth->create_allscheme_user('user256_24','user256_24@user256.com','@use24',$token,256,3);
		$this->logdb->log('register allscheme success user256_24',endtime($start),'1',9);
		
		//test batch #25
		$start=start();
		$this->myauth->create_allscheme_user('user256_25','user256_25@user256.com','@use25',$token,256,3);
		$this->logdb->log('register allscheme success user256_25',endtime($start),'1',9);
		
		//test batch #26
		$start=start();
		$this->myauth->create_allscheme_user('user256_26','user256_26@user256.com','@use26',$token,256,3);
		$this->logdb->log('register allscheme success user256_26',endtime($start),'1',9);
		
		//test batch #27
		$start=start();
		$this->myauth->create_allscheme_user('user256_27','user256_27@user256.com','@use27',$token,256,3);
		$this->logdb->log('register allscheme success user256_27',endtime($start),'1',9);
		
		//test batch #28
		$start=start();
		$this->myauth->create_allscheme_user('user256_28','user256_28@user256.com','@use28',$token,256,3);
		$this->logdb->log('register allscheme success user256_28',endtime($start),'1',9);
		
		//test batch #29
		$start=start();
		$this->myauth->create_allscheme_user('user256_29','user256_29@user256.com','@use29',$token,256,3);
		$this->logdb->log('register allscheme success user256_29',endtime($start),'1',9);
		
		//test batch #30
		$start=start();
		$this->myauth->create_allscheme_user('user256_30','user256_30@user256.com','@use30',$token,256,3);
		$this->logdb->log('register allscheme success user256_30',endtime($start),'1',9);
		
		//test batch #31
		$start=start();
		$this->myauth->create_allscheme_user('user256_31','user256_31@user256.com','@use31',$token,256,3);
		$this->logdb->log('register allscheme success user256_31',endtime($start),'1',9);
		
		//test batch #32
		$start=start();
		$this->myauth->create_allscheme_user('user256_32','user256_32@user256.com','@use32',$token,256,3);
		$this->logdb->log('register allscheme success user256_32',endtime($start),'1',9);
		
		//test batch #33
		$start=start();
		$this->myauth->create_allscheme_user('user256_33','user256_33@user256.com','@use33',$token,256,3);
		$this->logdb->log('register allscheme success user256_33',endtime($start),'1',9);
		
		//test batch #34
		$start=start();
		$this->myauth->create_allscheme_user('user256_34','user256_34@user256.com','@use34',$token,256,3);
		$this->logdb->log('register allscheme success user256_34',endtime($start),'1',9);
		
		//test batch #35
		$start=start();
		$this->myauth->create_allscheme_user('user256_35','user256_35@user256.com','@use35',$token,256,3);
		$this->logdb->log('register allscheme success user256_35',endtime($start),'1',9);
		
		//test batch #36
		$start=start();
		$this->myauth->create_allscheme_user('user256_36','user256_36@user256.com','@use36',$token,256,3);
		$this->logdb->log('register allscheme success user256_36',endtime($start),'1',9);
		
		//test batch #37
		$start=start();
		$this->myauth->create_allscheme_user('user256_37','user256_37@user256.com','@use37',$token,256,3);
		$this->logdb->log('register allscheme success user256_37',endtime($start),'1',9);
		
		//test batch #38
		$start=start();
		$this->myauth->create_allscheme_user('user256_38','user256_38@user256.com','@use38',$token,256,3);
		$this->logdb->log('register allscheme success user256_38',endtime($start),'1',9);
		
		//test batch #39
		$start=start();
		$this->myauth->create_allscheme_user('user256_39','user256_39@user256.com','@use39',$token,256,3);
		$this->logdb->log('register allscheme success user256_39',endtime($start),'1',9);
		
		//test batch #40
		$start=start();
		$this->myauth->create_allscheme_user('user256_40','user256_40@user256.com','@use40',$token,256,3);
		$this->logdb->log('register allscheme success user256_40',endtime($start),'1',9);
		
		//test batch #41
		$start=start();
		$this->myauth->create_allscheme_user('user256_41','user256_41@user256.com','@use41',$token,256,3);
		$this->logdb->log('register allscheme success user256_41',endtime($start),'1',9);
		
		//test batch #42
		$start=start();
		$this->myauth->create_allscheme_user('user256_42','user256_42@user256.com','@use42',$token,256,3);
		$this->logdb->log('register allscheme success user256_42',endtime($start),'1',9);
		
		//test batch #43
		$start=start();
		$this->myauth->create_allscheme_user('user256_43','user256_43@user256.com','@use43',$token,256,3);
		$this->logdb->log('register allscheme success user256_43',endtime($start),'1',9);
		
		//test batch #44
		$start=start();
		$this->myauth->create_allscheme_user('user256_44','user256_44@user256.com','@use44',$token,256,3);
		$this->logdb->log('register allscheme success user256_44',endtime($start),'1',9);
		
		//test batch #45
		$start=start();
		$this->myauth->create_allscheme_user('user256_45','user256_45@user256.com','@use45',$token,256,3);
		$this->logdb->log('register allscheme success user256_45',endtime($start),'1',9);
		
		//test batch #46
		$start=start();
		$this->myauth->create_allscheme_user('user256_46','user256_46@user256.com','@use46',$token,256,3);
		$this->logdb->log('register allscheme success user256_46',endtime($start),'1',9);
		
		//test batch #47
		$start=start();
		$this->myauth->create_allscheme_user('user256_47','user256_47@user256.com','@use47',$token,256,3);
		$this->logdb->log('register allscheme success user256_47',endtime($start),'1',9);
		
		//test batch #48
		$start=start();
		$this->myauth->create_allscheme_user('user256_48','user256_48@user256.com','@use48',$token,256,3);
		$this->logdb->log('register allscheme success user256_48',endtime($start),'1',9);
		
		//test batch #49
		$start=start();
		$this->myauth->create_allscheme_user('user256_49','user256_49@user256.com','@use49',$token,256,3);
		$this->logdb->log('register allscheme success user256_49',endtime($start),'1',9);
		
		//test batch #50
		$start=start();
		$this->myauth->create_allscheme_user('user256_50','user256_50@user256.com','@use50',$token,256,3);
		$this->logdb->log('register allscheme success user256_50',endtime($start),'1',9);

	}

}

/* End of file main.php */
/* Location: ./application/modules/main/controllers/main.php */

 ?>