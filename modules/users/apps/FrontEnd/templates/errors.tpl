{display_error_messages}
{assign var='contactLink' value=$GLOBALS.site_url|cat:'/contact/'}
{assign var='siteUrl' value=$GLOBALS.site_url}
{foreach from=$errors key=error item=errmess}
	{if $error eq 'NO_SUCH_USER'}<p class="error">[[Invalid username/password combination. Please try again.]]</p>
	{elseif $error eq 'INVALID_PASSWORD'}<p class="error">[[Invalid username/password combination. Please try again.]]</p>
	{elseif $error eq 'USER_NOT_ACTIVE'}<p class="error">[[Your account is not active]]</p>
	{elseif $error eq 'PARAMETERS_MISSED'} <p class="error">[[The system cannot proceed as some key parameters are missing]]</p>
	{elseif $error eq 'CANNOT_SEND_MAIL'}<p class="error">[[Unable to send mail. If this error persists please contact site administrator.]]</p>
	{elseif $error eq 'SEARCH_EXPIRED'}	<p class="error">[[Unfortunately, your search criteria have expired. Please start the search over.]]</p>
	{elseif $error eq 'INVALID_KEY_OR_SECRET'}	<p class="error">[[The OAuth Key and/or Secret settings are incorrect. Please <a href="$contactLink">contact</a> site administrator and report this issue. Thank you!]]</p>
	{elseif $error eq 'AUTH_FAILED'}	<p class="error">[[The login and/or password are incorrect; or the authorization failed/cancelled. Please double-check the login and password and approve data sharing between $siteUrl and social network.]]</p>
	{elseif $error eq 'CANNOT_CREATE_TEMP_DIR'}	<p class="error">[[The system cannot create a temporary folder for OpenID data. Please <a href="$contactLink">contact</a> site administrator and report this issue. Thank you!]]</p>
	{elseif $error eq 'CANNOT_REDIRECT_TO_SERVER'}	<p class="error">[[The system cannot pass data to OpenID provider. Please try to login using your OpenID account later and <a href="$contactLink">contact</a> site administrator to report this issue. Thank you!]]</p>
	{elseif $error eq 'INCORRECT_OPEN_ID'}	<p class="error">[[The provided OpenID is incorrect. Please double-check if you had an OpenID account and entered the correct data and try to login again. Thank you!]]</p>
	{elseif $error eq 'ALREADY_IN_THAT_GROUP'}	<p class="error">[[You are already in group you selected to change to.]]</p>
	{elseif $error eq 'UNKNOWN_USERGROUP'}	<p class="error">[[Group does not exist.]]</p>
    {elseif $error eq 'EMAIL_EXITS'} <p class="error">[[E-mail is already used]]</p>
{/if}
{/foreach}
