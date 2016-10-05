<div class="browseByState list{$number_of_cols}Columns">
	<h2>[[Browse by State]]</h2>
	{if $browseItemsGroupedByColumn|@count > 0}
		<ul>
			{foreach from=$browseItemsGroupedByColumn item=browseItemsForColumn}
				<li>
					<ul>
						{foreach from=$browseItemsForColumn item=browseItem}
							<li>
								<a href="{page_path id='browse_by_state'}{$browseItem.url}/"{if $browseItem.count == 0} class="emptyLink"{/if}>[[$browseItem.caption]] <span class="listingNumber">({$browseItem.count})</span></a>
							</li>
						{/foreach}
					</ul>
				</li>
			{/foreach}
		</ul>
	{else}
		[[There is no listing with the requested parameters in the system.]]
	{/if}
</div>
