{subject}Recommendation Regarding Listing #{$listing.sid} on {$GLOBALS.site_url}{/subject}
{message}
		Dear {$submitted_data.friend_name},<br /><br />
		
		Someone, most likely your friend or person you know {$submitted_data.name}, visited our website at {$GLOBALS.site_url} and decided that you should take a look at this listing:<br /><br />
		
		Listing ID: {$listing.id} "{$listing}"<br />
		Listing URL: <a href="{page_path id='listing'}{$listing.id}/">{page_path id='listing'}{$listing.id}/</a><br />
		Posted by: {if $listing.user_sid.value == 0}Administrator{else}{$listing.user.username}{/if} on {$listing.activation_date}<br />
		Recommended by: {$submitted_data.name}.<br /><br />		
		
		Message text: {$submitted_data.comment}<br />
		
		If you don't know this person, please simply disregard this email.<br /><br />
		
		Best regards,<br />
		Administrator,<br />
		{$GLOBALS.site_url}
{/message}
