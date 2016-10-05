{if $error eq null}
<p class="success">
	<span>{$savedListingsAmount}</span>
</p>
{elseif $error eq 'LISTING_ID_NOT_SPECIFIED'}
<p class="error">[[Listing ID is not specified]]</p>
{/if}
{require component="jquery" file="jquery.js"}
