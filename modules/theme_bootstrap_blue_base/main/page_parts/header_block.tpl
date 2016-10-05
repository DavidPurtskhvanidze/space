<nav id="mobile-menu" class="hidden-md hidden-lg">
    <div class="container">
        <div class="row">
            <div class="col-xs-2 text-left menu-trigger">
                <i id="trigger" class="fa fa-bars fa-2x"></i>
            </div>
            <div class="col-xs-5 col-sm-6 text-center">
                <div class="menu-logo">
                    <a href="{page_path id='root'}">
                        {if $GLOBALS.settings.fixed_top_menu_logo ne ''}
                            <img class="img-responsive" src="{$GLOBALS.site_url}/{$GLOBALS.PicturesDir}{$GLOBALS.settings.fixed_top_menu_logo}" alt="">
                        {else}
                            [[Your logo]]
                        {/if}
                    </a>
                </div>
            </div>
            <div class="col-xs-5 col-sm-4 text-right">
                {$smarty.capture.user_menu_block}
            </div>
        </div>
    </div>
</nav>

<nav id="main-menu" class="navbar hidden-sm hidden-xs navbar-inverse colorize-menu" role="navigation">
    <div class="container">
        <div class="row">
            <div class="col-md-2 hidden-sm left">
                <div class="middle menu-logo">
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
    $(document).ready(function(){
        $(window).bind('scroll', function () {
            var mainMenu = $('#main-menu, #mobile-menu');
            if ($(window).scrollTop() > 50) {
                mainMenu.addClass('navbar-fixed-top');
                mainMenu.find('#back-to-top').removeClass('hidden');
                $('#page-content').css('margin-top', '50px');
            } else {
                mainMenu.removeClass('navbar-fixed-top');
                mainMenu.find('#back-to-top').addClass('hidden');
                $('#page-content').css('margin-top', '0px');
            }
        });

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
                } );
    });
</script>

<div class="container">
	<div class="globalErrorWrapper">
		{extension_point name='modules\main\apps\FrontEnd\IGlobalErrorDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
	</div>
</div>

