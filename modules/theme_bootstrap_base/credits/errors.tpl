{foreach from=$errors item=error}
{strip}
	<p class="error alert alert-danger">
	{if $error == 'NO_PACKAGES_SELECTED'}
		[[You have not selected any credit packages. Please indicate a number of credit packages you would like to purchase.]]
	{elseif $error == 'INCORRECT_AMOUNT'}
		[[Number of credit packages can not be negative and can not contain decimals.]]
	{elseif $error == 'NOT_LOGGED_IN'}
		{assign var='registrationUrl' value={page_path id='user_registration'}}
		[[Please log in to buy credits. If you do not have an account, please <a href="$registrationUrl">register</a>.]]
	{else}
		{$error}
	{/if}
	</p>
{/strip}
{/foreach}
