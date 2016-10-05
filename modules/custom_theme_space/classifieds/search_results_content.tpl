{extension_point name='modules\main\apps\FrontEnd\ISearchResultsAdditionDisplayer' listingSearchID = $listing_search.id}


{if $listing_search.total_found > 0}

	<div class="searchResults">
		{foreach from=$listings item=listing name=listings}
			<div class="searchResultItemWrapper{if $listing@last} last{/if}">
				{display_listing listing=$listing listingControlsTemplate="search_result_listing_controls.tpl" listing_search=$listing_search}
			</div>
		{/foreach}
	</div>
	{include file="page_selector.tpl" current_page=$listing_search.current_page pages_number=$listing_search.pages_number url=$restore_url}
{else}
	{if !is_null($listing_search.search_form_uri)}
		{assign var=link value="{$GLOBALS.site_url}{$listing_search.search_form_uri}?action=restore&amp;searchId={$listing_search.id}"}
		<p class="error">[[There are no listings available that match your search criteria. Please try to <a href="$link">broaden your search criteria</a>.]]</p>
	{else}
		<p class="error">[[There are no listings available that match your search criteria. Please try to broaden your search criteria.]]</p>
	{/if}
{/if}
<script type="text/javascript">
	var listingsInComparisonCounter = {$listingsCountInComparison};
</script>
