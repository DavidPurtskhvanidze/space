<div class="viewListingPage">
	<div class="breadcrumbs">
		{foreach from=$ancestors item="ancestor" name="ancestors_cycle"}
			<a href="{$GLOBALS.site_url}/browse{$ancestor.path}">[[$ancestor.caption]]</a> /
		{/foreach}
	</div>
	{if !empty($listing_search)}
		<div class="searchResultControls">
			{if !empty($listing_search)}
				<a href="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}"><img class="linkIcon" src="{url file='main^icons/back_to_results.png'}" alt="&#8226;" /></a>&nbsp;
				<a href="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}">[[Back to Results]]</a>
			{/if}
			&nbsp;&nbsp;&nbsp;
			{if $listing_search.prev}
				<a href="{page_path id='listing'}{$listing_search.prev}/?searchId={$listing_search.id}">&lt;[[Previous]]</a> |
			{/if}
			{if $listing_search.next}
				<a href="{page_path id='listing'}{$listing_search.next}/?searchId={$listing_search.id}">[[Next]]&gt;</a>
			{/if}
		</div>
	{/if}
	{display_success_messages}
	{if $messages}{include file="message.tpl"}{/if}
	
	<h1>{$listing}</h1>
	
	{include file="listing_images.tpl" listing=$listing}

	<div class="listingControlsAndOwnerInfoAndAdditionalInfoWrapper">
		{include file="category_templates/display/social_network_buttons.tpl"}
		{include file="category_templates/display/listing_details_listing_controls.tpl"}
		<div class="ownerInfo">
			<h2>[[Seller Info]]</h2>
			{include file="author_info.tpl" listing=$listing}
		</div>
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
		{if $listing.Video.uploaded}
			<span class="fieldValue fieldValueVideo">
                <a onclick='return openDialogWindow("[[Watch a video]]", this.href, 560)'
                   href="{page_path id='video_player'}?listing_id={$listing.id}&raw_output=1">[[Watch a video]]</a></span>
		{/if}
	</div>

	{module name='google_map' function='display_map' address=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State default_latitude=$listing.ZipCode.latitude default_longitude=$listing.ZipCode.longitude}
	{module name="listing_feature_youtube" function="display_youtube" listing=$listing}

	{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
	{assign var='current_uri' value=$current_uri|urlencode}
	{module name="listing_comments" function="display_listing_details_comments" listing=$listing returnBackUri=$current_uri}
</div>
