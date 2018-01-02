<?php $this->load->view('auth/header') ?>
<div class="container" class="min-height:800px;height:800px;">
    <h1 class="text-center">SQL Injection Detection & Prevention</h1>
    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <h2 class="font-bold">SQL Injection Detection & Prevention</h2>
                <p>This Web application is used specifically for testing and evaluation of SQL Injection & Prevention mechanisms. There are several login schemes that are used, such as:
                  <ol>
                      <li><a href="<?php echo base_url('auth/') ?>login1">Unsecure Login Scheme</a></li>
                      <li><a href="<?php echo base_url('auth/') ?>login2">Simple Secure Login Scheme</a></li>
                      <li><a href="<?php echo base_url('auth/') ?>login3">Login Scheme with Encrypted Secret Key</a></li>
                      <li><a href="<?php echo base_url('auth/') ?>login4">Login Scheme with Hash Key</a></li>
                      <li><a href="<?php echo base_url('auth/') ?>login5">Login Scheme with Hybrid Encrypted Secret Key (Which is proposed in this thesis)</a></li>
                  </ol>
                </p>

            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

              <?php echo form_open("auth/login1",'role="form" class="form"');?>
              <legend>
                <h1><?php echo lang('login_heading');?></h1>
              </legend>
              <p><?php echo lang('login_subheading');?></p>
              <?php if(isset($message)): ?>
                <div class="alert alert-danger">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!-- <strong>Title!</strong> Alert body ... -->
                  <div id="infoMessage "><?php echo $message;?></div>
                </div>
              <?php endif; ?>


                <p>
                  <?php echo lang('login_identity_label', 'identity');?>
                  <?php echo form_input($identity);?>
                </p>

                <p>
                  <?php echo lang('login_password_label', 'password');?>
                  <?php echo form_input($password);?>
                </p>

                <p>
                  <?php echo lang('login_remember_label', 'remember');?>
                  <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                </p>


                <p><?php echo form_submit('submit', lang('login_submit_btn'),'class="btn btn-primary"');?></p>

              <?php echo form_close();?>

              <p><a  href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>

            </div>
        </div>
    </div>
</div>
<?php $this->load->view('auth/footer') ?>