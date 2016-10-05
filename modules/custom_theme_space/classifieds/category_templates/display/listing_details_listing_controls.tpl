{include file="miscellaneous^dialog_window.tpl"}

		{if $GLOBALS.current_user.logged_in && $listing.user.sid == $GLOBALS.current_user.id}
			<li>
				<a href="{page_path id='listing_edit'}{$listing.id}" class="link-with-icon">
					[[Edit Listing]]
				</a>
			</li>
		{/if}
		<li>
			<a onclick='return openDialogWindow("[[Tell a Friend]]", this.href, 650)' class="link-with-icon" href="{page_path id='tell_friends'}?listing_id={$listing.id}&listing_title={$listing|cat:""|strip_tags:false|urlencode}">
				[[Tell a Friend]]
			</a>
		</li>
		<li>
			<a href="{page_path id='print_listing'}?listing_id={$listing.id}" class="link-with-icon" onclick="return openLinkInWindow(this,'_blank');">
				[[Print This Ad]]
			</a>
		</li>
		{foreach from=$listingControlTemplateProviders item="templateProvider"}
			{include file=$templateProvider->getTemplateName()}
		{/foreach}
		<li>
			<a href="{page_path module='classifieds' function='display_qr_code'}?listing_id={$listing.id}" class="link-with-icon" onclick="return openLinkInWindow(this,'_blank');">
				[[Generate QR Code]]
			</a>
		</li>
		<li>
			{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
			{assign var='current_uri' value=$current_uri|urlencode}
			<a href="{page_path module='miscellaneous' function='report_improper_content'}?objectType=listing&amp;objectId={$listing.id}&amp;returnBackUri={$current_uri}" class="link-with-icon">
				[[Report Content]]
			</a>
		</li>
		<li>
			{if $listing.Zip.exists}
				{assign var="zip" value=$listing.Zip}
			{elseif $listing.ZipCode.exists}
				{assign var="zip" value=$listing.ZipCode}
			{/if}
			{$daddrLink=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State|cat:" "|cat:$zip|replace:" ":"+"}
			<a href="http://www.maps.google.com/?saddr={$search_criteria.Zip.value.location}&amp;daddr={$daddrLink}" onclick="return openLinkInWindow(this,'_blank');" class="link-with-icon">
				[[Get Directions]]
			</a>
		</li>
		{if isset($listing.Price)}
			<li>
				<a onclick='return openDialogWindow("[[Loan Calculator]]", this.href, 500)' class="link-with-icon" href="{page_path id='loan_calculator'}?listing_id={$listing.id}">
					[[Loan Calculator]]
				</a>
			</li>
		{/if}
		<li>
			{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
			{assign var='current_uri' value=$current_uri|urlencode}
			{module name="listing_comments" function="display_comment_control" listing=$listing returnBackUri=$current_uri controll="ADD_ON_LISTING_DETAILS"}
		</li>
		<li>
			<a href="{page_path id='compared_listings'}" class="link-with-icon" onclick='if (listingsInComparisonCounter >= 2) window.open(this.href, "_blank"); else alert("[[Please add 2 or more listings for comparison.:raw]]"); return false;'>
				[[Compare Listings]]
			</a>
		</li>


<script type="text/javascript">
	var listingsInComparisonCounter = {$listingsCountInComparison};
</script>
