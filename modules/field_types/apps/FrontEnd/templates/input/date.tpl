{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}
<input type="text" value="{$value|escape}"
       name="{$id}"
       {if $maxlength > 0}maxlength="{$maxlength}"{/if}
       {$id_attribute}
       class="inputString {if $maxlength > 0}maxlength{/if} form-control {if $hasError}has-error{/if}"
       {if $hasError}data-error="{$error}"{/if}
       placeholder="{$placeholder}" />
