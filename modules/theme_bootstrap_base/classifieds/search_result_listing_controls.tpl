{require component="js" file="toggleListingAction.js"}
<div class="listing-controls pull-right">
    <div class="row">
        <div class="col-xs-6">
            <label class="favorite">
                <input title="[[Save Ad:raw]]" type="checkbox" name="saveAddSwitch" onchange="toggleListingAction(this, '{page_path id='listing_save'}', '{page_path module='classifieds' function='delete_saved_listing'}')" value="{$listing.id}"{if $listing.saved.isTrue} checked="checked"{/if} />
                <span class="checked glyphicon glyphicon-heart" title="[[Saved:raw]]" data-toggle="tooltip" date-placement="top"></span>
                <span class="unchecked glyphicon glyphicon-heart" title="[[Save Ad:raw]]" data-toggle="tooltip" date-placement="top"></span>
            </label>
        </div>
        <div class="col-xs-6">
            <label class="compare">
                <input title="[[Compare Ad:raw]]" type="checkbox" name="compareAddSwitch" onchange="toggleListingAction(this, '{page_path id='listing_compare'}', '{page_path module='classifieds' function='remove_from_comparison'}')" value="{$listing.id}"{if $listing.inComparison.isTrue} checked="checked"{/if} />
                <span class="checked glyphicon glyphicon-bookmark" title="[[In Comparison:raw]]" data-toggle="tooltip" date-placement="top"></span>
                <span class="unchecked glyphicon glyphicon-bookmark" title="[[Compare Ad:raw]]" data-toggle="tooltip" date-placement="top"></span>
            </label>
        </div>
    </div>
</div>
