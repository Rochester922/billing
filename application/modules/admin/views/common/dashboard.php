<!-- Page header -->
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-title">
        <h4>
            <a href="<?= site_url('admin/users/index'); ?>"><i class="icon-arrow-left52 position-left"></i></a>
            <span class="text-semibold">Home</span> - Dashboard
            <small class="display-block">Welcome Back, <?= $auth_info['name']; ?>!</small>
            <small class="display-block"> 

            <?php

                // $testdb_users = $this->dashboard_model->get_all_stalker_users(); 
                // foreach ($testdb_users->result() as $usr) {   
                //     // now process each user    
                //     echo '&nbsp;&nbsp;' . 'Username: ' . $usr->login . '<br />';
                //     echo '&nbsp;&nbsp;' . 'First Name: ' . $usr->fname . '<br />';
                //     echo '&nbsp;&nbsp;' . 'Created: ' . $usr->created . '<br />';
                //     echo '&nbsp;&nbsp;' . 'Expiry: ' . $usr->expire_billing_date . '<br />';
                //     echo '<br />';
                // }

            ?>  

          </small>

        </h4>
      </div>

      <div class="heading-elements">
        <div class="heading-btn-group">
          <a href="<?= site_url('admin/users/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 blue-steel" href="<?= site_url('admin/users/index'); ?>">
                <div class="visual">
                    <i class="icon-users4"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value=""><?= $total_users ?></span>
                    </div>
                    <div class="desc"> Total Users </div>
                </div>
            </a>
        </div>
         <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 green-jungle" href="<?= site_url('admin/users/index?status=active'); ?>">
                    <div class="visual">
                        <i class="icon-user-check"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value=""><?= $active_users;?></span>
                        </div>
                        <div class="desc"> Active users </div>
                    </div>
                </a>
            </div>
        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <a class="dashboard-stat dashboard-stat-v2 red-thunderbird" href="<?= site_url('admin/users/index?status=expired'); ?>">
                <div class="visual">
                    <i class=" icon-user-block"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup" data-value=""><?=  $expired_users; ?></span> </div>
                        <div class="desc"> Expired Users </div>
                    </div>
                </a>
            </div>
           
            
                  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 yellow" href="<?= site_url('admin/managers/index'); ?>">
                    <div class="visual">
                        <i class=" icon-user-tie"></i>
                    </div>
                    <div class="details">
                        <div class="number"> 
                            <span data-counter="counterup" data-value=""><?= $total_mangers; ?></span> </div>
                            <div class="desc"> Total Managers </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 blue" href="<?= site_url('admin/resellers/index'); ?>">
                    <div class="visual">
                        <i class="icon-user"></i>
                    </div>
                    <div class="details">
                        <div class="number"> 
                            <span data-counter="counterup" data-value=""><?= $total_resellers; ?></span> </div>
                            <div class="desc"> Total Resellers </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 purple-seance" href="<?= site_url('admin/dealers/index'); ?>">
                    <div class="visual">
                        <i class="icon-users2"></i>
                    </div>
                    <div class="details">
                        <div class="number"> 
                            <span data-counter="counterup" data-value=""><?= $total_dealers; ?></span> </div>
                            <div class="desc"> Total Dealers </div>
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