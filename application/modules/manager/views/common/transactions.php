<!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
      
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="<?= site_url('manager/users/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
        <!-- /basic responsive configuration -->

      </div>
      <!-- /main content -->

    </div>
    <!-- /page content -->

  </div>
  <!-- /page container -->