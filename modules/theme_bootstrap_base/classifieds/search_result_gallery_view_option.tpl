{if $selected_view_type == $view_option_id}
	<span class="glyphicon glyphicon-th"></span> [[Gallery]]
{else}
	<a href="{$GLOBALS.site_url}{$search_result_uri}?action=restore&amp;searchId={$search_id}&amp;get_search_form_uri=1&amp;result_view_type={$view_option_id}">
		<span class="glyphicon glyphicon-th"></span> [[Gallery]]
	</a>
{/if}
