{* listingControlsTemplate must be valid template name *}
{capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html?searchId={$listing_search.id}{/capture}
{if isset($listingHeaderTemplate)}
    {include file=$listingHeaderTemplate listingUrl=$listingUrl}
{/if}
<div class="thumbnail" {if $listing.feature_highlighted.exists && $listing.feature_highlighted.isTrue}style="background-color:{get_custom_setting id='color_for_highlighted_listing' theme=$GLOBALS.current_theme}"{/if}>
    {if !isset($listingHeaderTemplate)}
        <div class="search-result-item-header">
            <div class="row">
                <div class="col-xs-6 text-left">
                    <span class="fieldValue fieldValueListingRating">{include file="rating.tpl"}</span>
                </div>
                <div class="col-xs-6 text-right">
                    {include file=$listingControlsTemplate listingUrl=$listingUrl}
                </div>
            </div>
        </div>
    {/if}
    <div class="row">
        <div class="col-md-3 col-sm-4">
            <div class="image">
                {assign var="number_of_pictures" value=$listing.pictures.numberOfItems}
                {if $number_of_pictures > 0}
                    {listing_image pictureInfo=$listing.pictures.collection.0 alt="Listing #"|cat:$listing.id}
                {else}
                    <img src="{url file='main^no_image_available_big.png'}" alt="[[No photos:raw]]" class="img img-responsive"/>
                {/if}
            </div>
        </div>
        <div class="col-md-9 item-info col-sm-8">
            <div class="row caption">
                <div class="col-sm-8 col-xs-6">
                    <h4>{$listing}</h4>
                </div>
                <div class="col-sm-4 col-xs-6 text-right">
                    <span class="h4">
                        <span class="orange">
                            {if $listing.Price.exists && !$listing.Price.isEmpty}
                                <span class="fieldValue fieldValuePrice money">{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]</span>
                            {/if}
                        </span>
                    </span>

                    {if $listing.Sold.exists && $listing.Sold.isTrue}
                        <div class="sold-label"><span>[[SOLD]]</span></div>
                    {else}
                        {module name="listing_feature_sponsored" function="display_label" listing=$listing}
                    {/if}
                </div>
            </div>
            <span class="divider"></span>
            {capture name="itemDetails"}
                <div>
                    <div class="itemDetails">
                        {strip}
                            {if !$listing.Description.isEmpty}<p>{$listing.Description.value|truncate:200|escape_user_input}</p>{/if}
                        {/strip}
                    </div>
                </div>
            {/capture}
            <div class="row">
                <div class="col-md-6 hidden-sm">
                    {$smarty.capture.itemDetails}
                </div>
                <div class="col-md-6">
                    {*Assigning authors name to $postedByValue variable*}
                    {capture assign="postedByValue"}
                        {if $listing.user_sid.value == 0}
                            [[Administrator]]
                        {elseif $listing.user.DealershipName.exists}
                            {$listing.user.DealershipName}
                        {else}
                            {$listing.user.FirstName} {$listing.user.LastName}
                        {/if}
                    {/capture}
                    {$postedByValue = $postedByValue|trim}
                    <div class="row author">
                        {if !empty($postedByValue)}
                            <div class="col-xs-8">
                                <span class="fieldValue fieldValuePostedBy">
                                    {$postedByValue}
                                </span>
                                {if $listing.user_sid.value != 0}
                                    <div class="phone">
                                        <span class="fieldValue fieldValuePhoneNumber">
                                           {$listing.user.PhoneNumber}
                                        </span>
                                    </div>
                                {/if}
                            </div>
                        {/if}
                        {if $listing.user_sid.value != 0}
                            <div class="col-xs-4">
                                <div class="phone">
                                    <a href="tel:{$listing.user.PhoneNumber}">
                                        <i class="fa fa-phone fa-2x"></i>
                                    </a>
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
            <span class="divider hidden-md hidden-lg hidden-xs"></span>
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
        <div class="col-xs-12 details-in-sm hidden-md hidden-lg hidden-xs">
            {$smarty.capture.itemDetails}
        </div>
    </div>
</div>
