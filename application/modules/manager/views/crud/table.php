<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard'); ?>">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span><?php echo $module; ?></span>
            </li>
        </ul>
        <div class="page-toolbar">
            <div class="btn-group">
                <button class="btn red" id="goback"><i class="fa fa-arrow-left"></i> BACK </button>
            </div>
        </div>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> <?php echo $module;?>
    
    </h1>
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject bold uppercase"> Managed Table</span>
                    </div>
                    <div class="tools">
                        <a href="" class="collapse" data-original-title="" title=""> </a>
                        <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                        <a href="" class="reload" data-original-title="" title=""> </a>
                        <a href="" class="fullscreen" data-original-title="" title=""> </a>
                        <a href="" class="remove" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="btn-group">
                                    <a id="sample_editable_1_new" href="<?= site_url('admin/resellers/add')?>" class="btn sbold green"> Add New
                                    <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th> Name </th>
                                <th> Username </th>
                                <th> Status </th>
                                <th> Password </th>
                                <th> Credits </th>
                                <th class="text-center"> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sno=0; foreach($sql->result() as $row):?>
                            <tr class="odd gradeX">
                                <td><?= ++$sno;?></td>
                                <td><?= $row->name;?></td>
                                <td><?= $row->username;?></td>
                                <td><?= ($row->status=='A') ? '<span class="label label-sm label-success block">Active</span>':'<span class="label label-sm label-danger block">INACTIVE</span>' ;?></td>
                                <td><?= $row->password;?></td>
                                <td><?= $row->credit;?></td>
                                <td class="text-center">
                                <div class="btn-group btn-group-solid" style="position: inherit;">
                                    <a href="#" class="btn btn-success btn-sm"><i class="fa fa-money flat-shadow"></i> </a>
                                    <a href="#" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> </a>
                                    <a href="#" class="btn btn-danger btn-sm "><i class="fa fa-trash"></i></a>
                                </div>
                                    
                                </td>
                            </tr>
                            <?php endforeach;?>
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
</div>