<head>
    <meta name="keywords" content="{$KEYWORDS}" />
    <meta name="description" content="{$DESCRIPTION}" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {$META_TAGS}
    <title>{$GLOBALS.custom_settings.site_name}{if $GLOBALS.custom_settings.site_name ne "" and $TITLE ne ""}: {/if}{$TITLE}</title>
    <!-- #EXTERNAL_COMPONENTS_PLACEHOLDER# -->
    <link rel="stylesheet" type="text/css" href="{url file='main^font-awesome/css/font-awesome.min.css'}">
    {includeDesignFiles}
    {includeFavicon}
    <script type="text/javascript" src="{url file='main^bookmarks.js'}"></script>
    {if $GLOBALS.settings.i18n_sort_translated_list_and_tree_values}
        {include file="I18N^sort_select_options_js.tpl"}
    {/if}
    {i18n->getCurrentLanguage assign="currentLanguage"}
    {module name='main' function='display_html_header_tag_content'}
    <script type="text/javascript" src="{url file="bootstrap-toggle-master/bootstrap-toggle.min.js"}"></script>
    <script type="text/javascript" src="{url file="waves/waves.min.js"}"></script>
</head>
