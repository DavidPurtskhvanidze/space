{title}{$listing|cat:""|strip_tags:false}{/title}
{keywords}{$listing|cat:""|strip_tags:false|escape:"html"}{/keywords}
{description}{$listing.Description.value|strip_tags:false}{/description}
{assign var="uriParametersPart" value=$listing.id|cat:"/"}
{if !empty($listing_search)}
	{assign var="uriParametersPart" value=$uriParametersPart|cat:"?searchId="|cat:$listing_search.id}
{/if}
<div class="listingDetails">
	<div class="summary">
		<h1>{if $listing.Sold.exists && $listing.Sold.isTrue}<span class="fieldValue fieldValueSold">[[SOLD]]!</span> {/if}{$listing}</h1>
		{include file="category_templates/display/social_network_buttons.tpl"}
		<span class="fieldValue fieldValueListingRating">{display property="ListingRating"}</span><br />
		{if $listing.Price.exists && !$listing.Price.isEmpty}
			<span class="fieldCaption fieldCaptionPrice">[[$form_fields.Price.caption]]:</span> <span class="fieldValue fieldValuePrice">{$GLOBALS.custom_settings.listing_currency}{display property="Price"}</span><br />
		{/if}
		{if $listing.pictures.numberOfItems}
			<span class="fieldValue fieldValuePictures">
				{if $listing.pictures.numberOfItems>1}
					{assign var="listingPicturesUri" value={page_path id='listing_pictures'}|cat:$uriParametersPart}
					<a href="{$listingPicturesUri}">
						{listing_image pictureInfo=$listing.pictures.collection.0}
					</a>
					<span>
						<a href="{$listingPicturesUri}">[[All pictures]]</a>
					</span>
				{else}
					{listing_image pictureInfo=$listing.pictures.collection.0}
				{/if}
			</span>
		{/if}
		<span class="fieldValue fieldValueDescription">{$listing.Description}</span><br />
		{assign var="fieldsToExclude"
			value=[
					'sid',
					'id',
					'active',
					'pictures',
					'views',
					'keywords',
					'activation_date',
					'expiration_date',
                    'meta_keywords',
                    'meta_description',
                    'page_title',
					'feature_featured',
					'feature_highlighted',
					'feature_slideshow',
					'feature_youtube',
					'feature_youtube_video_id',
					'feature_sponsored',
					'moderation_status',
					'type',
					'category_sid',
					'category',
					'user',
					'user_sid',
					'username',
					'package',
					'listing_package',
					'Title',
					'Description',
					'Price',
					'Video',
					'Sold',
					'ListingRating'
			]}
		{foreach from=$form_fields item=form_field}
			{* The following code excludes some fields from being displayed *}
			{if !in_array($form_field.id, $fieldsToExclude)}
			<span class="fieldCaption fieldCaption{$form_field.id}">[[$form_field.caption]]:</span> <span class="fieldValue fieldValue{$form_field.id}">{display property=$form_field.id}</span><br />
			{/if}
		{/foreach}
	</div>
	{include file="classifieds^category_templates/display/subpages_links.tpl" currentPageId="details" listing=$listing}
	{include file="classifieds^category_templates/display/search_results_controls.tpl"}
</div>
