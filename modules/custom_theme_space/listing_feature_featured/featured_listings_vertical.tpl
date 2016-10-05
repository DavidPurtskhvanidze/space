{extension_point name='modules\listing_feature_featured\apps\FrontEnd\IListingFeatureFeaturedAdditionRenderer'}
<div class="featuredListings gallery-view">
	<h2>[[Featured Ads]]</h2>
	{foreach from=$listings item=listing}
		{include file="featured_listing_item.tpl"}
	{/foreach}
</div>
