<div class="pictures">
    {if $listing.pictures.numberOfItems > 0}
        {require component="owl-carousel" file="assets/owl.carousel.css"}
        {require component="owl-carousel" file="assets/owl.theme.default.min.css"}
        {require component="jquery" file="jquery.js"}
        {require component="owl-carousel" file="owl.carousel.js"}


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
                    {listing_image pictureInfo=$picture thumbnail=1}
                </div>
            {/foreach}
        </div>
        <script>
            $(document).ready(function () {

                var images = $("#Images");
                var thumbs = $("#Thumbnails");

                images.owlCarousel({
                    center: true,
                    margin: 10,
                    items: 1,
                    autoHeight: true,
                    dots: false
                });

                thumbs.owlCarousel({
                    margin: 10,
                    dots: false,
                    nav: false,
                    responsive: {
                        0: {
                            items: 3
                        },
                        768: {
                            items: 8
                        },
                        979: {
                            items: 10
                        }
                    }
                });

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

                    thumbs.trigger('to.owl.carousel', [item, 300, true])
                    thumbs.find('.owl-item:eq(' + item + ')').click();
                });

            });

        </script>
    {else}
        <img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable" />
    {/if}
</div>

