<!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
      <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php echo $module; ?></span> </h4>
      <ul class="breadcrumb breadcrumb-caret position-right">
        <li><a href="<?= site_url('admin/dashboard'); ?>">Home</a></li>
        <li class="active"><?php echo $module; ?></li>
      </ul>
    </div>
    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="<?= site_url('admin/dealers/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
      <div class="panel panel-flat">
        <div class="panel-heading">
          <h5 class="panel-title"><?= $title; ?></h5>
          <div class="heading-elements">
           
          </div>
        </div>
        <div class="panel-body">
          <!-- BEGIN FORM-->
          <?php echo form_open('admin/dealers/add',array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
          <!-- <form action="#" id="form_sample_3" class="form-horizontal"> -->
            <div class="form-body">
              
                <div class="form-group <?= (form_error('name')) ? 'has-error' : '' ; ?>">
                  <label for="inputName9" class="control-label col-sm-3">Name</label>
                  <div class="col-sm-3">
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
                <div class="form-group <?= (form_error('password')) ? 'has-error' : '' ; ?>">
                  <label for="inputPassword9" class="control-label col-sm-3">Password</label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" placeholder="Type a password" id="inputPassword9" name="password" />
                    <?= form_error('password','<span class="help-block">','</span>');?>
                  </div>
                </div>
                <div class="form-group last <?= (form_error('username_owner')) ? 'has-error' : '' ; ?>">
                  <label for="inputPassword9" class="control-label col-sm-3">Select Reseller</label>
                  <div class="col-sm-3">
                    <select class="form-control" name="username_owner">
                      <option value="" selected="selected">Select Reseller</option>
                      <?php foreach($resellers as $reseller):?>
                        <option value="<?= $reseller->username; ?>"> <?= $reseller->username; ?> </option>
                      <?php endforeach; ?>
                    </select>
                    <?= form_error('username_owner','<span class="help-block">','</span>');?>
                  </div>
                </div>
                <!-- /.form-group -->
				<div class="form-group last <?= (form_error('tickets_manager')) ? 'has-error' : '' ; ?>">
					<label for="tickets_manager"  class="control-label col-sm-3">Tickets Manager</label>
					<div class="col-sm-3">
						<select class="form-control" name="tickets_manager" id="tickets_manager">
							<option value="0">No</option>
							<option value="1" >Yes</option>
						</select>
					</div>
				</div>
				<!-- /.form-group -->
			</div>
          <div class="form-actions">
            <div class="row">
              <div class="col-md-offset-3 col-md-9">
                <button type="submit" class="btn btn-sm btn-success"><i class="icon-floppy-disk"></i> Submit </button>
                <a class="btn btn-sm btn-danger" href="<?= site_url('admin/dealers/index'); ?>" ><i class="icon-blocked"></i> Cancel </a>
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
