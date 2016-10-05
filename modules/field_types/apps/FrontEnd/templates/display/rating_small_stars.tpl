{assign var=rating value=$listing.ListingRating}
{strip}
	{section name=stars start=1 loop=6 step=1}
	{if $smarty.section.stars.index <= round($rating, 1)}
	<img src="{url file='main^star_full_small.png'}" alt="*" />
	{elseif $smarty.section.stars.index - $rating > 0 and $smarty.section.stars.index - $rating < 1}
	<img src="{url file='main^star_half_small.png'}" alt="+" />
	{else}
	<img src="{url file='main^star_empty_small.png'}" alt="_" />
	{/if}
	{/section}
{/strip}
{assign var=ratingValue value=$rating|string_format:"%.1f"}
<span class="rating">[[rating <b>$ratingValue</b> from $count votes]]</span>
