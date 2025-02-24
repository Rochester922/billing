<div class="page-content">
  <!-- BEGIN PAGE HEADER-->
  
  <!-- BEGIN PAGE BAR -->
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="<?php echo site_url('admin/dashboard'); ?>">Home</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span><?php echo $module; ?></span>
      </li>
    </ul>
    <div class="page-toolbar">
      <div class="btn-group">
        <button class="btn red" id="goback"><i class="fa fa-arrow-left"></i> BACK </button>

      </div>
    </div>
  </div>
  <!-- END PAGE BAR -->
  <!-- BEGIN PAGE TITLE-->
  <h1 class="page-title"> <?php echo $module;?>
  
  </h1>
  <!-- END PAGE TITLE-->
  <!-- END PAGE HEADER-->
  <!-- BEGIN DASHBOARD STATS 1-->
  <div class="row">
    <div class="col-md-12">
      <!-- BEGIN VALIDATION STATES-->
      <div class="portlet light portlet-fit portlet-form bordered">
        <div class="portlet-title">
          <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase"><?php echo $title;?></span>
          </div>
          <div class="actions">
            
          </div>
        </div>
        <div class="portlet-body">
          <!-- BEGIN FORM-->
          <?php echo form_open('admin/managers/add',array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
          <!-- <form action="#" id="form_sample_3" class="form-horizontal"> -->
            <div class="form-body">
              
                <div class="form-group <?= (form_error('name')) ? 'has-error' : '' ; ?>">
                  <label for="inputName9" class="control-label col-sm-3">Name</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" placeholder="Max Hodgson" id="inputName9" name="name" value="<?= set_value('name');?>" />
                    <?= form_error('name','<span class="help-block">','</span>');?>
                  </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group <?= (form_error('username')) ? 'has-error' : '' ; ?>">
                  <label for="inputEmail9" class="control-label col-sm-3">Username</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" placeholder="examplelogin" id="inputEmail9" value="<?= set_value('username');?>" name="username">
                    <?= form_error('username','<span class="help-block">','</span>');?>
                  </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group last <?= (form_error('password')) ? 'has-error' : '' ; ?>">
                  <label for="inputPassword9" class="control-label col-sm-3">Password</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" placeholder="Type a password" id="inputPassword9" name="password" />
                    <?= form_error('password','<span class="help-block">','</span>');?>
                  </div>
                </div>
                <!-- /.form-group -->
              </div>
              <div class="form-actions">
                <div class="row">
                  <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn green">Submit</button>
                    <button type="button" class="btn default">Cancel</button>
                  </div>
                </div>
              </div>
            </form>
            <!-- END FORM-->
          </div>
          <!-- END VALIDATION STATES-->
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <!-- END DASHBOARD STATS 1-->
    
  </div>