<div class="loginRequestPage">
	<h1 class="addListingHeader">[[Add a New Listing]]</h1>

	<p class="error">
	{if $error eq 'NO_LISTING_PACKAGE_AVAILABLE'}
		{capture assign="contactAdminLink"}{page_path id='contact'}{/capture}
		[[There's no listing packages available on your membership plan]]. [[Please <a href="$contactAdminLink">contact</a> site administrator and report this issue. Thank you!]]
	{elseif $error eq 'LISTINGS_NUMBER_LIMIT_EXCEEDED'}
		[[You've reached the limit of number of listings allowed by your plan]]
	{elseif $error eq 'NO_CONTRACT'}
		[[Choose your memberhsip plan]]
	{elseif $error eq 'NOT_LOGGED_IN'}
		{assign var="url" value={page_path id='user_registration'}}
		[[Please log in to place a listing. If you do not have an account, please <a href="$url">Register</a>.]]
		{module name="users" function="login" HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
	{/if}
</div>
