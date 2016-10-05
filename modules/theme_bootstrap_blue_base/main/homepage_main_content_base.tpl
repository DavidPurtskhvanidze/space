<div class="container">
    {block name="listing_feature"}
        {module name="listing_feature_featured" function="featured_listings" number_of_cols="8" do_not_modify_meta_data=true}
    {/block}
</div>
<div class="space-20"></div>
<div class="blue-bg">
    <div class="container">
        {block name="popular_listings"}
            {module name="popular_listings" function="display_popular_listings" number_of_rows="2" number_of_cols="4"}
        {/block}
    </div>
    <div class="space-20"></div>
</div>
<div class="container">
	<div class="row">
		<div id="facebook-widget" class="col-sm-6">
			<div id="Facebook" class="facebook">
				<div id="fb-root"></div>
				<script>
					(function (d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s);
						js.id = id;
						js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=195402287233378";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>
				<div class="fb-like-box" data-href="http://www.facebook.com/worksforweb.classifieds" data-width="600"
				     data-height="250" data-show-faces="true" data-stream="false" data-show-border="false"
				     data-header="false"></div>
			</div>
		</div>
		<div id="google-adsense-block" class="col-sm-6">
			{module name="main" function="display_template" template_file="google_adsense.tpl" do_not_modify_meta_data=true}
		</div>
	</div>
</div>
{block name="browse_block"}
{/block}
<div class="blue-bg">
    <div class="container">
        {block name="recent_listings"}
            {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='12H' name="recent_listings" function="display_recent_listings" number_of_rows="1" number_of_cols="4" do_not_modify_meta_data=true}
        {/block}
    </div>
    <div class="space-20"></div>
</div>
<div class="container white-bg">
    {module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="publications" function="show_publications" passed_parameters_via_uri="" category_id="News" number_of_publications="4" publications_template="print_news_box_articles.tpl"}
    {module name="recent_tweets" function="display_recent_tweets" count="3"}
    {module name="poll" function="poll_form"}
</div>

