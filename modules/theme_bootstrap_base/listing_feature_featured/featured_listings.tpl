{extension_point name='modules\listing_feature_featured\apps\FrontEnd\IListingFeatureFeaturedAdditionRenderer'}
<div class="featuredListings widget gallery-view">
	<h2>
		<a href="{page_path id='listings_featured'}">
			[[Featured Ads]]
		</a>
	</h2>
	<div class="row">
		{foreach from=$listings item=listing}
			<div class="col-sm-6 col-md-3">
				{include file="featured_listing_item.tpl"}
			</div>
			{if $listing@iteration is div by 4}<div class="clearfix visible-md visible-lg"></div>{/if}
			{if $listing@iteration is div by 2}<div class="clearfix visible-sm"></div>{/if}
		{/foreach}
	</div>
</div>
