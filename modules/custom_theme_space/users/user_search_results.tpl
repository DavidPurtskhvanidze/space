{display_success_messages}
<section class="user-search-results-block">
	<div class="row">
		{if $search.total_found > 0}
			<div class="users-box">
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
</section>

