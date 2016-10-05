<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li>[[Import Language]]</li>
	</ul>
</div>

<div class="page-content">
	<div class="page-header">
		<h1 class="lighter">[[Import Language]]</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			{display_error_messages}
			<form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
                {CSRF_token}
				<input type="hidden" name="action" value="import_language">
				<div class="form-group">
					<label class="col-sm-3 control-label">
						[[Language Import File]]
					</label>
					<div class="col-sm-5">
						<input type="file" name="lang_file" id="id-input-file">
					</div>
				</div>
				<div class="clearfix form-actions">
					<input type="submit" value="[[Import:raw]]" class="btn btn-default">
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#id-input-file').ace_file_input({
		no_file:'No File ...',
		btn_choose:'Choose',
		btn_change:'Change',
		droppable:false,
		onchange:null,
		icon_remove: false,
		thumbnail:false, //| true | large
		blacklist:'exe|php|gif|png|jpg|jpeg'
		//whitelist:'gif|png|jpg|jpeg'
		//onchange:''
		//
	});
</script>
