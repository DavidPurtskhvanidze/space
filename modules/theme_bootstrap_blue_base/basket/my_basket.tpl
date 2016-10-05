{include file="miscellaneous^dialog_window.tpl"}
<div class="myBasket">
    <div class="container">
        {$return_uri = "&return_uri=$return_uri"}
        <h1 class="page-title">[[My Basket]]</h1>
        <div class="space-20"></div>
        {display_success_messages}
        <div class="space-20"></div>

        <div class="alert bg-info">
            {$url = "{page_path id='user_payments'}"}
            [[Please note that pending and failed transactions are listed on the <a href="$url">My Transactions</a> page, where you can complete them if necessary.]]
        </div>

        {if $displayLinkToMyBasket}
            {$url = "{page_path id='basket'}"}
            {$sid = $REQUEST.listing_sid.equal}
            <div class="basketIsFilteredMessage">[[The basket is filtered by the listing #$sid. <a href="$url">Show all basket</a>]]</div>
        {/if}
    </div>
    {if $itemsGroupedByListings}
	{$totalPrice=0}
        <div class="searchResults bg-grey">
            <div class="space-20"></div>
            <div class="space-20"></div>
            <div class="space-20"></div>
            <div class="container">
                <form action="{page_path module='basket' function='buy_items'}" method="post">
                    {CSRF_token}
                    {$listingCount = count($itemsGroupedByListings)}
                    <div class="row">
                        {foreach from=$itemsGroupedByListings item="listingOptions"}
                            {$listing=$listingOptions.listing}
                            {capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
                            <div class="col-xs-12 col-sm-6 col-md-4 {if $listingOptions@last} last{/if}">
                                <div class="item">
                                    <div class="thumbnail">
                                        <div class="image">
                                            <a href="{$listingUrl}" title="{$listingTitle}">
                                                {if $listing.pictures.numberOfItems > 0}
                                                    {listing_image pictureInfo=$listing.pictures.collection.0}
                                                {else}
                                                    <img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]"
                                                         class="noImageAvailable"/>
                                                {/if}
                                            </a>
                                        </div>
                                        <div class="item-detail">
                                            <div class="row">
                                                <div class="col-sm-9 col-xs-8">
                                                    <h3><a href="{$listingUrl}" class="listingCaption h5">{$listing}</a></h3>
                                                </div>
                                                <div class="col-sm-3 col-xs-4">
                                                    <div class="linksSection text-right">
                                                        <ul class="list-inline">
                                                            {if $listingOptions.buyableListingPackageOptions|@count > 0}
                                                                <li>
                                                                    <a class="control addMoreOptions"
                                                                       href="{page_path module='basket' function='add_options'}?listing_sid={$listing.sid}{$return_uri}"
                                                                       title="[[Add More Options:raw]]"><i class="glyphicon glyphicon-plus"></i></a>
                                                                </li>
                                                            {/if}
                                                            <li>
                                                                <a class="control removeListingOptions text-danger"
                                                                   href="{page_path module='basket' function='remove_item'}?listing_sid={$listing.sid}{$return_uri}"
                                                                   title="[[Remove Listing From Basket]]"><i class="glyphicon glyphicon-trash"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr/>
                                            <div>
                                                <ul class="listingOptions list-unstyled">
                                                    {foreach from=$listingOptions.options item="option"}
                                                        {strip}
                                                            <li class="listingOption {$option.id}">
                                                                <div class="row">
                                                                    <input type="hidden" name="listing_options[]" value="{$option.sid}">
                                                                    <div class="col-xs-6 vcenter">
                                                                        [[{$option.name}]]
                                                                    </div>
                                                                    <div class="col-xs-6 vcenter">
                                                                        <span class="amount orange">
                                                                            {display_price_with_currency amount=$option.price}
                                                                            {if $option.isRemovable}
                                                                                <span class="control remove middle pull-right">
                                                                                    <a href="{page_path module='basket' function='remove_item'}?sid={$option.sid}{$return_uri}" class="removeOptionLink" title="[[Remove Option]]" >
                                                                                        <i class="fa fa-remove"></i>
                                                                                    </a>
                                                                                </span>
                                                                            {/if}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        {/strip}
                                                    {/foreach}
                                                    {if $listingCount > 1}
                                                        <li class="subtotal">
                                                            <div class="row">
                                                                <div class="col-xs-6">
                                                                    <strong>[[Subtotal]]</strong>
                                                                </div>
                                                                <div class="col-xs-6">
                                                                    <span class="amount orange">{display_price_with_currency amount=$listingOptions.totalOptionPrice}</span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    {/if}
                                                </ul>
                                                {$totalPrice=$totalPrice+$listingOptions.totalOptionPrice}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {if $listingOptions@iteration is div by 3}<div class="clearfix visible-md visible-lg"></div>{/if}
                            {if $listingOptions@iteration is div by 2}<div class="clearfix visible-sm"></div>{/if}
                        {/foreach}
                    </div>
                    <div class="space-20"></div>
                    <div class="space-20"></div>
                    <div class="text-center">
                        <div class="h4">
                            [[Total]]  <span class="orange">{display_price_with_currency amount=$totalPrice}</span>
                        </div>

                        <div class="space-20"></div>
                        <input type="submit" value="[[Buy]]" class="btn btn-orange h5"/>

                    </div>

                </form>
            </div>
            <div class="space-20"></div>
            <div class="space-20"></div>
            <div class="space-20"></div>
            <div class="space-20"></div>
    </div>
	{else}
        <div class="space-20"></div>
        <div class="container">
            {capture assign="addListingLink"}{page_path id='listing_add'}{/capture}
            {capture assign="myListingsLink"}{page_path id='user_listings'}{/capture}
            [[Your basket is empty. Please <a href="$addListingLink">create a new listing</a>, or add more options to <a href="$myListingsLink">your existing ads</a> to promote them better.]]
        </div>        
    {/if}
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('a.control.addMoreOptions').click(function (){
			return openDialogWindow(this.title, this.href, 360, true);
		});
	});
</script>
