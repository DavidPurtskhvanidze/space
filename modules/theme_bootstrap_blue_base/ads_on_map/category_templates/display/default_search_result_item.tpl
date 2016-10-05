{strip}
    {capture assign="listingUrl"}{page_path id='listing'}{$listing.id}/{$listing.urlData|replace:' ':'-'|escape:"urlpathinfo"}.html?searchId={$listing_search.id}{/capture}
    <div class="listingPreview {$listing.id}">
        {include file="classifieds^category_templates/display/default_search_result_item.tpl" not_quick_viewable=true}
    </div>
    {module name="ads_on_map" function="receive_location" address=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State default_latitude=$listing.ZipCode.latitude default_longitude=$listing.ZipCode.longitude listing_id=$listing.id title=$listing|string_format:"%s"}
{/strip}
