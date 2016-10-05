{if !empty($listing_search)}
<div class="searchControls">
	<ul>
	<li class="backToResults">
		<a href="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}"><img class="linkIcon" src="{url file='main^icons/back_to_results.png'}" alt="&#8226;" /></a>&nbsp;
		<a href="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}">[[Back to Results]]</a>
	</li>
	{if $listing_search.prev}
		<li class="previous">
			<a href="{page_path id='listing'}{$listing_search.prev}/?searchId={$listing_search.id}">[[Previous]]</a>
		</li>
	{/if}
	{if $listing_search.next}
		<li class="next">
			<a href="{page_path id='listing'}{$listing_search.next}/?searchId={$listing_search.id}">[[Next]]</a>
		</li>
	{/if}
	</ul>
</div>
{/if}
