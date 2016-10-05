{extension_point name='modules\main\apps\FrontEnd\IListingDisplayAdditionDisplayer'}
{title}{$listing|cat:""|strip_tags:false}{/title}
{keywords}{$listing|cat:""|strip_tags:false|escape:"html"}{/keywords}
{description}{$listing.Description.value|strip_tags:false}{/description}
<div class="viewListingPage">
	<div class="breadcrumbs">
		{foreach from=$ancestors item="ancestor" name="ancestors_cycle"}
			<a href="{$GLOBALS.site_url}/browse{$ancestor.path}">[[$ancestor.caption]]</a> /
		{/foreach}
	</div>
	{if !empty($listing_search)}
		<div class="searchControls">
			<ul>
				<li class="backToResults">
					<a href="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}"><img class="linkIcon" src="{url file='main^icons/back_to_results.png'}" alt="&#8226;" /></a>&nbsp;
					<a href="{$GLOBALS.site_url}{$listing_search.search_results_uri}?action=restore&amp;searchId={$listing_search.id}">[[Back to Results]]</a>
				</li>
				{if $listing_search.prev}
					<li class="previous">
						<a href="{page_path id='listing'}{$listing_search.prev}/?searchId={$listing_search.id}">[[Previous]]</a>
					</li>
				{/if}
				{if $listing_search.next}
					<li class="next">
						<a href="{page_path id='listing'}{$listing_search.next}/?searchId={$listing_search.id}">[[Next]]</a>
					</li>
				{/if}
			</ul>
		</div>
	{/if}
	{display_success_messages}
	{if $messages}{include file="message.tpl"}{/if}
	<h1>
		{if $listing.Sold.exists && $listing.Sold.isTrue}
			<span class="fieldValue fieldValueSold">[[SOLD]]!</span>
		{/if}
		{$listing}
		{if $listing.Price.exists}
			<span class="fieldValue fieldValuePrice {if !$listing.Price.isEmpty}{$listing.Price.type}{/if}">{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]</span>
		{/if}
	</h1>

	{include file="listing_images.tpl" listing=$listing}

	<div class="listingControlsAndOwnerInfoAndAdditionalInfoWrapper">
		{include file="category_templates/display/social_network_buttons.tpl"}
		<span class="fieldValue fieldValueListingRating">{display property=ListingRating}</span>
		{include file="category_templates/display/listing_details_listing_controls.tpl"}
		<div class="ownerInfo">
			<h2>[[Seller Info]]</h2>
			{include file="author_info.tpl" listing=$listing}
		</div>
		<div class="additionalInfo">
			<span class="fieldCaption fieldCaptionActivationDate">[[FormFieldCaptions!Date Posted]]:</span> <span class="fieldValue fieldValueActivationDate">[[$listing.activation_date]]</span><br />
			<span class="fieldValue fieldValueViews">{$listing.views} [[views]].</span>
		</div>
		{module name='google_map' function='display_map' address=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State default_latitude=$listing.ZipCode.latitude default_longitude=$listing.ZipCode.longitude}
	</div>

	<div class="overview">
		<ul>
			<li>
				<div class="fieldValue fieldValueDescription">{$listing.Description}</div>
			</li>
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
						'Sold'
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
                <a onclick='return openDialogWindow("[[Watch a video]]", this.href, 650)'
                   href="{page_path id='video_player'}?listing_id={$listing.id}&raw_output=1">
                    [[Watch a video]]
                </a>
            </span>
		{/if}
	</div>

	{module name="listing_feature_youtube" function="display_youtube" listing=$listing width="565px" height="318px"}

	{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
	{assign var='current_uri' value=$current_uri|urlencode}
	{module name="listing_comments" function="display_listing_details_comments" listing=$listing returnBackUri=$current_uri}
    {module name="facebook_comments" function="display_comments" url="{page_url id='listing'}"|cat:$listing.id}
</div>
