{require component="owl-carousel" file="assets/owl.carousel.css"}
{require component="owl-carousel" file="assets/owl.theme.default.min.css"}
{require component="jquery" file="jquery.js"}
{require component="owl-carousel" file="owl.carousel.js"}

<div id="HomePageCarousel" class="carousel">
	{foreach $images as $image}
		<div>
			{if $image.url}<a href="{if strpos($image.url, '/') === 0}{$GLOBALS.site_url}{/if}{$image.url}">{/if}
				<img src="{$image.image.original.url}" class="img-responsive" title="{$image.caption}">
			{if $image.url}</a>{/if}
		</div>
	{/foreach}
</div>

<script>
	$(function () {
		$("#HomePageCarousel").owlCarousel({
			loop: {if $images|@count > 1}true{else}false{/if},
			autoplay: {if $images|@count > 1}true{else}false{/if},
			autoplaySpeed: 400,
			dots: true,
			animateOut: 'fadeOut',
			items: 1
		});
	});
</script>
