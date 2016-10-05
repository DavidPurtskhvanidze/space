{strip}
{if $category.sid > 0}
	<option value="{$category.sid}" {if $category.sid == $selected }selected{/if} {if $category.listing_number == 0}disabled{/if}>
		{capture assign="categoryCaption"}[[Categories!{$category.caption}]]{/capture}
		{$categoryCaption|indent:$level:" &nbsp; &nbsp;"}({$category.listing_number})
	</option>
{/if}
{if $category.categories}
    {foreach from=$category.categories item=subcategory}
	        {include file="category_item_option.tpl" category=$subcategory level=$level+1 selected=$selected}
    {/foreach}
{/if}
{/strip}
