{require component="js" file="toggleListingAction.js"}
<div class="listing-controls-bar" data-listingId="{$listing.id}">
    <ul class="list-unstyled">
        <li>
            <a target="_blank" href="{page_path id='compared_listings'}" title="[[Compare:raw]]">
                <i data-toggle="tooltip" title="[[Compare:raw]]" class="fa fa-columns" aria-hidden="true"></i>
            </a>
        </li>
        <li>
            <a href="?action=delete&amp;listings[{$listing.id}]=1" title="[[Delete:raw]]">
                <i data-toggle="tooltip" title="Delete" class="fa fa-trash-o" aria-hidden="true"></i>
            </a>
        </li>

        <li class="hidden-xs">
            <label data-toggle="tooltip" title="Check">
                <input type="checkbox" name="listings[{$listing.id}]" value="1" class="item-selector" />
            </label>
        </li>
    </ul>
</div>
<span class="listing-controls">
    <label class="st-controls">
        <input title="[[Compare Ad:raw]]" type="checkbox" name="compareAddSwitch" onchange="toggleListingAction(this, '{page_path id='listing_compare'}', '{page_path module='classifieds' function='remove_from_comparison'}')" value="{$listing.id}"{if $listing.inComparison.isTrue} checked="checked"{/if} />
        <span class="checked glyphicon glyphicon-bookmark" title="[[In Comparison:raw]]" data-toggle="tooltip" date-placement="top"></span>
        <span class="unchecked glyphicon glyphicon-bookmark" title="[[Compare Ad:raw]]" data-toggle="tooltip" date-placement="top"></span>
    </label>
</span>
