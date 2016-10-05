{if $selected_view_type == $view_option_id}
	<i class="fa fa-th-large fa-2x" title="[[Gallery]]"></i>
{else}
	<a class="hidden-xs" href="{$GLOBALS.site_url}{$search_result_uri}?action=restore&amp;searchId={$search_id}&amp;get_search_form_uri=1&amp;result_view_type={$view_option_id}" title="[[Gallery]]">
        <i class="fa fa-th-large fa-2x"></i>
	</a>

    <i class="fa fa-th-large fa-2x visible-xs" title="[[Gallery]]"></i>
{/if}
