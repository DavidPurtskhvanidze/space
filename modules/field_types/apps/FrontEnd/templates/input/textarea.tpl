{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}
<textarea name="{$id}"{$id_attribute}
		class="form-control {if $hasError}has-error{/if}"
		{if $hasError}data-error="{$error}"{/if}>
	{$value}
</textarea>
