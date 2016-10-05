<div class="recentListings widget gallery-view 3-columns">
	{extension_point name='modules\recent_listings\apps\FrontEnd\IRecentListingsAdditionRenderer'}
	<h2 class="h2">
		<a class="h2" href="{page_path id='listings_recent'}">
			[[Recent Ads]]
		</a>
	</h2>
	<div class="row">
		{foreach from=$listings item=listing}
			{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
			<div class="col-sm-4 col-md-3">
                {include file="listing_feature_featured^featured_listing_item.tpl"}
			</div>
            {if $listing@iteration is div by 4}<div class="clearfix visible-md visible-lg"></div>{/if}
            {if $listing@iteration is div by 3}<div class="clearfix visible-sm"></div>{/if}
		{/foreach}

	</div>
</div>
