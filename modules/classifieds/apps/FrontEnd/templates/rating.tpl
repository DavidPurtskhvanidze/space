{assign var=rating value=$listing.ListingRating}
{strip}
	{section name=stars start=1 loop=6 step=1}
	{if $smarty.section.stars.index <= round($rating.rating, 1)}
	<img src="{url file='main^star_full.png'}" alt="*" />
	{elseif $smarty.section.stars.index - $rating.rating > 0 and $smarty.section.stars.index - $rating.rating < 1}
	<img src="{url file='main^star_half.png'}" alt="+" />
	{else}
	<img src="{url file='main^star_empty.png'}" alt="_" />
	{/if}
	{/section}
{/strip}
