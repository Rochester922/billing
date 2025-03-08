<!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
       

         <a href="<?= site_url('dealer/users/add');?>" class="btn btn-primary btn-sm"> <i class="icon-add"></i> ADD NEW </a>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="<?= site_url('dealer/dashboard'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
            <div style="display: none;" class="actiontools">
                   <?php $q = $this->input->get('status'); ?>
                  <a href="<?= site_url('dealer/users/index'); ?>" class="btn <?php if(empty($q)) {?>active <?php } ?> btn-default btn-sm text-primary"> ALL (<?= $total_users; ?>) </a>
                  <a href="<?= site_url('dealer/users/index?status=active'); ?>" class="btn btn-default text-success <?php if(!empty($q) && $q=='active') {?>active <?php } ?>  btn-sm"> ACTIVE (<?= $active_users->num_rows(); ?>) </a>
               
                  <a href="<?= site_url('dealer/users/index?status=expired'); ?>" class="btn btn-default text-danger <?php if(!empty($q) && $q=='expired') {?>active <?php } ?> btn-sm"> EXPIRED (<?= $expired_users->num_rows(); ?>) </a>
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
                <td class="text-center"><a href="<?= site_url('dealer/users/edit/'.$row->account); ?>"><?php if(empty($row->mac)) { $this->stalker_model->mac_update($row->account); }else{
                  echo $row->mac;
                  } ?></a></td>
                <td><a href="<?= site_url('dealer/users/edit/'.$row->account); ?>"><?=$row->full_name;?></a></td>
                <td><?=$row->password;?></td>
                <td class="text-center"><?= ($row->status == 0) ? '<span class="label label-sm label-success block">Active</span>' : '<span class="label label-sm label-danger block">INACTIVE</span>';?></td>
                <td class="text-center">
                  <?=$this->stalker_model->expiry_date($row->expires);?>

                  <a href="javascript:void(0)" onclick="add1Month(this, '<?=$row->account;?>')" class="label label-sm label-primary">+1</a>

                  <?php echo form_open('dealer/users/renewOneMonth/'.$row->account,array('class'=>'form-horizontal'));?>
                    <input type="hidden" name="query" value="<?= $query;?>">
                    <input type="hidden" name="validity" value="1">
                    <input type="hidden" name="reseller" value="<?=get_reseller($row->username, 'SRSLR');?>">
                    <input type="hidden" name="dealer" value="<?=get_dealer($row->username, 'RSLR');?>">
                    <button type="submit" class="label label-sm label-primary" style="display: none;"></button>
                  </form>
                </td>
                <td class="text-center">
                  <?=user_action_buttons($row->account, 'dealer');?>
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