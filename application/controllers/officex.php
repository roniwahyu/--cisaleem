<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Office extends MX_Controller {
	function __construct(){
		parent::__construct();
	}
	public function index()
	{
		
	}
	function offices_management()
		{
		$crud = new grocery_CRUD();
		 
		$crud->set_theme('datatables');
		$crud->set_table('offices');
		$crud->set_subject('Office');
		$crud->required_fields('city');
		$crud->columns('city','country','phone','addressLine1','postalCode');
		 
		$output = $crud->render();
		 
		$this->_example_output($output);
		}

	function employees_management(){
		$crud = new grocery_CRUD();
		 
		$crud->set_theme('datatables');
		$crud->set_table('employees');
		$crud->set_relation('officeCode','offices','city');
		$crud->display_as('officeCode','Office City');
		$crud->set_subject('Employee');
		 
		$output = $crud->render();
		 
		$this->_example_output($output);
		}
	function customers_management()
	{
		$crud = new grocery_CRUD();
		 
		$crud->set_table('customers');
		$crud->columns('customerName','contactLastName','phone','city','country','salesRepEmployeeNumber','creditLimit');
		$crud->display_as('salesRepEmployeeNumber','from Employeer')
		             ->display_as('customerName','Name')
		             ->display_as('contactLastName','Last Name');
		$crud->required_fields('customerName','contactLastName');
		$crud->set_subject('Customer');
		$crud->set_relation('salesRepEmployeeNumber','employees','lastName');
		 
		$output = $crud->render();
		 
		$this->_example_output($output);
	}
	function orders_management()
	{
		$crud = new grocery_CRUD();
		 
		$crud->set_relation('customerNumber','customers','contactLastName');
		$crud->display_as('customerNumber','Customer');
		$crud->set_table('orders');
		$crud->set_subject('Order');
		$crud->unset_add();
		$crud->unset_delete();
		 
		$output = $crud->render();
		 
		$this->_example_output($output);
		}
	function products_management()
	{
		$crud = new grocery_CRUD();
		 
		$crud->set_table('products');
		$crud->set_subject('Product');
		$crud->unset_columns('productDescription');
		$crud->callback_column('buyPrice',array($this,'valueToEuro'));
		 
		$output = $crud->render();
		 
		$this->_example_output($output);
	}    
	 
	function valueToEuro($value, $row)
	{
		return $value.' &euro;';
	}
}

/* End of file office.php */
/* Location: ./application/modules/office/controllers/office.php */

 ?>