<!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
       

         <a href="<?= site_url('admin/users/add');?>" class="btn btn-primary btn-sm"> <i class="icon-add"></i> ADD NEW </a>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="<?= site_url('admin/dashboard'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
          <div class="panel panel-flat">
            <div class="panel-heading">
              <h5 class="panel-title"><?=$title;?></h5>
              <div class="heading-elements">
                <div class="actiontools"></div>
              </div>
            </div>
            <!-- Panel Body -->
            <div class="panel-body">
              <?=validation_errors('<div class="alert alert-danger">', '</div>');?>
              <?=form_open('admin/users/index/', array('class' => 'form-horizontal', 'method' => 'get'));?>
              <div class="form-group">
                <label class="control-label col-md-5">Enter Query</label>
                <div class="col-md-3">
                  <input type="text" name="query" class="form-control" value="<?php echo set_value('query'); ?>">
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-3 col-md-offset-5">
                  <button class="btn btn-sm btn-success" type="submit"><i class=" icon-search4"></i> Search</button>
                </div>
              </div>
              <?=form_close();?>
            </div>
          </div>
        </div>
      </div>
      <?php if (!empty($sql)): ?>
      <div class="row">
        <div class="col-md-12">
          <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <!-- Basic responsive configuration -->
        <div class="panel panel-flat">
          <div class="panel-heading">
            <h5 class="panel-title"><?= $title; ?></h5>
            <div class="heading-elements">
            <div style="display: none;" class="actiontools">
                  <?php $q = $this->input->get('status');?>
                  <a href="<?=site_url('admin/users/index');?>" class="btn <?php if (empty($q)) {?>active <?php }?> btn-default btn-sm"> <span class="text-primary">ALL (<?=$total_users;?>)</span> </a>
                  <a href="<?=site_url('admin/users/index?status=active');?>" class="btn btn-default <?php if (!empty($q) && $q == 'active') {?>active <?php }?>  btn-sm"><span class="text-success"> ACTIVE (<?=$active_users;?>) </span> </a>
                  <a href="<?=site_url('admin/users/index?status=expired');?>" class="btn btn-default <?php if (!empty($q) && $q == 'expired') {?>active <?php }?> btn-sm"> <span class="text-danger"> EXPIRED (<?=$expired_users;?>) </span> </a>
                </div>
                    </div>
          </div>

           <table class="table table-bordered table-striped datatable-responsive">
            <thead>
              <tr>
                <th> Manager </th>
                <th> Reseller </th>
                <th> Dealer </th>
                <th> Name </th>
                <th> Username </th>
                <th> Password </th>
                <th class="text-center"> MAC </th>
                <th class="text-center"> Status </th>
                <th class="text-center"> Expiry </th>
                <th class="text-center"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sql->result() as $row): ?>
              <tr class="odd gradeX">
                <td><?=get_manager($row->username, 'MNGR');?></td>
                <td><?=get_reseller($row->username, 'SRSLR');?></td>
                <td><?=get_dealer($row->username, 'RSLR');?></td>
                <td><a href="<?= site_url('admin/users/edit/'.$row->account); ?>"><?=$row->full_name;?></a></td>
                <td><a href="<?= site_url('admin/users/edit/'.$row->account); ?>"><?=$row->account;?></a></td>
                <td><?=$row->password;?></td>
                <td class="text-center"><a href="<?= site_url('admin/users/edit/'.$row->account); ?>"><?php if(empty($row->mac)) { $this->stalker_model->mac_update($row->account); }else{
                  echo $row->mac;
                  } ?></a></td>
                <td class="text-center"><?=($row->status == 0) ? '<span class="label label-sm label-success block">Active</span>' : '<span class="label label-sm label-danger block">INACTIVE</span>';?></td>
                <td class="text-center"><?=$this->stalker_model->expiry_date($row->expires);?></td>
                <td class="text-center">
                  <?=user_action_buttons($row->account, 'admin');?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
          </div>
        <!-- /basic responsive configuration -->
        </div>
      </div>
      <?php endif;?>
      </div>
      <!-- /main content -->

    </div>
    <!-- /page content -->

  </div>
  <!-- /page container -->