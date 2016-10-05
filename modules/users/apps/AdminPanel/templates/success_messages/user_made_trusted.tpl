{if $usernames|@count == 1}
	{$username=$usernames[0]}
[[The user $username was successfully made trusted.]]
	{else}
[[The status of the selected users was changed to trusted.]]
{/if}
