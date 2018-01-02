<form action="<?php echo (!empty($post)&&isset($post))?$post:'' ?>" method="POST" role="form">
	<legend>Form title</legend>

	<div class="form-group">
		<label for="">Username</label>
		<input name="username" type="text" class="form-control" id="" placeholder="Input field">
	</div>
	<div class="form-group">
				<input name="token" type="hidden" class="form-control" id="" value="<?php echo (!empty($token)&&isset($token))?$token:'' ?>" placeholder="Input field">

		<label for="">Password</label>
		<input name="password" type="password" class="form-control" id="" placeholder="Input field">
	</div>
	<button name="submit" value="1" type="submit" class="btn btn-primary">Submit</button>
</form>