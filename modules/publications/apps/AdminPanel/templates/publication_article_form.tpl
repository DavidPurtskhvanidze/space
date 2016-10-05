<form method="post" class="form-horizontal" role="form" action="" enctype="multipart/form-data">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="article_sid" value="{$article.sid}">
	<input type="hidden" name="category_sid" value="{$category.sid}">

	<div class="form-group">
		<label class="col-sm-3 control-label">
			[[$form_fields.title.caption]]
			{if $form_fields.title.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
		</label>

		<div class="col-sm-8">
			{input property='title'}
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">
			[[$form_fields.description.caption]]
			{if $form_fields.description.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
		</label>

		<div class="col-sm-8">
			{input property='description' ToolbarSet='FullNoForms' skip_escaping=true}
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">
			[[$form_fields.text.caption]]
			{if $form_fields.text.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
		</label>

		<div class="col-sm-8">
			{input property='text' ToolbarSet='FullNoForms' skip_escaping=true}
		</div>
	</div>
	{if $action == 'add'}
		<div class="form-group">
			<label class="col-sm-3 control-label">

			</label>

			<div class="col-sm-8">
				{module name="listing_repost" function="display_add_publication_article_settings"}
			</div>
		</div>
	{/if}

	{if $action == 'edit'}
		<div class="form-group">
			<label class="col-sm-3 control-label">
				[[$form_fields.date.caption]]
				{if $form_fields.date.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
			</label>

			<div class="col-sm-8">
				{input property='date'}
				{capture name="date_format_example" assign="date_format_example"}{tr type="date"}now{/tr}{/capture}
				{i18n->getDateFormat assign="date_format"}
				<div class="help-block">
					[[date format: '$date_format', for example: '$date_format_example']]
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">
				[[Move the article to section]]
				{if $form_fields.category_sid.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
			</label>

			<div class="col-sm-8">
				{input property='category_sid'}
			</div>
		</div>
	{/if}

	<div class="form-group">
		<label class="col-sm-3 control-label">
			[[Picture]]
		</label>

		<div class="col-sm-8">
			{input property='picture' template='arcticle_picture.tpl'}
		</div>
	</div>

	<div class="clearfix form-actions">
		<input type="submit" value="{if $action == 'add'}[[Add:raw]]{else}[[Save:raw]]{/if}" class="btn btn-default">
	</div>
</form>
