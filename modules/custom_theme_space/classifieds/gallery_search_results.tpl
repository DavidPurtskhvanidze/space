{set_global_parameter key='searchID' value= $listing_search.id}
{extension_point name='modules\main\apps\FrontEnd\ISearchResultsAdditionDisplayer' listingSearchID = $listing_search.id}
{capture assign="restore_url"}
	{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}
{/capture}

<section>
	<h3 class="title">
		[[Search Results]]
	</h3>
</section>

{module name="classifieds" ignoreFieldIds=$GLOBALS.parameters.browsingFieldIds function="refine_search" search_id=$listing_search.id}
{include file="menu^menu_accordion_js.tpl"}

{include file="search_result_header.tpl" restore_url=$restore_url}

{include file="classifieds^gallery_search_results_content.tpl"}

