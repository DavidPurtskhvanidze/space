{require component="owl-carousel" file="assets/owl.carousel.css"}
{require component="owl-carousel" file="assets/owl.theme.default.min.css"}
{require component="jquery" file="jquery.js"}
{require component="owl-carousel" file="owl.carousel.js"}
<a	onclick='return openDialogWindow("Quick View Listing #" + "{$listing.id}", this.href, 1087, true)'
      href="{page_path id='listing_quick_view'}{$listing.id}/"
      class="quick-view btn btn-orange">
	[[Quick View]]
</a>
{include file="miscellaneous^dialog_window.tpl"}
