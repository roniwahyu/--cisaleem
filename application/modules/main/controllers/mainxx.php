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
		$this->load->model('hybrid_model','hydb',TRUE);
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
		$token=$this->myauth->gentokenreg();		//generate token
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
	function loginsecretkeyform($msg=null){
		$token=$this->myauth->gentokenreg();		//generate token
		$token64=base64_encode($token); 				//encode token with base64
		$this->session->set_userdata(array('token'=>$token)); //set session token to validate token
		$this->template->load_view('login',array( //load view component 'login.php'
				'title'=>'Login User', //send this string to view
				'post'=>base_url('main/login/secretkey'), //login form will be post to this url http://localhost/!!cisaleem/main/login
				'reg'=>base_url('main/register'), //register form url --> http://localhost/!!cisaleem/main/register
				'token'=>$token64,
				'msg'=>$msg,
				));
	}
	function loginhashform($msg=null){
		$token=$this->myauth->gentokenreg();		//generate token
		$token64=base64_encode($token); 				//encode token with base64
		$this->session->set_userdata(array('token'=>$token)); //set session token to validate token
		$this->template->load_view('login',array( //load view component 'login.php'
				'title'=>'Login User', //send this string to view
				'post'=>base_url('main/login/hash'), //login form will be post to this url http://localhost/!!cisaleem/main/login
				'reg'=>base_url('main/register'), //register form url --> http://localhost/!!cisaleem/main/register
				'token'=>$token64,
				'msg'=>$msg,
				));
	}
	function loginhybridform($msg=null){
		$token=$this->myauth->gentokenreg();		//generate token
		$token64=base64_encode($token); 				//encode token with base64
		$this->session->set_userdata(array('token'=>$token)); //set session token to validate token
		$this->template->load_view('login',array( //load view component 'login.php'
				'title'=>'Login User', //send this string to view
				'post'=>base_url('main/login/hybrid'), //login form will be post to this url http://localhost/!!cisaleem/main/login
				'reg'=>base_url('main/register'), //register form url --> http://localhost/!!cisaleem/main/register
				'token'=>$token64,
				'msg'=>$msg,
				));
	}

	//login process
	function login($scheme=null){
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
			//if validation is false
			if ($this->form_validation->run() == FALSE){
			  	$this->session->set_flashdata(validation_errors());  
			  	$msg=base64_encode(validation_errors());
				// echo "validation failed"; 
				$this->loginform($msg);
			}else{	//validation is true
				// echo "validation success"; 
				if((isset($decode64)||!empty($decode64)) && ($sestoken==$decode64)){	//decode tokenkey
					//token validation is success
					if(!empty($scheme)||$scheme!=null){		//login scheme
						switch ($scheme) {
							case 'secretkey':	//login secretkey scheme 
								# code...
									$this->loginsecretkey($username,$password);
								break;
							case 'hybrid':
								# code...
									$this->loginhybrid($username,$password);
								break;
							case 'hash':
								# code...
									$this->loginhash($username,$password);
								break;
							default:
									$this->loginsecretkey($username,$password);
							break;
						}
					}
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
	function loginsecretkey($username,$password,$test=FALSE){
		$start=start();
//		$this->myaes->size(128);	//setup AES Encryption 128bit
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
				if($test==FALSE){
					redirect('home');
				}else{
					return TRUE;
				}
			}else{
				$this->session->unset_userdata('identifier');  //unset session identifier
				$this->session->unset_userdata('username');  //unset session username
				$this->session->unset_userdata('logged_in');  //unset session logged_in
				
				$this->logdb->log('login secretkey failed '.$username,endtime($start),'0',3);
				$msg=base64_encode('Encrypted Username/Password Wrong'); //msg variabel send to view
				if($test==FALSE){
					$this->loginsecretkeyform($msg);
				}else{
					return FALSE;
				}

			}
		}else{
			$this->logdb->log('login secretkey failed'.$username,endtime($start),'0',3);
			$msg=base64_encode('Username does not exist'); //msg variabel send to view
			if($test==FALSE){
					$this->loginsecretkeyform($msg);
				}else{
					return FALSE;
				}
		}
	}
	function loginhash($username,$password,$test=FALSE){
				
		$start=start(); //start time logger begin here
		if($this->hashdb->username_exists($username)==TRUE){ //if username exist in table
			$userdetail=$this->hashdb->getuser($username); //get  user detail
			
				$username_hash=md5($username); //password hash concatenate with salt
				$password_hash=md5($password);//username hash concatenate with salt
				// print_r($username_hash);
				// $query=$this->hashdb->login($username,$password,$username_hash,$password_hash); 
				//check up login at users table using these parameters
				if($this->hashdb->login($username,$password,$username_hash,$password_hash)==TRUE){
					$this->session->set_userdata(array(
							'identifier'=> $userdetail['identifier'], 	//setup session identifier
							'username' 	=> $userdetail['username'],		//setup session username
							'logged_in' => $_SERVER['REQUEST_TIME']		//setup session logged_in
						));
					
					$this->logdb->log('login hash success '.$username,endtime($start),'1',4);
					if($test==FALSE){
						redirect('home');
					}else{
						return TRUE;
					}
				}else{
					$this->session->unset_userdata('identifier');  //unset session identifier
					$this->session->unset_userdata('username');  //unset session username
					$this->session->unset_userdata('logged_in');  //unset session logged_in
					
					$this->logdb->log('login hash failed '.$username,endtime($start),'0',4);
					$msg=base64_encode('Encrypted Username/Password Wrong'); //msg variabel send to view
					if($test==FALSE){
						$this->loginhashform($msg);
					}else{
						return FALSE;
					}
				}			
		}else{
				$this->logdb->log('login hash failed '.$username,endtime($start),'0',4);//end of logger time save 'failed' to logger table with these paramters
				$msg=base64_encode('Username is not exist');
				if($test==FALSE){
						$this->loginhashform($msg);
					}else{
						return FALSE;
					}
		}
	}
	function loginhybrid($username,$password,$test=FALSE){
		$start=start(); //start time logger begin here
		if($this->hydb->username_exists($username,5)==TRUE){ //if username exist in table
			$userdetail=$this->hydb->getuser($username); //get  user detail
			
				$salted=$userdetail['salt_after']; //random salt that saved on the user table 
				$username_hashsalt=md5($username).$salted; //password hash concatenate with salt
				$password_hashsalt=md5($password).$salted;//username hash concatenate with salt
				// print_r($username_hashsalt);
				//check up login at users table using these parameters
				if($this->hydb->login($username,$password,$username_hashsalt,$password_hashsalt)==TRUE){
					// return TRUE;
					$this->session->set_userdata(array(
						'identifier' => $userdetail['identifier'], //set session identifier
						'username' => $userdetail['username'], //set session username
						'logged_in' => $_SERVER['REQUEST_TIME'] //set session request time
					));
					// $log=endtime($start);
					$this->logdb->log('login hybrid success '.$username,endtime($start),'1',5); //end of logger time save 'success' to logger table with these paramters
					if($test==FALSE){
						redirect('home'); //if success will redirect to 'home'
					}else{
						return TRUE;
					}
				}else{
					$this->session->unset_userdata('identifier'); //unset session identifier
					$this->session->unset_userdata('username'); //unset session username
					$this->session->unset_userdata('logged_in'); //unset session logged_in
					$msg=base64_encode('Username/Password Wrong');
					// $log=endtime($start);
					$this->logdb->log('login hybrid failed '.$username,endtime($start),'0',5); //end of logger time save 'failed' to logger table with these paramters
					if($test==FALSE){
						$this->loginhybridform($msg);
					}else{
						return FALSE;
					}
				}
			
		}else{
				$msg=base64_encode('Username is not exist');
				$this->logdb->log('login hybrid failed '.$username,endtime($start),'0',5);//end of logger time save 'failed' to logger table with these paramters
				if($test==FALSE){
						$this->loginhybridform($msg);
					}else{
						return FALSE;
					}
		
		}
	}
	function testloginsecretx(){
		$this->loginsecretkey('user128_01','@use01');

	}
	function testloginsecret1(){
		$start=start();
		//0-10
		$this->loginsecretkey('user128_01','@use01');echo "batch #1</br>";
		$this->loginsecretkey('user128_02','@use02');echo "batch #2</br>";
		$this->loginsecretkey('user128_03','@use03');echo "batch #3</br>";
		$this->loginsecretkey('user128_04','@use04');echo "batch #4</br>";
		$this->loginsecretkey('user128_05','@use05');echo "batch #5</br>";
		$this->loginsecretkey('user128_06','@use06');echo "batch #6</br>";
		$this->loginsecretkey('user128_07','@use07');echo "batch #7</br>";
		$this->loginsecretkey('user128_08','@use08');echo "batch #8</br>";
		$this->loginsecretkey('user128_09','@use09');echo "batch #9</br>";
		$this->loginsecretkey('user128_10','@use10');echo "batch #10</br>";
		$this->logdb->log('login secretkey aes128 10 data success ',endtime($start),'1',3);
		//10 - 20
		$this->loginsecretkey('user128_11','@use11');echo "batch #11</br>";
		$this->loginsecretkey('user128_12','@use12');echo "batch #12</br>";
		$this->loginsecretkey('user128_13','@use13');echo "batch #13</br>";
		$this->loginsecretkey('user128_14','@use14');echo "batch #14</br>";
		$this->loginsecretkey('user128_15','@use15');echo "batch #15</br>";
		$this->loginsecretkey('user128_16','@use16');echo "batch #16</br>";
		$this->loginsecretkey('user128_17','@use17');echo "batch #17</br>";
		$this->loginsecretkey('user128_18','@use18');echo "batch #18</br>";
		$this->loginsecretkey('user128_19','@use19');echo "batch #19</br>";
		$this->loginsecretkey('user128_20','@use20');echo "batch #20</br>";
		$this->logdb->log('login secretkey aes128 20 data success ',endtime($start),'1',3);
		//20 - 30
		$this->loginsecretkey('user128_21','@use21');echo "batch #21</br>";
		$this->loginsecretkey('user128_22','@use22');echo "batch #22</br>";
		$this->loginsecretkey('user128_23','@use23');echo "batch #23</br>";
		$this->loginsecretkey('user128_24','@use24');echo "batch #24</br>";
		$this->loginsecretkey('user128_25','@use25');echo "batch #25</br>";
		$this->loginsecretkey('user128_26','@use26');echo "batch #26</br>";
		$this->loginsecretkey('user128_27','@use27');echo "batch #27</br>";
		$this->loginsecretkey('user128_28','@use28');echo "batch #28</br>";
		$this->loginsecretkey('user128_29','@use29');echo "batch #29</br>";
		$this->loginsecretkey('user128_30','@use30');echo "batch #30</br>";
		$this->logdb->log('login secretkey aes128 30 data success ',endtime($start),'1',3);
		//30 - 40
		$this->loginsecretkey('user128_31','@use31');echo "batch #31</br>";
		$this->loginsecretkey('user128_32','@use32');echo "batch #32</br>";
		$this->loginsecretkey('user128_33','@use33');echo "batch #33</br>";
		$this->loginsecretkey('user128_34','@use34');echo "batch #34</br>";
		$this->loginsecretkey('user128_35','@use35');echo "batch #35</br>";
		$this->loginsecretkey('user128_36','@use36');echo "batch #36</br>";
		$this->loginsecretkey('user128_37','@use37');echo "batch #37</br>";
		$this->loginsecretkey('user128_38','@use38');echo "batch #38</br>";
		$this->loginsecretkey('user128_39','@use39');echo "batch #39</br>";
		$this->loginsecretkey('user128_40','@use40');echo "batch #40</br>";
		$this->logdb->log('login secretkey aes128 40 data success ',endtime($start),'1',3);
		//40 - 50
		$this->loginsecretkey('user128_41','@use41');echo "batch #41</br>";
		$this->loginsecretkey('user128_42','@use42');echo "batch #42</br>";
		$this->loginsecretkey('user128_43','@use43');echo "batch #43</br>";
		$this->loginsecretkey('user128_44','@use44');echo "batch #44</br>";
		$this->loginsecretkey('user128_45','@use45');echo "batch #45</br>";
		$this->loginsecretkey('user128_46','@use46');echo "batch #46</br>";
		$this->loginsecretkey('user128_47','@use47');echo "batch #47</br>";
		$this->loginsecretkey('user128_48','@use48');echo "batch #48</br>";
		$this->loginsecretkey('user128_49','@use49');echo "batch #49</br>";
		$this->loginsecretkey('user128_50','@use50');echo "batch #50</br>";
		$this->logdb->log('login secretkey aes128 50 data success ',endtime($start),'1',3);


	}
	function testloginsecret2(){
		$start=start();
		//0 - 10
		$this->loginsecretkey('user192_01','@use01');echo "batch #1</br>";
		$this->loginsecretkey('user192_02','@use02');echo "batch #2</br>";
		$this->loginsecretkey('user192_03','@use03');echo "batch #3</br>";
		$this->loginsecretkey('user192_04','@use04');echo "batch #4</br>";
		$this->loginsecretkey('user192_05','@use05');echo "batch #5</br>";
		$this->loginsecretkey('user192_06','@use06');echo "batch #6</br>";
		$this->loginsecretkey('user192_07','@use07');echo "batch #7</br>";
		$this->loginsecretkey('user192_08','@use08');echo "batch #8</br>";
		$this->loginsecretkey('user192_09','@use09');echo "batch #9</br>";
		$this->loginsecretkey('user192_10','@use10');echo "batch #10</br>";
		$this->loginsecretkey('user192_11','@use11');echo "batch #11</br>";
		//10 - 20
		$this->logdb->log('login secretkey aes192 10 data success ',endtime($start),'1',3);
		$this->loginsecretkey('user192_12','@use12');echo "batch #12</br>";
		$this->loginsecretkey('user192_13','@use13');echo "batch #13</br>";
		$this->loginsecretkey('user192_14','@use14');echo "batch #14</br>";
		$this->loginsecretkey('user192_15','@use15');echo "batch #15</br>";
		$this->loginsecretkey('user192_16','@use16');echo "batch #16</br>";
		$this->loginsecretkey('user192_17','@use17');echo "batch #17</br>";
		$this->loginsecretkey('user192_18','@use18');echo "batch #18</br>";
		$this->loginsecretkey('user192_19','@use19');echo "batch #19</br>";
		$this->loginsecretkey('user192_20','@use20');echo "batch #20</br>";
		$this->loginsecretkey('user192_21','@use21');echo "batch #21</br>";
		//20 - 30
		$this->logdb->log('login secretkey aes192 20 data success ',endtime($start),'1',3);
		$this->loginsecretkey('user192_22','@use22');echo "batch #22</br>";
		$this->loginsecretkey('user192_23','@use23');echo "batch #23</br>";
		$this->loginsecretkey('user192_24','@use24');echo "batch #24</br>";
		$this->loginsecretkey('user192_25','@use25');echo "batch #25</br>";
		$this->loginsecretkey('user192_26','@use26');echo "batch #26</br>";
		$this->loginsecretkey('user192_27','@use27');echo "batch #27</br>";
		$this->loginsecretkey('user192_28','@use28');echo "batch #28</br>";
		$this->loginsecretkey('user192_29','@use29');echo "batch #29</br>";
		$this->loginsecretkey('user192_30','@use30');echo "batch #30</br>";
		$this->loginsecretkey('user192_31','@use31');echo "batch #31</br>";
		//30 - 40
		$this->logdb->log('login secretkey aes192 30 data success ',endtime($start),'1',3);
		$this->loginsecretkey('user192_32','@use32');echo "batch #32</br>";
		$this->loginsecretkey('user192_33','@use33');echo "batch #33</br>";
		$this->loginsecretkey('user192_34','@use34');echo "batch #34</br>";
		$this->loginsecretkey('user192_35','@use35');echo "batch #35</br>";
		$this->loginsecretkey('user192_36','@use36');echo "batch #36</br>";
		$this->loginsecretkey('user192_37','@use37');echo "batch #37</br>";
		$this->loginsecretkey('user192_38','@use38');echo "batch #38</br>";
		$this->loginsecretkey('user192_39','@use39');echo "batch #39</br>";
		$this->loginsecretkey('user192_40','@use40');echo "batch #40</br>";
		$this->loginsecretkey('user192_41','@use41');echo "batch #41</br>";
		//40 - 50
		$this->logdb->log('login secretkey aes192 40 data success ',endtime($start),'1',3);
		$this->loginsecretkey('user192_42','@use42');echo "batch #42</br>";
		$this->loginsecretkey('user192_43','@use43');echo "batch #43</br>";
		$this->loginsecretkey('user192_44','@use44');echo "batch #44</br>";
		$this->loginsecretkey('user192_45','@use45');echo "batch #45</br>";
		$this->loginsecretkey('user192_46','@use46');echo "batch #46</br>";
		$this->loginsecretkey('user192_47','@use47');echo "batch #47</br>";
		$this->loginsecretkey('user192_48','@use48');echo "batch #48</br>";
		$this->loginsecretkey('user192_49','@use49');echo "batch #49</br>";
		$this->loginsecretkey('user192_50','@use50');echo "batch #50</br>";
		$this->logdb->log('login secretkey aes192 50 data success ',endtime($start),'1',3);
	}
	function testloginsecret3(){
		$start=start();
		//0 - 10
		$this->loginsecretkey('user256_01','@use01');echo "batch #1</br>";
		$this->loginsecretkey('user256_02','@use02');echo "batch #2</br>";
		$this->loginsecretkey('user256_03','@use03');echo "batch #3</br>";
		$this->loginsecretkey('user256_04','@use04');echo "batch #4</br>";
		$this->loginsecretkey('user256_05','@use05');echo "batch #5</br>";
		$this->loginsecretkey('user256_06','@use06');echo "batch #6</br>";
		$this->loginsecretkey('user256_07','@use07');echo "batch #7</br>";
		$this->loginsecretkey('user256_08','@use08');echo "batch #8</br>";
		$this->loginsecretkey('user256_09','@use09');echo "batch #9</br>";
		$this->loginsecretkey('user256_10','@use10');echo "batch #10</br>";
		$this->loginsecretkey('user256_11','@use11');echo "batch #11</br>";
		//10 - 20
		$this->logdb->log('login secretkey aes256 10 data success ',endtime($start),'1',3);
		$this->loginsecretkey('user256_12','@use12');echo "batch #12</br>";
		$this->loginsecretkey('user256_13','@use13');echo "batch #13</br>";
		$this->loginsecretkey('user256_14','@use14');echo "batch #14</br>";
		$this->loginsecretkey('user256_15','@use15');echo "batch #15</br>";
		$this->loginsecretkey('user256_16','@use16');echo "batch #16</br>";
		$this->loginsecretkey('user256_17','@use17');echo "batch #17</br>";
		$this->loginsecretkey('user256_18','@use18');echo "batch #18</br>";
		$this->loginsecretkey('user256_19','@use19');echo "batch #19</br>";
		$this->loginsecretkey('user256_20','@use20');echo "batch #20</br>";
		$this->loginsecretkey('user256_21','@use21');echo "batch #21</br>";
		//20 - 30
		$this->logdb->log('login secretkey aes256 20 data success ',endtime($start),'1',3);
		$this->loginsecretkey('user256_22','@use22');echo "batch #22</br>";
		$this->loginsecretkey('user256_23','@use23');echo "batch #23</br>";
		$this->loginsecretkey('user256_24','@use24');echo "batch #24</br>";
		$this->loginsecretkey('user256_25','@use25');echo "batch #25</br>";
		$this->loginsecretkey('user256_26','@use26');echo "batch #26</br>";
		$this->loginsecretkey('user256_27','@use27');echo "batch #27</br>";
		$this->loginsecretkey('user256_28','@use28');echo "batch #28</br>";
		$this->loginsecretkey('user256_29','@use29');echo "batch #29</br>";
		$this->loginsecretkey('user256_30','@use30');echo "batch #30</br>";
		$this->loginsecretkey('user256_31','@use31');echo "batch #31</br>";
		//30 - 40
		$this->logdb->log('login secretkey aes256 30 data success ',endtime($start),'1',3);
		$this->loginsecretkey('user256_32','@use32');echo "batch #32</br>";
		$this->loginsecretkey('user256_33','@use33');echo "batch #33</br>";
		$this->loginsecretkey('user256_34','@use34');echo "batch #34</br>";
		$this->loginsecretkey('user256_35','@use35');echo "batch #35</br>";
		$this->loginsecretkey('user256_36','@use36');echo "batch #36</br>";
		$this->loginsecretkey('user256_37','@use37');echo "batch #37</br>";
		$this->loginsecretkey('user256_38','@use38');echo "batch #38</br>";
		$this->loginsecretkey('user256_39','@use39');echo "batch #39</br>";
		$this->loginsecretkey('user256_40','@use40');echo "batch #40</br>";
		$this->loginsecretkey('user256_41','@use41');echo "batch #41</br>";
		//40 - 50
		$this->logdb->log('login secretkey aes256 40 data success ',endtime($start),'1',3);
		$this->loginsecretkey('user256_42','@use42');echo "batch #42</br>";
		$this->loginsecretkey('user256_43','@use43');echo "batch #43</br>";
		$this->loginsecretkey('user256_44','@use44');echo "batch #44</br>";
		$this->loginsecretkey('user256_45','@use45');echo "batch #45</br>";
		$this->loginsecretkey('user256_46','@use46');echo "batch #46</br>";
		$this->loginsecretkey('user256_47','@use47');echo "batch #47</br>";
		$this->loginsecretkey('user256_48','@use48');echo "batch #48</br>";
		$this->loginsecretkey('user256_49','@use49');echo "batch #49</br>";
		$this->loginsecretkey('user256_50','@use50');echo "batch #50</br>";
		$this->logdb->log('login secretkey aes256 50 data success ',endtime($start),'1',3);
	}
	function testloginhash(){
		$start=start();
		//0 - 10
		$this->loginhash('user128_01','@use01');echo "batch #1</br>";
		$this->loginhash('user128_02','@use02');echo "batch #2</br>";
		$this->loginhash('user128_03','@use03');echo "batch #3</br>";
		$this->loginhash('user128_04','@use04');echo "batch #4</br>";
		$this->loginhash('user128_05','@use05');echo "batch #5</br>";
		$this->loginhash('user128_06','@use06');echo "batch #6</br>";
		$this->loginhash('user128_07','@use07');echo "batch #7</br>";
		$this->loginhash('user128_08','@use08');echo "batch #8</br>";
		$this->loginhash('user128_09','@use09');echo "batch #9</br>";
		$this->loginhash('user128_10','@use10');echo "batch #10</br>";
		$this->loginhash('user128_11','@use11');echo "batch #11</br>";
		//10 - 20
		$this->logdb->log('login hash data 10 success ',endtime($start),'1',4);
		$this->loginhash('user192_12','@use12');echo "batch #12</br>";
		$this->loginhash('user192_13','@use13');echo "batch #13</br>";
		$this->loginhash('user192_14','@use14');echo "batch #14</br>";
		$this->loginhash('user192_15','@use15');echo "batch #15</br>";
		$this->loginhash('user192_16','@use16');echo "batch #16</br>";
		$this->loginhash('user192_17','@use17');echo "batch #17</br>";
		$this->loginhash('user192_18','@use18');echo "batch #18</br>";
		$this->loginhash('user192_19','@use19');echo "batch #19</br>";
		$this->loginhash('user192_20','@use20');echo "batch #20</br>";
		$this->loginhash('user192_21','@use21');echo "batch #21</br>";
		//20 - 30
		$this->logdb->log('login hash data 20 success ',endtime($start),'1',4);
		$this->loginhash('user256_22','@use22');echo "batch #22</br>";
		$this->loginhash('user256_23','@use23');echo "batch #23</br>";
		$this->loginhash('user256_24','@use24');echo "batch #24</br>";
		$this->loginhash('user256_25','@use25');echo "batch #25</br>";
		$this->loginhash('user256_26','@use26');echo "batch #26</br>";
		$this->loginhash('user256_27','@use27');echo "batch #27</br>";
		$this->loginhash('user256_28','@use28');echo "batch #28</br>";
		$this->loginhash('user256_29','@use29');echo "batch #29</br>";
		$this->loginhash('user256_30','@use30');echo "batch #30</br>";
		$this->loginhash('user256_31','@use31');echo "batch #31</br>";
		//30 - 40
		$this->logdb->log('login hash data 30 success ',endtime($start),'1',4);
		$this->loginhash('user128_32','@use32');echo "batch #32</br>";
		$this->loginhash('user128_33','@use33');echo "batch #33</br>";
		$this->loginhash('user128_34','@use34');echo "batch #34</br>";
		$this->loginhash('user128_35','@use35');echo "batch #35</br>";
		$this->loginhash('user128_36','@use36');echo "batch #36</br>";
		$this->loginhash('user128_37','@use37');echo "batch #37</br>";
		$this->loginhash('user128_38','@use38');echo "batch #38</br>";
		$this->loginhash('user128_39','@use39');echo "batch #39</br>";
		$this->loginhash('user128_40','@use40');echo "batch #40</br>";
		$this->loginhash('user128_41','@use41');echo "batch #41</br>";
		//40 - 50
		$this->logdb->log('login hash data 40 success ',endtime($start),'1',4);
		$this->loginhash('user256_42','@use42');echo "batch #42</br>";
		$this->loginhash('user256_43','@use43');echo "batch #43</br>";
		$this->loginhash('user256_44','@use44');echo "batch #44</br>";
		$this->loginhash('user256_45','@use45');echo "batch #45</br>";
		$this->loginhash('user256_46','@use46');echo "batch #46</br>";
		$this->loginhash('user256_47','@use47');echo "batch #47</br>";
		$this->loginhash('user256_48','@use48');echo "batch #48</br>";
		$this->loginhash('user256_49','@use49');echo "batch #49</br>";
		$this->loginhash('user256_50','@use50');echo "batch #50</br>";
		$this->logdb->log('login hash data 50 success ',endtime($start),'1',4);
	}
	function testloginhybrid(){
		$start=start();
		//0 - 10
		$this->loginhybrid('user128_01','@use01');echo "batch #1</br>";
		$this->loginhybrid('user128_02','@use02');echo "batch #2</br>";
		$this->loginhybrid('user128_03','@use03');echo "batch #3</br>";
		$this->loginhybrid('user128_04','@use04');echo "batch #4</br>";
		$this->loginhybrid('user128_05','@use05');echo "batch #5</br>";
		$this->loginhybrid('user128_06','@use06');echo "batch #6</br>";
		$this->loginhybrid('user128_07','@use07');echo "batch #7</br>";
		$this->loginhybrid('user128_08','@use08');echo "batch #8</br>";
		$this->loginhybrid('user128_09','@use09');echo "batch #9</br>";
		$this->loginhybrid('user128_10','@use10');echo "batch #10</br>";
		$this->loginhybrid('user128_11','@use11');echo "batch #11</br>";
		//10 - 20
		$this->logdb->log('login hybrid data 10 success ',endtime($start),'1',5);
		$this->loginhybrid('user192_12','@use12');echo "batch #12</br>";
		$this->loginhybrid('user192_13','@use13');echo "batch #13</br>";
		$this->loginhybrid('user192_14','@use14');echo "batch #14</br>";
		$this->loginhybrid('user192_15','@use15');echo "batch #15</br>";
		$this->loginhybrid('user192_16','@use16');echo "batch #16</br>";
		$this->loginhybrid('user192_17','@use17');echo "batch #17</br>";
		$this->loginhybrid('user192_18','@use18');echo "batch #18</br>";
		$this->loginhybrid('user192_19','@use19');echo "batch #19</br>";
		$this->loginhybrid('user192_20','@use20');echo "batch #20</br>";
		$this->loginhybrid('user192_21','@use21');echo "batch #21</br>";
		//20 - 30
		$this->logdb->log('login hybrid data 20 success ',endtime($start),'1',5);
		$this->loginhybrid('user256_22','@use22');echo "batch #22</br>";
		$this->loginhybrid('user256_23','@use23');echo "batch #23</br>";
		$this->loginhybrid('user256_24','@use24');echo "batch #24</br>";
		$this->loginhybrid('user256_25','@use25');echo "batch #25</br>";
		$this->loginhybrid('user256_26','@use26');echo "batch #26</br>";
		$this->loginhybrid('user256_27','@use27');echo "batch #27</br>";
		$this->loginhybrid('user256_28','@use28');echo "batch #28</br>";
		$this->loginhybrid('user256_29','@use29');echo "batch #29</br>";
		$this->loginhybrid('user256_30','@use30');echo "batch #30</br>";
		$this->loginhybrid('user256_31','@use31');echo "batch #31</br>";
		//30 - 40
		$this->logdb->log('login hybrid data 30 success ',endtime($start),'1',5);
		$this->loginhybrid('user128_32','@use32');echo "batch #32</br>";
		$this->loginhybrid('user128_33','@use33');echo "batch #33</br>";
		$this->loginhybrid('user128_34','@use34');echo "batch #34</br>";
		$this->loginhybrid('user128_35','@use35');echo "batch #35</br>";
		$this->loginhybrid('user128_36','@use36');echo "batch #36</br>";
		$this->loginhybrid('user128_37','@use37');echo "batch #37</br>";
		$this->loginhybrid('user128_38','@use38');echo "batch #38</br>";
		$this->loginhybrid('user128_39','@use39');echo "batch #39</br>";
		$this->loginhybrid('user128_40','@use40');echo "batch #40</br>";
		$this->loginhybrid('user128_41','@use41');echo "batch #41</br>";
		//40 - 50
		$this->logdb->log('login hybrid data 40 success ',endtime($start),'1',5);
		$this->loginhybrid('user256_42','@use42');echo "batch #42</br>";
		$this->loginhybrid('user256_43','@use43');echo "batch #43</br>";
		$this->loginhybrid('user256_44','@use44');echo "batch #44</br>";
		$this->loginhybrid('user256_45','@use45');echo "batch #45</br>";
		$this->loginhybrid('user256_46','@use46');echo "batch #46</br>";
		$this->loginhybrid('user256_47','@use47');echo "batch #47</br>";
		$this->loginhybrid('user256_48','@use48');echo "batch #48</br>";
		$this->loginhybrid('user256_49','@use49');echo "batch #49</br>";
		$this->loginhybrid('user256_50','@use50');echo "batch #50</br>";
		$this->logdb->log('login hybrid data 50 success ',endtime($start),'1',5);
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
	function testtoken(){
		$token=$this->myauth->generate_tokenreg();

		print($token);
		print(strlen($token));
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
		// $token=$this->myauth->generate_tokenreg();
		
		//test batch #01
		$start=start();
		$token=$this->myauth->gentokenreg('user192_01');
		$this->myauth->create_allscheme_user('user192_01','user192_01@user192.com','@use01',$token,192,3);
		$this->logdb->log('register allscheme success user192_01',endtime($start),'1',9);
		
		//test batch #02
		$start=start();
		$token=$this->myauth->gentokenreg('user192_02');
		$this->myauth->create_allscheme_user('user192_02','user192_02@user192.com','@use02',$token,192,3);
		$this->logdb->log('register allscheme success user192_02',endtime($start),'1',9);
		
		//test batch #03
		$start=start();
		$token=$this->myauth->gentokenreg('user192_03');
		$this->myauth->create_allscheme_user('user192_03','user192_03@user192.com','@use03',$token,192,3);
		$this->logdb->log('register allscheme success user192_03',endtime($start),'1',9);
		
		//test batch #04
		$start=start();
		$token=$this->myauth->gentokenreg('user192_04');
		$this->myauth->create_allscheme_user('user192_04','user192_04@user192.com','@use04',$token,192,3);
		$this->logdb->log('register allscheme success user192_04',endtime($start),'1',9);
		
		//test batch #05
		$start=start();
		$token=$this->myauth->gentokenreg('user192_05');
		$this->myauth->create_allscheme_user('user192_05','user192_05@user192.com','@use05',$token,192,3);
		$this->logdb->log('register allscheme success user192_05',endtime($start),'1',9);
		
		//test batch #06
		$start=start();
		$token=$this->myauth->gentokenreg('user192_06');
		$this->myauth->create_allscheme_user('user192_06','user192_06@user192.com','@use06',$token,192,3);
		$this->logdb->log('register allscheme success user192_06',endtime($start),'1',9);
		
		//test batch #07
		$start=start();
		$token=$this->myauth->gentokenreg('user192_07');
		$this->myauth->create_allscheme_user('user192_07','user192_07@user192.com','@use07',$token,192,3);
		$this->logdb->log('register allscheme success user192_07',endtime($start),'1',9);
		
		//test batch #08
		$start=start();
		$token=$this->myauth->gentokenreg('user192_08');
		$this->myauth->create_allscheme_user('user192_08','user192_08@user192.com','@use08',$token,192,3);
		$this->logdb->log('register allscheme success user192_08',endtime($start),'1',9);
		
		//test batch #09
		$start=start();
		$token=$this->myauth->gentokenreg('user192_09');
		$this->myauth->create_allscheme_user('user192_09','user192_09@user192.com','@use09',$token,192,3);
		$this->logdb->log('register allscheme success user192_09',endtime($start),'1',9);
		
		//test batch #10
		$start=start();
		$token=$this->myauth->gentokenreg('user192_10');
		$this->myauth->create_allscheme_user('user192_10','user192_10@user192.com','@use10',$token,192,3);
		$this->logdb->log('register allscheme success user192_10',endtime($start),'1',9);
		
		//test batch #11
		$start=start();
		$token=$this->myauth->gentokenreg('user192_11');
		$this->myauth->create_allscheme_user('user192_11','user192_11@user192.com','@use11',$token,192,3);
		$this->logdb->log('register allscheme success user192_11',endtime($start),'1',9);
		
		//test batch #12
		$start=start();
		$token=$this->myauth->gentokenreg('user192_12');
		$this->myauth->create_allscheme_user('user192_12','user192_12@user192.com','@use12',$token,192,3);
		$this->logdb->log('register allscheme success user192_12',endtime($start),'1',9);
		
		//test batch #13
		$start=start();
		$token=$this->myauth->gentokenreg('user192_13');
		$this->myauth->create_allscheme_user('user192_13','user192_13@user192.com','@use13',$token,192,3);
		$this->logdb->log('register allscheme success user192_13',endtime($start),'1',9);
		
		//test batch #14
		$start=start();
		$token=$this->myauth->gentokenreg('user192_14');
		$this->myauth->create_allscheme_user('user192_14','user192_14@user192.com','@use14',$token,192,3);
		$this->logdb->log('register allscheme success user192_14',endtime($start),'1',9);
		
		//test batch #15
		$start=start();
		$token=$this->myauth->gentokenreg('user192_15');
		$this->myauth->create_allscheme_user('user192_15','user192_15@user192.com','@use15',$token,192,3);
		$this->logdb->log('register allscheme success user192_15',endtime($start),'1',9);
		
		//test batch #16
		$start=start();
		$token=$this->myauth->gentokenreg('user192_16');
		$this->myauth->create_allscheme_user('user192_16','user192_16@user192.com','@use16',$token,192,3);
		$this->logdb->log('register allscheme success user192_16',endtime($start),'1',9);
		
		//test batch #17
		$start=start();
		$token=$this->myauth->gentokenreg('user192_17');
		$this->myauth->create_allscheme_user('user192_17','user192_17@user192.com','@use17',$token,192,3);
		$this->logdb->log('register allscheme success user192_17',endtime($start),'1',9);
		
		//test batch #18
		$start=start();
		$token=$this->myauth->gentokenreg('user192_18');
		$this->myauth->create_allscheme_user('user192_18','user192_18@user192.com','@use18',$token,192,3);
		$this->logdb->log('register allscheme success user192_18',endtime($start),'1',9);
		
		//test batch #19
		$start=start();
		$token=$this->myauth->gentokenreg('user192_19');
		$this->myauth->create_allscheme_user('user192_19','user192_19@user192.com','@use19',$token,192,3);
		$this->logdb->log('register allscheme success user192_19',endtime($start),'1',9);
		
		//test batch #20
		$start=start();
		$token=$this->myauth->gentokenreg('user192_20');
		$this->myauth->create_allscheme_user('user192_20','user192_20@user192.com','@use20',$token,192,3);
		$this->logdb->log('register allscheme success user192_20',endtime($start),'1',9);
		
		//test batch #21
		$start=start();
		$token=$this->myauth->gentokenreg('user192_21');
		$this->myauth->create_allscheme_user('user192_21','user192_21@user192.com','@use21',$token,192,3);
		$this->logdb->log('register allscheme success user192_21',endtime($start),'1',9);
		
		//test batch #22
		$start=start();
		$token=$this->myauth->gentokenreg('user192_22');
		$this->myauth->create_allscheme_user('user192_22','user192_22@user192.com','@use22',$token,192,3);
		$this->logdb->log('register allscheme success user192_22',endtime($start),'1',9);
		
		//test batch #23
		$start=start();
		$token=$this->myauth->gentokenreg('user192_23');
		$this->myauth->create_allscheme_user('user192_23','user192_23@user192.com','@use23',$token,192,3);
		$this->logdb->log('register allscheme success user192_23',endtime($start),'1',9);
		
		//test batch #24
		$start=start();
		$token=$this->myauth->gentokenreg('user192_24');
		$this->myauth->create_allscheme_user('user192_24','user192_24@user192.com','@use24',$token,192,3);
		$this->logdb->log('register allscheme success user192_24',endtime($start),'1',9);
		
		//test batch #25
		$start=start();
		$token=$this->myauth->gentokenreg('user192_25');
		$this->myauth->create_allscheme_user('user192_25','user192_25@user192.com','@use25',$token,192,3);
		$this->logdb->log('register allscheme success user192_25',endtime($start),'1',9);
		
		//test batch #26
		$start=start();
		$token=$this->myauth->gentokenreg('user192_26');
		$this->myauth->create_allscheme_user('user192_26','user192_26@user192.com','@use26',$token,192,3);
		$this->logdb->log('register allscheme success user192_26',endtime($start),'1',9);
		
		//test batch #27
		$start=start();
		$token=$this->myauth->gentokenreg('user192_27');
		$this->myauth->create_allscheme_user('user192_27','user192_27@user192.com','@use27',$token,192,3);
		$this->logdb->log('register allscheme success user192_27',endtime($start),'1',9);
		
		//test batch #28
		$start=start();
		$token=$this->myauth->gentokenreg('user192_28');
		$this->myauth->create_allscheme_user('user192_28','user192_28@user192.com','@use28',$token,192,3);
		$this->logdb->log('register allscheme success user192_28',endtime($start),'1',9);
		
		//test batch #29
		$start=start();
		$token=$this->myauth->gentokenreg('user192_29');
		$this->myauth->create_allscheme_user('user192_29','user192_29@user192.com','@use29',$token,192,3);
		$this->logdb->log('register allscheme success user192_29',endtime($start),'1',9);
		
		//test batch #30
		$start=start();
		$token=$this->myauth->gentokenreg('user192_30');
		$this->myauth->create_allscheme_user('user192_30','user192_30@user192.com','@use30',$token,192,3);
		$this->logdb->log('register allscheme success user192_30',endtime($start),'1',9);
		
		//test batch #31
		$start=start();
		$token=$this->myauth->gentokenreg('user192_31');
		$this->myauth->create_allscheme_user('user192_31','user192_31@user192.com','@use31',$token,192,3);
		$this->logdb->log('register allscheme success user192_31',endtime($start),'1',9);
		
		//test batch #32
		$start=start();
		$token=$this->myauth->gentokenreg('user192_32');
		$this->myauth->create_allscheme_user('user192_32','user192_32@user192.com','@use32',$token,192,3);
		$this->logdb->log('register allscheme success user192_32',endtime($start),'1',9);
		
		//test batch #33
		$start=start();
		$token=$this->myauth->gentokenreg('user192_33');
		$this->myauth->create_allscheme_user('user192_33','user192_33@user192.com','@use33',$token,192,3);
		$this->logdb->log('register allscheme success user192_33',endtime($start),'1',9);
		
		//test batch #34
		$start=start();
		$token=$this->myauth->gentokenreg('user192_34');
		$this->myauth->create_allscheme_user('user192_34','user192_34@user192.com','@use34',$token,192,3);
		$this->logdb->log('register allscheme success user192_34',endtime($start),'1',9);
		
		//test batch #35
		$start=start();
		$token=$this->myauth->gentokenreg('user192_35');
		$this->myauth->create_allscheme_user('user192_35','user192_35@user192.com','@use35',$token,192,3);
		$this->logdb->log('register allscheme success user192_35',endtime($start),'1',9);
		
		//test batch #36
		$start=start();
		$token=$this->myauth->gentokenreg('user192_36');
		$this->myauth->create_allscheme_user('user192_36','user192_36@user192.com','@use36',$token,192,3);
		$this->logdb->log('register allscheme success user192_36',endtime($start),'1',9);
		
		//test batch #37
		$start=start();
		$token=$this->myauth->gentokenreg('user192_37');
		$this->myauth->create_allscheme_user('user192_37','user192_37@user192.com','@use37',$token,192,3);
		$this->logdb->log('register allscheme success user192_37',endtime($start),'1',9);
		
		//test batch #38
		$start=start();
		$token=$this->myauth->gentokenreg('user192_38');
		$this->myauth->create_allscheme_user('user192_38','user192_38@user192.com','@use38',$token,192,3);
		$this->logdb->log('register allscheme success user192_38',endtime($start),'1',9);
		
		//test batch #39
		$start=start();
		$token=$this->myauth->gentokenreg('user192_39');
		$this->myauth->create_allscheme_user('user192_39','user192_39@user192.com','@use39',$token,192,3);
		$this->logdb->log('register allscheme success user192_39',endtime($start),'1',9);
		
		//test batch #40
		$start=start();
		$token=$this->myauth->gentokenreg('user192_40');
		$this->myauth->create_allscheme_user('user192_40','user192_40@user192.com','@use40',$token,192,3);
		$this->logdb->log('register allscheme success user192_40',endtime($start),'1',9);
		
		//test batch #41
		$start=start();
		$token=$this->myauth->gentokenreg('user192_41');
		$this->myauth->create_allscheme_user('user192_41','user192_41@user192.com','@use41',$token,192,3);
		$this->logdb->log('register allscheme success user192_41',endtime($start),'1',9);
		
		//test batch #42
		$start=start();
		$token=$this->myauth->gentokenreg('user192_42');
		$this->myauth->create_allscheme_user('user192_42','user192_42@user192.com','@use42',$token,192,3);
		$this->logdb->log('register allscheme success user192_42',endtime($start),'1',9);
		
		//test batch #43
		$start=start();
		$token=$this->myauth->gentokenreg('user192_43');
		$this->myauth->create_allscheme_user('user192_43','user192_43@user192.com','@use43',$token,192,3);
		$this->logdb->log('register allscheme success user192_43',endtime($start),'1',9);
		
		//test batch #44
		$start=start();
		$token=$this->myauth->gentokenreg('user192_44');
		$this->myauth->create_allscheme_user('user192_44','user192_44@user192.com','@use44',$token,192,3);
		$this->logdb->log('register allscheme success user192_44',endtime($start),'1',9);
		
		//test batch #45
		$start=start();
		$token=$this->myauth->gentokenreg('user192_45');
		$this->myauth->create_allscheme_user('user192_45','user192_45@user192.com','@use45',$token,192,3);
		$this->logdb->log('register allscheme success user192_45',endtime($start),'1',9);
		
		//test batch #46
		$start=start();
		$token=$this->myauth->gentokenreg('user192_46');
		$this->myauth->create_allscheme_user('user192_46','user192_46@user192.com','@use46',$token,192,3);
		$this->logdb->log('register allscheme success user192_46',endtime($start),'1',9);
		
		//test batch #47
		$start=start();
		$token=$this->myauth->gentokenreg('user192_47');
		$this->myauth->create_allscheme_user('user192_47','user192_47@user192.com','@use47',$token,192,3);
		$this->logdb->log('register allscheme success user192_47',endtime($start),'1',9);
		
		//test batch #48
		$start=start();
		$token=$this->myauth->gentokenreg('user192_48');
		$this->myauth->create_allscheme_user('user192_48','user192_48@user192.com','@use48',$token,192,3);
		$this->logdb->log('register allscheme success user192_48',endtime($start),'1',9);
		
		//test batch #49
		$start=start();
		$token=$this->myauth->gentokenreg('user192_49');
		$this->myauth->create_allscheme_user('user192_49','user192_49@user192.com','@use49',$token,192,3);
		$this->logdb->log('register allscheme success user192_49',endtime($start),'1',9);
		
		//test batch #50
		$start=start();
		$token=$this->myauth->gentokenreg('user192_50');
		$this->myauth->create_allscheme_user('user192_50','user192_50@user192.com','@use50',$token,192,3);
		$this->logdb->log('register allscheme success user192_50',endtime($start),'1',9);

	}
	function test_reg_batch256(){
		// $token=$this->myauth->generate_tokenreg();
		
		//test batch #01
		$start=start();
		$token=$this->myauth->gentokenreg('user256_01');
		$this->myauth->create_allscheme_user('user256_01','user256_01@user256.com','@use01',$token,256,3);
		$this->logdb->log('register allscheme success user256_01',endtime($start),'1',9);
		
		//test batch #02
		$start=start();
		$token=$this->myauth->gentokenreg('user256_02');
		$this->myauth->create_allscheme_user('user256_02','user256_02@user256.com','@use02',$token,256,3);
		$this->logdb->log('register allscheme success user256_02',endtime($start),'1',9);
		
		//test batch #03
		$start=start();
		$token=$this->myauth->gentokenreg('user256_03');
		$this->myauth->create_allscheme_user('user256_03','user256_03@user256.com','@use03',$token,256,3);
		$this->logdb->log('register allscheme success user256_03',endtime($start),'1',9);
		
		//test batch #04
		$start=start();
		$token=$this->myauth->gentokenreg('user256_04');
		$this->myauth->create_allscheme_user('user256_04','user256_04@user256.com','@use04',$token,256,3);
		$this->logdb->log('register allscheme success user256_04',endtime($start),'1',9);
		
		//test batch #05
		$start=start();
		$token=$this->myauth->gentokenreg('user256_05');
		$this->myauth->create_allscheme_user('user256_05','user256_05@user256.com','@use05',$token,256,3);
		$this->logdb->log('register allscheme success user256_05',endtime($start),'1',9);
		
		//test batch #06
		$start=start();
		$token=$this->myauth->gentokenreg('user256_06');
		$this->myauth->create_allscheme_user('user256_06','user256_06@user256.com','@use06',$token,256,3);
		$this->logdb->log('register allscheme success user256_06',endtime($start),'1',9);
		
		//test batch #07
		$start=start();
		$token=$this->myauth->gentokenreg('user256_07');
		$this->myauth->create_allscheme_user('user256_07','user256_07@user256.com','@use07',$token,256,3);
		$this->logdb->log('register allscheme success user256_07',endtime($start),'1',9);
		
		//test batch #08
		$start=start();
		$token=$this->myauth->gentokenreg('user256_08');
		$this->myauth->create_allscheme_user('user256_08','user256_08@user256.com','@use08',$token,256,3);
		$this->logdb->log('register allscheme success user256_08',endtime($start),'1',9);
		
		//test batch #09
		$start=start();
		$token=$this->myauth->gentokenreg('user256_09');
		$this->myauth->create_allscheme_user('user256_09','user256_09@user256.com','@use09',$token,256,3);
		$this->logdb->log('register allscheme success user256_09',endtime($start),'1',9);
		
		//test batch #10
		$start=start();
		$token=$this->myauth->gentokenreg('user256_10');
		$this->myauth->create_allscheme_user('user256_10','user256_10@user256.com','@use10',$token,256,3);
		$this->logdb->log('register allscheme success user256_10',endtime($start),'1',9);
		
		//test batch #11
		$start=start();
		$token=$this->myauth->gentokenreg('user256_11');
		$this->myauth->create_allscheme_user('user256_11','user256_11@user256.com','@use11',$token,256,3);
		$this->logdb->log('register allscheme success user256_11',endtime($start),'1',9);
		
		//test batch #12
		$start=start();
		$token=$this->myauth->gentokenreg('user256_12');
		$this->myauth->create_allscheme_user('user256_12','user256_12@user256.com','@use12',$token,256,3);
		$this->logdb->log('register allscheme success user256_12',endtime($start),'1',9);
		
		//test batch #13
		$start=start();
		$token=$this->myauth->gentokenreg('user256_13');
		$this->myauth->create_allscheme_user('user256_13','user256_13@user256.com','@use13',$token,256,3);
		$this->logdb->log('register allscheme success user256_13',endtime($start),'1',9);
		
		//test batch #14
		$start=start();
		$token=$this->myauth->gentokenreg('user256_14');
		$this->myauth->create_allscheme_user('user256_14','user256_14@user256.com','@use14',$token,256,3);
		$this->logdb->log('register allscheme success user256_14',endtime($start),'1',9);
		
		//test batch #15
		$start=start();
		$token=$this->myauth->gentokenreg('user256_15');
		$this->myauth->create_allscheme_user('user256_15','user256_15@user256.com','@use15',$token,256,3);
		$this->logdb->log('register allscheme success user256_15',endtime($start),'1',9);
		
		//test batch #16
		$start=start();
		$token=$this->myauth->gentokenreg('user256_16');
		$this->myauth->create_allscheme_user('user256_16','user256_16@user256.com','@use16',$token,256,3);
		$this->logdb->log('register allscheme success user256_16',endtime($start),'1',9);
		
		//test batch #17
		$start=start();
		$token=$this->myauth->gentokenreg('user256_17');
		$this->myauth->create_allscheme_user('user256_17','user256_17@user256.com','@use17',$token,256,3);
		$this->logdb->log('register allscheme success user256_17',endtime($start),'1',9);
		
		//test batch #18
		$start=start();
		$token=$this->myauth->gentokenreg('user256_18');
		$this->myauth->create_allscheme_user('user256_18','user256_18@user256.com','@use18',$token,256,3);
		$this->logdb->log('register allscheme success user256_18',endtime($start),'1',9);
		
		//test batch #19
		$start=start();
		$token=$this->myauth->gentokenreg('user256_19');
		$this->myauth->create_allscheme_user('user256_19','user256_19@user256.com','@use19',$token,256,3);
		$this->logdb->log('register allscheme success user256_19',endtime($start),'1',9);
		
		//test batch #20
		$start=start();
		$token=$this->myauth->gentokenreg('user256_20');
		$this->myauth->create_allscheme_user('user256_20','user256_20@user256.com','@use20',$token,256,3);
		$this->logdb->log('register allscheme success user256_20',endtime($start),'1',9);
		
		//test batch #21
		$start=start();
		$token=$this->myauth->gentokenreg('user256_21');
		$this->myauth->create_allscheme_user('user256_21','user256_21@user256.com','@use21',$token,256,3);
		$this->logdb->log('register allscheme success user256_21',endtime($start),'1',9);
		
		//test batch #22
		$start=start();
		$token=$this->myauth->gentokenreg('user256_22');
		$this->myauth->create_allscheme_user('user256_22','user256_22@user256.com','@use22',$token,256,3);
		$this->logdb->log('register allscheme success user256_22',endtime($start),'1',9);
		
		//test batch #23
		$start=start();
		$token=$this->myauth->gentokenreg('user256_23');
		$this->myauth->create_allscheme_user('user256_23','user256_23@user256.com','@use23',$token,256,3);
		$this->logdb->log('register allscheme success user256_23',endtime($start),'1',9);
		
		//test batch #24
		$start=start();
		$token=$this->myauth->gentokenreg('user256_24');
		$this->myauth->create_allscheme_user('user256_24','user256_24@user256.com','@use24',$token,256,3);
		$this->logdb->log('register allscheme success user256_24',endtime($start),'1',9);
		
		//test batch #25
		$start=start();
		$token=$this->myauth->gentokenreg('user256_25');
		$this->myauth->create_allscheme_user('user256_25','user256_25@user256.com','@use25',$token,256,3);
		$this->logdb->log('register allscheme success user256_25',endtime($start),'1',9);
		
		//test batch #26
		$start=start();
		$token=$this->myauth->gentokenreg('user256_26');
		$this->myauth->create_allscheme_user('user256_26','user256_26@user256.com','@use26',$token,256,3);
		$this->logdb->log('register allscheme success user256_26',endtime($start),'1',9);
		
		//test batch #27
		$start=start();
		$token=$this->myauth->gentokenreg('user256_27');
		$this->myauth->create_allscheme_user('user256_27','user256_27@user256.com','@use27',$token,256,3);
		$this->logdb->log('register allscheme success user256_27',endtime($start),'1',9);
		
		//test batch #28
		$start=start();
		$token=$this->myauth->gentokenreg('user256_28');
		$this->myauth->create_allscheme_user('user256_28','user256_28@user256.com','@use28',$token,256,3);
		$this->logdb->log('register allscheme success user256_28',endtime($start),'1',9);
		
		//test batch #29
		$start=start();
		$token=$this->myauth->gentokenreg('user256_29');
		$this->myauth->create_allscheme_user('user256_29','user256_29@user256.com','@use29',$token,256,3);
		$this->logdb->log('register allscheme success user256_29',endtime($start),'1',9);
		
		//test batch #30
		$start=start();
		$token=$this->myauth->gentokenreg('user256_30');
		$this->myauth->create_allscheme_user('user256_30','user256_30@user256.com','@use30',$token,256,3);
		$this->logdb->log('register allscheme success user256_30',endtime($start),'1',9);
		
		//test batch #31
		$start=start();
		$token=$this->myauth->gentokenreg('user256_31');
		$this->myauth->create_allscheme_user('user256_31','user256_31@user256.com','@use31',$token,256,3);
		$this->logdb->log('register allscheme success user256_31',endtime($start),'1',9);
		
		//test batch #32
		$start=start();
		$token=$this->myauth->gentokenreg('user256_32');
		$this->myauth->create_allscheme_user('user256_32','user256_32@user256.com','@use32',$token,256,3);
		$this->logdb->log('register allscheme success user256_32',endtime($start),'1',9);
		
		//test batch #33
		$start=start();
		$token=$this->myauth->gentokenreg('user256_33');
		$this->myauth->create_allscheme_user('user256_33','user256_33@user256.com','@use33',$token,256,3);
		$this->logdb->log('register allscheme success user256_33',endtime($start),'1',9);
		
		//test batch #34
		$start=start();
		$token=$this->myauth->gentokenreg('user256_34');
		$this->myauth->create_allscheme_user('user256_34','user256_34@user256.com','@use34',$token,256,3);
		$this->logdb->log('register allscheme success user256_34',endtime($start),'1',9);
		
		//test batch #35
		$start=start();
		$token=$this->myauth->gentokenreg('user256_35');
		$this->myauth->create_allscheme_user('user256_35','user256_35@user256.com','@use35',$token,256,3);
		$this->logdb->log('register allscheme success user256_35',endtime($start),'1',9);
		
		//test batch #36
		$start=start();
		$token=$this->myauth->gentokenreg('user256_36');
		$this->myauth->create_allscheme_user('user256_36','user256_36@user256.com','@use36',$token,256,3);
		$this->logdb->log('register allscheme success user256_36',endtime($start),'1',9);
		
		//test batch #37
		$start=start();
		$token=$this->myauth->gentokenreg('user256_37');
		$this->myauth->create_allscheme_user('user256_37','user256_37@user256.com','@use37',$token,256,3);
		$this->logdb->log('register allscheme success user256_37',endtime($start),'1',9);
		
		//test batch #38
		$start=start();
		$token=$this->myauth->gentokenreg('user256_38');
		$this->myauth->create_allscheme_user('user256_38','user256_38@user256.com','@use38',$token,256,3);
		$this->logdb->log('register allscheme success user256_38',endtime($start),'1',9);
		
		//test batch #39
		$start=start();
		$token=$this->myauth->gentokenreg('user256_39');
		$this->myauth->create_allscheme_user('user256_39','user256_39@user256.com','@use39',$token,256,3);
		$this->logdb->log('register allscheme success user256_39',endtime($start),'1',9);
		
		//test batch #40
		$start=start();
		$token=$this->myauth->gentokenreg('user256_40');
		$this->myauth->create_allscheme_user('user256_40','user256_40@user256.com','@use40',$token,256,3);
		$this->logdb->log('register allscheme success user256_40',endtime($start),'1',9);
		
		//test batch #41
		$start=start();
		$token=$this->myauth->gentokenreg('user256_41');
		$this->myauth->create_allscheme_user('user256_41','user256_41@user256.com','@use41',$token,256,3);
		$this->logdb->log('register allscheme success user256_41',endtime($start),'1',9);
		
		//test batch #42
		$start=start();
		$token=$this->myauth->gentokenreg('user256_42');
		$this->myauth->create_allscheme_user('user256_42','user256_42@user256.com','@use42',$token,256,3);
		$this->logdb->log('register allscheme success user256_42',endtime($start),'1',9);
		
		//test batch #43
		$start=start();
		$token=$this->myauth->gentokenreg('user256_43');
		$this->myauth->create_allscheme_user('user256_43','user256_43@user256.com','@use43',$token,256,3);
		$this->logdb->log('register allscheme success user256_43',endtime($start),'1',9);
		
		//test batch #44
		$start=start();
		$token=$this->myauth->gentokenreg('user256_44');
		$this->myauth->create_allscheme_user('user256_44','user256_44@user256.com','@use44',$token,256,3);
		$this->logdb->log('register allscheme success user256_44',endtime($start),'1',9);
		
		//test batch #45
		$start=start();
		$token=$this->myauth->gentokenreg('user256_45');
		$this->myauth->create_allscheme_user('user256_45','user256_45@user256.com','@use45',$token,256,3);
		$this->logdb->log('register allscheme success user256_45',endtime($start),'1',9);
		
		//test batch #46
		$start=start();
		$token=$this->myauth->gentokenreg('user256_46');
		$this->myauth->create_allscheme_user('user256_46','user256_46@user256.com','@use46',$token,256,3);
		$this->logdb->log('register allscheme success user256_46',endtime($start),'1',9);
		
		//test batch #47
		$start=start();
		$token=$this->myauth->gentokenreg('user256_47');
		$this->myauth->create_allscheme_user('user256_47','user256_47@user256.com','@use47',$token,256,3);
		$this->logdb->log('register allscheme success user256_47',endtime($start),'1',9);
		
		//test batch #48
		$start=start();
		$token=$this->myauth->gentokenreg('user256_48');
		$this->myauth->create_allscheme_user('user256_48','user256_48@user256.com','@use48',$token,256,3);
		$this->logdb->log('register allscheme success user256_48',endtime($start),'1',9);
		
		//test batch #49
		$start=start();
		$token=$this->myauth->gentokenreg('user256_49');
		$this->myauth->create_allscheme_user('user256_49','user256_49@user256.com','@use49',$token,256,3);
		$this->logdb->log('register allscheme success user256_49',endtime($start),'1',9);
		
		//test batch #50
		$start=start();
		$token=$this->myauth->gentokenreg('user256_50');
		$this->myauth->create_allscheme_user('user256_50','user256_50@user256.com','@use50',$token,256,3);
		$this->logdb->log('register allscheme success user256_50',endtime($start),'1',9);

	}

}

/* End of file main.php */
/* Location: ./application/modules/main/controllers/main.php */

 ?>