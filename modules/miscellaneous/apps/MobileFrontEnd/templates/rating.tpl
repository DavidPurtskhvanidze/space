{strip}
{section name="stars" start=1 loop=6}
{if $rating >= $smarty.section.stars.index}
<img src="{url file='main^star_full.gif'}" />
{else}
<img src="{url file='main^star_empty.gif'}" />
{/if}
{/section}
{/strip}
