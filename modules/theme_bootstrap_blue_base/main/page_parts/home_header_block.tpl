<header>
    <nav id="mobile-menu" class="hidden-md hidden-lg">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 text-left menu-trigger">
                    <i id="trigger" class="fa fa-bars fa-2x"></i>
                </div>
                <div class="col-xs-6 text-right">
                    {$smarty.capture.user_menu_block}
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="logo-container hidden-sm hidden-xs text-center">
            {$smarty.capture.logo}
        </div>

        <nav id="main-menu" class="navbar hidden-sm hidden-xs navbar-inverse colorize-menu" role="navigation">
            <div class="container">
                <div class="row">
                    <div class="col-md-2 hidden-sm left">
                        <div class="menu-logo hidden middle">
                            <a href="{page_path id='root'}">
                                {if $GLOBALS.settings.fixed_top_menu_logo ne ''}
                                    <img class="img-responsive" src="{$GLOBALS.site_url}/{$GLOBALS.PicturesDir}{$GLOBALS.settings.fixed_top_menu_logo}" alt="">
                                {else}
                                    [[Your logo]]
                                {/if}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="collapse navbar-collapse">
                            {$smarty.capture.top_menu}
                        </div>
                    </div>
                    <div class="col-md-2 hidden-sm right">
                        <a id="back-to-top" class="hidden" href="#top"><span>[[Back to top]]</span><i class="fa fa-angle-double-up fa-2x"></i></a>
                    </div>
                </div>
            </div>
        </nav>
        <script type="text/javascript">
            function toTopMainMenu()
            {
                $(window).bind('scroll', function () {
                    var mainMenu = $('#main-menu, #mobile-menu');
                    if ($(window).scrollTop() > 180) {
                        mainMenu.addClass('navbar-fixed-top').find('.menu-logo').removeClass('hidden');
                        mainMenu.find('#back-to-top').removeClass('hidden');
                    } else {
                        mainMenu.removeClass('navbar-fixed-top').find('.menu-logo').addClass('hidden');
                        mainMenu.find('#back-to-top').addClass('hidden');
                    }
                });
            }

            $(document).ready(function()
            {
                if (getWindowWidth() > 767)
                {
                    toTopMainMenu();
                }
                new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ),
                        {
                            openCallBack: function(ev){
                                document.documentElement.style.overflow = 'hidden';
                                document.body.scroll = "no";
                                window.scrollTo(0, 0);
                            },
                            closeCallBack: function(ev){
                                document.documentElement.style.overflow = 'auto';
                                document.body.scroll = "yes";
                            }
                        });
            });
        </script>
        {block name="slider_images"}
            {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="image_carousel" function="display_carousel" width="1500" height="500"}
        {/block}
        {block name="quick_search"}
            {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="search_form" form_template="quick_search.tpl" category_id="car" do_not_modify_meta_data=true}
        {/block}
    </div>
</header>
<div class="container">
	<div class="globalErrorWrapper">
		{extension_point name='modules\main\apps\FrontEnd\IGlobalErrorDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
	</div>
</div>

