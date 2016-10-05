<span><input type="button" value="[[View Slide Show:raw]]" class="slideShowLauncher" /></span>

<div class="slideShowContainer" style="display: none;">
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
</div>

{require component="jquery" file="jquery.js"}
{require component="ad_gallery" file="ad_gallery.js"}
{require component="ad_gallery" file="ad_gallery.css"}

<script type="text/javascript">
	$(document).ready(function(){
		var SlideshowGalleryBuilt = false;
		$(".galleryControls .slideShowLauncher").click(function(){
			$(".slideShowContainer").dialog({
				"title" : '{$listing|strip_tags}',
				"draggable" : false,
				"modal" : true,
				"resizable" : false,
				"width" : 378,
				"create" : function(event, ui) {
					$(event.target).parent().css('position', 'fixed');
				}
			});
			if (!SlideshowGalleryBuilt) {
				var SlideshowGallery = $('.slideShowContainer > .ad-gallery').adGallery({
					update_window_hash: false,
					effect : 'slide-hori',
					enable_keyboard_move : true,
					cycle : true,
					animation_speed : 400,
					loader_image: '{url file='main^loader.gif'}',
					slideshow: {
						enable: true,
						autostart: true,
						speed: 5000,
						start_label: 'Start',
						stop_label: 'Stop',
						stop_on_scroll: true,
						countdown_prefix: '(',
						countdown_sufix: ')'
					},
					callbacks: {
						init: function() {
							this.preloadImage(0);
							this.preloadImage(1);
							this.preloadImage(2);
						}
					}
				});
				SlideshowGalleryBuilt = true;
			}
		});
	});
</script>
