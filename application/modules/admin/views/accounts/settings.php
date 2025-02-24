<!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      
    </div>
    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="<?= site_url('admin/dashboard/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
          <?php echo form_open('admin/settings/', array('class' => 'form-horizontal', 'id' => 'form_sample_3')); ?>
          <!-- <form action="#" id="form_sample_3" class="form-horizontal"> -->
          <div class="form-group <?php if (form_error('title')): echo 'has-error';endif;?>">
            <label class="col-md-3 control-label">Title</label>
            <div class="col-md-7">
              <input type="text" class="form-control" name="title" value="<?php echo $settings->title; ?>">
              <?php echo form_error('title', '<span class="help-block">', '</span>'); ?>
            </div>
          </div>
          <div class="form-group <?php if (form_error('email')): echo 'has-error';endif;?>">
            <label class="col-md-3 control-label">Admin's Email</label>
            <div class="col-md-5">
              <input type="text" class="form-control" name="email" value="<?php echo $settings->email; ?>">
              <?php echo form_error('email', '<span class="help-block">', '</span>'); ?>
            </div>
          </div>
          <div class="form-group last <?php if (form_error('global_msg')): echo 'has-error';endif;?>">
            <label class="col-md-3 control-label">Global Anouncement</label>
            <div class="col-md-7">
              <textarea name="global_msg" class="form-control" rows="5"><?php echo $settings->global_msg; ?></textarea>
              <?php echo form_error('global_msg', '<span class="help-block">', '</span>'); ?>
            </div>
          </div>
          <!-- /.form-group -->
        </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-offset-4 col-md-9">
                <button type="submit" class="btn btn-sm btn-success"><i class="icon-floppy-disk"></i> SAVE </button>
                <a class="btn btn-sm btn-danger" href="<?= site_url('admin/dashboard'); ?>" ><i class="icon-blocked"></i> Cancel </a>
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