{* listingControlsTemplate must be valid template name *}
{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html?searchId={$listing_search.id}{/capture}
<div class="searchResultItem" {if $listing.feature_highlighted.exists && $listing.feature_highlighted.isTrue}style="background-color:{get_custom_setting id='color_for_highlighted_listing' theme=$GLOBALS.current_theme}"{/if}>
	{if $listing.Sold.exists && $listing.Sold.isTrue}
		<div class="soldLabel"><span>[[SOLD]]</span></div>
	{else}
		{module name="listing_feature_sponsored" function="display_label" listing=$listing}
	{/if}
	<div class="pictures">
		<span class="fieldValue fieldValuePictures">
			{assign var="number_of_pictures" value=$listing.pictures.numberOfItems}
			<a href="{$listingUrl}" title="{$listingTitle}">
				{if $number_of_pictures > 0}
					{listing_image pictureInfo=$listing.pictures.collection.0 thumbnail=1}
				{else}
					<img src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]" class="noImageAvailable"/>
				{/if}
			</a>
		</span>
	</div>
	<div class="additionalDetailWrapper">
		{if !$listing.activation_date.isEmpty}
			<span class="fieldValue fieldValueActivationDate">[[$listing.activation_date]]</span>
		{/if}
		<span class="fieldValue fieldValueListingRating">{include file="rating.tpl"}</span>
	</div>
	<div class="details">
		<h2><a href="{$listingUrl}">{$listing}</a></h2>

		{*Assigning authors name to $postedByValue variable*}
		{capture assign="postedByValue"}
			{if $listing.user_sid.value == 0 }
	            [[Administrator]]
				{elseif $listing.user.DealershipName.exists}
				{$listing.user.DealershipName}
				{else}
				{$listing.user.FirstName} {$listing.user.LastName}
			{/if}
		{/capture}
		{$postedByValue = $postedByValue|trim}
		{if !empty($postedByValue)}
			<span class="fieldValue fieldValuePostedBy">{$postedByValue}</span>
		{/if}
	</div>
	{include file=$listingControlsTemplate listingUrl=$listingUrl}
</div>
