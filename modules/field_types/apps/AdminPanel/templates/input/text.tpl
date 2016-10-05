{if $GLOBALS.settings.enable_wysiwyg_editor}
	{if empty($ToolbarSet)}
		{$ToolbarSet="FullNoForms"}
	{/if}
	{$width='100%'}
	{$height='200px'}
	<div class="form-control-group {if $hasError}has-error tooltip-error{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if} style="width:{$width}; display:inline-block">
		{WYSIWYGEditor type="ckeditor" name="$id" width="$width" height="$height" ToolbarSet=$ToolbarSet}
			{$value}
		{/WYSIWYGEditor}
	</div>
{else}
	<textarea name="{$id}" class="form-control {if $hasError}has-error tooltip-error{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}>{$value}</textarea>
{/if}
