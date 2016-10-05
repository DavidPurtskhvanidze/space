{capture assign=successMessages}
	{display_success_messages}
{/capture}
{extension_point name='modules\main\apps\FrontEnd\IListingDisplayAdditionDisplayer'}
{title}{$listing|cat:""|strip_tags:false}{/title}
{keywords}{$listing|cat:""|strip_tags:false|escape:"html"}{/keywords}
{description}{$listing.Description.value|strip_tags:false}{/description}
<div class="threeColumnLayout leftColumnBlock">
	<div class="listingToolboxHeader">
		<span>[[Toolbox]]</span>
	</div>
	<div class="toolboxControls refineSearchForm">
		<ul class="menu accordion">
			{if $GLOBALS.current_user.logged_in && $listing.user.sid == $GLOBALS.current_user.id}
				<li class="header EditListing"><a href="{page_path id='listing_edit'}{$listing.id}"><img src="{url file='main^icons/pencil.png'}">[[Edit Listing]]</a></li>
			{/if}
			{if !empty($listing_search)}
				<li class="header displayListingPagination">
					{include file="category_templates/display/listing_details_search_controls.tpl" listing=$listing}
				</li>
			{/if}
			<li class="tab ManageSearch opened"><a href="#"><i class="iconMenuTriangle"></i>[[Manage Search]]</a></li>
			<li class="content ManageSearch">
				{include file="classifieds^search_controls.tpl"}
			</li>
			<li class="tab Rating opened"><a href="#"><i class="iconMenuTriangle"></i>[[Rating]]</a></li>
			<li class="content Rating">
				{include file="category_templates/display/social_network_buttons.tpl"}
				<span class="fieldValue fieldValueListingRating">{display property=ListingRating}</span>
			</li>
			<li class="tab SellerInfo opened"><a href="#"><i class="iconMenuTriangle"></i>[[Seller Info]]</a></li>
			<li class="content SellerInfo">
				{include file="author_info.tpl" listing=$listing}
			</li>
		</ul>
	</div>
</div>
<div class="displayListingHeader listingTitle">
	<h1>
		{if $listing.Sold.exists && $listing.Sold.isTrue}
			<span class="fieldValue fieldValueSold">[[SOLD]]!</span>
		{/if}
		{$listing} 
		{if $listing.Price.exists && !$listing.Price.isEmpty}
			<span class="fieldValue fieldValuePrice {$listing.Price.type}">{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]</span>
		{/if}
	</h1>
</div>
<div class="threeColumnLayout rightColumnBlock">
	<div class="featuredAndRecentListingTabs tabularPages">
		<ul>
			<li class="tab featuredAds"><a href="#FeaturedListings">[[FeaturedAds]]</a></li>
			<li class="tab recentAds"><a href="#RecentListings">[[RecentAds]]</a></li>
		</ul>
		<div id="FeaturedListings" class="tabContent featuredAds">
			{module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings_simple.tpl" number_of_rows="9" number_of_cols="1"}
		</div>
		<div id="RecentListings" class="tabContent recentAds">
			{module name="recent_listings" function="display_recent_listings" recent_listings_template="recent_listings_simple.tpl" number_of_rows="9" number_of_cols="1"}
		</div>
	</div>
</div>
<div class="threeColumnLayout mainContentBlock">
	<div class="listingDetailTabs tabularPages">
		<ul>
			<li class="tab summary"><a href="#Summary">[[Summary]]</a></li>
			{if $listing.pictures.numberOfItems > 0}
				<li class="tab pictures"><a href="#Pictures">[[Pictures]]</a></li>
			{/if}
			{if $listing.Video.uploaded || ($listing.feature_youtube_video_id.exists && $listing.feature_youtube_video_id.isNotEmpty && !$listing.feature_youtube.isFalse)}
				<li class="tab video"><a href="#Video">[[Video]]</a></li>
			{/if}
			<li class="tab onTheMap"><a href="#OnTheMap" id="googleMapDisplayControl">[[On the Map]]</a></li>
			{module name="listing_comments" function="display_comment_control" listing=$listing wrapperTemplate='category_templates/display/comment_tab.tpl'}
		</ul>
		<div id="Summary" class="tabContent summary">
			{$successMessages}
			{include file="category_templates/display/listing_view_summary.tpl"}
		</div>
		{if $listing.pictures.numberOfItems > 0}
			<div id="Pictures" class="tabContent pictureGallary">
				{$successMessages}
				{include file="listing_images.tpl" listing=$listing}
			</div>
		{/if}
		<div id="Video" class="tabContent videoGallary">
			{$successMessages}
			{module name="listing_feature_youtube" function="display_youtube" listing=$listing width="513px"}

			{if $listing.Video.uploaded}
				{include file="video_player.tpl"}
			{/if}
		</div>
		<div id="OnTheMap" class="tabContent locationOnMap">
			{$successMessages}
			<div class="fieldValue fieldValueLocationOnMap">
				{module name='google_map' function='display_map' address=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State default_latitude=$listing.ZipCode.latitude default_longitude=$listing.ZipCode.longitude}
			</div>
		</div>
		<div id="Comments" class="tabContent comments">
			{$successMessages}
			{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?action=restore&'|cat:$smarty.server.QUERY_STRING}
			{assign var='current_uri' value=$current_uri|urlencode}
			{module name="listing_comments" function="display_listing_details_comments" listing=$listing returnBackUri=$current_uri}
            {module name="facebook_comments" function="display_comments" url="{page_url id='listing'}"|cat:$listing.id}
		</div>
	</div>
</div>
{require component='jquery' file='jquery.js'}
{require component="jquery-cookie" file="jquery.cookie.js"}
{require component="ad_gallery" file="ad_gallery.js"}
{require component="ad_gallery" file="ad_gallery.css"}
{include file="menu^menu_accordion_js.tpl"}
{include file="miscellaneous^save_active_tab_js.tpl"}
<script type="text/javascript">
var listingSid = '{$listing.sid}';
var needToRestoreActiveTab = '{$smarty.request.restoreActiveTab}';
var activeTab = '{$smarty.request.activeTab}';	
	$(document).ready(function(){
		function makeTabs(selector) {
			var tabArray = $(selector + ' > ul li');
			var tabContentArray = $(selector + ' > div');
			tabArray.removeClass('active').filter(':first').addClass('active');
			tabContentArray.removeClass('active').filter(':first').addClass('active');
			galleryRendered = false;

			$(selector + ' > ul a').click(function () {
				tabContentArray.removeClass('active');
				tabContentArray.filter(this.hash).addClass('active');
				tabArray.removeClass('active');
				$(this).parent().addClass('active');
				
				if (this.hash == '#Pictures' && !galleryRendered)
				{
					var galleries = $('.pictures > .ad-gallery').adGallery({
						effect : 'slide-hori',
						enable_keyboard_move : true,
						update_window_hash: false,
						cycle : true,
						animation_speed : 400,
						loader_image:'{url file='main^loader.gif'}',
						slideshow: {
							enable: false
						},
						callbacks: {
							init: function() {
								this.preloadImage(0);
								this.preloadImage(1);
								this.preloadImage(2);
							}
						}
					});
					galleryRendered = true;
				}
				
				return false;
			}).filter(':first').click();
		}
		makeTabs('.listingDetailTabs');
		makeTabs('.featuredAndRecentListingTabs');

		$('.listingDetailTabs > ul a').click(function(){
			saveActiveTab($(this).parent().attr('class'));
		});

		if (needToRestoreActiveTab == 'restore')
		{
			restoreActiveTab();
		}

		var googleMapResized = false;
		$('#googleMapDisplayControl').bind('click', function() {
			if (!googleMapResized) {
				var center = map.getCenter();
				google.maps.event.trigger(map, 'resize');
				map.setCenter(center);
				googleMapResized = true;
			}
		});
	});
</script>
