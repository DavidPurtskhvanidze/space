{capture name="sortPageSelectorContent"}
    <div class="row">
        <div class="col-sm-6 sortMenu">
            {if !empty($REQUEST.sorting_field_selector_template)}
                {$sorting_field_selector_template = $REQUEST.sorting_field_selector_template}
            {else}
                {$sorting_field_selector_template = "classifieds^sorting_field_selector.tpl"}
            {/if}
            {include file=$sorting_field_selector_template listing_search=$listing_search url=$restore_url}
        </div>
        <div class="col-sm-6">
            {include file="classifieds^objects_per_page_selector.tpl" listing_search=$listing_search url=$restore_url}
        </div>
    </div>
{/capture}
{strip}
    <div class="searchResultsHeader">
        <div class="row">
            <div class="col-xs-6 col-md-3 found-count vcenter">
                {assign var="listings_number" value=$listing_search.total_found}
                <span class="h5">[[$listings_number found]]</span>
            </div>
            <div class="col-md-6 hidden-xs hidden-sm vcenter">
                {$smarty.capture.sortPageSelectorContent}
            </div>
            <div class="col-xs-6 col-md-3 text-right vcenter">
                <ul class="list-inline view-options">
                    {foreach from=$resultViewTypeControls item=resultViewTypeControl}
                        <li>{$resultViewTypeControl}</li>
                    {/foreach}
                </ul>
            </div>
        </div>
        <div class="hidden-md hidden-lg">
            <hr/>
            {$smarty.capture.sortPageSelectorContent}
        </div>
    </div>
{/strip}
