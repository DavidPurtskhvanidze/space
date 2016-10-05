<!DOCTYPE html>
<html lang="{i18n->getCurrentLanguage}">
<head>
<meta name="keywords" content="{$KEYWORDS}" />
<meta name="description" content="{$DESCRIPTION}" />
<title>{$GLOBALS.settings.product_name} Admin Panel {if $TITLE ne ""} :: {$TITLE}{/if}</title>
<!-- #EXTERNAL_COMPONENTS_PLACEHOLDER# -->

{includeDesignFiles}

</head>
<body class="{foreach from=$GLOBALS.themeInheritanceBranch item=theme} {$theme->getName()} {/foreach} fullSize">
	{$MAIN_CONTENT}
</body>
</html>
