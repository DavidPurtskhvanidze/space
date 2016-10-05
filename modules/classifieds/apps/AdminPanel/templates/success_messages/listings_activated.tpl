{if $count == 1}
	{capture assign=link}
		<a href="{page_path id='display_listing'}?listing_id={$listingIds.0}">#{$listingIds.0}</a>
	{/capture}
	[[Listing $link has been successfully activated.]]
{else}
	[[Selected listings have been successfully activated.]]
{/if}
