<!-- Page header -->
<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">

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

                    </div>
                </div>
                <div class="panel-body">
                    <!-- BEGIN FORM-->
                    <?php echo form_open('admin/deductions/index', array('class' => 'form-horizontal', 'id' => 'form_sample_3')); ?>
                    <div class="form-body">
                        <?php for ($i = 0; $i < count($deductions); $i++): ?>
                            <div class="form-group <?= (form_error("month_deduction_{$deductions[$i]->month}")) ? 'has-error' : ''; ?>">
                                <label for="inputName9" class="control-label col-sm-3">
                                    <?= $deductions[$i]->month ?> Month
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" placeholder="1" name="month_deduction_<?= $deductions[$i]->month ?>" value="<?= $deductions[$i]->month_deduction;?>" />
                                    <?= form_error("month_deduction_{$deductions[$i]->month}", '<span class="help-block">', '</span>'); ?>
                                </div>
                            </div>
                            <!-- /.form-group -->
                        <?php endfor; ?>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-sm btn-success"><i class="icon-floppy-disk"></i> Submit </button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- /basic responsive configuration -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->