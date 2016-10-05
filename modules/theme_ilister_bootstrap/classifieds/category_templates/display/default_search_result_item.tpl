{* listingControlsTemplate must be valid template name *}
{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html?searchId={$listing_search.id}{/capture}
<div class="thumbnail" {if $listing.feature_highlighted.exists && $listing.feature_highlighted.isTrue}style="background-color:{get_custom_setting id='color_for_highlighted_listing' theme=$GLOBALS.current_theme}"{/if}>
	<div class="row">
		<div class="col-md-4">
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
								<span class="fieldValue fieldValuePrice {if !$listing.Price.isEmpty}{$listing.Price.type}{/if}"><span class="currencySign">{$GLOBALS.custom_settings.listing_currency}</span>[[$listing.Price]]</span>
						</span>
					{/if}
				</a>
			</div>
		</div>
		<div class="col-md-8">
			<div class="caption">
				<div class="row">
					<div class="col-xs-8">
						<h4>
							<a href="{$listingUrl}">{$listing}</a>
						</h4>
					</div>
					<div class="col-xs-4">
						{include file=$listingControlsTemplate listingUrl=$listingUrl}
					</div>

				</div>
				<span class="fieldValue fieldValueListingRating">{include file="rating.tpl"}</span>

				{if !$listing.Description.isEmpty}<p>{$listing.Description.value|truncate:200|escape_user_input}</p>{/if}

				<div>
					
					{*Assigning authors name to $postedByValue variable*}
					{capture assign="postedByValue"}
						{if $listing.user_sid.value == 0}
				            [[Administrator]]
							{elseif $listing.user.AgencyName.exists}
							{$listing.user.AgencyName}
							{else}
							{$listing.user.FirstName} {$listing.user.LastName}
						{/if}
					{/capture}
					{$postedByValue = $postedByValue|trim}
					{if !empty($postedByValue)}
						<span class="fieldValue fieldValuePostedBy">
							{$postedByValue}
			            </span>
					{/if}
					{if $listing.user_sid.value != 0}
						<span class="fieldValue fieldValuePhoneNumber">
							<span class="glyphicon glyphicon-earphone"></span> {$listing.user.PhoneNumber}
						</span>
					{/if}
				</div>

				{if strcasecmp($listing.moderation_status.rawValue, 'REJECTED') == 0}
					<div class="moderation-status">
						<span class="label label-danger">[[$listing.moderation_status.rawValue]]</span>
					</div>
				{elseif strcasecmp($listing.moderation_status.rawValue, 'PENDING') == 0}
					<div class="moderation-status">
						<span class="label label-info">[[$listing.moderation_status.rawValue]]</span>
					</div>
				{/if}

			</div>
		</div>
	</div>
</div>
