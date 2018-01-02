<?php $this->load->view('auth/header') ?>
<div class="container" class="min-height:800px;height:800px;">
    <div class="row">
          <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            
          </div>
          <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 white-bg" style="color:#333">
            
          
        <?php echo form_open("auth/register");?>
        <legend>
          <h1><?php echo lang('create_user_heading');?></h1>
        </legend>
        <p><?php echo lang('create_user_subheading');?></p>

        <div id="infoMessage"><?php echo $message;?></div>


        <p>
              <?php echo lang('create_user_fname_label', 'first_name');?> <br />
              <?php echo form_input($first_name);?>
        </p>

        <p>
              <?php echo lang('create_user_lname_label', 'last_name');?> <br />
              <?php echo form_input($last_name);?>
        </p>
        
        <?php
        if($identity_column!=='email') {
            echo '<p>';
            echo lang('create_user_identity_label', 'identity');
            echo '<br />';
            echo form_error('identity');
            echo form_input($identity);
            echo '</p>';
        }
        ?>

       
        <p>
              <?php echo lang('create_user_email_label', 'email');?> <br />
              <?php echo form_input($email);?>
        </p>

      
        <p>
              <?php echo lang('create_user_password_label', 'password');?> <br />
              <?php echo form_input($password);?>
        </p>

        <p>
              <?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
              <?php echo form_input($password_confirm);?>
        </p>


        <p><?php echo form_submit('submit', lang('create_user_submit_btn'),'class="btn btn-primary"');?></p>

        <?php echo form_close();?>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
          
        </div>
</div>
</div>

<?php $this->load->view('auth/footer') ?> 