<div class="listingDetails">
	{module name='google_map' function='display_map' address=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State default_latitude=$listing.ZipCode.latitude default_longitude=$listing.ZipCode.longitude}
	{include file="classifieds^category_templates/display/subpages_links.tpl" currentPageId="map" listing=$listing}
	{include file="classifieds^category_templates/display/search_results_controls.tpl"}
</div>

