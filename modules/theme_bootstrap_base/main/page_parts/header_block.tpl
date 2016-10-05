{require component="jquery" file="jquery.js"}
{require component="twitter-bootstrap" file="css/bootstrap.min.css"}
{require component="twitter-bootstrap" file="js/bootstrap.min.js"}
{include file="miscellaneous^bootstrap_button_noconflict.tpl"}

<header>
	<nav class="navbar navbar-inverse colorize-menu" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-menu">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand colorize-menu" href="{$GLOBALS.site_url}"><span class="glyphicon glyphicon-home"></span></a>
				{if $GLOBALS.current_user.logged_in}
					{extension_point name="modules\menu\apps\FrontEnd\ITopMenuItem" wrapperStartTag="<span class=\"col-xs-offset-2 hidden-sm hidden-md hidden-lg basketTopMenuItemMobile\">" wrapperEndTag="</span>"}
				{/if}
			</div>

			<div class="collapse navbar-collapse" id="main-menu">
				{module name="menu" function="top_menu"}
				{module name="users" function="user_menu_block"}
			</div>
		</div>
	</nav>

	<div class="container logo-container">
		<div class="row">
			<div class="col-sm-9">
				{IncludeMainLogo}
                <div class="clearfix"></div>
            </div>
			<div class="col-md-3 logo-right">
				<div class="pull-right widget-display">
					{extension_point name='modules\main\apps\FrontEnd\IWidgetDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
				</div>
				<div class="pull-right lang-selector">
					{include file="miscellaneous^language_selector.tpl"}
				</div>
                <div class="clearfix"></div>
			</div>
		</div>
	</div>
</header>

<div class="container">
	<div class="globalErrorWrapper">
		{extension_point name='modules\main\apps\FrontEnd\IGlobalErrorDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
	</div>
</div>
