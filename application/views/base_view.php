<?php date_default_timezone_set('Asia/Bangkok'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <meta name="description" content="<?php echo $description; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Extra metadata -->
        <?php echo $metadata; ?>
        <!-- / -->

        
    </head>

<body class="">
     <section id="imgoverlay" style="display:none">
                    <div id="overlay"> 
                        <i class="fa fa-spinner fa-spin spin-big"></i>
                        <!-- <p>Loading...harap menunggu</p> -->
                    </div>
                </section>
    
	  <?php echo $body; ?>



    <script src="<?php echo assets_url() ?>js/jquery-1.11.3.min.js"></script>
    <link href="<?php echo assets_url() ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo assets_url() ?>font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo assets_url() ?>css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="<?php echo assets_url() ?>css/style.css" rel="stylesheet">
    <link href="<?php echo assets_url() ?>css/animate.css" rel="stylesheet">
    <link href="<?php echo assets_url() ?>css/custom.css" rel="stylesheet">

     <?php echo $css; ?>


    <!-- Mainly scripts -->
    
    <script src="<?php echo assets_url() ?>js/bootstrap.min.js"></script>
  
	 <!-- Extra javascript -->
        <?php echo $js; ?>
        <?php echo $assets; ?>

    <?php 
    if(isset($css_files) && isset($js_files)):
    foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; 
        endif;
    ?>
        
        <!-- / -->
    <script type="text/javascript">
        $(document).ajaxStart(function(){
            $("#imgoverlay").fadeIn(50);
        });
        $(document).ajaxComplete(function(){
            $("#imgoverlay").fadeOut(200);
        }); 
    </script>
    <script src="<?php echo assets_url() ?>js/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
                
            });

        </script> 
</html>
