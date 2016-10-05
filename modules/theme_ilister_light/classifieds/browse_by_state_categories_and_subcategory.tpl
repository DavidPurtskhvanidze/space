{if $browseItemsGroupedByColumn|@count > 0}
	{foreach from=$browseItemsGroupedByColumn item=browseItemsForColumn}
		<ul>
			{foreach from=$browseItemsForColumn item=browseItem}
				<li>
					<a href="{$browseItem.url}/"{if $browseItem.count == 0} class="emptyLink"{/if}>[[$browseItem.caption]] <span class="listingNumber">({$browseItem.count})</span></a>
				</li>
			{/foreach}
		</ul>
	{/foreach}
{/if}
