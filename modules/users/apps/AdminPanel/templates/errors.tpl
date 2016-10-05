{foreach from=$errors item=error}
	<p class="error">
		{if $error == "USER_NOT_FOUND"}
			[[User(s) not found.]]
		{elseif $error == "USERGROUP_NOT_FOUND"}
			[[Unknown User Group.]]
		{else}
			[[$error]]
		{/if}
	</p>
{/foreach}
