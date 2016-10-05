<div class="fullWidthBlock">
	<div class="fixedWidthBlock twoColumnLayout">
		<div class="rightHelperColumnLayout helperColumnBlock">
			<div class="featuredAndRecentListingTabs tabularPages">
				<ul>
					<li class="tab featuredAds active"><span>[[Featured Ads]]</span></li>
				</ul>
				<div id="FeaturedAds" class="tabContent featuredAds active">
					{module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings.tpl" number_of_rows="4" number_of_cols="1"}
				</div>
			</div>
		</div>
		<div class="rightHelperColumnLayout mainContentBlock">
			{$MAIN_CONTENT}
		</div>
	</div>
</div>
