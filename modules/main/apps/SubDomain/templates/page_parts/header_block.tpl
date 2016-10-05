<div id="PageHeader">
{extension_point name='modules\main\apps\SubDomain\IWidgetDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}

    {include file="miscellaneous^lang_selector.tpl"}
    <div class="pageHeaderLogo">
        <a href="{$GLOBALS.site_url}/"><img src="{url file='main^logo.png'}" alt="Your Logo Here" /></a>
    </div>
    <div class="slogan">[[Your slogan here]]</div>
    {module name="menu" function="top_menu"}
</div>
<div class="fixedWidthBlock globalErrorWrapper">
	{extension_point name='modules\main\apps\SubDomain\IGlobalErrorDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
</div>
