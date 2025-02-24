<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">

		</div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="<?= site_url('manager/dashboard'); ?>" class="btn btn-danger btn-sm"> <i
						class=" icon-circle-left2"></i> BACK </a>
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
					<h5 class="panel-title"><?php if ($tickets_enabled == "1") echo 'All Tickets'; else echo 'My Tickets'; ?></h5>
					<div class="heading-elements">
						<div class="actiontools"></div>
					</div>
				</div>

				<div class="row">
					<div class="col-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">

								<div class="col-md-12 mb20">
									<div class="col-md-12">
										<a href="<?php echo site_url('manager/tickets/create'); ?>">
											<button class="btn btn-info" type="button">Create New Ticket</button>
										</a>
									</div>
									<div class="col-md-12" style="margin-top: 20px;">
										<?php echo $this->session->flashdata('success_msg'); ?>
										<?php echo $this->session->flashdata('error_msg'); ?>
										<div class="col-12" style="margin-top: 20px; margin-bottom: 20px;">
											<a href="<?php echo site_url('manager/tickets'); ?>">
												<button type="button" class="btn">Active Tickets</button>
											</a>
											<a href="<?php echo site_url('manager/tickets/complete'); ?>">
												<button type="button" class="btn btn-success">Completed Tickets</button>
											</a>
										</div>
										<div class="table-responsive">
											<table id="order-listing" class="table">
												<thead>
												<tr>
													<th>#</th>
													<th>Subject</th>
													<th>Status</th>
													<th>Last Updated</th>
													<?php if($tickets_enabled=="1") { ?>
														<th>Agent</th>
													<?php } ?>
													<th>Priority</th>
													<th>Owner</th>
													<th>Category</th>
												</tr>
												</thead>
												<tbody>
												<?php
												foreach($sql->result() as $data) {?>
													<tr>
														<td><?php echo $data->id; ?></td>
														<td><a href="<?php echo site_url('manager/tickets/view').'/'.base64_encode($data->id); ?>"><?php echo $data->subject; ?></a></td>
														<td><?php echo getStatusFromID($data->status_id); ?></td>
														<td><?php echo date("Y-m-d H:i:s",$data->updated_at); ?></td>
														<?php if($tickets_enabled=="1") { ?>
															<td><?php if($data->agent_id==0) {
																	echo '-';
																} else {
																	echo getOwnerNameFromID($data->agent_id);
																} ?></td>
														<?php } ?>
														<td><?php echo getPriorityFromID($data->priority_id); ?></td>
														<td><?php echo getOwnerNameFromID($data->user_id); ?></td>
														<td><?php echo $category[$data->category_id]; ?></td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /basic responsive configuration -->
	</div>
	<!-- /main content -->
</div>
<!-- /page content -->
</div>
<!-- /page container -->

<script>
	(function($) {
		'use strict';
		$(function() {
			$('#order-listing').DataTable({
				"aLengthMenu": [
					[5, 10, 20, 50, -1],
					[5, 10, 20, 50, "All"]
				],
				"iDisplayLength": 20,
				"language": {
					search: ""
				},
				"order": [[ 3, "desc" ]],
				"columnDefs": []
			});
			$('#order-listing').each(function() {
				var datatable = $(this);
				var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
				search_input.attr('placeholder', 'Search');
				search_input.removeClass('form-control-sm');
				var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
				length_sel.removeClass('form-control-sm');
			});
		});
	})(jQuery);
</script>
