{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}

<div class="checkbox {if $hasError}has-error{/if}"  {if $hasError}data-error="{$error}"{/if}>
	<input type="hidden" name="{$id}" value="0">
	<label>
		<input type="checkbox" name="{$id}" {if $value}checked{/if} value="1" {$id_attribute}> {$caption}
	</label>
</div>
