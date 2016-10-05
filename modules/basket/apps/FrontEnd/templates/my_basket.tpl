{include file="miscellaneous^dialog_window.tpl"}
{$return_uri = "&return_uri=$return_uri"}
<div class="myBasket">
	<h1>[[My Basket]]</h1>
	{display_success_messages}

	<div class="hint">
		{$url = "{page_path id='user_payments'}"}
		[[Please note that pending and failed transactions are listed on the <a href="$url">My Transactions</a> page, where you can complete them if necessary.]]
	</div>

{if $displayLinkToMyBasket}
	{$url = "{page_path id='basket'}"}
	{$sid = $REQUEST.listing_sid.equal}
    <div class="basketIsFilteredMessage">[[The basket is filtered by the listing #$sid. <a href="$url">Show all basket</a>]]</div>
{/if}

{if $itemsGroupedByListings}
	{$totalPrice=0}
	<form action="{page_path module='basket' function='buy_items'}" method="post">
        {CSRF_token}
		{$listingCount = count($itemsGroupedByListings)}
		{foreach from=$itemsGroupedByListings item="listingOptions"}
			{$listing=$listingOptions.listing}
			{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
            <div class="searchResultItemWrapper{if $listingOptions@last} last{/if}">
	            <div class="searchResultItem">
                    <div class="picturesAndLinksWrapper">
                        <div class="pictures">
						<span class="fieldValue fieldValuePictures">
						<a href="{$listingUrl}" title="{$listingTitle}">
							{if $listing.pictures.numberOfItems > 0}
								{listing_image pictureInfo=$listing.pictures.collection.0 thumbnail="1"}
								{else}
                                <img src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]"
                                     class="noImageAvailable"/>
							{/if}
                        </a>
						</span>

                        </div>
                        <div class="linksSection">
                            <ul>
			                    {if $listingOptions.buyableListingPackageOptions|@count > 0}
                                    <li>
                                        <a class="control addMoreOptions"
                                           href="{page_path module='basket' function='add_options'}?listing_sid={$listing.sid}{$return_uri}"
                                           title="[[Add More Options:raw]]">[[Add options]]</a>
                                    </li>
			                    {/if}
	                            <li>
									<a class="control removeListingOptions"
										href="{page_path module='basket' function='remove_item'}?listing_sid={$listing.sid}{$return_uri}"
										title="[[Remove Listing From Basket]]">[[Remove]]</a>
	                            </li>
                            </ul>
                        </div>
                    </div>
					<div class="details">
						<h2><a href="{$listingUrl}" class="listingCaption">{$listing}</a></h2>
						<ul class="listingOptions">
							{foreach from=$listingOptions.options item="option"}
								<li class="listingOption {$option.id}">
									<input type="hidden" name="listing_options[]" value="{$option.sid}">
									<span class="caption">[[{$option.name}]]</span>
									<span class="amount">{display_price_with_currency amount=$option.price}</span>
									{if $option.isRemovable}
										<span class="control remove">
											<a href="{page_path module='basket' function='remove_item'}?sid={$option.sid}{$return_uri}" class="removeOptionLink" title="[[Remove Option]]" ></a>
										</span>
									{/if}
								</li>
							{/foreach}
							{if $listingCount > 1}
								<li class="subtotal">
									<span class="caption">[[Subtotal]]</span>
									<span class="amount">{display_price_with_currency amount=$listingOptions.totalOptionPrice}</span>
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
			<span class="amount">{display_price_with_currency amount=$totalPrice}</span>
		</div>
		<div class="submitButtonWrapper">
            <input type="submit" value="[[Buy]]" class="submit"/>
		</div>
	</form>
	{else}

	{capture assign="addListingLink"}{page_path id='listing_add'}{/capture}
	{capture assign="myListingsLink"}{page_path id='user_listings'}{/capture}
    [[Your basket is empty. Please <a href="$addListingLink">create a new listing</a>, or add more options to <a href="$myListingsLink">your existing ads</a> to promote them better.]]
{/if}
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('a.control.addMoreOptions').click(function (){
			return openDialogWindow(this.title, this.href, 360, true);
		});
	});
</script>
