[[The site failed to send the message to the following users:]]
<ul>
	{foreach from=$failedUsers item=failedUser}
		<li>{$failedUser}</li>
	{/foreach}
</ul>
