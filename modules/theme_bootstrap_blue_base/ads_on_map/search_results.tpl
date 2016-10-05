<script type="text/javascript">
    var listing_locations = { };
</script>
{set_global_parameter key='searchID' value= $listing_search.id}
{extension_point name='modules\main\apps\FrontEnd\ISearchResultsAdditionDisplayer' listingSearchID = $listing_search.id}
<div class="SearchResults searchResults listings white-bg">
	{if $listing_search.total_found > 0}
		{capture assign="restore_url"}
			{page_path id='search_map'}?action=restore&amp;searchId={$listing_search.id}
		{/capture}
        <div class="container">
            <h2 class="h2 page-title">[[Search results]]</h2>
        </div>
        <div class="bg-grey">
            <div class="container">
                <div class="space-20"></div>
                {include file="classifieds^search_result_header.tpl" restore_url=$restore_url}
                <div class="ListingsSearchResults">
                    {foreach from=$listings item=listing name=listings}
                        {display_listing listing=$listing listingControlsTemplate="search_result_listing_controls.tpl" listing_search=$listing_search}
                    {/foreach}
                </div>
                {capture name=map_page_selector}
                    {include file="classifieds^page_selector.tpl" current_page=$listing_search.current_page pages_number=$listing_search.pages_number url=$restore_url}
                {/capture}
                {include file="miscellaneous^dialog_window.tpl"}
            </div>
        </div>

		{else}
        <div class="container">
            {include file="search_controls.tpl"}
            <p class="alert warning">[[There are no listings available that match your search criteria. Please try
                to broaden your search criteria.]]</p>
        </div>
	{/if}
</div>
<script type="text/javascript">
    var listingsInComparisonCounter = {$listingsCountInComparison};
</script>

