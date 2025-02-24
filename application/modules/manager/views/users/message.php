<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
    </div>
    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="<?=site_url('manager/resellers/index');?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
    <div class="row">
        <div class="col-md-12">
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
          <div class="panel panel-flat">
            
            <!-- Panel Body -->
            <div class="panel-body">
                            <div class="col-md-4">
                                <div class="info_row">
                                    <label>  Receiver : </label> <?=$this->stalker_model->receiver_staus($row->account);?>
                                </div>
                                <div class="info_row">
                                    <label>Package :  </label> <span class="label-primary label">
                                    <?=$this->stalker_model->get_tariff_name($row->account);?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info_row">
                                <label>IP : </label> <?=$this->stalker_model->user_info($row->account, 'ip');?>
                            </div>
                            <div class="info_row">
                                <label>Expiry :  </label> <?=$this->stalker_model->expiry_date($row->expires);?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info_row">
                                <label>Firmware : </label> <?=$this->stalker_model->user_info($row->account, 'image_version');?>
                            </div>
                            <div class="info_row">
                                <label>Watching :  </label> <?=$this->stalker_model->user_info($row->account, 'now_playing_content');?>
                            </div>
                        </div>
                    </div>
            
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-flat">
            <div class="panel-heading">
              <h5 class="panel-title"><?=$title;?></h5>
              <div class="heading-elements">
                <div class="actiontools"></div>
              </div>
            </div>
            <!-- Panel Body -->
            <div class="panel-body">
          <!-- BEGIN FORM-->
          <?php echo form_open('manager/users/message/'.$row->account,array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
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
                  <button type="submit" class="btn green-jungle"><i class="fa fa-send"></i> SEND</button>
                   <a class="btn red-thunderbird" href="<?= site_url('manager/users/index'); ?>" ><i class="fa fa-ban"></i> Cancel </a>
                </div>
              </div>
            </div>
          </form>
         </div>
          </div>
        </div>
      </div>
      
      </div>
    <!-- /basic responsive configuration -->
  </div>
  <!-- /main content -->
</div>
<!-- /page content -->
</div>
<!-- /page container -->