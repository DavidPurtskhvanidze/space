{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
<div class="thumbnail">
	<div class="image">
	{module name="classifieds" function="display_quick_view_button" listing=$listing}
	{if $listing.pictures.numberOfItems > 0}
		<a href="{$listingUrl}">{listing_image pictureInfo=$listing.pictures.collection.0 alt="Listing #"|cat:$listing.id}</a>
	{else}
		<a href="{$listingUrl}"><img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable"/></a>
	{/if}
	</div>
	<div class="caption">
		<h3>
            <a href="{$listingUrl}" title='{$listing|strip_tags}'>{$listing|cat:""|strip_tags:false}</a>
            <span class="paragraph-end"></span>
        </h3>
        <p>{include file="miscellaneous^listing/price.tpl"}</p>
	</div>
</div>

