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
         <a class="btn red-thunderbird" href="<?= site_url('admin/users/index'); ?>" ><i class="fa fa-arrow-left"></i> BACK </a>
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
          <?php echo form_open('admin/users/edit/'.$row->account,array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
          <!-- <form action="#" id="form_sample_3" class="form-horizontal"> -->
          <div class="form-body">
            <div class="form-group <?= (form_error('name')) ? 'has-error' : '' ; ?>">
              <label for="inputName9" class="control-label col-sm-3">Name</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" placeholder="Max Hodgson" id="inputName9" name="name" value="<?= $row->full_name;?>" />
                <?= form_error('name','<span class="help-block">','</span>');?>
              </div>
            </div>
            <!-- /.form-group -->
            <div class="form-group <?= (form_error('username')) ? 'has-error' : '' ; ?>">
              <label for="inputEmail9" class="control-label col-sm-3">Username</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" disabled="disabled" placeholder="examplelogin" id="inputEmail9" value="<?= $row->account; ?>" name="username">
                <?= form_error('username','<span class="help-block">','</span>');?>
              </div>
            </div>
            <!-- /.form-group -->
            <div class="form-group last <?= (form_error('password')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">Password</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" placeholder="Type a password" id="inputPassword9" name="password" value="<?= $row->password; ?>" />
                <?= form_error('password','<span class="help-block">','</span>');?>
              </div>
            </div>
            <!-- /.form-group -->
            <!-- /.form-group -->
            <div class="form-group last <?= (form_error('status')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">Status</label>
              <div class="col-sm-3">
                <select class="form-control" name="status">
                  <option value="0" <?= ($row->status==0) ? 'selected="selected"':'';?>>ACTIVE</option>
                  <option value="1" <?= ($row->status==1) ? 'selected="selected"':'';?>>INACTIVE</option>
                </select>
                <?= form_error('status','<span class="help-block">','</span>');?>
              </div>
            </div>
            <!-- /.form-group -->
            <!-- /.form-group -->
            <div class="form-group last <?= (form_error('password')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">Phone</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" placeholder="Type a Phone" id="inputPassword9" name="phone" value="<?= $row->phone; ?>" />
                <?= form_error('phone','<span class="help-block">','</span>');?>
              </div>
            </div>
            <!-- /.form-group -->
            <div class="form-group last <?= (form_error('comments')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">Comments</label>
              <div class="col-sm-5">
                <textarea class="form-control" name="note"><?= $row->note; ?></textarea>
                <?= form_error('note','<span class="help-block">','</span>');?>
              </div>
            </div>
            <!-- /.form-group -->
            <?php
            $is_dealer = $this->dealer_model->is_dealer($row->username);
            $parent = ($is_dealer==true) ? $this->users_model->get_reseller($row->username): $row->username;
            ?>
            <div class="form-group last <?= (form_error('reseller')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">Select Reseller</label>
              <div class="col-sm-3">
                <select class="form-control" name="reseller" id="reseller_drop" onchange="getDealer();">
                  <?php foreach($resellers as $reseller):?>
                  <option value="<?= $reseller->username; ?>" <?=  ($row->username==$reseller->username) ? 'selected="selected"': '';?> > <?= $reseller->username; ?> </option>
                  <?php endforeach; ?>
                </select>
                <?= form_error('reseller','<span class="help-block">','</span>');?>
              </div>
            </div>
            <?php if($is_dealer==true) {?>
            <div class="form-group last <?= (form_error('dealer')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">Select Dealer</label>
              <div class="col-sm-3" id="dealer-list">
                  <?php $reseller_login = $this->users_model->get_reseller($row->username); ?>
                  <?php $dealers = $this->dealer_model->get_all($reseller_login); ?>
                <select class="form-control" name="dealer">
                  <?php foreach($dealers as $dealer):?>
                  <option value="<?= $dealer->username; ?>" <?=  ($row->username===$dealer->username) ? 'selected="selected"': '';?> > <?= $dealer->username; ?> </option>
                  <?php endforeach; ?>
                </select>
                <?= form_error('dealer','<span class="help-block">','</span>');?>
              </div>
            </div>
            <?php }else{?>
            <div class="form-group last <?= (form_error('dealer')) ? 'has-error' : '' ; ?>">
              <label for="inputPassword9" class="control-label col-sm-3">Select Dealer</label>
              <div class="col-sm-3" id="dealer-list">
                <select class="form-control" name="dealer" disabled="disabled">
                  <option value="" selected="selected">Select Dealer</option>
                </select>
                <?= form_error('dealer','<span class="help-block">','</span>');?>
              </div>
            </div>
            <?php  } ?>
            <div class="form-group <?php if(form_error('package')): echo 'has-error'; endif; ?>">
              <label class="col-md-3 control-label">Package</label>
              <div class="col-md-5">
                <select name="package" class="form-control" id="tariff_custom">
                  <option value="0" <?php if($stalker->tariff_plan_id==0): echo 'selected="selected"'; endif;?>>[Default]</option>
                  <?php $packages = $this->stalker_model->get_tariff();?>
                  <?php foreach ($packages->result() as $package):?>
                  <option value="<?php echo $package->id; ?>" <?php if($stalker->tariff_plan_id==$package->id): echo 'selected="selected"'; endif;?> ><?php echo $package->name; ?></option>
                  <?php endforeach;?>
                </select>
                <?php echo form_error('package','<span class="help-block">','</span>');?>
              </div>
            </div>
            <?php $custom_pack_id = $this->stalker_model->get_custom_plan_id();?>
            <div class="form-group" id="Custom_Packages" <?php if($stalker->tariff_plan_id!=$custom_pack_id) { ?> style="display: none;" <?php } ?>>
              <label class="col-md-3 control-label">Select Packages</label>
              <div class="col-md-7" id="show_packages">
                <div class="well well-sm">
                  <p><a href="javascript:void(0);" id="select_all">Select All</a> / <a href="javascript:void(0);" id="deselect_all">Deselect All</a></p>
                  <?php
                  // $custom_pack_id = $this->stalker_model->get_custom_plan_id();
                  $packages_tar = $this->stalker_model->get_package($custom_pack_id);
                  $packages_in = $this->stalker_model->get_user_packages($stalker->id);  ?>
                  <?php foreach ($packages_tar as $package): ?>
                  <p><input type="checkbox" class="checkbox_pack" <?php if(in_array($package->package_id, $packages_in)) { echo 'checked="checked"'; }?> name="packs[]" value="<?php echo $package->package_id; ?>"> <?php echo $this->stalker_model->get_package_name($package->package_id); ?></p>
                  <?php endforeach;?>
                </div>
                
              </div>
              <!-- /.form-group -->
            </div>
            <div class="form-actions">
              <div class="row">
                <div class="col-md-offset-3 col-md-9">
                  <button type="submit" class="btn green-jungle"><i class="fa fa-save"></i> Submit </button>
                   <a class="btn red-thunderbird" href="<?= site_url('admin/users/index'); ?>" ><i class="fa fa-ban"></i> Cancel </a>
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