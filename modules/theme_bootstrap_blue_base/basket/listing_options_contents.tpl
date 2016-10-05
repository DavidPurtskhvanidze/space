<div class="listingOptionsContents">
	{$listingCount = count($itemsGroupedByListing)}
	{foreach from=$itemsGroupedByListing item="listingOptions" key="listing_sid"}
		{$listing=$listingOptions.listing}
		{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
    <div class="searchResultItemWrapper{if $listingOptions@last} last{/if}{if $listingOptions@first} first{/if}">
		<div class="row">
            <div class="col-sm-3">
				<span class="fieldValue fieldValuePictures">
					{if !is_null($listing)}
						<a href="{$listingUrl}" class="respondInMainWindow">
							{if $listing.pictures.numberOfItems > 0}
								{listing_image pictureInfo=$listing.pictures.collection.0 thumbnail="1"}
								{else}
								<img src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]" class="noImageAvailable"/>
							{/if}
						</a>
					{else}
						<img src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]" class="noImageAvailable"/>
					{/if}
				</span>
			</div>

            <div class="col-sm-9">
				<h4>
					{if !is_null($listing)}
						<a href="{$listingUrl}" class="respondInMainWindow">{$listing}</a>
					{else}
						[[Listing #$listing_sid (deleted)]]
					{/if}
				</h4>
				<ul class="list-unstyled">
					{foreach from=$listingOptions.options item="option"}
						<li class="listingOption {$option.option_id}">
                            <div class="row">
                                <div class="col-sm-6">
                                    [[{$option.option_name}]]
                                </div>
                                <div class="col-sm-6">
                                    {display_price_with_currency amount=$option.price payment_method=$payment_method}
                                </div>
                            </div>
						</li>
					{/foreach}
					{if $listingCount > 1}
						<li class="subtotal">
                            <div class="row">
                                <div class="col-sm-6">
                                    [[Subtotal]]
                                </div>
                                <div class="col-sm-6">
                                    {display_price_with_currency amount=$listingOptions.totalOptionPrice payment_method=$payment_method}
                                </div>
                            </div>
						</li>
					{/if}
				</ul>
				{$totalPrice=$totalPrice+$listingOptions.totalOptionPrice}
			</div>
		</div>
    </div>
	{/foreach}

	<div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
            <div class="row">
                <span class="col-sm-6"><strong>[[Total]]</strong></span>
                <span class="col-sm-6">
                    <strong>{display_price_with_currency amount=$totalPrice payment_method=$payment_method}</strong>
                </span>
            </div>
        </div>
	</div>
</div>
