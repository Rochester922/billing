<!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
       <a href="<?= site_url('manager/resellers/add');?>" class="btn btn-primary btn-sm"> <i class="icon-add"></i> ADD NEW </a>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="<?= site_url('manager/dashboard'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
             <div class="actiontools"></div>
                    </div>
          </div>

          
           <table class="table table-bordered table-striped datatable-responsive">
            <thead>
              <tr>
                <th> Name </th>
                <th> Username </th>
                <th> Password </th>
                <th> Total Dealers </th>
                <th> Total Users </th>
                <th class="text-center"> Status </th>
                <th> Credits </th>
                <th class="text-center"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sql->result() as $row): ?>
              <tr class="odd gradeX">
                <td><?=$row->name;?></td>
                 <td><a href="<?= site_url('manager/resellers/edit/'.$row->username);?>"><?=$row->username;?></a></td>
                <td><?=$row->password;?></td>
                 <td><?=$this->manager_model->count_resellers($row->username);?></td>
                <td><?=$this->dealer_model->count_users($row->username);?></td>
                <td class="text-center"><?=($row->status == 'A') ? '<span class="label label-sm label-success block">ACTIVE</span>' : '<span class="label label-sm label-danger block">INACTIVE</span>';?></td>
                <td><?= get_balance($row->username);?></td>
                <td class="text-center">
                  <?= reseller_action_buttons($row->username, 'manager');?>
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