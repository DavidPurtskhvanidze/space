<div class="row">
	{foreach from=$browseItemsGroupedByColumn item=browseItemsForColumn}
		{foreach from=$browseItemsForColumn item=browseItem}
            {if $browseItem.count > 0}
			<div class="col-sm-4 col-md-3">
				<a href="{page_path id='browse'}{$browseItem.url}/"{if $browseItem.count == 0} class="emptyLink"{/if}>[[$browseItem.caption]] ({$browseItem.count})</a>
			</div>
            {/if}
		{/foreach}
	{/foreach}
</div>
