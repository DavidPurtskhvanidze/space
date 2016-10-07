{require component="owl-carousel" file="assets/owl.carousel.css"}
{require component="owl-carousel" file="assets/owl.theme.default.min.css"}
{require component="jquery" file="jquery.js"}
{require component="owl-carousel" file="owl.carousel.js"}

<section class="quick-search-block">
    <div class="container">
        <h1>
            [[Place some great words here]]
        </h1>
        <h4>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. At commodi deserunt error eum quia.
        </h4>
        <div class="quick-search-form-block">
            {module name="classifieds" function="search_form" form_template="classifieds^quick_search.tpl" do_not_modify_meta_data=true}
        </div>
        <a href="{page_path id='search'}">[[Advanced search]]</a>
    </div>
</section>

<section class="featured-ads-block">
    <div class="container">
        <h3 class="title">
            [[Featured Ads]]
        </h3>
        <div id="featured-ads-box" class="carousel">
            {module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings_slider.tpl" number_of_rows="8"}
        </div>
        <script>
            $(function () {
                $("#featured-ads-box").owlCarousel({
                    responsiveClass:true,
                    loop: true,
                    dots: true,
                    animateOut: 'fadeOut',
                    items: 3,
                    nav: true,
                    navText: [
                        '<div><img src="{url file='main^img/slider-arrow-right.png'}"></div>',
                        '<div><img src="{url file='main^img/slider-arrow-left.png'}"></div>'
                    ],
                    responsive:{
                        0:{
                            items:1,
                            nav: false
                        },
                        480: {
                            items:2,
                            nav: false
                        },
                        768:{
                            nav: true
                        }
                    }
                });
            });
        </script>
    </div>
</section>

<section class="popular-listings-block">
    <div class="container">
        <h3 class="title">
            [[Popular Listings]]
        </h3>
        {module name="popular_listings" function="display_popular_listings" number_of_rows="1" number_of_cols="4"}
    </div>
</section>

<section class="browse-block">
    <div class="container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#BrowseByCategory" role="tab" data-toggle="tab">[[Browse by Category]]</a>
            </li>
            <li>
                <a href="#BrowseByState" role="tab" data-toggle="tab">[[Browse by State]]</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade in active" id="BrowseByCategory">
                {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="browse" category_id="root" fields="category_sid" number_of_cols="4" browse_template="browse_by_categories_and_subcategories.tpl" do_not_modify_meta_data=true}
            </div>
            <div class="tab-pane fade" id="BrowseByState">
                {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="browse" category_id="root" fields="State" number_of_cols="4" browse_template="browse_by_state.tpl" do_not_modify_meta_data=true}
            </div>
        </div>
    </div>
</section>

<section class="recent-listings-block">
    <div class="container">
        <h3 class="title">
            [[Recent Listings]]
        </h3>
        {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='12H' name="recent_listings" function="display_recent_listings" number_of_rows="8" do_not_modify_meta_data=true}
    </div>
</section>

<section class="news-block">
    <div class="container">
        <h3 class="title">
            [[News]]
        </h3>
        {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="publications" function="show_publications" passed_parameters_via_uri="" category_id="News" number_of_publications="3" publications_template="print_news_box_articles.tpl"}
    </div>
</section>

<section class="tweets-block">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <h3 class="title">
                    [[Recent Tweets]]
                </h3>
                <div class="text-center hidden-md hidden-sm hidden-xs">
                    <a class="btn h5 btn-orange" href="https://twitter.com/#!/{$twitterTimeline.user.screen_name}" onclick="javascript:window.open(this.href, '_blank'); return false;" rel="nofollow">
                        [[View all tweets]]
                    </a>
                </div>
            </div>
            {module name="recent_tweets" function="display_recent_tweets" count="3"}
            <div class="text-center hidden-lg">
                <a class="btn h5 btn-orange" href="https://twitter.com/#!/{$twitterTimeline.user.screen_name}" onclick="javascript:window.open(this.href, '_blank'); return false;" rel="nofollow">
                    [[View all tweets]]
                </a>
            </div>
        </div>
    </div>
</section>

<section class="social-block">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="fb-box">
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) return;
                            js = d.createElement(s); js.id = id;
                            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
                    </script>
                    <div class="fb-page" data-href="https://www.facebook.com/worksforweb.classifieds/" data-width="500" data-height="250" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                        <blockquote cite="https://www.facebook.com/worksforweb.classifieds/" class="fb-xfbml-parse-ignore">
                            <a href="https://www.facebook.com/worksforweb.classifieds/">
                                Worksforweb Classifieds Software &amp; Custom Services
                            </a>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                {module name="main" function="display_template" template_file="google_adsense.tpl" do_not_modify_meta_data=true}
            </div>
        </div>
    </div>
</section>

