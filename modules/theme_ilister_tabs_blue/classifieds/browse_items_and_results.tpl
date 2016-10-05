{set_global_parameter key='browsingFieldIds' value= $browsingFieldIds}
<div class="fixedWidthBlock twoColumnLayout">
	{if $browseItemsGroupedByColumn|@count > 0}
		<div class="rightHelperColumnLayout helperColumnBlock">
			<div class="featuredAndRecentListingTabs tabularPages">
				<ul>
					<li class="tab featuredAds active"><a href="#FeaturedAds">[[Featured Ads]]</a></li>
				</ul>
				<div id="FeaturedAds" class="tabContent featuredAds active">
					{module name="listing_feature_featured" function="featured_listings" featured_listings_template="featured_listings.tpl" number_of_rows="4" number_of_cols="1"}
				</div>
			</div>
		</div>
	{/if}
	<div class="{if $browseItemsGroupedByColumn|@count > 0}rightHelperColumnLayout mainContentBlock{/if}">
		<div class="browsePage">
			<ul class="breadcrumbs">
				{strip}
				{if $browse_navigation_elements || isset($REQUEST.view_all)}
					<li><a href="{$GLOBALS.site_url}{$current_page_uri}">[[$TITLE]]</a></li>
				{else}
					<li><span>[[$TITLE]]</span></li>
				{/if}

				{foreach from=$browse_navigation_elements item=element name="nav_elements"}
					{title}{tr metadata=$element.metadata mode="raw"}{$element.caption}{/tr}{/title}
					{keywords}{tr metadata=$element.metadata mode="raw"}{$element.caption}{/tr}{/keywords}
					{description}{tr metadata=$element.metadata mode="raw"}{$element.caption}{/tr}{/description}

					{if $smarty.foreach.nav_elements.last && !isset($REQUEST.view_all)}
						<li><span>{tr metadata=$element.metadata}{$element.caption}{/tr}</span></li>
					{else}
						<li><a href="{$GLOBALS.site_url}{$element.uri}">{tr metadata=$element.metadata}{$element.caption}{/tr}</a></li>
					{/if}
				{/foreach}
				{/strip}
			</ul>
			{include file="errors.tpl"}
			{if $browseItemsGroupedByColumn|@count > 0}
				{capture name=browseItems}
					<ul>
					{foreach from=$browseItemsGroupedByColumn item=browseItemsForColumn}
						<li>
							<ul>
								{foreach from=$browseItemsForColumn item=browseItem}
									<li>
										<a href="{$browseItem.url}/"{if $browseItem.count == 0} class="emptyLink"{/if}>[[$browseItem.caption]] ({$browseItem.count})</a>
									</li>
								{/foreach}
							</ul>
						</li>
					{/foreach}
					</ul>
				{/capture}

				<span class="viewAllBrosingItemsLink"><a href='?view_all'>[[View All Listings]] ({$totalListingsNumber})</a></span>
				<div class="browseItems list{$number_of_cols}Columns">
					{$smarty.capture.browseItems}
				</div>
			{else}
				{module name="classifieds" function="search_results" results_template=$results_template}
			{/if}
		</div>
	</div>
</div>
