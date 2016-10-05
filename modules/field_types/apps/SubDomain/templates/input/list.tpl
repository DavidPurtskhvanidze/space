{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}
<select name='{$id}'{$id_attribute}
        class="inputList form-control {if $hasError}has-error{/if}"
        {if $hasError}data-error="{$error}"{/if}>
	<option value="">[[Miscellaneous!Select:raw]] [[FormFieldCaptions!{$caption}:raw]]</option>
	{foreach from=$list_values item=list_value}
		<option value='{$list_value.id|escape}' {if $list_value.id == $value}selected="selected"{/if} >{tr mode="raw" domain="Property_$id"}{$list_value.caption}{/tr}</option>
	{/foreach}
</select>
