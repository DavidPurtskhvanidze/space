{if !$GLOBALS.current_user.logged_in}
	<ul class="nav navbar-nav navbar-right">
		<li><a class="colorize-menu colorize-menu-text" href='{page_path id='user_registration'}'>[[Register]]</a></li>
		<li class="dropdown">
			<a class="colorize-menu colorize-menu-text" href="#" class="dropdown-toggle" data-toggle="dropdown">[[Login]] <b class="caret"></b></a>
			<div class="dropdown-menu">
				{module name="users" function="login" template="login_navbar.tpl" HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
			</div>
		</li>
	</ul>
{else}
	{extension_point name="modules\menu\apps\FrontEnd\ITopMenuItem" wrapperStartTag="<span class=\"hidden-xs basketTopMenuItem\">" wrapperEndTag="</span>"}
	<ul class="nav navbar-nav navbar-right">
		<li class="dropdown">
			<a href="#" class="dropdown-toggle colorize-menu colorize-menu-text" data-toggle="dropdown">
				<span class="glyphicon glyphicon-user"></span> {$GLOBALS.current_user.user_name} <span class="caret"></span>
			</a>
			{module name="users" function="user_menu"}
		</li>
	</ul>
{/if}
