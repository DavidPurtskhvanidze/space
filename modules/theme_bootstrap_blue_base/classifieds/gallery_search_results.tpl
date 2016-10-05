<div class="white-bg">
    <div class="container">
        <h2 class="h2 page-title">[[Search results]]</h2>
        {module name="classifieds" ignoreFieldIds=$GLOBALS.parameters.browsingFieldIds function="refine_search" search_id=$listing_search.id}
        {include file="menu^menu_accordion_js.tpl"}
        <div class="space-20"></div>
    </div>

    <div class="search-result-container">
        <div class="container">
            {set_global_parameter key='searchID' value= $listing_search.id}
            {extension_point name='modules\main\apps\FrontEnd\ISearchResultsAdditionDisplayer' listingSearchID = $listing_search.id}
            <div class="listingSearchResultsPage searchResults gallery-view">
                {if $listing_search.total_found > 0}
                    {strip}
                        {capture assign="restore_url"}
                            {$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}
                        {/capture}
                    {/strip}

                    {include file="search_result_header.tpl" restore_url=$restore_url}

                    <div class="row">
                        {foreach from=$listings item=listing name=listings}
                            <div class="col-sm-4 col-md-3">
                                {display_listing listing=$listing listingControlsTemplate="search_result_listing_controls.tpl" listing_search=$listing_search listingTemplate="category_templates/display/gallery_search_result_item.tpl"}
                            </div>
                            {if $listing@iteration is div by 4}<div class="clearfix visible-md visible-lg"></div>{/if}
                            {if $listing@iteration is div by 3}<div class="clearfix visible-sm"></div>{/if}
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
            </div>
            <script type="text/javascript">
                var listingsInComparisonCounter = {$listingsCountInComparison};
            </script>
        </div>
    </div>
</div>

