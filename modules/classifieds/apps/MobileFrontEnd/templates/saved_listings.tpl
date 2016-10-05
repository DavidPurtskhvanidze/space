<div class="savedListingsPage">
	<h1>[[Saved Listings]]</h1>
	{if $listing_search.total_found == 0}
		<p class="error">[[You have no saved listings now.]]</p>
	{else}
		{foreach from=$listings item=listing name=listings}
			{display_listing listing=$listing listingControlsTemplate="saved_listing_controls.tpl" listing_search=$listing_search}
		{/foreach}
		{include file="search_results_paging.tpl" current_page=$listing_search.current_page pages_number=$listing_search.pages_number}
	{/if}
</div>
