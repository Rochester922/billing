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
              <?=form_open('manager/users/index/', array('class' => 'form-horizontal', 'method' => 'get'));?>
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
          <div class="panel panel-flat">
            <div class="panel-heading">
              <h5 class="panel-title"><?=$title;?></h5>
              <div class="heading-elements">
                <div class="actiontools"></div>
              </div>
            </div>
            <!-- Panel Body -->
            <table class="table  table-striped table-bordered datatable-responsive" id="sample_1">
              <thead>
                <tr>
                  <th> Login ID </th>
                  <th class="text-center" > MAC </th>
                  <th> Name </th>
                  <th> Password </th>
                  <th width="100"> Reseller </th>
                  <th> Dealer </th>
                  <th class="text-center" > Status </th>
                  <th class="text-center" > Receiver </th>
                  <th  class="text-center" > Expiry </th>
                  <th class="text-center"> Actions </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($sql->result() as $row): ?>
                <tr class="odd gradeX">
                <td><a href="<?= site_url('manager/users/edit/'.$row->account); ?>"><?=$row->account;?></a></td>
                  <td class="text-center" style=" padding: 5px 6px;"><a href="<?= site_url('manager/users/edit/'.$row->account); ?>"><?php if(empty($row->mac)) { $this->stalker_model->mac_update($row->account); }else{
                  echo $row->mac;
                  } ?></a></td>
                 <td><a href="<?= site_url('manager/users/edit/'.$row->account); ?>"><?=$row->full_name;?></a></td>
                 <td><?= $row->password;?></td>
                  <td><?=get_reseller($row->username, 'SRSLR');?></td>
                  <td><?=$row->username;?></td>
                  <td class="text-center"><?=($row->status == 0) ? '<span class="label label-sm label-success block">Active</span>' : '<span class="label label-sm label-danger block">INACTIVE</span>';?></td>
                  <td class="text-center" style=" padding: 5px 6px;"><?=$this->stalker_model->receiver_staus($row->account);?></td>
                  <td class="text-center" style=" padding: 5px 6px;">
                    <?=$this->stalker_model->expiry_date($row->expires);?>

                    <a href="javascript:void(0)" onclick="add1Month(this, '<?=$row->account;?>')" class="label label-sm label-primary">+1</a>

                    <?php echo form_open('manager/users/renewOneMonth/'.$row->account,array('class'=>'form-horizontal'));?>
                      <input type="hidden" name="query" value="<?= $query;?>">
                      <input type="hidden" name="validity" value="1">
                      <input type="hidden" name="reseller" value="<?=get_reseller($row->username, 'SRSLR');?>">
                      <input type="hidden" name="dealer" value="<?=get_dealer($row->username, 'RSLR');?>">
                      <button type="submit" class="label label-sm label-primary" style="display: none;"></button>
                    </form>
                  </td>
                  <td class="text-center" style="padding: 5px 6px;">
                    <?=user_action_buttons($row->account, 'manager');?>
                  </td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php endif;?>

		<?php if (!empty($sql_expired)): ?>
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">Expired MAC</h5>
							<div class="heading-elements">
								<div class="actiontools"></div>
							</div>
						</div>
						<!-- Panel Body -->
						<table class="table  table-striped table-bordered datatable-responsive" id="sample_1">
							<thead>
							<tr>
								<th> Login ID </th>
								<th class="text-center" > MAC </th>
								<th> Name </th>
								<th> Password </th>
								<th width="100"> Reseller </th>
								<th> Dealer </th>
								<th class="text-center" > Status </th>
								<th class="text-center" > Receiver </th>
								<th  class="text-center" > Expiry </th>
								<th class="text-center"> Actions </th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($sql_expired->result() as $row_expired): ?>
								<tr class="odd gradeX">
									<td><a href="<?= site_url('manager/users/edit/'.$row_expired->account); ?>"><?=$row_expired->account;?></a></td>
									<td class="text-center" style=" padding: 5px 6px;"><a href="<?= site_url('manager/users/edit/'.$row_expired->account); ?>"><?php if(empty($row_expired->mac)) { $this->stalker_model->mac_update($row_expired->account); }else{
												echo $row_expired->mac;
											} ?></a></td>
									<td><a href="<?= site_url('manager/users/edit/'.$row_expired->account); ?>"><?=$row_expired->full_name;?></a></td>
									<td><?= $row_expired->password;?></td>
									<td><?=get_reseller($row_expired->username, 'SRSLR');?></td>
									<td><?=$row_expired->username;?></td>
									<td class="text-center"><?=($row_expired->status == 0) ? '<span class="label label-sm label-success block">Active</span>' : '<span class="label label-sm label-danger block">INACTIVE</span>';?></td>
									<td class="text-center" style=" padding: 5px 6px;"><?=$this->stalker_model->receiver_staus($row_expired->account);?></td>
									<td class="text-center" style=" padding: 5px 6px;"><?=$this->stalker_model->expiry_date($row_expired->expires);?></td>
									<td class="text-center" style="padding: 5px 6px;">
										<?=user_action_buttons($row_expired->account, 'manager');?>
									</td>
								</tr>
							<?php endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php endif;?>

	</div>
    <!-- /basic responsive configuration -->
  </div>
  <!-- /main content -->
</div>
<!-- /page content -->
</div>
<!-- /page container -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function add1Month(element, account) {
    Swal.fire({
      title: `Please confirm renew of account ${account} for a month?`,
      showCancelButton: true,
      confirmButtonText: "OK",
    }).then((result) => {
      if (result.isConfirmed) {
        let form = element.closest('a').nextElementSibling;
        if (form && form.tagName.toLowerCase() === 'form') {
          form.submit(); // Submit form
        }
      }
    });
  }
</script>