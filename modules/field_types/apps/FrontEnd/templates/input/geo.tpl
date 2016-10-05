{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}
<input type="text" value="{$value|escape}" name="{$id}"{$id_attribute}
       class="inputGeo form-control {if $hasError}has-error{/if}"
       {if $hasError}data-error="{$error}"{/if}
       placeholder="{$placeholder}" />
