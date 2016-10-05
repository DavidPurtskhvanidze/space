{include file="miscellaneous^toggle_search_form_js.tpl"}
{assign var="listings_number" value=$listing_search.total_found}
{if $listing_search.total_found == 0}
	{if empty($search_criteria)}
		{if $REQUEST.userTotalListingNumber == 0}
			{assign var="url" value={page_path id='listing_add'}}
			<p>[[You have no listings now. Click <a href="$url">here</a> to add a new listing.]]</p>
			<script type="text/javascript">
				$(document).ready(function(){
					$('a').addClass('respondInMainWindow');
				})
			</script>
		{else}
			<p>[[There are no listings available that match your search criteria. Please try to broaden your search
				criteria.]]</p>
		{/if}
	{else}
		<p class="error">[[No listings were found.]]</p>
	{/if}
{else}
	<form method="get" action="{$GLOBALS.site_url}{$GLOBALS.current_page_uri}" class="massActionForm">
		<div class="searchResultItemsControls listingSearchResultHeader">
			<input type="hidden" name="searchId" value="{$listing_search.id}" />
			{capture assign="restore_url"}
				{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&searchId={$listing_search.id}&add_listing_session_id={$REQUEST.add_listing_session_id}
			{/capture}
			<div class="numberOfObjectsFoundInfo">
				{assign var="listings_number" value=$listing_search.total_found}
				[[$listings_number listings found]]
			</div>
		</div>
		<div class="searchResults mainContentBlock">
			{foreach from=$listings item=listing}
				<a name="listing{$listing->getId()}"></a>
				<div class="itemsSelectorAndSearchResultItemWrapper{if $listing@last} last{/if}">
					<div class="searchResultItemWrapper">
						{display_listing listing=$listing listingTemplate="category_templates/display/search_result_item_in_modal.tpl" listingControlsTemplate="category_templates/input/fill/listing_controls.tpl" listing_search=$listing_search}
					</div>
				</div>
			{/foreach}
		</div>
	</form>
	{include file="page_selector.tpl" current_page=$listing_search.current_page pages_number=$listing_search.pages_number url=$restore_url}
{/if}
{include file="miscellaneous^dialog_window.tpl"}
