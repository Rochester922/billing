<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php echo $module; ?></span> </h4>
			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="<?= site_url('dealer/dashboard'); ?>">Home</a></li>
				<li class="active"><?php echo $module; ?></li>
			</ul>
		</div>
		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="<?= site_url('dealer/dealers/index'); ?>" class="btn btn-danger btn-sm"> <i class=" icon-circle-left2"></i> BACK </a>
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

					</div>
				</div>
				<div class="panel-body">
					<script src="/assets/ckeditor/ckeditor.js"></script>
					<!-- BEGIN FORM-->
					<?php $attr = array('class'=>'form-horizontal bs-example','role'=>'form');?>
					<?php echo form_open('dealer/tickets/create',array('class'=>'form-horizontal','id'=>'form_sample_3'));?>
					<!-- <form action="#" id="form_sample_3" class="form-horizontal"> -->
					<div class="form-body">

						<div class="form-group  <?= (form_error('channel_number')) ? 'has-error' : '' ; ?>">
							<label for="channel_number" class="control-label col-sm-3">Enter Your Channel Number</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" placeholder="" id="channel_number" name="channel_number" value="" />
								<?= form_error('channel_number','<span class="help-block">','</span>');?>
							</div>
						</div>
						<!-- /.form-group -->
						<div class="form-group  <?= (form_error('subject')) ? 'has-error' : '' ; ?>">
							<label for="subject" class="control-label col-sm-3">Subject</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" placeholder="" id="subject" name="subject" value="" />
								<?= form_error('subject','<span class="help-block">','</span>');?>
							</div>
						</div>
						<!-- /.form-group -->
						<div class="form-group  <?= (form_error('priority')) ? 'has-error' : '' ; ?>">
							<label for="priority" class="control-label col-sm-3">Priority</label>
							<div class="col-sm-3">
								<select class="form-control" name="priority">
									<option value="" selected="selected">Select Priority</option>
									<option value="1">High</option>
									<option value="2">Normal</option>
									<option value="3">Low</option>
								</select>
								<?= form_error('priority','<span class="help-block">','</span>');?>
							</div>
						</div>
						<!-- /.form-group -->
						<div class="form-group  <?= (form_error('category')) ? 'has-error' : '' ; ?>">
							<label for="category" class="control-label col-sm-3">Category</label>
							<div class="col-sm-3">
								<select class="form-control" name="category" id="category">
									<?php
									foreach ($category as $key=>$value) {
										echo '<option value="'.$key.'">'.$value.'</option>';
									}
									?>
								</select>
								<?= form_error('category','<span class="help-block">','</span>');?>
							</div>
						</div>
						<!-- /.form-group -->
						<div class="form-group  <?= (form_error('channel')) ? 'has-error' : '' ; ?>">
							<label for="channel" class="control-label col-sm-3">Channel</label>
							<div class="col-sm-3">
								<select class="form-control" name="channel">
								</select>
								<?= form_error('channel','<span class="help-block">','</span>');?>
							</div>
						</div>
						<!-- /.form-group -->
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<div class="form-check form-check-info">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input" name="no_audio">
											No audio
											<i class="input-helper"></i></label>
									</div>
									<div class="form-check form-check-info">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input" name="no_video">
											No video
											<i class="input-helper"></i></label>
									</div>
									<div class="form-check form-check-info">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input" name="stream_error">
											Stream Error
											<i class="input-helper"></i></label>
									</div>
									<div class="form-check form-check-info">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input" name="no_epg">
											No EPG
											<i class="input-helper"></i></label>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<div class="form-check form-check-info">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input" name="catch_up_needed">
											Catch Up Needed
											<i class="input-helper"></i></label>
									</div>
									<div class="form-check form-check-info">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input" name="epg_needed">
											EPG needed
											<i class="input-helper"></i></label>
									</div>
									<div class="form-check form-check-info">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input" name="file_missing">
											File Missing On Catch Up
											<i class="input-helper"></i></label>
									</div>
									<div class="form-check form-check-info">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input" name="wrong_channel_name">
											Wrong Channel Name
											<i class="input-helper"></i></label>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group ">
									<label for="description">Description</label>
									<textarea class="form-control" name="description" rows="5"></textarea>
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
										CKEDITOR.replace( 'description');
									</script>
								</div>
							</div>
						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button type="submit" class="btn btn-sm btn-success"><i class="icon-floppy-disk"></i> Submit </button>
								<a class="btn btn-sm btn-danger" href="<?= site_url('dealer/tickets/index'); ?>" ><i class="icon-blocked"></i> Cancel </a>
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

<script>
	var findNumber = false;
	function getChannelCategory()
	{
		if(findNumber==false) {
			var url = '/index.php/dealer/tickets/channels';
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
		var url = '/index.php/dealer/tickets/channels';
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
		var url = '/index.php/dealer/tickets/channel_category_and_id';
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
