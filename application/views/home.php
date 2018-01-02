<div class="jumbotron">
	<div class="container text-center">
		<h1>SQL Injection </h1>
		<h1>Detection And Prevention</h1>
		<p>By Salem Aldrder</p>
		<p>
			<?php 
				// echo $this->session->userdata('identifier');
				if ($this->myauth->is_loggedin())
					{
						// echo "<p>logedin</p>";
						// $this->template->load_view('home');
						echo '<a href="'. base_url('home/logout').'" class="btn btn-primary btn-lg">Logout</a>';
					} else {
						// echo "not logedin";
						echo '<a href="'.base_url('home/login1').'" class="btn btn-primary btn-lg">Login</a>';
						// redirect('simpleauth/login1');
						// User is NOT logged in
					}

			 ?>
		</p>
	</div>
</div>