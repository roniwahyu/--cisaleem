<h1 class="logo-name text-center">SQL Injection</h1>
<div class="loginColumns animated fadeInDown">
    <div class="row" style="margin-bottom:100px">
        <div class="col-md-6">
            <h2 class="font-bold">SQL Injection Detection & Prevention</h2>
            <p>This Web application is used specifically for testing and evaluation of SQL Injection & Prevention mechanisms. There are several login schemes that are used, such as:
                <ol>
                
                    <li><a href="<?php echo base_url('main/') ?>loginsecretkeyform">Login Scheme with AES (Advanced Encrypted Standard)</a></li>
                    <li><a href="<?php echo base_url('main/') ?>loginhashform">Login Scheme with Hash Key</a></li>
                    <li><a href="<?php echo base_url('main/') ?>loginhybridform">Login Scheme with Hybrid (Hash + Salt) Which is proposed in the thesis</a></li>
                </ol>
            </p>
            <p><a href="http://localhost/!!cisaleem/public/home/loginlist" class="btn btn-primary btn-lg"><i class="fa fa-bar-chart fa2x"></i> Chart</a></p>
        </div>
        <div class="col-md-6">
            <h3 class="font-bold"><?= isset($title)?$title:''  ?></h3>
            <div class="ibox-content">
                <?php if(isset($msg)): ?>
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo isset($msg)?"<strong>Warning: </strong>". base64_decode($msg):''; ?>
                </div>
                <?php endif; ?>
                <form class="m-t" role="form" action="<?= isset($post)?$post:'' ?>" method="post" style="color:#333333">
                    <input name="token" type="hidden" class="form-control hidden" id="" value="<?php echo (!empty($token)&&isset($token))?$token:'' ?>" readonly >
                    <div class="form-group">
                        <input type="text" class="form-control input-lg" placeholder="Username" required="" name="username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control input-lg" placeholder="Password" required="" name="password">
                    </div>
                    <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary block full-width m-b">Login</button>
                    <a href="forgot_password.php">
                            <small>Forgot password?</small>
                        </a>
                    <p class="text-muted text-center">
                        <small>Do not have an account?</small>
                    </p>
                    <a class="btn btn-sm btn-success btn-block" href="<?php echo !empty($reg)?$reg:'#' ?>">Create an account</a>
                </form>
            </div>
        </div>
    </div>
</div>