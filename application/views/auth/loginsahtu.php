<?php $this->load->view('auth/header') ?>
<div class="container">
    <h1 class="logo-name text-center">SQL Injection</h1>
    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <h2 class="font-bold">SQL Injection Detection & Prevention</h2>
          <p>This Web application is used specifically for testing and evaluation of SQL Injection & Prevention mechanisms. There are several login schemes that are used, such as:
            <ol>
                <li><a href="login1.php">Unsecure Login Scheme</a></li>
                <li><a href="login2.php">Simple Secure Login Scheme</a></li>
                <li><a href="login3.php">Login Scheme with Encrypted Secret Key</a></li>
                <li><a href="login4.php">Login Scheme with Hash Key</a></li>
                <li><a href="login5.php">Login Scheme with Hybrid Encrypted Secret Key (Which is proposed in this thesis)</a></li>
            </ol>
          </p>

            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            
            <?php echo form_open("auth/login1",'role="form" class="form"');?>
            <legend><h1><?php echo lang('login_heading');?></h1></legend>
            <p><?php echo lang('login_subheading');?></p>

              

            <div id="infoMessage"><?php echo $message;?></div>

              <div class="form-group">
                <label for=""><?php echo lang('login_identity_label', 'identity');?></label>
                <input type="text" class="form-control input-lg" placeholder="Username" required="" name="identity" id="identity">
                <input type="text" class="hidden" name="tipe" value="<?php echo base64_encode("unsecure") ?>">
                <?php //echo form_input($identity,'class="form-control input-lg"');?>
              </div>
              <div class="form-group">
                <label for=""><?php echo lang('login_password_label', 'password');?></label>
                <input type="password" class="form-control input-lg" placeholder="Password" required="" name="password" id="password">
              </div>
              <div class="form-group">
              </div>


              <div class="form-group">
                <label for=""><?php echo lang('login_remember_label', 'remember');?></label>
                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
              </div>


              <p><?php echo form_submit('submit', lang('login_submit_btn'),'class="btn btn-primary"');?></p>

            <?php echo form_close();?>

            <p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
            </div>
<?php $this->load->view('auth/footer') ?>