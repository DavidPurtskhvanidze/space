{extension_point name='modules\main\apps\FrontEnd\IListingDisplayAdditionDisplayer'}

{title}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/title}
{keywords}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/keywords}
{description}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/description}
{require component="js" file="toggleListingAction.js"}


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
    <div class="col-md-8 listing-info">
        {if $listing.pictures.numberOfItems > 0}
            {include file="listing_images.tpl" listing=$listing}
        {/if}

        {display_success_messages}
        {if $messages}{include file="message.tpl"}{/if}

        <div class="listing-details-info">
            <dl class="dl-horizontal">
                {foreach $magicFields->excludeSystemFields()->excludeByType('text', 'rating', 'video', 'calendar', 'multilist') as $fieldId => $formField}
                    <dt class="field-caption {$fieldId}">[[$formField.caption]]</dt>
                    <dd class="field-value {$fieldId}">{display property=$fieldId}&nbsp;</dd>
                {/foreach}

                <dt class="field-caption">[[FormFieldCaptions!Listing Views]]</dt>
                <dd class="field-value">[[$listing.views]]&nbsp;</dd>

                <dt class="field-caption">[[FormFieldCaptions!Date Posted]]</dt>
                <dd class="field-value">[[$listing.activation_date]]&nbsp;</dd>
            </dl>

            {foreach $magicFields->excludeSystemFields()->filterByType('text') as $fieldId => $formField}
                {if $listing.$fieldId.isNotEmpty}
                    {$listing.$fieldId}
                {/if}
            {/foreach}
            <hr>
            {foreach $magicFields->excludeSystemFields()->filterByType('multilist') as $fieldId => $formField}
                {if $listing.$fieldId.isNotEmpty}
                    {$listing.$fieldId}
                {/if}
            {/foreach}

            {foreach $magicFields->filterByType('calendar') as $fieldId => $formField}
                {display property=$fieldId}
            {/foreach}

            {module name="listing_feature_youtube" function="display_youtube" listing=$listing listing=$listing width="380px" height="300px"}
        </div>


        {assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
        {assign var='current_uri' value=$current_uri|urlencode}
        {module name="listing_comments" function="display_listing_details_comments" listing=$listing returnBackUri=$current_uri}
        {module name="facebook_comments" function="display_comments" url="{page_url id='listing'}"|cat:$listing.id}
    </div>
    <div class="col-md-4">
        {*{if $listing.pictures.numberOfItems > 0}*}
        {*{include file="listing_images.tpl" listing=$listing}*}
        {*{/if}*}
        {include file="author_info.tpl" listing=$listing}
        <div class="listing-controls listing-details-head-sub-listing-controls">
            <label class="dropdown">
                <button class="default-button wb dropdown-toggle" type="button" id="listing_controls"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-align-justify"></span>
                    Options

                </button>
                <ul class="dropdown-menu" aria-labelledby="listing_controls">
                    {include file="category_templates/display/listing_details_listing_controls.tpl"}
                </ul>
            </label>
            <label class="default-button wb">
                <input title="[[Save Ad:raw]]" type="checkbox" name="saveAddSwitch"
                       onchange="toggleListingAction(this, '{page_path id='listing_save'}', '{page_path module='classifieds' function='delete_saved_listing'}')"
                       value="{$listing.id}"{if $listing.saved.isTrue} checked="checked"{/if} />
                    <span class="checked glyphicon glyphicon-ok" title="[[Saved:raw]]" data-toggle="tooltip"
                          date-placement="top"></span>
                    <span class="unchecked glyphicon glyphicon-floppy-disk" title="[[Save Ad:raw]]"
                          data-toggle="tooltip" date-placement="top"></span>
                <span>Save</span>
            </label>

            <label class="default-button wb">
                <input title="[[Compare Ad:raw]]" type="checkbox" name="compareAddSwitch"
                       onchange="toggleListingAction(this, '{page_path id='listing_compare'}', '{page_path module='classifieds' function='remove_from_comparison'}')"
                       value="{$listing.id}"{if $listing.inComparison.isTrue} checked="checked"{/if} />
                    <span class="checked glyphicon glyphicon-ok" title="[[In Comparison:raw]]" data-toggle="tooltip"
                          date-placement="top"></span>
                    <span class="unchecked glyphicon glyphicon-transfer" title="[[Compare Ad:raw]]"
                          data-toggle="tooltip" date-placement="top"></span>
                <span>Add to compare list</span>
            </label>
            <!-- Modal Button Map-->
            <button type="button" class="default-button wb" data-toggle="modal" data-target="#g-map">
                <span class="glyphicon glyphicon-globe"></span>
                On Map
            </button>
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

            {if $listing.Video.uploaded}
                <a class="default-button wb" onclick='return openDialogWindow("[[Watch a video]]", this.href, 1087, true)'
                   href="{page_path id='video_player'}?listing_id={$listing.id}&raw_output=1">
                    <span class="glyphicon glyphicon-facetime-video"></span> [[Watch a video]]
                </a>
            {/if}
        </div>
        <div class="panel panel-default">
            <div class="list-group">
                <div class="list-group-item">
                    {include file="category_templates/display/social_network_buttons.tpl"}
                </div>
                <div class="list-group-item">
                    <span class="fieldValue fieldValueListingRating">{display property=ListingRating template='rating_responsive.tpl'}</span>
                </div>
            </div>
        </div>
    </div>
</div>
