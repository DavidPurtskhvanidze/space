<table class="listingImageAndListingControlAndAdditionalInfoWrapper">
	<tr>
		<td class="listingImage">
			{if $listing.pictures.numberOfItems > 0}
				{assign var="mainPicture" value=$listing.pictures.collection.0}
				<img src="{$mainPicture.file.picture.url}" alt="{$mainPicture.caption}" />
			{else}
				<img src="{url file='main^no_image_available_big.png'}" alt="" class="noImageAvailable" />
			{/if}
		</td>
		<td class="cellPadding">
			&nbsp;
		</td>
		<td class="listingControlAndAdditionalInfoWrapper">
			{include file="category_templates/display/listing_details_listing_controls.tpl"}
			<div class="additionalInfo">
				<span class="fieldCaption fieldCaptionActivationDate">[[FormFieldCaptions!Date Posted]]:</span> <span class="fieldValue fieldValueActivationDate">[[$listing.activation_date]]</span><br />
				<span class="fieldValue fieldValueViews">{$listing.views} [[views]]</span>
			</div>
		</td>
	</tr>
</table>
<dl class="listingFields">
	<dt class="listingField odd Description">[[FormFieldCaptions!Description]]&nbsp;</dt>
	<dd class="listingField odd Description">[[$listing.Description]]&nbsp;</dd>
	<dt class="listingField even Id">[[Listing #]]&nbsp;</dt>
	<dd class="listingField even Id">{$listing.id}&nbsp;</dd>
	{assign var="fieldsToExclude"
		value=[
				'sid',
				'id',
				'active',
				'pictures',
				'views',
				'auto_extend',
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
				'ListingRating',
				'Video',
				'Title',
				'Description',
				'Price',
				'Sold',
				'numberOfComments'
		]}
	{foreach from=$form_fields item=form_field}
		{* The following code excludes some fields from being displayed *}
		{if !in_array($form_field.id, $fieldsToExclude) && !$listing[$form_field.id].isEmpty}
			{cycle assign="oddOrEvenClass" values="odd,even"}
			<dt class="listingField {$oddOrEvenClass} {$form_field.id}">[[$form_field.caption]]&nbsp;</dt>
			<dd class="listingField {$oddOrEvenClass} {$form_field.id}">{display property=$form_field.id}&nbsp;</dd>
		{/if}
	{/foreach}
</dl>
