<!DOCTYPE html>
<html lang="{i18n->getCurrentLanguage}">
{include file="main^page_parts/html_head.tpl"}
<body class="colorize-main {foreach from=$GLOBALS.themeInheritanceBranch item=theme} {$theme->getName()} {/foreach} {$main_content_module} {$main_content_function}">
	{include file="miscellaneous^check_js_cookies.tpl"}
	{module name='main' function='display_body_top_templates'}
	<div id="SiteFrame">
		<div class="headerAndCenterWrapper">
		    {include file="main^page_parts/header_block.tpl"}
		    <div class="colmask leftmenu">
		        <div class="colright">
		            <div class="leftBlockWrap">
		                <div class="mainContent">
		                    {$MAIN_CONTENT}
		                </div>
		            </div>
		            <div class="leftBlock">
		                {include file="main^page_parts/left_block.tpl"}
						{module name='main' function='display_left_block_bottom_templates'}
		            </div>
		        </div>
		    </div>
		</div>
		{include file="main^page_parts/footer_block.tpl"}
	</div>
	{module name='main' function='display_body_bottom_templates'}
</body>
</html>
