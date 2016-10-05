{module name="classifieds" ignoreFieldIds=$GLOBALS.parameters.browsingFieldIds function="refine_search" search_id=$GLOBALS.parameters.searchID}
{include file="miscellaneous^language_selector.tpl"}
{extension_point name='modules\main\apps\FrontEnd\IWidgetDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
<ul class="leftUserMenu menu accordion {$main_content_module} {$main_content_function} {if $GLOBALS.current_user.logged_in}userLoggedIn{/if}">
	<li class="tab opened">
		<a href="#">
			<i class="iconMenuTriangle white"></i>
			{if $GLOBALS.current_user.logged_in}
				{if !empty($GLOBALS.current_user.ProfilePicture.file_url)}
					<img src="{$GLOBALS.current_user.ProfilePicture.file_url}" alt="{$GLOBALS.current_user.user_name}" />
				{else}
					<img src="{url file='main^icons/user.png'}" alt="{$GLOBALS.current_user.user_name}" />
				{/if}
				<span>{$GLOBALS.current_user.user_name}</span>
			{else}
				[[Menu]]
			{/if}
		</a>
	</li>
	<li class="content">
		{module name="menu" function="left_menu"}
		{module name="users" function="user_menu"}
	</li>
</ul>
{include file="menu^menu_accordion_js.tpl"}
{if !$GLOBALS.current_user.logged_in && !$GLOBALS.loginFormWasAlreadyDisplayed}
	{module name="users" function="login" HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
{/if}
{module name="poll" function="poll_form"}
{module name="publications" function="show_publications" passed_parameters_via_uri="" category_id="News" number_of_publications="2" publications_template="print_news_box_articles.tpl"}
