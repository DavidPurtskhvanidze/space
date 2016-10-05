<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{i18n->getCurrentLanguage}" lang="{i18n->getCurrentLanguage}">
<head>
	<title>{$GLOBALS.custom_settings.site_name} :: {$TITLE}</title>
	<meta name="keywords" content="{$KEYWORDS}" />
	<meta name="description" content="{$DESCRIPTION}" />
	<meta name="apple-touch-fullscreen" content="YES" />
	<meta name="HandheldFriendly" content="true" />
	<meta name="viewport" content="format-detection=no,initial-scale=1.0,maximum-scale=1.0,user-scalable=1,width=device-width" />
	{$META_TAGS}
	{includeDesignFiles}
	<!-- #EXTERNAL_COMPONENTS_PLACEHOLDER# -->
	{module name='main' function='display_html_header_tag_content'}
</head>
<body>
	<div class="wrap">
		<div class="main {if $GLOBALS.current_user.logged_in}long{else}short{/if}">
			{module name='main' function='display_before_header_templates'}
			<div class="header">
				<a href="{$GLOBALS.site_url}"><img class="logo" src="{url file='main^logo.png'}" /></a>
				{include file="miscellaneous^language_selector.tpl"}
			</div>
			<div class="menu">
				{include file="menu^menu.tpl"}
			</div>
			<div class="messages">
				{include file="miscellaneous^messages.tpl" messages=$REQUEST.messages}
			</div>
			<div class="content">
				{$MAIN_CONTENT}
			</div>
		</div>
	</div>
	<div class="footer {if $GLOBALS.current_user.logged_in}long{else}short{/if}">
		{include file="main^page_parts/footer_block.tpl"}
	</div>
</body>
</html>
