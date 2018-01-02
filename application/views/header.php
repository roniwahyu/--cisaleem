<?php if ($this->myauth->is_loggedin()): ?>
<nav class="navbar navbar-static-top navbar-inverse" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo base_url('home') ?>">SQLIPA</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
            <!-- <li class="active"><a href="#">Link</a></li>
			<li><a href="#">Link</a></li> -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list"></i> Office Management <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href='<?php echo site_url('grocery/examples/customers_management ')?>'>Customers</a>
                    </li>
                    <li>
                        <a href='<?php echo site_url('grocery/examples/orders_management ')?>'>Orders</a>
                    </li>
                    <li>
                        <a href='<?php echo site_url('grocery/examples/products_management ')?>'>Products</a>
                    </li>
                    <li>
                        <a href='<?php echo site_url('grocery/examples/offices_management ')?>'>Offices</a>
                    </li>
                    <li>
                        <a href='<?php echo site_url('grocery/examples/employees_management ')?>'>Employees</a>
                    </li>
                    <li>
                        <a href='<?php echo site_url('grocery/examples/film_management ')?>'>Films</a>
                    </li>
                </ul>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php if ($this->myauth->is_loggedin()): ?>
            
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-refresh"></i>Testing <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href='<?php echo site_url('main/test_reg_batch128')?>'>Batch Register AES128 Test</a></li>
                    <li><a href='<?php echo site_url('main/test_reg_batch192')?>'>Batch Register AES192 Test</a></li> 
                    <li><a href='<?php echo site_url('main/test_reg_batch256')?>'>Batch Register AES256 Test</a></li>
                    <li>
                        <a href='<?php echo site_url('main/testloginaes/128')?>'>Login AES128 Test</a>
                    </li>
                    <li>
                        <a href='<?php echo site_url('main/testloginaes/192')?>'>Login AES192 Test</a>
                    </li>
                    <li>
                        <a href='<?php echo site_url('main/testloginaes/256')?>'>Login AES256 Test</a>
                    </li>
                    <li>
                        <a href='<?php echo site_url('main/testloginhash')?>'>Login Hash Test</a>
                    </li>
                    <li>
                        <a href='<?php echo site_url('main/testloginhybrid')?>'>Login Hybrid MD5+Salt Test</a>
                    </li>
                </ul>
            </li>
            <li><a href="<?php echo base_url('home/users') ?>"><i class="fa fa-users"></i> User List</a></li>
            <li><a href="<?php echo base_url('home/loginlist') ?>"><i class="fa fa-bar-chart"></i> Chart</a></li>
           
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url('home/logout') ?>">Logout</a></li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>
<?php endif; ?>