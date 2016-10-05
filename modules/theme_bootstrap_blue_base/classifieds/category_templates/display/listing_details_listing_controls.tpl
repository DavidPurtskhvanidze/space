{include file="miscellaneous^dialog_window.tpl"}
<ul class="dropdown-menu listing-detail-listing-control">
    {if $GLOBALS.current_user.logged_in && $listing.user.sid == $GLOBALS.current_user.id}
        <li>
            <a href="{page_path id='listing_edit'}{$listing.id}">
                <i class="fa fa-edit"></i>
                <span>[[Edit Listing]]</span>
            </a>
        </li>
    {/if}
    <li>
        <a role="menuitem" onclick='return openDialogWindow("[[Tell a Friend]]", this.href, 650)' href="{page_path id='tell_friends'}?listing_id={$listing.id}&listing_title={$listing|cat:""|strip_tags:false|urlencode}">
            <i class="fa fa-bullhorn"></i>
            <span>[[Tell a Friend]]</span>
        </a>
    </li>
    <li>
        <a role="menuitem" href="{page_path id='print_listing'}?listing_id={$listing.id}" onclick="return openLinkInWindow(this,'_blank');">
            <i class="fa fa-print"></i>
            <span>[[Print This Ad]]</span>
        </a>
    </li>

    {foreach from=$listingControlTemplateProviders item="templateProvider"}
        <li>
            {include file=$templateProvider->getTemplateName()}
        </li>
    {/foreach}
    <li>
        <a target="_blank" role="menuitem" href="{page_path module='classifieds' function='display_qr_code'}?listing_id={$listing.id}">
            <i class="fa fa-qrcode"></i>
            <span>[[Generate QR Code]]</span>
        </a>
    </li>
    <li>
        {assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
        {assign var='current_uri' value=$current_uri|urlencode}
        <a href="{page_path module='miscellaneous' function='report_improper_content'}?objectType=listing&amp;objectId={$listing.id}&amp;returnBackUri={$current_uri}">
            <i class="fa fa-warning"></i>
            <span>[[Report Content]]</span>
        </a>
    </li>
    <li>
        {if $listing.Zip.exists}
            {assign var="zip" value=$listing.Zip}
        {elseif $listing.ZipCode.exists}
            {assign var="zip" value=$listing.ZipCode}
        {/if}
        {$daddrLink=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State|cat:" "|cat:$zip|replace:" ":"+"}
        <a href="http://www.maps.google.com/?saddr={$search_criteria.Zip.value.location}&amp;daddr={$daddrLink}" onclick="return openLinkInWindow(this,'_blank');">
            <i class="fa fa-map-marker"></i>
            <span>[[Get Directions]]</span>
        </a>
    </li>
    {if isset($listing.Price)}
        <li>
            <a onclick='return openDialogWindow("[[Loan Calculator]]", this.href, 500)' href="{page_path id='loan_calculator'}?listing_id={$listing.id}">
                <i class="fa fa-calculator"></i>
                <span>[[Loan Calculator]]</span>
            </a>
        </li>
    {/if}
    <li>
        {assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
        {assign var='current_uri' value=$current_uri|urlencode}
        {module name="listing_comments" function="display_comment_control" listing=$listing returnBackUri=$current_uri controll="ADD_ON_LISTING_DETAILS"}
    </li>
    <li>
        <a href="{page_path id='compared_listings'}" onclick='if (listingsInComparisonCounter >= 2) window.open(this.href, "_blank"); else alert("[[Please add 2 or more listings for comparison.:raw]]"); return false;'>
            <i class="fa fa-tasks"></i>
            <span>[[Compare Listings]]</span>
        </a>
    </li>
</ul>
<script type="text/javascript">
	var listingsInComparisonCounter = {$listingsCountInComparison};
</script>
