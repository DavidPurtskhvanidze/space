{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html?searchId={$listing_search.id}{/capture}
<div class="thumbnail" {if $listing.feature_highlighted.exists && $listing.feature_highlighted.isTrue}style="background-color:{get_custom_setting id='color_for_highlighted_listing' theme=$GLOBALS.current_theme}"{/if}>
	<div class="image">
		{module name="classifieds" function="display_quick_view_button" listing=$listing}
		{assign var="number_of_pictures" value=$listing.pictures.numberOfItems}
		<a href="{$listingUrl}">
			{if $number_of_pictures > 0}
				{listing_image pictureInfo=$listing.pictures.collection.0 alt="Listing #"|cat:$listing.id}
			{else}
				<img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable"/>
			{/if}
			{if $listing.Sold.exists && $listing.Sold.isTrue}
				<div class="soldLabel overlay top left"><span>[[SOLD]]</span></div>
			{else}
				{module name="listing_feature_sponsored" function="display_label" listing=$listing}
			{/if}
			{if $listing.Price.exists && !$listing.Price.isEmpty}
				<span class="overlay bottom left">
					<span class="fieldValue fieldValuePrice money">{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]</span>
				</span>
			{/if}
		</a>
	</div>
	<div class="caption">
		<h3>
			<a href="{$listingUrl}" title='{$listing|strip_tags}'>{$listing}</a>
            <span class="paragraph-end"></span>
		</h3>

		<span class="fieldValue fieldValueListingRating">{include file="rating.tpl"}</span>
	</div>
</div>
