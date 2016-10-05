<head>
<meta name="keywords" content="{$KEYWORDS}" />
<meta name="description" content="{$DESCRIPTION}" />
{$META_TAGS}
<title>{$GLOBALS.custom_settings.site_name}{if $TITLE ne ""}: {$TITLE}{/if}</title>
<!-- #EXTERNAL_COMPONENTS_PLACEHOLDER# -->

{includeDesignFiles}
{includeFavicon}
<script type="text/javascript" src="{url file='main^bookmarks.js'}"></script>
{if $GLOBALS.settings.i18n_sort_translated_list_and_tree_values}
	{include file="I18N^sort_select_options_js.tpl"}
{/if}
{i18n->getCurrentLanguage assign="currentLanguage"}
{module name='main' function='display_html_header_tag_content'}
</head>
