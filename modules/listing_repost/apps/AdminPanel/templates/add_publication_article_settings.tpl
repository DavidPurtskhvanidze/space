<div class="listingRepostSettings">
	[[Do not repost this article in]]
	<br />
	<input type="checkbox" id="doNotRepostToFacebook" name="doNotRepostToFacebook" {if $doNotRepostToFacebook}checked="checked"{/if} value="1" {if $facebookRepostStatus == 0}disabled{/if} />
	<label for="doNotRepostToFacebook">[[Facebook]]{if $facebookRepostStatus == 0} ([[Disabled]]) {/if}</label>
	<br />
	<input type="checkbox" id="doNotRepostToTwitter" name="doNotRepostToTwitter" {if $doNotRepostToTwitter}checked="checked"{/if} value="1" {if $twitterRepostStatus == 0}disabled{/if}/>
	<label for="doNotRepostToTwitter">[[Twitter]]{if $twitterRepostStatus == 0} ([[Disabled]]) {/if}</label>
	<br />
	{assign var="repostSectionUrl" value={page_path id='settings'}|cat:"#SocialNetworks"}
	{if $twitterRepostStatus == 0 || $facebookRepostStatus == 0}
		[[You can enable listing repost on the System Setting page under the <a href="$repostSectionUrl">Social Networks section</a>.]]
	{/if}
</div>
