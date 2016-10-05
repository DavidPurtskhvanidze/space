<nobr>
{strip}
{section name=stars start=1 loop=6 step=1}
{if $smarty.section.stars.index <= round($rating, 2)}
<img src="{url file='main^star_full.gif'}" alt="" border=0 />
{elseif $smarty.section.stars.index - $rating > 0 and $smarty.section.stars.index - $rating < 1}
<img src="{url file='main^star_half.gif'}" alt="" border=0 />
{else}
<img src="{url file='main^star_empty.gif'}" alt="" border=0 />
{/if}
{/section}
{/strip}
</nobr>
{assign var=ratingValue value=$rating|string_format:"%.1f"}
[[$count votes]].
