{if $pictures.0.thumbnail_url ne ''}
	{listing_image pictureInfo=$pictures.0}<br/>
	{foreach from=$pictures item=picture}
		{listing_image pictureInfo=$picture thumbnail=1}
	{/foreach}
{/if}




