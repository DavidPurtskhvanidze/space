{if $listing.Price.exists && !$listing.Price.isEmpty}
    <span class="field Price">
        <span class="fieldValue fieldValuePrice money">{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]</span>
    </span>
{else}
    &nbsp;
{/if}
