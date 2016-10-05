{display_error_messages}
{if $renderTemplate !== false}
	{* Show the number of the listings in the comparison table if requested. *}
	{if !is_null($REQUEST.getCount)}{$listingCount}{/if}
{/if}
