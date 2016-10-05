<table class="items table table-hover">
	{foreach from=$statItems item=statItem}
		<tr class="{$statItem.trClass}">
			<td>[[$statItem.caption]]</td>
			<td>{$statItem.content}</td>
		</tr>
	{/foreach}
</table>
