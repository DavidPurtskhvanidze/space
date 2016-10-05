{foreach from=$messages item="message"}
	{if $message eq "LOGGED_IN"}
		<p class="success">[[You have been successfully logged in!]]</p>
	{elseif $message eq "LISTING_SAVED"}
		<p class="success">[[Listing has been saved]]</p>
	{else}
		<p class="success">{$message}</p>
	{/if}
{/foreach}
