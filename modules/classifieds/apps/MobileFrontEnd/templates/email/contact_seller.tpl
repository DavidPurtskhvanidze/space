{subject}Request for Additional Information About Listing #{$listing.id} on {$GLOBALS.site_url}{/subject}

{message}
	Dear {$listing.user.username},<br /><br />
	One of the visitors of {$GLOBALS.site_url} would like to contact you for additional information about your listing #{$listing.id} "{$listing}":<br /><br />
	
	Listing URL: <a href="{page_path id='listing'}{$listing.id}/">{page_path id='listing'}{$listing.id}/</a><br /><br />
	
	Here is the information the contacting person requested, along with their user details: <br /><br />
	
	Contact's Name: {$seller_request.FullName}<br />
	Contact's Email: {$seller_request.Email}<br />
	Comments: {$seller_request.Request}<br /><br />
	
	Best regards,<br />
	Administrator,<br />
	{$GLOBALS.site_url}
{/message}
