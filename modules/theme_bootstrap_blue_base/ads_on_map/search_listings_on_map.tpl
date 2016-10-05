<script>
	var default_location = "{$map_default_location}";
	function toggleListingActionUpdate(checkbox, classSelector, base_url, additional_url) {
		/**
		 * There are two blocks for the listing that appears in the info window - first is hidden and second is in info window.
		 * The content for the info window is taken from hidden block. That is why when visitor checks or unchecks "saveListing" or "compareListing"
		 * we need to update the corresponding checkbox "checked" attribute in the hidden block too.
		 */

		// 'checked' attribute  =  'defaultChecked' property
		$(classSelector).prop('defaultChecked', $(checkbox).prop('checked'));

		toggleListingAction(checkbox, base_url, additional_url);
	}
</script>
<div class="searchOnMap listingSearchResultsPage">
    {if isset($backToAdvancedSearchURI)}
    <div class="bg-grey">
        <div class="container">
            <div class="space-20"></div>

                    <div class="linkBackToAdvancedSearch">
                        <span><a href="{$GLOBALS.site_url}{$backToAdvancedSearchURI}">[[Back to search form]]</a></span>
                    </div>
                    {include file="miscellaneous^multilevelmenu_js.tpl"}
            <div class="space-20"></div>
         </div>
    </div>
    {/if}

{module name="ads_on_map" function="search_results" default_listings_per_page="20" results_template="search_results.tpl" }
	<div class="mapWithSearchForm">
		{include file="ads_on_map^google_map.tpl"}
		{module name="classifieds" function="search_form" form_template="ads_on_map^search_form.tpl"}
	</div>
</div>
