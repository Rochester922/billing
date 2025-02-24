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
  <h1 class="page-title"> <?php echo $module; ?>
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
            <span class="caption-subject font-dark sbold uppercase"><?php echo $title; ?></span>
          </div>
          <div class="actions">
          </div>
        </div>
        <div class="portlet-body">
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