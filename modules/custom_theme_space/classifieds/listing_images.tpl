<div class="pictures">
	{if $listing.pictures.numberOfItems > 0}
		{require component="owl-carousel" file="assets/owl.carousel.css"}
		{require component="owl-carousel" file="assets/owl.theme.default.min.css"}
		{require component="jquery" file="jquery.js"}
		{require component="owl-carousel" file="owl.carousel.js"}

		{module name="listing_feature_slideshow" function="display_slideshow" listing=$listing}
		<div id="Images" class="owl-carousel">
			{foreach from=$listing.pictures.collection key=key item=picture}
				<div class="item">
					{listing_image pictureInfo=$picture type="big"}
				</div>
			{/foreach}
		</div>
		<div id="Thumbnails" class="owl-carousel">
			{foreach from=$listing.pictures.collection key=key item=picture}
				<div class="item">
					{listing_image pictureInfo=$picture}
				</div>
			{/foreach}
		</div>
		<script>
			$(document).ready(function () {

				function disableAngle(angle)
				{
					if (angle == "left")
					{
						$('.fa-angle-left', images).hide();
						$('.fa-angle-right', images).show();
					}
					else
					{
						$('.fa-angle-right', images).hide();
						$('.fa-angle-left', images).show();
					}
				}
				var icons = $('.pictures ul.list-inline');
				var position = $('.pictures .slider_position');
				var images = $("#Images");
				var thumbs = $("#Thumbnails");
				var imagesCount = {$listing.pictures.collection|count};
				function init()
				{
					images.owlCarousel({
						center: true,
						margin: 10,
						items: 1,
						nav: true,
						navText: ['<i class="fa fa fa-angle-left fa-2x glyphicon glyphicon-chevron-left"></i>', '<i class="fa fa-angle-right fa-2x glyphicon glyphicon-chevron-right"></i>','sdfsd'],
						autoHeight: true,
						dots: false
					});

					icons.appendTo($('.owl-controls'));
                    position.appendTo($('.owl-controls'));
                    position.find(".count").html(imagesCount);

                    if (!(imagesCount > 1)) {
                        $('.fa-angle-left', images).hide();
                        $('.fa-angle-right', images).hide();
                    } else {
                        disableAngle("left");
                    }
                }

				init();

				thumbs.owlCarousel({
					margin: 3,
					dots: false,
					nav: false,
					responsive: {
						0: {
							items: 3
						},
						768: {
							items: 3
						},
						979: {
							items: 5
						}
					}
				});

                thumbs.find('.owl-item:first-child').addClass('selected');

                thumbs.on('click', '.owl-item', function (e) {

					// position to the image corresponding to the thumb
					images.trigger('to.owl.carousel', [$(this).index(), 300, true]);

					// add selected class to the thumb
					$(this).siblings().removeClass('selected');
					$(this).addClass('selected');

					// show next or prev thumb if it exists
					if ($(this).parent().find('.active').last().is(this))
					{
						thumbs.trigger('next.owl.carousel');
					}
					else if ($(this).parent().find('.active').first().is(this))
					{
						thumbs.trigger('prev.owl.carousel');
					}
				});

				images.on('changed.owl.carousel', function (event) {
					// if user slides a big image then we position thumbnail carousel corresponding to the slided image
					var item = event.item.index;
					thumbs.trigger('to.owl.carousel', [item, 300, true]);
					thumbs.find('.owl-item:eq(' + item + ')').click();
					if (item > 0 && item < imagesCount) $('.fa-angle-left, .fa-angle-right', images).show();
					if (item == 0) disableAngle("left");
					if (item == imagesCount - 1) disableAngle("right");
					position.find(".current").html(item+1);
				});

				if (imagesCount == 1) {
					$('#Images .owl-controls').addClass('hidden');
				}

				$(window).resize(function(){
					images.trigger('destroy.owl.carousel');
					images.html(images.find('.owl-stage-outer').html()).removeClass('owl-loaded');
					init();
				});

			});

		</script>
    {else}
        <img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable img-responsive" />
	{/if}
</div>
