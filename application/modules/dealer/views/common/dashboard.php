<!-- Page header -->
<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            
        </div>
        <div class="heading-elements">
            <div class="heading-btn-group">
                <a href="<?= site_url('dealer//users/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
                    <a class="dashboard-stat dashboard-stat-v2 blue" href="<?= site_url('dealer/transactions') ?>">
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
                    <a class="dashboard-stat dashboard-stat-v2 blue-chambray" href="<?= site_url('dealer/users/index');?>">
                        <div class="visual">
                            <i class="icon-users"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <span data-counter="counterup" data-value=""><?= $total_dealers->num_rows(); ?></span></div>
                                <div class="desc"> Total Users </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-v2 green-jungle" href="<?= site_url('dealer/users/index?status=active'); ?>">
                            <div class="visual">
                                <i class="icon-user-check"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value=""><?= $active_users->num_rows(); ?></span>
                                </div>
                                <div class="desc"> Active Users </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a class="dashboard-stat dashboard-stat-v2 red" href="<?= site_url('dealer/users/index?status=expired'); ?>">
                            <div class="visual">
                                <i class="icon-user-block"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    <span data-counter="counterup" data-value=""><?= $expired_users->num_rows(); ?></span> </div>
                                    <div class="desc"> Expired Users </div>
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