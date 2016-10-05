{if $userinfo_ip_address}
	|
	{if !$isIpBlocked}
		{assign var="returnBackUri" value="{page_path module='listing_comments' function='manage_comments'}?action=restore&searchId="|cat:$searchId|cat:"&message=BLOCKLIST_IP_ADDED"}
		<a class="itemControls addIPToBlocklist" target="blockIpRange" onclick='return openDialogWindow("[[Block IP Range:raw]]", this.href, 450, true)'
			href="{page_path module='ip_blocklist' function='block_ip_range'}?ip_range={$userinfo_ip_address}&returnBackUri={$returnBackUri|urlencode}" title="[[Add IP $userinfo_ip_address to Blocklist:raw]]">[[Add IP $userinfo_ip_address to Blocklist]]</a>
	{else}
		<a class="itemControls removeIPFromBlocklist" href="{page_path module='ip_blocklist' function='blocklist'}?filter_ip_range={$userinfo_ip_address}">[[Remove the IP $userinfo_ip_address from the Blocklist]]</a>
	{/if}
{/if}
