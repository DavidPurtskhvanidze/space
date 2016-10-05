<div class="listingSearchResultsPage gallery-view">

    {if $listing_search.total_found > 0}
        <div class="row">
            {foreach from=$listings item=listing name=listings}
                <div class="col-sm-3 col-xs-6 col-xxs-12">
                    {display_listing listing=$listing listingControlsTemplate="search_result_listing_controls.tpl" listing_search=$listing_search listingTemplate="category_templates/display/gallery_search_result_item.tpl"}
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
</div>
<script type="text/javascript">
    var listingsInComparisonCounter = {$listingsCountInComparison};
</script>