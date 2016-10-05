
{foreach from=$list_values item=list_value}

	{if $list_value.id == $value}

		{tr domain="Property_$id"}{$list_value.caption}{/tr}
		
	{/if}

{/foreach}
