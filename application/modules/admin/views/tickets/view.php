<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php echo $module; ?></span> </h4>
			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="<?= site_url('admin/dashboard'); ?>">Home</a></li>
				<li class="active"><?php echo $module; ?></li>
			</ul>
		</div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="<?= site_url('admin/tickets/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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
				<?php
				$result = '';
				foreach($sql_ticket->result() as $data) {
					$result = $data;
					break;
				}
				?>
				<div class="panel-heading">
					<h5 class="panel-title"><?= $title.' - '.$result->subject; ?></h5>
					<div class="heading-elements">

					</div>
				</div>
				<script src="/assets/ckeditor/ckeditor.js"></script>

				<div class="panel-body">
					<div >
						<div class="row">
							<div class="col-12 grid-margin stretch-card">
								<div class="card">
									<div class="card-body">

										<?php echo $this->session->flashdata('success_msg');?>
										<?php echo $this->session->flashdata('error_msg');?>
										<?php if($data->status_id==2 && $tickets_enabled==0) { ?>
											<div class="row">
												<div class="col-lg-12 mb20">
													<?php $attr = array('class'=>'form-horizontal bs-example','role'=>'form');?>
													<?php echo form_open(site_url('admin/tickets/reopen'),$attr);?>
													<input type="hidden" name="subject" value="<?php echo $data->subject; ?>">
													<input type="hidden" name="redirect" value="<?php echo base64_encode($data->id); ?>">
													<input type="hidden" name="ticket_id" value="<?php echo base64_encode($data->id); ?>">
													<button class="btn btn-success" type="submit" style="margin-top: 10px;">Reopen Ticket</button>
													</form>
												</div>
											</div>
										<?php } ?>
										<?php if($data->status_id!=2 && $tickets_enabled==0) { ?>
											<div class="row">
												<div class="col-lg-12 mb20">
													<?php $attr = array('class'=>'form-horizontal bs-example','role'=>'form');?>
													<?php echo form_open(site_url('admin/tickets/markcomplete'),$attr);?>
													<input type="hidden" name="subject" value="<?php echo $data->subject; ?>">
													<input type="hidden" name="redirect" value="<?php echo base64_encode($data->id); ?>">
													<input type="hidden" name="ticket_id" value="<?php echo base64_encode($data->id); ?>">
													<button class="btn btn-success" type="submit" style="margin-top: 10px;">Mark Complete</button>
													</form>
												</div>
											</div>
										<?php } ?>


										<?php if($tickets_enabled==1) { ?>
											<div class="row">
												<div class="col-lg-12 mb20">
													<?php if($data->status_id==2) { ?>
														<?php $attr = array('class'=>'form-horizontal bs-example','role'=>'form');?>
														<?php echo form_open(site_url('admin/tickets/reopen'),$attr);?>
														<input type="hidden" name="subject" value="<?php echo $data->subject; ?>">
														<input type="hidden" name="redirect" value="<?php echo base64_encode($data->id); ?>">
														<input type="hidden" name="ticket_id" value="<?php echo base64_encode($data->id); ?>">
														<button class="btn btn-success manager-button" type="submit" style="margin-top: 10px;">Reopen Ticket</button>
														</form>
													<?php } ?>
													<?php if($data->status_id!=2) { ?>
														<?php $attr = array('class'=>'form-horizontal bs-example','role'=>'form');?>
														<?php echo form_open(site_url('admin/tickets/markcomplete'),$attr);?>
														<input type="hidden" name="subject" value="<?php echo $data->subject; ?>">
														<input type="hidden" name="redirect" value="<?php echo base64_encode($data->id); ?>">
														<input type="hidden" name="ticket_id" value="<?php echo base64_encode($data->id); ?>">
														<button class="btn btn-success manager-button" type="submit" style="margin-top: 10px;">Mark Complete</button>
														</form>
													<?php } ?>

													<button class="btn btn-info manager-button" type="button" data-toggle="modal" data-target="#edit-ticket-modal" style="margin-top: 10px;">Edit</button>
													<div class="modal fade" id="edit-ticket-modal" tabindex="-1" role="dialog" aria-labelledby="edit-ticket-modal" aria-hidden="true">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title" id="edit-ticket-modal"><?php echo $data->subject; ?></h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																<?php $attr = array('class'=>'form-horizontal bs-example','role'=>'form');?>
																<?php echo form_open(site_url('admin/tickets/updateticket'),$attr);?>
																<div class="modal-body">
																	<div class="form-group <?php if(form_error('priority')) { echo 'has-danger'; }?>">
																		<label for="description">Priority</label>
																		<select class="form-control" name="priority" id="priority">
																			<option value="1" <?php if($data->priority_id=="1") echo 'selected'; ?>>High</option>
																			<option value="2" <?php if($data->priority_id=="2") echo 'selected'; ?>>Normal</option>
																			<option value="3" <?php if($data->priority_id=="3") echo 'selected'; ?>>Low</option>
																		</select>
																		<?php echo form_error('priority','<label class="error mt-2 text-danger">','</label>');?>
																	</div>
																	<div class="form-group <?php if(form_error('status')) { echo 'has-danger'; }?>">
																		<label for="description">Status</label>
																		<select class="form-control" name="status" id="status">
																			<option value="1" <?php if($data->status_id=="1") echo 'selected'; ?>>In Progress</option>
																			<option value="2" <?php if($data->status_id=="2") echo 'selected'; ?>>Fixed</option>
																			<option value="3" <?php if($data->status_id=="3") echo 'selected'; ?>>Re-opened</option>
																		</select>
																		<?php echo form_error('status','<label class="error mt-2 text-danger">','</label>');?>
																	</div>
																</div>
																<div class="modal-footer">
																	<input type="hidden" name="subject" value="<?php echo $data->subject; ?>">
																	<input type="hidden" name="redirect" value="<?php echo base64_encode($data->id); ?>">
																	<input type="hidden" name="ticket_id" value="<?php echo base64_encode($data->id); ?>">
																	<button type="submit" class="btn btn-success">Submit</button>
																	<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
																</div>
																</form>
															</div>
														</div>
													</div>

													<?php $attr = array('class'=>'form-horizontal bs-example','role'=>'form');?>
													<?php echo form_open(site_url('admin/tickets/delete'),$attr);?>
													<input type="hidden" name="subject" value="<?php echo $data->subject; ?>">
													<input type="hidden" name="redirect" value="<?php echo base64_encode($data->id); ?>">
													<input type="hidden" name="ticket_id" value="<?php echo base64_encode($data->id); ?>">
													<button class="btn btn-danger  manager-button" type="submit" style="margin-top: 10px;" onclick="return confirm('Are you sure you want to delete this ticket?');">Delete</button>
													</form>
												</div>
											</div>
										<?php } ?>
										<div class="card col-md-12" style="box-shadow: none; border: 1px solid #f3f3f3; margin: 20px 0px;">
											<div class="card-body row info-ticket-body"
												 style="padding-top: 10px; padding-bottom: 10px;">
												<div class="col-md-6">
													<p><strong>Owner</strong>: <?php echo getOwnerNameFromID($data->user_id); ?></p>
													<p><strong>Status</strong>: <?php echo getStatusFromID($data->status_id); ?></p>
													<p><strong>Priority</strong>: <?php echo getPriorityFromID($data->priority_id); ?></p>
													<p><strong>Options</strong>: <?php echo listOptions($data); ?></p>
												</div>
												<div class="col-md-6">
													<p><strong>Category</strong>: <?php echo $category[$data->category_id]; ?></p>
													<p><strong>Created</strong>: <?php echo date("Y-m-d H:i:s",$data->created_at); ?></p>
													<p><strong>Last Update</strong>: <?php echo date("Y-m-d H:i:s",$data->updated_at); ?></p>
												</div>
											</div>
										</div>
										<div style="margin-bottom: 20px;"><?php echo $data->html; ?></div>

									</div>
								</div>

							</div>
						</div>
						<?php foreach($sql_comments->result() as $data_comment) { ?>
							<div class="row" style="margin-top: 15px; margin-bottom: 15px;">
								<div class="col-md-12">
									<div class="card mb-3" style="border: 1px solid #ccc;">
										<div class="card-header d-flex justify-content-between align-items-baseline flex-wrap" style="overflow: hidden; background: #eee;  padding: 10px;">
											<div class="comment-name" style="float: left;"><p><?php echo getOwnerNameFromID($data_comment->user_id); ?></p></div>
											<div class="comment-date" style="float: right;"><p><?php echo date("Y-m-d H:i:s",$data_comment->updated_at); ?></p></div>
										</div>
										<div class="card-body" style="padding: 10px;"><?php echo $data_comment->html; ?></div>
									</div>
								</div>
							</div>
						<?php } ?>

						<div class="row">
							<div class="col-md-12" style="margin-top: 30px;">
								<div class="card mb-3">
									<div class="card-body">
										<?php $attr = array('class'=>'form-horizontal bs-example','role'=>'form');?>
										<?php echo form_open(site_url('admin/tickets/comment'),$attr);?>
										<div class="form-group <?php if(form_error('comment')) { echo 'has-danger'; }?>">
											<label for="comment">Comment</label>
											<textarea class="form-control" name="comment" rows="5"></textarea>
											<style>.cke_button__emojione, .cke_button__specialchar, .cke_toolbar_last,
												.cke_dialog_ui_hbox.cke_dialog_image_url .cke_dialog_ui_button,
												#cke_133_uiElement{
													display: none !important;
												}
												.cke_dialog_ui_hbox.cke_dialog_image_url .cke_dialog_ui_hbox_first {
													width: 100% !important;
													padding-right: 0px;
												}

											</style>
											<script>
												CKEDITOR.editorConfig = function( config ) {
													config.removeButtons = 'BGColor,About,Flash,Smiley,SpecialChar,HorizontalRule,PageBreak,Iframe,Anchor,BidiRtl,Language,BidiLtr,CreateDiv,Outdent,Indent,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Scayt,PasteFromWord,Templates,Save,NewPage,Preview,Print';

													config.toolbarGroups = [
														{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
														{ name: 'forms', groups: [ 'forms' ] },
														{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
														{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
														{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
														{ name: 'links', groups: [ 'links' ] },
														{ name: 'insert', groups: [ 'insert' ] },
														{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
														'/',
														{ name: 'styles', groups: [ 'styles' ] },
														{ name: 'colors', groups: [ 'colors' ] },
														{ name: 'tools', groups: [ 'tools' ] },
														{ name: 'others', groups: [ 'others' ] },
														{ name: 'about', groups: [ 'about' ] }
													];

												};
												CKEDITOR.replace( 'comment');
											</script>
											<?php echo form_error('comment','<label class="error mt-2 text-danger">','</label>');?>
										</div>
										<input type="hidden" name="ticket_id" value="<?php echo $data->id; ?>">
										<input type="hidden" name="redirect" value="<?php echo base64_encode($data->id); ?>">
										<button type="submit" class="btn btn-info mr-2">Reply</button>
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
	var findNumber = false;
	function getChannelCategory()
	{
		if(findNumber==false) {
			var url = '/index.php/admin/tickets/channels';
			$.ajax({
				type: 'GET',
				url: url,
				async: true,
				data: {id: $('select[name="category"] option:selected').val()},
				success: function(response) {
					$('select[name="channel"]').empty();
					console.log(response);
					var result = jQuery.parseJSON(response);
					$.each(result, function (key, value) {
						$('select[name="channel"]')
							.append($("<option></option>")
								.attr("data-number", value.number)
								.attr("value", value.id)
								.text(value.name));

						/*Set Subject Text From The First Option OF Channel*/
						$("input[name=channel_number]").val($('select[name="channel"] option:selected').data('number'));
						$("#subject").val($('select[name="channel"] option:selected').text());
					});
				}
			});
		}
	}
	function getChannelCategorySet(category, id)
	{
		var url = '/index.php/admin/tickets/channels';
		$.ajax({
			type: 'GET',
			url: url,
			async: true,
			data: {id: $('select[name="category"] option:selected').val()},
			success: function(response) {
				console.log(response);
				var result = jQuery.parseJSON(response);
				$('select[name="channel"]').empty();
				$.each(result, function(key, value) {
					if(value.id==id) {
						$('select[name="channel"]')
							.append($("<option></option>")
								.attr("value",value.id)
								.attr("data-number", value.number)
								.attr("selected","selected")
								.text(value.name));
					} else {
						$('select[name="channel"]')
							.append($("<option></option>")
								.attr("data-number", value.number)
								.attr("value",value.id)
								.text(value.name));
					}

				});

				$("#subject").val($('select[name="channel"] option:selected').text());
				findNumber = true;
			}
		});
	}
	function getChannelCategoryNumber()
	{
		findNumber = true;
		var url = '/index.php/admin/tickets/channel_category_and_id';
		$.ajax({
			type: 'GET',
			url: url,
			async: true,
			data: {number: $('input[name="channel_number"]').val()},
			success: function(response) {
				console.log(response);
				var result = jQuery.parseJSON(response);
				$.each(result, function(key, value) {
					$('select[name="category"]').val(value.tv_genre_id).trigger('change');
					getChannelCategorySet(value.tv_genre_id, value.id);
				});
			}
		});
	}
	$(function(){
		/*Get Channels From Specific Category*/
		getChannelCategory();
		$('select[name="category"]').change(function(){
			getChannelCategory();
		});
		/*Set Subject Text From The Selected Option Text OF Channel*/

		$('select[name="channel"]').change(function(){

			$("input[name=channel_number]").val($('select[name="channel"] option:selected').data('number'));
			$("#subject").val($('select[name="channel"] option:selected').text());
		});

		$('input[name="channel_number"]').change(function(){
			getChannelCategoryNumber();
		});
		$('input[name="channel_number"]').keyup(function(){
			getChannelCategoryNumber();
		});
	});
</script>


<script>
	jQuery(document).ready(function () {
		jQuery('.nav-item.active, .nav-link.active').removeClass('active');
		jQuery('.nav-link.link-tickets-create').addClass('active');
		jQuery('.nav-link.link-tickets-create').parent().parent().parent().parent().addClass('active');
	});
</script>
