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

		{require component="jquery" file="jquery.js"}
		{require component="ad_gallery" file="ad_gallery.js"}
		{require component="ad_gallery" file="ad_gallery.css"}

		{literal}
		<script type="text/javascript">
		$(document).ready(function(){
			$(function() {
				var galleries = $('.pictures > .ad-gallery').adGallery({
					effect : 'slide-hori',
					enable_keyboard_move : true,
					update_window_hash: false,
					cycle : true,
					animation_speed : 400,
					loader_image:{/literal}'{url file='main^loader.gif'}'{literal},
					slideshow: {
						enable: false
					},
					callbacks: {
						init: function() {
							this.preloadImage(0);
							this.preloadImage(1);
							this.preloadImage(2);
						}
					}
				});
			});
		});
		</script>
		{/literal}
			<div class="galleryControls">
				{module name="listing_feature_slideshow" function="display_slideshow" listing=$listing}
			</div>
	{else}
		<img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable" />
	{/if}
</div>
