<div class="viewListingPage">
	<div class="breadcrumbs">
		{foreach from=$ancestors item="ancestor" name="ancestors_cycle"}
			<a href="{$GLOBALS.site_url}/browse{$ancestor.path}">[[$ancestor.caption]]</a> /
		{/foreach}
	</div>
		{display_success_messages}
	{if $messages}{include file="message.tpl"}{/if}
	
	<h1>{$listing}</h1>
	
	{include file="listing_images.tpl" listing=$listing}

	<div class="listingControlsAndOwnerInfoAndAdditionalInfoWrapper">
		<div class="additionalInfo">
			<span class="fieldCaption fieldCaptionViews">[[FormFieldCaptions!Listing Views]]:</span> <span class="fieldValue fieldValueViews">{$listing.views}</span><br />
			<span class="fieldCaption fieldCaptionActivationDate">[[FormFieldCaptions!Date Posted]]:</span> <span class="fieldValue fieldValueViews">[[$listing.activation_date]]</span>
		</div>
	</div>

	<div class="overview">
		<ul>
			<li>
				<span class="fieldCaption fieldCaptionId">[[Listing #]]</span>
				<span class="fieldValue fieldValueId">{$listing.id}</span>
			</li>
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
						'feature_sponsored',
						'feature_youtube_video_id',
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
						'Video'
				]}
			{foreach from=$form_fields item=form_field}
				{* The following code excludes some fields from being displayed *}
				{if !in_array($form_field.id, $fieldsToExclude) and !$listing[$form_field.id].isEmpty}
					<li>
						<span class="fieldCaption fieldCaption{$form_field.id}">[[$form_field.caption]]:</span>
						<span class="fieldValue fieldValue{$form_field.id}">{display property=$form_field.id}</span>
					</li>
				{/if}
			{/foreach}
		</ul>
	</div>
</div>
