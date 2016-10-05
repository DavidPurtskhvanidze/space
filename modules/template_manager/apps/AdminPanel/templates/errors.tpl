{if $isNotEditableReason.code == "THEME_IS_READ_ONLY"}
	<div class="alert alert-warning">
		[[You cannot modify the look of your site when you use a default theme.]]<br />
		[[Please create a custom theme in]] &quot;[[Site Layout]]&quot; -> &quot;[[Themes]]&quot; [[to make changes to templates.]]
	</div>
{elseif $isNotEditableReason.code == "TEMPLATE_IS_NOT_WRITABLE"}
	{$current_template=$isNotEditableReason.filePath}
	<p class="text-warning">[[Cannot write to $current_template template file.]] [[Please check the permissions are 666.]]</p>
{elseif $isNotEditableReason.code == "MODULE_TEMPLATES_DIR_IS_NOT_WRITABLE"}
	{$current_theme=$isNotEditableReason.filePath}
	<p class="text-warning">[[Cannot write to $current_theme directory.]] [[Please check the permissions are 777.]]</p>
{elseif $isNotEditableReason.code == "THEME_DIR_IS_NOT_WRITABLE"}
	{$current_theme=$isNotEditableReason.filePath}
	<p class="text-warning">[[Cannot write to $current_theme directory.]] [[Please check the permissions are 777.]]</p>
{elseif $isNotEditableReason.code == "THEME_ALREADY_EXISTS"}
	{$fileName=$isNotEditableReason.filePath}
	<p class="text-danger">[[Cannot create $fileName.]] [[The file with specified name already exists.]]</p>
{/if}
