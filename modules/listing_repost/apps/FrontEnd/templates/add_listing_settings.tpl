{if $facebookIsSetUp || $twitterIsSetUp}
	<div class="listingRepostSettings">
        <h3>[[Listing Repost]]</h3>
		[[Do not repost this listing in]]
		<br />
		{if $twitterIsSetUp}
			<input type="checkbox" id="doNotRepostToTwitter" name="doNotRepostToTwitter" {if $doNotRepostToTwitter}checked="checked"{/if} value="1" {if $twitterRepostStatus == 0}disabled{/if}/>
			<label for="doNotRepostToTwitter">[[Twitter]]{if $twitterRepostStatus == 0} ([[Disabled]]) {/if}</label>
			<br />
		{/if}
		{assign var="profilePageUrl" value={page_path id='user_profile'}}
		{if ($twitterIsSetUp && $twitterRepostStatus == 0) || ($facebookIsSetUp && $facebookRepostStatus == 0)}
			[[You can enable listing repost on your <a href="$profilePageUrl">profile page</a>.]]
		{/if}
	</div>
{else}
	<!-- Error: third party providers are not set up -->
{/if}
