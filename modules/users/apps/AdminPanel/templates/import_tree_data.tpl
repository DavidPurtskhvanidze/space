<div class="importTreeValues">
	<div class="breadcrumbs">
	<a href="{page_path id='user_groups'}">[[User Groups]]</a>
	{if $type_info.sid != 0}
	&gt; <a href="{page_path id='edit_user_group'}?sid={$type_sid}">{$type_info.id}</a>
	{/if}
	&gt; <a href="{page_path id='edit_user_profile'}?user_group_sid={$type_info.sid}">[[Edit User Profile Fields]]</a>
	&gt; <a href="{page_path id='edit_user_profile_field'}?sid={$field_sid}&amp;user_group_sid={$type_info.sid}">[[$field.caption]]</a>
	</div>

	<h1>Import Tree Data</h1>

	<div class="hint">
		[[Please note that for the Excel format you can import .xls files of the Ms Office versions 95, 97, 2000 and 2003; and you cannot import .xlsx files.]]
	</div>

	{display_error_messages}

	<div class="info">[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]</div>
	<form method="post" enctype="multipart/form-data">
        {CSRF_token}
		<input type="hidden" name="field_sid" value="{$field.sid}">
		<input type="hidden" name="action" value="import">
		<table class="properties">
			<tr>
				<td>[[File]]</td>
				<td><span class="asterisk">*</span></td>
				<td><input type="file" name="imported_tree_file" class="form-control-file"></td>
			</tr>
			<tr>
				<td>[[File Format]]</td>
				<td><span class="asterisk">*</span></td>
				<td>
					<select name="file_format" class="form-control">
						<option value="csv">[[CSV]]</option>
						<option value="excel" {if $imported_file_config.file_format == excel}selected{/if}>[[Excel]]</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>[[Start Line]]</td>
				<td><span class="asterisk">*</span></td>
				<td><input type="text" name="start_line" value="{$imported_file_config.start_line}" class="form-control"></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td><input type="submit" value="[[Import:raw]]" class="btn btn-default"></td>
			</tr>
		</table>
	</form>
</div>
