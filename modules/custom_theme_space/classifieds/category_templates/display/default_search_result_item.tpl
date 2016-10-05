{* listingControlsTemplate must be valid template name *}
{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html?searchId={$listing_search.id}{/capture}
{capture assign="postedByValue"}
	{if $listing.user_sid.value == 0}
		[[Administrator]]
	{elseif $listing.user.AgencyName.exists}
		{$listing.user.AgencyName}
	{else}
		{$listing.user.FirstName} {$listing.user.LastName}
	{/if}
{/capture}
<div class="listing-box" {if $listing.feature_highlighted.exists && $listing.feature_highlighted.isTrue}style="background-color:{get_custom_setting id='color_for_highlighted_listing' theme=$GLOBALS.current_theme}"{/if}>
	<div class="row">
		<div class="col-md-3">
			<div class="listing-box-image">
				{module name="classifieds" function="display_quick_view_button" listing=$listing}
				{if $listing.pictures.numberOfItems > 0}
					{listing_image pictureInfo=$listing.pictures.collection.0 alt="Listing #"|cat:$listing.id}
				{else}
					<img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]" class="noImageAvailable img-responsive"/>
				{/if}
				{if $listing.Sold.exists && $listing.Sold.isTrue}
					<div class="sold-label"><span>[[SOLD]]</span></div>
				{else}
					{module name="listing_feature_sponsored" function="display_label" listing=$listing}
				{/if}
				<a class="overlay-img wb" href="{$listingUrl}"></a>
			</div>
		</div>
		<div class="col-md-9">
			<div class="listing-box-caption">
				<div class="row">
					<div class="{if $listing.Price.exists && !$listing.Price.isEmpty}col-md-8{else}col-md-12{/if}">
						<div class="listing-box-caption-text {if $listing.Price.exists && !$listing.Price.isEmpty}desc-tile{/if}">
							<h4>
								<a href="{$listingUrl}" title="{$listing|cat:""|strip_tags:false}">
									{$listing|cat:""|strip_tags:false}
								</a>
								<span class="listing-category">
									{$listing.category_sid}
								</span>
							</h4>
						</div>
					</div>
					{if $listing.Price.exists && !$listing.Price.isEmpty}
						<div class="col-md-4">
							<div class="listing-box-caption-price">
								<span class="listing-money">
									{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]
								</span>
							</div>
						</div>
					{/if}
					<div class="col-md-12">
						{if !$listing.Description.isEmpty}
							<p class="listing-box-caption-description">{$listing.Description.value|truncate:200|escape_user_input}</p>
						{/if}
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-4">
						{$postedByValue = $postedByValue|trim}
						{if !empty($postedByValue)}
							<span class="fieldValue fieldValuePostedBy">
							{$postedByValue}
			            </span>
						{/if}
					</div>
					<div class="col-md-4 text-center">
						{if $listing.user_sid.value != 0}
							<span class="fieldValue fieldValuePhoneNumber">
							<span class="glyphicon glyphicon-earphone"></span> {$listing.user.PhoneNumber}
						</span>
						{/if}
					</div>
					<div class="col-md-4">
						{include file=$listingControlsTemplate listingUrl=$listingUrl}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

