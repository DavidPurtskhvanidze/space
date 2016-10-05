<div class="searchResultsHeader">
	<div class="row">
        <div class="col-lg-5">
            <ul class="list-inline">
                {foreach from=$resultViewTypeControls item=resultViewTypeControl}
                    <li>{$resultViewTypeControl}</li>
                {/foreach}
                <li>
                    {assign var="listings_number" value=$listing_search.total_found}
                    [[$listings_number found]]
                </li>
            </ul>
        </div>
        <div class="col-lg-7">
            <ul class="list-inline">
                <li>
                    <div class="row">
                        <div class="col-lg-12 sortMenu">
                            {if !empty($REQUEST.sorting_field_selector_template)}
                                {$sorting_field_selector_template = $REQUEST.sorting_field_selector_template}
                            {else}
                                {$sorting_field_selector_template = "classifieds^sorting_field_selector.tpl"}
                            {/if}
                            {include file=$sorting_field_selector_template listing_search=$listing_search url=$restore_url}
                        </div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="col-lg-12">
                            {include file="classifieds^objects_per_page_selector.tpl" listing_search=$listing_search url=$restore_url}
                        </div>
                    </div>
                </li>
            </ul>
        </div>
	</div>
</div>
