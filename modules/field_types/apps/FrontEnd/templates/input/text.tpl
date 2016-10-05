{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}
{if $GLOBALS.settings.enable_wysiwyg_editor}
	{$width='100%'}
	{$height=$parameters['height']|default:"300px"}
	<div class="form-control-group {if $hasError}has-error{/if}" {if $hasError}data-error="{$error}"{/if} style="width:{$width}; display:inline-block">
		{WYSIWYGEditor type="ckeditor" name="$id" width="$width" height="$height" ToolbarSet="Tiny"}
			{$value}
		{/WYSIWYGEditor}
	</div>
{else}
	<textarea class="form-control {if $hasError}has-error{/if} inputText{if $maxlength > 0} maxlength{/if}" name="{$id}" {if $hasError}data-error="{$error}"{/if} {if $maxlength > 0} maxlength={$maxlength}{/if}{$id_attribute}>{$value}</textarea>
{/if}
