{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}

<div class="custom-form-control">
	<input type="hidden" name="{$id}" value="0">
    <input id="{$id}" type="checkbox" name="{$id}" {if $value}checked{/if} value="1" {$id_attribute}>
    <label class="checkbox {if $hasError}has-error{/if}" for="{$id}" {if $hasError}data-error="{$error}"{/if}>{$caption}</label>
</div>
