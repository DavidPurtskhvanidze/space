<div class="homepageMainContent">
	<div class="browseByStateWrapper">
		{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="browse" category_id="root" fields="State" number_of_cols="1" browse_template="browse_by_state.tpl" do_not_modify_meta_data=true}
	</div>
	<div class="centerBlockWrapper">
		{module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings.tpl" number_of_rows="1" number_of_cols="4" do_not_modify_meta_data=true}
		{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="browse" category_id="root" fields="category_sid" browse_template="browse_by_categories_and_subcategories.tpl" number_of_cols="3" do_not_modify_meta_data=true}
		{module name="main" function="display_template" template_file="google_adsense.tpl" do_not_modify_meta_data=true}
		{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="recent_listings" function="display_recent_listings" number_of_rows="1" number_of_cols="4" do_not_modify_meta_data=true}
	</div>
</div>
