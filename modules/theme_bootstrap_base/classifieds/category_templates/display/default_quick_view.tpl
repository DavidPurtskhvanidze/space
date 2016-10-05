{extension_point name='modules\main\apps\FrontEnd\IListingDisplayAdditionDisplayer'}
<div class="listing-details quick-view-page">
	{display_success_messages}
	{if $messages}{include file="message.tpl"}{/if}
	<div class="row">
		<div class="quick-view-header">
			{if $listing.Sold.exists && $listing.Sold.isTrue}
				<span class="fieldValue fieldValueSold">[[SOLD]]!</span>
			{/if}
			{$listing}
			{if ($listing.Price.exists || $listing.Rent.exists) && (!empty($listing.Price.value) || !empty($listing.Rent.value))}
				{strip}
					<span class="fieldValue fieldValuePrice money">
	                   <span class="currencySign">{$GLOBALS.custom_settings.listing_currency}</span>
						{if $listing.Price.exists}
							[[$listing.Price]]
						{elseif $listing.Rent.exists}
							[[$listing.Rent]]
						{/if}
                    </span>

				{/strip}
			{/if}
		</div>
		<div class="col-sm-7 listing-details">
			<div class="text-center">
                {if $listing.pictures.numberOfItems > 0}
                    {listing_image pictureInfo=$listing.pictures.collection[0] type="big"}
                {else}
                    <img src="{url file='main^no_image_available_quick_view.jpg'}" alt="[[No photos:raw]]" class="noImageAvailable" />
                {/if}
            </div>
		</div>
		<div class="col-sm-5">
			<dl class="dl-horizontal">
				<dt class="fieldCaption">[[Votes]]:</dt>
				<dd class="fieldValue">{display property=ListingRating template='rating_responsive_quick_view.tpl'}&nbsp;</dd>
				{foreach $magicFields->excludeSystemFields()->excludeByType('text', 'rating', 'video', 'calendar', 'multilist', 'money') as $fieldId => $formField}
					<dt class="fieldCaption {$fieldId}">[[$formField.caption]]:</dt>
					<dd class="fieldValue {$fieldId}">{display property=$fieldId}&nbsp;</dd>
				{/foreach}

				<dt class="fieldCaption views">[[FormFieldCaptions!Listing Views]]:</dt>
				<dd class="fieldValue views">[[$listing.views]]&nbsp;</dd>

				<dt class="fieldCaption views">[[FormFieldCaptions!Date Posted]]:</dt>
				<dd class="fieldValue views">[[$listing.activation_date]]&nbsp;</dd>
				<br/>
				<dt class="fieldCaption">
				</dt>
				<dd class="fieldValue">
					{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
					<a href="{$listingUrl}" class="respondInMainWindow btn btn-primary" title='{$listing|strip_tags}'>[[View Details]]</a>
				</dd>
			</dl>

		</div>
	</div>
</div>


