<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
         
        </div>
        <div class="heading-elements">
            <div class="heading-btn-group">
                <a href="<?= site_url('dealer/users/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
            </div>
        </div>
    </div>
</div>

<?php $user_parent_password = $this->stalker_model->user_info($row->account, 'parent_password');?>

<?php //echo ($this->users_model->is_free_trial_user($row->account) ? 'free trial user' : 'NOT a free trial usr'); ?>

<!-- /page header -->
<!-- Page container -->
<div class="page-container">
    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Edit <?= $module; ?></h5>
                            <div class="heading-elements">
                                
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php echo form_open('dealer/users/edit/'.$row->account,array('class'=>'','id'=>'form_sample_3'));?>
                            <div class="form-group <?= (form_error('name')) ? 'has-error' : '' ; ?>">
                                <label for="inputName9" class="">Name</label>
                                
                                <input type="text" class="form-control" placeholder="Max Hodgson" id="inputName9" name="name" value="<?= $row->full_name;?>" />
                                <?= form_error('name','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group <?= (form_error('username')) ? 'has-error' : '' ; ?>">
                                <label for="inputEmail9" class="">Username</label>
                                <input type="text" class="form-control" disabled="disabled" placeholder="examplelogin" id="inputEmail9" value="<?= $row->account; ?>" name="username">
                                <?= form_error('username','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group last <?= (form_error('password')) ? 'has-error' : '' ; ?>">
                                <label for="inputPassword9" class="">Password</label>
                                <input type="text" class="form-control" placeholder="Type a password" id="inputPassword9" name="password" value="<?= $row->password; ?>" />
                                <?= form_error('password','<span class="help-block">','</span>');?>
                            </div>
                            <div class="form-group <?= (form_error('mac')) ? 'has-error' : '' ; ?>">
                                <label for="inputPassword9" class="">MAC</label>
                                
                                <input type="text" class="form-control inputmask" id="mac_mask"  name="mac" placeholder="00:1A:79:__:__:__" maxlength="17" value="<?= $row->mac; ?>" />
                                <?= form_error('mac','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <!-- /.form-group -->
                            <div class="form-group  <?= (form_error('status')) ? 'has-error' : '' ; ?>">
                                <label for="inputPassword9" class="">Status</label>
                                <select class="form-control" name="status" <?php if($this->stalker_model->check_expired($row->expires)=="Expired") { ?> disabled="disabled" <?php } ?>>
                                    <option value="0" <?= ($row->status==0) ? 'selected="selected"':'';?>>ACTIVE</option>
                                    <option value="1" <?= ($row->status==1) ? 'selected="selected"':'';?>>INACTIVE</option>
                                </select>
                                <?= form_error('status','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <!-- /.form-group -->
                            <div class="form-group last <?= (form_error('password')) ? 'has-error' : '' ; ?>">
                                <label for="inputPassword9" class="">Phone</label>
                                <input type="text" class="form-control" placeholder="Type a Phone" id="inputPassword9" name="phone" value="<?= $row->phone; ?>" />
                                <?= form_error('phone','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group last <?= (form_error('comments')) ? 'has-error' : '' ; ?>">
                                <label for="inputPassword9" class="">Comments</label>
                                
                                <textarea class="form-control" name="note"><?= $row->note; ?></textarea>
                                <?= form_error('note','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <?php
                            $is_dealer = $this->dealer_model->is_dealer($row->username);
                            $parent = ($is_dealer==true) ? $this->users_model->get_reseller($row->username): $row->username;
                            ?>
                           
                            <div class="form-group <?php if(form_error('package')): echo 'has-error'; endif; ?>">
                                <label for="inputPassword9" class="">Package</label>
                                <select name="package" class="form-control" id="tariff_custom" onchange="package_selecter();">
                                    <option value="0" <?php if($stalker->tariff_plan_id==0): echo 'selected="selected"'; endif;?>>[Default]</option>
                                    <?php $packages = $this->stalker_model->get_tariff();?>
                                    <?php foreach ($packages->result() as $package):?>
                                    <option value="<?php echo $package->id; ?>" <?php if($stalker->tariff_plan_id==$package->id): echo 'selected="selected"'; endif;?> ><?php echo $package->name; ?></option>
                                    <?php endforeach;?>
                                </select>
                                <?php echo form_error('package','<span class="help-block">','</span>');?>
                            </div>
                            <?php $custom_pack_id = $this->stalker_model->get_custom_plan_id();?>
                            <div class="form-group" id="Custom_Packages" <?php if($stalker->tariff_plan_id!=$custom_pack_id) { ?> style="display: none;" <?php } ?>>
                                <label class="">Select Packages</label>
                                <div  id="show_packages">
                                    <div class="well well-sm">
                                        <p><a href="javascript:void(0);" onclick="check_all();" id="select_all">Select All</a> / <a href="javascript:void(0);" id="deselect_all" onclick="uncheck_all();">Deselect All</a></p>
                                        <?php
                                        // $custom_pack_id = $this->stalker_model->get_custom_plan_id();
                                        $packages_tar = $this->stalker_model->get_package($custom_pack_id);
                                        $packages_in = $this->stalker_model->get_user_packages($stalker->id);  ?>
                                        <?php foreach ($packages_tar as $package): ?>
                                        <p><input type="checkbox" class="checkbox_pack" <?php if(in_array($package->package_id, $packages_in)) { echo 'checked="checked"'; }?> name="packs[]" value="<?php echo $package->package_id; ?>"> <?php echo $this->stalker_model->get_package_name($package->package_id); ?></p>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group last <?= (form_error('parent_password')) ? 'has-error' : '' ; ?>">
                                <label for="inputPassword9" class="">Parent Pin</label>
                                <input type="text" class="form-control" id="parent_password" name="parent_password" value="<?= $user_parent_password; ?>" />
                                <?= form_error('parent_password','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <!-- /.form-group -->
                            <!-- /.form-group -->
                            
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-sm btn-success "><i class=" icon-floppy-disk"></i> Submit</button>
                                <a class="btn btn-sm btn-danger" href="<?= site_url('dealer/users/index'); ?>" ><i class="icon-blocked"></i> Cancel </a>
                            </div>
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
                <!-- Credits Section -->
                <div class="col-md-9">
                    <div class="panel panel-flat ">
                      <div class="panel-heading">
                            <h5 class="panel-title">STB info</h5>
                            <div class="heading-elements">
                                
                            </div>
                        </div>  
                        <div class="panel-body">
                            <div class="col-md-4">
                <div class="info_row">
                  <label style="width: 80px; text-align: left !important;">  Receiver  </label> <span class="semicolon" style="font-weight: bold;">:</span> <?=$this->stalker_model->receiver_staus($row->account);?>
                </div>
                <div class="info_row">
                  <label style="width: 80px; text-align: left !important;">Package</label> 
                    <span class="semicolon" style="font-weight: bold;">:</span> 
                    <span class="label-primary label"> <?=$this->stalker_model->get_tariff_name($row->account);?></span>
                </div>
                <div class="info_row">
                  <label style="width: 80px; text-align: left !important;">Parent Pin</label> 
                    <span class="semicolon" style="font-weight: bold;">:</span> 
                    <span class="label-primary label"> <div id="stb-info-parent-pin"> <?=$user_parent_password;?> </div> </span>
                </div>
            </div>
            <div class="col-md-4">
              <div class="info_row">
                <label style="width: 80px; text-align: left !important;">IP  </label>  <span class="semicolon" style="font-weight: bold;">:</span> <?=$this->stalker_model->user_info($row->account, 'ip');?>
              </div>
              <div class="info_row">
                <label style="width: 80px; text-align: left !important;">Expiry  </label> <span class="semicolon" style="font-weight: bold;">:</span> <?=$this->stalker_model->expiry_date($row->expires);?>
              </div>
            </div>
            <div class="col-md-4">
              <div class="info_row">
                <label style="width: 80px; text-align: left !important;">Firmware  </label> <span class="semicolon" style="font-weight: bold;">:</span> <?=$this->stalker_model->user_info($row->account, 'image_version');?>
              </div>
              <div class="info_row">
                <label style="width: 80px; text-align: left !important;">Watching   </label> <span class="semicolon" style="font-weight: bold;">:</span> <?=$this->stalker_model->user_info($row->account, 'now_playing_content');?>
              </div>
            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                
                <div class="panel panel-flat ">
                    <div class="panel-heading">
                        <h5 class="panel-title">Credits</h5>
                        <div class="heading-elements">
                            
                        </div>
                    </div>
                    <div class="panel-body">
                        <?= form_open('dealer/users/renew/'.$row->account,array('class'=>'form-horizontal'));?>
                        <div class="form-group <?php if(form_error('credits')): echo 'has-error'; endif; ?>">
                           <label class="control-label col-md-5">Select Credits</label>
                            <div class="col-md-3 ">
                                <select class="form-control" name="credits">
                                    <?php 
                                        for ($i=1; $i <=2000; $i++) {
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        } 
                                    ?>
                                </select>
                                <?php echo form_error('credits', '<span class="help-block">', '</span>'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-5 control-label">Type</label>
                            <div class="col-md-6">
                                
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type" class="styled" checked="checked" value="CRDT">
                                        ADD
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type" class="styled" value="RCDT">
                                        RECOVER
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-5">
                                <button class="btn btn-sm btn-success" type="submit"><i class="icon-floppy-disk"></i> Submit </button>
                                <a class="btn btn-sm btn-danger" href="<?= site_url('dealer/users/index'); ?>" ><i class="icon-blocked"></i> Cancel </a>
                            </div>
                        </div>
                        <?= form_close();?>
                    </div>
                </div>

                <div class="col-md-12" style="padding: 0px;">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Send Message</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                    <li><a data-action="reload"></a></li>
                                    <li><a data-action="close"></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php echo form_open('dealer/users/message/'.$row->account,array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
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
                                    <div class="col-md-offset-5 col-md-6">
                                        <button type="submit" class="btn  btn-sm btn-success"><i class="icon-enter position-left"></i> SEND</button>
                                        <a class="btn btn-sm btn-danger" href="<?= site_url('dealer/users/index'); ?>" ><i class="icon-blocked"></i> Cancel </a>
                                        
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-12" style="padding: 0px;">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Transaction History</h5>
                        <div class="heading-elements">
                            <div class="actiontools"></div>
                        </div>
                    </div>
                    
                    <table class="table  datatable-responsive" id="sample_1">
                        <thead>
                            <tr>
                                <th width="100">
                                    Transaction
                                </th>
                                <th> Type </th>
                                <th> Months </th>
                                <th> Sub-account </th>
                                <th> Coverage Start </th>
                                <th> Coverage End </th>
                                <th> Date / Time </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_credits = 0;?>
                            <?php $trans_sql=  $this->users_model->get_transactions($row->account);?>
                            <?php  foreach ($trans_sql->result() as $trans) :?>
                            <?php $total_credits +=$trans->periods; ?>
                            <tr class="odd gradeX">
                                <td><?= (str_pad($trans->transaction, 8, "0", STR_PAD_LEFT));?></td>
                                <td><?= ($trans->type=='DBIT') ? '<span class="label label-sm label-success green block">PURCHASED</span>':'<span class="label label-sm label-danger block">USED</span>' ;?></td>
                                <td><?= $trans->periods;?></td>
                                <td><?= (empty($trans->account)) ? '-':$trans->account;?></td>
                                <td><?= (empty($trans->coverage_start)) ? '-':$trans->coverage_start;?></td>
                                <td><?= (empty($trans->coverage_end)) ? '-':$trans->coverage_end;?></td>
                                <td><?=  $trans->timestamp; ?></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                        <tfoot>
                        <th><?php echo $trans_sql->num_rows(); ?></th>
                        <th>-</th>
                        <th>Total <br> <?php echo $total_credits; ?></th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                        <th>-</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>

$( document ).ready(function() { 

    $("#stb-info-parent-pin").css('cursor', 'pointer');

    /* edit user view */
    $("#stb-info-parent-pin").click(function() {
        //alert('hey');
        //$("#parent_password").val('');
        $('#parent_password').focus(); /* move focus to the editable 'parent password' field */
        $('#parent_password').animate({backgroundColor: '#2196F3'}, 'fast')        
        $('#parent_password').animate({backgroundColor: '#F7F7F7'}, 'fast')        
    });

});

</script>
    
    
</div>
<!-- /main content -->
</div>
<!-- /page content -->
</div>
<!-- /page container


