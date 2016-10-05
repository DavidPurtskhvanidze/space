<!DOCTYPE html>
<html lang="{i18n->getCurrentLanguage}">
{include file="main^page_parts/html_head.tpl"}
<body class="colorize-main {foreach from=$GLOBALS.themeInheritanceBranch item=theme} {$theme->getName()} {/foreach} {$main_content_module} {$main_content_function}">
{include file="miscellaneous^check_js_cookies.tpl"}
{module name='main' function='display_body_top_templates' do_not_modify_meta_data=true}

{include file="main^page_parts/header_block.tpl"}

{include file="main^page_parts/menu_and_carousel_block.tpl"}
{include file="main^page_parts/quick_search_block.tpl"}
{include file="main^page_parts/featured_listings_block.tpl"}
{include file="main^page_parts/homepage_main_content_block.tpl"}
{include file="main^page_parts/recent_listings_block.tpl"}
{module name="popular_listings" function="display_popular_listings" number_of_rows="1" number_of_cols="7"}
{include file="main^page_parts/footer_block.tpl"}
{module name='main' function='display_body_bottom_templates' do_not_modify_meta_data=true}
</body>
</html>
