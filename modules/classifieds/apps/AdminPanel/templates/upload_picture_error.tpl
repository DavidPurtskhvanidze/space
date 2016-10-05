{if $error eq 'FILE_NOT_SPECIFIED'}
	[[File is not specified]]
{elseif $error eq 'NOT_SUPPORTED_IMAGE_FORMAT'}
	[[Not supported image format]]
{elseif $error eq 'PICTURES_LIMIT_EXCEEDED'}
	[[Limit is exceeded]]
{else}
	[[$error]]
{/if}
