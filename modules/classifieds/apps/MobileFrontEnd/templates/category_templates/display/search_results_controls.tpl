{if !empty($listing_search)}
<div class="searchResultsControls">
	<div class="searchResultsPageSelector">
		<div class="prevPageSelector">
			{if $listing_search.prev}
				<a href="{page_path id='listing'}{$listing_search.prev}/?searchId={$listing_search.id}">« [[Previous]]</a>
			{else}
				<span>« [[Previous]]</span>
			{/if}
		</div>
		<div class="nextPageSelector">
			{if $listing_search.next}
				<a href="{page_path id='listing'}{$listing_search.next}/?searchId={$listing_search.id}">[[Next]] »</a>
			{else}
				<span>[[Next]] »</span>
			{/if}
		</div>
		<span class="restoreSearch">
			{if !empty($listing_search)}
				<a href="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&searchId={$listing_search.id}">[[Back to Results]]</a>
			{else}
				&nbsp;
			{/if}
		</span>
	</div>
</div>
{/if}
