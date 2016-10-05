{if $parameters.id_prefix}
	{$id_attribute = " id=\"{$parameters.id_prefix}_{$id}\""}
{else}
	{$id_attribute = " id=\"input_{$id}\""}
{/if}
<input type="hidden" name="{$id}" value="{$value}" class="ratingValue"{$id_attribute} />
{strip}
{section name=stars start=1 loop=6}
<a href="#" onclick="rating({$smarty.section.stars.index});return false"><img src="{url file='main^star_empty.png'}" class="star{$smarty.section.stars.index}" alt="_" /></a>
{/section}
{/strip}
{require component="jquery" file="jquery.js"}
<script type="text/javascript">
	var currentRating = {if !empty($value)}{$value}{else}0{/if};
	var fullStarImgSrc = "{url file='main^star_full.png'}";
	var emptyStarImgSrc = "{url file='main^star_empty.png'}";
	{literal}
	function rating(rate)
	{
		$(".ratingValue").val(rate);
		for (var i = 1; i <= 5; i++)
		{
			if (i <= rate) $('.star' + i).attr('src', fullStarImgSrc);
			else $('.star' + i).attr('src', emptyStarImgSrc);
		}
	}
	$(document).ready(function(){
		rating(currentRating);
	});
	{/literal}
</script>
