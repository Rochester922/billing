<div class="page-content">
  <!-- BEGIN PAGE HEADER-->
  
  <!-- BEGIN PAGE BAR -->
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="<?php echo site_url('manager/dashboard'); ?>">Home</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span><?php echo $module; ?></span>
      </li>
    </ul>
    <div class="page-toolbar">
      <div class="btn-group">
         <a class="btn red-thunderbird" href="<?= site_url('manager/dashboard'); ?>" ><i class="fa fa-arrow-left"></i> BACK </a>
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
          <?php echo form_open('manager/users/add',array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
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
            <div class="form-group <?= (form_error('password')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">Password</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" placeholder="Type a password" id="inputPassword9" name="password" />
                <?= form_error('password','<span class="help-block">','</span>');?>
              </div>
            </div>
            <div class="form-group <?= (form_error('mac')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">MAC</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="mac_mask" name="mac" placeholder="00:1A:79:__:__:__" />
                <?= form_error('mac','<span class="help-block">','</span>');?>
              </div>
            </div>
            <div class="form-group <?php if(form_error('validity')): echo 'has-error'; endif; ?>">
              <label class="col-md-3 control-label">Validity</label>
              <div class="col-md-3">
                <select name="validity" class="form-control">
                  <option value="FREE_TRIAL" >Free trial</option>
                  <option value="1" selected="selected">1 Month</option>
                  <?php for($i=2; $i<=12; $i++){?>
                  <option value="<?php echo $i;?>" ><?php echo $i;?> Months</option>
                  <?php }?>
                </select>
                <?php echo form_error('validity','<span class="help-block">','</span>');?>
              </div>
            </div>
            
            <div class="form-group <?php if(form_error('status')): echo 'has-error'; endif; ?>">
              <label class="col-md-3 control-label">Status</label>
              <div class="col-md-3">
                <?php $options = array('0'=>'Active','1'=>'Inactive');?>
                <?php echo form_dropdown('status',$options,set_value('status'),'class="form-control"');?>
                <?php echo form_error('status','<span class="help-block">','</span>');?>
              </div>
            </div>
            <div class="form-group last <?= (form_error('reseller')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">Select Reseller</label>
              <div class="col-sm-3">
                <select class="form-control" name="reseller" id="reseller_drop" onchange="getDealer();">
                  <option value="" selected="selected">Select Reseller</option>
                  <?php foreach($resellers as $reseller):?>
                  <option value="<?= $reseller->username; ?>"> <?= $reseller->username; ?> </option>
                  <?php endforeach; ?>
                </select>
                <?= form_error('reseller','<span class="help-block">','</span>');?>
              </div>
            </div>
            <div class="form-group last <?= (form_error('dealer')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">Select Dealer</label>
              <div class="col-sm-3" id="dealer-list">
                <select class="form-control" name="reseller" disabled="disabled" >
                  <option value="" selected="selected">Select Dealer</option>
                </select>
                <?= form_error('dealer','<span class="help-block">','</span>');?>
              </div>
            </div>
            <div class="form-group <?php if(form_error('package')): echo 'has-error'; endif; ?>">
              <label class="col-md-3 control-label">Package</label>
              <div class="col-md-3">
                <select name="package" class="form-control" id="tariff_custom">
                  <option value="0" selected="selected">[Select Package]</option>
                  <?php $packages = $this->stalker_model->get_tariff();?>
                  <?php foreach ($packages->result() as $package):?>
                  <option value="<?php echo $package->id; ?>"><?php echo $package->name; ?></option>
                  <?php endforeach;?>
                </select>
                <?php echo form_error('package','<span class="help-block">','</span>');?>
              </div>
            </div>
            <div class="form-group" id="Custom_Packages" style="display: none;">
              <label class="col-md-3 control-label">Select Packages</label>
              <div class="col-md-5" id="show_packages">
                <div class="well well-sm">
                  <p><a href="javascript:void(0);" id="select_all">Select All</a> / <a href="javascript:void(0);" id="deselect_all">Deselect All</a></p>
                  <?php
                  $custom_pack_id = $this->stalker_model->get_custom_plan_id();
                  $packages_tar = $this->stalker_model->get_package($custom_pack_id);?>
                  <?php foreach ($packages_tar as $package): ?>
                  <p><input type="checkbox" class="checkbox_pack" name="packs[]" value="<?php echo $package->package_id; ?>"> <?php echo $package->name; ?> </p>
                  <?php endforeach;?>
                </div>
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