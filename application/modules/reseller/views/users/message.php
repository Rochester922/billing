<div class="page-content">
  <!-- BEGIN PAGE HEADER-->
  <!-- BEGIN PAGE BAR -->
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="<?php echo site_url('reseller/dashboard'); ?>">Home</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span><?php echo $module; ?></span>
      </li>
    </ul>
    <div class="page-toolbar">
      <div class="btn-group">
        <a class="btn red-thunderbird" href="<?= site_url('reseller/users/index'); ?>" ><i class="fa fa-arrow-left"></i> BACK </a>
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
          <?php echo form_open('reseller/users/message/'.$row->account,array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
          <!-- <form action="#" id="form_sample_3" class="form-horizontal"> -->
          <div class="form-body">
            
            <div class="form-group <?php if(form_error('message')) { echo 'has-error'; }?>">
              <div class="col-md-6 col-md-offset-3">
                 <textarea name="message" class="form-control" placeholder="Type Your Message" rows="5"></textarea>
                 <?php echo form_error('message','<span class="help-block">','</span>');?>
              </div>
            </div>
              <!-- /.form-group -->
            </div>
            <div class="form-actions">
              <div class="row">
                <div class="col-md-offset-3 col-md-9">
                  <button type="submit" class="btn green-jungle"><i class="fa fa-send"></i> Send </button>
              <a class="btn red-thunderbird" href="<?= site_url('reseller/users/index'); ?>" ><i class="fa fa-ban"></i> Cancel </a>
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