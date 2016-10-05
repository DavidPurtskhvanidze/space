<!DOCTYPE html>
<html lang="{i18n->getCurrentLanguage}">
{include file="main^page_parts/html_head.tpl"}
<body class="colorize-main {foreach from=$GLOBALS.themeInheritanceBranch item=theme} {$theme->getName()} {/foreach}">
<div class="menu-container">
   <div class="mp-pusher" id="mp-pusher">
        <div id="page-content">
            {include file="miscellaneous^check_js_cookies.tpl"}
            {module name='main' function='display_body_top_templates' do_not_modify_meta_data=true}
            {include file="main^page_parts/very_top.tpl"}

            {capture name='top_menu'}
                {module name="menu" function="top_menu"}
            {/capture}
            <nav id="mp-menu" class="mp-menu">
                <div class="mp-level">
                    {$smarty.capture.top_menu}
                </div>
            </nav>

            {block name="header"}
                {include file="main^page_parts/header_block.tpl"}
            {/block}
            {block name="mainContent"}
                <div class="space-20"></div>
                <div class="container">
                    {$MAIN_CONTENT}
                </div>
            {/block}
        </div>

        {include file="main^page_parts/footer_block.tpl"}
        {module name='main' function='display_body_bottom_templates' do_not_modify_meta_data=true}
    </div>
</div>
</body>
</html>
