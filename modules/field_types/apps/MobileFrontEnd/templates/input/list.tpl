{if $hasError}<div class="error validation">{$error}</div>{/if}
<select class="inputList" name="{$id}" id="{$id}">
	<option value="">[[Miscellaneous!Select:raw]] [[FormFieldCaptions!{$caption}:raw]]</option>
	{foreach from=$list_values item=list_value}
		<option value='{$list_value.id|escape}' {if $list_value.id == $value}selected{/if} >{tr mode="raw" domain="Property_$id"}{$list_value.caption}{/tr}</option>
	{/foreach}
</select>
