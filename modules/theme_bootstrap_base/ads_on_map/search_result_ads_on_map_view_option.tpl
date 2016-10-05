{if $selected_view_type == $view_option_id}
	<span class="glyphicon glyphicon-map-marker"></span> [[Map]]
{else}
	<a href="{page_path id='search_map'}?action=restore&amp;searchId={$search_id}&amp;result_view_type={$view_option_id}">
		<span class="glyphicon glyphicon-map-marker"></span> [[Map]]
	</a>
{/if}
