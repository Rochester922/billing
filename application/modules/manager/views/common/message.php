<!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      
    </div>
    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="<?= site_url('manager/dashboard/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
      </div>
    </div>
  </div>
</div>
<!-- /page header -->
<!-- Page container -->
<div class="page-container">
  <!-- Page content -->
  <div class="page-content">
    <!-- Main content -->
    <div class="content-wrapper">
      <!-- Basic responsive configuration -->
      <div class="panel panel-flat col-md-7 col-md-offset-2">
        <div class="panel-heading">
          <h5 class="panel-title"><?= $title; ?></h5>
          <div class="heading-elements">
            
          </div>
        </div>
        <div class="panel-body">
          <!-- BEGIN FORM-->
          <?php echo form_open('manager/message', array('class' => 'form-horizontal', 'id' => 'form_sample_3')); ?>
          <!-- <form action="#" id="form_sample_3" class="form-horizontal"> -->
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Users</label>
              <div class="col-md-5">
                <select class="form-control" name="type" onchange="show_custom_selection(this.value);">
                  <option value="All" selected="">To All</option>
                  <option value="Custom">Custom Selection</option>
                </select>
              </div>
            </div>
            <div class="form-group" >
              <div class="col-md-12" id="cust_sel" >
                <select name="users[]" class="form-control"  multiple="" disabled="">
                    <?php foreach ($user_details->result() as $users_data): ?>
                  <option value="<?php echo $users_data->account ?>"> <?php echo $users_data->account;?></option>
                  <?php endforeach;?>
                </select>
              </div>
            </div>
            <div class="form-group last <?php if(form_error('message')) { echo 'has-error'; }?>">
              <textarea name="message" class="form-control" placeholder="Type Your Message" rows="5"></textarea>
              <?php echo form_error('message','<span class="help-block">','</span>');?>
            </div>
            <!-- /.form-group -->
           </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-offset-4 col-md-9">
                <button type="submit" class="btn btn-sm btn-success"><i class="icon-enter"></i> SEND </button>
                <a class="btn btn-sm btn-danger" href="<?= site_url('manager/users/index'); ?>" ><i class="icon-blocked"></i> Cancel </a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- /basic responsive configuration -->
  </div>
  <!-- /main content -->
</div>
<!-- /page content -->
</div>
<!-- /page container -->