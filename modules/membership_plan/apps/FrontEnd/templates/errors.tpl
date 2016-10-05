{foreach from=$ERRORS item="error_message" key="error"}
{assign var="url" value=$GLOBALS.site_url}
{if $error eq "NOT_LOGGED_IN"}<p class="error">[[You are not logged in]]</p>
{elseif $error eq "ALREADY_SUBSCRIBED"}

{module name="membership_plan" function="contract_info" id=$contractId}
{module name="membership_plan" function="user_membership_plans" user_sid=$GLOBALS.current_user.id}

{elseif $error eq 'ACTION_IS_EXPIRED'}
    <p class="error">[[Action has expired or never existed. Please restart action again.]]</p>
{elseif $error eq 'NO_AVAILABLE_MEMBERSHIP_PLAN'}
	{include file="miscellaneous^dialog_window.tpl"}
	{capture assign="changeUserGroupLink"}{page_path module='users' function='change_user_group'}{/capture}
	{capture assign="onClickAction"}return openDialogWindow('[[Change User Group:raw]]', this.href, 400, true){/capture}
	[[Your current User Group does not provide users the ability to add listings. Please <a href="$changeUserGroupLink" onclick="$onClickAction">change your User Group</a>.]]
{/if}
{/foreach}
