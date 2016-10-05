<ul class="list-unstyled browsing">
    {foreach from=$browseItemsGroupedByColumn item=browseItemsForColumn}
        {foreach from=$browseItemsForColumn item=browseItem}
            {if $browseItem.count > 0}
                <li class="item">
                    <a href="{page_path id='browse_by_state'}{$browseItem.url}/">[[$browseItem.caption]] ({$browseItem.count})</a>
                </li>
            {/if}
        {/foreach}
    {/foreach}
</ul>
