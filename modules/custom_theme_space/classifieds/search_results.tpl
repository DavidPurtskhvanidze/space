{capture assign="restore_url"}
	{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}
{/capture}
<div class="container">
	<section>
		<h3 class="title">
			[[Search Results]]
		</h3>
	</section>

	{include file="search_result_header.tpl" restore_url=$restore_url}

	{include file="classifieds^search_results_content.tpl"}
</div>

