<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu  page-header-fixed <?= ($is_user=="users") ? 'page-sidebar-menu-closed': ''; ?> " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <!-- END SIDEBAR TOGGLER BUTTON -->
            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
            <li class="sidebar-search-wrapper">
                <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
                <!-- <form class="sidebar-search  " action="page_general_search_3.html" method="POST"> -->
                <?php $active_uri = $this->uri->segment(2); ?>
                <?php echo form_open('admin/users/index',array('class'=>'sidebar-search','method'=>'get')); ?>
                <a href="javascript:;" class="remove">
                    <i class="icon-close"></i>
                </a>
                <div class="input-group">
                    <input type="text" class="form-control" name="query" placeholder="Search..." value="<?= $this->input->get('query'); ?>">
                    <span class="input-group-btn">
                        <a href="javascript:;" class="btn submit">
                            <i class="icon-magnifier"></i>
                        </a>
                    </span>
                </div>
            </form>
            <!-- END RESPONSIVE QUICK SEARCH FORM -->
        </li>
        <li class="nav-item start <?= ($active_uri=='dashboard') ? 'active open': '';?>">
            <a href="<?php echo site_url('admin/dashboard'); ?>" class="nav-link nav-toggle">
                <i class="icon-home"></i>
                <span class="title">Dashboard</span>
                <?= ($active_uri=='dashboard') ? '<span class="selected"></span>': '';?>
            </a>
        </li>
        <li class="heading">
            <h3 class="uppercase">Peoples</h3>
        </li>
        <li class="nav-item  <?= ($active_uri=='managers') ? 'active open': '';?>">
            <a href="<?php echo site_url('admin/managers/index'); ?>" class="nav-link nav-toggle">
                <i class="icon-user"></i>
                <span class="title">Managers</span>
                <?= ($active_uri=='managers') ? '<span class="selected"></span>': '';?>
            </a>
            
        </li>
        <li class="nav-item  <?= ($active_uri=='resellers') ? 'active open': '';?>">
            <a href="<?php echo site_url('admin/resellers/index'); ?>" class="nav-link nav-toggle">
                <i class="fa fa-user"></i>
                <span class="title">Resellers</span>
                <?= ($active_uri=='resellers') ? '<span class="selected"></span>': '';?>
            </a>
            
        </li>
        <li class="nav-item <?= ($active_uri=='dealers') ? 'active open': '';?> ">
            <a href="<?php echo site_url('admin/dealers/index'); ?>" class="nav-link nav-toggle">
                <i class="icon-users"></i>
                <span class="title">Dealers</span>
                <?= ($active_uri=='dealers') ? '<span class="selected"></span>': '';?>
            </a>
           
        </li>
        <li class="nav-item  <?= ($active_uri=='users') ? 'active open': '';?>">
            <a href="<?php echo site_url('admin/users/index'); ?>" class="nav-link nav-toggle">
                <i class="fa fa-users"></i>
                <span class="title">Users</span>
                <?= ($active_uri=='users') ? '<span class="selected"></span>': '';?>
            </a>
            
        </li>
        <li class="nav-item <?= ($active_uri=='message') ? 'active open': '';?>">
            <a href="<?php echo site_url('admin/message'); ?>" class="nav-link nav-toggle">
                <i class="fa fa-envelope"></i>
                <span class="title">Message</span>
                <?= ($active_uri=='message') ? '<span class="selected"></span>': '';?>
                <!-- <span class="selected"></span> -->
            </a>
        </li>
        <li class="heading">
            <h3 class="uppercase">configuration</h3>
        </li>
        <li class="nav-item <?= ($active_uri=='settings') ? 'active open': '';?>">
            <a href="<?php echo site_url('admin/settings'); ?>" class="nav-link nav-toggle">
                <i class="fa fa-cogs"></i>
                <span class="title">Settings</span>
                <?= ($active_uri=='settings') ? '<span class="selected"></span>': '';?>
                <!-- <span class="selected"></span> -->
            </a>
        </li>
        
    </ul>
    <!-- END SIDEBAR MENU -->
    <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->
</div>