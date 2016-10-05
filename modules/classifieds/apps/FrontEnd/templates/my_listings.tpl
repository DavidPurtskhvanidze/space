{extension_point name='modules\main\apps\FrontEnd\IMyListingsAdditionRenderer'}
<div class="myListingsPage">
	<div class="searchForm">
		<h1 class="active" title="[[Click to hide the search form:raw]]">[[My Listings]]</h1>
		{module name="classifieds" function="search_form" form_template="my_listings_form.tpl"}
	</div>
	{include file="miscellaneous^toggle_search_form_js.tpl"}
	{display_error_messages}
	{display_success_messages}
	{display_warning_messages}
	{assign var="listings_number" value=$listing_search.total_found}
	{if $listing_search.total_found == 0}
		{if empty($search_criteria)}
            {if $REQUEST.userTotalListingNumber == 0}
                {assign var="url" value={page_path id='listing_add'}}
                <p>[[You have no listings now. Click <a href="$url">here</a> to add a new listing.]]</p>
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
				<div class="massActionControls">
					<input type="checkbox" class="checkAll" />
					{strip}
					<ul>
						<li>
							<input type="submit" class="deactivateIcon" name="action_deactivate" value="Deactivate" title="[[Deactivate:raw]]" onclick="return confirm('[[Are you sure?:raw]]')">
						</li>
						<li>
							<input type="submit" class="deleteIcon" name="action_delete" value="Delete" title="[[Delete:raw]]" onclick="return confirm('[[Are you sure?:raw]]')">
						</li>
					</ul>
					{/strip}
				</div>
				{capture assign="restore_url"}
					{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}
				{/capture}
				<div class="numberOfObjectsFoundInfo">
					{assign var="listings_number" value=$listing_search.total_found}
					[[$listings_number listings found]]
				</div>

				<ul class="searchControls multilevelMenu">
					<li class="sortBySelector">
						{if !empty($REQUEST.sorting_field_selector_template)}
							{$sorting_field_selector_template = $REQUEST.sorting_field_selector_template}
						{else}
							{$sorting_field_selector_template = "sorting_field_selector.tpl"}
						{/if}
						{include file=$sorting_field_selector_template listing_search=$listing_search url=$restore_url}
					</li>
					<li class="objectsPerPageSelector">
						{include file="objects_per_page_selector.tpl" listing_search=$listing_search url=$restore_url}
					</li>
				</ul>
				{include file="miscellaneous^multilevelmenu_js.tpl"}
			</div>
			<div class="searchResults mainContentBlock">
				{foreach from=$listings item=listing}
                    <a name="listing{$listing->getId()}"></a>
                    <div class="itemsSelectorAndSearchResultItemWrapper{if $listing@last} last{/if}">
						{if $REQUEST.featureActivatedForListing == $listing->getId()}
							<div class="featureActivatedNotification">
								{capture assign="featureName"}[[{$REQUEST.featureActivated}]]{/capture}
								<p>
									[[Feature "$featureName" has been activated for this listing]]
								</p>
								<small>([[click on this box to dismiss]])</small>
							</div>
						{/if}
						{if $REQUEST.listingActivated == $listing->getId()}
							<div class="listingActivatedNotification">
								<p>
									{if $listing->isActive()}
										[[This listing has been activated]]
									{else}
										{assign var=message value=$listing->getModerationStatus()}
										[[Your listing in status $message. It will be activated after approving by administrator.]]
									{/if}
								</p>
								<small>([[click on this box to dismiss]])</small>
							</div>
						{/if}
						{if $REQUEST.listingDeactivated == $listing->getId()}
							<div class="listingDeactivatedNotification">
								<p>
									[[This listing has been deactivated]]
								</p>
								<small>([[click on this box to dismiss]])</small>
							</div>
						{/if}
						<div class="itemsSelector">
							<input type="checkbox" name="listings[{$listing->getId()}]" value="1" />
						</div>
						<div class="searchResultItemWrapper">
							{display_listing listing=$listing listingControlsTemplate="my_listing_controls.tpl" listing_search=$listing_search features=$REQUEST.features}
						</div>
					</div>
				{/foreach}
			</div>
		</form>
		{include file="page_selector.tpl" current_page=$listing_search.current_page pages_number=$listing_search.pages_number url=$restore_url}

		{require component="jquery" file="jquery.js"}
		<script type="text/javascript">

			$(document).ready(function () {
				$(".featureActivatedNotification").click(function () {
					$(this).remove();
				});
				$(".listingActivatedNotification").click(function () {
					$(this).remove();
				});
				$(".listingDeactivatedNotification").click(function () {
					$(this).remove();
				});

				$(".massActionControls ul").hide();

				$(".massActionControls .checkAll").change(function () {
					$(".massActionForm input[type='checkbox']").prop("checked", $(this).prop("checked"));
				});

				$(".massActionForm input[type='checkbox']").change(function () {
					var atLeastOneCheckboxChecked = $(".massActionForm input[type='checkbox']:checked").length > 0;
					$(".massActionControls ul").toggle(atLeastOneCheckboxChecked);
				});

			});

		</script>
	{/if}
</div>
{include file="miscellaneous^dialog_window.tpl"}
