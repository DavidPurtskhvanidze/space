<div class="featuredListings{if $number_of_cols > 0} list{$number_of_cols}Columns{/if}">
	<ul>
		{foreach from=$listings item=listing}
			<li>
				<div class="item">
					{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
					<div class="fieldValue fieldValuePictures">
						{if $listing.pictures.numberOfItems > 0}
							<a href="{$listingUrl}">{listing_image pictureInfo=$listing.pictures.collection.0 alt="Listing #"|cat:$listing.id thumbnail=1}</a>
						{else}
							<a href="{$listingUrl}"><img src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]" class="noImageAvailable" /></a>
						{/if}
					</div>
					<div class="details">
						<a href="{$listingUrl}">{$listing|cat:""|strip_tags:false|truncate:30:"...":true}</a>
						{if $listing.Price.exists && !$listing.Price.isEmpty}
							<span class="fieldValue fieldValuePrice"><a href="{$listingUrl}">{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]</a></span>
						{/if}
					</div>
				</div>
			</li>
		{/foreach}
        <div class="clearBoth"></div>
	</ul>

</div>
