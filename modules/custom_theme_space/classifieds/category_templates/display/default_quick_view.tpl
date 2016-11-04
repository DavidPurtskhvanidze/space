{extension_point name='modules\main\apps\FrontEnd\IListingDisplayAdditionDisplayer'}
<div class="listing-details quick-view-page">
    {display_success_messages}
    {if $messages}{include file="message.tpl"}{/if}
    <div class="row">
        {*<div class="quick-view-header">*}
            {*{if $listing.Sold.exists && $listing.Sold.isTrue}*}
                {*<span class="fieldValue fieldValueSold">[[SOLD]]!</span>*}
            {*{/if}*}
            {*{$listing}*}
        {*</div>*}
        {*<hr>*}
        <div class="col-sm-12 listing-details">
            <div class="text-center">
                {if $listing.pictures.numberOfItems > 0}
                    {listing_image pictureInfo=$listing.pictures.collection[0] type="big"}
                {else}
                    <img src="{url file='main^no_image_available_quick_view.jpg'}" alt="[[No photos:raw]]" class="noImageAvailable" />
                {/if}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="listing-details-info">
                <div class="listing-details-info-table">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th class="field-caption">[[FormFieldCaptions!Votes]]</th>
                            <td class="field-value">
                                {display property=ListingRating template='rating_responsive_quick_view.tpl'}
                            </td>
                        </tr>
                        {foreach $magicFields->excludeSystemFields()->excludeByType('text', 'rating', 'video', 'calendar', 'multilist') as $fieldId => $formField}                            <tr>
                                <th class="field-caption {$fieldId}">
                                    [[$formField.caption]]
                                </th>
                                <td class="field-value {$fieldId}">
                                    {display property=$fieldId}
                                </td>
                            </tr>
                        {/foreach}
                        <tr>
                            <th class="field-caption">[[FormFieldCaptions!Listing Views]]</th>
                            <td class="field-value">[[$listing.views]]&nbsp;</td>
                        </tr>
                        <tr>
                            <th class="field-caption">[[FormFieldCaptions!Date Posted]]</th>
                            <td class="field-value">[[$listing.activation_date]]&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="col-sm-12 text-center">
            {capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html{/capture}
            <a href="{$listingUrl}" class="respondInMainWindow default-button details-modal-bt wb" title='{$listing|strip_tags}'>[[View Details]]</a>
        </div>
    </div>

</div>


