<div class="searchControls">
	<ul class="actionList">
		{if $GLOBALS.current_base_uri != '/search-results/' && $listing_search.id}
			<li class="actionLink BackToResults"><a href="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}">[[Back to Results]]</a></li>
		{/if}
		{if $listing_search.search_form_uri}
			<li><a href="{$GLOBALS.site_url}{$listing_search.search_form_uri}?action=restore&amp;searchId={$listing_search.id}">[[Modify Search]]</a></li>
		{/if}
		<li><a href="{page_path id='search'}">[[New Search]]</a></li>
		{if $listing_search.id}
			<li><a onclick='return openDialogWindow("[[Save Search:raw]]", this.href, 420, true)' class="saveSearch" href="{page_path id='search_save'}?searchId={$listing_search.id}">[[Save Search]]</a></li>
		{/if}
		<li class="savedSearchesLink">
			<a href="{page_path id='user_saved_searches'}">[[View Saved Searches]]</a>
			<span class="savedSearchesCount counter">{$savedSearchesCount}</span>
		</li>
		<li class="savedListingsLink">
			<a href="{page_path id='user_saved_listings'}">[[View Saved Ads]]</a>
			<span class="savedListingsCount counter">{$savedListingsCount}</span>
		</li>
		<li class="listingsInComparisonLink">
			<a onclick='if (listingsInComparisonCounter >= 2) javascript:window.open(this.href, "_blank"); else alert("[[Please add 2 or more listings for comparison.:raw]]"); return false;' href="{page_path id='compared_listings'}">[[Compare Selected Listings]]</a>
			<span class="listingsCountInComparison counter">{$listingsCountInComparison}</span>
		</li>
	</ul>
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
