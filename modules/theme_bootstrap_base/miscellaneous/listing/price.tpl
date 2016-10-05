{if $listing.Price.exists && !$listing.Price.isEmpty}
    <span class="fieldValue fieldValuePrice money">{$GLOBALS.custom_settings.listing_currency}[[$listing.Price]]</span>
{/if}
