<div class="breadcrumbs">
	<ul class="breadcrumb">
		<li><a href="{page_path id='user_groups'}">[[User Groups]]</a>
			{if $user_group_sid != 0}&gt; <a href="{page_path id='edit_user_group'}?sid={$user_group_sid}">{$user_group_info.id}</a>{/if}
			&gt; <a href="{page_path id='edit_user_profile'}?user_group_sid={$user_group_sid}">[[Edit User Profile Fields]]</a>
			&gt; {$user_profile_field_info.caption}</li>
	</ul>
</div>
<div class="page-content">
	<div class="page-header">
		<h1 class="lighter">[[Edit User Profile Field]]</h1>
	</div>
	{display_error_messages}
	<div class="row">
		<div class="alert alert-info">[[Fields marked with an asterisk (<i class="icon-asterisk smaller-60"></i>) are mandatory]]</div>
		<form method="post" class="form-horizontal" role="form">
			<input type="hidden" name="action" value="save_info">
			<input type="hidden" name="sid" value="{$user_profile_field_sid}">
			<input type="hidden" name="user_group_sid" value="{$user_group_sid}">
			{foreach from=$form_fields key=field_name item=form_field}
				<div class="form-group">
					<label class="col-sm-2 control-label">
						[[$form_field.caption]]
						{if $form_field.is_required}<i class="icon-asterisk smaller-60"></i>{/if}
					</label>

					<div class="col-sm-8">
						{input property=$form_field.id}
					</div>
				</div>
			{/foreach}
			<div class="clearfix form-actions">
				<input type="submit" value="[[Save:raw]]" class="btn btn-default">
			</div>
		</form>
	</div>
	{if $field_type eq 'list'}
		<a class="btn btn-link" href="{page_path id='edit_user_profile_field_edit_list'}?field_sid={$user_profile_field_sid}">[[Edit List Values]]</a>
	{elseif $field_type eq 'geo'}
		<a class="btn btn-link" href="{page_path id='geographic_data'}">[[Edit Geographic Data]]</a>
	{elseif $field_type eq 'tree'}
		<a class="btn btn-link" href="{page_path id='edit_user_profile_field_edit_tree'}?field_sid={$user_profile_field_sid}">[[Edit Tree Values]]</a>
	{/if}
</div>
