<div class="container">
    {include file="miscellaneous^dialog_window.tpl"}
    {$return_uri = "&return_uri=$return_uri"}
    <h1 class="title">[[My Basket]]</h1>
    <div class="myBasket row">
        {display_success_messages}

        <div class="alert alert-warning">
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
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <form action="{page_path module='basket' function='buy_items'}" method="post">
                    {CSRF_token}
                    {$listingCount = count($itemsGroupedByListings)}
                    {foreach from=$itemsGroupedByListings item="listingOptions"}
                        {$listing=$listingOptions.listing}
                        {capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
                        <div class="thumbnail searchResultItemWrapper{if $listingOptions@last} last{/if}">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="pictures">
                                    <span class="fieldValue fieldValuePictures">
                                        <a href="{$listingUrl}" title="{$listingTitle}">
                                            {if $listing.pictures.numberOfItems > 0}
                                                {listing_image pictureInfo=$listing.pictures.collection.0}
                                            {else}
                                                <img src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]"
                                                     class="noImageAvailable"/>
                                            {/if}
                                        </a>
                                    </span>

                                    </div>

                                </div>
                                <div class="col-sm-9">
                                    <div class="row">

                                        <div class="col-xs-9">
                                            <h3><a href="{$listingUrl}" class="listingCaption">{$listing}</a></h3>
                                        </div>
                                        <div class="col-xs-3">
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
                                    <ul class="listingOptions list-unstyled">
                                        {foreach from=$listingOptions.options item="option"}
                                            <li class="listingOption {$option.id}">
                                                <div class="row">
                                                    <input type="hidden" name="listing_options[]" value="{$option.sid}">
                                                    <div class="col-sm-6">
                                                        <span class="caption">[[{$option.name}]]</span>
                                                    </div>
                                                    <div class="col-sm-6">
                                                     <span class="amount">{display_price_with_currency amount=$option.price}
                                                         {if $option.isRemovable}
                                                             <span class="control remove middle">
                                                                <a href="{page_path module='basket' function='remove_item'}?sid={$option.sid}{$return_uri}" class="removeOptionLink" title="[[Remove Option]]" >
                                                                    <i class="glyphicon glyphicon-remove"></i>
                                                                </a>
                                                            </span>
                                                         {/if}
                                                      </span>
                                                    </div>
                                                </div>
                                            </li>
                                        {/foreach}
                                        {if $listingCount > 1}
                                            <li class="subtotal">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <span class="caption">[[Subtotal]]</span>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <span class="amount">{display_price_with_currency amount=$listingOptions.totalOptionPrice}</span>
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
                        <div class="col-md-3">&nbsp;</div>
                        <div class="col-md-9">
                            <div class="total row">
                                <div class="col-sm-6">
                                    <span class="caption">[[Total]]</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="amount">{display_price_with_currency amount=$totalPrice}</span>
                                    <div class="submitButtonWrapper">
                                        <input type="submit" value="[[Buy]]" class="btn btn-primary"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-sm-2"></div>

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

</div>