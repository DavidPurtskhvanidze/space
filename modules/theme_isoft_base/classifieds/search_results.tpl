<div class="twoColumnLayout">
	<div class="leftHelperColumnLayout helperColumnBlock">
		{module name="classifieds" ignoreFieldIds=$GLOBALS.parameters.browsingFieldIds function="refine_search" search_id=$listing_search.id}
		{include file="menu^menu_accordion_js.tpl"}
	</div>
	<div class="leftHelperColumnLayout mainContentBlock">
		{include file="classifieds^search_results_content.tpl"}
	</div>
</div>
