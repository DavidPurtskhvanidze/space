{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html?searchId={$listing_search.id}{/capture}
{i18n->getCurrentLanguage assign="currentLanguage"}
<div class="searchResultItem" {if $listing.feature_highlighted.exists && $listing.feature_highlighted.isTrue}style="background-color:{get_custom_setting id='color_for_highlighted_listing' theme=$GLOBALS.current_theme}"{/if}>
	<div class="itemPicture">
	<a href="{$listingUrl}" title="{$listingTitle}">
		{if $listing.pictures.numberOfItems > 0}
			{listing_image pictureInfo=$listing.pictures.collection.0 thumbnail=1}
		{else}
			<img src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]" class="noImageAvailable"/>
		{/if}
	</a>
	{if $listing.Sold.exists && $listing.Sold.isTrue}
		<div class="soldLabel"><span>[[SOLD]]</span></div>
	{else}
		{module name="listing_feature_sponsored" function="display_label" listing=$listing}
	{/if}
	</div>
	<div class="detailsAndItemControlsWrapper">
		<div class="details">
			<h1><a href="{$listingUrl}">{$listing}</a></h1>
			{if $listing.Price.exists && $listing.Price.isNotEmpty}
				<span class="fieldValue fieldValuePrice">
					{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]
				</span>
			{/if}
		</div>
	</div>
	{if $listingControlsTemplate}
		{include file=$listingControlsTemplate listingUrl=$listingUrl}
	{/if}
</div>
