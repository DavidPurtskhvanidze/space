<div class="row">
	<div class="col-md-9">
		{include file="classifieds^search_results_content.tpl"}
	</div>
	<div class="col-md-3">
		{module name="classifieds" ignoreFieldIds=$GLOBALS.parameters.browsingFieldIds function="refine_search" search_id=$listing_search.id}
		{include file="menu^menu_accordion_js.tpl"}
	</div>
</div>
