{if $hasError}<div class="error validation">{$error}</div>{/if}
<input type="hidden" name="{$id}" value="{$value}" class="ratingValue" />

{section name=stars start=1 loop=6}
<a href="#" onclick="rating({$smarty.section.stars.index});return false"><img src="{url file='main^star_empty.gif'}" class="star{$smarty.section.stars.index}" /></a>
{/section}

{require component="jquery" file="jquery.js"}
<script>
var currentRating = {if !empty($value)}{$value}{else}0{/if};
var fullStarImgSrc = "{url file='main^star_full.gif'}";
var emptyStarImgSrc = "{url file='main^star_empty.gif'}";
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
