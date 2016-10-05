{if $count == 1}
	{capture assign=link}
		#{$listingIds.0}
	{/capture}
	[[Listing $link has been successfully deleted.]]
{else}
	[[Selected listings have been successfully deleted.]]
{/if}
