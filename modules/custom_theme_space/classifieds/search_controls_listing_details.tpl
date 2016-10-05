<div class="row">
	<nav class="genesis">
		<div class="wrap">
			<ul class="genesis-nav-menu">
				{include file="category_templates/display/listing_details_search_controls.tpl" listing=$listing}
				{if $GLOBALS.current_base_uri != '/search-results/' && $listing_search.id}
					<li>
						<a href="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}">
							[[Back to Results]]
						</a>
					</li>
				{/if}
				{if $listing_search.search_form_uri}
					<li>
						<a href="{$GLOBALS.site_url}{$listing_search.search_form_uri}?action=restore&amp;searchId={$listing_search.id}">
							[[Modify Search]]
						</a>
					</li>
				{/if}
				<li>
					<a href="{page_path id='search'}">[[New Search]]</a>
				</li>
				<li>
					<a onclick='return openDialogWindow("[[Save Search:raw]]", this.href, 420, true)' class="saveSearch" href="{page_path id='search_save'}?searchId={$listing_search.id}">[[Save Search]]</a>
				</li>
				<li>
					<a href="{page_path id='user_saved_searches'}">[[Saved Searches]]&nbsp;(<span class="savedSearchesCount counter">{$savedSearchesCount}</span>)</a>
				</li>
				<li>
					<a href="{page_path id='user_saved_listings'}">[[Saved Ads]]&nbsp;(<span class="savedListingsCount counter">{$savedListingsCount}</span>)</a>
				</li>
				<li>
					<a onclick='if (listingsInComparisonCounter >= 2) javascript:window.open(this.href, "_blank"); else alert("[[Please add 2 or more listings for comparison.:raw]]"); return false;' href="{page_path id='compared_listings'}">[[Compare List]]&nbsp;(<span class="listingsCountInComparison counter">{$listingsCountInComparison}</span>)</a>
				</li>
			</ul>
		</div>
	</nav>
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
