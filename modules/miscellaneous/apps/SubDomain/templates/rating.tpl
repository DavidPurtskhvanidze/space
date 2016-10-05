{strip}
	{section name="stars" start=1 loop=6}
	{if $rating.value >= $smarty.section.stars.index}
	<img src="{url file='main^star_full.png'}" alt="+" />
	{else}
	<img src="{url file='main^star_empty.png'}" alt="_" />
	{/if}
	{/section}
{/strip}
