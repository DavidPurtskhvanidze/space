{display_success_messages}
<div class="userSearchResultsPage">
	{if $search.total_found > 0}
		<ul class="nav nav-tabs" role="tablist">
			<li class="dropdown pull-right">
				{include file="objects_per_page_selector.tpl" listing_search=$listing_search url='?action=restore'}
			</li>
		</ul>
		<div class="searchResults">
			{foreach from=$users item=user}
				{include file="user_search_result_item.tpl"}
			{/foreach}
			{include file="miscellaneous^dialog_window.tpl"}
		</div>
		{include file="page_selector.tpl" current_page=$search.current_page pages_number=$search.pages_number url='?action=restore'}
		{include file="miscellaneous^multilevelmenu_js.tpl"}
	{else}
		<p>[[There are no user profiles that match your search criteria.]]</p>
	{/if}
</div>
