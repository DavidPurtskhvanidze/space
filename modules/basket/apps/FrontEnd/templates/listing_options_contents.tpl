<div class="listingOptionsContents">
	{$listingCount = count($itemsGroupedByListing)}
	{foreach from=$itemsGroupedByListing item="listingOptions" key="listing_sid"}
		{$listing=$listingOptions.listing}
		{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
    <div class="searchResultItemWrapper{if $listingOptions@last} last{/if}{if $listingOptions@first} first{/if}">
		<div class="searchResultItem">
			<div class="pictures">
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
			<div class="details">
				<h2>
					{if !is_null($listing)}
						<a href="{$listingUrl}" class="respondInMainWindow">{$listing}</a>
					{else}
						[[Listing #$listing_sid (deleted)]]
					{/if}
				</h2>
				<ul class="listingOptions">
					{foreach from=$listingOptions.options item="option"}
						<li class="listingOption {$option.option_id}">
							<span class="caption">[[{$option.option_name}]]</span>
							<span class="amount">{display_price_with_currency amount=$option.price payment_method=$payment_method}</span>
						</li>
					{/foreach}
					{if $listingCount > 1}
						<li class="subtotal">
							<span class="caption">[[Subtotal]]</span>
							<span class="amount">{display_price_with_currency amount=$listingOptions.totalOptionPrice payment_method=$payment_method}</span>
						</li>
					{/if}
				</ul>
				{$totalPrice=$totalPrice+$listingOptions.totalOptionPrice}
			</div>
		</div>
    </div>
	{/foreach}
	<div class="total">
		<span class="caption">[[Total]]</span>
		<span class="amount">{display_price_with_currency amount=$totalPrice payment_method=$payment_method}</span>
	</div>

</div>
