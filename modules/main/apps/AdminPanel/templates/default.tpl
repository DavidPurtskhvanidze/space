<!DOCTYPE html>
<html lang="{i18n->getCurrentLanguage}">
<head>
<meta name="keywords" content="{$KEYWORDS}" />
<meta name="description" content="{$DESCRIPTION}" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<title>{$GLOBALS.settings.product_name} Admin Panel {if $TITLE ne ""} :: {$TITLE}{/if}</title>
<!-- #EXTERNAL_COMPONENTS_PLACEHOLDER# -->
{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="twitter-bootstrap" file="js/bootstrap.min.js"}
{require component="jquery-cookie" file="jquery.cookie.js"}
{require component="js" file="error_tooltips.js"}

{require component="twitter-bootstrap" file="css/bootstrap.min.css"}
<script type="text/javascript" src="{url file="ace/jquery.maskedinput.min.js"}"></script>
<script type="text/javascript" src="{url file="ace/ace-extra.min.js"}"></script>
<script type="text/javascript" src="{url file="ace/ace-elements.min.js"}"></script>
<script type="text/javascript" src="{url file="ace/ace.min.js"}"></script>
<script type="text/javascript" src="{url file="ace/bootstrap-datepicker.min.js"}"></script>
<script type="text/javascript" src="{url file="adminLib.js"}"></script>
<script type="text/javascript" src="{url file="menuPosition.js"}"></script>
<link rel="stylesheet" type="text/css" href="{url file="ace/datepicker.css"}" media="all"/>
<link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=cyrillic-ext,vietnamese,latin-ext,greek-ext' rel='stylesheet' type='text/css'>

{includeDesignFiles}

</head>
<body class="{foreach from=$GLOBALS.themeInheritanceBranch item=theme} {$theme->getName()} {/foreach}">
	{include file="miscellaneous^check_js_cookies.tpl"}
	{module name='main' function='display_body_top_templates'}

	<div class="navbar navbar-default" id="navbar">
		<script type="text/javascript">
			try {
				ace.settings.check('navbar', 'fixed')
			} catch (e) {
			}
		</script>
		<div id="navbar-container" class="navbar-container">
			<div class="navbar-header navbar-left">
				<a href="{$GLOBALS.site_url}" class="navbar-brand">
					<h3 class="white">[[Admin Panel]]
						<small class="white">[[Version]] {$GLOBALS.settings.product_version}</small>
					</h3>
				</a><!-- /.brand -->
			</div>
			<!-- /.navbar-header -->
			<div class="collapse in navbar-collapse navbar-right">
				{include file="miscellaneous^lang_selector.tpl"}
			</div>

			<div class="navbar-header pull-right" role="navigation">
				<ul class="nav ace-nav">
					<li class="purple">
						<a href="{$GLOBALS.site_url}/../" target="_blank">[[Front-End]]</a>
					</li>
					<li class="purple">
						<a target="_blank" href="{$GLOBALS.site_url}/../doc/UserManual/">[[User Manual]]</a>
					</li>
					<li class="light-blue">
						<a data-toggle="dropdown" href="#" class="dropdown-toggle">
							<i class="icon-user"></i>
						</a>
						<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
							<li>
								<a href="{page_path module='main' function='admin_logout'}">
									<i class="icon-off"></i>
									Logout
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="main-container" id="main-container">
		<script type="text/javascript">
			try {
				ace.settings.check('main-container', 'fixed')
			} catch (e) {
			}
		</script>
		<div class="main-container-inner">
			<a class="menu-toggler" id="menu-toggler" href="#">
				<span class="menu-text"></span>
			</a>

			{include file="main^left_block.tpl"}

			<div class="main-content">
				{$MAIN_CONTENT}
			</div>

		</div>
		<div class="clear"></div>
		<div id="footer">
			<div class="center">
				&copy; 2006&ndash;2016 <a href="http://www.worksforweb.com/">WorksForWeb</a>
			</div>
		</div>
	</div>

	{module name='main' function='display_body_bottom_templates'}
</body>
</html>
