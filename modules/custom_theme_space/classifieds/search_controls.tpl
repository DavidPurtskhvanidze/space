<div id="ManageSearch">
	<div class="row">
		<div class="col-md-6">
			{if $GLOBALS.current_base_uri != '/search-results/' && $listing_search.id}
				<div class="search-result-bar-tab-item"><a href="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}">[[Back to Results]]</a></div>
			{/if}
			{if $listing_search.search_form_uri}
				<div class="search-result-bar-tab-item"><a href="{$GLOBALS.site_url}{$listing_search.search_form_uri}?action=restore&amp;searchId={$listing_search.id}">[[Modify Search]]</a></div>
			{/if}
			<div class="search-result-bar-tab-item"><a href="{page_path id='search'}">[[New Search]]</a></div>
			{if $listing_search.id}
				<div class="search-result-bar-tab-item"><a onclick='return openDialogWindow("[[Save Search:raw]]", this.href, 420, true)' class="saveSearch" href="{page_path id='search_save'}?searchId={$listing_search.id}">[[Save Search]]</a></div>
			{/if}
		</div>
		<div class="col-md-6">
			<div class="search-result-bar-tab-item">
				<span class="badge"><span class="savedSearchesCount counter">{$savedSearchesCount}</span></span>
				<a href="{page_path id='user_saved_searches'}">[[View Saved Searches]]</a>
			</div>
			<div class="search-result-bar-tab-item">
				<span class="badge"><span class="savedListingsCount counter">{$savedListingsCount}</span></span>
				<a href="{page_path id='user_saved_listings'}">[[View Saved Ads]]</a>
			</div>
			<div class="search-result-bar-tab-item">
				<span class="badge"><span class="listingsCountInComparison counter">{$listingsCountInComparison}</span></span>
				<a onclick='if (listingsInComparisonCounter >= 2) javascript:window.open(this.href, "_blank"); else alert("[[Please add 2 or more listings for comparison.:raw]]"); return false;' href="{page_path id='compared_listings'}">[[Compare Selected Listings]]</a>
			</div>
		</div>
	</div>
</div>

{include file="miscellaneous^dialog_window.tpl"}
<script type="text/javascript">
	savedListingsCounter = {$savedListingsCount};
	savedSearchesCounter = {$savedSearchesCount};
	listingsInComparisonCounter = {$listingsCountInComparison};
	$(function () {
		if (listingsInComparisonCounter == 0)
		{
			$(".listingsInComparisonLink").hide();
		}
		else if (listingsInComparisonCounter > 0)
		{
			$(".listingsInComparisonLink").show();
		}

		if (savedListingsCounter == 0) {
			$(".savedListingsLink").hide();
		}
		else if (savedListingsCounter > 0)
		{
			$(".savedListingsLink").show();
		}

		if (savedSearchesCounter == 0) {
			$(".savedSearchesLink").hide();
		}
		else if (savedSearchesCounter > 0)
		{
			$(".savedSearchesLink").show();
		}
	})
</script>
