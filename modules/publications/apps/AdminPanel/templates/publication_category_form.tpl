<div class="row">
	<div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>

	<form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
		<input type="hidden" name="action" value="save">
		<input type="hidden" name="category_sid" value="{$category.sid}">
		{foreach from=$form_fields item=ff}
			<div class="form-group">
				<label class="col-sm-2 control-label">
					[[$ff.caption]]
					{if $ff.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
				</label>

				<div class="col-sm-8">
					{input property=$ff.id}
				</div>
			</div>
		{/foreach}
		<div class="clearfix form-actions">
			<input type="submit" value="{if $action == 'add'}[[Add:raw]]{else}[[Save:raw]]{/if}" class="btn btn-default">
		</div>
	</form>
</div>
