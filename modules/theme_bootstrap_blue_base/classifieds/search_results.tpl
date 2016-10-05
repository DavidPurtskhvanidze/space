<div class="white-bg">
    <div class="container">
        <h2 class="h2 page-title">[[Search results]]</h2>
        {module name="classifieds" ignoreFieldIds=$GLOBALS.parameters.browsingFieldIds function="refine_search" search_id=$listing_search.id}
        {include file="menu^menu_accordion_js.tpl"}
        <div class="space-20"></div>
    </div>

    <div class="search-result-container">
        <div class="container">
            {include file="classifieds^search_results_content.tpl"}
        </div>
    </div>
</div>

