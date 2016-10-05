{$rating=round($listing.ListingRating.rating)}
{strip}
	{section name=stars start=1 loop=6 step=1}
		{if $smarty.section.stars.index <= $rating}
			<span class="glyphicon glyphicon-star"></span>
		{elseif $smarty.section.stars.index - $rating > 0 and $smarty.section.stars.index - $rating < 1}
			{*<img src="{url file='main^star_half.png'}" alt="+"/>*}
		{else}
			<span class="glyphicon glyphicon-star-empty"></span>
		{/if}
	{/section}
{/strip}
