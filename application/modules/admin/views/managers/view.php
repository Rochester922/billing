<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
           <!--  <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php echo $module; ?></span> </h4>
            <ul class="breadcrumb breadcrumb-caret position-right">
                <li><a href="<?= site_url('admin/dashboard'); ?>">Home</a></li>
                <li class="active"><?php echo $module; ?></li>
            </ul> -->
        </div>
        <div class="heading-elements">
            <div class="heading-btn-group">
                <a href="<?= site_url('admin/managers/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
                <div class="col-md-3">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Edit <?= $module; ?></h5>
                            <div class="heading-elements">
                                
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php echo form_open('admin/managers/edit/'.$row->username,array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
                            <div class="form-group <?= (form_error('name')) ? 'has-error' : '' ; ?>">
                                <label for="inputName9">Name</label>
                                <input type="text" class="form-control" placeholder="Max Hodgson" id="inputName9" name="name" value="<?= $row->name;?>" />
                                <?= form_error('name','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group <?= (form_error('username')) ? 'has-error' : '' ; ?>">
                                <label for="inputEmail9" class="">Username</label>
                                
                                <input type="text" class="form-control" disabled="disabled" placeholder="examplelogin" id="inputEmail9" value="<?= $row->username; ?>" name="username">
                                <?= form_error('username','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group last <?= (form_error('password')) ? 'has-error' : '' ; ?>">
                                <label for="inputPassword9" class="">Password</label>
                                <input type="text" class="form-control" placeholder="Type a password" id="inputPassword9" name="password" value="<?= $row->password; ?>" />
                                <?= form_error('password','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <!-- /.form-group -->
                            <div class="form-group last <?= (form_error('status')) ? 'has-error' : '' ; ?>">
                                <label for="inputPassword9" class="">Status</label>
                                <select class="form-control" name="status">
                                    <option value="A" <?= ($row->status=='A') ? 'selected="selected"':'';?>>ACTIVE</option>
                                    <option value="S" <?= ($row->status=='S') ? 'selected="selected"':'';?>>INACTIVE</option>
                                </select>
                                <?= form_error('status','<span class="help-block">','</span>');?>
                            </div>
                            <!-- /.form-group -->
                            <!-- /.form-group -->
                            <div class="form-group <?= (form_error('comments')) ? 'has-error' : '' ; ?>">
                                <label for="inputPassword9" class="">Comments</label>
                                <textarea class="form-control" name="comments"><?= $row->comments; ?></textarea>
                                <?= form_error('comments','<span class="help-block">','</span>');?>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-sm btn-success "><i class=" icon-floppy-disk"></i> Submit</button>
                                <a class="btn btn-sm btn-danger" href="<?= site_url('admin/managers/index'); ?>" ><i class="icon-blocked"></i> Cancel </a>
                            </div>
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
                <!-- Credits Section -->
                <div class="col-md-9">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">Credits</h5>
                            <div class="heading-elements">
                                
                            </div>
                        </div>
                        <div class="panel-body">
                            <?= form_open('admin/managers/transactions/'.$row->username,array('class'=>'form-horizontal'));?>
                            <div class="form-group">
                                <label class="control-label col-md-5">Select Credits</label>
                                <div class="col-md-3">
                                    <select class="form-control" name="credits">
                                        <?php for ($i=1; $i <=2000 ; $i++) {
                                        echo '<option value="'.$i.'">'.$i.'</option>';
                                        } ?>
                                    </select>
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
                                            <input type="radio" name="type" class="styled" value="DBIT">
                                            RECOVER
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 col-md-offset-5">
                                    <button class="btn btn-sm btn-success" type="submit"><i class="icon-floppy-disk"></i> Submit </button>
                                    <a class="btn btn-sm btn-danger" href="<?= site_url('admin/managers/index'); ?>" ><i class="icon-blocked"></i> Cancel </a>
                                </div>
                            </div>
                            <?= form_close();?>
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
            <?php $trans_sql=  $this->transaction_model->get_all($row->username);?>
              <?php  foreach ($trans_sql->result() as $trans) :?>
              <?php $total_credits +=$trans->periods; ?>
              <tr class="odd gradeX">
                <td><?= (str_pad($trans->transaction, 8, "0", STR_PAD_LEFT));?></td>
                <td><?= ($trans->type=='CRDT') ? '<span class="label label-sm label-success green block">PURCHASED</span>':'<span class="label label-sm label-danger block">USED</span>' ;?></td>
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
                

            
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container