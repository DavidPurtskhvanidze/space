{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html?searchId={$listing_search.id}{/capture}
<div class="searchResultGalleryItem">
	<div class="pictureFrame">
		{module name="classifieds" function="display_quick_view_button" listing=$listing}
		{if $listing.Sold.exists && $listing.Sold.isTrue}
			<div class="soldLabel"><span>[[SOLD]]</span></div>
		{else}
			{module name="listing_feature_sponsored" function="display_label" listing=$listing}
		{/if}

		{assign var="number_of_pictures" value=$listing.pictures.numberOfItems}
        <a href="{$listingUrl}">
            {if $number_of_pictures > 0}
                {listing_image pictureInfo=$listing.pictures.collection.0}
            {else}
                <img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]"
                     class="noImageAvailable"/>
            {/if}
        </a>
	</div>
	<h2 class="listingTitle{if $listing.feature_highlighted.exists && $listing.feature_highlighted.isTrue} highlighted{/if}">
		<a href="{$listingUrl}">
			{$listing}
			{if $listing.Price.isExist && !$listing.Price.isEmpty}
				<span class="fieldValue fieldValuePrice {if !$listing.Price.isEmpty}{$listing.Price.type}{/if}">
					<span class="currencySign">{$GLOBALS.custom_settings.listing_currency}</span>[[$listing.Price]]
				</span>
			{/if}
		</a>
	</h2>
</div>
