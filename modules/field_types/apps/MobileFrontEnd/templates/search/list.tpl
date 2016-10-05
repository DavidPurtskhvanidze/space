<select name='{$id}[equal]' id="{$id}" class="searchList">
	<option value="">[[Miscellaneous!Any:raw]] [[FormFieldCaptions!{$caption}:raw]]</option>
	{foreach from=$list_values item=list_value}
		<option value='{$list_value.id|escape}' {if $list_value.id == $value.equal}selected{/if} >{tr mode="raw" domain="Property_$id"}{$list_value.caption}{/tr}</option>
	{/foreach}
</select>
