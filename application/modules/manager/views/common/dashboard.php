<!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h4>
           <a href="<?= site_url('manager/users/index'); ?>"><i class="icon-arrow-left52 position-left"></i></a>
          <span class="text-semibold">Home</span> - Dashboard
          <small class="display-block">Welcome Back, <?= $auth_info['name']; ?>!</small>
        </h4>
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

            <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue" href="<?= site_url('manager/transactions/index'); ?>">
                <div class="visual">
                    <i class="icon-coins"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value=""><?= $balance; ?></span>
                    </div>
                    <div class="desc"> Credits Remaining </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 red" href="<?= site_url('manager/users/index'); ?>">
                <div class="visual">
                    <i class="icon-users4"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value=""><?= $users; ?></span></div>
                        <div class="desc"> Total Users </div>
                    </div>
                </a>
            </div>
             <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 purple" href="<?= site_url('manager/resellers/index'); ?>">
                    <div class="visual">
                        <i class="icon-user"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value=""><?= $resellers->num_rows(); ?></span></div>
                            <div class="desc"> Resellers </div>
                        </div>
                    </a>
                </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 green" href="<?= site_url('manager/dealers/index'); ?>">
                    <div class="visual">
                        <i class="icon-users2"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="<?= $dealers; ?>">0</span>
                        </div>
                        <div class="desc"> Dealers </div>
                    </div>
                </a>
            </div>
           
              
            </div>

            <div class="clearfix"></div>
            <!-- END DASHBOARD STATS 1-->
            
        </div>

      </div>
      <!-- /main content -->

    </div>
    <!-- /page content -->

  </div>
  <!-- /page container -->