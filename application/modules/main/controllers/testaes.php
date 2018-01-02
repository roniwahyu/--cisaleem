<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class testaes extends MX_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('AES5');
	}
	public function index()
	{
		start();
		$token=$this->myauth->gentokenreg();
		$token2=$this->myauth->gentokenreg32();
		// include 'AES.php';
	    $inputText = "Hallo";
	    $inputKey = "abcdefghijklmnopqrstuvwxyzuvwxyz";
	    $blockSize = 192;
	    $aes = new AES5($inputText, $token2, $blockSize);
	    $enc = $aes->encrypt();
	    $aes->setData($enc);
	    $dec=$aes->decrypt();
	    echo "After encryption: ".$enc."<br/>";
	    echo "After decryption: ".$dec."<br/>";	
	    echo "Token: ".$token."<br/>";	
	    echo "Token: ".$token2."<br/>";	
	}

}

/* End of file aes.php */
/* Location: ./application/modules/main/controllers/aes.php */
 ?>