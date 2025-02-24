
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <!-- Global stylesheets -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets');?>/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets');?>/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets');?>/css/core.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets');?>/css/components.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url('assets');?>/css/colors.css" rel="stylesheet" type="text/css">
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <script type="text/javascript" src="<?php echo base_url('assets');?>/js/plugins/loaders/pace.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('assets');?>/js/core/libraries/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('assets');?>/js/core/libraries/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('assets');?>/js/plugins/loaders/blockui.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('assets');?>/js/plugins/ui/nicescroll.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url('assets');?>/js/plugins/ui/drilldown.js"></script>
  <!-- /core JS files -->


  <!-- Theme JS files -->
  <script type="text/javascript" src="<?php echo base_url('assets');?>/js/core/app.js"></script>
  <!-- /theme JS files -->

</head>

<body class="login-container">
  <!-- Page container -->
  <div class="page-container">

    <!-- Page content -->
    <div class="page-content">

      <!-- Main content -->
      <div class="content-wrapper">

        <!-- Simple login form -->
        <?php echo form_open('login');?>
          <div class="panel panel-body login-form">
        
            <div class="text-center">
              <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
              <h5 class="content-group">Login to your account <small class="display-block">Enter your credentials below</small></h5>
            </div>
             <?php echo $this->session->flashdata('msg');?>
            <div class="form-group has-feedback has-feedback-left <?php echo (form_error('login')) ? 'has-error' : '' ;?>">
              <input type="text" class="form-control" name="login" value="<?= set_value('login');?>" placeholder="Username">
              <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
              </div>
              <?php echo form_error('login','<span class="help-block">','</span>');?>
            </div>

            <div class="form-group has-feedback has-feedback-left <?php echo (form_error('password')) ? 'has-error' : '' ;?> ">
              <input type="password" name="password" class="form-control" placeholder="Password">
              <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
              </div>
              <?php echo form_error('password','<span class="help-block">','</span>');?>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>
            </div>

            
          </div>
        </form>
        <!-- /simple login form -->

      </div>
      <!-- /main content -->

    </div>
    <!-- /page content -->

  </div>
  <!-- /page container -->


  <!-- Footer -->
  <div class="footer text-muted text-center">
    &copy; <?= date('Y');?>. 
  </div>
  <!-- /footer -->

</body>
</html>
