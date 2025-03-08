<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $app_title; ?> :: <?php echo (!empty($title)) ? $title : ''; ?></title>
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets'); ?>/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets'); ?>/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets'); ?>/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets'); ?>/css/components.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets'); ?>/css/colors.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets'); ?>/master.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <!-- Core JS files -->
    <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/plugins/loaders/blockui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/plugins/ui/nicescroll.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/plugins/ui/drilldown.js"></script>
    <!-- /core JS files -->
    <!--datatables and select -->
    <script type="text/javascript"> var SiteURL = "<?php echo site_url('manager'); ?>";</script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>
	  <?php if($this->uri->segment(2)!="tickets") { ?>
		  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/selects/select2.min.js"></script>
	  <?php } ?>
	  <!-- checkbox -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/forms/styling/uniform.min.js"></script>
    <!-- Theme JS files -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/notifications/pnotify.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/inputmask.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/pickers/daterangepicker.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/pickers/anytime.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/pickers/pickadate/picker.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/pickers/pickadate/picker.date.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/pickers/pickadate/picker.time.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/pickers/pickadate/legacy.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/core/app.js"></script>
	  <?php if($this->uri->segment(2)!="tickets") { ?>
		  <script type="text/javascript" src="<?php echo base_url('assets'); ?>/master.js"></script>
	  <?php } else { ?>
		  <script type="text/javascript" src="<?php echo base_url('assets'); ?>/master-noselect.js"></script>
	  <?php } ?>
	  <!-- /theme JS files -->
    <style type="text/css">
    .action-left{
      margin-right: 5px; 
    }
    .action_btns{
      margin-left: 10px;
      /*float: left;*/
    }
    h5.panel-title, .h5.panel-title{
      font-weight: bold;
    }
    .btn-sm:not(.btn-rounded), .btn-group-sm > .btn:not(.btn-rounded), .btn-xs:not(.btn-rounded), .btn-group-xs > .btn:not(.btn-rounded) {
    border-radius: 0px !important;
    }
    .form-control{
    border-radius: 0px !important;
    background: #f7f7f7;
    }
    label{
    font-weight: bold;
    text-align: right !important;
    }
    </style>
  </head>
  <body>
    <!-- Main navbar -->
    <div class="navbar navbar-inverse">
      <div class="navbar-header">
        <a class="navbar-brand" href="<?=site_url('manager/dashboard');?>"><?php $top_remain_crdt = $this->transaction_model->get_credit_balance($auth_info['username']);?>
       <span class="label <?php if($top_remain_crdt < 10) { echo 'bg-danger-400'; }elseif($top_remain_crdt < 20 && $top_remain_crdt > 10){ echo 'bg-warning-400';}else{ echo 'bg-success-400';}?>  label-roundless"> <i class="icon-coins"></i> <?php echo $this->transaction_model->get_credit_balance($auth_info['username']);?> Credits Remaining </span></a>
        <ul class="nav navbar-nav pull-right visible-xs-block">
          <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        </ul>
      </div>
      <div class="navbar-collapse collapse" id="navbar-mobile">
         <p class="navbar-text"><span class="label bg-success-400 label-roundless">Last Login : <?php echo  $this->dealer_model->get_last_login($auth_info['username']);?></span></p>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown dropdown-user">
            <a class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo base_url('assets'); ?>/images/placeholder.jpg" alt="">
              <span><?=$auth_info['name'];?></span>
              <i class="caret"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li><a href="<?=site_url('manager/profile');?>"><i class="icon-user-plus"></i> My profile</a></li>
              <li><a href="<?=site_url('manager/logout');?>"><i class="icon-switch2"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <!-- /main navbar -->
    <!-- Second navbar -->
    <div class="navbar navbar-default" id="navbar-second">
      <ul class="nav navbar-nav no-border visible-xs-block">
        <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
      </ul>
      <div class="navbar-collapse collapse" id="navbar-second-toggle">
        <ul class="nav navbar-nav">
          <?php $active_uri = $this->uri->segment(2);?>
          <li class="<?=($active_uri == 'dashboard') ? 'active' : '';?>"><a href="<?=site_url('manager/dashboard');?>"><i class="icon-display4 position-left"></i> Dashboard</a></li>
          <li class="<?=($active_uri == 'resellers') ? 'active ' : '';?>">
            <a href="<?php echo site_url('manager/resellers/index'); ?>">
              <i class="icon-user position-left"></i>
              Resellers
            </a>
          </li>
          <li class="<?=($active_uri == 'dealers') ? 'active ' : '';?> ">
            <a href="<?php echo site_url('manager/dealers/index'); ?>" >
              <i class="icon-users2 position-left"></i>
              Dealers
            </a>
          </li>
          <li class=" <?=($active_uri == 'users') ? 'active ' : '';?>">
            <a href="<?php echo site_url('manager/users/index'); ?>" >
              <i class="icon-users4 position-left"></i>
              Users
            </a>
          </li>
          <li class="<?=($active_uri == 'message') ? 'active ' : '';?>">
            <a href="<?php echo site_url('manager/message'); ?>" >
              <i class="icon-envelope position-left"></i>
              Message
              <!-- <span class="selected"></span> -->
            </a>
          </li>
            <li class="<?=($active_uri == 'tickets') ? 'active ' : '';?>">
                <a href="<?php echo site_url('manager/tickets'); ?>" >
                    <i class="icon-envelope position-left"></i>
                    Tickets
                    <!-- <span class="selected"></span> -->
                </a>
            </li>
         <li class="<?=($active_uri == 'transactions') ? 'active' : '';?>"><a href="<?=site_url('manager/transactions');?>"><i class="icon-coins position-left"></i> Transactions </a></li>
         <li class="<?=($active_uri == 'check_mac') ? 'active' : '';?>"><a href="<?=site_url('manager/check_mac');?>"><i class="icon-server position-left"></i> Check MAC </a></li>
        </ul>
        <div class="col-sm-3 col-md-3 navbar-right">
          <?php echo form_open('manager/users/index', array('class' => 'navbar-form navbar-right', 'method' => 'get')); ?>
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search users by mac or phone or username" name="query">
            <div class="input-group-btn">
              <button class="btn btn-success " type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
  <!-- /second navbar -->
  <?php echo $view_content; ?>

  <!-- Footer -->
  <div class="footer text-muted">
    &copy;
    <?php
      //$fromYear = 2015; 
      $thisYear = (int)date('Y'); 
      $fromYear = $thisYear; 
      echo $fromYear . (($fromYear != $thisYear) ? '-' . $thisYear : '');
    ?>.        
  </div>


  <?php $global_msg = $this->session->flashdata('global_msg');?>
  <?php if (!empty($global_msg)): ?>
  <!-- Modal -->
  <div class="modal fade" id="myModal"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content " style="background-color: #a90f0f;">
        <div class="modal-body">
          <p style="color: #fff; font-size: 16px; font-weight: bold; text-align: center;" ><?php echo $global_msg; ?></p>
        </div>
        <div class="modal-footer"  style="border: none; text-align: center;">
          <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
  <?php endif;?>
  <!-- /footer -->
  <?php
$flash_msg = $this->session->flashdata('msg');
$msg_type  = $this->session->flashdata('msg_type');?>
  <script type="text/javascript">
  $(function() {
  <?php if (!empty($flash_msg)): ?>
  <?php $alert_type  = ($msg_type == 'danger') ? 'error' : 'success';?>
  <?php $alert_title = ($msg_type == 'danger') ? 'Error' : 'Success';?>
  <?php $alert_icon  = ($msg_type == 'danger') ? 'icon-warning22' : 'icon-checkmark3';?>
  new PNotify({
  title: '<?=$alert_title;?>',
  text: "<?php echo $flash_msg; ?>",
  icon: '<?php echo $alert_icon; ?>',
  type: '<?php echo $alert_type; ?>'
  });
  <?php endif;?>
  <?php if (!empty($global_msg)): ?>
  $('#myModal').modal("show");
  <?php endif;?>
  });
  </script>
</body>
</html>
