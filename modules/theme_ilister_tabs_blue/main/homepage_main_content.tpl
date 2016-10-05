<div class="quickSearchAndNewsWrapper">
	<div class="quickSearch">
		<h2>[[Buy & Sell With Us]]</h2>
		<div class="quickSearchTabs">
			<ul>
				<li class="tab quickSearch"><a href="#QuickSearch">[[Quick Search]]</a></li>
				<li class="tab findSeller"><a href="#FindSeller">[[Find a Seller]]</a></li>
				<li class="tab sellCar"><a href="#SellCar">[[Post Your Ad]]</a></li>
			</ul>
			<div id="QuickSearch" class="tabContent quickSearch">
				{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='10D' name="classifieds" function="search_form" form_template="quick_search.tpl" do_not_modify_meta_data=true}
			</div>
			<div id="FindSeller" class="tabContent findSeller">
				{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='10D' name="users" function="search_users" user_group_id="Dealer" search_form_template="homepage_user_search_form.tpl" do_not_modify_meta_data=true}
			</div>
			<div id="SellCar" class="tabContent sellCar">
				{assign var="siteName" value=$GLOBALS.custom_settings.site_name}
				[[Why people choose $siteName]]:
				<ul class="standard">
					<li>[[Ease: create your ad in 4 easy steps: registration, login, filling car specs, add pictures]]</li>
					<li>[[Value: we have a range of packages to suit your budget]]</li>
					<li>[[Confidence: with over years of online and live experience, we help make it a smooth sale]]</li>
				</ul>
				<form action="{page_path id='listing_add'}">
					<fieldset class="formControls">
						<input type="submit" value="[[Post Your Ad:raw]]" />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<div class="news">
		{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='2M' name="listing_feature_youtube" function="show_youtube_video"  videoId="sJ_7iu_5TQ4" width="413" height="212" do_not_modify_meta_data=true}
		{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="publications" function="show_publications" passed_parameters_via_uri="" category_id="News" number_of_publications="2" publications_template="homepage_news.tpl" do_not_modify_meta_data=true}
	</div>
</div>

<div class="AdsAndFacebookWrapper">
	<div class="leftTabGroup tabularPages">
		<ul>
			<li class="tab featuredAds"><a href="#FeaturedListings">[[Featured Ads]]</a></li>
			<li class="tab recentAds"><a href="#RecentListings">[[Recent Ads]]</a></li>
		</ul>
		<div id="FeaturedListings" class="tabContent featuredAds">
			{module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings.tpl" number_of_rows="2" number_of_cols="2"}
		</div>
		<div id="RecentListings" class="tabContent recentAds">
			{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='12H' name="recent_listings" function="display_recent_listings" number_of_rows="2" number_of_cols="2"}
		</div>
	</div>
	<div class="rightTabGroup tabularPages">
		<ul>
			<li class="tab facebook active"><span>[[Facebook]]</span></li>
		</ul>
		<div id="Facebook" class="tabContent facebook active">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=195402287233378";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-like-box" data-href="http://www.facebook.com/worksforweb.classifieds" data-width="395" data-height="250" data-show-faces="true" data-stream="false" data-show-border="false" data-header="false"></div>
		</div>
</div>
</div>

{module name="main" function="display_template" template_file="google_adsense.tpl"}

<div class="BrowsingTabsWrapper">
	<div class="leftTabGroup tabularPages">
		<ul>
			<li class="tab browseByCategory active"><span>[[Browse by Category]]</span></li>
		</ul>
		<div id="BrowseByCategory" class="tabContent BrowseByCategory active">
			{module name="classifieds" function="browse" category_id="root" fields="category_sid" browse_template="browse_by_categories_and_subcategories.tpl" number_of_cols="3"}
			<div class="toggleItems">
				<span class="expand">
					<a href="#" >[[View All Categories]]</a>
					<a href="#" ><img src="{url file='main^grey_arrow_down.png'}" /></a>
				</span>
				<span class="collapse" style="display:none;">
					<a href="#" >[[View Less]]</a>
					<a href="#" ><img src="{url file='main^grey_arrow_up.png'}" /></a>
				</span>
			</div>
		</div>
	</div>
	<div class="rightTabGroup tabularPages">
		<ul>
			<li class="tab browseByState active"><span>[[Browse By State]]</span></li>
		</ul>
		<div id="BrowseByState" class="tabContent BrowseByState active">
			{module cached=$GLOBALS.settings.cache_blocks_main_page cacheLifeTime='1D' name="classifieds" function="browse" category_id="root" fields="State" number_of_cols="3" browse_template="browse_by_state.tpl"}
		</div>
	</div>
</div>
{module name="popular_listings" function="display_popular_listings" number_of_rows="1" number_of_cols="4"}
{require component='jquery' file='jquery.js'}
<script type="text/javascript">
	$(document).ready(function(){
		function makeTabs(selector) {
			var tabArray = $(selector + ' > ul li');
			var tabContentArray = $(selector + ' > div');
			tabArray.removeClass('active').filter(':first').addClass('active');
			tabContentArray.removeClass('active').filter(':first').addClass('active');

			$(selector + ' > ul a').click(function () {
				tabContentArray.removeClass('active');
				tabContentArray.filter(this.hash).addClass('active');
				tabArray.removeClass('active');
				$(this).parent().addClass('active');
				
				return false;
			}).filter(':first').click();
		}
		makeTabs('.quickSearchTabs');
		makeTabs('.AdsAndFacebookWrapper .leftTabGroup');
		
		function setInputControlCaption(selector)
		{
			selector += ' input[type="text"]';
			$(selector).each(function(){
				var element = $(this);
				var caption = element.parent().attr('rel');
				element.val(caption);
				
				element.bind('focus', function(){
					if ($(this).val() == caption){
						$(this).val('');
					}
				});
				element.bind('blur', function(){
					if (!$.trim(this.value).length){
						$(this).val(caption);
					}
				});
				element.closest("form").bind('submit', function(){
					if (element.val() == caption){
						element.val('');
					}
				});
			});
		}
		setInputControlCaption('.inputValuePlaceholder');
		
		function expandTabContent(selector)
		{
			var contentElement = $(selector).parent();
			var collapsedHeight = contentElement.css('height');
			var expandedHeight = contentElement.prop('scrollHeight') + $(selector).prop('scrollHeight');
			
			$(selector + ' .expand a').bind('click', function(){
				contentElement.animate({
					height: expandedHeight
				},1000);
				$(selector + ' .collapse').css('display', 'inline');
				$(selector + ' .expand').css('display', 'none');
				return false;
			});
			$(selector + ' .collapse a').bind('click', function(){
				contentElement.animate({
					height: collapsedHeight
				},1000);
				$(selector + ' .expand').css('display', 'inline');
				$(selector + ' .collapse').css('display', 'none');
				return false;
			});
		}
		expandTabContent('.tabContent .toggleItems');

		if ($(".featuredListings .item").length == 0 && $(".recentListings .item").length > 0)
			$(".recentAds a").click();
	});
</script>
