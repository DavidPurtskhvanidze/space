{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}

<div class="listing-box">
	<div class="listing-box-image">
		{module name="classifieds" function="display_quick_view_button" listing=$listing}
		{if $listing.pictures.numberOfItems > 0}
			{listing_image pictureInfo=$listing.pictures.collection.0 alt="Listing #"|cat:$listing.id}
		{else}
			<img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable img-responsive"/>
		{/if}
		<a class="overlay-img wb" href="{$listingUrl}"></a>
		{if $listing.Price.exists && !$listing.Price.isEmpty}
			<div class="listing-box-caption-price">
				{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]
			</div>
		{/if}
	</div>
	<div class="listing-box-caption">
		<div class="listing-box-caption-text">
			<h4>
				<a href="{$listingUrl}" title="{$listing|cat:""|strip_tags:false}">
					{$listing|cat:""|strip_tags:false}
				</a>
				<span class="listing-category">
					{$listing.category_sid}
				</span>
				<span class="paragraph-end"></span>
			</h4>
		</div>
	</div>
</div>
