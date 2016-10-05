<div class="homepageMainContent">
	<div class="featuredListingsWrapper">
		{module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings.tpl" number_of_rows="4" number_of_cols="1" do_not_modify_meta_data=true}
	</div>
	<div class="centerBlockWrapper">
		{module name="classifieds" function="browse" category_id="root" fields="category_sid" browse_template="browse_by_categories.tpl" number_of_cols="5" do_not_modify_meta_data=true}
		{module name="recent_listings" function="display_recent_listings" number_of_rows="1" number_of_cols="4" do_not_modify_meta_data=true}
	</div>
</div>
