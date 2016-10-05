<div class="header">
	<div class="slogan">
		{IncludeMainLogo}
	</div>
	{module name="classifieds" function="search_form" form_template="quick_search.tpl"}
</div>
<div class="fixedWidthBlock globalErrorWrapper">
	{extension_point name='modules\main\apps\FrontEnd\IGlobalErrorDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
</div>
