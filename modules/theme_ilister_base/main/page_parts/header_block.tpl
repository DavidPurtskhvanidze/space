<div id="PageHeader">
    {extension_point name='modules\main\apps\FrontEnd\IWidgetDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}

    {include file="miscellaneous^lang_selector.tpl"}
    <div class="pageHeaderLogo">
        <a href="{$GLOBALS.site_url}/"><img src="{url file='main^logo.png'}" alt="Your Logo Here" /></a>
    </div>
    <div class="slogan">[[Classifieds Software Demo Site]]</div>
    {module name="classifieds" function="search_form" form_template="quick_search.tpl"}
</div>
<div class="fixedWidthBlock globalErrorWrapper">
	{extension_point name='modules\main\apps\FrontEnd\IGlobalErrorDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
</div>
