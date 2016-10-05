<!DOCTYPE html>
<html lang="{i18n->getCurrentLanguage}">
{include file="main^page_parts/html_head.tpl"}
<body class="print">
	{$MAIN_CONTENT}
	{module name='main' function='display_body_bottom_templates'}
</body>
</html>
