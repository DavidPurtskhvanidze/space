{if $selected_view_type == $view_option_id}
	<i class="fa fa-map-marker fa-2x" title="[[Map]]"></i>
{else}
	<a href="{page_path id='search_map'}?action=restore&amp;searchId={$search_id}&amp;result_view_type={$view_option_id}" title="[[Map]]">
        <i class="fa fa-map-marker fa-2x"></i>
	</a>
{/if}
