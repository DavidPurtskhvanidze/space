{extension_point name='modules\listing_feature_featured\apps\FrontEnd\IListingFeatureFeaturedAdditionRenderer'}
{foreach from=$listings item=listing}
    {include file="featured_listing_item.tpl"}
{/foreach}