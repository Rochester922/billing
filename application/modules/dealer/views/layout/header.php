<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <!-- <a href="#">
            <img src="<?php echo base_url('assets'); ?>/layouts/layout/img/logo.png" alt="logo" class="logo-default" /> </a> -->
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                 <li><a href="<?= site_url('dealer/transactions') ?>"><span class="label-danger label"> <i class="fa fa-money"></i> <?= $this->transaction_model->get_credit_balance($auth_info['username']); ?> Credits Remaining</span></a></li>
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="<?php echo base_url('assets'); ?>/layouts/layout/img/avatar3_small.jpg" />
                        <span class="username username-hide-on-mobile"> <?=$auth_info['username'];?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="<?=site_url('dealer/profile');?>">
                            <i class="icon-user"></i> My Profile </a>
                        </li>

                        <li class="divider"> </li>

                        <li>
                            <a href="<?=site_url('dealer/logout');?>">
                            <i class="icon-key"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>