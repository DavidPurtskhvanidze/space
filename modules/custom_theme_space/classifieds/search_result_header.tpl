<div class="searchResultsHeader">
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    {if !empty($REQUEST.sorting_field_selector_template)}
                        {$sorting_field_selector_template = $REQUEST.sorting_field_selector_template}
                    {else}
                        {$sorting_field_selector_template = "classifieds^sorting_field_selector.tpl"}
                    {/if}
                    {include file=$sorting_field_selector_template listing_search=$listing_search url=$restore_url}
                </div>
                <div class="col-md-6">
                    {include file="classifieds^objects_per_page_selector.tpl" listing_search=$listing_search url=$restore_url}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <ul class="list-inline text-right">
                <li>
                    {assign var="listings_number" value=$listing_search.total_found}
                    [[$listings_number found]]
                </li>
                {foreach from=$resultViewTypeControls item=resultViewTypeControl}
                    <li>{$resultViewTypeControl}</li>
                {/foreach}

            </ul>
        </div>
    </div>
</div>
<hr>