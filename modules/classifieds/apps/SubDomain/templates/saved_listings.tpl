<div class="savedListingsPage">
	<h1>[[Saved Listings]]</h1>
	{if $listing_search.total_found == 0}
		<p class="error">[[You have no saved listings now.]]</p>
	{else}
		{assign var="listings_number" value=$listing_search.total_found}

		<form method="post" action="" class="massActionForm">
			<div class="searchResultItemsControls listingSearchResultHeader">
                {CSRF_token}
				<input type="hidden" name="searchId" value="{$listing_search.id}" />
				{include file="miscellaneous^toggle_search_form_js.tpl"}
				<div class="massActionControls">
					<input type="checkbox" class="checkAll" />
					<ul>
						<li>
							<a class="delete" href="{$GLOBALS.site_url}{$GLOBALS.current_page_uri}?action_delete=Delete" title="[[Delete:raw]]" class="itemControl delete">
								<img src="{url file='main^icons/search_results_header_bin.png'}" />
							</a>
						</li>
					</ul>
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
				{foreach from=$listings item=listing name=listings_block}
					<div class="itemsSelectorAndSearchResultItemWrapper{if $listing@last} last{/if}">
						<div class="itemsSelector">
							<input type="checkbox" name="listings[{$listing->getId()}]" value="1" />
						</div>
						<div class="searchResultItemWrapper">
							{display_listing listing=$listing listingControlsTemplate="saved_listing_controls.tpl" listing_search=$listing_search}
						</div>
					</div>
				{/foreach}
			</div>
			{include file="page_selector.tpl" current_page=$listing_search.current_page pages_number=$listing_search.pages_number url=$restore_url}
		</form>
		{require component="jquery" file="jquery.js"}
		<script type="text/javascript">
			$(document).ready(function () {
				$(".massActionControls ul").hide();
				
				$(".massActionControls a").click(function () {
					window.location.href = $(this).attr("href") + "&" + $(".massActionForm").serialize();
					return false;
				});

				$(".massActionControls .checkAll").change(function () {
					$(".massActionForm .itemsSelector input[type='checkbox']").prop("checked", $(this).prop("checked")).trigger('change');
				});

				$(".massActionForm .itemsSelector input[type='checkbox']").change(function () {
					var atLeastOneCheckboxChecked = $(".massActionForm .itemsSelector input[type='checkbox']:checked").length > 0;
					$(".massActionControls ul").toggle(atLeastOneCheckboxChecked);
				});
				$(".massActionForm .listingControls input[type='checkbox']:checked").each(function(){
					onCompleteActionComparison($(this));
				});
			});
		function onCompleteActionComparison($checkbox)
		{
			var listingId = $checkbox.val();
			if ($checkbox.prop('checked'))
			{
				$('.listingControls[data-listingId='+listingId+'] .compareListingsDelim').show();
				$('.listingControls[data-listingId='+listingId+'] .compareListingsLink').show();
			}
			else
			{
				$('.listingControls[data-listingId='+listingId+'] .compareListingsDelim').hide();
				$('.listingControls[data-listingId='+listingId+'] .compareListingsLink').hide();
			}
		}
		</script>
	{/if}
</div>
