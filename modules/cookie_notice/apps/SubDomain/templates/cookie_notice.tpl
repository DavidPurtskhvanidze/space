<div class="cookieNoticePage">
	{i18n->getCurrentLanguage assign="currentLanguage"}
	{module name="static_content" function="show_static_content" pageid='CookiePolicy_'|cat:$currentLanguage}
</div>
