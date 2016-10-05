{block name="slider_images"}
	{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="image_carousel" function="display_carousel" width="1500" height="500"}
{/block}

<div class="container">
	{block name="listing_feature"}
		{module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings.tpl" number_of_rows="1" number_of_cols="8" do_not_modify_meta_data=true}
	{/block}
    {block name="popular_listings"}
        {module name="popular_listings" function="display_popular_listings" number_of_rows="1" number_of_cols="4"}
    {/block}
	<div class="row">
		<div class="col-md-6">
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
		<div class="col-md-6">
			{module name="main" function="display_template" template_file="google_adsense.tpl" do_not_modify_meta_data=true}
		</div>
	</div>
</div>

{block name="browse_block"}
{/block}

<div class="container">
	{block name="recent_listings"}
		{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='12H' name="recent_listings" function="display_recent_listings" number_of_rows="1" number_of_cols="4" do_not_modify_meta_data=true}
	{/block}
</div>
