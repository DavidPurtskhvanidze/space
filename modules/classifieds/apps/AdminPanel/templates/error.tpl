{foreach from=$ERRORS item="error_message" key="error"}
	{if $error eq "INVALID_REQUEST"}
		<p class="error">{$error_message}</p>
	{elseif $error eq "INVALID_DATA"}
		<p class="error">{$error_message}</p>
	{elseif $error eq "REQUEST_FORM_NOT_EXIST"}
		<p class="error">[[Provided request form does not exist, please contact administrator.]]</p>
	{elseif $error eq "INPUT_FORM_NOT_EXIST"}
		<p class="error">[[Provided input form does not exist, please contact administrator.]]</p>
	{elseif $error eq "PARAMETERS_MISSED"}
		<p class="error">[[The system cannot proceed as some key parameters are missing.]]</p>
	{elseif $error eq "MYSQL_ERROR"}
		{$error_message}
	{elseif $error eq "NOT_LOGGED_IN"}
		<p class="error">[[The user is not logged in.]]</p>
	{elseif $error eq "NOT_OWNER"}
		<p class="error">[[You are not the owner of this listing.]]</p>
	{/if}
{/foreach}
