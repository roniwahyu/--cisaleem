<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * User table
 *
 * This table should contain the username and password fields specified below. It can contain any other fields, such as "first_name"
 */
$config['authentication']['user_table'] = 'users';


/**
 * User identifier field
 *
 * This field will usually be "id" or "user_id" but you could use something like "username"
 */
$config['authentication']['identifier_field'] = 'id';


/**
 * Username field
 *
 * This field can be named what ever you like, an example would be "email"
 */
$config['authentication']['username_field'] = 'username';
$config['authentication']['enc_username_field'] = 'enc_username';


/**
 * Password field
 */
$config['authentication']['password_field'] = 'password';
$config['authentication']['enc_password_field'] = 'enc_password';
$config['authentication']['email_field'] = 'email';
$config['authentication']['scheme_field'] = 'scheme';
$config['authentication']['token_field'] = 'tokenkey';
$config['authentication']['passwordhash_field'] = 'password_hash';
$config['authentication']['usernamehash_field'] = 'username_hash';
$config['authentication']['saltbefore_field'] = 'salt_before';
$config['authentication']['saltafter_field'] = 'salt_after';



/* End of file authentication.php */
/* Location: ./application/config/authentication.php */