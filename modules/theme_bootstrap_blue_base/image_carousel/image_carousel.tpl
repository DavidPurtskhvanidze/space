{require component="owl-carousel" file="assets/owl.carousel.css"}
{require component="owl-carousel" file="assets/owl.theme.default.min.css"}
{require component="jquery" file="jquery.js"}
{require component="owl-carousel" file="owl.carousel.js"}

<div class="carousel-wrapper">
    <div class="logo-container hidden-md hidden-lg text-center">
        {$smarty.capture.logo}
    </div>
    <div id="HomePageCarousel" class="carousel">
        {foreach $images as $image}
            <div>
                {if $image.url}<a href="{if strpos($image.url, '/') === 0}{$GLOBALS.site_url}{/if}{$image.url}">{/if}
                    <img src="{$image.image.original.url}" class="img-responsive" title="{$image.caption}">
                    {if $image.url}</a>{/if}
            </div>
        {/foreach}
    </div>
</div>

<script type="text/javascript">
    var carouselBottomWidth;
    function changeBottomLineWidth($itemIndex)
    {
        var width = carouselBottomWidth * $itemIndex;
        $("<style type='text/css'> #HomePageCarousel .owl-stage-outer::after{ width: "+ width +"px }</style>").appendTo("head");
    }

    var slideCount = {$images|@count};
	$(function () {
        var carouselContainer = $("#HomePageCarousel");
        carouselContainer.owlCarousel({
			loop: {if $images|@count > 1}true{else}false{/if},
			autoplay: {if $images|@count > 1}true{else}false{/if},
			autoplaySpeed: 400,
			dots: true,
            nav: true,
            navText: ['<i class="fa fa fa-angle-left fa-4x hidden-xs"></i>', '<i class="fa fa-angle-right fa-4x hidden-xs"></i>'],
			animateOut: 'fadeOut',
			items: 1
		});

        carouselContainer.on('changed.owl.carousel', function(e){
            changeBottomLineWidth(e.item.index - 1);
        });


        carouselBottomWidth = carouselContainer.width() / slideCount;

        changeBottomLineWidth(1);


        $(window).resize(function(){
            carouselBottomWidth = carouselContainer.width() / slideCount;
        });

        if (getWindowWidth() > 767) {
            var carouselHeight = carouselContainer.find('.owl-stage-outer').height();

            carouselContainer.find('.owl-nav').css({
                top: ((carouselHeight - carouselContainer.find('.owl-nav > div').height()) / 2) + 'px',
                width: carouselContainer.width() + 'px'
            });

            $(window).resize(function () {
                carouselHeight = carouselContainer.find('.owl-stage-outer').height();
                carouselContainer.find('.owl-nav').css({
                    top: ((carouselHeight - carouselContainer.find('.owl-nav > div').height()) / 2) + 'px',
                    width: carouselContainer.width() + 'px'
                });
            });
        }
	});
</script>
