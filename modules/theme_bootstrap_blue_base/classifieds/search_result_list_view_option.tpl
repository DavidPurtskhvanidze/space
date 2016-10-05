{if $selected_view_type == $view_option_id}
	<i class="fa fa-bars fa-2x hidden-xs" title="[[List]]"></i>
{else}
	<a class="hidden-xs" href="{$GLOBALS.site_url}{$search_result_uri}?action=restore&amp;searchId={$search_id}&amp;get_search_form_uri=1&amp;result_view_type={$view_option_id}" title="[[List]]">
        <i class="fa fa-bars fa-2x"></i>
	</a>
{/if}
