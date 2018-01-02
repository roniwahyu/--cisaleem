<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Authentication Class
 *
 * Very basic user authentication for CodeIgniter.
 * 
 * @package		Authentication
 * @version		1.0
 * @author		Joel Vardy <info@joelvardy.com>
 * @link		https://github.com/joelvardy/Basic-CodeIgniter-Authentication
 */
class Authentication {


	/**
	 * CodeIgniter
	 *
	 * @access	private
	 */
	private $ci;


	/**
	 * Config items
	 *
	 * @access	private
	 */
	private $user_table;
	private $identifier_field;
	private $username_field;
	private $email_field;
	private $password_field;
	private $scheme_field;
	private $token_field;
	private $passdwordhash_field;
	private $usernamehash_field;
	private $enc_password_field;
	private $enc_username_field;
	private $saltbefore_field;
	private $saltafter_field;


	/**
	 * Constructor
	 */
	function __construct()
	{

		// Assign CodeIgniter object to $this->ci
		$this->ci =& get_instance();

		// Load config
		$this->ci->config->load('authentication');
		$authentication_config = $this->ci->config->item('authentication');

		// Set config items
		$this->user_table = $authentication_config['user_table'];
		$this->identifier_field = $authentication_config['identifier_field'];
		$this->username_field = $authentication_config['username_field'];
		$this->enc_username_field = $authentication_config['enc_username_field'];
		$this->usernamehash_field = $authentication_config['usernamehash_field'];
		$this->email_field = $authentication_config['email_field'];
		$this->password_field = $authentication_config['password_field'];
		$this->enc_password_field = $authentication_config['enc_password_field'];
		$this->passwordhash_field = $authentication_config['passwordhash_field'];
		$this->scheme_field = $authentication_config['scheme_field'];
		$this->token_field = $authentication_config['token_field'];
		$this->saltbefore_field = $authentication_config['saltbefore_field'];
		$this->saltafter_field = $authentication_config['saltafter_field'];

		// Load database
		$this->ci->load->database();

		// Load libraries
		$this->ci->load->library('session');

	}


	/**
	 * Check whether the username is unique
	 *
	 * @access	public
	 * @param	string [$username] The username to query
	 * @return	boolean
	 */
	public function username_check($username)
	{

		// Read users where username matches
		$query = $this->ci->db->where($this->username_field, $username)->get($this->user_table);

		// If there are users
		if ($query->num_rows() > 0)
		{
			// Username is not available
			return FALSE;
		}

		// No users were found
		return TRUE;

	}
	public function username_validation($username,$email)
	{

		// Read users where username matches
		$query = $this->ci->db->where(array($this->username_field=>$username,$this->email_field=>$email))->get($this->user_table);

		// If there are users
		if ($query->num_rows() > 0)
		{
			// Username is not available
			return TRUE;
		}

		// No users were found
		return FALSE;

	}


	/**
	 * Generate a salt
	 *
	 * @access	protected
	 * @param	integer [$cost] The strength of the resulting hash, must be within the range 04-31
	 * @return	string The generated salt
	 */
	protected function generate_salt($cost = 16)
	{
		
		// We are using blowfish, so this must be set at the beginning of the salt
		$salt = '$2a$'.$cost.'$';

		// Generate a random string based on time
		$salt .= substr(str_replace('+', '.', base64_encode(sha1(microtime(TRUE), TRUE))), 0, 22);
		// $salt .= substr(str_replace('+', '.', base64_encode(md5(microtime(TRUE), TRUE))), 0, 22);
		// $salt .= substr(str_replace('+', '.', md5(sha1(microtime(TRUE), TRUE))), 0, 22);
		// $salt .= substr(str_replace('+','.',sha1(microtime(TRUE),TRUE)),0,22); //failed
		// $salt .= substr(str_replace('+','.',base64_encode(microtime(TRUE))),0,22);
		// $salt .= substr(str_replace('+','.',base64_encode(microtime(TRUE))),0,22);

		// Return salt
		return $salt.'$';

	}
	public function genhash($password,$salt){
		return $this->generate_hash($password,$salt);
	}
	public function gensalt($cost=16){
		return $this->generate_salt($cost);
	}

	/**
	 * Generate a hash
	 *
	 * @access	protected
	 * @param	string [$password] The password for which the hash should be generated for
	 * @param	string [$salt] The salt can either be the one returned from the generate_salt method or the current password
	 * @return	string The generated hash
	 */
	function generate_tokenreg(){
		// return substr(str_replace('+', '.', base64_encode(sha1(microtime(TRUE), TRUE))), 0, 22);
		return substr(str_replace('+', '.', base64_encode(sha1(date('Ymd_His'), TRUE))), 0, 24);
	}
	function gentokenreg32(){
		return substr(str_replace('+', '.', base64_encode(sha1(microtime(TRUE), TRUE))), 0, 32);
		// return substr(str_replace('+', '.', base64_encode(md5(sha1(date('Ymd_His'), TRUE)))), 0, 32);
		// return md5(sha1(date('Ymd_His'), TRUE));
	}
	function generate_tokenlog(){
		// return substr(str_replace('+', '.', base64_encode(sha1(microtime(TRUE), TRUE))), 0, 22);
		return substr(str_replace('+', '.', base64_encode(sha1(date('Ymd_Hi'), TRUE))), 0, 22);
	}
	function gentokenreg($user=null){
		return $this->__genhash2($user);
	}
	public function gen_user_hash($password,$salt){
		return crypt($password, $salt);
		
	}
	public function gen_password_hash($password,$salt){
		return crypt($password, $salt);
	}
	protected function generate_hash($password, $salt)
	{

		// Hash the generated details with a salt to form a secure password hash
		return crypt($password, $salt);

	}

	function genbcrypthash($string=null,$costs=16){
		return password_hash($string,PASSWORD_BCRYPT,array('costs'=>$costs));
	}
	function verifyhash($string,$hash){
		if(password_verify($string,$this->genhash(16,$hash))){
			// echo 'password valid';

			return TRUE;
		}else{
			// echo "invalid";
			return FALSE;
		}
	}


		//HASH USING SHA MD5 BASE ENCODE
	function __genhash($string){
		return str_replace('+', '.',base64_encode(sha1(md5($string))));
	}
	function __genhash2($string){
		return sha1(md5($string));
	}
	function genhashamd5($string){
		// return substr(str_replace('+', '.',base64_encode(sha1(md5($string)))),0,32);
		return $this->__genhash($string);
	}
	function hashverify($string,$hash){
		// $inputhash=substr(str_replace('+', '.',base64_encode(sha1(md5($string)))),0,32);
		$inputhash=$this->__genhash($string);
		echo "<pre>";
		if($inputhash==$hash){
			// return "TRUE";
			echo "TRUE";
			echo "<br>";
			echo $hash;
			echo "<br>";
			echo $inputhash;
			echo "<br>";
			echo str_replace('+', '.',base64_encode(sha1(md5($string))));
		}else{
			// return "FALSE";
			echo "FALSE";
			echo "<br>";
			echo $hash;
			echo "<br>";
			echo $inputhash;
			echo "<br>";
			echo str_replace('+', '.',base64_encode(sha1(md5($string))));
		}
		echo "</pre>";
		
	}
	function verifyhashx($password){
		$salt='SALT';
		$before="123";
		$after="321";
		$password = md5($password);
		$passwordhash = $this->generate_hash($before.$password.$after, $salt);
		return $passwordhash;
	}
	function getSalt($type='num',$len=3) {
		if($type=='char'){
			// $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\][{}\'";:?.>,<!@#$%^&*()-_=+|';
			$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+?';
		}elseif($type=='num'){
			$charset = '1234567890';
		}
		// $salt .= substr(str_replace('+', '.', base64_encode(sha1(microtime(TRUE), TRUE))), 0, 22);

        $randStringLen = $len;
		$randString = "";
        for ($i = 0; $i < $randStringLen; $i++) {
        	$randString .= $charset[mt_rand(0, strlen($charset) - 1)];
        }
		return $randString;
    }

	/**
	 * Create user
	 *
	 * @access	public
	 * @param	string [$username] The username of the user to be created
	 * @param	string [$password] The users password
	 * @return	integer|boolean Either the user ID or FALSEupon failure
	 */
	public function create_user($username, $password)
	{

		// Ensure username is available
		if ( ! $this->username_check($username))
		{
			// Username is not available
			return FALSE;
		}

		// Generate salt
		$salt = $this->generate_salt();

		// Generate hash
		$password = $this->generate_hash($password, $salt);

		// Define data to insert
		$data = array(
			$this->username_field => $username,
			$this->password_field => $password
		);

		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data))
		{
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}
	
	public function create_unsecure_user($username, $password,$scheme)
	{

		// Ensure username is available
		if ( ! $this->username_check($username))
		{
			// Username is not available
			return FALSE;
		}

		// Generate salt
		// $salt = $this->generate_salt();

		// Generate hash
		// $password = $this->generate_hash($password, $salt);

		// Define data to insert
		$data = array(
			$this->username_field => $username,
			$this->password_field => $password,
			$this->scheme_field =>$scheme,
			
		);

		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data))
		{
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}
	public function create_simplesecure_user($username,$email, $password,$scheme)
	{

		// Ensure username is available
		if ( ! $this->username_check($username))
		{
			// Username is not available
			return FALSE;
		}

		// Generate salt
		// $salt = $this->generate_salt();

		// Generate hash
		// $password = $this->generate_hash($password, $salt);
		$password = md5($password);

		// Define data to insert
		$data = array(
			$this->username_field => $username,
			$this->password_field => $password,
			$this->email_field => $email,
			$this->scheme_field =>$scheme,
			'datetime'=>date('Y-m-d H:i:s'),
			
		);

		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data))
		{
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}
	public function create_token_user($username, $email,$password,$scheme,$token)
	{

		// Ensure username is available
		if ( ! $this->username_check($username,$email))
		{
			// Username is not available
			return FALSE;
		}

		// Generate salt
		// $salt = $this->generate_salt();

		// Generate hash
		// $password = $this->generate_hash($password, $salt);
		$password = md5($password);

		// Define data to insert
		$data = array(
			$this->username_field => $username,
			$this->email_field => $email,
			$this->password_field => $password,
			$this->scheme_field =>$scheme,
			$this->token_field =>$token,
			'datetime'=>date('Y-m-d H:i:s'),
			
		);

		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data))
		{
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}
	public function create_secretkey_user($username, $email,$password,$scheme,$token)
	{
		$this->ci->load->library('MyAES');

		// Ensure username is available
		if ( ! $this->username_check($username,$email))
		{
			// Username is not available
			return FALSE;
		}

		// Generate salt
		// $salt = $this->generate_salt();

		// Generate hash
		// $password = $this->generate_hash($password, $salt);x
		$this->ci->myaes->size(128);
		$password = md5($password);
		$enc_password = $this->ci->myaes->enc($password, $token);
		$enc_username = $this->ci->myaes->enc($username, $token);
		// $decrypted_string = $this->ci->myaes->dec($encrypted_string, $key);

		// Define data to insert
		$data = array(
			$this->username_field => $username,
			$this->password_field => $password,
			$this->enc_username_field => $enc_username,
			$this->enc_password_field => $enc_password,
			$this->email_field => $email,
			$this->scheme_field =>$scheme,
			$this->token_field =>$token,
			'datetime'=>date('Y-m-d H:i:s'),
			
		);
		// print_r($data);

		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data))
		{
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}
	public function create_allscheme_user($username,$email,$password,$token,$bit=128,$num=3)
	{
		//load library AES

		$this->ci->load->library('AES5');
		$this->ci->load->helper('logger');
		$this->ci->load->model('logger_model','logdb',TRUE);
		// Ensure username is available
		if ( ! $this->username_check($username,$email)){
			// Username is not available
			return FALSE;
		}

		// $start=start();
		/* create user scheme secretkey */
		$aes_username = new AES5($username, $token, $bit);
		$aes_password = new AES5($password, $token, $bit);
	    
	    $enc_username = $aes_username->encrypt();
	    $enc_password = $aes_password->encrypt();
	    // $this->ci->logdb->log('encrypt aes'.$bit.' register success '.$username,endtime($start),'1',9,$bit,$num); //end of logger 
	
		
		// $password = md5($password);
		// $decrypted_string = $this->ci->myaes->dec($encrypted_string, $key);
		/* end of create user scheme secretkey */
		
		/*create user scheme hybrid*/
		//generate 3 digit salt for hybrid user
		$saltnum=$this->getsalt('num',$num);
		/*end of create user scheme hybrid*/
		
		/* create user scheme hash*/
		//md5 hash password (remove 3 digit on the right) and hybrid (md5 + 3 digit salt)
		// $start=start();
		$password_hash = md5($password).$saltnum; 
		$username_hash = md5($username).$saltnum; 
    	// $this->ci->logdb->log('hashing and salt register success '.$username,endtime($start),'1',9,$bit,$num); //end of

		//md5 hash username (remove 3 digit on the right) and hybrid (md5 + 3 digit salt)
		/* end of create user scheme has*/
		
		// Define data to insert
		$data = array(
			$this->username_field => $username,				// input username		
			$this->password_field => $password,				// input password
			$this->enc_username_field => $enc_username,		// input encrypted username (AES)
			$this->enc_password_field => $enc_password,		// input encrypted password (AES)
			$this->usernamehash_field => $username_hash,	// input hash username
			$this->passwordhash_field => $password_hash,	// input hash password
			$this->saltafter_field=>$saltnum,				// input numeric salt
			$this->email_field => $email,					// input email
			$this->token_field =>$token,					// input token key
			'datetime'=>date('Y-m-d H:i:s'),				// input datetime
		);
		
		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data)){
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}
	public function create_allscheme_userx($username,$email,$password,$token,$bit=128,$num=3)
	{
		//load library AES
		$this->ci->load->library('MyAES');
		// Ensure username is available
		if ( ! $this->username_check($username,$email)){
			// Username is not available
			return FALSE;
		}

	
		/* create user scheme secretkey */
		$this->ci->myaes->size($bit);
		// $password = md5($password);
		$enc_password = $this->ci->myaes->enc($password, $token);
		$enc_username = $this->ci->myaes->enc($username, $token);
		// $decrypted_string = $this->ci->myaes->dec($encrypted_string, $key);
		/* end of create user scheme secretkey */
		
		/*create user scheme hybrid*/
		//generate 3 digit salt for hybrid user
		$saltnum=$this->getsalt('num',$num);
		/*end of create user scheme hybrid*/
		
		/* create user scheme hash*/
		//md5 hash password (remove 3 digit on the right) and hybrid (md5 + 3 digit salt)
		$password_hash = md5($password).$saltnum; 

		//md5 hash username (remove 3 digit on the right) and hybrid (md5 + 3 digit salt)
		$username_hash = md5($username).$saltnum; 
		/* end of create user scheme has*/
		
		// Define data to insert
		$data = array(
			$this->username_field => $username,				// input username		
			$this->password_field => $password,				// input password
			$this->enc_username_field => $enc_username,		// input encrypted username (AES)
			$this->enc_password_field => $enc_password,		// input encrypted password (AES)
			$this->usernamehash_field => $username_hash,	// input hash username
			$this->passwordhash_field => $password_hash,	// input hash password
			$this->saltafter_field=>$saltnum,				// input numeric salt
			$this->email_field => $email,					// input email
			$this->token_field =>$token,					// input token key
			'datetime'=>date('Y-m-d H:i:s'),				// input datetime
		);
		
		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data)){
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}

	//hash using CRYPT()
	public function create_hash_user($username,$email,$password,$scheme,$token){
		// Ensure username is available
		if ( ! $this->username_check($username,$email))
		{
			// Username is not available
			return FALSE;
		}
		// $password = md5($password);
		$password = sha1($password);
		// $password_hash = $this->genbcrypthash($password,16);
		$password_hash = crypt($password);
		// $username_hash = $this->genbcrypthash($username,16);
		$username_hash = crypt($username);

		$data = array(
			$this->username_field => $username,
			$this->usernamehash_field => $username_hash,
			$this->email_field => $email,
			$this->password_field => $password,
			$this->passwordhash_field => $password_hash,
			$this->scheme_field =>$scheme,
			$this->token_field =>$token,
			'datetime'=>date('Y-m-d H:i:s'),
			
		);

		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data))
		{
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}

	public function create_hash_user_shamd5($username,$email,$password,$scheme,$token){
		// Ensure username is available
		if ( ! $this->username_check($username,$email))
		{
			// Username is not available
			return FALSE;
		}
		// $password = md5($password);
		// $password = sha1(md5($password));
		// $username = sha1(md5($username);
		// $password_hash = $this->genbcrypthash($password,16);
		// $password_hash = str_replace('+', '.',base64_encode(sha1(md5($password))));
		$password_hash =$this->genhashamd5($password);
		// $username_hash = $this->genbcrypthash($username,16);
		// $username_hash = crypt($username);
		// $username_hash = base64_encode(sha1(md5($username)));
		$username_hash =$this->genhashamd5($username);

		$data = array(
			$this->username_field => $username,
			$this->usernamehash_field => $username_hash,
			$this->email_field => $email,
			$this->password_field => $password,
			$this->passwordhash_field => $password_hash,
			$this->scheme_field =>$scheme,
			$this->token_field =>$token,
			'datetime'=>date('Y-m-d H:i:s'),
			
		);
		// print_r($data);

		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data))
		{
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}
	public function create_hybrid_user($username,$email,$password,$scheme,$token){
		// Ensure username is available
		if ( ! $this->username_check($username,$email))
		{
			// Username is not available
			return FALSE;
		}
		$passhash = md5($password);
		$userhash = md5($username);
		// $password = sha1(md5($password));
		// $username = sha1(md5($username);
		// $password_hash = $this->genbcrypthash($password,16);
		// $password_hash = str_replace('+', '.',base64_encode(sha1(md5($password))));
		// $password_hash =$this->genhashamd5($password);
		$saltnum=$this->getsalt('num',6);
		$password_hash =$passhash.$saltnum;
		// $username_hash = $this->genbcrypthash($username,16);
		// $username_hash = crypt($username);
		// $username_hash = base64_encode(sha1(md5($username)));
		$username_hash =$userhash.$saltnum;
		// $username_hash =$this->genhashamd5($username);

		$data = array(
			$this->username_field => $username,
			$this->usernamehash_field => $username_hash,
			$this->email_field => $email,
			$this->password_field => $password,
			$this->passwordhash_field => $password_hash,
			$this->scheme_field =>$scheme,
			$this->token_field =>$token,
			$this->saltafter_field=>$saltnum,
			'datetime'=>date('Y-m-d H:i:s'),
			
		);
		// print_r($data);

		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data))
		{
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}
	public function create_hash_user2($username, $email,$password,$scheme,$token)
	{

		// Ensure username is available
		if ( ! $this->username_check($username,$email))
		{
			// Username is not available
			return FALSE;
		}

		// Generate salt
		$salt = $this->generate_salt();

		// Generate hash
		// $salt='SALT';
		$before="123";
		$after="321";
		$password = md5($password);
		$passwordhash = $this->generate_hash($before.$password.$after, $salt);
		$usernamehash = $this->generate_hash($username, $salt);

		// Define data to insert
		$data = array(
			$this->username_field => $username,
			$this->usernamehash_field => $usernamehash,
			$this->email_field => $email,
			$this->password_field => $password,
			$this->passwordhash_field => $passwordhash,
			$this->scheme_field =>$scheme,
			$this->token_field =>$token,
			'datetime'=>date('Y-m-d H:i:s'),
			
		);

		// If inserting data fails
		if ( ! $this->ci->db->insert($this->user_table, $data))
		{
			// Return false
			return FALSE;
		}

		// Return user ID
		return (int) $this->ci->db->insert_id();

	}

	
	/**
	 * Login
	 *
	 * @access	public
	 * @param	string [$username] The username of the user to authenticate
	 * @param	string [$password] The password to authenticate
	 * @return	boolean Either TRUE or FALSE depending upon successful login
	 */
	public function login($username, $password)
	{

		// Select user details
		$user = $this->ci->db
			->select($this->identifier_field.' as identifier, '.$this->username_field.' as username, '.$this->password_field.' as password')
			->where($this->username_field, $username)
			->get($this->user_table);

		// Ensure there is a user with that username
		if ($user->num_rows() == 0)
		{
			// There is no user with that username, but we won't tell the user that
			return FALSE;
		}

		// Set the user details
		$user_details = $user->row();

		// Do passwords match
		if ($this->generate_hash($password, $user_details->password) == $user_details->password)
		{

			// Yes, the passwords match

			// Set the userdata for the current user
			$this->ci->session->set_userdata(array(
				'identifier' => $user_details->identifier,
				'username' => $user_details->username,
				'logged_in' => $_SERVER['REQUEST_TIME']
			));

			return TRUE;

		// The passwords don't match
		} else {
			// The passwords don't match, but we won't tell the user that
			return FALSE;
		}

	}

	public function simple_secure_login($username, $password)
	{

		// Select user details
		$user = $this->ci->db
			->select($this->identifier_field.' as identifier, '.$this->username_field.' as username, '.$this->password_field.' as password')
			->where($this->username_field, $username)
			->get($this->user_table);

		// Ensure there is a user with that username
		if ($user->num_rows() == 0)
		{
			// There is no user with that username, but we won't tell the user that
			return FALSE;
			// return $this->ci->db->last_query();
		}

		// Set the user details
		$user_details = $user->row();

		// Do passwords match
		// if ($this->generate_hash($password, $user_details->password) == $user_details->password)
		if (md5($password) == $user_details->password)
		{

			// Yes, the passwords match

			// Set the userdata for the current user
			$this->ci->session->set_userdata(array(
				'identifier' => $user_details->identifier,
				'username' => $user_details->username,
				'logged_in' => $_SERVER['REQUEST_TIME']
			));

			return TRUE;

		// The passwords don't match
		} else {
			// The passwords don't match, but we won't tell the user that
			return FALSE;
		}

	}


	/**
	 * Check whether a user is logged in
	 *
	 * @access	public
	 * @return	boolean TRUE for a logged in user otherwise FALSE
	 */
	public function is_loggedin()
	{

		// Return true or flase based on the presence of user data
		return (bool) $this->ci->session->userdata('identifier');

	}


	/**
	 * Read user details
	 *
	 * @access	public
	 * @return	mixed or FALSE
	 */
	public function read($key)
	{

		// Only allow us to read certain data
		switch ($key)
		{
			case 'identifier': {

				// If the user is not logged in return false
				if ( ! $this->is_loggedin()) return false;

				// Return user identifier
				return (int) $this->ci->session->userdata('identifier');

				break;

			}
			case 'username': {

				// If the user is not logged in return false
				if ( ! $this->is_loggedin()) return false;

				// Return username
				return (string) $this->ci->session->userdata('username');

				break;

			}
			case 'login': {

				// If the user is not logged in return false
				if ( ! $this->is_loggedin()) return false;

				// Return time the user logged in at
				return (int) $this->ci->session->userdata('logged_in');

				break;

			}
			case 'logout': {

				// Return time the user logged out at
				return (int) $this->ci->session->userdata('logged_out');

				break;

			}
		}

		// If nothing has been returned yet
		return false;

	}


	/**
	 * Change password
	 *
	 * @access	public
	 * @param	string [$password] The new password
	 * @param	string [$user_identifier] The identifier of the user whos password will be changed, if none is set the current users password will be changed
	 * @return	boolean Either TRUE or FALSE depending upon successful login
	 */
	public function change_password($password, $user_identifier = null)
	{

		// If no user identifier has been set
		if ( ! $user_identifier)
		{
			// Ensure the current user is logged in
			if ($this->is_loggedin())
			{

				// Read the user identifier
				$user_identifier = $this->ci->session->userdata('identifier');

			// There is no current logged in user
			} else {
				return FALSE;
			}
		}

		// Generate salt
		$salt = $this->generate_salt();

		// Generate hash
		$password = $this->generate_hash($password, $salt);

		// Define data to update
		$data = array(
			$this->password_field => $password
		);

		// Update the users password
		if ($this->ci->db->where($this->identifier_field, $user_identifier)->update($this->user_table, $data))
		{
			return TRUE;
		// There was an error updating the user
		} else {
			return FALSE;
		}

	}


	/**
	 * Log a user out
	 *
	 * @access	public
	 * @return	boolean Will always return TRUE
	 */
	public function logout()
	{

		// Remove userdata
		$this->ci->session->unset_userdata('identifier');
		$this->ci->session->unset_userdata('username');
		$this->ci->session->unset_userdata('logged_in');

		// Set logged out userdata
		$this->ci->session->set_userdata(array(
			'logged_out' => $_SERVER['REQUEST_TIME']
		));

		// Return true
		return TRUE;

	}


	/**
	 * Delete user account
	 *
	 * @access	public
	 * @param	string [$user_identifier] The identifier of the user to delete
	 * @return	boolean Either TRUE or FALSE depending upon successful login
	 */
	public function delete_user($user_identifier)
	{

		// Update the users password
		if ($this->ci->db->where($this->identifier_field, $user_identifier)->delete($this->user_table))
		{
			return TRUE;
		// There was an error deleting the user
		} else {
			return FALSE;
		}

	}


}

/* End of file Authentication.php */
/* Location: ./application/libraries/Authentication.php */