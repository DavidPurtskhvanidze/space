{if $selected_view_type == $view_option_id}
	<i class="icon searchResultsList"></i>
	<span class="link searchResultsMap">[[List]]</span>
{else}
	<a class="icon searchResultsList" href="{$GLOBALS.site_url}{$search_result_uri}?action=restore&amp;searchId={$search_id}&amp;get_search_form_uri=1&amp;result_view_type={$view_option_id}"></a>
	<a class="link searchResultsList" href="{$GLOBALS.site_url}{$search_result_uri}?action=restore&amp;searchId={$search_id}&amp;get_search_form_uri=1&amp;result_view_type={$view_option_id}">[[List]]</a>
{/if}
