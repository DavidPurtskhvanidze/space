{if $level gt 0}
    {if $category.categories}
        <p class="bigger-120" style="padding-left:{$level*35}px">[[Categories!{$category.caption}]]</p>
        {foreach from=$category.categories item=subcategory}
            {include file="add_listing_choose_category_node.tpl" category=$subcategory level=$level+1} 
        {/foreach}
    {else}
        <p class="bigger-120" style="padding-left:{$level*35}px">
            <a href="?listing_package_id={$package}&category_id={$category.id}">[[Categories!{$category.caption}]]</a>
        </p>
    {/if}
{else}
    {foreach from=$category.categories item=subcategory}
        {include file="add_listing_choose_category_node.tpl" category=$subcategory level=$level+1} 
    {/foreach}   
{/if}
