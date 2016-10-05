{* listingControlsTemplate must be valid template name *}
{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html?searchId={$listing_search.id}{/capture}
{i18n->getCurrentLanguage assign="currentLanguage"}
<div class="searchResultItem" {if $listing.feature_highlighted.exists && $listing.feature_highlighted.isTrue}style="background-color:{get_custom_setting id='color_for_highlighted_listing' theme=$GLOBALS.current_theme}"{/if}>
	{if $listing.Sold.exists && $listing.Sold.isTrue}
		<div class="soldLabel"><span>[[SOLD]]</span></div>
	{else}
		{module name="listing_feature_sponsored" function="display_label" listing=$listing}
	{/if}
	<div class="pictures">
		<div class="fieldValue fieldValuePictures">
			<a href="{$listingUrl}">
				{if $listing.pictures.numberOfItems > 0}
					{listing_image pictureInfo=$listing.pictures.collection.0 alt="Listing #"|cat:$listing.id thumbnail=1}
				{else}
					<img src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]" class="noImageAvailable" />
				{/if}
			</a>
		</div>
	</div>
	<div class="itemHeader">
		<h2 class="fieldValue fieldValueTitle"><a href="{$listingUrl}">{$listing}</a></h2>
	</div>
	<ul class="additionalInfoblock">
		{if !$listing.Price.isEmpty}
			<li class="fieldValue fieldValuePrice {$listing.Price.type}"><span class="currencySign">{$GLOBALS.custom_settings.listing_currency}</span>[[$listing.Price]]</li>
		{/if}
		<li class="itemRating">{include file="rating.tpl"}</li>
		{if $listing.Condition.value == 'New'}
			<li class="fieldValue fieldValueCondition">[[$listing.Condition]]</li>
		{else}
			<li class="fieldValue fieldValueMileage">{if !$listing.Mileage.isEmpty}[[$listing.Mileage]] [[Miscellaneous!{$GLOBALS.settings.radius_search_unit}:raw]]{/if}</li>
		{/if}
	</ul>
	<div class="itemDetails">
		{if !$listing.Description.isEmpty}
			<div class="fieldValue fieldValueDescription">{$listing.Description.value|truncate:200|escape_user_input}</div>
		{/if}
	</div>
	<div class="itemOwnerInfo">
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
			<span class="fieldValue fieldValuePostedBy">
				{$postedByValue}
            </span>
			{if $listing.user_sid.value != 0 && $listing.user.PhoneNumber.isNotEmpty}
				<span class="fieldValue fieldValuePhoneNumber">
					<img class="linkIcon" src="{url file='main^icons/phone.png'}" alt="&#8226;" />
					{$listing.user.PhoneNumber}
				</span>
			{/if}
		{/if}
	</div>
	{include file=$listingControlsTemplate listingUrl=$listingUrl}
</div>
