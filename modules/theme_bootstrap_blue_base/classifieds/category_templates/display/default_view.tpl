{extension_point name='modules\main\apps\FrontEnd\IListingDisplayAdditionDisplayer'}

{title}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/title}
{keywords}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/keywords}
{description}{$listing|cat:""|strip_tags:false}, [[$listing.type.caption:raw]]{/description}

<div class="listing-details">
    <h1 class="page-title">
        <div class="row">
            <div class="col-sm-9">
                <h1 class="page-title without-border text-left">
                    <span class="pull-left">
                         {if $listing.Sold.exists && $listing.Sold.isTrue}
                             <span class="fieldValue fieldValueSold">[[SOLD]]!</span>
                         {/if}
                        {$listing} 
                    </span>
                </h1>        
            </div>
            <div class="col-sm-3">
                <h1 class="page-title without-border">
                    {if ($listing.Price.exists || $listing.Rent.exists) && (!empty($listing.Price.value) || !empty($listing.Rent.value))}
                        {strip}
                            <span class="orange pull-right fieldValue fieldValuePrice money">
                                <span class="currencySign">{$GLOBALS.custom_settings.listing_currency}</span>
                                {if $listing.Price.exists}
                                    [[$listing.Price]]
                                {elseif $listing.Rent.exists}
                                    [[$listing.Rent]]
                                {/if}
                            </span>
                        {/strip}
                    {/if}
                </h1>
            </div>
        </div>
    </h1>
    
	<div class="row">

        {capture name="listing_common_details"}
            {strip}
            <div class="row listing-common-details">
                <div class="col-sm-10 col-sm-offset-1 col-md-12 col-md-offset-0">
                    <div class="row">
                        <div class="col-sm-6 col-md-12 col-xs-12">
                            <span class="fieldCaption">[[Votes]]:</span>
                            <span class="fieldValue">{display property=ListingRating template='rating_responsive_quick_view.tpl'}</span>
                        </div>

                        {foreach $magicFields->excludeSystemFields()->excludeByType('text', 'rating', 'video', 'calendar', 'multilist', 'money') as $fieldId => $formField}
                        {if $listing.$fieldId.isNotEmpty}
                        	<div class="col-sm-6 col-md-12 col-xs-12">
                                <span class="fieldCaption {$fieldId}">[[$formField.caption]]:</span>
                                <span class="fieldValue {$fieldId}">{display property=$fieldId}</span>
                            </div>
                         {/if}   
                        {/foreach}
                        <div class="col-sm-6 col-md-12 col-xs-12">
                            <span class="fieldCaption views">[[FormFieldCaptions!Listing Views]]:</span>
                            <span class="fieldValue views">[[$listing.views]]</span>
                        </div>

                        <div class="col-sm-6 col-md-12 col-xs-12">
                            <span class="fieldCaption views">[[FormFieldCaptions!Date Posted]]:</span>
                            <span class="fieldValue views">[[$listing.activation_date]]</span>
                        </div>
                    </div>
                </div>
            </div>
            {/strip}
        {/capture}


        {capture name="listing_detail_right"}
            <div class="listing-controls">
                <ul class="list-inline">
                    <li>
                        <label>
                            <input title="[[Compare Ad:raw]]" type="checkbox" name="compareAddSwitch" onchange="toggleListingAction(this, '{page_path id='listing_compare'}', '{page_path module='classifieds' function='remove_from_comparison'}')" value="{$listing.id}"{if $listing.inComparison.isTrue} checked="checked"{/if} />
                            <span class="checked glyphicon glyphicon-bookmark" title="[[In Comparison:raw]]" data-toggle="tooltip" date-placement="top"></span>
                            <span class="unchecked glyphicon glyphicon-bookmark" title="[[Compare Ad:raw]]" data-toggle="tooltip" date-placement="top"></span>
                            <span class="small">
                                &nbsp;[[Compare]]
                                (<a onclick='if (listingsInComparisonCounter >= 2) javascript:window.open(this.href, "_blank"); else alert("[[Please add 2 or more listings for comparison.:raw]]"); return false;' href="{page_path id='compared_listings'}">
                                    <span class="listingsCountInComparison counter {if $listingsCountInComparison > 0} orange {/if}">
                                        {$listingsCountInComparison}
                                    </span>
                                 </a>
                                )
                            </span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input title="[[Save Ad:raw]]" type="checkbox" name="saveAddSwitch" onchange="toggleListingAction(this, '{page_path id='listing_save'}', '{page_path module='classifieds' function='delete_saved_listing'}')" value="{$listing.id}"{if $listing.saved.isTrue} checked="checked"{/if} />
                            <span class="checked glyphicon glyphicon-heart" title="[[Saved:raw]]" data-toggle="tooltip" date-placement="top"></span>
                            <span class="unchecked glyphicon glyphicon-heart" title="[[Save Ad:raw]]" data-toggle="tooltip" date-placement="top"></span>
                            <span class="small">
                                &nbsp;[[Save]]
                                (<a href="{page_path id='user_saved_listings'}">
                                    <span class="savedListingsCount counter {if $savedListingsCount > 0} orange {/if}">{$savedListingsCount}</span>
                                </a>)
                            </span>
                        </label>
                    </li>
                    <li class="dropdown">
                        <p class="visible-xs"></p>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>[[More Options]]</span> <i class="fa fa-chevron-down"></i> </a>
                        {include file="category_templates/display/listing_details_listing_controls.tpl"}
                    </li>
                </ul>
            </div>
            <div class="panel panel-default hidden-sm hidden-xs">
                {$smarty.capture.listing_common_details}
            </div>
            <div class="space-20 hidden-lg hidden-md"></div>
            {include file="author_info.tpl" listing=$listing}
        {/capture}


		<div class="col-sm-12 col-md-8 listing-info">

			{display_success_messages}

			{if $messages}{include file="message.tpl"}{/if}

			{if $listing.pictures.numberOfItems > 0}
				{include file="listing_images.tpl" listing=$listing}
			{/if}
            <div class="space-20"></div>
			
            <div class="hidden-md hidden-lg">
                <div class="space-20"></div>
                {$smarty.capture.listing_common_details}
                <div class="space-20"></div>
                <div class="space-20"></div>
                <div class="space-20"></div>
            </div>

            {foreach $magicFields->excludeSystemFields()->filterByType('multilist') as $fieldId => $formField}
                {if $listing.$fieldId.isNotEmpty}
                    <h3 class="h4 text-center">[[$formField.caption]]</h3>
                    <div class="fieldValue multi-list">{$listing.$fieldId}</div>
                {/if}
            {/foreach}

            <div class="text-fields">
                {foreach $magicFields->excludeSystemFields()->filterByType('text') as $fieldId => $formField}
                    {if $listing.$fieldId.isNotEmpty}
                        <h5 class="h4 text-center">[[$formField.caption]]</h5>
                        <div class="fieldValue text-field">{$listing.$fieldId}</div>
                    {/if}
                {/foreach}
            </div>

            <div class="space-20"></div>
            <div class="space-20"></div>
            {include file="category_templates/display/social_network_buttons.tpl"}
            <div class="space-20"></div>
            <div class="hidden-md hidden-lg">
                {$smarty.capture.listing_detail_right}
            </div>
            <div class="space-20"></div>
            {module name='google_map' function='display_map' address=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State default_latitude=$listing.ZipCode.latitude default_longitude=$listing.ZipCode.longitude}
            <div class="space-20"></div>
            {capture assign="yt_video"}
                {module name="listing_feature_youtube" function="display_youtube" listing=$listing listing=$listing width="380px" height="300px"}
            {/capture}
            {if $yt_video|count_characters > 1}
                <h3 class="h4 text-center">[[Video]]</h3>
                {$yt_video}
            {/if}

            {foreach $magicFields->filterByType('calendar') as $fieldId => $formField}
                <h3 class="h4 text-center">[[$formField.caption]]</h3>
                <div class="fieldValue">
                    {display property=$fieldId}
                </div>
            {/foreach}
            <div class="space-20"></div>
			{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
			{assign var='current_uri' value=$current_uri|urlencode}

			{module name="listing_comments" function="display_listing_details_comments" listing=$listing returnBackUri=$current_uri}
            {module name="facebook_comments" function="display_comments" url="{page_url id='listing'}"|cat:$listing.id}

		</div>

		<div class="col-sm-12 col-md-4 right-column hidden-sm hidden-xs">
           {$smarty.capture.listing_detail_right}
		</div>
	</div>
	<script>
		$(function () {
			$('.collapse')
					.on('show.bs.collapse', function () {
						$('.glyphicon', $(this).parent())
								.addClass($('.glyphicon', $(this).parent()).data('icon-down'))
								.removeClass($('.glyphicon', $(this).parent()).data('icon-up'));
					})
					.on('hide.bs.collapse', function () {
						$('.glyphicon', $(this).parent())
								.addClass($('.glyphicon', $(this).parent()).data('icon-up'))
								.removeClass($('.glyphicon', $(this).parent()).data('icon-down'));
					})
		})
	</script>
</div>
<div class="space-20"></div>
{include file="miscellaneous^dialog_window.tpl"}
