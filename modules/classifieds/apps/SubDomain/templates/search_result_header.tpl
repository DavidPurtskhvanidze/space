<div class="listingSearchResultHeader">
	<ul class="serachResultViewTypeSwitch head-item">
		{foreach from=$resultViewTypeControls item=resultViewTypeControl}
			<li>{$resultViewTypeControl}</li>
		{/foreach}
	</ul>
	<div class="numberOfObjectsFoundInfo head-item">
		{assign var="listings_number" value=$listing_search.total_found}
		[[$listings_number found]]
	</div>
	<ul class="searchControls multilevelMenu head-item">
		<li class="sortBySelector">
			{if !empty($REQUEST.sorting_field_selector_template)}
				{$sorting_field_selector_template = $REQUEST.sorting_field_selector_template}
			{else}
				{$sorting_field_selector_template = "classifieds^sorting_field_selector.tpl"}
			{/if}
			{include file=$sorting_field_selector_template listing_search=$listing_search url=$restore_url}
		</li>
		<li class="objectsPerPageSelector">
			{include file="classifieds^objects_per_page_selector.tpl" listing_search=$listing_search url=$restore_url}
		</li>
	</ul>
	{include file="miscellaneous^multilevelmenu_js.tpl"}
	{require component="jquery" file="jquery.js"}
	{require component="twitter-bootstrap" file="bootstrap.min.js"}
</div>
