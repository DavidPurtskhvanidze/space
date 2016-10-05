{require component="jquery" file="jquery.js"}
{require component="twitter-bootstrap" file="css/bootstrap.min.css"}
{require component="twitter-bootstrap" file="js/bootstrap.min.js"}

{include file="miscellaneous^bootstrap_button_noconflict.tpl"}

{capture name='user_menu_block'}
    {module name="users" function="user_menu_block"}
{/capture}

{capture name='logo'}
    {IncludeMainLogo}
{/capture}
<a name="top"></a>
<nav class="navbar very-top-menu navbar-inverse" role="navigation">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-md-4 row">
                <ul class="list-inline left">
                    <li>{extension_point name='modules\main\apps\FrontEnd\IWidgetDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}</li>
                    <li>{include file="miscellaneous^language_selector.tpl"}</li>
                    {if $GLOBALS.current_user.logged_in}
                        <li>
                            {extension_point name="modules\menu\apps\FrontEnd\ITopMenuItem" wrapperStartTag="<span class=\"basketTopMenuItem\">" wrapperEndTag="</span>"}
                        </li>
                    {/if}
                </ul>
            </div>
            <div class="col-xs-6 col-md-8 pull-right">
                <div class="row">
                    <div class="col-sm-12 col-md-8 text-right">
                        <ul class="list-inline text-right clear-margin">
                            <li>
                                <a href="{page_path id='user_saved_listings'}">[[Saved Ads]]</a>
                            </li>
                            <li class="hidden-xs">|</li>
                            <li class="hidden-xs">
                                <a href="{page_path id='user_saved_searches'}">[[Saved Searches]]</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-0 col-md-4 hidden-sm hidden-xs">
                            {$smarty.capture.user_menu_block}
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<script type="text/javascript">
    function getWindowWidth()
    {
        if (window.windowWidth != undefined && window.windowWidth > 0) return window.windowWidth;
        var w = window,
                d = document,
                e = d.documentElement,
                g = d.getElementsByTagName('body')[0],
                x = w.innerWidth || e.clientWidth || g.clientWidth;
        window.windowWidth = x;
        return window.windowWidth;
    }
</script>
