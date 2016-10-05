{require component="jquery" file="jquery.js"}
{require component="twitter-bootstrap" file="css/bootstrap.min.css"}
{require component="twitter-bootstrap" file="js/bootstrap.min.js"}

{include file="miscellaneous^bootstrap_button_noconflict.tpl"}

<header>
	<div class="container">
		<nav class="navbar navbar-custom">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="dot-bar"></span>
					<span class="dot-bar"></span>
					<span class="dot-bar"></span>
				</button>
				<a class="navbar-brand" href="{$GLOBALS.site_url}">
					<img src="{url file='main^img/ilister-prod-logo.png'}" alt="Main Logo">
				</a>
			</div>

			<div class="collapse navbar-collapse quest-menu" id="main-navbar-collapse">
				{module name="menu" function="top_menu"}
				{module name="users" function="user_menu_block"}
			</div>
		</nav>
	</div>
</header>

<div class="container">
	<div class="globalErrorWrapper">
		{extension_point name='modules\main\apps\FrontEnd\IGlobalErrorDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
	</div>
</div>
