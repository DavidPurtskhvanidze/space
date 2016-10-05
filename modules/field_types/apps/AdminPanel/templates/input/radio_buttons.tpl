{foreach from=$list_values item=list_value}
	<label>
		<input type="radio" name="{$id}" value="{$list_value.id|escape}"{if $list_value.id == $value} checked='checked'{/if}>
		[[{$list_value.caption}]]
	</label>
	{$divider}
{/foreach}
