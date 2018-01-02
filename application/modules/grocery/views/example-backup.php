<!-- <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body> -->
	<div>
		<a href='<?php echo site_url('grocery/examples/customers_management')?>'>Customers</a> |
		<a href='<?php echo site_url('grocery/examples/orders_management')?>'>Orders</a> |
		<a href='<?php echo site_url('grocery/examples/products_management')?>'>Products</a> |
		<a href='<?php echo site_url('grocery/examples/offices_management')?>'>Offices</a> | 
		<a href='<?php echo site_url('grocery/examples/employees_management')?>'>Employees</a> |		 
		<a href='<?php echo site_url('grocery/examples/film_management')?>'>Films</a> |
		<a href='<?php echo site_url('grocery/examples/multigrids')?>'>Multigrid [BETA]</a>
		
	</div>
	<div style='height:20px;'></div>  
    <div>
		<?php echo $output; ?>
    </div>
    <?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<!-- </body> -->
<!-- </html> -->
