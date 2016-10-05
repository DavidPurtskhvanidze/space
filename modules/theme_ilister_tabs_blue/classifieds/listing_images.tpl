<div class="pictures">
	{if $listing.pictures.numberOfItems > 0}
		<div class="ad-gallery">
			<div class="ad-image-wrapper"></div>
			<div class="ad-controls"></div>
			<div class="ad-nav">
				<div class="ad-thumbs">
					<ul class="ad-thumb-list">
						{foreach from=$listing.pictures.collection key=key item=picture name=thumbnails}
							<li>
								<a href="{$picture.file.picture.url}">
									{listing_image pictureInfo=$picture thumbnail=1}
								</a>
							</li>
						{/foreach}
					</ul>
				</div>
			</div>
		</div>
		<div class="galleryControls">
			<span><a href="{page_path id='listing_pictures'}{$listing.id}/{if isset($listing_search)}?searchId={$listing_search.id}{/if}">[[Show all pictures]]</a></span>
			{module name="listing_feature_slideshow" function="display_slideshow" listing=$listing}
		</div>
	{else}
		<img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable" />
	{/if}
</div>
