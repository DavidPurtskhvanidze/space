<div class="searchResults">
	{if $listing_search.total_found > 0}
		{assign var="listings_number" value=$listing_search.total_found}
		<div class="numberOfObjectsFoundInfo">
			[[$listings_number found]].
		</div>
		{if !empty($REQUEST.sorting_field_selector_template)}
			{assign var="sorting_field_selector_template" value=$REQUEST.sorting_field_selector_template}
		{else}
			{assign var="sorting_field_selector_template" value="sorting_field_selector.tpl"}
		{/if}
		{include file=$sorting_field_selector_template}

		{foreach from=$listings item=listing name=listings}
			{display_listing listing=$listing listing_search=$listing_search}
		{/foreach}
		{include file="search_results_paging.tpl" current_page=$listing_search.current_page pages_number=$listing_search.pages_number}
	{else}
		<p>[[There are no listings available that match your search criteria.]]</p>	
	{/if}
	{if !is_null($listing_search.search_form_uri)}
	<div class="searchResultsLinks">
		<a href="{$GLOBALS.site_url}{$listing_search.search_form_uri}?action=restore&searchId={$listing_search.id}">[[Modify Search]]</a>
	</div>
	{/if}
</div>
