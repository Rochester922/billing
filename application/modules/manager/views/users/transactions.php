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
  <h1 class="page-title"> <?php echo $module;?>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <!-- BEGIN EXAMPLE TABLE PORTLET-->
      <div class="portlet light bordered">
        <div class="portlet-title">
          <div class="caption font-dark">
            <i class="fa fa-money font-dark"></i>
            <span class="caption-subject bold uppercase"> ADD CREDITS</span>
          </div>
          
        </div>
        <div class="portlet-body">
        
          <?= validation_errors('<div class="alert alert-danger">','</div>');?>
          <?= form_open('admin/dealers/transactions/'.$row->username,array('class'=>'form-horizontal'));?>
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
              <div class="mt-radio-list">
                <label class="mt-radio mt-radio-outline">
                  <input type="radio" name="type" id="optionsRadios22" value="CRDT" checked> ADD 
                  <span></span>
                </label>
                <label class="mt-radio mt-radio-outline">
                  <input type="radio" name="type" id="optionsRadios23" value="DBIT" checked> RECOVER
                  <span></span>
                </label>

              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-3 col-md-offset-5">
              <button class="btn red" type="submit"><i class="fa fa-save"></i> Submit</button>
            </div>
          </div>
          <?= form_close();?>
        </div>
      </div>
      <!-- END EXAMPLE TABLE PORTLET-->
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <!-- BEGIN EXAMPLE TABLE PORTLET-->
      <div class="portlet light bordered">
        <div class="portlet-title">
          <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase"> <?php echo $title; ?></span>
          </div>
          
        </div>
        <div class="portlet-body">
          
          <table class="table table-striped table-bordered  table-hover table-checkable order-column" id="sample_1">
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
              <?php  foreach ($sql->result() as $row) :?>
              <?php $total_credits +=$row->periods; ?>
              <tr class="odd gradeX">
                <td><?= (str_pad($row->transaction, 8, "0", STR_PAD_LEFT));?></td>
                <td><?= ($row->type=='CRDT') ? '<span class="label label-sm label-success green block">PURCHASED</span>':'<span class="label label-sm label-danger block">USED</span>' ;?></td>
                <td><?= $row->periods;?></td>
                <td><?= (empty($row->account)) ? '-':$row->account;?></td>
                <td><?= (empty($row->coverage_start)) ? '-':$row->coverage_start;?></td>
                <td><?= (empty($row->coverage_end)) ? '-':$row->coverage_end;?></td>
                <td><?=  $row->timestamp; ?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
            <tfoot>
            <th><?php echo $sql->num_rows(); ?></th>
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
      <!-- END EXAMPLE TABLE PORTLET-->
    </div>
  </div>
</div>