{if $selected_view_type == $view_option_id}
	<img src="{url file="main^img/gallery.png"}" alt="">
{else}
	<a href="{$GLOBALS.site_url}{$search_result_uri}?action=restore&amp;searchId={$search_id}&amp;get_search_form_uri=1&amp;result_view_type={$view_option_id}">
		<img src="{url file="main^img/gallery_blue.png"}" alt="">
	</a>
{/if}
