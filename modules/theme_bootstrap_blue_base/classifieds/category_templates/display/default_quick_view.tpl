{extension_point name='modules\main\apps\FrontEnd\IListingDisplayAdditionDisplayer'}
{strip}
<div class="quick-view-page">
	{display_success_messages}
	{if $messages}{include file="message.tpl"}{/if}
	<div class="row top-bar">
		<div class="col-sm-7">
            {if $listing.Sold.exists && $listing.Sold.isTrue}
                <div class="sticker">
                    <span class="fieldValue fieldValueSold">[[SOLD]]!</span>
                </div>
            {/if}
			{if $listing.pictures.numberOfItems > 0}
                <div class="center">
                    {listing_image pictureInfo=$listing.pictures.collection[0] type="big"}
                </div>
            {else}
                <div class="image center">
                    <img src="{url file='main^no_image_available_quick_view.jpg'}" alt="[[No photos:raw]]" class="noImageAvailable" />
                </div>
			{/if}
		</div>
		<div class="col-sm-5 text-center">
            <h1 class="h2">
                {$listing}
            </h1>
            <div class="price">
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
		</div>
	</div>
    <div class="row bottom-bar">
        <div class="col-sm-12">
            <div class="row listing-common-details">

                <div class="listing-common-details">
                    <div class="col-xs-12 col-sm-8 col-md-6">
                        <span class="fieldCaption">[[Votes]]:</span>
                        <span class="fieldValue">{display property=ListingRating template='rating_responsive_quick_view.tpl'}</span>
                    </div>

                    {foreach $magicFields->excludeSystemFields()->excludeByType('text', 'rating', 'video', 'calendar', 'multilist', 'money') as $fieldId => $formField}
                        <div class="col-xs-12 col-sm-8 col-md-6">
                            <span class="fieldCaption {$fieldId}">[[$formField.caption]]:</span>
                            <span class="fieldValue {$fieldId}">{display property=$fieldId}</span>
                        </div>
                    {/foreach}
                    <div class="col-xs-12 col-sm-8 col-md-6">
                        <span class="fieldCaption views">[[FormFieldCaptions!Listing Views]]:</span>
                        <span class="fieldValue views">[[$listing.views]]</span>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-6">
                        <span class="fieldCaption views">[[FormFieldCaptions!Date Posted]]:</span>
                        <span class="fieldValue views">[[$listing.activation_date]]</span>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-xs-12 text-center">
            {capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
            <a href="{$listingUrl}" class="respondInMainWindow btn h5 btn-orange" title='{$listing|strip_tags}'>[[View Details]]</a>
        </div>
    </div>
</div>
{/strip}


