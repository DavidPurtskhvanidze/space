{extension_point name='modules\recent_listings\apps\FrontEnd\IRecentListingsAdditionRenderer'}
{if $listings}
    <div class="recentListings{if $number_of_cols > 0} list{$number_of_cols}Columns{/if}">
        <h2>
            <a href="{page_path id='listings_recent'}">
                [[Recent Ads]]
            </a>
        </h2>
        <ul>
            {foreach from=$listings item=listing}
                <li>
                    <div class="item">
                        {capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
                        <div class="fieldValue fieldValuePictures">
                            {if $listing.pictures.numberOfItems > 0}
                                <a href="{$listingUrl}">
                                    {listing_image pictureInfo=$listing.pictures.collection.0 thumbnail=1}
                                </a>
                            {else}
                                <a href="{$listingUrl}"><img src="{url file='main^no_image_available_small.png'}" alt="[[No photos:raw]]" class="noImageAvailable" /></a>
                            {/if}
                        </div>
                        <div class="details">
                            <a href="{$listingUrl}">{$listing|cat:""|strip_tags:false|truncate:45:"...":true}</a>
                            {if $listing.Price.exists && !$listing.Price.isEmpty}<br />
                                <span class="fieldValue fieldValuePrice money"><a href="{$listingUrl}">{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]</a></span>
                            {/if}
                        </div>
                    </div>
                </li>
            {/foreach}
        </ul>
    </div>
{/if}
