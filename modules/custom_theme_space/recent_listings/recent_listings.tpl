{extension_point name='modules\recent_listings\apps\FrontEnd\IRecentListingsAdditionRenderer'}
<div class="row">
	{foreach from=$listings item=listing}
		{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
		<div class="col-sm-3 col-xs-6 col-xxs-12">
			{include file="listing_feature_featured^featured_listing_item.tpl"}
		</div>
	{/foreach}
</div>

