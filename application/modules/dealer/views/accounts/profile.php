<!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
     
    </div>
    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="<?= site_url('dealer/dashboard/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
          <?php echo form_open('dealer/profile/',array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
          <!-- <form action="#" id="form_sample_3" class="form-horizontal"> -->
          
          <!-- /.form-group -->
          <div class="form-group  <?= (form_error('old_password')) ? 'has-error' : '' ; ?>">
            <label for="inputPassword9" class="control-label col-sm-4">Current Password</label>
            <div class="col-sm-5">
              <input type="password" class="form-control" placeholder="Type your current password" id="inputPassword9" name="old_password" />
              <?= form_error('old_password','<span class="help-block">','</span>');?>
            </div>
          </div>
          <div class="form-group  <?= (form_error('new_password')) ? 'has-error' : '' ; ?>">
            <label for="inputPassword9" class="control-label col-sm-4">New Password</label>
            <div class="col-sm-5">
              <input type="password" class="form-control" placeholder="Type your New Password" id="inputPassword9" name="new_password" />
              <?= form_error('new_password','<span class="help-block">','</span>');?>
            </div>
          </div>
          <div class="form-group <?= (form_error('new_confirm_passsword')) ? 'has-error' : '' ; ?>">
            <label for="inputPassword9" class="control-label col-sm-4">Retype New Password</label>
            <div class="col-sm-5">
              <input type="password" class="form-control" placeholder="ReType your New Password" id="inputPassword9" name="new_confirm_passsword" />
              <?= form_error('new_confirm_passsword','<span class="help-block">','</span>');?>
            </div>
          </div>
          
          <!-- /.form-group -->
         </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-offset-4 col-md-9">
                <button type="submit" class="btn btn-sm btn-success"><i class="icon-floppy-disk"></i> SAVE </button>
                <a class="btn btn-sm btn-danger" href="<?= site_url('dealer/dashboard'); ?>" ><i class="icon-blocked"></i> Cancel </a>
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