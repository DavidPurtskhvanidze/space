<ul>
	{foreach from=$list_values item=list_value}
		{if isset($value[$list_value.rank])}
			<li>
				{tr domain="Property_$id"}{$list_value.caption}{/tr}
			</li>
		{/if}
	{/foreach}
</ul>
