{extension_point name='modules\main\apps\FrontEnd\IListingDisplayAdditionDisplayer'}

{title}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/title}
{keywords}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/keywords}
{description}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/description}
{require component="js" file="toggleListingAction.js"}
<div class="listing-details-container">
    <div class="listing-details">
        <div class="listing-details-head">
            <div class="listing-details-head-top">
                {include file="classifieds^search_controls_listing_details.tpl"}
            </div>
            <div class="listing-details-head-sub">
                <div class="listing-title">
                    {if ($listing.Price.exists || $listing.Rent.exists) && (!empty($listing.Price.value) || !empty($listing.Rent.value))}
                        {strip}
                            <span class="field-valu-price">
                            <span class="currencySign">{$GLOBALS.custom_settings.listing_currency}</span>
                                {if $listing.Price.exists}
                                    [[$listing.Price]]
                                {elseif $listing.Rent.exists}
                                    [[$listing.Rent]]
                                {/if}
                        </span>
                        {/strip}
                    {/if}
                    {if $listing.Sold.exists && $listing.Sold.isTrue}
                        <span class="fieldValue fieldValueSold">[[SOLD]]!</span>
                    {/if}
                    {$listing.Title}
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-9 listing-info">
            <div class="listing-controls listing-details-head-sub-listing-controls">
                <label class="default-button wb">
                    <input title="[[Save Ad:raw]]" type="checkbox" name="saveAddSwitch"
                           onchange="toggleListingAction(this, '{page_path id='listing_save'}', '{page_path module='classifieds' function='delete_saved_listing'}')"
                           value="{$listing.id}"{if $listing.saved.isTrue} checked="checked"{/if} />
                    <span class="checked glyphicon glyphicon-ok" title="[[Saved:raw]]" date-placement="top"></span>
                    <span class="unchecked glyphicon glyphicon-floppy-disk" title="[[Save Ad:raw]]" date-placement="top"></span>
                    <span>Save</span>
                </label>
                <label class="separate"></label>
                <label class="default-button wb">
                    <input title="[[Compare Ad:raw]]" type="checkbox" name="compareAddSwitch"
                           onchange="toggleListingAction(this, '{page_path id='listing_compare'}', '{page_path module='classifieds' function='remove_from_comparison'}')"
                           value="{$listing.id}"{if $listing.inComparison.isTrue} checked="checked"{/if} />
                    <span class="checked glyphicon glyphicon-ok" title="[[In Comparison:raw]]" date-placement="top"></span>
                    <span class="unchecked glyphicon glyphicon-transfer" title="[[Compare Ad:raw]]" date-placement="top"></span>
                    <span>Add to compare list</span>
                </label>
                <label class="separate"></label>
                <!-- Modal Button Map-->
                <label class="default-button wb" data-toggle="modal" data-target="#g-map">
                    <span class="glyphicon glyphicon-globe"></span>
                    On Map
                </label>
                <!-- Modal Map-->
                <div class="modal fade" id="g-map" tabindex="-1" role="dialog" aria-labelledby="g-mapLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="g-mapLabel">Modal title</h4>
                            </div>
                            <div class="modal-body">
                                <div class="g-map">
                                    {module name='google_map' function='display_map' address=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State default_latitude=$listing.ZipCode.latitude default_longitude=$listing.ZipCode.longitude}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {if !$listing.Video.uploaded}
                    <label class="separate"></label>
                    <a class="default-button wb" onclick='return openDialogWindow("[[Watch a video]]", this.href, 1087, true)'
                       href="{page_path id='video_player'}?listing_id={$listing.id}&raw_output=1">
                        <span class="glyphicon glyphicon-facetime-video"></span> [[Watch a video]]
                    </a>
                {/if}
            </div>
            {if $listing.pictures.numberOfItems > 0}
                {include file="listing_images.tpl" listing=$listing}
            {/if}
            <hr>
            <div class="listing-details-info">
                {foreach $magicFields->excludeSystemFields()->filterByType('text') as $fieldId => $formField}
                    {if $listing.$fieldId.isNotEmpty}
                        <div class="listing-details-info-text">
                            {$listing.$fieldId}
                        </div>
                    {/if}
                {/foreach}
                <div class="listing-details-info-table">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th class="field-caption">[[FormFieldCaptions!Votes]]</th>
                            <td class="field-value">
                                {display property=ListingRating template='rating_responsive.tpl'}
                            </td>
                        </tr>
                        {foreach $magicFields->excludeSystemFields()->excludeByType('text', 'rating', 'video', 'calendar', 'multilist') as $fieldId => $formField}
                            <tr>
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
                    {foreach $magicFields->excludeSystemFields()->filterByType('multilist') as $fieldId => $formField}
                        {if $listing.$fieldId.isNotEmpty}
                            <hr>
                            {$listing.$fieldId}
                        {/if}
                    {/foreach}
                    {foreach $magicFields->filterByType('calendar') as $fieldId => $formField}
                        {if $listing.$fieldId}
                            <hr>
                            {display property=$fieldId}
                        {/if}
                    {/foreach}
                </div>
            </div>



            {display_success_messages}
            {if $messages}{include file="message.tpl"}{/if}

            {module name="listing_feature_youtube" function="display_youtube" listing=$listing listing=$listing width="380px" height="300px"}
        </div>

        <div class="col-md-3">
            {include file="author_info.tpl" listing=$listing}
            <hr>
            {include file="category_templates/display/listing_details_listing_controls.tpl"}
            <hr>
            {include file="category_templates/display/social_network_buttons.tpl"}
        </div>

    </div>
</div>
{*Comments*}
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        {assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
        {assign var='current_uri' value=$current_uri|urlencode}
        {module name="listing_comments" function="display_listing_details_comments" listing=$listing returnBackUri=$current_uri}
        {module name="facebook_comments" function="display_comments" url="{page_url id='listing'}"|cat:$listing.id}
    </div>
</div>

