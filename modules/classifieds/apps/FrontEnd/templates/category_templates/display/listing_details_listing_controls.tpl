{include file="miscellaneous^dialog_window.tpl"}
{require component="js" file="toggleListingAction.js"}
<div class="listingControls">
	<ul>
		{if $GLOBALS.current_user.logged_in && $listing.user.sid == $GLOBALS.current_user.id}
			<li>
				<a href="{page_path id='listing_edit'}{$listing.id}"><img class="linkIcon" src="{url file='main^icons/pencil.png'}" alt="&#8226;"></a>
				<a href="{page_path id='listing_edit'}{$listing.id}">[[Edit Listing]]</a>
			</li>
		{/if}
		<li>
			<a onclick='return openDialogWindow("[[Tell a Friend]]", this.href, 560)' href="{page_path id='tell_friends'}?listing_id={$listing.id}&listing_title={$listing|cat:""|strip_tags:false|urlencode}"><img class="linkIcon" src="{url file='main^icons/tell_friend.png'}" alt="&#8226;" /></a>
			<a onclick='return openDialogWindow("[[Tell a Friend]]", this.href, 560)' href="{page_path id='tell_friends'}?listing_id={$listing.id}&listing_title={$listing|cat:""|strip_tags:false|urlencode}">[[Tell a Friend]]</a>
		</li>
		<li>
			<a href="{page_path id='print_listing'}?listing_id={$listing.id}" onclick="return openLinkInWindow(this,'_blank');"><img class="linkIcon" src="{url file='main^icons/print.png'}" alt="&#8226;" /></a>
			<a href="{page_path id='print_listing'}?listing_id={$listing.id}" onclick="return openLinkInWindow(this,'_blank');">[[Print This Ad]]</a>
		</li>
		{foreach from=$listingControlTemplateProviders item="templateProvider"}
			{include file=$templateProvider->getTemplateName()}
		{/foreach}
		<li>
			<a href="{page_path module='classifieds' function='display_qr_code'}?listing_id={$listing.id}" onclick="return openLinkInWindow(this,'_blank');"><img class="linkIcon" src="{url file='main^icons/generate_qr_code.png'}" alt="&#8226;" /></a>
			<a href="{page_path module='classifieds' function='display_qr_code'}?listing_id={$listing.id}" onclick="return openLinkInWindow(this,'_blank');">[[Generate QR Code]]</a>
		</li>
		<li>
			{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
			{assign var='current_uri' value=$current_uri|urlencode}
			<a href="{page_path module='miscellaneous' function='report_improper_content'}?objectType=listing&amp;objectId={$listing.id}&amp;returnBackUri={$current_uri}"><img class="linkIcon" src="{url file='main^icons/report.png'}" alt="&#8226;" /></a>
			<a href="{page_path module='miscellaneous' function='report_improper_content'}?objectType=listing&amp;objectId={$listing.id}&amp;returnBackUri={$current_uri}">[[Report Content]]</a>
		</li>
		<li>
			{$daddrLink=$listing.Address|cat:", "|cat:$listing.City|cat:", "|cat:$listing.State|cat:" "|cat:$listing.ZipCode|replace:" ":"+"}
            <a href="http://www.maps.google.com/?saddr={$search_criteria.ZipCode.value.location}&amp;daddr={$daddrLink}" onclick="return openLinkInWindow(this,'_blank');"><img class="linkIcon" src="{url file='main^icons/get_directions.png'}" alt="&#8226;" /></a>
			<a href="http://www.maps.google.com/?saddr={$search_criteria.ZipCode.value.location}&amp;daddr={$daddrLink}" onclick="return openLinkInWindow(this,'_blank');">[[Get Directions]]</a>
		</li>
		<li>
			<a onclick='return openDialogWindow("[[Loan Calculator]]", this.href, 400)' href="{page_path id='loan_calculator'}?listing_id={$listing.id}"><img class="linkIcon" src="{url file='main^icons/calculator.png'}" alt="&#8226;" /></a>
			<a onclick='return openDialogWindow("[[Loan Calculator]]", this.href, 400)' href="{page_path id='loan_calculator'}?listing_id={$listing.id}">[[Loan Calculator]]</a>
		</li>
		{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
		{assign var='current_uri' value=$current_uri|urlencode}
		{module name="listing_comments" function="display_comment_control" listing=$listing returnBackUri=$current_uri wrapperTemplate='comment_controll_ul_wrapper.tpl' controll="ADD_ON_LISTING_DETAILS" }
		<li>
			<a href="{page_path id='compared_listings'}" onclick='if (listingsInComparisonCounter >= 2) javascript:window.open(this.href, "_blank"); else alert("[[Please add 2 or more listings for comparison.:raw]]"); return false;'><img class="linkIcon" src="{url file='main^icons/compare_table.png'}" alt="&#8226;" /></a>
			<a href="{page_path id='compared_listings'}" onclick='if (listingsInComparisonCounter >= 2) javascript:window.open(this.href, "_blank"); else alert("[[Please add 2 or more listings for comparison.:raw]]"); return false;'>[[Compare Listings]]</a>
		</li>
		<li>
			<label><input type="checkbox" name="compareAddSwitch" onclick="toggleListingAction(this, '{page_path id='listing_compare'}', '{page_path module='classifieds' function='remove_from_comparison'}')" value="{$listing.id}"{if $listing.inComparison.isTrue} checked="checked"{/if} /><span class="label">[[Compare Ad]]</span></label>
		</li>
		<li>
			<label><input type="checkbox" name="saveAddSwitch" onclick="toggleListingAction(this, '{page_path id='listing_save'}', '{page_path module='classifieds' function='delete_saved_listing'}')" value="{$listing.id}"{if $listing.saved.isTrue} checked="checked"{/if} /><span class="label">[[Save Ad]]</span></label>
		</li>
	</ul>
</div>
<script type="text/javascript">
	var listingsInComparisonCounter = {$listingsCountInComparison};
</script>
