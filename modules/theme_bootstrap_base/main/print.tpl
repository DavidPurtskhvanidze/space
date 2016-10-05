<!DOCTYPE html>
<html lang="{i18n->getCurrentLanguage}">
{include file="main^page_parts/html_head.tpl"}
{require component="jquery" file="jquery.js"}
{require component="twitter-bootstrap" file="css/bootstrap.min.css"}
{require component="twitter-bootstrap" file="js/bootstrap.min.js"}
<body class="print">
    <div class="container">
        {$MAIN_CONTENT}
    </div>
    {module name='main' function='display_body_bottom_templates'}
</body>
</html>
