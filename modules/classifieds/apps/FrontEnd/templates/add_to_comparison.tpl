{foreach from=$errors item=error}
	{if $error == 'LISTING_ID_NOT_SPECIFIED'}
		[[Listing ID is not specified]]
	{else}
	    <p class="error">[[$error]]</p>
	{/if}
{foreachelse}
	{* Show the number of the listings in the comparison table if requested. *}
	{if !is_null($REQUEST.getCount)}{$listingCount}{/if}
{/foreach}
