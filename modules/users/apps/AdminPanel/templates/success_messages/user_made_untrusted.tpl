{if $usernames|@count == 1}
	{$username=$usernames[0]}
[[The user $username was successfully made untrusted.]]
	{else}
[[The status of the selected users was changed to untrusted.]]
{/if}
