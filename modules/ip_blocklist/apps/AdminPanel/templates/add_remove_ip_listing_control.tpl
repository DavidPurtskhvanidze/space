<li>
	{if !$userinfo_ip_address}
		[[The IP is empty and cannot be blocked]]
	{elseif !$userinfo_ip_blocked}
		<a onclick='return openDialogWindow("[[Add IP $userinfo_ip_address to Blocklist:raw]]", this.href, 450)' href="{page_path module='ip_blocklist' function='block_ip_range'}?returnBackUri={$returnBackUri}&amp;ip_range={$userinfo_ip_address}" title="[[Add IP $userinfo_ip_address to Blocklist:raw]]">[[Add IP $userinfo_ip_address to Blocklist]]</a>
	{else}
		<a href="{page_path module='ip_blocklist' function='blocklist'}?filter_ip_range={$userinfo_ip_address}">[[Remove the IP $userinfo_ip_address from the Blocklist]]</a>
	{/if}
</li>
