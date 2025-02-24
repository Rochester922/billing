<!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
     
    </div>
    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="<?= site_url('manager/users/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
          <?php echo form_open('manager/check_mac',array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
          <!-- <form action="#" id="form_sample_3" class="form-horizontal"> -->
     
            <div class="form-group <?= (form_error('mac')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">MAC</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" id="mac_mask" name="mac" placeholder="00:1A:79:__:__:__" value="<?= set_value('mac');?>" />
                <?= form_error('mac','<span class="help-block">','</span>');?>
              </div>
            </div>
            <?php if(!empty($status)) { ?>
              <div class="form-group">
              <label for="inputPassword9" class="control-label col-sm-3">Status</label>
              <div class="col-sm-6 control-label">
                <?php echo $status; ?>
              </div>
            </div>
            <?php } ?>
             <?php if(!empty($expired)) { ?>
              <div class="form-group">
              <label for="inputPassword9" class="control-label col-sm-3">Expriy Status</label>
              <div class="col-sm-6 control-label">
                <?php echo $expired; ?>
              </div>
            </div>
            <?php } ?>
            <!-- /.form-group -->
          </div>
         
          <div class="form-group">
            <div class="row">
              <div class="col-md-offset-3 col-md-9">
                <button type="submit" class="btn btn-sm btn-success"><i class="icon-search4"></i> Submit </button>
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