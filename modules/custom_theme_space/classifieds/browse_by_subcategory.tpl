<ul class="list-unstyled browsing">
	{foreach from=$browseItemsGroupedByColumn item=browseItemsForColumn}
		{foreach from=$browseItemsForColumn item=browseItem}
			<li class="item">
                <a href="{page_path id='browse'}{$browseItem.url}/?view_all"{if $browseItem.count == 0} class="emptyLink"{/if}>[[$browseItem.caption]] ({$browseItem.count})</a>
			</li>
		{/foreach}
	{/foreach}
</ul>
