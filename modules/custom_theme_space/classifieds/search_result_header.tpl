<div class="searchResultsHeader">
    {module name="classifieds" ignoreFieldIds=$GLOBALS.parameters.browsingFieldIds function="refine_search" search_id=$listing_search.id}
    <div class="searchResultsHeader-item">
        {if !empty($REQUEST.sorting_field_selector_template)}
            {$sorting_field_selector_template = $REQUEST.sorting_field_selector_template}
        {else}
            {$sorting_field_selector_template = "classifieds^sorting_field_selector.tpl"}
        {/if}
    </div>
    <div class="searchResultsHeader-item">
        {include file=$sorting_field_selector_template listing_search=$listing_search url=$restore_url}
    </div>
    <div class="searchResultsHeader-item">
        {include file="classifieds^objects_per_page_selector.tpl" listing_search=$listing_search url=$restore_url}
    </div>
    <div class="searchResultsHeader-item">
        {assign var="listings_number" value=$listing_search.total_found}
        [[$listings_number found]]
    </div>
    {foreach from=$resultViewTypeControls item=resultViewTypeControl}
        <div class="searchResultsHeader-item">{$resultViewTypeControl}</div>
    {/foreach}
</div>
<hr>