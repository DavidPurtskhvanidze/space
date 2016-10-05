{if $pages_number > 1}
<div class="searchResultsPageSelector">
	<div class="prevPageSelector">
		{if $current_page-1 > 0}
			<a href="?action=restore&amp;searchId={$listing_search.id}&amp;page={$current_page-1}">« [[Previous]]</a>
		{else}
			<span>« [[Previous]]</span>
		{/if}
	</div>
	<div class="nextPageSelector">
		{if $current_page+1 <= $pages_number}
			<a href="?action=restore&amp;searchId={$listing_search.id}&amp;page={$current_page+1}">[[Next]] »</a>
		{else}
			<span>[[Next]] »</span>
		{/if}
	</div>
	<span class="currPageData">[[Page]] {$current_page}  [[of]] {$pages_number}</span>
</div>
{/if}
