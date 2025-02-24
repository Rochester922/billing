<!-- Page header -->
<div class="page-header">
  <div class="page-header-content">
    <div class="page-title">
    </div>
    <div class="heading-elements">
      <div class="heading-btn-group">
        <a href="<?=site_url('reseller/dealers/index');?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
          <h5 class="panel-title"><?=$title;?></h5>
          <div class="heading-elements">
            <div style="display: none;" class="actiontools">
              <?php $q = $this->input->get('status');?>
              <a href="<?=site_url('reseller/dealers_users/index/' . $del_row->username);?>" class="btn <?php if (empty($q)) {?>active <?php }?> btn-default btn-sm text-primary"> ALL (<?=$total_users;?>) </a>
              <a href="<?=site_url('reseller/dealers_users/index/' . $del_row->username . '?status=active');?>" class="btn btn-default text-success <?php if (!empty($q) && $q == 'active') {?>active <?php }?>  btn-sm"> ACTIVE (<?=$active_users->num_rows();?>) </a>
              <a href="<?=site_url('reseller/dealers_users/index/' . $del_row->username . '?status=expired');?>" class="btn btn-default text-danger <?php if (!empty($q) && $q == 'expired') {?>active <?php }?> btn-sm"> EXPIRED (<?=$expired_users->num_rows();?>) </a>
            </div>
          </div>
        </div>
        <table class="table table-bordered table-striped datatable-responsive">
          <thead>
            <tr>
              <th> Login ID </th>
              <th class="text-center"> MAC </th>
              <th> Name </th>
              <th> Password </th>
              <th class="text-center"> Status </th>
              <th class="text-center"> Expiry </th>
              <th class="text-center"> Actions </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sql->result() as $row): ?>
            <tr class="odd gradeX">
              <td><?=$row->account;?></td>
              <td class="text-center"><?php if (empty($row->mac)) {$this->stalker_model->mac_update($row->account);} else {
                echo $row->mac;
              }?></td>
              <td><?=$row->full_name;?></td>
              <td><?=$row->password;?></td>
              <td class="text-center"><?=($row->status == 0) ? '<span class="label label-sm label-success block">Active</span>' : '<span class="label label-sm label-danger block">INACTIVE</span>';?></td>
              <td class="text-center"><?=$this->stalker_model->expiry_date($row->expires);?></td>
              <td class="text-center">
                <?php if ($row->status == 1 && $this->stalker_model->check_expired($row->expires) !== "Expired") {?>
                <a href="<?=site_url('reseller/dealers_users/activate/' . $row->account);?>" class="btn btn-xs btn-success " data-popup="tooltip" title="Activate"><i class="icon-user-check"></i> Activate </a>
                <?php } else {?>
                <a href="<?php if ($row->status == 1 && $this->stalker_model->check_expired($row->expires) == "Expired") {echo 'javascript:void(0);';} else {?> <?=site_url('reseller/dealers_users/block/' . $row->account);?> <?php }?>" class="btn btn-xs bg-grey " data-popup="tooltip" title="Block"><i class="icon-user-block"></i> Block </a>
                <?php }?>
                <?php if ($this->stalker_model->check_expired($row->expires) !== "Expired") {?>
                <?php echo del_dis_button("You can't delete active account!"); ?>
                <?php } else {?>
                <?php echo del_button('reseller/dealers_users/delete/' . $row->account, 'User'); ?>
                <?php }?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <!-- /basic responsive configuration -->
    </div>
    <!-- /main content -->
  </div>
  <!-- /page content -->
</div>
<!-- /page container -->