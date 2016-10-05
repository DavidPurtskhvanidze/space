{foreach from=$ERROR item="error_message" key="error"}
    {if $error eq "PARAMETERS_MISSED"}
    <p class="error">[[The system cannot proceed as some key parameters are missing]]</p>
    {elseif $error eq "USER_NOT_FOUND"}
    <p class="error">[[This user was not found]]</p>
    {elseif $error eq "INVALID_ACTIVATION_KEY"}
    <p class="error">[[Wrong activation key is specified]]</p>
    {elseif $error eq "INVALID_ACTIVATION_KEY"}
    <p class="error">[[Cannot activate account. Please contact administrator.]]</p>
    {/if}
{/foreach}
{foreach from=$INFO item="info_message" key="info"}
    {if $info eq "ACCOUNT_ACTIVATED"}
    {assign var="url" value={page_path id='user_login'}}
    <p class="success">
		<span>[[Account has been activated. Please <a href="$url">login</a>.]]</span>
	</p>
    {/if}
{/foreach}
{require component="jquery" file="jquery.js"}
