{assign var='contactAdminLink' value=$GLOBALS.site_url|cat:'/contact/'}
{foreach from=$errors item=error}

{if $error eq 'NOT_SUPPORTED_OBJECT_TYPE'}
<p class="error">[[Looks like you have specified an unsupported object type]]</p>
{elseif $error eq 'LISTING_DOES_NOT_EXIST'}
<p class="error">[[The system does not have a listing with the specified ID]]</p>
{elseif $error eq 'COMMENT_DOES_NOT_EXIST'}
<p class="error">[[The system does not have a comment with the specified ID ]]</p>
{elseif $error eq 'PARAMETERS_MISSING'}
<p class="error">[[The system cannot proceed as some key parameters are missing.]]</p>
{elseif $error eq 'CANNOT_SEND_MAIL'}
<p class="error">[[The website has encountered an error while sending your email. This error may be a result of server misconfiguration, temporary mail server problems, or email address format problems. Please make sure that you have entered the email address correctly.]]
<br /><br />
[[If the problem persists, please <a href="$contactAdminLink">report</a> this to the website admin.]]</p>
{else}
<p class="error">{$error}</p>
{/if}

{/foreach}
