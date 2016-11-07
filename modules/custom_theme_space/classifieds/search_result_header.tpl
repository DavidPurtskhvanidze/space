<div class="search-results-header">
    <div class="row">
        <div class="col-sm-7 col-xs-12 search-results-header-ls">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="search-results-header-item">
                        {include file="classifieds^objects_per_page_selector.tpl" listing_search=$listing_search url=$restore_url}
                    </div>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-6">
                    <div class="search-results-header-item">
                        {if !empty($REQUEST.sorting_field_selector_template)}
                            {$sorting_field_selector_template = $REQUEST.sorting_field_selector_template}
                        {else}
                            {$sorting_field_selector_template = "classifieds^sorting_field_selector.tpl"}
                        {/if}
                        {include file=$sorting_field_selector_template listing_search=$listing_search url=$restore_url}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-5 hidden-xs search-results-header-rs">
            <div class="search-results-header-item">
                {assign var="listings_number" value=$listing_search.total_found}
                [[$listings_number found]]
            </div>
            {foreach from=$resultViewTypeControls item=resultViewTypeControl}
                <div class="search-results-header-item">{$resultViewTypeControl}</div>
            {/foreach}
        </div>
    </div>
</div>
