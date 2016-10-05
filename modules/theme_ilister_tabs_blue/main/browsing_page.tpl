<!DOCTYPE html>
<html lang="{i18n->getCurrentLanguage}">
{include file="main^page_parts/html_head.tpl"}
<body class="colorize-main {foreach from=$GLOBALS.themeInheritanceBranch item=theme} {$theme->getName()} {/foreach}">
	{include file="miscellaneous^check_js_cookies.tpl"}
	{module name='main' function='display_body_top_templates'}
	
	{include file="main^page_parts/header_block.tpl"}
	{include file="main^page_parts/single_column_main_content_block_for_browsing.tpl"}
	{include file="main^page_parts/footer_block.tpl"}
	{module name='main' function='display_body_bottom_templates'}
</body>
</html>
