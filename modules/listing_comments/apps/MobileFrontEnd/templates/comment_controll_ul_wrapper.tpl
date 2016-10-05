{if $listing.numberOfComments > '0' && $currentPageId != 'comments'}
	<li>
		{include $commentControlTemplate}
	</li>
{/if}
